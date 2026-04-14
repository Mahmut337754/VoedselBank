<?php

/**
 * Voorraad model
 * Beheert voorraadmutaties voor producten.
 */
class Voorraad extends BaseModel
{
    /**
     * Haal alle producten op met voorraadinfo.
     */
    public function getAllProducten(): array
    {
        try {
            $stmt = $this->db->query('CALL sp_GetAllProducten()');
            $result = $stmt->fetchAll();
            $stmt->closeCursor();
            return $result;
        } catch (Throwable $e) {
            $stmt = $this->db->query('
                SELECT p.product_id, p.productnaam, c.naam AS categorie,
                       p.ean, p.aantal_op_voorraad
                FROM producten p
                INNER JOIN `categorieën` c ON c.categorie_id = p.categorie_id
                ORDER BY p.productnaam
            ');
            return $stmt->fetchAll();
        }
    }

    /**
     * Haal één product op via ID.
     */
    public function getProductById(int $id): array|false
    {
        try {
            $stmt = $this->db->prepare('CALL sp_GetProductById(?)');
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            $stmt->closeCursor();
            return $result;
        } catch (Throwable $e) {
            $stmt = $this->db->prepare('
                SELECT p.product_id, p.productnaam, p.categorie_id,
                       c.naam AS categorie, p.ean, p.aantal_op_voorraad
                FROM producten p
                INNER JOIN `categorieën` c ON c.categorie_id = p.categorie_id
                WHERE p.product_id = :id
            ');
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        }
    }

    /**
     * Lever producten uit: trekt het opgegeven aantal af van de voorraad.
     * Gooit een RuntimeException als de voorraad onvoldoende is.
     *
     * @param int $productId
     * @param int $aantalUitgeleverd  Positief getal = aantal af te boeken
     * @throws RuntimeException als voorraad onvoldoende is
     */
    public function uitleveren(int $productId, int $aantalUitgeleverd): void
    {
        // Haal huidige voorraad op
        $product = $this->getProductById($productId);
        if (!$product) {
            throw new RuntimeException('Product niet gevonden.');
        }

        $huidig = (int)$product['aantal_op_voorraad'];

        if ($aantalUitgeleverd > $huidig) {
            throw new RuntimeException('Er worden meer producten uitgeleverd dan er in voorraad zijn.');
        }

        try {
            $stmt = $this->db->prepare('CALL sp_UpdateVoorraad(?, ?)');
            $stmt->execute([$productId, -$aantalUitgeleverd]);
            $stmt->closeCursor();
        } catch (Throwable $e) {
            // Fallback directe SQL
            $stmt = $this->db->prepare('
                UPDATE producten
                SET aantal_op_voorraad = aantal_op_voorraad - :mutatie
                WHERE product_id = :id AND aantal_op_voorraad >= :mutatie
            ');
            $stmt->execute([
                ':mutatie' => $aantalUitgeleverd,
                ':id'      => $productId,
            ]);
            if ($stmt->rowCount() === 0) {
                throw new RuntimeException('Er worden meer producten uitgeleverd dan er in voorraad zijn.');
            }
        }
    }
}

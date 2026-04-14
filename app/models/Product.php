<?php

/**
 * Product model
 */
class Product extends BaseModel
{
    public function getAll(): array
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

    public function findById(int $id): array|false
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

    public function getAllCategorieen(): array
    {
        $stmt = $this->db->query('SELECT * FROM `categorieën` ORDER BY naam');
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        try {
            $stmt = $this->db->prepare('CALL sp_CreateProduct(?, ?, ?, ?)');
            $stmt->execute([
                $data['productnaam'],
                $data['categorie_id'],
                $data['ean'],
                $data['aantal_op_voorraad'],
            ]);
            $stmt->closeCursor();
            return (int)$this->db->lastInsertId();
        } catch (Throwable $e) {
            if (str_contains($e->getMessage(), 'EAN')) {
                throw new RuntimeException('Een product met dit EAN-nummer bestaat al.');
            }
            $stmt = $this->db->prepare('
                INSERT INTO producten (productnaam, categorie_id, ean, aantal_op_voorraad)
                VALUES (:productnaam, :categorie_id, :ean, :aantal_op_voorraad)
            ');
            $stmt->execute([
                ':productnaam'        => $data['productnaam'],
                ':categorie_id'       => $data['categorie_id'],
                ':ean'                => $data['ean'],
                ':aantal_op_voorraad' => $data['aantal_op_voorraad'],
            ]);
            return (int)$this->db->lastInsertId();
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $stmt = $this->db->prepare('CALL sp_UpdateProduct(?, ?, ?, ?, ?)');
            $stmt->execute([
                $id,
                $data['productnaam'],
                $data['categorie_id'],
                $data['ean'],
                $data['aantal_op_voorraad'],
            ]);
            $stmt->closeCursor();
            return true;
        } catch (Throwable $e) {
            if (str_contains($e->getMessage(), 'EAN')) {
                throw new RuntimeException('Een ander product met dit EAN-nummer bestaat al.');
            }
            $stmt = $this->db->prepare('
                UPDATE producten
                SET productnaam = :productnaam,
                    categorie_id = :categorie_id,
                    ean = :ean,
                    aantal_op_voorraad = :aantal_op_voorraad
                WHERE product_id = :id
            ');
            return $stmt->execute([
                ':id'                 => $id,
                ':productnaam'        => $data['productnaam'],
                ':categorie_id'       => $data['categorie_id'],
                ':ean'                => $data['ean'],
                ':aantal_op_voorraad' => $data['aantal_op_voorraad'],
            ]);
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare('CALL sp_DeleteProduct(?)');
            $stmt->execute([$id]);
            $stmt->closeCursor();
            return true;
        } catch (Throwable $e) {
            if (str_contains($e->getMessage(), 'voedselpakket')) {
                throw new RuntimeException('Product zit nog in een voedselpakket en kan niet worden verwijderd.');
            }
            $stmt = $this->db->prepare('DELETE FROM producten WHERE product_id = :id');
            return $stmt->execute([':id' => $id]);
        }
    }

    public function existsByEan(string $ean, int $excludeId = 0): bool
    {
        if ($excludeId > 0) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM producten WHERE ean = :ean AND product_id != :id');
            $stmt->execute([':ean' => $ean, ':id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM producten WHERE ean = :ean');
            $stmt->execute([':ean' => $ean]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }
}

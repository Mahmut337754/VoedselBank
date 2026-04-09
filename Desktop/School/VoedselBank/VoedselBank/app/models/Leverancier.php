<?php

/**
 * Leverancier model
 * 
 * Beheert alle database-interacties voor leveranciers.
 * Ondersteunt zowel stored procedures als fallback SQL.
 */
class Leverancier extends BaseModel
{
    /**
     * Haalt alle leveranciers op, gesorteerd op eerstvolgende levering.
     * 
     * @return array
     */
    public function getAllOrderedByNextDelivery(): array
    {
        try {
            $statement = $this->db->query('CALL sp_leveranciers_overzicht()');
            $result = $statement->fetchAll();
            $statement->closeCursor();
            return $result;
        } catch (Throwable $exception) {
            // Fallback SQL als de stored procedure niet bestaat
            $sql = '
                SELECT
                    l.leverancier_id,
                    l.bedrijfsnaam,
                    l.adres,
                    l.postcode,
                    l.plaats,
                    l.contactpersoon,
                    l.email_contact,
                    l.telefoon,
                    l.eerstvolgende_levering,
                    COUNT(le.levering_id) AS totaal_leveringen,
                    COALESCE(SUM(le.hoeveelheid), 0) AS totaal_hoeveelheid
                FROM leveranciers l
                LEFT JOIN leveringen le ON le.leverancier_id = l.leverancier_id
                GROUP BY
                    l.leverancier_id,
                    l.bedrijfsnaam,
                    l.adres,
                    l.postcode,
                    l.plaats,
                    l.contactpersoon,
                    l.email_contact,
                    l.telefoon,
                    l.eerstvolgende_levering
                ORDER BY l.eerstvolgende_levering ASC, l.bedrijfsnaam ASC
            ';
            $statement = $this->db->query($sql);
            return $statement->fetchAll();
        }
    }

    /**
     * Vind een leverancier op ID.
     * 
     * @param int $id
     * @return array|false
     */
    public function findById(int $id): array|false
    {
        $sql = 'SELECT * FROM leveranciers WHERE leverancier_id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Controleer of een bedrijfsnaam al bestaat.
     * 
     * @param string $bedrijfsnaam
     * @param int    $excludeId   ID dat overgeslagen moet worden (bij bewerken)
     * @return bool
     */
    public function existsByBedrijfsnaam(string $bedrijfsnaam, int $excludeId = 0): bool
    {
        if ($excludeId > 0) {
            $sql = 'SELECT COUNT(*) FROM leveranciers WHERE bedrijfsnaam = :naam AND leverancier_id != :exclude';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':naam' => $bedrijfsnaam, ':exclude' => $excludeId]);
        } else {
            $sql = 'SELECT COUNT(*) FROM leveranciers WHERE bedrijfsnaam = :naam';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':naam' => $bedrijfsnaam]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Maak een nieuwe leverancier aan.
     * 
     * @param array $data
     * @return int Het nieuwe leverancier ID
     */
    public function create(array $data): int
    {
        try {
            $statement = $this->db->prepare('CALL sp_leverancier_toevoegen(?, ?, ?, ?, ?, ?, ?, ?)');
            $statement->execute([
                $data['bedrijfsnaam'],
                $data['adres'],
                $data['postcode'],
                $data['plaats'],
                $data['contactpersoon'],
                $data['email_contact'],
                $data['telefoon'],
                $data['eerstvolgende_levering'],
            ]);
            $statement->closeCursor();
            return (int)$this->db->lastInsertId();
        } catch (Throwable $exception) {
            // Fallback SQL als de stored procedure niet bestaat
            $sql = '
                INSERT INTO leveranciers (
                    bedrijfsnaam,
                    adres,
                    postcode,
                    plaats,
                    contactpersoon,
                    email_contact,
                    telefoon,
                    eerstvolgende_levering
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ';
            $statement = $this->db->prepare($sql);
            $statement->execute([
                $data['bedrijfsnaam'],
                $data['adres'],
                $data['postcode'],
                $data['plaats'],
                $data['contactpersoon'],
                $data['email_contact'],
                $data['telefoon'],
                $data['eerstvolgende_levering'],
            ]);
            return (int)$this->db->lastInsertId();
        }
    }

    /**
     * Werk een bestaande leverancier bij.
     * 
     * @param int   $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $sql = '
            UPDATE leveranciers
            SET bedrijfsnaam = :bedrijfsnaam,
                adres = :adres,
                postcode = :postcode,
                plaats = :plaats,
                contactpersoon = :contactpersoon,
                email_contact = :email_contact,
                telefoon = :telefoon,
                eerstvolgende_levering = :eerstvolgende_levering
            WHERE leverancier_id = :id
        ';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'                   => $id,
            ':bedrijfsnaam'         => $data['bedrijfsnaam'],
            ':adres'                => $data['adres'],
            ':postcode'             => $data['postcode'],
            ':plaats'               => $data['plaats'],
            ':contactpersoon'       => $data['contactpersoon'],
            ':email_contact'        => $data['email_contact'],
            ':telefoon'             => $data['telefoon'],
            ':eerstvolgende_levering' => $data['eerstvolgende_levering'],
        ]);
    }

    /**
     * Verwijder een leverancier (alleen als er geen leveringen meer zijn).
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        // Eerst controleren of er nog leveringen zijn
        if ($this->hasLeveringen($id)) {
            return false;
        }
        $sql = 'DELETE FROM leveranciers WHERE leverancier_id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Controleer of een leverancier nog leveringen heeft.
     * 
     * @param int $leverancierId
     * @return bool
     */
    public function hasLeveringen(int $leverancierId): bool
    {
        $sql = 'SELECT COUNT(*) FROM leveringen WHERE leverancier_id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $leverancierId]);
        return (int)$stmt->fetchColumn() > 0;
    }
}

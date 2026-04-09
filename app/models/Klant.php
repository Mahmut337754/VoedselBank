<?php

/**
 * Klant Model
 * 
 * Verantwoordelijk voor alle database-interacties rondom klanten
 * en hun gekoppelde wensen (allergieën/dieetwensen).
 * 
 * @package App\Models
 */

require_once APP_ROOT . '/app/config/database.php';

class Klant
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection(); // PDO verbinding
    }

    /**
     * Haal alle klanten op, gesorteerd op gezinsnaam
     * 
     * @return array Lijst van klanten (associatieve arrays)
     */
    public function getAllKlanten(): array
    {
        $sql = "SELECT * FROM klanten ORDER BY gezinsnaam ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Haal één klant op via ID
     * 
     * @param int $id Klant ID
     * @return array|false Klantgegevens of false als niet gevonden
     */
    public function getKlantById(int $id): array|false
    {
        $sql = "SELECT * FROM klanten WHERE klant_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Voeg een nieuwe klant toe
     * 
     * @param array $data Alle klantvelden (gezinsnaam, telefoon, email, adres, postcode, plaats, 
     *                     aantal_volwassenen, aantal_kinderen, aantal_babys)
     * @return int|false Het nieuwe klant ID of false bij mislukken
     */
    public function createKlant(array $data): int|false
    {
        $sql = "INSERT INTO klanten 
                (gezinsnaam, telefoon, email, adres, postcode, plaats, 
                 aantal_volwassenen, aantal_kinderen, aantal_babys) 
                VALUES 
                (:gezinsnaam, :telefoon, :email, :adres, :postcode, :plaats, 
                 :aantal_volwassenen, :aantal_kinderen, :aantal_babys)";

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':gezinsnaam'          => $data['gezinsnaam'],
            ':telefoon'            => $data['telefoon'],
            ':email'               => $data['email'],
            ':adres'               => $data['adres'],
            ':postcode'            => $data['postcode'],
            ':plaats'              => $data['plaats'],
            ':aantal_volwassenen'  => $data['aantal_volwassenen'],
            ':aantal_kinderen'     => $data['aantal_kinderen'],
            ':aantal_babys'        => $data['aantal_babys']
        ]);

        return $success ? (int)$this->db->lastInsertId() : false;
    }

    /**
     * Werk bestaande klant bij
     * 
     * @param int   $id   Klant ID
     * @param array $data Nieuwe waarden (zelfde velden als create)
     * @return bool True bij succes
     */
    public function updateKlant(int $id, array $data): bool
    {
        $sql = "UPDATE klanten SET 
                    gezinsnaam = :gezinsnaam,
                    telefoon = :telefoon,
                    email = :email,
                    adres = :adres,
                    postcode = :postcode,
                    plaats = :plaats,
                    aantal_volwassenen = :aantal_volwassenen,
                    aantal_kinderen = :aantal_kinderen,
                    aantal_babys = :aantal_babys
                WHERE klant_id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'                  => $id,
            ':gezinsnaam'          => $data['gezinsnaam'],
            ':telefoon'            => $data['telefoon'],
            ':email'               => $data['email'],
            ':adres'               => $data['adres'],
            ':postcode'            => $data['postcode'],
            ':plaats'              => $data['plaats'],
            ':aantal_volwassenen'  => $data['aantal_volwassenen'],
            ':aantal_kinderen'     => $data['aantal_kinderen'],
            ':aantal_babys'        => $data['aantal_babys']
        ]);
    }

    /**
     * Verwijder een klant (alleen als er geen voedselpakketten aan gekoppeld zijn)
     * 
     * @param int $id Klant ID
     * @return bool True als verwijderd, false bij fout of restrictie
     */
    public function deleteKlant(int $id): bool
    {
        // Optioneel: eerst controleren of er pakketten bestaan
        $checkSql = "SELECT COUNT(*) FROM voedselpakketten WHERE klant_id = :id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([':id' => $id]);
        if ($checkStmt->fetchColumn() > 0) {
            return false; // Klant heeft nog pakketten, niet verwijderen
        }

        $sql = "DELETE FROM klanten WHERE klant_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ------------------------------
    // Wensen (Allergieën/Dieetwensen)
    // ------------------------------

    /**
     * Haal alle beschikbare wensen op (uit de tabel 'wensen')
     * 
     * @return array Lijst van wensen (wens_id, naam, omschrijving)
     */
    public function getAllWensen(): array
    {
        $sql = "SELECT * FROM wensen ORDER BY naam";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Haal de IDs van wensen die aan een specifieke klant zijn gekoppeld
     * 
     * @param int $klantId Klant ID
     * @return array Lijst met wens_id's
     */
    public function getKlantWensenIds(int $klantId): array
    {
        $sql = "SELECT wens_id FROM klantwensen WHERE klant_id = :klant_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':klant_id' => $klantId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Koppel een lijst van wensen aan een klant (vervangt bestaande koppelingen)
     * 
     * @param int   $klantId    Klant ID
     * @param array $wensenIds  Array met wens_id's (lege array = alle koppelingen verwijderen)
     * @return bool True bij succes
     */
    public function syncWensen(int $klantId, array $wensenIds): bool
    {
        // Begin transactie
        $this->db->beginTransaction();
        try {
            // 1. Bestaande koppelingen verwijderen
            $deleteSql = "DELETE FROM klantwensen WHERE klant_id = :klant_id";
            $deleteStmt = $this->db->prepare($deleteSql);
            $deleteStmt->execute([':klant_id' => $klantId]);

            // 2. Nieuwe koppelingen toevoegen (indien aanwezig)
            if (!empty($wensenIds)) {
                $insertSql = "INSERT INTO klantwensen (klant_id, wens_id) VALUES (:klant_id, :wens_id)";
                $insertStmt = $this->db->prepare($insertSql);
                foreach ($wensenIds as $wensId) {
                    $insertStmt->execute([
                        ':klant_id' => $klantId,
                        ':wens_id'  => $wensId
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

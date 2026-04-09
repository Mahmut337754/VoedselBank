<?php

/**
 * Allergie model – beheert de 'wensen' tabel (allergieën/dieetwensen)
 *
 * Voldoet aan PSR-12 codeconventie.
 *
 * @author  VoedselBank Maaskantje
 * @version 1.0
 */
class Allergie extends BaseModel
{
    /**
     * getAll – Haal alle allergieën op zonder extra info.
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->db->query(
            'SELECT * FROM wensen ORDER BY wens_id'
        )->fetchAll();
    }

    /**
     * getAllWithKlantCount – Haal alle allergieën op met het aantal gekoppelde klanten.
     *
     * Gebruikt een LEFT JOIN op klantwensen zodat allergieën zonder
     * gekoppelde klanten ook worden getoond (klant_count = 0).
     *
     * @return array
     */
    public function getAllWithKlantCount(): array
    {
        return $this->db->query(
            'SELECT w.wens_id,
                    w.naam,
                    w.omschrijving,
                    COUNT(kw.klant_id) AS klant_count
             FROM wensen w
             LEFT JOIN klantwensen kw ON kw.wens_id = w.wens_id
             GROUP BY w.wens_id, w.naam, w.omschrijving
             ORDER BY w.wens_id'
        )->fetchAll();
    }

    /**
     * findById – Zoek één allergie op ID.
     *
     * @param  int         $id
     * @return array|false
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM wensen WHERE wens_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * getKlantenByAllergieId – Haal alle klanten op die een bepaalde allergie hebben.
     *
     * Gebruikt een INNER JOIN tussen klantwensen en klanten om de
     * klantgegevens te koppelen aan de allergie.
     *
     * @param  int   $allergieId  Het ID van de allergie
     * @return array              Lijst van gekoppelde klanten
     */
    public function getKlantenByAllergieId(int $allergieId): array
    {
        $stmt = $this->db->prepare(
            'SELECT k.klant_id,
                    k.voornaam,
                    k.achternaam
             FROM klanten k
             INNER JOIN klantwensen kw ON kw.klant_id = k.klant_id
             WHERE kw.wens_id = ?
             ORDER BY k.achternaam, k.voornaam'
        );
        $stmt->execute([$allergieId]);
        return $stmt->fetchAll();
    }

    /**
     * nameExists – Controleer of een naam al bestaat (optioneel exclusief een ID).
     *
     * @param  string $naam
     * @param  int    $excludeId  Sla dit ID over bij de controle (voor updates)
     * @return bool
     */
    public function nameExists(string $naam, int $excludeId = 0): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM wensen WHERE naam = ? AND wens_id != ?'
        );
        $stmt->execute([$naam, $excludeId]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * create – Maak een nieuwe allergie aan.
     *
     * @param  string $naam
     * @param  string $omschrijving
     * @return bool
     */
    public function create(string $naam, string $omschrijving): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO wensen (naam, omschrijving) VALUES (?, ?)'
        );
        return $stmt->execute([$naam, $omschrijving ?: null]);
    }

    /**
     * update – Werk een bestaande allergie bij.
     *
     * @param  int    $id
     * @param  string $naam
     * @param  string $omschrijving
     * @return bool
     */
    public function update(int $id, string $naam, string $omschrijving): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE wensen SET naam = ?, omschrijving = ? WHERE wens_id = ?'
        );
        return $stmt->execute([$naam, $omschrijving ?: null, $id]);
    }

    /**
     * delete – Verwijder een allergie als deze niet meer in gebruik is.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        // Controleer of de allergie nog gekoppeld is aan klanten
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM klantwensen WHERE wens_id = ?'
        );
        $stmt->execute([$id]);

        if ((int) $stmt->fetchColumn() > 0) {
            // Nog in gebruik, niet verwijderen
            return false;
        }

        $stmt = $this->db->prepare('DELETE FROM wensen WHERE wens_id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * isInUse – Controleer of een allergie nog gekoppeld is aan klanten.
     *
     * @param  int  $id
     * @return bool
     */
    public function isInUse(int $id): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM klantwensen WHERE wens_id = ?'
        );
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}

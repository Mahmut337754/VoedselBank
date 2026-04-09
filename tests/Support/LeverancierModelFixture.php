<?php

use PHPUnit\Framework\TestCase;

class TestLeverancierModel extends Leverancier
{
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
}

abstract class LeverancierModelBaseTest extends TestCase
{
    private PDO $db;
    protected TestLeverancierModel $model;

    protected function setUp(): void
    {
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $this->createSchema($this->db);
        $this->seedLeveranciers($this->db);

        $this->model = new TestLeverancierModel($this->db);
    }

    private function createSchema(PDO $db): void
    {
        $db->exec(
            'CREATE TABLE leveranciers (
                leverancier_id INTEGER PRIMARY KEY AUTOINCREMENT,
                bedrijfsnaam TEXT NOT NULL,
                adres TEXT NOT NULL,
                postcode TEXT NOT NULL,
                plaats TEXT NOT NULL,
                contactpersoon TEXT NOT NULL,
                email_contact TEXT NOT NULL UNIQUE,
                telefoon TEXT NOT NULL,
                eerstvolgende_levering TEXT NOT NULL
            )'
        );

        $db->exec(
            'CREATE TABLE leveringen (
                levering_id INTEGER PRIMARY KEY AUTOINCREMENT,
                leverancier_id INTEGER NOT NULL,
                product_id INTEGER,
                hoeveelheid INTEGER,
                leverdatum TEXT,
                FOREIGN KEY (leverancier_id) REFERENCES leveranciers(leverancier_id)
            )'
        );
    }

    private function seedLeveranciers(PDO $db): void
    {
        $stmt = $db->prepare(
            'INSERT INTO leveranciers (
                bedrijfsnaam,
                adres,
                postcode,
                plaats,
                contactpersoon,
                email_contact,
                telefoon,
                eerstvolgende_levering
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute(['Alpha BV', 'Alphaweg 1', '1111AA', 'Utrecht', 'A. Alpha', 'alpha@example.com', '030-1234567', '2026-04-20 09:00:00']);
        $stmt->execute(['Beta BV', 'Betastraat 2', '2222BB', 'Amsterdam', 'B. Beta', 'beta@example.com', '020-1234567', '2026-04-22 09:00:00']);
        $stmt->execute(['Gamma BV', 'Gammalaan 3', '3333CC', 'Rotterdam', 'G. Gamma', 'gamma@example.com', '010-1234567', '2026-04-18 09:00:00']);

        $db->exec("INSERT INTO leveringen (leverancier_id, product_id, hoeveelheid, leverdatum) VALUES (1, 1, 10, '2026-04-10')");
        $db->exec("INSERT INTO leveringen (leverancier_id, product_id, hoeveelheid, leverdatum) VALUES (1, 2, 5, '2026-04-11')");
        $db->exec("INSERT INTO leveringen (leverancier_id, product_id, hoeveelheid, leverdatum) VALUES (2, 1, 12, '2026-04-12')");
    }
}

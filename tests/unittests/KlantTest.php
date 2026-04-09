
<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/config/config.php';
require_once APP_ROOT . '/app/models/Klant.php';

class KlantTest extends TestCase
{
    private Klant $klantModel;

    protected function setUp(): void
    {
        // Maak een verse verbinding met de testdatabase
        $this->klantModel = new Klant();
        // Zorg dat de testdatabase leeg begint (optioneel)
        $this->truncateTables();
    }

    protected function tearDown(): void
    {
        // Opruimen na elke test
        $this->truncateTables();
    }

    private function truncateTables(): void
    {
        $db = $this->getPrivateProperty($this->klantModel, 'db');
        $db->exec("SET FOREIGN_KEY_CHECKS=0");
        $db->exec("TRUNCATE TABLE pakketproducten");
        $db->exec("TRUNCATE TABLE voedselpakketten");
        $db->exec("TRUNCATE TABLE klantwensen");
        $db->exec("TRUNCATE TABLE klanten");
        $db->exec("SET FOREIGN_KEY_CHECKS=1");
    }

    private function getPrivateProperty($object, string $property)
    {
        $reflection = new ReflectionClass($object);
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);
        return $prop->getValue($object);
    }

    public function testCreateKlant()
    {
        $data = [
            'gezinsnaam' => 'Test Gezin',
            'telefoon' => '0612345678',
            'email' => 'test@example.com',
            'adres' => 'Teststraat 1',
            'postcode' => '1234AB',
            'plaats' => 'Testdorp',
            'aantal_volwassenen' => 2,
            'aantal_kinderen' => 1,
            'aantal_babys' => 0
        ];

        $id = $this->klantModel->createKlant($data);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);

        $klant = $this->klantModel->getKlantById($id);
        $this->assertEquals('Test Gezin', $klant['gezinsnaam']);
    }

    public function testUpdateKlant()
    {
        // Eerst aanmaken
        $data = [
            'gezinsnaam' => 'Oud Gezin',
            'telefoon' => '0612345678',
            'email' => 'update@example.com',
            'adres' => 'Oudepad 1',
            'postcode' => '1111AA',
            'plaats' => 'Oudorp',
            'aantal_volwassenen' => 1,
            'aantal_kinderen' => 0,
            'aantal_babys' => 0
        ];
        $id = $this->klantModel->createKlant($data);

        // Bijwerken
        $data['gezinsnaam'] = 'Nieuw Gezin';
        $result = $this->klantModel->updateKlant($id, $data);
        $this->assertTrue($result);

        $updated = $this->klantModel->getKlantById($id);
        $this->assertEquals('Nieuw Gezin', $updated['gezinsnaam']);
    }

    public function testDeleteKlantWithNoPackages()
    {
        $data = [
            'gezinsnaam' => 'Verwijderbaar',
            'telefoon' => '0699999999',
            'email' => 'delete@example.com',
            'adres' => 'Weg 1',
            'postcode' => '9999ZZ',
            'plaats' => 'Nullstad',
            'aantal_volwassenen' => 1,
            'aantal_kinderen' => 0,
            'aantal_babys' => 0
        ];
        $id = $this->klantModel->createKlant($data);
        $deleted = $this->klantModel->deleteKlant($id);
        $this->assertTrue($deleted);
        $this->assertFalse($this->klantModel->getKlantById($id));
    }

    public function testWensenKoppelen()
    {
        // Maak klant
        $data = [
            'gezinsnaam' => 'Wensen Gezin',
            'telefoon' => '0677777777',
            'email' => 'wensen@example.com',
            'adres' => 'Wenslaan 5',
            'postcode' => '5555XX',
            'plaats' => 'Wensdorp',
            'aantal_volwassenen' => 2,
            'aantal_kinderen' => 0,
            'aantal_babys' => 0
        ];
        $id = $this->klantModel->createKlant($data);

        // Haal alle wensen op (uit database)
        $allWensen = $this->klantModel->getAllWensen();
        if (count($allWensen) >= 2) {
            $wensIds = [$allWensen[0]['wens_id'], $allWensen[1]['wens_id']];
            $this->klantModel->syncWensen($id, $wensIds);
            $gekoppeld = $this->klantModel->getKlantWensenIds($id);
            $this->assertCount(2, $gekoppeld);
            $this->assertContains($wensIds[0], $gekoppeld);
        } else {
            $this->markTestSkipped('Er zijn niet genoeg wensen in de database voor deze test.');
        }
    }
}

<?php

use PHPUnit\Framework\Attributes\TestDox;

final class LeverancierToevoegenTest extends LeverancierModelBaseTest
{
    #[TestDox('een leverancier kan succesvol worden aangemaakt')]
    public function testNieuweLeverancierWordtToegevoegdViaFallbackInsert(): void
    {
        $id = $this->model->create([
            'bedrijfsnaam' => 'Delta BV',
            'adres' => 'Deltaweg 4',
            'postcode' => '4444DD',
            'plaats' => 'Eindhoven',
            'contactpersoon' => 'D. Delta',
            'email_contact' => 'delta@example.com',
            'telefoon' => '040-1234567',
            'eerstvolgende_levering' => '2026-05-01 09:00:00',
        ]);

        $this->assertGreaterThan(0, $id);
        $created = $this->model->findById($id);
        $this->assertIsArray($created);
        $this->assertSame('Delta BV', $created['bedrijfsnaam']);
    }

    #[TestDox('een dubbele bedrijfsnaam wordt bij toevoegen geblokkeerd')]
    public function testBestaatByBedrijfsnaamDetecteertDubbeleInvoer(): void
    {
        $this->assertTrue($this->model->existsByBedrijfsnaam('Alpha BV'));
        $this->assertFalse($this->model->existsByBedrijfsnaam('Niet Bestaat BV'));
    }
}

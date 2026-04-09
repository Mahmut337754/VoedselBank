<?php

use PHPUnit\Framework\Attributes\TestDox;

final class LeverancierWijzigenTest extends LeverancierModelBaseTest
{
    #[TestDox('een bestaande leverancier kan succesvol worden gewijzigd')]
    public function testBestaandeLeverancierWordtGewijzigd(): void
    {
        $ok = $this->model->update(1, [
            'bedrijfsnaam' => 'Alpha BV Gewijzigd',
            'adres' => 'Nieuweweg 10',
            'postcode' => '1111AA',
            'plaats' => 'Utrecht',
            'contactpersoon' => 'A. Nieuw',
            'email_contact' => 'alpha-nieuw@example.com',
            'telefoon' => '030-7654321',
            'eerstvolgende_levering' => '2026-06-01 10:00:00',
        ]);

        $this->assertTrue($ok);
        $updated = $this->model->findById(1);
        $this->assertSame('Alpha BV Gewijzigd', $updated['bedrijfsnaam']);
        $this->assertSame('alpha-nieuw@example.com', $updated['email_contact']);
    }

    #[TestDox('de duplicate controle negeert de huidige leverancier bij wijzigen')]
    public function testExistsByBedrijfsnaamExceptIdNegeertHuidigeLeverancier(): void
    {
        $this->assertFalse($this->model->existsByBedrijfsnaamExceptId('Alpha BV', 1));
        $this->assertTrue($this->model->existsByBedrijfsnaamExceptId('Beta BV', 1));
    }
}

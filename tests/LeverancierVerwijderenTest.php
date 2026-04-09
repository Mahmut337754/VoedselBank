<?php

use PHPUnit\Framework\Attributes\TestDox;

final class LeverancierVerwijderenTest extends LeverancierModelBaseTest
{
    #[TestDox('een bestaande leverancier kan succesvol worden verwijderd')]
    public function testBestaandeLeverancierWordtVerwijderd(): void
    {
        $deleted = $this->model->delete(2);

        $this->assertTrue($deleted);
        $this->assertFalse($this->model->findById(2));
    }
}

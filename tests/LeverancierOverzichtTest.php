<?php

use PHPUnit\Framework\Attributes\TestDox;

final class LeverancierOverzichtTest extends LeverancierModelBaseTest
{
    #[TestDox('een leveranciersoverzicht wordt gesorteerd op eerstvolgende levering')]
    public function testOverzichtIsGesorteerdOpEerstvolgendeLevering(): void
    {
        $rows = $this->model->getAllOrderedByNextDelivery();

        $this->assertCount(3, $rows);
        $this->assertSame('Gamma BV', $rows[0]['bedrijfsnaam']);
        $this->assertSame('Alpha BV', $rows[1]['bedrijfsnaam']);
        $this->assertSame('Beta BV', $rows[2]['bedrijfsnaam']);
    }
}

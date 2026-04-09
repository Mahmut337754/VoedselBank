<?php
/**
 * AllergieTest – Unit tests voor het Allergie model
 * Examenopdracht Dag 2 – Voedselbank Maaskantje
 *
 * Voert tests uit op de logica van het Allergie model
 * met behulp van een gemockte PDO-verbinding (geen echte database nodig).
 */

use PHPUnit\Framework\TestCase;

class AllergieTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private Allergie $allergie;

    protected function setUp(): void
    {
        // Definieer APP_ROOT als dat nog niet gedaan is
        if (!defined('APP_ROOT')) {
            define('APP_ROOT', dirname(__DIR__, 2));
        }

        // Laad config zodat DB_HOST e.d. beschikbaar zijn
        require_once APP_ROOT . '/app/config/config.php';

        // Laad benodigde klassen
        require_once APP_ROOT . '/app/models/BaseModel.php';
        require_once APP_ROOT . '/app/models/Allergie.php';

        // Maak PDO en Statement mocks aan
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->pdoMock  = $this->createMock(PDO::class);

        // Maak Allergie instantie aan zonder echte DB via ReflectionClass
        // We omzeilen de BaseModel constructor door direct de property te zetten
        $this->allergie = (new ReflectionClass(Allergie::class))->newInstanceWithoutConstructor();
        $reflection = new ReflectionClass($this->allergie);
        $dbProp = $reflection->getProperty('db');
        $dbProp->setValue($this->allergie, $this->pdoMock);
    }

    /**
     * Test: nameExists() geeft true terug als naam al bestaat
     */
    public function testNameExistsReturnsTrueWhenNameFound(): void
    {
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetchColumn')->willReturn(1); // 1 record gevonden

        $this->pdoMock
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $result = $this->allergie->nameExists('Gluten');

        $this->assertTrue($result, 'nameExists() moet true teruggeven als de naam al bestaat.');
    }

    /**
     * Test: nameExists() geeft false terug als naam niet bestaat
     */
    public function testNameExistsReturnsFalseWhenNameNotFound(): void
    {
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetchColumn')->willReturn(0); // geen records

        $this->pdoMock
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $result = $this->allergie->nameExists('NietBestaandeAllergie');

        $this->assertFalse($result, 'nameExists() moet false teruggeven als de naam niet bestaat.');
    }

    /**
     * Test: create() geeft true terug bij succesvolle insert
     */
    public function testCreateReturnsTrue(): void
    {
        $this->stmtMock->method('execute')->willReturn(true);

        $this->pdoMock
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $result = $this->allergie->create('Lactose', 'Lactose-intolerantie');

        $this->assertTrue($result, 'create() moet true teruggeven bij een succesvolle insert.');
    }

    /**
     * Test: delete() geeft false terug als allergie nog in gebruik is bij klanten
     */
    public function testDeleteReturnsFalseWhenInUse(): void
    {
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetchColumn')->willReturn(2); // 2 klanten gebruiken deze allergie

        $this->pdoMock
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $result = $this->allergie->delete(1);

        $this->assertFalse($result, 'delete() moet false teruggeven als de allergie nog in gebruik is.');
    }

    /**
     * Test: isInUse() geeft true terug als allergie gekoppeld is aan klanten
     */
    public function testIsInUseReturnsTrueWhenLinkedToClients(): void
    {
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetchColumn')->willReturn(3);

        $this->pdoMock
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $result = $this->allergie->isInUse(5);

        $this->assertTrue($result, 'isInUse() moet true teruggeven als de allergie gekoppeld is aan klanten.');
    }
}

<?php

/**
 * KlantController
 * 
 * Verwerkt alle HTTP requests voor klantenbeheer.
 * Bevat volledige CRUD + validatie + foutafhandeling + logging.
 */

require_once APP_ROOT . '/app/models/Klant.php';

class KlantController
{
    private Klant $klantModel;

    public function __construct()
    {
        $this->klantModel = new Klant();
    }

    /**
     * Toon overzicht van alle klanten (READ)
     */
    public function index(): void
    {
        $klanten = $this->klantModel->getAllKlanten();
        $pageTitle = 'Klanten overzicht';

        // Log: bekijken overzicht
        $this->log("Klantenoverzicht bekeken door " . ($_SESSION['username'] ?? 'onbekend'), "INFO");

        require_once APP_ROOT . '/app/views/klanten/index.php';
    }

    /**
     * Toon formulier voor nieuwe klant (CREATE - formulier)
     */
    public function create(): void
    {
        $alleWensen = $this->klantModel->getAllWensen();
        $gekozenWensen = [];
        $pageTitle = 'Nieuwe klant';

        // Log: formulier geladen
        $this->log("Nieuw klantformulier geladen", "INFO");

        require_once APP_ROOT . '/app/views/klanten/create.php';
    }

    /**
     * Opslaan van nieuwe klant (CREATE - opslaan)
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/klanten');
            return;
        }

        $errors = $this->validateKlantData($_POST);
        if (!empty($errors)) {
            // Log: validatiefouten
            $this->log("Validatiefouten bij aanmaken klant: " . implode(', ', $errors), "WARNING");
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('/klanten/create');
            return;
        }

        $klantData = [
            'gezinsnaam'         => $_POST['gezinsnaam'],
            'telefoon'           => $_POST['telefoon'],
            'email'              => $_POST['email'],
            'adres'              => $_POST['adres'],
            'postcode'           => $_POST['postcode'],
            'plaats'             => $_POST['plaats'],
            'aantal_volwassenen' => (int)$_POST['aantal_volwassenen'],
            'aantal_kinderen'    => (int)$_POST['aantal_kinderen'],
            'aantal_babys'       => (int)$_POST['aantal_babys']
        ];

        try {
            $klantId = $this->klantModel->createKlant($klantData);
            // Log: succesvol aangemaakt
            $this->log("Nieuwe klant aangemaakt: ID $klantId, naam {$klantData['gezinsnaam']}, email {$klantData['email']}", "INFO");
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errors['email'] = 'Een klant met dit e-mailadres bestaat al.';
                // Log: duplicate email
                $this->log("Duplicate email bij aanmaken: {$klantData['email']}", "ERROR");
            } else {
                $errors['general'] = 'Database fout: ' . $e->getMessage();
                $this->log("Database fout bij aanmaken klant: " . $e->getMessage(), "ERROR");
            }
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('/klanten/create');
            return;
        }

        if ($klantId === false) {
            $_SESSION['errors'] = ['general' => 'Klant kon niet worden opgeslagen.'];
            $this->log("Onbekende fout bij aanmaken klant (createKlant gaf false)", "ERROR");
            $_SESSION['old_input'] = $_POST;
            $this->redirect('/klanten/create');
            return;
        }

        $wensenIds = $_POST['wensen'] ?? [];
        $this->klantModel->syncWensen($klantId, $wensenIds);
        $this->log("Wensen gekoppeld aan klant ID $klantId: " . implode(',', $wensenIds), "DEBUG");

        $_SESSION['success'] = "Klant '{$_POST['gezinsnaam']}' is toegevoegd.";
        $this->redirect('/klanten');
    }

    /**
     * Toon detail van één klant (READ - detail)
     */
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/klanten');
            return;
        }

        $klant = $this->klantModel->getKlantById($id);
        if (!$klant) {
            $_SESSION['errors'] = ['general' => 'Klant niet gevonden.'];
            $this->log("Poging tot bekijken van niet-bestaande klant ID $id", "WARNING");
            $this->redirect('/klanten');
            return;
        }

        $wensIds = $this->klantModel->getKlantWensenIds($id);
        $alleWensen = $this->klantModel->getAllWensen();
        $gekoppeldeWensen = array_filter($alleWensen, function ($w) use ($wensIds) {
            return in_array($w['wens_id'], $wensIds);
        });

        $pageTitle = "Klant: {$klant['gezinsnaam']}";

        // Log: detail bekeken
        $this->log("Klantdetails bekeken: ID $id, naam {$klant['gezinsnaam']}", "INFO");

        require_once APP_ROOT . '/app/views/klanten/show.php';
    }

    /**
     * Toon bewerkingsformulier (UPDATE - formulier)
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/klanten');
            return;
        }

        $klant = $this->klantModel->getKlantById($id);
        if (!$klant) {
            $_SESSION['errors'] = ['general' => 'Klant niet gevonden.'];
            $this->log("Poging tot bewerken van niet-bestaande klant ID $id", "WARNING");
            $this->redirect('/klanten');
            return;
        }

        $alleWensen = $this->klantModel->getAllWensen();
        $gekozenWensen = $this->klantModel->getKlantWensenIds($id);
        $pageTitle = "Klant bewerken: {$klant['gezinsnaam']}";

        // Log: bewerkformulier geladen
        $this->log("Bewerkformulier geladen voor klant ID $id", "INFO");

        require_once APP_ROOT . '/app/views/klanten/edit.php';
    }

    /**
     * Bijwerken van bestaande klant (UPDATE - opslaan)
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/klanten');
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/klanten');
            return;
        }

        $errors = $this->validateKlantData($_POST);
        if (!empty($errors)) {
            $this->log("Validatiefouten bij bijwerken klant ID $id: " . implode(', ', $errors), "WARNING");
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect("/klanten/edit?id={$id}");
            return;
        }

        $klantData = [
            'gezinsnaam'         => $_POST['gezinsnaam'],
            'telefoon'           => $_POST['telefoon'],
            'email'              => $_POST['email'],
            'adres'              => $_POST['adres'],
            'postcode'           => $_POST['postcode'],
            'plaats'             => $_POST['plaats'],
            'aantal_volwassenen' => (int)$_POST['aantal_volwassenen'],
            'aantal_kinderen'    => (int)$_POST['aantal_kinderen'],
            'aantal_babys'       => (int)$_POST['aantal_babys']
        ];

        try {
            $success = $this->klantModel->updateKlant($id, $klantData);
            if ($success) {
                $this->log("Klant bijgewerkt: ID $id, nieuwe naam {$klantData['gezinsnaam']}", "INFO");
            } else {
                $this->log("Update mislukt (returned false) voor klant ID $id", "ERROR");
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errors['email'] = 'Een klant met dit e-mailadres bestaat al.';
                $this->log("Duplicate email bij bijwerken klant ID $id: {$klantData['email']}", "ERROR");
            } else {
                $errors['general'] = 'Database fout: ' . $e->getMessage();
                $this->log("Database fout bij bijwerken klant ID $id: " . $e->getMessage(), "ERROR");
            }
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect("/klanten/edit?id={$id}");
            return;
        }

        if (!$success) {
            $_SESSION['errors'] = ['general' => 'Klant kon niet worden bijgewerkt.'];
            $_SESSION['old_input'] = $_POST;
            $this->redirect("/klanten/edit?id={$id}");
            return;
        }

        $wensenIds = $_POST['wensen'] ?? [];
        $this->klantModel->syncWensen($id, $wensenIds);
        $this->log("Wensen gesynchroniseerd voor klant ID $id: " . implode(',', $wensenIds), "DEBUG");

        $_SESSION['success'] = "Klant '{$_POST['gezinsnaam']}' is bijgewerkt.";
        $this->redirect('/klanten');
    }

    /**
     * Verwijder een klant (DELETE)
     */
    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/klanten');
            return;
        }

        $klant = $this->klantModel->getKlantById($id);
        if (!$klant) {
            $_SESSION['errors'] = ['general' => 'Klant bestaat niet.'];
            $this->log("Poging tot verwijderen van niet-bestaande klant ID $id", "WARNING");
            $this->redirect('/klanten');
            return;
        }

        $deleted = $this->klantModel->deleteKlant($id);
        if (!$deleted) {
            $_SESSION['errors'] = ['general' => 'Klant kan niet worden verwijderd zolang er actieve voedselpakketten zijn.'];
            $this->log("Verwijderen klant ID $id mislukt: actieve voedselpakketten", "WARNING");
        } else {
            $_SESSION['success'] = "Klant '{$klant['gezinsnaam']}' is verwijderd.";
            $this->log("Klant verwijderd: ID $id, naam {$klant['gezinsnaam']}", "INFO");
        }

        $this->redirect('/klanten');
    }

    /**
     * Valideer de ingevoerde klantgegevens (per veld)
     */
    private function validateKlantData(array $data): array
    {
        $errors = [];

        if (empty(trim($data['gezinsnaam'] ?? ''))) {
            $errors['gezinsnaam'] = 'Gezinsnaam is verplicht.';
        }
        if (empty(trim($data['telefoon'] ?? ''))) {
            $errors['telefoon'] = 'Telefoonnummer is verplicht.';
        } elseif (!preg_match('/^[0-9\s\+\-\(\)]{8,20}$/', $data['telefoon'])) {
            $errors['telefoon'] = 'Voer een geldig telefoonnummer in (minimaal 8 cijfers).';
        }
        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'E-mailadres is verplicht.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Voer een geldig e-mailadres in.';
        }
        if (empty(trim($data['adres'] ?? ''))) {
            $errors['adres'] = 'Adres is verplicht.';
        }
        if (empty(trim($data['postcode'] ?? ''))) {
            $errors['postcode'] = 'Postcode is verplicht.';
        } elseif (!preg_match('/^[1-9][0-9]{3} ?[A-Z]{2}$/i', $data['postcode'])) {
            $errors['postcode'] = 'Voer een geldige Nederlandse postcode in (bijv. 1234AB).';
        }
        if (empty(trim($data['plaats'] ?? ''))) {
            $errors['plaats'] = 'Plaats is verplicht.';
        }
        if ((int)($data['aantal_volwassenen'] ?? 0) < 1) {
            $errors['aantal_volwassenen'] = 'Er moet minimaal één volwassene in het gezin zijn.';
        }
        if (isset($data['aantal_kinderen']) && (int)$data['aantal_kinderen'] < 0) {
            $errors['aantal_kinderen'] = 'Aantal kinderen mag niet negatief zijn.';
        }
        if (isset($data['aantal_babys']) && (int)$data['aantal_babys'] < 0) {
            $errors['aantal_babys'] = 'Aantal baby\'s mag niet negatief zijn.';
        }

        return $errors;
    }

    /**
     * Eenvoudige logging naar logs/technical.log
     * 
     * @param string $message Het logbericht
     * @param string $level   DEBUG, INFO, WARNING, ERROR
     */
    private function log(string $message, string $level = 'INFO'): void
    {
        $logFile = APP_ROOT . '/logs/technical.log';
        $timestamp = date('Y-m-d H:i:s');
        $entry = "[$timestamp] [$level] $message" . PHP_EOL;
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    }

    private function redirect(string $url): void
    {
        header("Location: " . BASE_URL . $url);
        exit;
    }
}

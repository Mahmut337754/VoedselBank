<?php

/**
 * KlantController
 * 
 * Verwerkt alle HTTP requests voor klantenbeheer.
 * Bevat volledige CRUD + validatie + foutafhandeling.
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
     * Toon overzicht van alle klanten
     */
    public function index(): void
    {
        $klanten = $this->klantModel->getAllKlanten();
        $pageTitle = 'Klanten overzicht';
        require_once APP_ROOT . '/app/views/klanten/index.php';
    }

    /**
     * Toon formulier voor nieuwe klant
     */
    public function create(): void
    {
        $alleWensen = $this->klantModel->getAllWensen();
        $gekozenWensen = [];
        $pageTitle = 'Nieuwe klant';
        require_once APP_ROOT . '/app/views/klanten/create.php';
    }

    /**
     * Opslaan van nieuwe klant met validatie en duplicate check
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/klanten');
            return;
        }

        // Validatie uitvoeren
        $errors = $this->validateKlantData($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('/klanten/create');
            return;
        }

        // Data voorbereiden
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
        } catch (PDOException $e) {
            // 1062 = duplicate entry (unieke email)
            if ($e->errorInfo[1] == 1062) {
                $errors['email'] = 'Een klant met dit e-mailadres bestaat al.';
            } else {
                $errors['general'] = 'Database fout: ' . $e->getMessage();
            }
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('/klanten/create');
            return;
        }

        if ($klantId === false) {
            $_SESSION['errors'] = ['general' => 'Klant kon niet worden opgeslagen.'];
            $_SESSION['old_input'] = $_POST;
            $this->redirect('/klanten/create');
            return;
        }

        // Wensen koppelen
        $wensenIds = $_POST['wensen'] ?? [];
        $this->klantModel->syncWensen($klantId, $wensenIds);

        $_SESSION['success'] = "Klant '{$_POST['gezinsnaam']}' is toegevoegd.";
        $this->redirect('/klanten');
    }

    /**
     * Toon detail van één klant
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
            $this->redirect('/klanten');
            return;
        }

        $wensIds = $this->klantModel->getKlantWensenIds($id);
        $alleWensen = $this->klantModel->getAllWensen();
        $gekoppeldeWensen = array_filter($alleWensen, function ($w) use ($wensIds) {
            return in_array($w['wens_id'], $wensIds);
        });

        $pageTitle = "Klant: {$klant['gezinsnaam']}";
        require_once APP_ROOT . '/app/views/klanten/show.php';
    }

    /**
     * Toon bewerkingsformulier
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
            $this->redirect('/klanten');
            return;
        }

        $alleWensen = $this->klantModel->getAllWensen();
        $gekozenWensen = $this->klantModel->getKlantWensenIds($id);

        $pageTitle = "Klant bewerken: {$klant['gezinsnaam']}";
        require_once APP_ROOT . '/app/views/klanten/edit.php';
    }

    /**
     * Bijwerken van bestaande klant met validatie en duplicate check
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

        // Validatie uitvoeren
        $errors = $this->validateKlantData($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect("/klanten/edit?id={$id}");
            return;
        }

        // Data voorbereiden
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
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errors['email'] = 'Een klant met dit e-mailadres bestaat al.';
            } else {
                $errors['general'] = 'Database fout: ' . $e->getMessage();
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

        // Wensen synchroniseren
        $wensenIds = $_POST['wensen'] ?? [];
        $this->klantModel->syncWensen($id, $wensenIds);

        $_SESSION['success'] = "Klant '{$_POST['gezinsnaam']}' is bijgewerkt.";
        $this->redirect('/klanten');
    }

    /**
     * Verwijder een klant (alleen als er geen openstaande pakketten zijn)
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
            $this->redirect('/klanten');
            return;
        }

        $deleted = $this->klantModel->deleteKlant($id);
        if (!$deleted) {
            $_SESSION['errors'] = ['general' => 'Klant kan niet worden verwijderd zolang er actieve voedselpakketten zijn.'];
        } else {
            $_SESSION['success'] = "Klant '{$klant['gezinsnaam']}' is verwijderd.";
        }

        $this->redirect('/klanten');
    }

    /**
     * Valideer de ingevoerde klantgegevens (per veld)
     * 
     * @param array $data POST data
     * @return array Lijst met foutmeldingen per veld
     */
    private function validateKlantData(array $data): array
    {
        $errors = [];

        // Gezinsnaam
        if (empty(trim($data['gezinsnaam'] ?? ''))) {
            $errors['gezinsnaam'] = 'Gezinsnaam is verplicht.';
        }

        // Telefoon
        if (empty(trim($data['telefoon'] ?? ''))) {
            $errors['telefoon'] = 'Telefoonnummer is verplicht.';
        } elseif (!preg_match('/^[0-9\s\+\-\(\)]{8,20}$/', $data['telefoon'])) {
            $errors['telefoon'] = 'Voer een geldig telefoonnummer in (minimaal 8 cijfers).';
        }

        // E-mail
        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'E-mailadres is verplicht.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Voer een geldig e-mailadres in.';
        }

        // Adres
        if (empty(trim($data['adres'] ?? ''))) {
            $errors['adres'] = 'Adres is verplicht.';
        }

        // Postcode (Nederlands formaat)
        if (empty(trim($data['postcode'] ?? ''))) {
            $errors['postcode'] = 'Postcode is verplicht.';
        } elseif (!preg_match('/^[1-9][0-9]{3} ?[A-Z]{2}$/i', $data['postcode'])) {
            $errors['postcode'] = 'Voer een geldige Nederlandse postcode in (bijv. 1234AB).';
        }

        // Plaats
        if (empty(trim($data['plaats'] ?? ''))) {
            $errors['plaats'] = 'Plaats is verplicht.';
        }

        // Aantal volwassenen
        $volw = (int)($data['aantal_volwassenen'] ?? 0);
        if ($volw < 1) {
            $errors['aantal_volwassenen'] = 'Er moet minimaal één volwassene in het gezin zijn.';
        }

        // Aantal kinderen (mag niet negatief)
        if (isset($data['aantal_kinderen']) && (int)$data['aantal_kinderen'] < 0) {
            $errors['aantal_kinderen'] = 'Aantal kinderen mag niet negatief zijn.';
        }

        // Aantal baby's (mag niet negatief)
        if (isset($data['aantal_babys']) && (int)$data['aantal_babys'] < 0) {
            $errors['aantal_babys'] = 'Aantal baby\'s mag niet negatief zijn.';
        }

        return $errors;
    }

    /**
     * Eenvoudige redirect functie
     * 
     * @param string $url Relatief pad (begint met /)
     */
    private function redirect(string $url): void
    {
        header("Location: " . BASE_URL . $url);
        exit;
    }
}

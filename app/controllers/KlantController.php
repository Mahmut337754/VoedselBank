<?php

/**
 * KlantController
 * 
 * Verwerkt alle HTTP requests voor klantenbeheer:
 * - overzicht tonen (index)
 * - nieuw formulier (create) & opslaan (store)
 * - detail tonen (show)
 * - wijzig formulier (edit) & updaten (update)
 * - verwijderen (delete)
 * 
 * @package App\Controllers
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
     * Toon lijst van alle klanten
     * GET /klanten
     */
    public function index(): void
    {
        $klanten = $this->klantModel->getAllKlanten();

        // View laden
        $pageTitle = 'Klanten overzicht';
        require_once APP_ROOT . '/app/views/partials/header.php';
        require_once APP_ROOT . '/app/views/partials/sidebar.php';
        require_once APP_ROOT . '/app/views/klanten/index.php';
        require_once APP_ROOT . '/app/views/partials/footer.php';
    }

    /**
     * Toon formulier om nieuwe klant aan te maken
     * GET /klanten/create
     */
    public function create(): void
    {
        // Haal alle wensen op voor de checkbox-lijst
        $alleWensen = $this->klantModel->getAllWensen();
        $gekozenWensen = []; // leeg bij create

        $pageTitle = 'Nieuwe klant';
        require_once APP_ROOT . '/app/views/partials/header.php';
        require_once APP_ROOT . '/app/views/partials/sidebar.php';
        require_once APP_ROOT . '/app/views/klanten/create.php';
        require_once APP_ROOT . '/app/views/partials/footer.php';
    }

    /**
     * Sla nieuwe klant op in database
     * POST /klanten/store
     */
    public function store(): void
    {
        // Alleen POST requests accepteren
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/klanten');
            return;
        }

        // Basis validatie
        $errors = $this->validateKlantData($_POST);
        if (!empty($errors)) {
            // Toon formulier opnieuw met foutmeldingen (vereenvoudigd: sessie flash)
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('/klanten/create');
            return;
        }

        // Klantgegevens opslaan
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

        $klantId = $this->klantModel->createKlant($klantData);
        if ($klantId === false) {
            $_SESSION['errors'] = ['Database fout: klant kon niet worden opgeslagen.'];
            $this->redirect('/klanten/create');
            return;
        }

        // Wensen koppelen (indien geselecteerd)
        $wensenIds = $_POST['wensen'] ?? [];
        $this->klantModel->syncWensen($klantId, $wensenIds);

        $_SESSION['success'] = "Klant '{$_POST['gezinsnaam']}' is toegevoegd.";
        $this->redirect('/klanten');
    }

    /**
     * Toon details van één klant (inclusief zijn/haar wensen)
     * GET /klanten/show?id=123
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
            $_SESSION['errors'] = ['Klant niet gevonden.'];
            $this->redirect('/klanten');
            return;
        }

        // Haal de gekoppelde wensen op (alle details)
        $wensIds = $this->klantModel->getKlantWensenIds($id);
        $alleWensen = $this->klantModel->getAllWensen();
        $gekoppeldeWensen = array_filter($alleWensen, function ($w) use ($wensIds) {
            return in_array($w['wens_id'], $wensIds);
        });

        $pageTitle = "Klant: {$klant['gezinsnaam']}";
        require_once APP_ROOT . '/app/views/partials/header.php';
        require_once APP_ROOT . '/app/views/partials/sidebar.php';
        require_once APP_ROOT . '/app/views/klanten/show.php';
        require_once APP_ROOT . '/app/views/partials/footer.php';
    }

    /**
     * Toon bewerkingsformulier voor bestaande klant
     * GET /klanten/edit?id=123
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
            $_SESSION['errors'] = ['Klant niet gevonden.'];
            $this->redirect('/klanten');
            return;
        }

        $alleWensen = $this->klantModel->getAllWensen();
        $gekozenWensen = $this->klantModel->getKlantWensenIds($id);

        $pageTitle = "Klant bewerken: {$klant['gezinsnaam']}";
        require_once APP_ROOT . '/app/views/partials/header.php';
        require_once APP_ROOT . '/app/views/partials/sidebar.php';
        require_once APP_ROOT . '/app/views/klanten/edit.php';
        require_once APP_ROOT . '/app/views/partials/footer.php';
    }

    /**
     * Werk bestaande klant bij in database
     * POST /klanten/update
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

        $success = $this->klantModel->updateKlant($id, $klantData);
        if (!$success) {
            $_SESSION['errors'] = ['Database fout: klant kon niet worden bijgewerkt.'];
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
     * Verwijder klant (als er geen pakketten aan hangen)
     * GET /klanten/delete?id=123
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
            $_SESSION['errors'] = ['Klant bestaat niet.'];
            $this->redirect('/klanten');
            return;
        }

        $deleted = $this->klantModel->deleteKlant($id);
        if (!$deleted) {
            $_SESSION['errors'] = ['Klant kan niet worden verwijderd omdat er nog voedselpakketten aan gekoppeld zijn.'];
        } else {
            $_SESSION['success'] = "Klant '{$klant['gezinsnaam']}' is verwijderd.";
        }

        $this->redirect('/klanten');
    }

    // ------------------------------
    // Private helper methodes
    // ------------------------------

    /**
     * Valideer de ingevoerde klantgegevens
     * 
     * @param array $data POST data
     * @return array Lijst met foutmeldingen (leeg als alles OK is)
     */
    private function validateKlantData(array $data): array
    {
        $errors = [];

        if (empty(trim($data['gezinsnaam'] ?? ''))) {
            $errors[] = 'Gezinsnaam is verplicht.';
        }
        if (empty(trim($data['telefoon'] ?? ''))) {
            $errors[] = 'Telefoonnummer is verplicht.';
        }
        if (empty(trim($data['email'] ?? '')) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Een geldig e-mailadres is verplicht.';
        }
        if (empty(trim($data['adres'] ?? ''))) {
            $errors[] = 'Adres is verplicht.';
        }
        if (empty(trim($data['postcode'] ?? ''))) {
            $errors[] = 'Postcode is verplicht.';
        }
        if (empty(trim($data['plaats'] ?? ''))) {
            $errors[] = 'Plaats is verplicht.';
        }

        // Gezinssamenstelling: minimaal 1 volwassene?
        $volw = (int)($data['aantal_volwassenen'] ?? 0);
        if ($volw < 1) {
            $errors[] = 'Er moet minimaal één volwassene in het gezin zijn.';
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

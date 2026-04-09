<?php

class AllergieController
{
    /** @var Allergie Model voor allergie-gerelateerde databaseoperaties */
    private Allergie $allergieModel;

    /** @var Logger Schrijft acties en fouten weg naar het logbestand */
    private Logger $logger;

    /**
     * Constructor – controleert sessie en initialiseert modellen.
     * Redirect naar login als de gebruiker niet is ingelogd.
     */
    public function __construct()
    {
        // Controleer of de gebruiker is ingelogd via de sessie
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $this->allergieModel = new Allergie();
        $this->logger        = new Logger();
    }

    public function index(): void
    {
        try {
            $allergieen = $this->allergieModel->getAllWithKlantCount();

            // Lees en wis de flash-melding uit de sessie
            $success = $_SESSION['flash_success'] ?? null;
            $flashError = $_SESSION['flash_error'] ?? null;
            unset($_SESSION['flash_success'], $_SESSION['flash_error']);

            require APP_ROOT . '/app/views/allergieen/index.php';
        } catch (\Exception $e) {
            $this->logger->error('Fout bij ophalen allergieën: ' . $e->getMessage());
            die('Er is een fout opgetreden bij het laden van de allergieën.');
        }
    }

 
    public function create(): void
    {
        $this->requireRole();

        $error = '';
        require APP_ROOT . '/app/views/allergieen/create.php';
    }

    /**
     * store – Verwerkt het POST-formulier en slaat een nieuwe allergie op.
     *
     * @return void
     */
    public function store(): void
    {
        $this->requireRole();

        // Alleen POST-verzoeken zijn toegestaan
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /allergieen');
            exit;
        }

        // Haal en saniteer de invoer uit het formulier
        $naam         = trim($_POST['naam'] ?? '');
        $omschrijving = trim($_POST['omschrijving'] ?? '');
        $error        = '';

        try {
            // Valideer de naam: verplicht, max 100 tekens, uniek
            if ($naam === '') {
                $error = 'Naam is verplicht.';
            } elseif (strlen($naam) > 100) {
                $error = 'Naam mag maximaal 100 tekens bevatten.';
            } elseif ($this->allergieModel->nameExists($naam)) {
                $error = 'Er bestaat al een allergie/wens met deze naam.';
            } else {
                // Sla de nieuwe allergie op in de database
                $this->allergieModel->create($naam, $omschrijving);
                $this->logger->info("Allergie aangemaakt: {$naam}");

                $_SESSION['flash_success'] = 'Allergie "' . htmlspecialchars($naam) . '" is succesvol aangemaakt.';
                header('Location: /allergieen');
                exit;
            }
        } catch (\Exception $e) {
            // Databasefout afvangen en loggen
            $this->logger->error('Fout bij aanmaken allergie: ' . $e->getMessage());
            $error = 'Er is een technische fout opgetreden. Probeer het opnieuw.';
        }

        // Toon het formulier opnieuw met de foutmelding
        require APP_ROOT . '/app/views/allergieen/create.php';
    }

    public function show(): void
    {
        // Haal het ID op uit de querystring en cast naar integer
        $id = (int) ($_GET['id'] ?? 0);

        try {
            // Haal de allergie op inclusief gekoppelde klanten via JOIN
            $allergie  = $this->allergieModel->findById($id);
            $klanten   = $this->allergieModel->getKlantenByAllergieId($id);
            $inGebruik = !empty($klanten);

            if (!$allergie) {
                header('Location: /allergieen');
                exit;
            }

            require APP_ROOT . '/app/views/allergieen/show.php';
        } catch (\Exception $e) {
            $this->logger->error("Fout bij ophalen allergie (id: {$id}): " . $e->getMessage());
            die('Er is een fout opgetreden bij het laden van de allergie.');
        }
    }

    /**
     * edit – Toont het bewerkingsformulier voor een bestaande allergie.
     *
     * @return void
     */
    public function edit(): void
    {
        $this->requireRole();

        $id = (int) ($_GET['id'] ?? 0);

        try {
            $allergie = $this->allergieModel->findById($id);

            if (!$allergie) {
                header('Location: /allergieen');
                exit;
            }

            $error = '';
            require APP_ROOT . '/app/views/allergieen/edit.php';
        } catch (\Exception $e) {
            $this->logger->error("Fout bij laden bewerkingsformulier allergie (id: {$id}): " . $e->getMessage());
            die('Er is een fout opgetreden bij het laden van het formulier.');
        }
    }

    /**
     * update – Verwerkt het POST-formulier en werkt een bestaande allergie bij.
     *
     * @return void
     */
    public function update(): void
    {
        $this->requireRole();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /allergieen');
            exit;
        }

        // Haal de formulierwaarden op
        $id           = (int) ($_POST['id'] ?? 0);
        $naam         = trim($_POST['naam'] ?? '');
        $omschrijving = trim($_POST['omschrijving'] ?? '');
        $error        = '';

        try {
            $allergie = $this->allergieModel->findById($id);

            if (!$allergie) {
                header('Location: /allergieen');
                exit;
            }

            // Valideer de invoer
            if ($naam === '') {
                $error = 'Naam is verplicht.';
            } elseif (strlen($naam) > 100) {
                $error = 'Naam mag maximaal 100 tekens bevatten.';
            } elseif ($this->allergieModel->nameExists($naam, $id)) {
                $error = 'Er bestaat al een allergie/wens met deze naam.';
            } else {
                // Werk de allergie bij in de database
                $this->allergieModel->update($id, $naam, $omschrijving);
                $this->logger->info("Allergie bijgewerkt (id: {$id}): {$naam}");

                $_SESSION['flash_success'] = 'Allergie "' . htmlspecialchars($naam) . '" is succesvol bijgewerkt.';
                header('Location: /allergieen');
                exit;
            }
        } catch (\Exception $e) {
            $this->logger->error("Fout bij bijwerken allergie (id: {$id}): " . $e->getMessage());
            $error = 'Er is een technische fout opgetreden. Probeer het opnieuw.';
        }

        // Toon het formulier opnieuw met de foutmelding
        require APP_ROOT . '/app/views/allergieen/edit.php';
    }

    public function delete(): void
    {
        $this->requireRole();

        $id = (int) ($_GET['id'] ?? 0);

        try {
            // Controleer of de allergie nog gekoppeld is aan klanten
            if ($this->allergieModel->isInUse($id)) {
                $this->logger->warning("Poging tot verwijderen van allergie in gebruik (id: {$id})");
                $_SESSION['flash_error'] = 'Deze allergie kan niet worden verwijderd omdat deze nog gekoppeld is aan één of meer klanten.';
            } else {
                // Haal de naam op voor de bevestigingsmelding
                $allergie = $this->allergieModel->findById($id);
                $naam     = $allergie ? $allergie['naam'] : '';

                $this->allergieModel->delete($id);
                $this->logger->info("Allergie verwijderd (id: {$id}): {$naam}");

                $_SESSION['flash_success'] = 'Allergie "' . htmlspecialchars($naam) . '" is verwijderd.';
            }
        } catch (\Exception $e) {
            $this->logger->error("Fout bij verwijderen allergie (id: {$id}): " . $e->getMessage());
            $_SESSION['flash_error'] = 'Er is een technische fout opgetreden bij het verwijderen.';
        }

        header('Location: /allergieen');
        exit;
    }

    private function requireRole(): void
    {
        $role = $_SESSION['role'] ?? '';

        if (!in_array($role, ['Directie', 'Magazijnmedewerker'], true)) {
            http_response_code(403);
            die('Toegang geweigerd. Alleen Directie en Magazijnmedewerkers hebben toegang tot dit onderdeel.');
        }
    }
}

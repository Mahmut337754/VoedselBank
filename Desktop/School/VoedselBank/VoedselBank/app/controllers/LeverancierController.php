<?php

/**
 * LeverancierController – beheer van leveranciers (alleen Directie)
 */
class LeverancierController
{
    private Leverancier $leverancierModel;
    private string $technicalLogPath;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['role'] ?? '') !== 'Directie') {
            header('Location: /dashboard');
            exit;
        }

        $this->leverancierModel = new Leverancier();
        $this->technicalLogPath = APP_ROOT . '/logs/technical.log';
    }

    /**
     * Overzicht van alle leveranciers
     */
    public function index(): void
    {
        $pageTitle = 'Leveranciersoverzicht - ' . APP_NAME;
        $leveranciers = [];
        $error = null;
        $success = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);

        try {
            $leveranciers = $this->leverancierModel->getAllOrderedByNextDelivery();
        } catch (Throwable $exception) {
            $this->logTechnical('LEVERANCIERS_INDEX_ERROR', [
                'message' => $exception->getMessage(),
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);
            $error = 'De leveranciersgegevens konden niet worden geladen.';
        }

        require APP_ROOT . '/app/views/leveranciers/index.php';
    }

    /**
     * Toon formulier voor nieuwe leverancier
     */
    public function create(): void
    {
        $pageTitle = 'Leverancier Toevoegen - ' . APP_NAME;
        $error = null;
        $fieldErrors = [];
        $formData = [
            'bedrijfsnaam' => '',
            'adres' => '',
            'postcode' => '',
            'plaats' => '',
            'contactpersoon' => '',
            'email_contact' => '',
            'telefoon' => '',
            'eerstvolgende_levering' => '',
        ];

        require APP_ROOT . '/app/views/leveranciers/create.php';
    }

    /**
     * Opslaan van nieuwe leverancier
     */
    public function store(): void
    {
        $pageTitle = 'Leverancier Toevoegen - ' . APP_NAME;
        $error = null;
        $fieldErrors = [];

        $formData = [
            'bedrijfsnaam' => trim($_POST['bedrijfsnaam'] ?? ''),
            'adres' => trim($_POST['adres'] ?? ''),
            'postcode' => trim($_POST['postcode'] ?? ''),
            'plaats' => trim($_POST['plaats'] ?? ''),
            'contactpersoon' => trim($_POST['contactpersoon'] ?? ''),
            'email_contact' => trim($_POST['email_contact'] ?? ''),
            'telefoon' => trim($_POST['telefoon'] ?? ''),
            'eerstvolgende_levering' => trim($_POST['eerstvolgende_levering'] ?? ''),
        ];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /leveranciers');
            exit;
        }

        // Validatie verplichte velden
        foreach ($formData as $key => $value) {
            if ($value === '') {
                $fieldErrors[$key] = 'Dit veld is verplicht.';
            }
        }

        if ($formData['email_contact'] !== '' && !filter_var($formData['email_contact'], FILTER_VALIDATE_EMAIL)) {
            $fieldErrors['email_contact'] = 'Vul een geldig e-mailadres in.';
        }

        if ($formData['eerstvolgende_levering'] !== '' && strtotime($formData['eerstvolgende_levering']) === false) {
            $fieldErrors['eerstvolgende_levering'] = 'Vul een geldige datum en tijd in.';
        }

        if (!empty($fieldErrors)) {
            $error = 'Controleer de ingevulde gegevens en probeer opnieuw.';
            require APP_ROOT . '/app/views/leveranciers/create.php';
            return;
        }

        $formatted = date('Y-m-d H:i:s', strtotime($formData['eerstvolgende_levering']));
        $formData['eerstvolgende_levering'] = $formatted;

        try {
            if ($this->leverancierModel->existsByBedrijfsnaam($formData['bedrijfsnaam'])) {
                $fieldErrors['bedrijfsnaam'] = 'Er bestaat al een leverancier met dezelfde bedrijfsnaam.';
                $error = 'Er bestaat al een leverancier met dezelfde bedrijfsnaam.';
                $this->logTechnical('LEVERANCIER_DUPLICATE', [
                    'bedrijfsnaam' => $formData['bedrijfsnaam'],
                    'user_id' => $_SESSION['user_id'] ?? null,
                ]);
                require APP_ROOT . '/app/views/leveranciers/create.php';
                return;
            }

            $newId = $this->leverancierModel->create($formData);
            $this->logTechnical('LEVERANCIER_CREATED', [
                'leverancier_id' => $newId,
                'bedrijfsnaam' => $formData['bedrijfsnaam'],
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            $_SESSION['success_message'] = 'Leverancier is succesvol toegevoegd.';
            header('Location: /leveranciers');
            exit;
        } catch (Throwable $exception) {
            $this->logTechnical('LEVERANCIER_CREATE_ERROR', [
                'message' => $exception->getMessage(),
                'bedrijfsnaam' => $formData['bedrijfsnaam'],
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            $error = 'De leverancier kon niet worden opgeslagen. Probeer het opnieuw.';
            require APP_ROOT . '/app/views/leveranciers/create.php';
        }
    }

    /**
     * Toon bewerkingsformulier (GET)
     */
    public function edit(): void
    {
        // ID uit GET halen (ondersteunt zowel ?id= als /id/wijzigen)
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $requestUri = $_SERVER['REQUEST_URI'];
            if (preg_match('#/leveranciers/(\d+)/wijzigen#', $requestUri, $matches)) {
                $id = (int)$matches[1];
            }
        }

        if ($id <= 0) {
            $_SESSION['error_message'] = 'Ongeldig leveranciers ID.';
            header('Location: /leveranciers');
            exit;
        }

        try {
            $leverancier = $this->leverancierModel->findById($id);
            if (!$leverancier) {
                $_SESSION['error_message'] = 'Leverancier niet gevonden.';
                header('Location: /leveranciers');
                exit;
            }
        } catch (Throwable $exception) {
            $this->logTechnical('LEVERANCIER_EDIT_LOAD_ERROR', [
                'id' => $id,
                'message' => $exception->getMessage(),
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);
            $_SESSION['error_message'] = 'Leverancier kon niet worden geladen.';
            header('Location: /leveranciers');
            exit;
        }

        // Zet de opgehaalde gegevens klaar voor de view als $formData
        $formData = [
            'bedrijfsnaam' => $leverancier['bedrijfsnaam'],
            'adres' => $leverancier['adres'],
            'postcode' => $leverancier['postcode'],
            'plaats' => $leverancier['plaats'],
            'contactpersoon' => $leverancier['contactpersoon'],
            'email_contact' => $leverancier['email_contact'],
            'telefoon' => $leverancier['telefoon'],
            'eerstvolgende_levering' => $leverancier['eerstvolgende_levering'],
        ];

        $pageTitle = 'Leverancier Bewerken - ' . APP_NAME;
        $error = null;
        $fieldErrors = [];

        require APP_ROOT . '/app/views/leveranciers/edit.php';
    }

    /**
     * Verwerk update van leverancier (POST)
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /leveranciers');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error_message'] = 'Ongeldig leveranciers ID.';
            header('Location: /leveranciers');
            exit;
        }

        // Haal huidige gegevens op voor fallback
        $current = $this->leverancierModel->findById($id);
        if (!$current) {
            $_SESSION['error_message'] = 'Leverancier niet gevonden.';
            header('Location: /leveranciers');
            exit;
        }

        $formData = [
            'bedrijfsnaam' => trim($_POST['bedrijfsnaam'] ?? ''),
            'adres' => trim($_POST['adres'] ?? ''),
            'postcode' => trim($_POST['postcode'] ?? ''),
            'plaats' => trim($_POST['plaats'] ?? ''),
            'contactpersoon' => trim($_POST['contactpersoon'] ?? ''),
            'email_contact' => trim($_POST['email_contact'] ?? ''),
            'telefoon' => trim($_POST['telefoon'] ?? ''),
            'eerstvolgende_levering' => trim($_POST['eerstvolgende_levering'] ?? ''),
        ];

        $fieldErrors = [];
        foreach ($formData as $key => $value) {
            if ($value === '') {
                $fieldErrors[$key] = 'Dit veld is verplicht.';
            }
        }

        if ($formData['email_contact'] !== '' && !filter_var($formData['email_contact'], FILTER_VALIDATE_EMAIL)) {
            $fieldErrors['email_contact'] = 'Vul een geldig e-mailadres in.';
        }

        if ($formData['eerstvolgende_levering'] !== '' && strtotime($formData['eerstvolgende_levering']) === false) {
            $fieldErrors['eerstvolgende_levering'] = 'Vul een geldige datum en tijd in.';
        }

        if (!empty($fieldErrors)) {
            // Toon edit formulier opnieuw met fouten
            $leverancier = $current;
            $error = 'Controleer de ingevulde gegevens.';
            $pageTitle = 'Leverancier Bewerken - ' . APP_NAME;
            require APP_ROOT . '/app/views/leveranciers/edit.php';
            return;
        }

        $formatted = date('Y-m-d H:i:s', strtotime($formData['eerstvolgende_levering']));
        $formData['eerstvolgende_levering'] = $formatted;

        try {
            // Controleer op dubbele bedrijfsnaam (behalve de eigen)
            if ($this->leverancierModel->existsByBedrijfsnaam($formData['bedrijfsnaam'], $id)) {
                $fieldErrors['bedrijfsnaam'] = 'Er bestaat al een leverancier met deze bedrijfsnaam.';
                $error = 'Er bestaat al een leverancier met deze bedrijfsnaam.';
                $leverancier = $current;
                $pageTitle = 'Leverancier Bewerken - ' . APP_NAME;
                require APP_ROOT . '/app/views/leveranciers/edit.php';
                return;
            }

            $success = $this->leverancierModel->update($id, $formData);
            if (!$success) {
                throw new RuntimeException('Update retourneerde false');
            }

            $this->logTechnical('LEVERANCIER_UPDATED', [
                'leverancier_id' => $id,
                'bedrijfsnaam' => $formData['bedrijfsnaam'],
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            $_SESSION['success_message'] = 'Leverancier is succesvol bijgewerkt.';
            header('Location: /leveranciers');
            exit;
        } catch (Throwable $exception) {
            $this->logTechnical('LEVERANCIER_UPDATE_ERROR', [
                'id' => $id,
                'message' => $exception->getMessage(),
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            $error = 'De leverancier kon niet worden bijgewerkt. Probeer het opnieuw.';
            $leverancier = $current;
            $pageTitle = 'Leverancier Bewerken - ' . APP_NAME;
            require APP_ROOT . '/app/views/leveranciers/edit.php';
        }
    }

    /**
     * Verwijder een leverancier (POST)
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /leveranciers');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error_message'] = 'Ongeldig leveranciers ID.';
            header('Location: /leveranciers');
            exit;
        }

        try {
            // Controleer of leverancier nog leveringen heeft (optioneel)
            if ($this->leverancierModel->hasLeveringen($id)) {
                $_SESSION['error_message'] = 'Deze leverancier kan niet worden verwijderd omdat er nog leveringen aan gekoppeld zijn.';
                header('Location: /leveranciers');
                exit;
            }

            $deleted = $this->leverancierModel->delete($id);
            if (!$deleted) {
                throw new RuntimeException('Verwijderen mislukt (geen rijen beïnvloed)');
            }

            $this->logTechnical('LEVERANCIER_DELETED', [
                'leverancier_id' => $id,
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);

            $_SESSION['success_message'] = 'Leverancier is succesvol verwijderd.';
        } catch (Throwable $exception) {
            $this->logTechnical('LEVERANCIER_DELETE_ERROR', [
                'id' => $id,
                'message' => $exception->getMessage(),
                'user_id' => $_SESSION['user_id'] ?? null,
            ]);
            $_SESSION['error_message'] = 'Leverancier kon niet worden verwijderd. Controleer of er nog leveringen zijn.';
        }

        header('Location: /leveranciers');
        exit;
    }

    /**
     * show is niet nodig (we tonen geen detailpagina)
     */
    public function show(): void
    {
        $this->notAvailable();
    }

    private function notAvailable(): void
    {
        http_response_code(404);
        echo '<h1>404 – Pagina niet gevonden</h1>';
    }

    private function logTechnical(string $event, array $context = []): void
    {
        $line = sprintf(
            "[%s] %s %s%s",
            date('Y-m-d H:i:s'),
            $event,
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            PHP_EOL
        );

        $dir = dirname($this->technicalLogPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        if (@file_put_contents($this->technicalLogPath, $line, FILE_APPEND) === false) {
            error_log($line);
        }
    }
}

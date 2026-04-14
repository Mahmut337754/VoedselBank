<?php

/**
 * VoorraadController – beheer van productvoorraden
 */
class VoorraadController
{
    private Voorraad $voorraadModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->voorraadModel = new Voorraad();
    }

    /**
     * Overzicht van alle productvoorraden
     */
    public function index(): void
    {
        $pageTitle = 'Overzicht Productvoorraden - ' . APP_NAME;
        $producten = [];
        $error     = null;
        $success   = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);

        try {
            $producten = $this->voorraadModel->getAllProducten();
        } catch (Throwable $e) {
            $this->log('VOORRAAD_INDEX_ERROR', $e->getMessage());
            $error = 'De voorraadgegevens konden niet worden geladen.';
        }

        require APP_ROOT . '/app/views/voorraad/index.php';
    }

    /**
     * Toon detailpagina van één product (Product Details)
     */
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '/voorraad');
            exit;
        }

        $product = null;
        $error   = null;

        try {
            $product = $this->voorraadModel->getProductById($id);
            if (!$product) {
                $_SESSION['error_message'] = 'Product niet gevonden.';
                header('Location: ' . BASE_URL . '/voorraad');
                exit;
            }
        } catch (Throwable $e) {
            $this->log('VOORRAAD_SHOW_ERROR', $e->getMessage());
            $_SESSION['error_message'] = 'Product kon niet worden geladen.';
            header('Location: ' . BASE_URL . '/voorraad');
            exit;
        }

        $pageTitle = 'Product Details - ' . htmlspecialchars($product['productnaam']) . ' - ' . APP_NAME;
        $success   = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);

        require APP_ROOT . '/app/views/voorraad/show.php';
    }

    /**
     * Toon formulier voor uitleveren (Wijzig Product Details)
     */
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '/voorraad');
            exit;
        }

        try {
            $product = $this->voorraadModel->getProductById($id);
            if (!$product) {
                $_SESSION['error_message'] = 'Product niet gevonden.';
                header('Location: ' . BASE_URL . '/voorraad');
                exit;
            }
        } catch (Throwable $e) {
            $this->log('VOORRAAD_EDIT_LOAD_ERROR', $e->getMessage());
            $_SESSION['error_message'] = 'Product kon niet worden geladen.';
            header('Location: ' . BASE_URL . '/voorraad');
            exit;
        }

        $pageTitle   = 'Wijzig Product Details - ' . htmlspecialchars($product['productnaam']) . ' - ' . APP_NAME;
        $error       = null;
        $fieldErrors = [];
        $formData    = ['aantal_uitgeleverd' => ''];

        require APP_ROOT . '/app/views/voorraad/edit.php';
    }

    /**
     * Verwerk uitlevering (POST)
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/voorraad');
            exit;
        }

        $id = (int)($_POST['product_id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '/voorraad');
            exit;
        }

        // Haal product op voor validatie en fallback
        try {
            $product = $this->voorraadModel->getProductById($id);
            if (!$product) {
                $_SESSION['error_message'] = 'Product niet gevonden.';
                header('Location: ' . BASE_URL . '/voorraad');
                exit;
            }
        } catch (Throwable $e) {
            $this->log('VOORRAAD_UPDATE_LOAD_ERROR', $e->getMessage());
            $_SESSION['error_message'] = 'Product kon niet worden geladen.';
            header('Location: ' . BASE_URL . '/voorraad');
            exit;
        }

        $aantalRaw   = trim($_POST['aantal_uitgeleverd'] ?? '');
        $fieldErrors = [];
        $formData    = ['aantal_uitgeleverd' => $aantalRaw];

        // Validatie
        if ($aantalRaw === '') {
            $fieldErrors['aantal_uitgeleverd'] = 'Dit veld is verplicht.';
        } elseif (!ctype_digit($aantalRaw) || (int)$aantalRaw <= 0) {
            $fieldErrors['aantal_uitgeleverd'] = 'Vul een positief geheel getal in.';
        }

        if (!empty($fieldErrors)) {
            $error     = 'Controleer de ingevulde gegevens.';
            $pageTitle = 'Wijzig Product Details - ' . htmlspecialchars($product['productnaam']) . ' - ' . APP_NAME;
            require APP_ROOT . '/app/views/voorraad/edit.php';
            return;
        }

        $aantalUitgeleverd = (int)$aantalRaw;

        try {
            $this->voorraadModel->uitleveren($id, $aantalUitgeleverd);
            $this->log('VOORRAAD_UITGELEVERD', "product_id={$id}, aantal={$aantalUitgeleverd}");

            $_SESSION['success_message'] = "De productgegevens zijn gewijzigd. Er zijn {$aantalUitgeleverd} stuks uitgeleverd.";
            header('Location: ' . BASE_URL . '/voorraad/show?id=' . $id);
            exit;
        } catch (RuntimeException $e) {
            // Validatiefout: meer uitleveren dan voorraad
            $fieldErrors['aantal_uitgeleverd'] = $e->getMessage();
            $error     = $e->getMessage();
            $pageTitle = 'Wijzig Product Details - ' . htmlspecialchars($product['productnaam']) . ' - ' . APP_NAME;
            require APP_ROOT . '/app/views/voorraad/edit.php';
        } catch (Throwable $e) {
            $this->log('VOORRAAD_UPDATE_ERROR', $e->getMessage());
            $error       = 'De voorraad kon niet worden bijgewerkt. Probeer het opnieuw.';
            $fieldErrors = [];
            $pageTitle   = 'Wijzig Product Details - ' . htmlspecialchars($product['productnaam']) . ' - ' . APP_NAME;
            require APP_ROOT . '/app/views/voorraad/edit.php';
        }
    }

    // Niet gebruikt voor voorraad
    public function create(): void { $this->redirect('/voorraad'); }
    public function store(): void  { $this->redirect('/voorraad'); }
    public function delete(): void { $this->redirect('/voorraad'); }

    private function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    private function log(string $event, string $message): void
    {
        $logFile = APP_ROOT . '/logs/technical.log';
        $dir = dirname($logFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $line = sprintf("[%s] %s %s%s", date('Y-m-d H:i:s'), $event, $message, PHP_EOL);
        @file_put_contents($logFile, $line, FILE_APPEND);
    }
}

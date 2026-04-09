<?php
/**
 * AccountController – gebruikersbeheer (alleen Directie)
 */
class AccountController
{
    private User $userModel;
    private Role $roleModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->requireRole('Directie');
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

    public function index(): void
    {
        $users = $this->userModel->getAll();
        require APP_ROOT . '/app/views/account/index.php';
    }

    public function create(): void
    {
        $roles = $this->roleModel->getAll();
        $error = '';
        require APP_ROOT . '/app/views/account/create.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/accounts');
        }

        $username = trim($_POST['gebruikersnaam'] ?? '');
        $password = $_POST['wachtwoord'] ?? '';
        $confirm  = $_POST['wachtwoord_bevestig'] ?? '';
        $roleId   = (int)($_POST['rol_id'] ?? 0);

        $roles = $this->roleModel->getAll();
        $error = '';

        if ($username === '' || $password === '' || $roleId === 0) {
            $error = 'Vul alle verplichte velden in.';
        } elseif (strlen($password) < 8) {
            $error = 'Wachtwoord moet minimaal 8 tekens bevatten.';
        } elseif ($password !== $confirm) {
            $error = 'Wachtwoorden komen niet overeen.';
        } elseif ($this->userModel->usernameExists($username)) {
            $error = 'Gebruikersnaam is al in gebruik.';
        } else {
            $this->userModel->create($username, $password, $roleId);
            $this->redirect('/accounts');
        }

        require APP_ROOT . '/app/views/account/create.php';
    }

    public function show(): void
    {
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->findById($id);
        if (!$user) $this->redirect('/accounts');
        require APP_ROOT . '/app/views/account/show.php';
    }

    public function edit(): void
    {
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->findById($id);
        if (!$user) $this->redirect('/accounts');
        $roles = $this->roleModel->getAll();
        $error = '';
        require APP_ROOT . '/app/views/account/edit.php';
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/accounts');
        }

        $id       = (int)($_POST['id'] ?? 0);
        $username = trim($_POST['gebruikersnaam'] ?? '');
        $roleId   = (int)($_POST['rol_id'] ?? 0);
        $actief   = isset($_POST['actief']);
        $password = $_POST['wachtwoord'] ?? '';
        $confirm  = $_POST['wachtwoord_bevestig'] ?? '';

        $user  = $this->userModel->findById($id);
        $roles = $this->roleModel->getAll();
        $error = '';

        if (!$user) $this->redirect('/accounts');

        if ($username === '' || $roleId === 0) {
            $error = 'Vul alle verplichte velden in.';
        } elseif ($this->userModel->usernameExists($username, $id)) {
            $error = 'Gebruikersnaam is al in gebruik.';
        } elseif ($password !== '' && strlen($password) < 8) {
            $error = 'Wachtwoord moet minimaal 8 tekens bevatten.';
        } elseif ($password !== '' && $password !== $confirm) {
            $error = 'Wachtwoorden komen niet overeen.';
        } else {
            $this->userModel->update($id, $username, $roleId, $actief);
            if ($password !== '') {
                $this->userModel->updatePassword($id, $password);
            }
            $this->redirect('/accounts');
        }

        require APP_ROOT . '/app/views/account/edit.php';
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        // Voorkom dat directie zichzelf verwijdert
        if ($id === (int)$_SESSION['user_id']) {
            $this->redirect('/accounts');
        }

        $this->userModel->delete($id);
        $this->redirect('/accounts');
    }

    private function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    private function requireRole(string $role): void
    {
        if (($_SESSION['role'] ?? '') !== $role) {
            http_response_code(403);
            die('Toegang geweigerd. Alleen Directie heeft toegang tot dit onderdeel.');
        }
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}

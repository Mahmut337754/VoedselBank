<?php

/**
 * AuthController – inloggen en uitloggen
 */
class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['gebruikersnaam'] ?? '');
            $password = $_POST['wachtwoord'] ?? '';

            if ($username === '' || $password === '') {
                $error = 'Vul alle velden in.';
            } else {
                $user = $this->userModel->findByUsername($username);

                if ($user && password_verify($password, $user['wachtwoord_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id']   = $user['gebruiker_id'];
                    $_SESSION['username']  = $user['gebruikersnaam'];
                    $_SESSION['role']      = $user['rolnaam'];
                    $_SESSION['rol_id']    = $user['rol_id'];
                    $destination = ($user['rolnaam'] === 'Directie') ? '/' : '/dashboard';
                    $this->redirect($destination);
                } else {
                    $error = 'Ongeldige gebruikersnaam of wachtwoord.';
                }
            }
        }

        require APP_ROOT . '/app/views/auth/login.php';
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }

    // Directie kan zelf geen registratie-pagina gebruiken; accounts worden via AccountController aangemaakt.
    // Deze methode is een fallback redirect.
    public function register(): void
    {
        $this->redirect('/login');
    }

    // Wachtwoord vergeten – simpele placeholder
    public function forgotPassword(): void
    {
        require APP_ROOT . '/app/views/auth/forgot-password.php';
    }

    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}

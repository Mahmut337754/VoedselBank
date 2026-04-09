<?php
/**
 * HomeController – landingspagina
 */
class HomeController
{
    public function index(): void
    {
        // Niet ingelogd → naar login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Alleen directie krijgt de lege landingspagina
        if ($_SESSION['role'] !== 'Directie') {
            header('Location: /dashboard');
            exit;
        }

        $pageTitle = 'Home – ' . APP_NAME;
        require APP_ROOT . '/app/views/home/index.php';
    }
}

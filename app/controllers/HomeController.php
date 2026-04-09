<?php
/**
 * HomeController – homepage
 */
class HomeController
{
    public function index(): void
    {
        // Als ingelogd, redirect naar dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        // Anders redirect naar login
        header('Location: /login');
        exit;
    }
}

<?php
/**
 * DashboardController
 */
class DashboardController
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index(): void
    {
        require APP_ROOT . '/app/views/dashboard/index.php';
    }
}

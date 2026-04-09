<?php
$pageTitle = $pageTitle ?? APP_NAME;
require APP_ROOT . '/app/views/layouts/header.php';
?>

<div class="d-flex min-vh-100">

    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3" style="width:220px;">
        <div class="text-white fw-bold mb-4 ps-2">
            <i class="bi bi-basket2-fill me-2"></i><?= APP_NAME ?>
        </div>
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a href="/" class="nav-link active">
                    <i class="bi bi-house me-2"></i>Home
                </a>
            </li>
            <li class="nav-item">
                <a href="/dashboard" class="nav-link">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="/accounts" class="nav-link">
                    <i class="bi bi-people me-2"></i>Accounts
                </a>
            </li>
        </ul>
        <div class="mt-auto">
            <a href="/logout" class="nav-link text-danger">
                <i class="bi bi-box-arrow-left me-2"></i>Uitloggen
            </a>
        </div>
    </nav>

    <!-- Lege content -->
    <main class="flex-grow-1 p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="mb-0">Welkom, <?= htmlspecialchars($_SESSION['username']) ?></h5>
            <span class="badge bg-primary"><?= htmlspecialchars($_SESSION['role']) ?></span>
        </div>
        <!-- Directie landingspagina – bewust leeg gelaten -->
    </main>

</div>

<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>

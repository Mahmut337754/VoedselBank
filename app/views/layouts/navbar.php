<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$role = $_SESSION['role'] ?? '';

function navActive(string $path, string $current): string {
    return str_starts_with($current, $path) ? 'active text-white' : '';
}
?>
<div class="col-auto col-md-2 sidebar d-flex flex-column p-3">
    <a href="/dashboard" class="d-flex align-items-center mb-4 text-white text-decoration-none">
        <i class="bi bi-basket2-fill fs-4 me-2"></i>
        <span class="fw-bold"><?= APP_NAME ?></span>
    </a>

    <ul class="nav flex-column gap-1">
        <li class="nav-item">
            <a href="/dashboard" class="nav-link <?= navActive('/dashboard', $currentPath) ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="/klanten" class="nav-link <?= navActive('/klanten', $currentPath) ?>">
                <i class="bi bi-people me-2"></i> Klanten
            </a>
        </li>
        <li class="nav-item">
            <a href="/producten" class="nav-link <?= navActive('/producten', $currentPath) ?>">
                <i class="bi bi-box-seam me-2"></i> Producten
            </a>
        </li>
        <li class="nav-item">
            <a href="/voorraad" class="nav-link <?= navActive('/voorraad', $currentPath) ?>">
                <i class="bi bi-archive me-2"></i> Voorraad
            </a>
        </li>
        <li class="nav-item">
            <a href="/voedselpakketten" class="nav-link <?= navActive('/voedselpakketten', $currentPath) ?>">
                <i class="bi bi-bag-heart me-2"></i> Voedselpakketten
            </a>
        </li>
        <li class="nav-item">
            <a href="/leveranciers" class="nav-link <?= navActive('/leveranciers', $currentPath) ?>">
                <i class="bi bi-truck me-2"></i> Leveranciers
            </a>
        </li>
        <li class="nav-item">
            <a href="/allergieen" class="nav-link <?= navActive('/allergieen', $currentPath) ?>">
                <i class="bi bi-shield-exclamation me-2"></i> Allergieën
            </a>
        </li>

        <?php if ($role === 'Directie'): ?>
        <li class="mt-3">
            <small class="text-uppercase text-secondary px-2" style="font-size:0.7rem;">Beheer</small>
        </li>
        <li class="nav-item">
            <a href="/accounts" class="nav-link <?= navActive('/accounts', $currentPath) ?>">
                <i class="bi bi-person-gear me-2"></i> Accounts
            </a>
        </li>
        <li class="nav-item">
            <a href="/users" class="nav-link <?= navActive('/users', $currentPath) ?>">
                <i class="bi bi-people-fill me-2"></i> Gebruikers
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <div class="mt-auto pt-3 border-top border-secondary">
        <div class="text-white-50 small mb-2">
            <i class="bi bi-person-circle me-1"></i>
            <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
            <span class="badge bg-secondary role-badge ms-1"><?= htmlspecialchars($role) ?></span>
        </div>
        <a href="/logout" class="btn btn-sm btn-outline-danger w-100">
            <i class="bi bi-box-arrow-right me-1"></i> Uitloggen
        </a>
    </div>
</div>

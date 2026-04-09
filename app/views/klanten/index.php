<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #1a3c5e;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
        }

        .sidebar .nav-link {
            padding: 0.6rem 1rem;
        }

        .role-badge {
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $role = $_SESSION['role'] ?? '';
            function navActive($path, $current)
            {
                return str_starts_with($current, $path) ? 'active text-white' : '';
            }
            ?>
            <div class="col-auto col-md-2 sidebar d-flex flex-column p-3">
                <a href="<?= BASE_URL ?>/dashboard" class="d-flex align-items-center mb-4 text-white text-decoration-none">
                    <i class="bi bi-basket2-fill fs-4 me-2"></i>
                    <span class="fw-bold"><?= APP_NAME ?></span>
                </a>
                <ul class="nav flex-column gap-1">
                    <li class="nav-item"><a href="<?= BASE_URL ?>/dashboard" class="nav-link <?= navActive('/dashboard', $currentPath) ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                    <li class="nav-item"><a href="<?= BASE_URL ?>/klanten" class="nav-link <?= navActive('/klanten', $currentPath) ?>"><i class="bi bi-people me-2"></i> Klanten</a></li>
                    <li class="nav-item"><a href="<?= BASE_URL ?>/producten" class="nav-link <?= navActive('/producten', $currentPath) ?>"><i class="bi bi-box-seam me-2"></i> Producten</a></li>
                    <li class="nav-item"><a href="<?= BASE_URL ?>/voorraad" class="nav-link <?= navActive('/voorraad', $currentPath) ?>"><i class="bi bi-archive me-2"></i> Voorraad</a></li>
                    <li class="nav-item"><a href="<?= BASE_URL ?>/voedselpakketten" class="nav-link <?= navActive('/voedselpakketten', $currentPath) ?>"><i class="bi bi-bag-heart me-2"></i> Voedselpakketten</a></li>
                    <li class="nav-item"><a href="<?= BASE_URL ?>/leveranciers" class="nav-link <?= navActive('/leveranciers', $currentPath) ?>"><i class="bi bi-truck me-2"></i> Leveranciers</a></li>
                    <li class="nav-item"><a href="<?= BASE_URL ?>/allergieen" class="nav-link <?= navActive('/allergieen', $currentPath) ?>"><i class="bi bi-shield-exclamation me-2"></i> Allergieën</a></li>
                    <?php if ($role === 'Directie'): ?>
                        <li class="mt-3"><small class="text-uppercase text-secondary px-2" style="font-size:0.7rem;">Beheer</small></li>
                        <li class="nav-item"><a href="<?= BASE_URL ?>/accounts" class="nav-link <?= navActive('/accounts', $currentPath) ?>"><i class="bi bi-person-gear me-2"></i> Accounts</a></li>
                        <li class="nav-item"><a href="<?= BASE_URL ?>/users" class="nav-link <?= navActive('/users', $currentPath) ?>"><i class="bi bi-people-fill me-2"></i> Gebruikers</a></li>
                    <?php endif; ?>
                </ul>
                <div class="mt-auto pt-3 border-top border-secondary">
                    <div class="text-white-50 small mb-2"><i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['username'] ?? '') ?> <span class="badge bg-secondary role-badge ms-1"><?= htmlspecialchars($role) ?></span></div>
                    <a href="<?= BASE_URL ?>/logout" class="btn btn-sm btn-outline-danger w-100"><i class="bi bi-box-arrow-right me-1"></i> Uitloggen</a>
                </div>
            </div>

            <!-- Main content -->
            <div class="col p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>Klanten</h1>
                    <a href="<?= BASE_URL ?>/klanten/create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nieuwe klant</a>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['errors'])): ?>
                    <div class="alert alert-danger"><?php foreach ($_SESSION['errors'] as $error) echo "<div>" . htmlspecialchars($error) . "</div>"; ?></div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Gezinsnaam</th>
                                <th>Adres</th>
                                <th>Plaats</th>
                                <th>Telefoon</th>
                                <th>Gezin (V/K/B)</th>
                                <th class="text-end">Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($klanten as $klant): ?>
                                <tr>
                                    <td><?= htmlspecialchars($klant['gezinsnaam']) ?></td>
                                    <td><?= htmlspecialchars($klant['adres']) ?></td>
                                    <td><?= htmlspecialchars($klant['plaats']) ?></td>
                                    <td><?= htmlspecialchars($klant['telefoon']) ?></td>
                                    <td><?= $klant['aantal_volwassenen'] ?> / <?= $klant['aantal_kinderen'] ?> / <?= $klant['aantal_babys'] ?></td>
                                    <td class="text-end">
                                        <a href="<?= BASE_URL ?>/klanten/show?id=<?= $klant['klant_id'] ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                        <a href="<?= BASE_URL ?>/klanten/edit?id=<?= $klant['klant_id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="<?= BASE_URL ?>/klanten/delete?id=<?= $klant['klant_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet u zeker dat u deze klant wilt verwijderen?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($klanten)): ?><tr>
                                    <td colspan="6" class="text-center">Geen klanten gevonden.</td>
                                </tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
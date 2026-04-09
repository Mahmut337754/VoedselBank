<?php
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old_input'] ?? [];
?>
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
            <!-- Sidebar (zelfde als eerder) -->
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
                    <h1>Nieuwe klant toevoegen</h1>
                    <a href="<?= BASE_URL ?>/klanten" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Terug</a>
                </div>

                <form action="<?= BASE_URL ?>/klanten/store" method="POST">
                    <div class="row">
                        <!-- Gezinsnaam -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gezinsnaam *</label>
                            <input type="text" name="gezinsnaam"
                                class="form-control <?= isset($errors['gezinsnaam']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['gezinsnaam'] ?? '') ?>" >
                            <?php if (isset($errors['gezinsnaam'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['gezinsnaam']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Telefoon -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefoon *</label>
                            <input type="tel" name="telefoon"
                                class="form-control <?= isset($errors['telefoon']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['telefoon'] ?? '') ?>" >
                            <?php if (isset($errors['telefoon'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['telefoon']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">E-mail *</label>
                            <input type="email" name="email"
                                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['email'] ?? '') ?>" >
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Adres -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Adres *</label>
                            <input type="text" name="adres"
                                class="form-control <?= isset($errors['adres']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['adres'] ?? '') ?>" >
                            <?php if (isset($errors['adres'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['adres']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Postcode -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Postcode *</label>
                            <input type="text" name="postcode"
                                class="form-control <?= isset($errors['postcode']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['postcode'] ?? '') ?>" >
                            <?php if (isset($errors['postcode'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['postcode']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Plaats -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Plaats *</label>
                            <input type="text" name="plaats"
                                class="form-control <?= isset($errors['plaats']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($old['plaats'] ?? '') ?>" >
                            <?php if (isset($errors['plaats'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['plaats']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Aantal volwassenen -->
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Aantal volwassenen *</label>
                            <input type="number" name="aantal_volwassenen"
                                class="form-control <?= isset($errors['aantal_volwassenen']) ? 'is-invalid' : '' ?>"
                                min="1" value="<?= htmlspecialchars($old['aantal_volwassenen'] ?? 1) ?>" >
                            <?php if (isset($errors['aantal_volwassenen'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['aantal_volwassenen']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Aantal kinderen -->
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Aantal kinderen</label>
                            <input type="number" name="aantal_kinderen"
                                class="form-control <?= isset($errors['aantal_kinderen']) ? 'is-invalid' : '' ?>"
                                min="0" value="<?= htmlspecialchars($old['aantal_kinderen'] ?? 0) ?>">
                            <?php if (isset($errors['aantal_kinderen'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['aantal_kinderen']) ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Aantal baby's -->
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Aantal baby's</label>
                            <input type="number" name="aantal_babys"
                                class="form-control <?= isset($errors['aantal_babys']) ? 'is-invalid' : '' ?>"
                                min="0" value="<?= htmlspecialchars($old['aantal_babys'] ?? 0) ?>">
                            <?php if (isset($errors['aantal_babys'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['aantal_babys']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Wensen (zelfde als eerder) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Dieetwensen / Allergieën</label>
                        <div class="row">
                            <?php foreach ($alleWensen as $wens): ?>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="wensen[]"
                                            value="<?= $wens['wens_id'] ?>" id="wens_<?= $wens['wens_id'] ?>"
                                            <?= (isset($old['wensen']) && in_array($wens['wens_id'], $old['wensen'])) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="wens_<?= $wens['wens_id'] ?>">
                                            <?= htmlspecialchars($wens['naam']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Opslaan</button>
                    <a href="<?= BASE_URL ?>/klanten" class="btn btn-secondary">Annuleren</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php unset($_SESSION['errors'], $_SESSION['old_input']); ?>
</body>

</html>
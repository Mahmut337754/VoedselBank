<?php $pageTitle = 'Inloggen - ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow" style="width:100%;max-width:420px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-basket2-fill text-primary fs-1"></i>
                <h4 class="mt-2 fw-bold"><?= APP_NAME ?></h4>
                <p class="text-muted small">Log in om verder te gaan</p>
            </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="/login" novalidate>
                <div class="mb-3">
                    <label for="gebruikersnaam" class="form-label">Gebruikersnaam</label>
                    <input type="text" id="gebruikersnaam" name="gebruikersnaam" class="form-control" required autofocus value="<?= htmlspecialchars($_POST['gebruikersnaam'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="wachtwoord" class="form-label">Wachtwoord</label>
                    <input type="password" id="wachtwoord" name="wachtwoord" class="form-control" required>
                </div>
                <div class="d-flex justify-content-end mb-3">
                    <a href="/forgot-password" class="small text-muted">Wachtwoord vergeten?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Inloggen
                </button>
            </form>
        </div>
    </div>
</div>
<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>

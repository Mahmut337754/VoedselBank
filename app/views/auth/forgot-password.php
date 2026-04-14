<?php $pageTitle = 'Wachtwoord vergeten - ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow" style="width:100%;max-width:420px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-key-fill text-warning fs-1"></i>
                <h4 class="mt-2 fw-bold">Wachtwoord vergeten</h4>
                <p class="text-muted small">Neem contact op met de Directie om uw wachtwoord te laten resetten.</p>
            </div>
            <a href="/login" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-1"></i> Terug naar inloggen
            </a>
        </div>
    </div>
</div>
<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>
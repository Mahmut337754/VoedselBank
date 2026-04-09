<?php $pageTitle = 'Nieuwe allergie - ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>
        <main class="col p-4" style="max-width:600px;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="/allergieen" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
                <h5 class="fw-bold mb-0">Nieuwe allergie aanmaken</h5>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="/allergieen/store" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Naam <span class="text-danger">*</span></label>
                            <input type="text" name="naam" class="form-control" required maxlength="100"
                                   value="<?= htmlspecialchars($_POST['naam'] ?? '') ?>"
                                   placeholder="bijv. Glutenallergie">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Omschrijving</label>
                            <textarea name="omschrijving" class="form-control" rows="3" maxlength="255"
                                      placeholder="Optionele toelichting..."><?= htmlspecialchars($_POST['omschrijving'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Aanmaken
                        </button>
                        <a href="/allergieen" class="btn btn-outline-secondary ms-2">Annuleren</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>

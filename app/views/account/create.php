<?php $pageTitle = 'Nieuw account - ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>
        <main class="col p-4" style="max-width:600px;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="/accounts" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
                <h5 class="fw-bold mb-0">Nieuw account aanmaken</h5>
            </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="/accounts/store" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Gebruikersnaam <span class="text-danger">*</span></label>
                            <input type="text" name="gebruikersnaam" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['gebruikersnaam'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol <span class="text-danger">*</span></label>
                            <select name="rol_id" class="form-select" required>
                                <option value="">-- Kies een rol --</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['rol_id'] ?>"
                                        <?= (($_POST['rol_id'] ?? '') == $r['rol_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($r['rolnaam']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" name="wachtwoord" class="form-control" required minlength="8">
                            <div class="form-text">Minimaal 8 tekens.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Wachtwoord bevestigen <span class="text-danger">*</span></label>
                            <input type="password" name="wachtwoord_bevestig" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i> Account aanmaken
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>
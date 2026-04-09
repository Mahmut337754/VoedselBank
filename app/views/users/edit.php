<?php $pageTitle = 'Gebruiker bewerken – ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>
        <main class="col p-4" style="max-width:640px;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="/users" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
                <h5 class="fw-bold mb-0">Gebruiker bewerken</h5>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="/users/update" novalidate>
                        <input type="hidden" name="id" value="<?= $user['gebruiker_id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Gebruikersnaam <span class="text-danger">*</span></label>
                            <input type="text" name="gebruikersnaam" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['gebruikersnaam'] ?? $user['gebruikersnaam']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol <span class="text-danger">*</span></label>
                            <select name="rol_id" class="form-select" required>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['rol_id'] ?>"
                                        <?= (($_POST['rol_id'] ?? $user['rol_id']) == $r['rol_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($r['rolnaam']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="actief" id="actief"
                                    <?= (isset($_POST['actief']) ? true : (bool)$user['actief']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="actief">Account actief</label>
                            </div>
                        </div>

                        <hr>
                        <p class="text-muted small mb-3">Laat leeg om wachtwoord ongewijzigd te laten.</p>

                        <div class="mb-3">
                            <label class="form-label">Nieuw wachtwoord</label>
                            <input type="password" name="wachtwoord" class="form-control" minlength="8">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Wachtwoord bevestigen</label>
                            <input type="password" name="wachtwoord_bevestig" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Opslaan
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>

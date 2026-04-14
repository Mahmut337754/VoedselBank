<?php $pageTitle = 'Account details - ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>
        <main class="col p-4" style="max-width:500px;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="/accounts" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
                <h5 class="fw-bold mb-0">Account details</h5>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8"><?= $user['gebruiker_id'] ?></dd>
                        <dt class="col-sm-4">Gebruikersnaam</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($user['gebruikersnaam']) ?></dd>
                        <dt class="col-sm-4">Rol</dt>
                        <dd class="col-sm-8"><span class="badge bg-secondary"><?= htmlspecialchars($user['rolnaam']) ?></span></dd>
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <?php if ($user['actief']): ?>
                                <span class="badge bg-success">Actief</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactief</span>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
                <div class="card-footer bg-transparent d-flex gap-2">
                    <a href="/accounts/edit?id=<?= $user['gebruiker_id'] ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Bewerken
                    </a>
                    <?php if ($user['gebruiker_id'] != $_SESSION['user_id']): ?>
                    <a href="/accounts/delete?id=<?= $user['gebruiker_id'] ?>"
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Account verwijderen?')">
                        <i class="bi bi-trash me-1"></i> Verwijderen
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>
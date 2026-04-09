<?php $pageTitle = 'Gebruikers – ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>
        <main class="col p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Gebruikersbeheer</h5>
                <a href="/users/create" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus me-1"></i> Nieuwe gebruiker
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Gebruikersnaam</th>
                                <th>Rol</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="text-muted"><?= $u['gebruiker_id'] ?></td>
                                <td><?= htmlspecialchars($u['gebruikersnaam']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($u['rolnaam']) ?></span></td>
                                <td>
                                    <?php if ($u['actief']): ?>
                                        <span class="badge bg-success">Actief</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactief</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="/users/show?id=<?= $u['gebruiker_id'] ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/users/edit?id=<?= $u['gebruiker_id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($u['gebruiker_id'] != $_SESSION['user_id']): ?>
                                    <a href="/users/delete?id=<?= $u['gebruiker_id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Gebruiker verwijderen?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>

<?php $pageTitle = 'Allergieën - ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>
        <main class="col p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Allergieën &amp; Dieetwensen</h5>
                <?php if (in_array($_SESSION['role'] ?? '', ['Directie', 'Magazijnmedewerker'])): ?>
                <a href="/allergieen/create" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Nieuwe allergie
                </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php
            $flashError = $_SESSION['flash_error'] ?? null;
            unset($_SESSION['flash_error']);
            if ($flashError): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($flashError) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Naam</th>
                                <th>Omschrijving</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($allergieen)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Geen allergieën gevonden.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($allergieen as $a): ?>
                            <tr>
                                <td><?= $a['wens_id'] ?></td>
                                <td><?= htmlspecialchars($a['naam']) ?></td>
                                <td class="text-muted"><?= htmlspecialchars($a['omschrijving'] ?? '—') ?></td>
                                <td class="text-end">
                                    <a href="/allergieen/show?id=<?= $a['wens_id'] ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (in_array($_SESSION['role'] ?? '', ['Directie', 'Magazijnmedewerker'])): ?>
                                    <a href="/allergieen/edit?id=<?= $a['wens_id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/allergieen/delete?id=<?= $a['wens_id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Allergie \'<?= htmlspecialchars(addslashes($a['naam'])) ?>\' verwijderen?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>

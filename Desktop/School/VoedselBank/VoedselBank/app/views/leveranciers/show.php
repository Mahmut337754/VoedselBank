<?php $pageTitle = 'Allergie details - ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>
        <main class="col p-4" style="max-width:500px;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="/allergieen" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
                <h5 class="fw-bold mb-0">Allergie details</h5>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">ID</dt>
                        <dd class="col-sm-8"><?= $allergie['wens_id'] ?></dd>

                        <dt class="col-sm-4">Naam</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($allergie['naam']) ?></dd>

                        <dt class="col-sm-4">Omschrijving</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($allergie['omschrijving'] ?? '—') ?></dd>

                        <dt class="col-sm-4">In gebruik</dt>
                        <dd class="col-sm-8">
                            <?php if ($inGebruik): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-people me-1"></i> Gekoppeld aan klanten
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Niet in gebruik</span>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
                <div class="card-footer bg-transparent d-flex gap-2">
                    <?php if (in_array($_SESSION['role'] ?? '', ['Directie', 'Magazijnmedewerker'])): ?>
                        <a href="/allergieen/edit?id=<?= $allergie['wens_id'] ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i> Bewerken
                        </a>
                        <?php if (!$inGebruik): ?>
                            <a href="/allergieen/delete?id=<?= $allergie['wens_id'] ?>"
                                class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('Allergie \'<?= htmlspecialchars(addslashes($allergie['naam'])) ?>\' verwijderen?')">
                                <i class="bi bi-trash me-1"></i> Verwijderen
                            </a>
                        <?php else: ?>
                            <button class="btn btn-outline-danger btn-sm" disabled title="Kan niet verwijderen: nog gekoppeld aan klanten">
                                <i class="bi bi-trash me-1"></i> Verwijderen
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($inGebruik): ?>
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Deze allergie is gekoppeld aan één of meer klanten en kan daarom niet worden verwijderd.
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>
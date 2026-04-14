<?php
$pageTitle = $pageTitle ?? 'Overzicht Productvoorraden - ' . APP_NAME;
require APP_ROOT . '/app/views/layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>

        <main class="col p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Overzicht Productvoorraden</h5>
                    <p class="text-muted mb-0">Alle producten met hun huidige voorraadstatus.</p>
                </div>
                <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Terug naar dashboard
                </a>
            </div>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" role="alert">
                    <div>
                        <div class="fw-bold mb-1">Voorraadgegevens konden niet worden geladen</div>
                        <div><?= htmlspecialchars($error) ?></div>
                    </div>
                    <a href="<?= BASE_URL ?>/voorraad" class="btn btn-danger">
                        <i class="bi bi-arrow-clockwise me-1"></i>Opnieuw proberen
                    </a>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Totaal producten</span>
                        <span class="badge bg-primary"><?= count($producten) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Productnaam</th>
                                        <th>Categorie</th>
                                        <th>EAN</th>
                                        <th>Aantal op voorraad</th>
                                        <th class="text-end">Voorraad Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($producten)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Er zijn nog geen producten gevonden.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($producten as $product): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= htmlspecialchars($product['productnaam']) ?></td>
                                                <td><?= htmlspecialchars($product['categorie']) ?></td>
                                                <td><code><?= htmlspecialchars($product['ean']) ?></code></td>
                                                <td>
                                                    <?php $aantal = (int)$product['aantal_op_voorraad']; ?>
                                                    <span class="badge <?= $aantal === 0 ? 'bg-danger' : ($aantal < 10 ? 'bg-warning text-dark' : 'bg-success') ?>">
                                                        <?= $aantal ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="<?= BASE_URL ?>/voorraad/show?id=<?= (int)$product['product_id'] ?>"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i>Details
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>

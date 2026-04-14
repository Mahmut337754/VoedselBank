<?php $pageTitle = 'Dashboard - ' . APP_NAME; ?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>
        <main class="col p-4">
            <h5 class="fw-bold mb-4">Dashboard</h5>
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <i class="bi bi-people-fill fs-2 text-primary"></i>
                            <div>
                                <div class="text-muted small">Klanten</div>
                                <a href="/klanten" class="stretched-link text-decoration-none fw-bold">Beheren</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <i class="bi bi-box-seam-fill fs-2 text-success"></i>
                            <div>
                                <div class="text-muted small">Producten</div>
                                <a href="/producten" class="stretched-link text-decoration-none fw-bold">Beheren</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <i class="bi bi-bag-heart-fill fs-2 text-danger"></i>
                            <div>
                                <div class="text-muted small">Voedselpakketten</div>
                                <a href="/voedselpakketten" class="stretched-link text-decoration-none fw-bold">Beheren</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <i class="bi bi-truck fs-2 text-warning"></i>
                            <div>
                                <div class="text-muted small">Leveranciers</div>
                                <a href="/leveranciers" class="stretched-link text-decoration-none fw-bold">Beheren</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (($_SESSION['role'] ?? '') === 'Directie'): ?>
            <div class="mt-4">
                <div class="alert alert-info d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>U bent ingelogd als <strong>Directie</strong>. U kunt <a href="/accounts">accounts beheren</a>.</span>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>
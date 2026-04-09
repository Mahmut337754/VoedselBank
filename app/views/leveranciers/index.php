<?php
$pageTitle = $pageTitle ?? 'Leveranciersoverzicht - ' . APP_NAME;
require APP_ROOT . '/app/views/layouts/header.php';

function formatDeliveryDate(?string $value): string
{
	if (!$value) {
		return '-';
	}

	$date = DateTime::createFromFormat('Y-m-d H:i:s', $value) ?: new DateTime($value);

	return $date->format('d-m-Y H:i');
}
?>

<div class="container-fluid">
	<div class="row">
		<?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>

		<main class="col p-4">
			<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
				<div>
					<h5 class="fw-bold mb-1">Leveranciersoverzicht</h5>
					<p class="text-muted mb-0">Alle leveranciers, gesorteerd op eerstvolgende levering.</p>
				</div>
				<div class="d-flex gap-2">
					<a href="/leveranciers/nieuw" class="btn btn-primary">
						<i class="bi bi-plus-lg me-1"></i>Leverancier Toevoegen
					</a>
					<a href="/dashboard" class="btn btn-outline-secondary">
						<i class="bi bi-arrow-left me-1"></i>Terug naar dashboard
					</a>
				</div>
			</div>

			<?php if (!empty($success)): ?>
				<div class="alert alert-success" role="alert">
					<?= htmlspecialchars($success) ?>
				</div>
			<?php endif; ?>

			<?php if (!empty($error)): ?>
				<div class="alert alert-danger d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" role="alert">
					<div>
						<div class="fw-bold mb-1">Leveranciersgegevens konden niet worden geladen</div>
						<div><?= htmlspecialchars($error) ?></div>
					</div>
					<a href="/leveranciers" class="btn btn-danger">
						<i class="bi bi-arrow-clockwise me-1"></i>Opnieuw proberen
					</a>
				</div>
			<?php else: ?>
				<div class="card border-0 shadow-sm">
					<div class="card-header bg-white border-0 pb-0 d-flex justify-content-between align-items-center">
						<span class="text-muted">Totaal leveranciers</span>
						<span class="badge bg-primary"><?= count($leveranciers) ?></span>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table align-middle table-hover mb-0">
								<thead class="table-light">
									<tr>
										<th>Bedrijfsnaam</th>
										<th>Contactpersoon</th>
										<th>Email</th>
										<th>Telefoon</th>
										<th>Adres</th>
										<th>Eerstvolgende levering</th>
									</tr>
								</thead>
								<tbody>
									<?php if (empty($leveranciers)): ?>
										<tr>
											<td colspan="6" class="text-center text-muted py-4">Er zijn nog geen leveranciers gevonden.</td>
										</tr>
									<?php else: ?>
										<?php foreach ($leveranciers as $leverancier): ?>
											<tr>
												<td class="fw-semibold"><a class="text-decoration-none" href="/leveranciers/<?= (int)$leverancier['leverancier_id'] ?>/wijzigen"><?= htmlspecialchars($leverancier['bedrijfsnaam']) ?></a></td>
												<td><?= htmlspecialchars($leverancier['contactpersoon']) ?></td>
												<td><a href="mailto:<?= htmlspecialchars($leverancier['email_contact']) ?>" class="text-decoration-none"><?= htmlspecialchars($leverancier['email_contact']) ?></a></td>
												<td><?= htmlspecialchars($leverancier['telefoon']) ?></td>
												<td>
													<?= htmlspecialchars($leverancier['adres']) ?><br>
													<span class="text-muted small"><?= htmlspecialchars($leverancier['postcode']) ?> <?= htmlspecialchars($leverancier['plaats']) ?></span>
												</td>
												<td><?= htmlspecialchars(formatDeliveryDate($leverancier['eerstvolgende_levering'])) ?></td>
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

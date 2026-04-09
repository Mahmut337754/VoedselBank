<?php
$pageTitle = $pageTitle ?? 'Leverancier Wijzigen - ' . APP_NAME;
require APP_ROOT . '/app/views/layouts/header.php';

$formData = $formData ?? [
	'bedrijfsnaam' => '',
	'adres' => '',
	'postcode' => '',
	'plaats' => '',
	'contactpersoon' => '',
	'email_contact' => '',
	'telefoon' => '',
	'eerstvolgende_levering' => '',
];
$fieldErrors = $fieldErrors ?? [];
$error = $error ?? null;
$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));

function editOldValue(array $data, string $key): string
{
	return htmlspecialchars($data[$key] ?? '');
}

function editErrorClass(array $errors, string $key): string
{
	return isset($errors[$key]) ? 'is-invalid' : '';
}
?>

<div class="container-fluid">
	<div class="row">
		<?php require APP_ROOT . '/app/views/layouts/navbar.php'; ?>

		<main class="col p-4">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<div>
					<h5 class="fw-bold mb-1">Leverancier Wijzigen</h5>
					<p class="text-muted mb-0">Werk de leveranciersgegevens bij en sla de wijzigingen op.</p>
				</div>
				<a href="/leveranciers" class="btn btn-outline-secondary">
					<i class="bi bi-arrow-left me-1"></i>Terug naar overzicht
				</a>
			</div>

			<?php if (!empty($error)): ?>
				<div class="alert alert-danger" role="alert">
					<?= htmlspecialchars($error) ?>
				</div>
			<?php endif; ?>

			<div class="card border-0 shadow-sm">
				<div class="card-body">
					<form method="POST" action="/leveranciers/<?= $id ?>/wijzigen" novalidate>
						<input type="hidden" name="id" value="<?= $id ?>">
						<div class="row g-3">
							<div class="col-md-6">
								<label for="bedrijfsnaam" class="form-label">Bedrijfsnaam</label>
								<input type="text" class="form-control <?= editErrorClass($fieldErrors, 'bedrijfsnaam') ?>" id="bedrijfsnaam" name="bedrijfsnaam" value="<?= editOldValue($formData, 'bedrijfsnaam') ?>" required>
								<?php if (isset($fieldErrors['bedrijfsnaam'])): ?>
									<div class="invalid-feedback"><?= htmlspecialchars($fieldErrors['bedrijfsnaam']) ?></div>
								<?php endif; ?>
							</div>

							<div class="col-md-6">
								<label for="contactpersoon" class="form-label">Contactpersoon</label>
								<input type="text" class="form-control <?= editErrorClass($fieldErrors, 'contactpersoon') ?>" id="contactpersoon" name="contactpersoon" value="<?= editOldValue($formData, 'contactpersoon') ?>" required>
								<?php if (isset($fieldErrors['contactpersoon'])): ?>
									<div class="invalid-feedback"><?= htmlspecialchars($fieldErrors['contactpersoon']) ?></div>
								<?php endif; ?>
							</div>

							<div class="col-md-6">
								<label for="email_contact" class="form-label">E-mailadres</label>
								<input type="email" class="form-control <?= editErrorClass($fieldErrors, 'email_contact') ?>" id="email_contact" name="email_contact" value="<?= editOldValue($formData, 'email_contact') ?>" required>
								<?php if (isset($fieldErrors['email_contact'])): ?>
									<div class="invalid-feedback"><?= htmlspecialchars($fieldErrors['email_contact']) ?></div>
								<?php endif; ?>
							</div>

							<div class="col-md-6">
								<label for="telefoon" class="form-label">Telefoon</label>
								<input type="text" class="form-control <?= editErrorClass($fieldErrors, 'telefoon') ?>" id="telefoon" name="telefoon" value="<?= editOldValue($formData, 'telefoon') ?>" required>
								<?php if (isset($fieldErrors['telefoon'])): ?>
									<div class="invalid-feedback"><?= htmlspecialchars($fieldErrors['telefoon']) ?></div>
								<?php endif; ?>
							</div>

							<div class="col-md-8">
								<label for="adres" class="form-label">Adres</label>
								<input type="text" class="form-control <?= editErrorClass($fieldErrors, 'adres') ?>" id="adres" name="adres" value="<?= editOldValue($formData, 'adres') ?>" required>
								<?php if (isset($fieldErrors['adres'])): ?>
									<div class="invalid-feedback"><?= htmlspecialchars($fieldErrors['adres']) ?></div>
								<?php endif; ?>
							</div>

							<div class="col-md-4">
								<label for="postcode" class="form-label">Postcode</label>
								<input type="text" class="form-control <?= editErrorClass($fieldErrors, 'postcode') ?>" id="postcode" name="postcode" value="<?= editOldValue($formData, 'postcode') ?>" required>
								<?php if (isset($fieldErrors['postcode'])): ?>
									<div class="invalid-feedback"><?= htmlspecialchars($fieldErrors['postcode']) ?></div>
								<?php endif; ?>
							</div>

							<div class="col-md-6">
								<label for="plaats" class="form-label">Plaats</label>
								<input type="text" class="form-control <?= editErrorClass($fieldErrors, 'plaats') ?>" id="plaats" name="plaats" value="<?= editOldValue($formData, 'plaats') ?>" required>
								<?php if (isset($fieldErrors['plaats'])): ?>
									<div class="invalid-feedback"><?= htmlspecialchars($fieldErrors['plaats']) ?></div>
								<?php endif; ?>
							</div>

							<div class="col-md-6">
								<label for="eerstvolgende_levering" class="form-label">Eerstvolgende levering</label>
								<input type="datetime-local" class="form-control <?= editErrorClass($fieldErrors, 'eerstvolgende_levering') ?>" id="eerstvolgende_levering" name="eerstvolgende_levering" value="<?= editOldValue($formData, 'eerstvolgende_levering') ?>" required>
								<?php if (isset($fieldErrors['eerstvolgende_levering'])): ?>
									<div class="invalid-feedback"><?= htmlspecialchars($fieldErrors['eerstvolgende_levering']) ?></div>
								<?php endif; ?>
							</div>
						</div>

						<div class="d-flex justify-content-end gap-2 mt-4">
							<a href="/leveranciers" class="btn btn-outline-secondary">Annuleren</a>
							<button type="submit" class="btn btn-primary">
								<i class="bi bi-save me-1"></i>Wijzigingen Opslaan
							</button>
						</div>
					</form>
				</div>
			</div>
		</main>
	</div>
</div>

<?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>

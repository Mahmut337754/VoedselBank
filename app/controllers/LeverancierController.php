<?php

/**
 * LeverancierController – leveranciersoverzicht
 */
class LeverancierController
{
	private Leverancier $leverancierModel;
	private string $technicalLogPath;

	public function __construct()
	{
		if (!isset($_SESSION['user_id'])) {
			header('Location: /login');
			exit;
		}

		if (($_SESSION['role'] ?? '') !== 'Directie') {
			header('Location: /dashboard');
			exit;
		}

		$this->leverancierModel = new Leverancier();
		$this->technicalLogPath = APP_ROOT . '/app/logs/technical.log';
	}

	public function index(): void
	{
		$pageTitle = 'Leveranciersoverzicht - ' . APP_NAME;
		$leveranciers = [];
		$error = null;
		$success = $_SESSION['success_message'] ?? null;
		unset($_SESSION['success_message']);

		try {
			$leveranciers = $this->leverancierModel->getAllOrderedByNextDelivery();
		} catch (Throwable $exception) {
			$this->logTechnical('LEVERANCIERS_INDEX_ERROR', [
				'message' => $exception->getMessage(),
				'user_id' => $_SESSION['user_id'] ?? null,
			]);
			$error = 'De leveranciersgegevens konden niet worden geladen.';
		}

		require APP_ROOT . '/app/views/leveranciers/index.php';
	}

	public function create(): void
	{
		$pageTitle = 'Leverancier Toevoegen - ' . APP_NAME;
		$error = null;
		$fieldErrors = [];
		$formData = [
			'bedrijfsnaam' => '',
			'adres' => '',
			'postcode' => '',
			'plaats' => '',
			'contactpersoon' => '',
			'email_contact' => '',
			'telefoon' => '',
			'eerstvolgende_levering' => '',
		];

		require APP_ROOT . '/app/views/leveranciers/create.php';
	}

	public function store(): void
	{
		$pageTitle = 'Leverancier Toevoegen - ' . APP_NAME;
		$error = null;
		$fieldErrors = [];

		$formData = [
			'bedrijfsnaam' => trim($_POST['bedrijfsnaam'] ?? ''),
			'adres' => trim($_POST['adres'] ?? ''),
			'postcode' => trim($_POST['postcode'] ?? ''),
			'plaats' => trim($_POST['plaats'] ?? ''),
			'contactpersoon' => trim($_POST['contactpersoon'] ?? ''),
			'email_contact' => trim($_POST['email_contact'] ?? ''),
			'telefoon' => trim($_POST['telefoon'] ?? ''),
			'eerstvolgende_levering' => trim($_POST['eerstvolgende_levering'] ?? ''),
		];

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			header('Location: /leveranciers/nieuw');
			exit;
		}

		foreach ($formData as $key => $value) {
			if ($value === '') {
				$fieldErrors[$key] = 'Dit veld is verplicht.';
			}
		}

		if ($formData['email_contact'] !== '' && !filter_var($formData['email_contact'], FILTER_VALIDATE_EMAIL)) {
			$fieldErrors['email_contact'] = 'Vul een geldig e-mailadres in.';
		}

		if ($formData['eerstvolgende_levering'] !== '' && strtotime($formData['eerstvolgende_levering']) === false) {
			$fieldErrors['eerstvolgende_levering'] = 'Vul een geldige datum en tijd in.';
		}

		if (!empty($fieldErrors)) {
			$error = 'Controleer de ingevulde gegevens en probeer opnieuw.';
			require APP_ROOT . '/app/views/leveranciers/create.php';
			return;
		}

		$formatted = date('Y-m-d H:i:s', strtotime($formData['eerstvolgende_levering']));
		$formData['eerstvolgende_levering'] = $formatted;

		try {
			if ($this->leverancierModel->existsByBedrijfsnaam($formData['bedrijfsnaam'])) {
				$fieldErrors['bedrijfsnaam'] = 'Er bestaat al een leverancier met dezelfde bedrijfsnaam.';
				$error = 'Er bestaat al een leverancier met dezelfde bedrijfsnaam.';
				$this->logTechnical('LEVERANCIER_DUPLICATE', [
					'bedrijfsnaam' => $formData['bedrijfsnaam'],
					'user_id' => $_SESSION['user_id'] ?? null,
				]);
				require APP_ROOT . '/app/views/leveranciers/create.php';
				return;
			}

			$newId = $this->leverancierModel->create($formData);
			$this->logTechnical('LEVERANCIER_CREATED', [
				'leverancier_id' => $newId,
				'bedrijfsnaam' => $formData['bedrijfsnaam'],
				'user_id' => $_SESSION['user_id'] ?? null,
			]);

			$_SESSION['success_message'] = 'Leverancier is succesvol toegevoegd.';
			header('Location: /leveranciers');
			exit;
		} catch (Throwable $exception) {
			$this->logTechnical('LEVERANCIER_CREATE_ERROR', [
				'message' => $exception->getMessage(),
				'bedrijfsnaam' => $formData['bedrijfsnaam'],
				'user_id' => $_SESSION['user_id'] ?? null,
			]);

			$error = 'De leverancier kon niet worden opgeslagen. Probeer het opnieuw.';
			require APP_ROOT . '/app/views/leveranciers/create.php';
		}
	}

	public function show(): void
	{
		$this->notAvailable();
	}

	public function edit(): void
	{
		$this->notAvailable();
	}

	public function update(): void
	{
		$this->notAvailable();
	}

	public function delete(): void
	{
		$this->notAvailable();
	}

	private function notAvailable(): void
	{
		http_response_code(404);
		echo '<h1>404 – Pagina niet gevonden</h1>';
	}

	private function logTechnical(string $event, array $context = []): void
	{
		$line = sprintf(
			"[%s] %s %s%s",
			date('Y-m-d H:i:s'),
			$event,
			json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
			PHP_EOL
		);

		$dir = dirname($this->technicalLogPath);
		if (!is_dir($dir)) {
			@mkdir($dir, 0777, true);
		}

		if (@file_put_contents($this->technicalLogPath, $line, FILE_APPEND) === false) {
			error_log($line);
		}
	}
}

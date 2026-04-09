<?php

/**
 * LeverancierController – leveranciersoverzicht
 */
class LeverancierController
{
	private Leverancier $leverancierModel;

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
	}

	public function index(): void
	{
		$pageTitle = 'Leveranciersoverzicht - ' . APP_NAME;
		$leveranciers = [];
		$error = null;

		try {
			$leveranciers = $this->leverancierModel->getAllOrderedByNextDelivery();
		} catch (Throwable $exception) {
			error_log('Leveranciersoverzicht kon niet worden geladen: ' . $exception->getMessage());
			$error = 'De leveranciersgegevens konden niet worden geladen.';
		}

		require APP_ROOT . '/app/views/leveranciers/index.php';
	}

	public function create(): void
	{
		$this->notAvailable();
	}

	public function store(): void
	{
		$this->notAvailable();
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
}

<?php

/**
 * Leverancier model
 */
class Leverancier extends BaseModel
{
	/**
	 * Haalt alle leveranciers op, gesorteerd op eerstvolgende levering.
	 */
	public function getAllOrderedByNextDelivery(): array
	{
		try {
			$statement = $this->db->query('CALL sp_leveranciers_overzicht()');
			$result = $statement->fetchAll();
			$statement->closeCursor();

			return $result;
		} catch (Throwable $exception) {
			$sql = '
				SELECT
					l.leverancier_id,
					l.bedrijfsnaam,
					l.adres,
					l.postcode,
					l.plaats,
					l.contactpersoon,
					l.email_contact,
					l.telefoon,
					l.eerstvolgende_levering,
					COUNT(le.levering_id) AS totaal_leveringen,
					COALESCE(SUM(le.hoeveelheid), 0) AS totaal_hoeveelheid
				FROM leveranciers l
				LEFT JOIN leveringen le ON le.leverancier_id = l.leverancier_id
				GROUP BY
					l.leverancier_id,
					l.bedrijfsnaam,
					l.adres,
					l.postcode,
					l.plaats,
					l.contactpersoon,
					l.email_contact,
					l.telefoon,
					l.eerstvolgende_levering
				ORDER BY l.eerstvolgende_levering ASC, l.bedrijfsnaam ASC
			';

			$statement = $this->db->query($sql);

			return $statement->fetchAll();
		}
	}

	public function existsByBedrijfsnaam(string $bedrijfsnaam): bool
	{
		$statement = $this->db->prepare('SELECT COUNT(*) FROM leveranciers WHERE bedrijfsnaam = ?');
		$statement->execute([$bedrijfsnaam]);

		return (int)$statement->fetchColumn() > 0;
	}

	public function create(array $data): int
	{
		try {
			$statement = $this->db->prepare('CALL sp_leverancier_toevoegen(?, ?, ?, ?, ?, ?, ?, ?)');
			$statement->execute([
				$data['bedrijfsnaam'],
				$data['adres'],
				$data['postcode'],
				$data['plaats'],
				$data['contactpersoon'],
				$data['email_contact'],
				$data['telefoon'],
				$data['eerstvolgende_levering'],
			]);
			$statement->closeCursor();

			return (int)$this->db->lastInsertId();
		} catch (Throwable $exception) {
			$sql = '
				INSERT INTO leveranciers (
					bedrijfsnaam,
					adres,
					postcode,
					plaats,
					contactpersoon,
					email_contact,
					telefoon,
					eerstvolgende_levering
				)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)
			';

			$statement = $this->db->prepare($sql);
			$statement->execute([
				$data['bedrijfsnaam'],
				$data['adres'],
				$data['postcode'],
				$data['plaats'],
				$data['contactpersoon'],
				$data['email_contact'],
				$data['telefoon'],
				$data['eerstvolgende_levering'],
			]);

			return (int)$this->db->lastInsertId();
		}
	}
}

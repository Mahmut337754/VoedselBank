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
		$sql = '
			SELECT
				leverancier_id,
				bedrijfsnaam,
				adres,
				postcode,
				plaats,
				contactpersoon,
				email_contact,
				telefoon,
				eerstvolgende_levering
			FROM leveranciers
			ORDER BY eerstvolgende_levering ASC, bedrijfsnaam ASC
		';

		$statement = $this->db->query($sql);

		return $statement->fetchAll();
	}
}

-- ============================================================
-- stored_procedures.sql - Dag 3
-- Importeer dit NA VoedselbankSql_dag3.sql
-- ============================================================

USE voedselbankmaaskantje;

DROP PROCEDURE IF EXISTS sp_get_all_voorraad;
DROP PROCEDURE IF EXISTS sp_update_voorraad;
DROP PROCEDURE IF EXISTS sp_get_all_leveranciers;
DROP PROCEDURE IF EXISTS sp_get_leveranciers_by_type;
DROP PROCEDURE IF EXISTS sp_get_producten_by_leverancier;
DROP PROCEDURE IF EXISTS sp_update_houdbaarheids_datum;

DELIMITER $$

-- ============================================================
-- Voorraad: alle producten ophalen
-- ============================================================
CREATE PROCEDURE sp_get_all_voorraad()
BEGIN
    SELECT p.id, p.naam, c.naam AS categorie, p.soort_allergie, p.barcode,
           p.houdbaarheids_datum, p.omschrijving, p.status,
           m.verpakkings_eenheid, m.aantal, ppm.locatie
    FROM product p
    LEFT JOIN categorie c ON p.categorie_id = c.id
    LEFT JOIN product_per_magazijn ppm ON p.id = ppm.product_id
    LEFT JOIN magazijn m ON ppm.magazijn_id = m.id
    ORDER BY c.naam ASC, p.naam ASC;
END$$

-- ============================================================
-- Voorraad: update aantal en status
-- ============================================================
CREATE PROCEDURE sp_update_voorraad(
    IN p_id     INT,
    IN p_status VARCHAR(50),
    IN p_aantal INT
)
BEGIN
    UPDATE magazijn m
    INNER JOIN product_per_magazijn ppm ON m.id = ppm.magazijn_id
    SET m.aantal = p_aantal, m.datum_gewijzigd = NOW()
    WHERE ppm.product_id = p_id;

    UPDATE product
    SET status = p_status, datum_gewijzigd = NOW()
    WHERE id = p_id;
END$$

-- ============================================================
-- Leverancier: alle leveranciers ophalen
-- ============================================================
CREATE PROCEDURE sp_get_all_leveranciers()
BEGIN
    SELECT l.id, l.naam, l.contact_persoon, l.leverancier_nummer, l.leverancier_type,
           c.straat, c.huisnummer, c.toevoeging, c.postcode, c.woonplaats, c.email, c.mobiel
    FROM leverancier l
    LEFT JOIN contact_per_leverancier cpl ON l.id = cpl.leverancier_id
    LEFT JOIN contact c ON cpl.contact_id = c.id
    WHERE l.is_actief = 1
    ORDER BY l.naam ASC;
END$$

-- ============================================================
-- Leverancier: filteren op type
-- ============================================================
CREATE PROCEDURE sp_get_leveranciers_by_type(
    IN p_type VARCHAR(50)
)
BEGIN
    SELECT l.id, l.naam, l.contact_persoon, l.leverancier_nummer, l.leverancier_type,
           c.straat, c.huisnummer, c.toevoeging, c.postcode, c.woonplaats, c.email, c.mobiel
    FROM leverancier l
    LEFT JOIN contact_per_leverancier cpl ON l.id = cpl.leverancier_id
    LEFT JOIN contact c ON cpl.contact_id = c.id
    WHERE l.is_actief = 1
      AND l.leverancier_type = p_type
    ORDER BY l.naam ASC;
END$$

-- ============================================================
-- Leverancier: producten van een leverancier ophalen
-- ============================================================
CREATE PROCEDURE sp_get_producten_by_leverancier(
    IN p_leverancier_id INT
)
BEGIN
    SELECT p.id, p.naam, p.houdbaarheids_datum, p.barcode, p.soort_allergie,
           p.omschrijving, p.status,
           cat.naam AS categorie,
           ppl.datum_aangeleverd,
           ppl.datum_eerst_volgende_levering
    FROM product p
    INNER JOIN product_per_leverancier ppl ON p.id = ppl.product_id
    LEFT JOIN categorie cat ON p.categorie_id = cat.id
    WHERE ppl.leverancier_id = p_leverancier_id
      AND p.is_actief = 1
    ORDER BY p.naam ASC;
END$$

-- ============================================================
-- Leverancier: houdbaarheidsdatum bijwerken
-- ============================================================
CREATE PROCEDURE sp_update_houdbaarheids_datum(
    IN p_product_id   INT,
    IN p_nieuwe_datum DATE
)
BEGIN
    UPDATE product
    SET houdbaarheids_datum = p_nieuwe_datum,
        datum_gewijzigd     = NOW()
    WHERE id = p_product_id;
END$$

DELIMITER ;

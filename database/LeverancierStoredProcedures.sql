-- =====================================================================
-- STORED PROCEDURES: Leveranciers
-- Past bij de methodes in app/models/Leverancier.php
-- =====================================================================

USE VoedselbankSql_dag2;

DROP PROCEDURE IF EXISTS sp_leveranciers_overzicht;
DROP PROCEDURE IF EXISTS sp_leverancier_ophalen;
DROP PROCEDURE IF EXISTS sp_leverancier_toevoegen;
DROP PROCEDURE IF EXISTS sp_leverancier_wijzigen;
DROP PROCEDURE IF EXISTS sp_leverancier_verwijderen;

DELIMITER $$

CREATE PROCEDURE sp_leveranciers_overzicht()
BEGIN
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
    ORDER BY l.eerstvolgende_levering ASC, l.bedrijfsnaam ASC;
END $$

CREATE PROCEDURE sp_leverancier_ophalen(
    IN p_leverancier_id INT UNSIGNED
)
BEGIN
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
    WHERE l.leverancier_id = p_leverancier_id
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
    LIMIT 1;
END $$

CREATE PROCEDURE sp_leverancier_toevoegen(
    IN p_bedrijfsnaam VARCHAR(150),
    IN p_adres VARCHAR(150),
    IN p_postcode VARCHAR(10),
    IN p_plaats VARCHAR(80),
    IN p_contactpersoon VARCHAR(100),
    IN p_email_contact VARCHAR(100),
    IN p_telefoon VARCHAR(20),
    IN p_eerstvolgende_levering DATETIME
)
BEGIN
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
    VALUES (
        p_bedrijfsnaam,
        p_adres,
        p_postcode,
        p_plaats,
        p_contactpersoon,
        p_email_contact,
        p_telefoon,
        p_eerstvolgende_levering
    );

    SELECT LAST_INSERT_ID() AS leverancier_id;
END $$

CREATE PROCEDURE sp_leverancier_wijzigen(
    IN p_leverancier_id INT UNSIGNED,
    IN p_bedrijfsnaam VARCHAR(150),
    IN p_adres VARCHAR(150),
    IN p_postcode VARCHAR(10),
    IN p_plaats VARCHAR(80),
    IN p_contactpersoon VARCHAR(100),
    IN p_email_contact VARCHAR(100),
    IN p_telefoon VARCHAR(20),
    IN p_eerstvolgende_levering DATETIME
)
BEGIN
    UPDATE leveranciers
    SET
        bedrijfsnaam = p_bedrijfsnaam,
        adres = p_adres,
        postcode = p_postcode,
        plaats = p_plaats,
        contactpersoon = p_contactpersoon,
        email_contact = p_email_contact,
        telefoon = p_telefoon,
        eerstvolgende_levering = p_eerstvolgende_levering
    WHERE leverancier_id = p_leverancier_id;

    SELECT ROW_COUNT() AS affected_rows;
END $$

CREATE PROCEDURE sp_leverancier_verwijderen(
    IN p_leverancier_id INT UNSIGNED
)
BEGIN
    DECLARE v_aantal_leveringen INT DEFAULT 0;

    SELECT COUNT(le.levering_id)
    INTO v_aantal_leveringen
    FROM leveranciers l
    LEFT JOIN leveringen le ON le.leverancier_id = l.leverancier_id
    WHERE l.leverancier_id = p_leverancier_id;

    IF v_aantal_leveringen > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Leverancier kan niet worden verwijderd: er zijn nog gekoppelde leveringen.';
    END IF;

    DELETE FROM leveranciers
    WHERE leverancier_id = p_leverancier_id;

    SELECT ROW_COUNT() AS affected_rows;
END $$

DELIMITER ;

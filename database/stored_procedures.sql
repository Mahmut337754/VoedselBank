-- =====================================================================
-- STORED PROCEDURES: VoedselbankSql_dag2
-- =====================================================================

USE VoedselbankSql_dag2;

DELIMITER $

-- ------------------------------------------------------------
-- WENSEN (Allergieën)
-- ------------------------------------------------------------

-- Haal alle wensen op met het aantal gekoppelde klanten
CREATE PROCEDURE sp_GetAllWensen()
BEGIN
    SELECT
        w.wens_id,
        w.naam,
        w.omschrijving,
        COUNT(kw.klant_id) AS klant_count
    FROM wensen w
    LEFT JOIN klantwensen kw ON kw.wens_id = w.wens_id
    GROUP BY w.wens_id, w.naam, w.omschrijving
    ORDER BY w.wens_id;
END$

-- Haal één wens op via ID
CREATE PROCEDURE sp_GetWensById(IN p_wens_id INT UNSIGNED)
BEGIN
    SELECT wens_id, naam, omschrijving
    FROM wensen
    WHERE wens_id = p_wens_id;
END$

-- Maak een nieuwe wens aan
CREATE PROCEDURE sp_CreateWens(
    IN p_naam         VARCHAR(100),
    IN p_omschrijving VARCHAR(255)
)
BEGIN
    IF EXISTS (SELECT 1 FROM wensen WHERE naam = p_naam) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Een wens met deze naam bestaat al.';
    END IF;

    INSERT INTO wensen (naam, omschrijving)
    VALUES (p_naam, NULLIF(p_omschrijving, ''));
END$

-- Werk een bestaande wens bij
CREATE PROCEDURE sp_UpdateWens(
    IN p_wens_id      INT UNSIGNED,
    IN p_naam         VARCHAR(100),
    IN p_omschrijving VARCHAR(255)
)
BEGIN
    IF EXISTS (
        SELECT 1 FROM wensen
        WHERE naam = p_naam AND wens_id != p_wens_id
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Een andere wens met deze naam bestaat al.';
    END IF;

    UPDATE wensen
    SET naam         = p_naam,
        omschrijving = NULLIF(p_omschrijving, '')
    WHERE wens_id = p_wens_id;
END$

-- Verwijder een wens (alleen als niet in gebruik)
CREATE PROCEDURE sp_DeleteWens(IN p_wens_id INT UNSIGNED)
BEGIN
    IF EXISTS (SELECT 1 FROM klantwensen WHERE wens_id = p_wens_id) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Wens is nog gekoppeld aan klanten en kan niet worden verwijderd.';
    END IF;

    DELETE FROM wensen WHERE wens_id = p_wens_id;
END$

-- Haal alle klanten op die een bepaalde wens hebben
CREATE PROCEDURE sp_GetKlantenByWensId(IN p_wens_id INT UNSIGNED)
BEGIN
    SELECT
        k.klant_id,
        k.gezinsnaam,
        k.email,
        k.telefoon
    FROM klanten k
    INNER JOIN klantwensen kw ON kw.klant_id = k.klant_id
    WHERE kw.wens_id = p_wens_id
    ORDER BY k.gezinsnaam;
END$

-- ------------------------------------------------------------
-- KLANTEN
-- ------------------------------------------------------------

-- Haal alle klanten op met aantal wensen
CREATE PROCEDURE sp_GetAllKlanten()
BEGIN
    SELECT
        k.klant_id,
        k.gezinsnaam,
        k.telefoon,
        k.email,
        k.adres,
        k.postcode,
        k.plaats,
        k.aantal_volwassenen,
        k.aantal_kinderen,
        k.aantal_babys,
        COUNT(DISTINCT kw.wens_id) AS aantal_wensen
    FROM klanten k
    LEFT JOIN klantwensen kw ON kw.klant_id = k.klant_id
    GROUP BY k.klant_id
    ORDER BY k.gezinsnaam;
END$

-- Haal één klant op via ID (inclusief gekoppelde wensen)
CREATE PROCEDURE sp_GetKlantById(IN p_klant_id INT UNSIGNED)
BEGIN
    SELECT
        k.klant_id,
        k.gezinsnaam,
        k.telefoon,
        k.email,
        k.adres,
        k.postcode,
        k.plaats,
        k.aantal_volwassenen,
        k.aantal_kinderen,
        k.aantal_babys
    FROM klanten k
    WHERE k.klant_id = p_klant_id;

    SELECT w.wens_id, w.naam, kw.opmerking
    FROM klantwensen kw
    INNER JOIN wensen w ON w.wens_id = kw.wens_id
    WHERE kw.klant_id = p_klant_id;
END$

-- Maak een nieuwe klant aan
CREATE PROCEDURE sp_CreateKlant(
    IN p_gezinsnaam         VARCHAR(100),
    IN p_telefoon           VARCHAR(20),
    IN p_email              VARCHAR(100),
    IN p_adres              VARCHAR(150),
    IN p_postcode           VARCHAR(10),
    IN p_plaats             VARCHAR(80),
    IN p_aantal_volwassenen TINYINT UNSIGNED,
    IN p_aantal_kinderen    TINYINT UNSIGNED,
    IN p_aantal_babys       TINYINT UNSIGNED
)
BEGIN
    IF EXISTS (SELECT 1 FROM klanten WHERE email = p_email) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Een klant met dit e-mailadres bestaat al.';
    END IF;

    INSERT INTO klanten (
        gezinsnaam, telefoon, email, adres, postcode, plaats,
        aantal_volwassenen, aantal_kinderen, aantal_babys
    ) VALUES (
        p_gezinsnaam, p_telefoon, p_email, p_adres, p_postcode, p_plaats,
        p_aantal_volwassenen, p_aantal_kinderen, p_aantal_babys
    );

    SELECT LAST_INSERT_ID() AS klant_id;
END$

-- Werk een bestaande klant bij
CREATE PROCEDURE sp_UpdateKlant(
    IN p_klant_id           INT UNSIGNED,
    IN p_gezinsnaam         VARCHAR(100),
    IN p_telefoon           VARCHAR(20),
    IN p_email              VARCHAR(100),
    IN p_adres              VARCHAR(150),
    IN p_postcode           VARCHAR(10),
    IN p_plaats             VARCHAR(80),
    IN p_aantal_volwassenen TINYINT UNSIGNED,
    IN p_aantal_kinderen    TINYINT UNSIGNED,
    IN p_aantal_babys       TINYINT UNSIGNED
)
BEGIN
    IF EXISTS (
        SELECT 1 FROM klanten
        WHERE email = p_email AND klant_id != p_klant_id
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Een andere klant met dit e-mailadres bestaat al.';
    END IF;

    UPDATE klanten
    SET gezinsnaam         = p_gezinsnaam,
        telefoon           = p_telefoon,
        email              = p_email,
        adres              = p_adres,
        postcode           = p_postcode,
        plaats             = p_plaats,
        aantal_volwassenen = p_aantal_volwassenen,
        aantal_kinderen    = p_aantal_kinderen,
        aantal_babys       = p_aantal_babys
    WHERE klant_id = p_klant_id;
END$

-- Verwijder een klant (alleen als geen actieve pakketten)
CREATE PROCEDURE sp_DeleteKlant(IN p_klant_id INT UNSIGNED)
BEGIN
    IF EXISTS (
        SELECT 1 FROM voedselpakketten
        WHERE klant_id = p_klant_id AND opgehaald = FALSE
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Klant heeft nog niet-opgehaalde pakketten en kan niet worden verwijderd.';
    END IF;

    DELETE FROM klanten WHERE klant_id = p_klant_id;
END$

-- ------------------------------------------------------------
-- PRODUCTEN / VOORRAAD
-- ------------------------------------------------------------

-- Haal alle producten op met categorienaam
CREATE PROCEDURE sp_GetAllProducten()
BEGIN
    SELECT
        p.product_id,
        p.productnaam,
        c.naam AS categorie,
        p.ean,
        p.aantal_op_voorraad
    FROM producten p
    INNER JOIN `categorieën` c ON c.categorie_id = p.categorie_id
    ORDER BY p.productnaam;
END$

-- Haal één product op via ID
CREATE PROCEDURE sp_GetProductById(IN p_product_id INT UNSIGNED)
BEGIN
    SELECT
        p.product_id,
        p.productnaam,
        p.categorie_id,
        c.naam AS categorie,
        p.ean,
        p.aantal_op_voorraad
    FROM producten p
    INNER JOIN `categorieën` c ON c.categorie_id = p.categorie_id
    WHERE p.product_id = p_product_id;
END$

-- Maak een nieuw product aan
CREATE PROCEDURE sp_CreateProduct(
    IN p_productnaam        VARCHAR(150),
    IN p_categorie_id       INT UNSIGNED,
    IN p_ean                CHAR(13),
    IN p_aantal_op_voorraad INT
)
BEGIN
    IF EXISTS (SELECT 1 FROM producten WHERE ean = p_ean) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Een product met dit EAN-nummer bestaat al.';
    END IF;

    INSERT INTO producten (productnaam, categorie_id, ean, aantal_op_voorraad)
    VALUES (p_productnaam, p_categorie_id, p_ean, p_aantal_op_voorraad);

    SELECT LAST_INSERT_ID() AS product_id;
END$

-- Werk een bestaand product bij
CREATE PROCEDURE sp_UpdateProduct(
    IN p_product_id         INT UNSIGNED,
    IN p_productnaam        VARCHAR(150),
    IN p_categorie_id       INT UNSIGNED,
    IN p_ean                CHAR(13),
    IN p_aantal_op_voorraad INT
)
BEGIN
    IF EXISTS (
        SELECT 1 FROM producten
        WHERE ean = p_ean AND product_id != p_product_id
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Een ander product met dit EAN-nummer bestaat al.';
    END IF;

    UPDATE producten
    SET productnaam        = p_productnaam,
        categorie_id       = p_categorie_id,
        ean                = p_ean,
        aantal_op_voorraad = p_aantal_op_voorraad
    WHERE product_id = p_product_id;
END$

-- Verwijder een product (alleen als niet in pakketten)
CREATE PROCEDURE sp_DeleteProduct(IN p_product_id INT UNSIGNED)
BEGIN
    IF EXISTS (SELECT 1 FROM pakketproducten WHERE product_id = p_product_id) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Product zit nog in een voedselpakket en kan niet worden verwijderd.';
    END IF;

    DELETE FROM producten WHERE product_id = p_product_id;
END$

-- Pas voorraad aan (positief = aanvullen, negatief = afboeken)
CREATE PROCEDURE sp_UpdateVoorraad(
    IN p_product_id INT UNSIGNED,
    IN p_mutatie    INT
)
BEGIN
    DECLARE v_huidig INT;

    SELECT aantal_op_voorraad INTO v_huidig
    FROM producten
    WHERE product_id = p_product_id;

    IF (v_huidig + p_mutatie) < 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Voorraad kan niet negatief worden.';
    END IF;

    UPDATE producten
    SET aantal_op_voorraad = aantal_op_voorraad + p_mutatie
    WHERE product_id = p_product_id;
END$

-- ------------------------------------------------------------
-- VOEDSELPAKKETTEN
-- ------------------------------------------------------------

-- Haal alle pakketten op met klantnaam en productaantal
CREATE PROCEDURE sp_GetAllPakketten()
BEGIN
    SELECT
        vp.pakket_id,
        k.gezinsnaam,
        vp.datum_samenstelling,
        vp.datum_uitgifte,
        vp.opgehaald,
        COUNT(pp.product_id) AS aantal_producten
    FROM voedselpakketten vp
    INNER JOIN klanten k ON k.klant_id = vp.klant_id
    LEFT JOIN pakketproducten pp ON pp.pakket_id = vp.pakket_id
    GROUP BY vp.pakket_id
    ORDER BY vp.datum_samenstelling DESC;
END$

-- Haal één pakket op met alle producten
CREATE PROCEDURE sp_GetPakketById(IN p_pakket_id INT UNSIGNED)
BEGIN
    SELECT
        vp.pakket_id,
        vp.klant_id,
        k.gezinsnaam,
        vp.datum_samenstelling,
        vp.datum_uitgifte,
        vp.opgehaald
    FROM voedselpakketten vp
    INNER JOIN klanten k ON k.klant_id = vp.klant_id
    WHERE vp.pakket_id = p_pakket_id;

    SELECT
        p.product_id,
        p.productnaam,
        pp.aantal
    FROM pakketproducten pp
    INNER JOIN producten p ON p.product_id = pp.product_id
    WHERE pp.pakket_id = p_pakket_id;
END$

-- Maak een nieuw voedselpakket aan
CREATE PROCEDURE sp_CreatePakket(
    IN p_klant_id            INT UNSIGNED,
    IN p_datum_samenstelling DATE,
    IN p_datum_uitgifte      DATE
)
BEGIN
    INSERT INTO voedselpakketten (klant_id, datum_samenstelling, datum_uitgifte, opgehaald)
    VALUES (p_klant_id, p_datum_samenstelling, p_datum_uitgifte, FALSE);

    SELECT LAST_INSERT_ID() AS pakket_id;
END$

-- Markeer pakket als opgehaald of niet
CREATE PROCEDURE sp_SetPakketOpgehaald(
    IN p_pakket_id INT UNSIGNED,
    IN p_opgehaald BOOLEAN
)
BEGIN
    UPDATE voedselpakketten
    SET opgehaald = p_opgehaald
    WHERE pakket_id = p_pakket_id;
END$

-- Verwijder een pakket (alleen als nog niet opgehaald)
CREATE PROCEDURE sp_DeletePakket(IN p_pakket_id INT UNSIGNED)
BEGIN
    IF EXISTS (
        SELECT 1 FROM voedselpakketten
        WHERE pakket_id = p_pakket_id AND opgehaald = TRUE
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Opgehaalde pakketten kunnen niet worden verwijderd.';
    END IF;

    DELETE FROM voedselpakketten WHERE pakket_id = p_pakket_id;
END$

DELIMITER ;

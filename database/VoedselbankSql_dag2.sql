-- =====================================================================
-- DATABASE: VoedselbankSql_dag2
-- =====================================================================

DROP DATABASE IF EXISTS VoedselbankSql_dag2;
CREATE DATABASE VoedselbankSql_dag2
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE VoedselbankSql_dag2;

CREATE TABLE `categorieën` (
    categorie_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    naam           VARCHAR(100) NOT NULL,
    omschrijving   VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (categorie_id),
    UNIQUE KEY uk_categorie_naam (naam)
) ENGINE=InnoDB;

CREATE TABLE wensen (
    wens_id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    naam           VARCHAR(100) NOT NULL,
    omschrijving   VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (wens_id),
    UNIQUE KEY uk_wens_naam (naam)
) ENGINE=InnoDB;

CREATE TABLE klanten (
    klant_id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    gezinsnaam          VARCHAR(100) NOT NULL,
    telefoon            VARCHAR(20)  NOT NULL,
    email               VARCHAR(100) NOT NULL,
    adres               VARCHAR(150) NOT NULL,
    postcode            VARCHAR(10)  NOT NULL,
    plaats              VARCHAR(80)  NOT NULL,
    aantal_volwassenen  TINYINT UNSIGNED NOT NULL DEFAULT 1,
    aantal_kinderen     TINYINT UNSIGNED NOT NULL DEFAULT 0,
    aantal_babys        TINYINT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (klant_id),
    UNIQUE KEY uk_klant_email (email)
) ENGINE=InnoDB;

CREATE TABLE klantwensen (
    klant_id    INT UNSIGNED NOT NULL,
    wens_id     INT UNSIGNED NOT NULL,
    opmerking   VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (klant_id, wens_id),
    FOREIGN KEY (klant_id) REFERENCES klanten(klant_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (wens_id)  REFERENCES wensen(wens_id)   ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE leveranciers (
    leverancier_id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    bedrijfsnaam             VARCHAR(150) NOT NULL,
    adres                    VARCHAR(150) NOT NULL,
    postcode                 VARCHAR(10)  NOT NULL,
    plaats                   VARCHAR(80)  NOT NULL,
    contactpersoon           VARCHAR(100) NOT NULL,
    email_contact            VARCHAR(100) NOT NULL,
    telefoon                 VARCHAR(20)  NOT NULL,
    eerstvolgende_levering   DATETIME     NOT NULL,
    PRIMARY KEY (leverancier_id),
    UNIQUE KEY uk_leverancier_email (email_contact)
) ENGINE=InnoDB;

CREATE TABLE `categorieën_ref` (
    categorie_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (categorie_id)
) ENGINE=InnoDB;

CREATE TABLE producten (
    product_id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    productnaam        VARCHAR(150) NOT NULL,
    categorie_id       INT UNSIGNED NOT NULL,
    ean                CHAR(13)     NOT NULL,
    aantal_op_voorraad INT NOT NULL DEFAULT 0,
    PRIMARY KEY (product_id),
    UNIQUE KEY uk_product_naam (productnaam),
    UNIQUE KEY uk_product_ean (ean),
    FOREIGN KEY (categorie_id) REFERENCES `categorieën`(categorie_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE voedselpakketten (
    pakket_id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    klant_id             INT UNSIGNED NOT NULL,
    datum_samenstelling  DATE NOT NULL,
    datum_uitgifte       DATE DEFAULT NULL,
    opgehaald            BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (pakket_id),
    FOREIGN KEY (klant_id) REFERENCES klanten(klant_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pakketproducten (
    pakket_id    INT UNSIGNED NOT NULL,
    product_id   INT UNSIGNED NOT NULL,
    aantal       SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (pakket_id, product_id),
    FOREIGN KEY (pakket_id)  REFERENCES voedselpakketten(pakket_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES producten(product_id)       ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE leveringen (
    levering_id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    leverancier_id INT UNSIGNED NOT NULL,
    product_id     INT UNSIGNED NOT NULL,
    hoeveelheid    INT UNSIGNED NOT NULL,
    leverdatum     DATE NOT NULL,
    PRIMARY KEY (levering_id),
    FOREIGN KEY (leverancier_id) REFERENCES leveranciers(leverancier_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (product_id)     REFERENCES producten(product_id)        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE rollen (
    rol_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    rolnaam  VARCHAR(50) NOT NULL,
    PRIMARY KEY (rol_id),
    UNIQUE KEY uk_rol_naam (rolnaam)
) ENGINE=InnoDB;

CREATE TABLE gebruikers (
    gebruiker_id     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    gebruikersnaam   VARCHAR(50) NOT NULL,
    wachtwoord_hash  VARCHAR(255) NOT NULL,
    rol_id           INT UNSIGNED NOT NULL,
    actief           BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (gebruiker_id),
    UNIQUE KEY uk_gebruikersnaam (gebruikersnaam),
    FOREIGN KEY (rol_id) REFERENCES rollen(rol_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================================
-- SEEDDATA
-- =====================================================================

INSERT INTO `categorieën` (naam, omschrijving) VALUES
('Aardappelen, groente, fruit', 'Verse en houdbare groenten, fruit en aardappelen'),
('Kaas, vleeswaren', 'Kaas, vleeswaren en vleesvervangers'),
('Zuivel, plantaardig en eieren', 'Melk, yoghurt, plantaardige alternatieven, eieren'),
('Bakkerij en banket', 'Brood, beschuit, koek, banket'),
('Frisdrank, sappen, koffie en thee', 'Niet-alcoholische dranken, koffie, thee'),
('Pasta, rijst en wereldkeuken', 'Pasta, rijst, noedels, wereldgerechten'),
('Soepen, sauzen, kruiden en olie', 'Soepen in blik/pak, sauzen, kruiden, bakolie'),
('Snoep, koek, chips en chocolade', 'Zoetigheden, hartige snacks, chocolade'),
('Baby, verzorging en hygiene', 'Babypoeder, luiers, zeep, shampoo');

INSERT INTO wensen (naam, omschrijving) VALUES
('Geen varkensvlees', 'Geen varkensvlees of producten met varkensgelatine'),
('Glutenallergie', 'Allergie voor gluten (coeliakie)'),
('Pinda-allergie', 'Ernstige allergie voor pindas'),
('Schaaldierenallergie', 'Allergie voor garnalen, krab, etc.'),
('Hazelnotenallergie', 'Allergie voor hazelnoten en notenmengsels'),
('Lactose-intolerantie', 'Kan lactose-bevattende producten niet verdragen'),
('Veganistisch', 'Geen dierlijke producten'),
('Vegetarisch', 'Geen vlees of vis, wel zuivel/eieren'),
('Overige', 'Handmatig ingevulde wens/allergie');

INSERT INTO klanten (gezinsnaam, telefoon, email, adres, postcode, plaats, aantal_volwassenen, aantal_kinderen, aantal_babys) VALUES
('Jansen', '0612345678', 'jansen@example.com', 'Hoofdstraat 12', '5271AN', 'Maaskantje', 2, 2, 0),
('De Vries', '0687654321', 'devries@example.com', 'Kerkplein 4', '5271BB', 'Maaskantje', 1, 1, 1),
('Bakker', '0611122233', 'bakker@example.com', 'Molenweg 8', '5271CC', 'Maaskantje', 2, 0, 0),
('Peters', '0699988776', 'peters@example.com', 'Schoolstraat 21', '5271DD', 'Maaskantje', 1, 3, 0),
('Maassen', '0655544433', 'maassen@example.com', 'Dorpsplein 5', '5271EE', 'Maaskantje', 2, 1, 1);

INSERT INTO klantwensen (klant_id, wens_id, opmerking) VALUES
(1, 2, NULL),(1, 6, NULL),(2, 3, 'Zeer ernstig'),(2, 7, NULL),
(3, 8, NULL),(4, 9, 'Allergie voor tomaten'),(5, 1, NULL);

INSERT INTO leveranciers (bedrijfsnaam, adres, postcode, plaats, contactpersoon, email_contact, telefoon, eerstvolgende_levering) VALUES
('Vers&Zo B.V.', 'Industrieweg 10', '5201AA', 'Den Bosch', 'J. van Dijk', 'inkoop@versenzo.nl', '073-1234567', '2025-05-20 09:00:00'),
('De Graanschuur', 'Korenmolen 3', '5271AX', 'Maaskantje', 'P. Bakker', 'info@graanschuur.nl', '0413-321654', '2025-05-22 13:30:00'),
('Lekker Nederlands', 'Zuivelstraat 22', '5211BB', 'Den Bosch', 'R. Jansen', 'robert@lekkernederlands.nl', '073-9876543', '2025-05-18 08:15:00'),
('Wereldkeuken BV', 'Havenkade 5', '5222CC', 'Rosmalen', 'L. Wong', 'l.wong@wereldkeuken.nl', '073-5556667', '2025-05-25 11:00:00'),
('Zuidwind Frisdranken', 'Drankenweg 1', '5233DD', 'Vught', 'T. Verhoeven', 't.verhoeven@zuidwind.nl', '073-8889990', '2025-05-19 14:45:00');

INSERT INTO producten (productnaam, categorie_id, ean, aantal_op_voorraad) VALUES
('Aardappelen kruimig 2kg', 1, '8712345678901', 150),
('Broccoli vers', 1, '8712345678918', 85),
('Halfvolle melk 1L', 3, '8712345678925', 200),
('Volkorenbrood', 4, '8712345678932', 45),
('Pindakaas', 2, '8712345678949', 60),
('Kipfilet kookworst', 2, '8712345678956', 0),
('Rijst basmati 1kg', 6, '8712345678963', 120),
('Tomatensoep blik', 7, '8712345678970', 90),
('Appelsap 1L', 5, '8712345678987', 110),
('Chocoladerepen (6x)', 8, '8712345678994', 300),
('Luiers maat 4 (30 st)', 9, '8712345679007', 75),
('Koffiebonen 500g', 5, '8712345679014', 40);

INSERT INTO voedselpakketten (klant_id, datum_samenstelling, datum_uitgifte, opgehaald) VALUES
(1,'2025-04-01','2025-04-02',TRUE),(2,'2025-04-03','2025-04-05',TRUE),
(3,'2025-04-10','2025-04-11',FALSE),(4,'2025-04-15',NULL,FALSE),(5,'2025-04-18','2025-04-19',TRUE);

INSERT INTO pakketproducten (pakket_id, product_id, aantal) VALUES
(1,1,2),(1,2,1),(1,3,2),(1,4,2),(1,9,1),
(2,3,1),(2,6,1),(2,8,2),(2,10,3),(2,12,1),
(3,1,1),(3,4,1),(3,7,1),(3,11,1),
(4,2,1),(4,5,1),(4,9,2),(4,10,2),
(5,1,2),(5,3,2),(5,7,2),(5,8,1),(5,11,1);

INSERT INTO leveringen (leverancier_id, product_id, hoeveelheid, leverdatum) VALUES
(1,1,500,'2025-04-10'),(1,2,300,'2025-04-10'),(2,4,200,'2025-04-12'),
(2,7,400,'2025-04-12'),(3,3,600,'2025-04-14'),(3,5,250,'2025-04-14'),
(4,8,350,'2025-04-16'),(4,9,500,'2025-04-16'),(5,10,800,'2025-04-18'),(5,12,150,'2025-04-18');

INSERT INTO rollen (rolnaam) VALUES ('Directie'),('Magazijnmedewerker'),('Vrijwilliger');

-- Wachtwoord voor alle accounts: Welkom123
-- Hash gegenereerd met password_hash('Welkom123', PASSWORD_BCRYPT)
INSERT INTO gebruikers (gebruikersnaam, wachtwoord_hash, rol_id, actief) VALUES
('directie_anne',      '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, TRUE),
('magazijn_piet',      '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, TRUE),
('vrijwilliger_sanne', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, TRUE);
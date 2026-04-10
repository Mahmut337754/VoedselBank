-- ============================================================
-- VoedselbankMaaskantje.sql - Dag 3
-- Tabellen + testdata (GEEN stored procedures - zie stored_procedures.sql)
-- ============================================================

CREATE DATABASE IF NOT EXISTS voedselbankmaaskantje
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE voedselbankmaaskantje;

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- DROP alle tabellen
-- ============================================================
DROP TABLE IF EXISTS product_per_voedselpakket;
DROP TABLE IF EXISTS product_per_magazijn;
DROP TABLE IF EXISTS product_per_leverancier;
DROP TABLE IF EXISTS contact_per_leverancier;
DROP TABLE IF EXISTS contact_per_gezin;
DROP TABLE IF EXISTS eetwens_per_gezin;
DROP TABLE IF EXISTS allergie_per_persoon;
DROP TABLE IF EXISTS rol_per_gebruiker;
DROP TABLE IF EXISTS voedselpakket;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS persoon;
DROP TABLE IF EXISTS magazijn;
DROP TABLE IF EXISTS leverancier;
DROP TABLE IF EXISTS gezin;
DROP TABLE IF EXISTS contact;
DROP TABLE IF EXISTS rol;
DROP TABLE IF EXISTS eetwens;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS allergie;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS password_reset_tokens;
DROP TABLE IF EXISTS users;

-- ============================================================
-- CREATE: users
-- ============================================================
CREATE TABLE users (
    id                  BIGINT UNSIGNED     AUTO_INCREMENT PRIMARY KEY,
    persoon_id          BIGINT UNSIGNED     NULL,
    name                VARCHAR(100)        NOT NULL,
    email               VARCHAR(150)        NOT NULL UNIQUE,
    inlog_naam          VARCHAR(150)        NULL,
    gebruikersnaam      VARCHAR(100)        NULL,
    email_verified_at   TIMESTAMP           NULL,
    password            VARCHAR(255)        NOT NULL,
    rol                 ENUM('manager','medewerker','vrijwilliger') NOT NULL DEFAULT 'medewerker',
    is_ingelogd         TINYINT(1)          NOT NULL DEFAULT 0,
    ingelogd            DATETIME(6)         NULL,
    uitgelogd           DATETIME(6)         NULL,
    remember_token      VARCHAR(100)        NULL,
    created_at          TIMESTAMP           NULL,
    updated_at          TIMESTAMP           NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: password_reset_tokens
-- ============================================================
CREATE TABLE password_reset_tokens (
    email       VARCHAR(150)    NOT NULL PRIMARY KEY,
    token       VARCHAR(255)    NOT NULL,
    created_at  TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: sessions
-- ============================================================
CREATE TABLE sessions (
    id              VARCHAR(255)    NOT NULL PRIMARY KEY,
    user_id         BIGINT UNSIGNED NULL,
    ip_address      VARCHAR(45)     NULL,
    user_agent      TEXT            NULL,
    payload         LONGTEXT        NOT NULL,
    last_activity   INT             NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: allergie
-- ============================================================
CREATE TABLE allergie (
    id                   INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    naam                 VARCHAR(100)    NOT NULL,
    omschrijving         VARCHAR(255)    NULL,
    anafylactisch_risico VARCHAR(50)     NULL,
    is_actief            TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking            VARCHAR(255)    NULL,
    datum_aangemaakt     DATETIME(6)     NULL,
    datum_gewijzigd      DATETIME(6)     NULL,
    created_at           TIMESTAMP       NULL,
    updated_at           TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: categorie
-- ============================================================
CREATE TABLE categorie (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    naam             VARCHAR(10)     NOT NULL,
    omschrijving     VARCHAR(100)    NOT NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: eetwens
-- ============================================================
CREATE TABLE eetwens (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    naam             VARCHAR(50)     NOT NULL,
    omschrijving     VARCHAR(100)    NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: rol
-- ============================================================
CREATE TABLE rol (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    naam             VARCHAR(50)     NOT NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: contact
-- ============================================================
CREATE TABLE contact (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    straat           VARCHAR(100)    NOT NULL,
    huisnummer       VARCHAR(10)     NOT NULL,
    toevoeging       VARCHAR(10)     NULL,
    postcode         VARCHAR(10)     NOT NULL,
    woonplaats       VARCHAR(100)    NOT NULL,
    email            VARCHAR(150)    NULL,
    mobiel           VARCHAR(20)     NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: gezin
-- ============================================================
CREATE TABLE gezin (
    id                     INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    naam                   VARCHAR(100)    NOT NULL,
    code                   VARCHAR(10)     NOT NULL UNIQUE,
    omschrijving           VARCHAR(100)    NULL,
    aantal_volwassenen     INT             NOT NULL DEFAULT 0,
    aantal_kinderen        INT             NOT NULL DEFAULT 0,
    aantal_babys           INT             NOT NULL DEFAULT 0,
    totaal_aantal_personen INT             NOT NULL DEFAULT 0,
    is_actief              TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking              VARCHAR(255)    NULL,
    datum_aangemaakt       DATETIME(6)     NULL,
    datum_gewijzigd        DATETIME(6)     NULL,
    created_at             TIMESTAMP       NULL,
    updated_at             TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: leverancier
-- ============================================================
CREATE TABLE leverancier (
    id                  INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    naam                VARCHAR(100)    NOT NULL,
    contact_persoon     VARCHAR(100)    NULL,
    leverancier_nummer  VARCHAR(20)     NOT NULL UNIQUE,
    leverancier_type    VARCHAR(50)     NULL,
    is_actief           TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking           VARCHAR(255)    NULL,
    datum_aangemaakt    DATETIME(6)     NULL,
    datum_gewijzigd     DATETIME(6)     NULL,
    created_at          TIMESTAMP       NULL,
    updated_at          TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: magazijn
-- ============================================================
CREATE TABLE magazijn (
    id                  INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    ontvangstdatum      DATE            NULL,
    uitleveringsdatum   DATE            NULL,
    verpakkings_eenheid VARCHAR(50)     NULL,
    aantal              INT             NOT NULL DEFAULT 0,
    is_actief           TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking           VARCHAR(255)    NULL,
    datum_aangemaakt    DATETIME(6)     NULL,
    datum_gewijzigd     DATETIME(6)     NULL,
    created_at          TIMESTAMP       NULL,
    updated_at          TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: persoon
-- ============================================================
CREATE TABLE persoon (
    id                   INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    gezin_id             INT UNSIGNED    NULL,
    voornaam             VARCHAR(50)     NOT NULL,
    tussenvoegsel        VARCHAR(20)     NULL,
    achternaam           VARCHAR(100)    NOT NULL,
    geboortedatum        DATE            NULL,
    type_persoon         VARCHAR(50)     NULL,
    is_vertegenwoordiger TINYINT(1)      NOT NULL DEFAULT 0,
    is_actief            TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking            VARCHAR(255)    NULL,
    datum_aangemaakt     DATETIME(6)     NULL,
    datum_gewijzigd      DATETIME(6)     NULL,
    created_at           TIMESTAMP       NULL,
    updated_at           TIMESTAMP       NULL,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: product
-- ============================================================
CREATE TABLE product (
    id                  INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    categorie_id        INT UNSIGNED    NULL,
    naam                VARCHAR(100)    NOT NULL,
    soort_allergie      VARCHAR(50)     NULL,
    barcode             VARCHAR(50)     NULL,
    houdbaarheids_datum DATE            NULL,
    omschrijving        VARCHAR(255)    NULL,
    status              VARCHAR(50)     NOT NULL DEFAULT 'OpVoorraad',
    is_actief           TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking           VARCHAR(255)    NULL,
    datum_aangemaakt    DATETIME(6)     NULL,
    datum_gewijzigd     DATETIME(6)     NULL,
    created_at          TIMESTAMP       NULL,
    updated_at          TIMESTAMP       NULL,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: voedselpakket
-- ============================================================
CREATE TABLE voedselpakket (
    id                  INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    gezin_id            INT UNSIGNED    NULL,
    pakket_nummer       INT             NULL,
    datum_samenstelling DATE            NULL,
    datum_uitgifte      DATE            NULL,
    status              VARCHAR(50)     NOT NULL DEFAULT 'NietUitgereikt',
    is_actief           TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking           VARCHAR(255)    NULL,
    datum_aangemaakt    DATETIME(6)     NULL,
    datum_gewijzigd     DATETIME(6)     NULL,
    created_at          TIMESTAMP       NULL,
    updated_at          TIMESTAMP       NULL,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CREATE: koppeltabellen
-- ============================================================
CREATE TABLE rol_per_gebruiker (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id     BIGINT UNSIGNED NOT NULL,
    rol_id           INT UNSIGNED    NOT NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    FOREIGN KEY (gebruiker_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (rol_id) REFERENCES rol(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE allergie_per_persoon (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    persoon_id       INT UNSIGNED    NOT NULL,
    allergie_id      INT UNSIGNED    NOT NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    FOREIGN KEY (persoon_id) REFERENCES persoon(id) ON DELETE CASCADE,
    FOREIGN KEY (allergie_id) REFERENCES allergie(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE eetwens_per_gezin (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    gezin_id         INT UNSIGNED    NOT NULL,
    eetwens_id       INT UNSIGNED    NOT NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    FOREIGN KEY (eetwens_id) REFERENCES eetwens(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_per_gezin (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    gezin_id         INT UNSIGNED    NOT NULL,
    contact_id       INT UNSIGNED    NOT NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    FOREIGN KEY (gezin_id) REFERENCES gezin(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_per_leverancier (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    leverancier_id   INT UNSIGNED    NOT NULL,
    contact_id       INT UNSIGNED    NOT NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    FOREIGN KEY (leverancier_id) REFERENCES leverancier(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contact(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE product_per_leverancier (
    id                            INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    leverancier_id                INT UNSIGNED    NOT NULL,
    product_id                    INT UNSIGNED    NOT NULL,
    datum_aangeleverd             DATE            NULL,
    datum_eerst_volgende_levering DATE            NULL,
    is_actief                     TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking                     VARCHAR(255)    NULL,
    datum_aangemaakt              DATETIME(6)     NULL,
    datum_gewijzigd               DATETIME(6)     NULL,
    created_at                    TIMESTAMP       NULL,
    updated_at                    TIMESTAMP       NULL,
    FOREIGN KEY (leverancier_id) REFERENCES leverancier(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE product_per_magazijn (
    id               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    product_id       INT UNSIGNED    NOT NULL,
    magazijn_id      INT UNSIGNED    NOT NULL,
    locatie          VARCHAR(100)    NULL,
    is_actief        TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking        VARCHAR(255)    NULL,
    datum_aangemaakt DATETIME(6)     NULL,
    datum_gewijzigd  DATETIME(6)     NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE,
    FOREIGN KEY (magazijn_id) REFERENCES magazijn(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE product_per_voedselpakket (
    id                      INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    voedselpakket_id        INT UNSIGNED    NOT NULL,
    product_id              INT UNSIGNED    NOT NULL,
    aantal_product_eenheden INT             NOT NULL DEFAULT 1,
    is_actief               TINYINT(1)      NOT NULL DEFAULT 1,
    opmerking               VARCHAR(255)    NULL,
    datum_aangemaakt        DATETIME(6)     NULL,
    datum_gewijzigd         DATETIME(6)     NULL,
    created_at              TIMESTAMP       NULL,
    updated_at              TIMESTAMP       NULL,
    FOREIGN KEY (voedselpakket_id) REFERENCES voedselpakket(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- TESTDATA: Gebruikers (wachtwoord: password)
-- ============================================================
INSERT INTO users (name, email, password, rol) VALUES
('Manager',             'manager@voedselbank.nl',     '$2y$12$He93gYfNsKJSxi2YGY9JU.xXqOVoyJDFdJQUAmIimZlost8eOkCZq', 'manager'),
('Magazijn Medewerker', 'medewerker@voedselbank.nl',  '$2y$12$He93gYfNsKJSxi2YGY9JU.xXqOVoyJDFdJQUAmIimZlost8eOkCZq', 'medewerker'),
('Vrijwilliger Jan',    'vrijwilliger@voedselbank.nl','$2y$12$He93gYfNsKJSxi2YGY9JU.xXqOVoyJDFdJQUAmIimZlost8eOkCZq', 'vrijwilliger');

-- ============================================================
-- TESTDATA: Leveranciers
-- ============================================================
INSERT INTO contact (straat, huisnummer, postcode, woonplaats, email, mobiel) VALUES
('Hoofdstraat',     '12', '5388 AA', 'Maaskantje', 'info@ahjansen.nl',        '0612345678'),
('Industrieweg',    '5',  '5388 BB', 'Maaskantje', 'contact@versmarkt.nl',    '0623456789'),
('Boerderijlaan',   '3',  '5388 CC', 'Maaskantje', 'boer@dehoeve.nl',         '0634567890'),
('Supermarktplein', '1',  '5388 DD', 'Maaskantje', 'donaties@plusmarkt.nl',   '0645678901'),
('Raadhuis',        '1',  '5388 EE', 'Maaskantje', 'gemeente@maaskantje.nl',  '0656789012'),
('Kerkstraat',      '7',  '5388 FF', 'Maaskantje', 'info@stichtingvoedsel.nl','0667890123');

INSERT INTO leverancier (naam, contact_persoon, leverancier_nummer, leverancier_type) VALUES
('AH Jansen',          'Jan Jansen',    'LEV-001', 'Bedrijf'),
('Versmarkt De Hoek',  'Maria de Hoek', 'LEV-002', 'Instelling'),
('Boerderij De Hoeve', 'Piet Hoeve',    'LEV-003', 'Overheid'),
('Plus Maaskantje',    'Sandra Plus',   'LEV-004', 'Particulier'),
('Gemeente Maaskantje','Kees de Vries', 'LEV-005', 'Overheid');

INSERT INTO contact_per_leverancier (leverancier_id, contact_id) VALUES
(1, 1), (2, 2), (3, 3), (4, 4), (5, 5);

-- ============================================================
-- TESTDATA: Categorieën
-- ============================================================
INSERT INTO categorie (naam, omschrijving) VALUES
('AGF',  'Aardappelen, groente en fruit'),
('KV',   'Kaas en vleeswaren'),
('ZPE',  'Zuivel, plantaardig en eieren'),
('BB',   'Bakkerij en Banket'),
('FSKT', 'Frisdranken, sappen, koffie en thee'),
('PRW',  'Pasta, rijst en wereldkeuken'),
('SSKO', 'Soepen, sauzen, kruiden en olie'),
('SKCC', 'Snoep, koek, chips en chocolade'),
('BVH',  'Baby, verzorging en hygiëne');

-- ============================================================
-- TESTDATA: Magazijn (29 rijen)
-- ============================================================
INSERT INTO magazijn (id, ontvangstdatum, uitleveringsdatum, verpakkings_eenheid, aantal) VALUES
( 1, '2026-03-12', NULL, '5 kg',       20),
( 2, '2026-04-02', NULL, '2.5 kg',     40),
( 3, '2026-03-16', NULL, '1 kg',       30),
( 4, '2026-04-08', NULL, '1.5 kg',     25),
( 5, '2026-04-06', NULL, '4 stuks',    75),
( 6, '2026-03-12', NULL, '1 kg/tros',  60),
( 7, '2026-03-20', NULL, '2 kg/tros', 200),
( 8, '2026-04-02', NULL, '200 g',      45),
( 9, '2026-04-04', NULL, '100 g',      60),
(10, '2026-04-07', NULL, '1 liter',   120),
(11, '2026-04-01', NULL, '250 g',      80),
(12, '2026-03-18', NULL, '6 stuks',   120),
(13, '2026-03-19', NULL, '800 g',     220),
(14, '2026-03-10', NULL, '1 stuk',    130),
(15, '2026-03-13', NULL, '150 ml',     72),
(16, '2026-03-18', NULL, '1 l',        12),
(17, '2026-03-11', NULL, '250 g',     300),
(18, '2026-04-02', NULL, '25 zakjes', 280),
(19, '2026-04-09', NULL, '500 g',     330),
(20, '2026-04-03', NULL, '1 kg',       34),
(21, '2026-04-02', NULL, '50 g',       23),
(22, '2026-03-16', NULL, '1 l',        46),
(23, '2026-03-14', NULL, '250 ml',     98),
(24, '2026-04-07', NULL, '1 potje',    56),
(25, '2026-03-17', NULL, '1 l',       210),
(26, '2026-04-05', NULL, '4 stuks',    24),
(27, '2026-04-07', NULL, '300 g',      87),
(28, '2026-04-06', NULL, '200 g',     230),
(29, '2026-04-08', NULL, '80 g',       30);

-- ============================================================
-- TESTDATA: Producten (29 rijen)
-- ============================================================
INSERT INTO product (id, categorie_id, naam, soort_allergie, barcode, houdbaarheids_datum, omschrijving, status) VALUES
( 1, 1, 'Aardappel',       NULL,        '8719587321239', '2026-05-12', 'Kruimige aardappel',        'OpVoorraad'),
( 2, 1, 'Aardappel',       NULL,        '8719587321239', '2026-05-26', 'Kruimige aardappel',        'OpVoorraad'),
( 3, 1, 'Ui',              NULL,        '8719437321335', '2026-05-02', 'Gele ui',                   'NietOpVoorraad'),
( 4, 1, 'Appel',           NULL,        '8719486321332', '2026-05-16', 'Granny Smith',              'NietLeverbaar'),
( 5, 1, 'Appel',           NULL,        '8719486321332', '2026-05-23', 'Granny Smith',              'NietLeverbaar'),
( 6, 1, 'Banaan',          'Banaan',    '8719484321336', '2026-05-12', 'Biologische Banaan',        'OverHoudbaarheidsDatum'),
( 7, 1, 'Banaan',          'Banaan',    '8719484321336', '2026-05-19', 'Biologische Banaan',        'OverHoudbaarheidsDatum'),
( 8, 2, 'Kaas',            'Lactose',   '8719487421338', '2026-05-19', 'Jonge Kaas',                'OpVoorraad'),
( 9, 2, 'Rosbief',         NULL,        '8719487421331', '2026-05-23', 'Rundvlees',                 'OpVoorraad'),
(10, 3, 'Melk',            'Lactose',   '8719447321332', '2026-05-23', 'Halfvolle melk',            'OpVoorraad'),
(11, 3, 'Margarine',       NULL,        '8719486321336', '2026-05-02', 'Plantaardige boter',        'OpVoorraad'),
(12, 3, 'Ei',              'Eier',      '8719487421334', '2026-05-04', 'Scharrelei',                'OpVoorraad'),
(13, 4, 'Brood',           'Gluten',    '8719487721337', '2026-05-07', 'Volkoren brood',            'OpVoorraad'),
(14, 4, 'Gevulde Koek',    'Amandel',   '8719483321333', '2026-05-04', 'Banketbakkers kwaliteit',   'OpVoorraad'),
(15, 5, 'Fristi',          'Lactose',   '8719487121331', '2026-05-28', 'Frisdrank',                 'NietOpVoorraad'),
(16, 5, 'Appelsap',        NULL,        '8719487521335', '2026-05-19', '100% vruchtensap',          'OpVoorraad'),
(17, 5, 'Koffie',          'Caffeïne',  '8719487381338', '2026-05-23', 'Arabica koffie',            'OverHoudbaarheidsDatum'),
(18, 5, 'Thee',            'Theïne',    '8719487329339', '2026-05-02', 'Ceylon thee',               'OpVoorraad'),
(19, 6, 'Pasta',           'Gluten',    '8719487321334', '2026-05-16', 'Macaroni',                  'NietLeverbaar'),
(20, 6, 'Rijst',           NULL,        '8719487331332', '2026-05-25', 'Basmati Rijst',             'OpVoorraad'),
(21, 6, 'Knorr Nasi Mix',  NULL,        '871948735135',  '2026-05-13', 'Nasi kruiden',              'OpVoorraad'),
(22, 7, 'Tomatensoep',     NULL,        '8719487371337', '2026-05-23', 'Romige tomatensoep',        'OpVoorraad'),
(23, 7, 'Tomatensaus',     NULL,        '8719487341334', '2026-05-21', 'Pizza saus',                'NietOpVoorraad'),
(24, 7, 'Peterselie',      NULL,        '8719487321636', '2026-05-31', 'Verse kruidenpot',          'OpVoorraad'),
(25, 8, 'Olie',            NULL,        '8719487327337', '2026-05-27', 'Olijfolie',                 'OpVoorraad'),
(26, 8, 'Mars',            NULL,        '8719487324334', '2026-05-11', 'Snoep',                     'OpVoorraad'),
(27, 8, 'Biscuit',         NULL,        '8719487311331', '2026-05-07', 'San Francisco biscuit',     'OpVoorraad'),
(28, 8, 'Paprika Chips',   NULL,        '87194873218398','2026-05-22', 'Ribbelchips paprika',       'OpVoorraad'),
(29, 8, 'Chocolade reep',  'Cacoa',     '8719487321533', '2026-05-21', 'Tony Chocolonely',          'OpVoorraad');

-- ============================================================
-- TESTDATA: Producten per leverancier (29 rijen)
-- ============================================================
INSERT INTO product_per_leverancier (leverancier_id, product_id, datum_aangeleverd, datum_eerst_volgende_levering) VALUES
(4, 1,  '2026-03-12', '2026-05-15'),
(4, 2,  '2026-04-02', '2026-05-05'),
(2, 3,  '2026-03-16', '2026-05-18'),
(1, 4,  '2026-04-08', '2026-05-11'),
(4, 5,  '2026-04-06', '2026-05-10'),
(1, 6,  '2026-03-12', '2026-05-15'),
(4, 7,  '2026-03-20', '2026-05-21'),
(4, 8,  '2026-04-02', '2026-05-08'),
(4, 9,  '2026-04-04', '2026-05-09'),
(3, 10, '2026-04-07', '2026-05-11'),
(3, 11, '2026-04-01', '2026-05-06'),
(3, 12, '2026-03-18', '2026-05-20'),
(3, 13, '2026-03-19', '2026-05-20'),
(2, 14, '2026-04-10', '2026-05-12'),
(2, 15, '2026-03-13', '2026-05-15'),
(1, 16, '2026-03-18', '2026-05-21'),
(1, 17, '2026-03-11', '2026-05-15'),
(1, 18, '2026-04-02', '2026-05-06'),
(1, 19, '2026-04-09', '2026-05-12'),
(4, 20, '2026-04-03', '2026-05-06'),
(2, 21, '2026-04-02', '2026-05-08'),
(1, 22, '2026-03-16', '2026-05-19'),
(3, 23, '2026-03-14', '2026-05-18'),
(3, 24, '2026-04-07', '2026-05-15'),
(1, 25, '2026-03-17', '2026-05-21'),
(2, 26, '2026-04-05', '2026-05-12'),
(1, 27, '2026-04-07', '2026-05-10'),
(2, 28, '2026-04-06', '2026-05-09'),
(3, 29, '2026-04-08', '2026-05-11');

-- ============================================================
-- TESTDATA: Product per magazijn (29 rijen)
-- ============================================================
INSERT INTO product_per_magazijn (product_id, magazijn_id, locatie) VALUES
( 1,  1, 'Berlicum'),
( 2,  2, 'Rosmalen'),
( 3,  3, 'Berlicum'),
( 4,  4, 'Berlicum'),
( 5,  5, 'Rosmalen'),
( 6,  6, 'Berlicum'),
( 7,  7, 'Rosmalen'),
( 8,  8, 'Sint-MichelsGestel'),
( 9,  9, 'Sint-MichelsGestel'),
(10, 10, 'Middelrode'),
(11, 11, 'Middelrode'),
(12, 12, 'Middelrode'),
(13, 13, 'Schijndel'),
(14, 14, 'Schijndel'),
(15, 15, 'Gemonde'),
(16, 16, 'Gemonde'),
(17, 17, 'Gemonde'),
(18, 18, 'Gemonde'),
(19, 19, 'Den Bosch'),
(20, 20, 'Den Bosch'),
(21, 21, 'Den Bosch'),
(22, 22, 'Heeswijk Dinther'),
(23, 23, 'Heeswijk Dinther'),
(24, 24, 'Heeswijk Dinther'),
(25, 25, 'Vught'),
(26, 26, 'Vught'),
(27, 27, 'Vught'),
(28, 28, 'Vught'),
(29, 29, 'Vught');

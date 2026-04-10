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
('AGF',  'Aardappelen, groente, fruit'),
('KV',   'Kaas, vleeswaren'),
('ZPE',  'Zuivel, plantaardig en eieren'),
('BB',   'Bakkerij en banket'),
('FSKT', 'Frisdrank, sappen, koffie en thee');

-- ============================================================
-- TESTDATA: Producten
-- ============================================================
INSERT INTO product (categorie_id, naam, soort_allergie, barcode, houdbaarheids_datum, status) VALUES
(1, 'Appels',          NULL,       '8710400123456', '2026-05-01', 'OpVoorraad'),
(1, 'Aardappelen',     NULL,       '8710400234567', '2026-04-20', 'OpVoorraad'),
(2, 'Kaas 48+',        'Lactose',  '8710400345678', '2026-04-25', 'OpVoorraad'),
(3, 'Volle melk',      'Lactose',  '8710400456789', '2026-04-18', 'OpVoorraad'),
(4, 'Witbrood',        'Gluten',   '8710400567890', '2026-04-15', 'OpVoorraad'),
(5, 'Sinaasappelsap',  NULL,       '8710400678901', '2026-06-01', 'OpVoorraad'),
(2, 'Rookworst',       NULL,       '8710400789012', '2026-04-22', 'OpVoorraad'),
(3, 'Yoghurt naturel', 'Lactose',  '8710400890123', '2026-04-19', 'OpVoorraad');

-- ============================================================
-- TESTDATA: Producten per leverancier
-- ============================================================
INSERT INTO product_per_leverancier (leverancier_id, product_id, datum_aangeleverd, datum_eerst_volgende_levering) VALUES
(1, 1, '2026-04-08', '2026-04-15'),
(1, 2, '2026-04-08', '2026-04-15'),
(1, 5, '2026-04-08', '2026-04-15'),
(2, 3, '2026-04-09', '2026-04-16'),
(2, 4, '2026-04-09', '2026-04-16'),
(2, 8, '2026-04-09', '2026-04-16'),
(3, 6, '2026-04-07', '2026-04-14'),
(4, 7, '2026-04-10', '2026-04-17');

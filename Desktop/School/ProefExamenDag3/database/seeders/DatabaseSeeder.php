<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Allergie ──────────────────────────────────────────────────────────
        DB::table('allergie')->insert([
            ['naam' => 'Gluten',      'omschrijving' => 'Allergisch voor gluten',       'anafylactisch_risico' => 'zeerlaag'],
            ['naam' => 'Pindas',      'omschrijving' => 'Allergisch voor pindas',       'anafylactisch_risico' => 'Hoog'],
            ['naam' => 'Schaaldieren','omschrijving' => 'Allergisch voor schaaldieren', 'anafylactisch_risico' => 'RedelijkHoog'],
            ['naam' => 'Hazelnoten', 'omschrijving' => 'Allergisch voor hazelnoten',   'anafylactisch_risico' => 'laag'],
            ['naam' => 'Lactose',     'omschrijving' => 'Allergisch voor lactose',      'anafylactisch_risico' => 'Zeerlaag'],
            ['naam' => 'Soja',        'omschrijving' => 'Allergisch voor soja',         'anafylactisch_risico' => 'Zeerlaag'],
        ]);

        // ── Categorie ─────────────────────────────────────────────────────────
        DB::table('categorie')->insert([
            ['naam' => 'AGF',  'omschrijving' => 'Aardappelen groente en fruit'],
            ['naam' => 'KV',   'omschrijving' => 'Kaas en vleeswaren'],
            ['naam' => 'ZPE',  'omschrijving' => 'Zuivel plantaardig en eieren'],
            ['naam' => 'BB',   'omschrijving' => 'Bakkerij en Banket'],
            ['naam' => 'FSKT', 'omschrijving' => 'Frisdranken, sappen, koffie en thee'],
            ['naam' => 'PRW',  'omschrijving' => 'Pasta, rijst en wereldkeuken'],
            ['naam' => 'SSKO', 'omschrijving' => 'Soepen, sauzen, kruiden en olie'],
            ['naam' => 'SKCC', 'omschrijving' => 'Snoep, koek, chips en chocolade'],
            ['naam' => 'BVH',  'omschrijving' => 'Baby, verzorging en hygiene'],
        ]);

        // ── Eetwens ───────────────────────────────────────────────────────────
        DB::table('eetwens')->insert([
            ['naam' => 'GeenVarken',  'omschrijving' => 'Geen Varkensvlees'],
            ['naam' => 'Veganistisch','omschrijving' => 'Geen zuivelproducten en vlees'],
            ['naam' => 'Vegetarisch', 'omschrijving' => 'Geen vlees'],
            ['naam' => 'Omnivoor',    'omschrijving' => 'Geen beperkingen'],
        ]);

        // ── Rol ───────────────────────────────────────────────────────────────
        DB::table('rol')->insert([
            ['naam' => 'Manager'],
            ['naam' => 'Medewerker'],
            ['naam' => 'Vrijwilliger'],
        ]);

        // ── Contact ───────────────────────────────────────────────────────────
        DB::table('contact')->insert([
            ['straat' => 'Prinses Irenestraat',    'huisnummer' => '12',  'toevoeging' => 'A',   'postcode' => '5271TH', 'woonplaats' => 'Maaskantje', 'email' => 'j.van.zevenhuizen@gmail.com', 'mobiel' => '+31 623456123'],
            ['straat' => 'Gibraltarstraat',        'huisnummer' => '234', 'toevoeging' => null,  'postcode' => '5271TJ', 'woonplaats' => 'Maaskantje', 'email' => 'a.bergkamp@hotmail.com',       'mobiel' => '+31 623456123'],
            ['straat' => 'Der Kinderenstraat',     'huisnummer' => '234', 'toevoeging' => 'Bis',  'postcode' => '5271TH', 'woonplaats' => 'Maaskantje', 'email' => 'z.van.de.heuvel@gmail.com',   'mobiel' => '+31 623456123'],
            ['straat' => 'Nachtegaalstraat',       'huisnummer' => '233', 'toevoeging' => 'A',   'postcode' => '5271TH', 'woonplaats' => 'Maaskantje', 'email' => 'e.scherder@gmail.com',         'mobiel' => '+31 623456123'],
            ['straat' => 'Bertram Russellstraat',  'huisnummer' => '45',  'toevoeging' => null,  'postcode' => '5271TH', 'woonplaats' => 'Maaskantje', 'email' => 'f.de.jong@hotmail.com',        'mobiel' => '+31 623456123'],
            ['straat' => 'Leonardo Da VinciHof',   'huisnummer' => '234', 'toevoeging' => null,  'postcode' => '5271ZE', 'woonplaats' => 'Maaskantje', 'email' => 'h.van.den.berg@gmail.com',     'mobiel' => '+31 623456123'],
            ['straat' => 'Siegfried Knutsenlaan',  'huisnummer' => '234', 'toevoeging' => null,  'postcode' => '5271ZE', 'woonplaats' => 'Maaskantje', 'email' => 'r.ter.weijden@ah.nl',          'mobiel' => '+31 623456123'],
            ['straat' => 'Theo de Bokstraat',      'huisnummer' => '256', 'toevoeging' => null,  'postcode' => '5271ZH', 'woonplaats' => 'Maaskantje', 'email' => 'l.pastoor@gmail.com',          'mobiel' => '+31 623456123'],
            ['straat' => 'Meester van Leerhof',    'huisnummer' => '2',   'toevoeging' => null,  'postcode' => '5271ZE', 'woonplaats' => 'Maaskantje', 'email' => 'm.yazid@gemeenteUtrecht.nl',   'mobiel' => '+31 623456123'],
            ['straat' => 'Van Wermelenplantsoen',  'huisnummer' => '300', 'toevoeging' => null,  'postcode' => '5271TH', 'woonplaats' => 'Maaskantje', 'email' => 'b.van.driel@gmail.com',        'mobiel' => '+31 623456123'],
            ['straat' => 'Terlingenhof',           'huisnummer' => '20',  'toevoeging' => null,  'postcode' => '5271TH', 'woonplaats' => 'Maaskantje', 'email' => 'j.pastorius@gmail.com',        'mobiel' => '+31 623456256'],
            ['straat' => 'Veldhoen',               'huisnummer' => '31',  'toevoeging' => null,  'postcode' => '5271TH', 'woonplaats' => 'Maaskantje', 'email' => 's.dollaard@gmail.com',         'mobiel' => '+31 623456123'],
            ['straat' => 'ScheringaDreef',         'huisnummer' => '37',  'toevoeging' => null,  'postcode' => '5271ZE', 'woonplaats' => 'Vught',      'email' => 'l.blokker@gemeentevught.nl',   'mobiel' => '+31 623452314'],
        ]);

        // ── Gezin ─────────────────────────────────────────────────────────────
        DB::table('gezin')->insert([
            ['naam' => 'ZevenhuizenGezin', 'code' => 'G0001', 'omschrijving' => 'Bijstandsgezin', 'aantal_volwassenen' => 2, 'aantal_kinderen' => 2, 'aantal_babys' => 0, 'totaal_aantal_personen' => 4],
            ['naam' => 'BergkampGezin',    'code' => 'G0002', 'omschrijving' => 'Bijstandsgezin', 'aantal_volwassenen' => 2, 'aantal_kinderen' => 1, 'aantal_babys' => 1, 'totaal_aantal_personen' => 4],
            ['naam' => 'HeuvelGezin',      'code' => 'G0003', 'omschrijving' => 'Bijstandsgezin', 'aantal_volwassenen' => 2, 'aantal_kinderen' => 0, 'aantal_babys' => 2, 'totaal_aantal_personen' => 4],
            ['naam' => 'ScherderGezin',    'code' => 'G0004', 'omschrijving' => 'Bijstandsgezin',        'aantal_volwassenen' => 2, 'aantal_kinderen' => 1, 'aantal_babys' => 0, 'totaal_aantal_personen' => 3],
            ['naam' => 'DeJongGezin',      'code' => 'G0005', 'omschrijving' => 'Bijstandsgezin', 'aantal_volwassenen' => 1, 'aantal_kinderen' => 1, 'aantal_babys' => 0, 'totaal_aantal_personen' => 2],
            ['naam' => 'VanderBergGezin',  'code' => 'G0006', 'omschrijving' => 'AlleenGaande',   'aantal_volwassenen' => 1, 'aantal_kinderen' => 0, 'aantal_babys' => 0, 'totaal_aantal_personen' => 1],
        ]);

        // ── Leverancier ───────────────────────────────────────────────────────
        DB::table('leverancier')->insert([
            ['naam' => 'Albert Heijn',       'contact_persoon' => 'Ruud ter Weijden',   'leverancier_nummer' => 'L0001', 'leverancier_type' => 'Bedrijf'],
            ['naam' => 'Albertus Kerk',      'contact_persoon' => 'Leo Pastoor',        'leverancier_nummer' => 'L0002', 'leverancier_type' => 'Instelling'],
            ['naam' => 'Gemeente Utrecht',   'contact_persoon' => 'Mohammed Yazidi',    'leverancier_nummer' => 'L0003', 'leverancier_type' => 'Overheid'],
            ['naam' => 'Boerderij Meerhoven','contact_persoon' => 'Bertus van Driel',   'leverancier_nummer' => 'L0004', 'leverancier_type' => 'Particulier'],
            ['naam' => 'Jan van de Heijden', 'contact_persoon' => 'Jan van de Heijden', 'leverancier_nummer' => 'L0005', 'leverancier_type' => 'Donor'],
            ['naam' => 'Vomar',              'contact_persoon' => 'Jaco Pastorius',     'leverancier_nummer' => 'L0006', 'leverancier_type' => 'Bedrijf'],
            ['naam' => 'DekaMarkt',          'contact_persoon' => 'Sil den Dollaard',   'leverancier_nummer' => 'L0007', 'leverancier_type' => 'Bedrijf'],
            ['naam' => 'Gemeente Vught',     'contact_persoon' => 'Jan Blokker',        'leverancier_nummer' => 'L0008', 'leverancier_type' => 'Overheid'],
        ]);

        // ── Magazijn ──────────────────────────────────────────────────────────
        DB::table('magazijn')->insert([
            ['ontvangstdatum' => '2026-03-12', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '5 kg',      'aantal' => 20],
            ['ontvangstdatum' => '2026-04-02', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '2.5 kg',    'aantal' => 40],
            ['ontvangstdatum' => '2026-03-16', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1 kg',      'aantal' => 30],
            ['ontvangstdatum' => '2026-04-08', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1.5 kg',    'aantal' => 25],
            ['ontvangstdatum' => '2026-04-06', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '4 stuks',   'aantal' => 75],
            ['ontvangstdatum' => '2026-03-12', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1 kg/tros', 'aantal' => 60],
            ['ontvangstdatum' => '2026-03-20', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '2 kg/tros', 'aantal' => 200],
            ['ontvangstdatum' => '2026-04-02', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '200 g',     'aantal' => 45],
            ['ontvangstdatum' => '2026-04-04', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '100 g',     'aantal' => 60],
            ['ontvangstdatum' => '2026-04-07', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1 liter',   'aantal' => 120],
            ['ontvangstdatum' => '2026-04-01', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '250 g',     'aantal' => 80],
            ['ontvangstdatum' => '2026-03-18', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '5 stuks',   'aantal' => 120],
            ['ontvangstdatum' => '2026-03-19', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '800 g',     'aantal' => 220],
            ['ontvangstdatum' => '2026-03-10', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1 stuk',    'aantal' => 130],
            ['ontvangstdatum' => '2026-03-18', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '150 ml',    'aantal' => 72],
            ['ontvangstdatum' => '2026-03-18', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1 l',       'aantal' => 12],
            ['ontvangstdatum' => '2026-03-11', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '250 g',     'aantal' => 46],
            ['ontvangstdatum' => '2026-04-02', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '25 zakjes', 'aantal' => 280],
            ['ontvangstdatum' => '2026-04-09', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '500 g',     'aantal' => 330],
            ['ontvangstdatum' => '2026-04-03', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1 kg',      'aantal' => 34],
            ['ontvangstdatum' => '2026-04-02', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '50 g',      'aantal' => 23],
            ['ontvangstdatum' => '2026-03-16', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1 l',       'aantal' => 46],
            ['ontvangstdatum' => '2026-03-14', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '250 ml',    'aantal' => 98],
            ['ontvangstdatum' => '2026-04-07', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1 potje',   'aantal' => 56],
            ['ontvangstdatum' => '2026-03-17', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '1 l',       'aantal' => 210],
            ['ontvangstdatum' => '2026-04-05', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '4 stuks',   'aantal' => 24],
            ['ontvangstdatum' => '2026-04-07', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '300 g',     'aantal' => 87],
            ['ontvangstdatum' => '2026-04-06', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '200 g',     'aantal' => 230],
            ['ontvangstdatum' => '2026-04-08', 'uitleveringsdatum' => null, 'verpakkings_eenheid' => '80 g',      'aantal' => 30],
        ]);

        // ── Persoon ───────────────────────────────────────────────────────────
        DB::table('persoon')->insert([
            ['gezin_id' => null, 'voornaam' => 'Hans',    'tussenvoegsel' => 'van',  'achternaam' => 'Leeuwen',     'geboortedatum' => '1958-02-12', 'type_persoon' => 'Manager',    'is_vertegenwoordiger' => 0],
            ['gezin_id' => null, 'voornaam' => 'Jan',     'tussenvoegsel' => 'van der','achternaam' => 'Sluijs',    'geboortedatum' => '1993-04-30', 'type_persoon' => 'Medewerker', 'is_vertegenwoordiger' => 0],
            ['gezin_id' => null, 'voornaam' => 'Herman',  'tussenvoegsel' => 'den',  'achternaam' => 'Duiker',      'geboortedatum' => null,         'type_persoon' => 'Vrijwilliger','is_vertegenwoordiger' => 0],
            ['gezin_id' => null, 'voornaam' => 'Johan',   'tussenvoegsel' => 'van',  'achternaam' => 'Zevenhuizen', 'geboortedatum' => '1990-05-20', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 1,    'voornaam' => 'Sarah',   'tussenvoegsel' => 'den',  'achternaam' => 'Dolder',      'geboortedatum' => '1985-03-23', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 1,    'voornaam' => 'Theo',    'tussenvoegsel' => 'van',  'achternaam' => 'Zevenhuizen', 'geboortedatum' => '2015-03-08', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 1,    'voornaam' => 'Jantien', 'tussenvoegsel' => 'van',  'achternaam' => 'Zevenhuizen', 'geboortedatum' => '2016-09-20', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 2,    'voornaam' => 'Arjan',   'tussenvoegsel' => null,   'achternaam' => 'Bergkamp',    'geboortedatum' => '1968-07-12', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 1],
            ['gezin_id' => 2,    'voornaam' => 'Janneke', 'tussenvoegsel' => null,   'achternaam' => 'Sanders',     'geboortedatum' => '1970-05-20', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 2,    'voornaam' => 'Stein',   'tussenvoegsel' => null,   'achternaam' => 'Bergkamp',    'geboortedatum' => '2011-02-02', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 2,    'voornaam' => 'Judith',  'tussenvoegsel' => null,   'achternaam' => 'Bergkamp',    'geboortedatum' => '2026-02-05', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 3,    'voornaam' => 'Mazin',   'tussenvoegsel' => 'van',  'achternaam' => 'Vliet',       'geboortedatum' => '1968-07-18', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 3,    'voornaam' => 'Selma',   'tussenvoegsel' => 'van de','achternaam' => 'Heuvel',     'geboortedatum' => '1963-09-04', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 1],
            ['gezin_id' => 4,    'voornaam' => 'Eva',     'tussenvoegsel' => null,   'achternaam' => 'Scherder',    'geboortedatum' => '2000-04-07', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 4,    'voornaam' => 'Felicia', 'tussenvoegsel' => null,   'achternaam' => 'Scherder',    'geboortedatum' => '2025-11-29', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 4,    'voornaam' => 'Devin',   'tussenvoegsel' => null,   'achternaam' => 'Scherder',    'geboortedatum' => '2026-03-01', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 5,    'voornaam' => 'Frieda',  'tussenvoegsel' => 'de',   'achternaam' => 'Jong',        'geboortedatum' => '1980-09-04', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 5,    'voornaam' => 'Simeon',  'tussenvoegsel' => 'de',   'achternaam' => 'Jong',        'geboortedatum' => '2018-05-23', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
            ['gezin_id' => 6,    'voornaam' => 'Hanna',   'tussenvoegsel' => 'van der','achternaam' => 'Berg',      'geboortedatum' => '1999-09-09', 'type_persoon' => 'Klant',      'is_vertegenwoordiger' => 0],
        ]);

        // ── Users (Gebruiker) ─────────────────────────────────────────────────
        DB::table('users')->insert([
            ['persoon_id' => 1, 'inlog_naam' => 'hans@maaskantje.nl',   'gebruikersnaam' => 'Hans',   'name' => 'Hans van Leeuwen',  'email' => 'hans@maaskantje.nl',   'password' => Hash::make('Admin1234!'),        'rol' => 'manager',     'is_ingelogd' => 1, 'ingelogd' => '2026-04-10 09:03:06', 'uitgelogd' => null],
            ['persoon_id' => 2, 'inlog_naam' => 'jan@maaskantje.nl',    'gebruikersnaam' => 'Jan',    'name' => 'Jan van der Sluijs', 'email' => 'jan@maaskantje.nl',    'password' => Hash::make('Medewerker1234!'),   'rol' => 'medewerker',  'is_ingelogd' => 1, 'ingelogd' => '2026-04-09 15:13:23', 'uitgelogd' => '2026-04-9 15:23:46'],
            ['persoon_id' => 3, 'inlog_naam' => 'herman@maaskantje.nl', 'gebruikersnaam' => 'Herman', 'name' => 'Herman den Duiker',  'email' => 'herman@maaskantje.nl', 'password' => Hash::make('Vrijwilliger1234!'), 'rol' => 'vrijwilliger','is_ingelogd' => 1, 'ingelogd' => '2026-04-08 12:05:20', 'uitgelogd' => null],
        ]);

        // ── RolPerGebruiker ───────────────────────────────────────────────────
        DB::table('rol_per_gebruiker')->insert([
            ['gebruiker_id' => 1, 'rol_id' => 1],
            ['gebruiker_id' => 2, 'rol_id' => 2],
            ['gebruiker_id' => 3, 'rol_id' => 3],
        ]);

        DB::table('allergie_per_persoon')->insert([
            ['persoon_id' => 4,  'allergie_id' => 1],
            ['persoon_id' => 5,  'allergie_id' => 2],
            ['persoon_id' => 6,  'allergie_id' => 3],
            ['persoon_id' => 7,  'allergie_id' => 4],
            ['persoon_id' => 8,  'allergie_id' => 2],
            ['persoon_id' => 9,  'allergie_id' => 5],
            ['persoon_id' => 10, 'allergie_id' => 2],
            ['persoon_id' => 11, 'allergie_id' => 5],
            ['persoon_id' => 12, 'allergie_id' => 2],
            ['persoon_id' => 13, 'allergie_id' => 4],
            ['persoon_id' => 14, 'allergie_id' => 3],
            ['persoon_id' => 15, 'allergie_id' => 1],
            ['persoon_id' => 16, 'allergie_id' => 3],
            ['persoon_id' => 17, 'allergie_id' => 2],
            ['persoon_id' => 18, 'allergie_id' => 1],
            ['persoon_id' => 19, 'allergie_id' => 4],
        ]);

        // ── EetwensPerGezin ───────────────────────────────────────────────────
        DB::table('eetwens_per_gezin')->insert([
            ['gezin_id' => 1, 'eetwens_id' => 2],
            ['gezin_id' => 2, 'eetwens_id' => 2],
            ['gezin_id' => 3, 'eetwens_id' => 3],
            ['gezin_id' => 4, 'eetwens_id' => 4],
            ['gezin_id' => 5, 'eetwens_id' => 3],
        ]);

        // ── ContactPerGezin ───────────────────────────────────────────────────
        DB::table('contact_per_gezin')->insert([
            ['gezin_id' => 1, 'contact_id' => 1],
            ['gezin_id' => 2, 'contact_id' => 2],
            ['gezin_id' => 3, 'contact_id' => 3],
            ['gezin_id' => 4, 'contact_id' => 4],
            ['gezin_id' => 5, 'contact_id' => 5],
            ['gezin_id' => 6, 'contact_id' => 6],
        ]);

        // ── ContactPerLeverancier ─────────────────────────────────────────────
        DB::table('contact_per_leverancier')->insert([
            ['leverancier_id' => 1, 'contact_id' => 7],
            ['leverancier_id' => 2, 'contact_id' => 8],
            ['leverancier_id' => 3, 'contact_id' => 9],
            ['leverancier_id' => 4, 'contact_id' => 10],
            ['leverancier_id' => 5, 'contact_id' => 11],
            ['leverancier_id' => 6, 'contact_id' => 12],
            ['leverancier_id' => 7, 'contact_id' => 12],
            ['leverancier_id' => 8, 'contact_id' => 13],
        ]);

        // ── Product ───────────────────────────────────────────────────────────
        DB::table('product')->insert([
            ['categorie_id' => 1, 'naam' => 'Aardappel',      'soort_allergie' => null,      'barcode' => '8719587321239', 'houdbaarheids_datum' => '2026-05-23', 'omschrijving' => 'Kruimige aardappel',      'status' => 'OpVoorraad'],
            ['categorie_id' => 1, 'naam' => 'Aardappel',      'soort_allergie' => null,      'barcode' => '8719587321239', 'houdbaarheids_datum' => '2026-05-26', 'omschrijving' => 'Kruimige aardappel',      'status' => 'OpVoorraad'],
            ['categorie_id' => 1, 'naam' => 'Ui',             'soort_allergie' => null,      'barcode' => '8719437321335', 'houdbaarheids_datum' => '2026-05-02', 'omschrijving' => 'Gele ui',                 'status' => 'NietOpVoorraad'],
            ['categorie_id' => 1, 'naam' => 'Appel',          'soort_allergie' => null,      'barcode' => '8719486321332', 'houdbaarheids_datum' => '2026-05-16', 'omschrijving' => 'Granny Smith',            'status' => 'NietLeverbaar'],
            ['categorie_id' => 1, 'naam' => 'Appel',          'soort_allergie' => null,      'barcode' => '8719486321332', 'houdbaarheids_datum' => '2026-05-23', 'omschrijving' => 'Granny Smith',            'status' => 'NietLeverbaar'],
            ['categorie_id' => 1, 'naam' => 'Banaan',         'soort_allergie' => 'Banaan',  'barcode' => '8719484321336', 'houdbaarheids_datum' => '2026-05-12', 'omschrijving' => 'Biologische Banaan',      'status' => 'OpVoorraad'],
            ['categorie_id' => 1, 'naam' => 'Banaan',         'soort_allergie' => 'Banaan',  'barcode' => '8719484321336', 'houdbaarheids_datum' => '2026-05-19', 'omschrijving' => 'Biologische Banaan',      'status' => 'OverHoudbaarheidsDatum'],
            ['categorie_id' => 2, 'naam' => 'Kaas',           'soort_allergie' => 'Lactose', 'barcode' => '8719487421338', 'houdbaarheids_datum' => '2026-03-19', 'omschrijving' => 'Jonge Kaas',              'status' => 'OpVoorraad'],
            ['categorie_id' => 2, 'naam' => 'Rosbief',        'soort_allergie' => null,      'barcode' => '8719487421331', 'houdbaarheids_datum' => '2026-05-23', 'omschrijving' => 'Rundvlees',               'status' => 'OpVoorraad'],
            ['categorie_id' => 3, 'naam' => 'Melk',           'soort_allergie' => 'Lactose', 'barcode' => '8719487321332', 'houdbaarheids_datum' => '2026-05-23', 'omschrijving' => 'Halfvolle melk',          'status' => 'OpVoorraad'],
            ['categorie_id' => 3, 'naam' => 'Margarine',      'soort_allergie' => null,      'barcode' => '8719486321336', 'houdbaarheids_datum' => '2026-05-02', 'omschrijving' => 'Plantaardige boter',      'status' => 'OpVoorraad'],
            ['categorie_id' => 3, 'naam' => 'Ei',             'soort_allergie' => 'Eier',    'barcode' => '8719487421334', 'houdbaarheids_datum' => '2026-05-04', 'omschrijving' => 'Scharrelei',              'status' => 'OpVoorraad'],
            ['categorie_id' => 4, 'naam' => 'Brood',          'soort_allergie' => 'Gluten',  'barcode' => '8719487371337', 'houdbaarheids_datum' => '2026-05-07', 'omschrijving' => 'Volkoren brood',          'status' => 'OpVoorraad'],
            ['categorie_id' => 4, 'naam' => 'Gevulde Koek',   'soort_allergie' => 'Amandel', 'barcode' => '8719483321333', 'houdbaarheids_datum' => '2026-05-04', 'omschrijving' => 'Banketbakkers kwaliteit', 'status' => 'OpVoorraad'],
            ['categorie_id' => 5, 'naam' => 'Fristi',         'soort_allergie' => 'Lactose', 'barcode' => '8719487121331', 'houdbaarheids_datum' => '2026-05-28', 'omschrijving' => 'Frisdrank',               'status' => 'NietOpVoorraad'],
            ['categorie_id' => 5, 'naam' => 'Appelsap',       'soort_allergie' => null,      'barcode' => '8719487521335', 'houdbaarheids_datum' => '2026-05-19', 'omschrijving' => '100% vruchtensap',        'status' => 'OpVoorraad'],
            ['categorie_id' => 5, 'naam' => 'Koffie',         'soort_allergie' => 'Caffeine','barcode' => '8719487381338', 'houdbaarheids_datum' => '2026-05-23', 'omschrijving' => 'Arabica koffie',          'status' => 'OpVoorraad'],
            ['categorie_id' => 5, 'naam' => 'Thee',           'soort_allergie' => 'Theine',  'barcode' => '8719487329339', 'houdbaarheids_datum' => '2026-05-02', 'omschrijving' => 'Ceylon thee',             'status' => 'OpVoorraad'],

            ['categorie_id' => 6, 'naam' => 'Pasta',          'soort_allergie' => 'Gluten',  'barcode' => '8719487321334', 'houdbaarheids_datum' => '2026-05-16', 'omschrijving' => 'Macaroni',                'status' => 'NietLeverbaar'],
            ['categorie_id' => 6, 'naam' => 'Rijst',          'soort_allergie' => null,      'barcode' => '8719487331332', 'houdbaarheids_datum' => '2026-05-25', 'omschrijving' => 'Basmati Rijst',           'status' => 'OpVoorraad'],
            ['categorie_id' => 6, 'naam' => 'Knorr Nasi Mix', 'soort_allergie' => null,      'barcode' => '8719487335135', 'houdbaarheids_datum' => '2026-05-13', 'omschrijving' => 'Nasi kruiden',            'status' => 'OpVoorraad'],
            ['categorie_id' => 7, 'naam' => 'Tomatensoep',    'soort_allergie' => null,      'barcode' => '8719487321332', 'houdbaarheids_datum' => '2026-05-23', 'omschrijving' => 'Romige tomatensoep',      'status' => 'OpVoorraad'],
            ['categorie_id' => 7, 'naam' => 'Tomatensaus',    'soort_allergie' => null,      'barcode' => '8719487341334', 'houdbaarheids_datum' => '2026-05-21', 'omschrijving' => 'Pizza saus',              'status' => 'NietOpVoorraad'],
            ['categorie_id' => 7, 'naam' => 'Peterselie',     'soort_allergie' => null,      'barcode' => '8719487321636', 'houdbaarheids_datum' => '2026-05-31', 'omschrijving' => 'Verse kruidenpot',        'status' => 'OpVoorraad'],
            ['categorie_id' => 8, 'naam' => 'Olie',           'soort_allergie' => null,      'barcode' => '8719487327337', 'houdbaarheids_datum' => '2026-05-27', 'omschrijving' => 'Olijfolie',               'status' => 'OpVoorraad'],
            ['categorie_id' => 8, 'naam' => 'Mars',           'soort_allergie' => null,      'barcode' => '8719487324334', 'houdbaarheids_datum' => '2026-05-11', 'omschrijving' => 'Snoep',                   'status' => 'OpVoorraad'],
            ['categorie_id' => 8, 'naam' => 'Biscuit',        'soort_allergie' => null,      'barcode' => '8719487311331', 'houdbaarheids_datum' => '2026-05-07', 'omschrijving' => 'San Francisco biscuit',   'status' => 'OpVoorraad'],
            ['categorie_id' => 8, 'naam' => 'Paprika Chips',  'soort_allergie' => null,      'barcode' => '8719487318398','houdbaarheids_datum' => '2026-05-22', 'omschrijving' => 'Ribbelchips paprika',     'status' => 'OpVoorraad'],
            ['categorie_id' => 8, 'naam' => 'Chocolade reep', 'soort_allergie' => 'Cacoa',   'barcode' => '8719487321533', 'houdbaarheids_datum' => '2026-05-21', 'omschrijving' => 'Tony Chocolonely',        'status' => 'OpVoorraad'],
        ]);

        // ── Voedselpakket ─────────────────────────────────────────────────────
        DB::table('voedselpakket')->insert([
            ['gezin_id' => 1, 'pakket_nummer' => 1, 'datum_samenstelling' => '2026-03-21', 'datum_uitgifte' => '2026-03-21', 'status' => 'Uitgereikt'],
            ['gezin_id' => 1, 'pakket_nummer' => 2, 'datum_samenstelling' => '2026-03-19', 'datum_uitgifte' => null,         'status' => 'NietUitgereikt'],
            ['gezin_id' => 1, 'pakket_nummer' => 3, 'datum_samenstelling' => '2026-03-17', 'datum_uitgifte' => null,         'status' => 'NietMeerIngeschreven'],
            ['gezin_id' => 2, 'pakket_nummer' => 4, 'datum_samenstelling' => '2026-03-10', 'datum_uitgifte' => '2026-03-14', 'status' => 'Uitgereikt'],
            ['gezin_id' => 2, 'pakket_nummer' => 5, 'datum_samenstelling' => '2026-03-18', 'datum_uitgifte' => '2026-03-20', 'status' => 'Uitgereikt'],
            ['gezin_id' => 2, 'pakket_nummer' => 6, 'datum_samenstelling' => '2026-04-08', 'datum_uitgifte' => null,         'status' => 'NietUitgereikt'],
        ]);

        // ── ProductPerVoedselpakket ───────────────────────────────────────────
        DB::table('product_per_voedselpakket')->insert([
            ['voedselpakket_id' => 1, 'product_id' => 7,  'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 1, 'product_id' => 8,  'aantal_product_eenheden' => 2],
            ['voedselpakket_id' => 1, 'product_id' => 9,  'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 2, 'product_id' => 12, 'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 2, 'product_id' => 13, 'aantal_product_eenheden' => 2],
            ['voedselpakket_id' => 2, 'product_id' => 14, 'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 3, 'product_id' => 3,  'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 3, 'product_id' => 4,  'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 4, 'product_id' => 20, 'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 4, 'product_id' => 19, 'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 4, 'product_id' => 21, 'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 5, 'product_id' => 24, 'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 5, 'product_id' => 25, 'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 5, 'product_id' => 26, 'aantal_product_eenheden' => 1],
            ['voedselpakket_id' => 6, 'product_id' => 27, 'aantal_product_eenheden' => 1],
        ]);

        // ── ProductPerLeverancier ─────────────────────────────────────────────
        DB::table('product_per_leverancier')->insert([
            ['leverancier_id' => 4, 'product_id' => 1,  'datum_aangeleverd' => '2026-03-12', 'datum_eerst_volgende_levering' => '2026-05-15'],
            ['leverancier_id' => 1, 'product_id' => 2,  'datum_aangeleverd' => '2026-04-02', 'datum_eerst_volgende_levering' => '2026-05-05'],
            ['leverancier_id' => 2, 'product_id' => 3,  'datum_aangeleverd' => '2026-03-16', 'datum_eerst_volgende_levering' => '2026-05-18'],
            ['leverancier_id' => 4, 'product_id' => 4,  'datum_aangeleverd' => '2026-04-08', 'datum_eerst_volgende_levering' => '2026-05-11'],
            ['leverancier_id' => 3, 'product_id' => 5,  'datum_aangeleverd' => '2026-04-06', 'datum_eerst_volgende_levering' => '2026-05-10'],
            ['leverancier_id' => 1, 'product_id' => 6,  'datum_aangeleverd' => '2026-03-12', 'datum_eerst_volgende_levering' => '2026-05-15'],
            ['leverancier_id' => 4, 'product_id' => 7,  'datum_aangeleverd' => '2026-03-20', 'datum_eerst_volgende_levering' => '2026-05-21'],
            ['leverancier_id' => 4, 'product_id' => 8,  'datum_aangeleverd' => '2026-04-02', 'datum_eerst_volgende_levering' => '2026-05-08'],
            ['leverancier_id' => 4, 'product_id' => 9,  'datum_aangeleverd' => '2026-04-04', 'datum_eerst_volgende_levering' => '2026-05-09'],
            ['leverancier_id' => 3, 'product_id' => 10, 'datum_aangeleverd' => '2026-04-07', 'datum_eerst_volgende_levering' => '2026-05-11'],
            ['leverancier_id' => 2, 'product_id' => 11, 'datum_aangeleverd' => '2026-04-01', 'datum_eerst_volgende_levering' => '2026-05-06'],
            ['leverancier_id' => 3, 'product_id' => 12, 'datum_aangeleverd' => '2026-03-18', 'datum_eerst_volgende_levering' => '2026-05-20'],
            ['leverancier_id' => 3, 'product_id' => 13, 'datum_aangeleverd' => '2026-03-19', 'datum_eerst_volgende_levering' => '2026-05-20'],
            ['leverancier_id' => 2, 'product_id' => 14, 'datum_aangeleverd' => '2026-04-10', 'datum_eerst_volgende_levering' => '2026-05-12'],
            ['leverancier_id' => 2, 'product_id' => 15, 'datum_aangeleverd' => '2026-03-13', 'datum_eerst_volgende_levering' => '2026-05-15'],
            ['leverancier_id' => 1, 'product_id' => 16, 'datum_aangeleverd' => '2026-03-18', 'datum_eerst_volgende_levering' => '2026-05-21'],
            ['leverancier_id' => 1, 'product_id' => 17, 'datum_aangeleverd' => '2026-03-11', 'datum_eerst_volgende_levering' => '2026-05-15'],
            ['leverancier_id' => 1, 'product_id' => 18, 'datum_aangeleverd' => '2026-04-02', 'datum_eerst_volgende_levering' => '2026-05-06'],
            ['leverancier_id' => 1, 'product_id' => 19, 'datum_aangeleverd' => '2026-04-09', 'datum_eerst_volgende_levering' => '2026-05-12'],
            ['leverancier_id' => 4, 'product_id' => 20, 'datum_aangeleverd' => '2026-04-03', 'datum_eerst_volgende_levering' => '2026-05-06'],
            ['leverancier_id' => 2, 'product_id' => 21, 'datum_aangeleverd' => '2026-04-02', 'datum_eerst_volgende_levering' => '2026-05-08'],
            ['leverancier_id' => 1, 'product_id' => 22, 'datum_aangeleverd' => '2026-03-16', 'datum_eerst_volgende_levering' => '2026-05-19'],
            ['leverancier_id' => 3, 'product_id' => 23, 'datum_aangeleverd' => '2026-03-14', 'datum_eerst_volgende_levering' => '2026-05-18'],
            ['leverancier_id' => 3, 'product_id' => 24, 'datum_aangeleverd' => '2026-04-07', 'datum_eerst_volgende_levering' => '2026-05-15'],
            ['leverancier_id' => 1, 'product_id' => 25, 'datum_aangeleverd' => '2026-03-17', 'datum_eerst_volgende_levering' => '2026-05-21'],
            ['leverancier_id' => 2, 'product_id' => 26, 'datum_aangeleverd' => '2026-04-05', 'datum_eerst_volgende_levering' => '2026-05-21'],
            ['leverancier_id' => 1, 'product_id' => 27, 'datum_aangeleverd' => '2026-04-07', 'datum_eerst_volgende_levering' => '2026-05-10'],
            ['leverancier_id' => 2, 'product_id' => 28, 'datum_aangeleverd' => '2026-04-06', 'datum_eerst_volgende_levering' => '2026-05-09'],
            ['leverancier_id' => 3, 'product_id' => 29, 'datum_aangeleverd' => '2026-04-08', 'datum_eerst_volgende_levering' => '2026-05-11'],
        ]);

        // ── Voorraad ──────────────────────────────────────────────────────────
        DB::table('voorraad')->insert([
            ['product_naam' => 'Aardappel',      'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719587321239', 'magazijn_locatie' => 'Berlicum',           'ontvangstdatum' => '2026-03-12', 'aantal_uitgeleverd' => 5,  'uitleveringsdatum' => '2026-03-21', 'aantal_op_voorraad' => 15],
            ['product_naam' => 'Aardappel',      'houdbaarheidsdatum' => '2026-05-26', 'barcode' => '8719587321239', 'magazijn_locatie' => 'Rosmalen',           'ontvangstdatum' => '2026-04-02', 'aantal_uitgeleverd' => 10, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 30],
            ['product_naam' => 'Ui',             'houdbaarheidsdatum' => '2026-05-02', 'barcode' => '8719437321335', 'magazijn_locatie' => 'Berlicum',           'ontvangstdatum' => '2026-03-16', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 30],
            ['product_naam' => 'Appel',          'houdbaarheidsdatum' => '2026-05-16', 'barcode' => '8719486321332', 'magazijn_locatie' => 'Berlicum',           'ontvangstdatum' => '2026-04-08', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 25],
            ['product_naam' => 'Appel',          'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719486321332', 'magazijn_locatie' => 'Rosmalen',           'ontvangstdatum' => '2026-04-06', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 75],
            ['product_naam' => 'Banaan',         'houdbaarheidsdatum' => '2026-05-12', 'barcode' => '8719486321332', 'magazijn_locatie' => 'Berlicum',           'ontvangstdatum' => '2026-03-12', 'aantal_uitgeleverd' => 20, 'uitleveringsdatum' => '2026-03-21', 'aantal_op_voorraad' => 40],
            ['product_naam' => 'Banaan',         'houdbaarheidsdatum' => '2026-05-19', 'barcode' => '8719484321336', 'magazijn_locatie' => 'Rosmalen',           'ontvangstdatum' => '2026-03-20', 'aantal_uitgeleverd' => 50, 'uitleveringsdatum' => '2026-04-01', 'aantal_op_voorraad' => 150],
            ['product_naam' => 'Kaas',           'houdbaarheidsdatum' => '2026-03-19', 'barcode' => '8719487421231', 'magazijn_locatie' => 'Sint-MichelsGestel', 'ontvangstdatum' => '2026-04-02', 'aantal_uitgeleverd' => 10, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 35],
            ['product_naam' => 'Rosbief',        'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719487421331', 'magazijn_locatie' => 'Sint-MichelsGestel', 'ontvangstdatum' => '2026-04-04', 'aantal_uitgeleverd' => 5,  'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 55],
            ['product_naam' => 'Melk',           'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719487321332', 'magazijn_locatie' => 'Middelrode',         'ontvangstdatum' => '2026-04-07', 'aantal_uitgeleverd' => 30, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 90],
            ['product_naam' => 'Margarine',      'houdbaarheidsdatum' => '2026-05-02', 'barcode' => '8719486321336', 'magazijn_locatie' => 'Middelrode',         'ontvangstdatum' => '2026-04-01', 'aantal_uitgeleverd' => 15, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 65],
            ['product_naam' => 'Ei',             'houdbaarheidsdatum' => '2026-05-04', 'barcode' => '8719487421334', 'magazijn_locatie' => 'Middelrode',         'ontvangstdatum' => '2026-03-18', 'aantal_uitgeleverd' => 20, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 100],
            ['product_naam' => 'Brood',          'houdbaarheidsdatum' => '2026-05-07', 'barcode' => '8719487371337', 'magazijn_locatie' => 'Schijndel',          'ontvangstdatum' => '2026-03-19', 'aantal_uitgeleverd' => 40, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 180],
            ['product_naam' => 'Gevulde Koek',   'houdbaarheidsdatum' => '2026-05-04', 'barcode' => '8719483321333', 'magazijn_locatie' => 'Schijndel',          'ontvangstdatum' => '2026-03-10', 'aantal_uitgeleverd' => 10, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 120],
            ['product_naam' => 'Fristi',         'houdbaarheidsdatum' => '2026-05-28', 'barcode' => '8719487121331', 'magazijn_locatie' => 'Gemonde',            'ontvangstdatum' => '2026-03-18', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 72],
            ['product_naam' => 'Appelsap',       'houdbaarheidsdatum' => '2026-05-19', 'barcode' => '8719487521335', 'magazijn_locatie' => 'Gemonde',            'ontvangstdatum' => '2026-03-18', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 12],
            ['product_naam' => 'Koffie',         'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719487381228', 'magazijn_locatie' => 'Gemonde',            'ontvangstdatum' => '2026-03-11', 'aantal_uitgeleverd' => 10, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 36],
            ['product_naam' => 'Thee',           'houdbaarheidsdatum' => '2026-05-02', 'barcode' => '8719487329326', 'magazijn_locatie' => 'Gemonde',            'ontvangstdatum' => '2026-04-02', 'aantal_uitgeleverd' => 50, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 230],
            ['product_naam' => 'Pasta',          'houdbaarheidsdatum' => '2026-05-16', 'barcode' => '8719487321334', 'magazijn_locatie' => 'Den Bosch',          'ontvangstdatum' => '2026-04-09', 'aantal_uitgeleverd' => 80, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 250],
            ['product_naam' => 'Rijst',          'houdbaarheidsdatum' => '2026-05-25', 'barcode' => '8719487331332', 'magazijn_locatie' => 'Den Bosch',          'ontvangstdatum' => '2026-04-03', 'aantal_uitgeleverd' => 10, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 24],
            ['product_naam' => 'Knorr Nasi Mix', 'houdbaarheidsdatum' => '2026-05-13', 'barcode' => '8719487335135', 'magazijn_locatie' => 'Den Bosch',          'ontvangstdatum' => '2026-04-16', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 23],
            ['product_naam' => 'Tomatensoep',    'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719487321332', 'magazijn_locatie' => 'Heeswijk Dinther',   'ontvangstdatum' => '2026-04-16', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 46],
            ['product_naam' => 'Tomatensaus',    'houdbaarheidsdatum' => '2026-05-21', 'barcode' => '8719487341334', 'magazijn_locatie' => 'Heeswijk Dinther',   'ontvangstdatum' => '2026-03-14', 'aantal_uitgeleverd' => 20, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 78],
            ['product_naam' => 'Peterselie',     'houdbaarheidsdatum' => '2026-05-31', 'barcode' => '8719487321636', 'magazijn_locatie' => 'Heeswijk Dinther',   'ontvangstdatum' => '2026-04-07', 'aantal_uitgeleverd' => 10, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 46],
            ['product_naam' => 'Olie',           'houdbaarheidsdatum' => '2026-05-27', 'barcode' => '8719487327337', 'magazijn_locatie' => 'Vught',              'ontvangstdatum' => '2026-03-17', 'aantal_uitgeleverd' => 50, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 160],
            ['product_naam' => 'Mars',           'houdbaarheidsdatum' => '2026-05-11', 'barcode' => '8719487324334', 'magazijn_locatie' => 'Vught',              'ontvangstdatum' => '2026-04-05', 'aantal_uitgeleverd' => 5,  'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 19],
            ['product_naam' => 'Biscuit',        'houdbaarheidsdatum' => '2026-05-07', 'barcode' => '8719487311331', 'magazijn_locatie' => 'Vught',              'ontvangstdatum' => '2026-04-07', 'aantal_uitgeleverd' => 20, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 67],
            ['product_naam' => 'Paprika Chips',  'houdbaarheidsdatum' => '2026-05-22', 'barcode' => '871948732lB398', 'magazijn_locatie' => 'Vught',             'ontvangstdatum' => '2026-04-06', 'aantal_uitgeleverd' => 30, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 200],
            ['product_naam' => 'Chocolade reep', 'houdbaarheidsdatum' => '2026-05-21', 'barcode' => '8719487321533', 'magazijn_locatie' => 'Vught',              'ontvangstdatum' => '2026-04-08', 'aantal_uitgeleverd' => 5,  'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 25],
        ]);

        // ── Voorraad ──────────────────────────────────────────────────────────
        DB::table('voorraad')->insert([
            ['product_naam' => 'Aardappel',      'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719587321239', 'magazijn_locatie' => 'Berlicum',           'ontvangstdatum' => '2026-03-12', 'aantal_uitgeleverd' => 5,  'uitleveringsdatum' => '2026-04-01', 'aantal_op_voorraad' => 15],
            ['product_naam' => 'Aardappel',      'houdbaarheidsdatum' => '2026-05-26', 'barcode' => '8719587321239', 'magazijn_locatie' => 'Rosmalen',           'ontvangstdatum' => '2026-04-02', 'aantal_uitgeleverd' => 10, 'uitleveringsdatum' => '2026-04-05', 'aantal_op_voorraad' => 30],
            ['product_naam' => 'Ui',             'houdbaarheidsdatum' => '2026-05-02', 'barcode' => '8719437321335', 'magazijn_locatie' => 'Berlicum',           'ontvangstdatum' => '2026-03-16', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 30],
            ['product_naam' => 'Appel',          'houdbaarheidsdatum' => '2026-05-16', 'barcode' => '8719486321332', 'magazijn_locatie' => 'Berlicum',           'ontvangstdatum' => '2026-04-08', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 25],
            ['product_naam' => 'Banaan',         'houdbaarheidsdatum' => '2026-05-12', 'barcode' => '8719486321332', 'magazijn_locatie' => 'Rosmalen',           'ontvangstdatum' => '2026-04-06', 'aantal_uitgeleverd' => 20, 'uitleveringsdatum' => '2026-04-08', 'aantal_op_voorraad' => 55],
            ['product_naam' => 'Kaas',           'houdbaarheidsdatum' => '2026-03-19', 'barcode' => '8719487421231', 'magazijn_locatie' => 'Sint-MichelsGestel', 'ontvangstdatum' => '2026-04-02', 'aantal_uitgeleverd' => 15, 'uitleveringsdatum' => '2026-04-06', 'aantal_op_voorraad' => 30],
            ['product_naam' => 'Rosbief',        'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719487421331', 'magazijn_locatie' => 'Sint-MichelsGestel', 'ontvangstdatum' => '2026-04-04', 'aantal_uitgeleverd' => 5,  'uitleveringsdatum' => '2026-04-07', 'aantal_op_voorraad' => 55],
            ['product_naam' => 'Melk',           'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719487321332', 'magazijn_locatie' => 'Middelrode',         'ontvangstdatum' => '2026-04-07', 'aantal_uitgeleverd' => 30, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 90],
            ['product_naam' => 'Margarine',      'houdbaarheidsdatum' => '2026-05-02', 'barcode' => '8719486321336', 'magazijn_locatie' => 'Middelrode',         'ontvangstdatum' => '2026-04-01', 'aantal_uitgeleverd' => 10, 'uitleveringsdatum' => '2026-04-05', 'aantal_op_voorraad' => 70],
            ['product_naam' => 'Ei',             'houdbaarheidsdatum' => '2026-05-04', 'barcode' => '8719487421334', 'magazijn_locatie' => 'Middelrode',         'ontvangstdatum' => '2026-03-18', 'aantal_uitgeleverd' => 24, 'uitleveringsdatum' => '2026-04-02', 'aantal_op_voorraad' => 96],
            ['product_naam' => 'Brood',          'houdbaarheidsdatum' => '2026-05-07', 'barcode' => '8719487371337', 'magazijn_locatie' => 'Schijndel',          'ontvangstdatum' => '2026-03-19', 'aantal_uitgeleverd' => 50, 'uitleveringsdatum' => '2026-04-03', 'aantal_op_voorraad' => 170],
            ['product_naam' => 'Gevulde Koek',   'houdbaarheidsdatum' => '2026-05-04', 'barcode' => '8719483321333', 'magazijn_locatie' => 'Schijndel',          'ontvangstdatum' => '2026-03-10', 'aantal_uitgeleverd' => 20, 'uitleveringsdatum' => '2026-04-01', 'aantal_op_voorraad' => 110],
            ['product_naam' => 'Appelsap',       'houdbaarheidsdatum' => '2026-05-19', 'barcode' => '8719487521335', 'magazijn_locatie' => 'Gemonde',            'ontvangstdatum' => '2026-03-18', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 12],
            ['product_naam' => 'Koffie',         'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719487381228', 'magazijn_locatie' => 'Gemonde',            'ontvangstdatum' => '2026-03-18', 'aantal_uitgeleverd' => 10, 'uitleveringsdatum' => '2026-04-04', 'aantal_op_voorraad' => 36],
            ['product_naam' => 'Thee',           'houdbaarheidsdatum' => '2026-05-02', 'barcode' => '8719487329326', 'magazijn_locatie' => 'Gemonde',            'ontvangstdatum' => '2026-03-11', 'aantal_uitgeleverd' => 6,  'uitleveringsdatum' => '2026-04-02', 'aantal_op_voorraad' => 40],
            ['product_naam' => 'Rijst',          'houdbaarheidsdatum' => '2026-05-25', 'barcode' => '8719487331332', 'magazijn_locatie' => 'Den Bosch',          'ontvangstdatum' => '2026-04-09', 'aantal_uitgeleverd' => 80, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 250],
            ['product_naam' => 'Knorr Nasi Mix', 'houdbaarheidsdatum' => '2026-05-13', 'barcode' => '8719487335135', 'magazijn_locatie' => 'Den Bosch',          'ontvangstdatum' => '2026-04-03', 'aantal_uitgeleverd' => 4,  'uitleveringsdatum' => '2026-04-07', 'aantal_op_voorraad' => 30],
            ['product_naam' => 'Tomatensoep',    'houdbaarheidsdatum' => '2026-05-23', 'barcode' => '8719487321332', 'magazijn_locatie' => 'Heeswijk Dinther',   'ontvangstdatum' => '2026-04-02', 'aantal_uitgeleverd' => 28, 'uitleveringsdatum' => '2026-04-08', 'aantal_op_voorraad' => 70],
            ['product_naam' => 'Peterselie',     'houdbaarheidsdatum' => '2026-05-31', 'barcode' => '8719487321636', 'magazijn_locatie' => 'Heeswijk Dinther',   'ontvangstdatum' => '2026-04-07', 'aantal_uitgeleverd' => 6,  'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 50],
            ['product_naam' => 'Olie',           'houdbaarheidsdatum' => '2026-05-27', 'barcode' => '8719487327337', 'magazijn_locatie' => 'Vught',              'ontvangstdatum' => '2026-03-17', 'aantal_uitgeleverd' => 40, 'uitleveringsdatum' => '2026-04-06', 'aantal_op_voorraad' => 170],
            ['product_naam' => 'Mars',           'houdbaarheidsdatum' => '2026-05-11', 'barcode' => '8719487324334', 'magazijn_locatie' => 'Vught',              'ontvangstdatum' => '2026-04-05', 'aantal_uitgeleverd' => 4,  'uitleveringsdatum' => '2026-04-08', 'aantal_op_voorraad' => 20],
            ['product_naam' => 'Biscuit',        'houdbaarheidsdatum' => '2026-05-07', 'barcode' => '8719487311331', 'magazijn_locatie' => 'Vught',              'ontvangstdatum' => '2026-04-07', 'aantal_uitgeleverd' => 17, 'uitleveringsdatum' => '2026-04-09', 'aantal_op_voorraad' => 70],
            ['product_naam' => 'Chocolade reep', 'houdbaarheidsdatum' => '2026-05-21', 'barcode' => '8719487321533', 'magazijn_locatie' => 'Vught',              'ontvangstdatum' => '2026-04-08', 'aantal_uitgeleverd' => 0,  'uitleveringsdatum' => null,         'aantal_op_voorraad' => 30],
        ]);

        // ── ProductPerMagazijn ────────────────────────────────────────────────
        DB::table('product_per_magazijn')->insert([
            ['product_id' => 1,  'magazijn_id' => 1,  'locatie' => 'Berlicum'],
            ['product_id' => 2,  'magazijn_id' => 2,  'locatie' => 'Rosmalen'],
            ['product_id' => 3,  'magazijn_id' => 3,  'locatie' => 'Berlicum'],
            ['product_id' => 4,  'magazijn_id' => 4,  'locatie' => 'Berlicum'],
            ['product_id' => 5,  'magazijn_id' => 5,  'locatie' => 'Rosmalen'],
            ['product_id' => 6,  'magazijn_id' => 6,  'locatie' => 'Berlicum'],
            ['product_id' => 7,  'magazijn_id' => 7,  'locatie' => 'Rosmalen'],
            ['product_id' => 8,  'magazijn_id' => 8,  'locatie' => 'Sint-MichelsGestel'],
            ['product_id' => 9,  'magazijn_id' => 9,  'locatie' => 'Sint-MichelsGestel'],
            ['product_id' => 10, 'magazijn_id' => 10, 'locatie' => 'Middelrode'],
            ['product_id' => 11, 'magazijn_id' => 11, 'locatie' => 'Middelrode'],
            ['product_id' => 12, 'magazijn_id' => 12, 'locatie' => 'Middelrode'],
            ['product_id' => 13, 'magazijn_id' => 13, 'locatie' => 'Schijndel'],
            ['product_id' => 14, 'magazijn_id' => 14, 'locatie' => 'Schijndel'],
            ['product_id' => 15, 'magazijn_id' => 15, 'locatie' => 'Gemonde'],
            ['product_id' => 16, 'magazijn_id' => 16, 'locatie' => 'Gemonde'],
            ['product_id' => 17, 'magazijn_id' => 17, 'locatie' => 'Gemonde'],
            ['product_id' => 18, 'magazijn_id' => 18, 'locatie' => 'Gemonde'],
            ['product_id' => 19, 'magazijn_id' => 19, 'locatie' => 'Den Bosch'],
            ['product_id' => 20, 'magazijn_id' => 20, 'locatie' => 'Den Bosch'],
            ['product_id' => 21, 'magazijn_id' => 21, 'locatie' => 'Den Bosch'],
            ['product_id' => 22, 'magazijn_id' => 22, 'locatie' => 'Heeswijk Dinther'],
            ['product_id' => 23, 'magazijn_id' => 23, 'locatie' => 'Heeswijk Dinther'],
            ['product_id' => 24, 'magazijn_id' => 24, 'locatie' => 'Heeswijk Dinther'],
            ['product_id' => 25, 'magazijn_id' => 25, 'locatie' => 'Vught'],
            ['product_id' => 26, 'magazijn_id' => 26, 'locatie' => 'Vught'],
            ['product_id' => 27, 'magazijn_id' => 27, 'locatie' => 'Vught'],
            ['product_id' => 28, 'magazijn_id' => 28, 'locatie' => 'Vught'],
            ['product_id' => 29, 'magazijn_id' => 29, 'locatie' => 'Vught'],
        ]);
    }
}

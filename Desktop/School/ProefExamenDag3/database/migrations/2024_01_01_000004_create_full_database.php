<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Allergie
        Schema::create('allergie', function (Blueprint $table) {
            $table->id();
            $table->string('naam', 100);
            $table->string('omschrijving', 255)->nullable();
            $table->string('anafylactisch_risico', 50)->nullable();
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
        });

        // Categorie
        Schema::create('categorie', function (Blueprint $table) {
            $table->id();
            $table->string('naam', 10);
            $table->string('omschrijving', 100);
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
        });

        // Eetwens
        Schema::create('eetwens', function (Blueprint $table) {
            $table->id();
            $table->string('naam', 50);
            $table->string('omschrijving', 100)->nullable();
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
        });

        // Rol
        Schema::create('rol', function (Blueprint $table) {
            $table->id();
            $table->string('naam', 50);
            $table->boolean('IsActief')->default(1);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt', 6)->nullable();
            $table->dateTime('DatumGewijzigd', 6)->nullable();
            $table->timestamps();
        });

        // Contact
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->string('straat', 100);
            $table->string('huisnummer', 10);
            $table->string('toevoeging', 10)->nullable();
            $table->string('postcode', 10);
            $table->string('woonplaats', 100);
            $table->string('email', 150)->nullable();
            $table->string('mobiel', 20)->nullable();
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
        });

        // Gezin
        Schema::create('gezin', function (Blueprint $table) {
            $table->id();
            $table->string('naam', 100);
            $table->string('code', 10)->unique();
            $table->string('omschrijving', 100)->nullable();
            $table->integer('aantal_volwassenen')->default(0);
            $table->integer('aantal_kinderen')->default(0);
            $table->integer('aantal_babys')->default(0);
            $table->integer('totaal_aantal_personen')->default(0);
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
        });

        // Leverancier
        Schema::create('leverancier', function (Blueprint $table) {
            $table->id();
            $table->string('naam', 100);
            $table->string('contact_persoon', 100)->nullable();
            $table->string('leverancier_nummer', 20)->unique();
            $table->string('leverancier_type', 50)->nullable();
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
        });

        // Magazijn
        Schema::create('magazijn', function (Blueprint $table) {
            $table->id();
            $table->date('ontvangstdatum')->nullable();
            $table->date('uitleveringsdatum')->nullable();
            $table->string('verpakkings_eenheid', 50)->nullable();
            $table->integer('aantal')->default(0);
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
        });

        // Persoon
        Schema::create('persoon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gezin_id')->nullable();
            $table->string('voornaam', 50);
            $table->string('tussenvoegsel', 20)->nullable();
            $table->string('achternaam', 100);
            $table->date('geboortedatum')->nullable();
            $table->string('type_persoon', 50)->nullable();
            $table->boolean('is_vertegenwoordiger')->default(0);
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('gezin_id')->references('id')->on('gezin')->nullOnDelete();
        });

        // Gebruiker (koppeling aan Persoon) - alleen toevoegen als kolommen nog niet bestaan
        if (!Schema::hasColumn('users', 'persoon_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('persoon_id')->nullable()->after('id');
                $table->string('inlog_naam', 100)->nullable()->after('persoon_id');
                $table->string('gebruikersnaam', 100)->nullable()->after('inlog_naam');
                $table->boolean('is_ingelogd')->default(0)->after('rol');
                $table->timestamp('ingelogd')->nullable()->after('is_ingelogd');
                $table->timestamp('uitgelogd')->nullable()->after('ingelogd');
                $table->foreign('persoon_id')->references('id')->on('persoon')->nullOnDelete();
            });
        }

        // Product
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categorie_id')->nullable();
            $table->string('naam', 100);
            $table->string('soort_allergie', 50)->nullable();
            $table->string('barcode', 50)->nullable();
            $table->date('houdbaarheids_datum')->nullable();
            $table->string('omschrijving', 255)->nullable();
            $table->string('status', 50)->default('OpVoorraad');
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('categorie_id')->references('id')->on('categorie')->nullOnDelete();
        });

        // Voedselpakket
        Schema::create('voedselpakket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gezin_id')->nullable();
            $table->integer('pakket_nummer')->nullable();
            $table->date('datum_samenstelling')->nullable();
            $table->date('datum_uitgifte')->nullable();
            $table->string('status', 50)->default('NietUitgereikt');
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('gezin_id')->references('id')->on('gezin')->nullOnDelete();
        });

        // RolPerGebruiker
        Schema::create('rol_per_gebruiker', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gebruiker_id');
            $table->unsignedBigInteger('rol_id');
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('gebruiker_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('rol_id')->references('id')->on('rol')->cascadeOnDelete();
        });

        // AllergiePerPersoon
        Schema::create('allergie_per_persoon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('persoon_id');
            $table->unsignedBigInteger('allergie_id');
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('persoon_id')->references('id')->on('persoon')->cascadeOnDelete();
            $table->foreign('allergie_id')->references('id')->on('allergie')->cascadeOnDelete();
        });

        // EetwensPerGezin
        Schema::create('eetwens_per_gezin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gezin_id');
            $table->unsignedBigInteger('eetwens_id');
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('gezin_id')->references('id')->on('gezin')->cascadeOnDelete();
            $table->foreign('eetwens_id')->references('id')->on('eetwens')->cascadeOnDelete();
        });

        // ContactPerGezin
        Schema::create('contact_per_gezin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gezin_id');
            $table->unsignedBigInteger('contact_id');
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('gezin_id')->references('id')->on('gezin')->cascadeOnDelete();
            $table->foreign('contact_id')->references('id')->on('contact')->cascadeOnDelete();
        });

        // ContactPerLeverancier
        Schema::create('contact_per_leverancier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leverancier_id');
            $table->unsignedBigInteger('contact_id');
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('leverancier_id')->references('id')->on('leverancier')->cascadeOnDelete();
            $table->foreign('contact_id')->references('id')->on('contact')->cascadeOnDelete();
        });

        // ProductPerLeverancier
        Schema::create('product_per_leverancier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leverancier_id');
            $table->unsignedBigInteger('product_id');
            $table->date('datum_aangeleverd')->nullable();
            $table->date('datum_eerst_volgende_levering')->nullable();
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('leverancier_id')->references('id')->on('leverancier')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('product')->cascadeOnDelete();
        });

        // ProductPerMagazijn
        Schema::create('product_per_magazijn', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('magazijn_id');
            $table->string('locatie', 100)->nullable();
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('product')->cascadeOnDelete();
            $table->foreign('magazijn_id')->references('id')->on('magazijn')->cascadeOnDelete();
        });

        // ProductPerVoedselpakket
        Schema::create('product_per_voedselpakket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voedselpakket_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('aantal_product_eenheden')->default(1);
            $table->boolean('is_actief')->default(1);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->nullable();
            $table->timestamp('datum_gewijzigd')->nullable();
            $table->timestamps();
            $table->foreign('voedselpakket_id')->references('id')->on('voedselpakket')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('product')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_per_voedselpakket');
        Schema::dropIfExists('product_per_magazijn');
        Schema::dropIfExists('product_per_leverancier');
        Schema::dropIfExists('contact_per_leverancier');
        Schema::dropIfExists('contact_per_gezin');
        Schema::dropIfExists('eetwens_per_gezin');
        Schema::dropIfExists('allergie_per_persoon');
        Schema::dropIfExists('rol_per_gebruiker');
        Schema::dropIfExists('voedselpakket');
        Schema::dropIfExists('product');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['persoon_id']);
            $table->dropColumn(['persoon_id', 'inlog_naam', 'gebruikersnaam', 'is_ingelogd', 'ingelogd', 'uitgelogd']);
        });
        Schema::dropIfExists('persoon');
        Schema::dropIfExists('magazijn');
        Schema::dropIfExists('leverancier');
        Schema::dropIfExists('gezin');
        Schema::dropIfExists('contact');
        Schema::dropIfExists('rol');
        Schema::dropIfExists('eetwens');
        Schema::dropIfExists('categorie');
        Schema::dropIfExists('allergie');
    }
};

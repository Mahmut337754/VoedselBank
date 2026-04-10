<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voorraad', function (Blueprint $table) {
            $table->id();
            $table->string('product_naam', 100);
            $table->date('houdbaarheidsdatum');
            $table->string('barcode', 50)->nullable();
            $table->string('magazijn_locatie', 50);
            $table->date('ontvangstdatum');
            $table->integer('aantal_uitgeleverd')->default(0);
            $table->date('uitleveringsdatum')->nullable();
            $table->integer('aantal_op_voorraad')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voorraad');
    }
};

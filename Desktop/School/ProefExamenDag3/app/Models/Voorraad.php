<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voorraad extends Model
{
    protected $table = 'voorraad';

    protected $fillable = [
        'product_naam',
        'houdbaarheidsdatum',
        'barcode',
        'magazijn_locatie',
        'ontvangstdatum',
        'aantal_uitgeleverd',
        'uitleveringsdatum',
        'aantal_op_voorraad',
    ];

    public $timestamps = true;
}

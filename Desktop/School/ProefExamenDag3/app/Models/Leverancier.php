<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Leverancier model – vertegenwoordigt een leverancier van Voedselbank Maaskantje.
 * PSR-12 codeconventie.
 */
class Leverancier extends Model
{
    protected $table = 'leverancier';

    protected $fillable = [
        'naam',
        'contact_persoon',
        'leverancier_nummer',
        'leverancier_type',
        'is_actief',
    ];

    public function contacten(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'contact_per_leverancier');
    }

    public function producten(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_per_leverancier')
                    ->withPivot('datum_aangeleverd', 'datum_eerst_volgende_levering');
    }
}

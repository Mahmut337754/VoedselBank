<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User model met gebruikersrollen.
 * Rollen: manager, medewerker, vrijwilliger
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Mass-assignable velden inclusief rol
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    // Verborgen velden bij serialisatie
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribuut casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Controleer of de gebruiker een manager is.
     */
    public function isAdmin(): bool
    {
        return $this->rol === 'manager';
    }

    /**
     * Controleer of de gebruiker een medewerker of manager is.
     */
    public function isMedewerker(): bool
    {
        return in_array($this->rol, ['manager', 'medewerker']);
    }
}

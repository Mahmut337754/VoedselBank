<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Registratie controller met gebruikersrollen (manager, medewerker, vrijwilliger).
 * PSR-12 codeconventie.
 */
class RegisteredUserController extends Controller
{
    /**
     * Toon het registratieformulier.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Verwerk het registratieformulier en log de gebruiker in.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'rol'      => ['required', 'in:manager,medewerker,vrijwilliger'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol'      => $validated['rol'],
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoorraadController;
use App\Http\Controllers\LeverancierController;

/*
|--------------------------------------------------------------------------
| Web Routes - Voedselbank Maaskantje
|--------------------------------------------------------------------------
| Beveiligd met auth middleware en rol-controle.
| Medewerkers en admins hebben toegang tot de voorraad functionaliteit.
*/

// Redirect root naar dashboard of login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard - toegankelijk voor alle ingelogde gebruikers
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Voorraad routes - beveiligd met auth + rol middleware
Route::middleware(['auth', 'rol:manager,medewerker'])->prefix('voorraad')->name('voorraad.')->group(function () {

    // US-Read: Overzicht van alle voorraadartikelen
    Route::get('/', [VoorraadController::class, 'index'])->name('index');

    // US-Update: Formulier tonen voor bewerken
    Route::get('/{id}/edit', [VoorraadController::class, 'edit'])->name('edit');

    // US-Update: Verwerken van de update
    Route::put('/{id}', [VoorraadController::class, 'update'])->name('update');
});

// Leverancier routes – US-07 Read (manager + medewerker)
Route::middleware(['auth', 'rol:manager,medewerker'])->prefix('leverancier')->name('leverancier.')->group(function () {
    Route::get('/', [LeverancierController::class, 'index'])->name('index');
    Route::get('/{id}', [LeverancierController::class, 'show'])->name('show');
});

// Leverancier routes – US-08 Update (alleen manager)
Route::middleware(['auth', 'rol:manager'])->prefix('leverancier')->name('leverancier.')->group(function () {
    Route::get('/{leverancierId}/product/{productId}/edit', [LeverancierController::class, 'edit'])->name('edit');
    Route::put('/{leverancierId}/product/{productId}', [LeverancierController::class, 'update'])->name('update');
});

// Laravel Breeze auth routes
require __DIR__ . '/auth.php';

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: controleer of de ingelogde gebruiker de juiste rol heeft.
 * Gebruik: Route::middleware('rol:admin') of Route::middleware('rol:admin,medewerker')
 */
class CheckRol
{
    /**
     * Verwerk het inkomende verzoek.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$rollen): Response
    {
        // Controleer of de gebruiker is ingelogd
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $gebruiker = Auth::user();

        // Controleer of de gebruiker een van de vereiste rollen heeft
        if (!in_array($gebruiker->rol, $rollen)) {
            abort(403, 'Toegang geweigerd. U heeft niet de juiste rechten voor deze pagina.');
        }

        return $next($request);
    }
}

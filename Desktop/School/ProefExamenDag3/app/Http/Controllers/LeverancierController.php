<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * LeverancierController – US-07 (Read) en US-08 (Update houdbaarheidsdatum).
 * PSR-12 codeconventie.
 */
class LeverancierController extends Controller
{
    /**
     * US-07 Read: Overzicht van alle leveranciers, optioneel gefilterd op type.
     */
    public function index(Request $request)
    {
        try {
            $geselecteerdType = $request->query('type');

            // Unieke types voor de filterdropdown
            $types = DB::table('leverancier')
                ->whereNotNull('leverancier_type')
                ->where('is_actief', 1)
                ->distinct()
                ->orderBy('leverancier_type')
                ->pluck('leverancier_type');

            // Haal leveranciers op – filter via directe query voor betrouwbaarheid
            $query = DB::table('leverancier as l')
                ->leftJoin('contact_per_leverancier as cpl', 'l.id', '=', 'cpl.leverancier_id')
                ->leftJoin('contact as c', 'cpl.contact_id', '=', 'c.id')
                ->select(
                    'l.id', 'l.naam', 'l.contact_persoon', 'l.leverancier_nummer',
                    'l.leverancier_type', 'c.straat', 'c.huisnummer', 'c.toevoeging',
                    'c.postcode', 'c.woonplaats', 'c.email', 'c.mobiel'
                )
                ->where('l.is_actief', 1)
                ->orderBy('l.naam');

            if ($geselecteerdType) {
                $query->where('l.leverancier_type', $geselecteerdType);
            }

            $leveranciers = $query->get();

            Log::info('[Leverancier] Overzicht bekeken', [
                'gebruiker_id' => auth()->id(),
                'type_filter'  => $geselecteerdType ?? 'alle',
                'aantal'       => $leveranciers->count(),
            ]);

            return view('leverancier.index', compact('leveranciers', 'types', 'geselecteerdType'));

        } catch (\Exception $e) {
            Log::error('[Leverancier] Fout bij ophalen overzicht: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Er is een fout opgetreden bij het ophalen van de leveranciers.');
        }
    }

    /**
     * US-08 stap 1: Producten van een specifieke leverancier.
     */
    public function show(int $id)
    {
        try {
            $leverancier = DB::table('leverancier')->where('id', $id)->first();

            if (!$leverancier) {
                return redirect()->route('leverancier.index')
                    ->with('error', 'Leverancier niet gevonden.');
            }

            $producten = DB::select('CALL sp_get_producten_by_leverancier(?)', [$id]);

            Log::info('[Leverancier] Producten bekeken', [
                'gebruiker_id'   => auth()->id(),
                'leverancier_id' => $id,
                'aantal'         => count($producten),
            ]);

            return view('leverancier.show', compact('producten', 'leverancier'));

        } catch (\Exception $e) {
            Log::error('[Leverancier] Fout bij ophalen producten voor leverancier ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Er is een fout opgetreden bij het ophalen van de producten.');
        }
    }

    /**
     * US-08 stap 2: Bewerkingsformulier houdbaarheidsdatum.
     */
    public function edit(int $leverancierId, int $productId)
    {
        try {
            $leverancier = DB::table('leverancier')->where('id', $leverancierId)->first();

            if (!$leverancier) {
                return redirect()->route('leverancier.index')
                    ->with('error', 'Leverancier niet gevonden.');
            }

            $product = DB::table('product')
                ->leftJoin('categorie', 'product.categorie_id', '=', 'categorie.id')
                ->select(
                    'product.id',
                    'product.naam',
                    'product.houdbaarheids_datum',
                    'product.barcode',
                    'categorie.naam as categorie'
                )
                ->where('product.id', $productId)
                ->first();

            if (!$product) {
                return redirect()->route('leverancier.show', $leverancierId)
                    ->with('error', 'Product niet gevonden.');
            }

            Log::info('[Leverancier] Bewerkingsformulier geopend', [
                'gebruiker_id'   => auth()->id(),
                'leverancier_id' => $leverancierId,
                'product_id'     => $productId,
            ]);

            return view('leverancier.edit', compact('product', 'leverancier'));

        } catch (\Exception $e) {
            Log::error('[Leverancier] Fout bij ophalen bewerkingsformulier: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Er is een fout opgetreden. Probeer het opnieuw.');
        }
    }

    /**
     * US-08 stap 2: Verwerk update houdbaarheidsdatum (max 7 dagen verlenging).
     */
    public function update(Request $request, int $leverancierId, int $productId)
    {
        $validated = $request->validate([
            'nieuwe_datum' => 'required|date',
        ]);

        try {
            $product = DB::table('product')
                ->select('id', 'naam', 'houdbaarheids_datum')
                ->where('id', $productId)
                ->first();

            if (!$product) {
                return redirect()->route('leverancier.show', $leverancierId)
                    ->with('error', 'Product niet gevonden.');
            }

            $huidigeDatum = Carbon::parse($product->houdbaarheids_datum);
            $nieuweDatum  = Carbon::parse($validated['nieuwe_datum']);
            $oudeDatum    = $product->houdbaarheids_datum;

            // 7-dagenregel
            if ($nieuweDatum->gt($huidigeDatum->copy()->addDays(7))) {
                return redirect()->back()
                    ->with(
                        'error',
                        'De houdbaarheidsdatum is niet gewijzigd. De houdbaarheidsdatum mag met maximaal 7 dagen worden verlengd'
                    )
                    ->withInput();
            }

            DB::statement('CALL sp_update_houdbaarheids_datum(?, ?)', [
                $productId,
                $nieuweDatum->format('Y-m-d'),
            ]);

            Log::info('[Leverancier] Houdbaarheidsdatum gewijzigd', [
                'gebruiker_id' => auth()->id(),
                'product_id'   => $productId,
                'oude_datum'   => $oudeDatum,
                'nieuwe_datum' => $nieuweDatum->format('Y-m-d'),
            ]);

            return redirect()->route('leverancier.edit', [$leverancierId, $productId])
                ->with('success', 'De houdbaarbaarheidsdatum is gewijzigd');

        } catch (\Exception $e) {
            Log::error('[Leverancier] Fout bij wijzigen houdbaarheidsdatum: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Er is een fout opgetreden bij het wijzigen. Probeer het opnieuw.')
                ->withInput();
        }
    }
}

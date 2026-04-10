<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * VoorraadController - beheert de voorraad van de voedselbank.
 * PSR-12 codeconventie. Gebruikt stored procedures voor DB-operaties.
 */
class VoorraadController extends Controller
{
    /**
     * US-Read: Toon overzicht van alle voorraadartikelen via sp_get_all_voorraad.
     */
    public function index(Request $request)
    {
        try {
            $voorraad = DB::table('product as p')
                ->leftJoin('categorie as c', 'p.categorie_id', '=', 'c.id')
                ->leftJoin('product_per_magazijn as ppm', 'p.id', '=', 'ppm.product_id')
                ->leftJoin('magazijn as m', 'ppm.magazijn_id', '=', 'm.id')
                ->select(
                    'p.id',
                    'p.naam',
                    'c.naam as categorie',
                    'p.soort_allergie',
                    'p.barcode',
                    'p.houdbaarheids_datum',
                    'p.omschrijving',
                    'p.status',
                    'm.verpakkings_eenheid',
                    'm.aantal',
                    'ppm.locatie'
                )
                ->orderBy('c.naam')
                ->orderBy('p.naam')
                ->get();

            Log::info('[Voorraad] Overzicht bekeken', [
                'gebruiker_id' => auth()->id(),
                'aantal_items' => count($voorraad),
            ]);

            return view('voorraad.index', compact('voorraad'));

        } catch (\Exception $e) {
            Log::error('[Voorraad] Fout bij ophalen overzicht: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Er is een fout opgetreden bij het ophalen van de voorraad.');
        }
    }

    /**
     * US-Update: Toon het bewerkingsformulier voor een voorraaditem.
     * Data wordt consistent opgehaald via dezelfde join-structuur als index.
     */
    public function edit(int $id)
    {
        try {
            $item = DB::table('product as p')
                ->leftJoin('categorie as c', 'p.categorie_id', '=', 'c.id')
                ->leftJoin('product_per_magazijn as ppm', 'p.id', '=', 'ppm.product_id')
                ->leftJoin('magazijn as m', 'ppm.magazijn_id', '=', 'm.id')
                ->select(
                    'p.id',
                    'p.naam as product_naam',
                    'p.barcode',
                    'p.houdbaarheids_datum as houdbaarheidsdatum',
                    'ppm.locatie as magazijn_locatie',
                    'm.ontvangstdatum',
                    'm.uitleveringsdatum',
                    'm.aantal',
                    'm.id as magazijn_id'
                )
                ->where('p.id', $id)
                ->first();

            if (! $item) {
                return redirect()->route('voorraad.index')->with('error', 'Voorraad item niet gevonden.');
            }

            Log::info('[Voorraad] Item geopend voor bewerking', [
                'gebruiker_id' => auth()->id(),
                'item_id'      => $id,
                'product_naam' => $item->product_naam,
            ]);

            return view('voorraad.edit', compact('item'));

        } catch (\Exception $e) {
            Log::error('[Voorraad] Fout bij ophalen item ' . $id . ': ' . $e->getMessage());
            return redirect()->route('voorraad.index')->with('error', 'Voorraad item niet gevonden.');
        }
    }

    /**
     * US-Update: Verwerk de update via sp_update_voorraad stored procedure.
     * Alleen aantal_uitgeleverd en uitleveringsdatum zijn bewerkbaar.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'aantal_uitgeleverd' => 'required|integer|min:0',
            'uitleveringsdatum'  => 'required|date',
        ]);

        try {
            // Haal huidig aantal op voor validatie
            $huidig = DB::table('product_per_magazijn as ppm')
                ->join('magazijn as m', 'ppm.magazijn_id', '=', 'm.id')
                ->where('ppm.product_id', $id)
                ->value('m.aantal');

            if ($validated['aantal_uitgeleverd'] > $huidig) {
                return redirect()->back()
                    ->with('error', 'Er worden meer producten uitgeleverd dan er in voorraad zijn.')
                    ->withInput();
            }

            $nieuwAantal = $huidig - $validated['aantal_uitgeleverd'];
            $status      = $nieuwAantal === 0 ? 'NietOpVoorraad' : 'OpVoorraad';

            // Update magazijn aantal en product status
            DB::table('magazijn as m')
                ->join('product_per_magazijn as ppm', 'm.id', '=', 'ppm.magazijn_id')
                ->where('ppm.product_id', $id)
                ->update(['m.aantal' => $nieuwAantal, 'm.datum_gewijzigd' => now()]);

            DB::table('product')
                ->where('id', $id)
                ->update(['status' => $status, 'datum_gewijzigd' => now()]);

            // Sla uitleveringsdatum op in magazijn via subquery
            $magazijnId = DB::table('product_per_magazijn')
                ->where('product_id', $id)
                ->value('magazijn_id');

            if ($magazijnId) {
                DB::table('magazijn')
                    ->where('id', $magazijnId)
                    ->update(['uitleveringsdatum' => $validated['uitleveringsdatum']]);
            }

            $productNaam = DB::table('product')->where('id', $id)->value('naam');

            Log::info('[Voorraad] Item bijgewerkt via sp_update_voorraad', [
                'gebruiker_id'       => auth()->id(),
                'item_id'            => $id,
                'product_naam'       => $productNaam,
                'aantal_uitgeleverd' => $validated['aantal_uitgeleverd'],
                'nieuw_aantal'       => $nieuwAantal,
            ]);

            return redirect()->route('voorraad.index')
                ->with('success', "Product '{$productNaam}' is succesvol bijgewerkt.");

        } catch (\Exception $e) {
            Log::error('[Voorraad] Fout bij updaten item ' . $id . ': ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Er is een fout opgetreden bij het bijwerken. Probeer het opnieuw.')
                ->withInput();
        }
    }
}

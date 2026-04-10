{{-- Leverancier overzicht – User Story 07: Read --}}
@extends('layouts.app')

@section('title', 'Overzicht Leveranciers - Voedselbank Maaskantje')

@section('content')
<div class="container">

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3">

            {{-- Header + filter rechtsboven --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <a href="{{ route('leverancier.index') }}" class="text-decoration-none" style="color: #2e7d32;">
                        Overzicht Leveranciers
                    </a>
                </h5>
                <form method="GET" action="{{ route('leverancier.index') }}" class="d-flex align-items-center gap-2">
                    <select name="type" id="type" class="form-select form-select-sm" style="min-width: 200px;">
                        <option value="">Selecteer Leveranciertype</option>
                        @php
                            $alleTypes = collect(['Bedrijf', 'Donor', 'Instelling', 'Overheid', 'Particulier']);
                            $extraTypes = $types->diff($alleTypes);
                            $dropdownTypes = $alleTypes->merge($extraTypes)->sort()->values();
                        @endphp
                        @foreach($dropdownTypes as $type)
                            <option value="{{ $type }}" {{ $geselecteerdType === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-secondary btn-sm text-nowrap">Toon Leveranciers</button>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Naam</th>
                            <th>Contactpersoon</th>
                            <th>Email</th>
                            <th>Mobiel</th>
                            <th>Leveranciernummer</th>
                            <th>LeverancierType</th>
                            <th>Product Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($leveranciers->isEmpty() && $geselecteerdType)
                            {{-- Filter actief maar geen resultaten: gele melding --}}
                            <tr>
                                <td colspan="7" class="py-3 text-center"
                                    style="background-color: #fef9e7; color: #555;">
                                    Er zijn geen leveranciers bekent van het geselecteerde leverancierstype
                                </td>
                            </tr>
                        @elseif($leveranciers->isEmpty())
                            {{-- Geen filter, gewoon lege database --}}
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
                                    Geen leveranciers gevonden.
                                </td>
                            </tr>
                        @else
                            @foreach($leveranciers as $leverancier)
                                <tr>
                                    <td>{{ $leverancier->naam }}</td>
                                    <td>{{ $leverancier->contact_persoon ?? '-' }}</td>
                                    <td>{{ $leverancier->email ?? '-' }}</td>
                                    <td>{{ $leverancier->mobiel ?? '-' }}</td>
                                    <td>{{ $leverancier->leverancier_nummer ?? '-' }}</td>
                                    <td>{{ $leverancier->leverancier_type ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('leverancier.show', $leverancier->id) }}"
                                           class="btn btn-sm btn-outline-primary p-1"
                                           title="Bekijk producten van {{ $leverancier->naam }}">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Knoppen rechtsonder --}}
            <div class="d-flex justify-content-end gap-2 mt-3">
                @if($geselecteerdType)
                    <a href="{{ route('leverancier.index') }}" class="btn btn-sm btn-secondary">Terug</a>
                @endif
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">Home</a>
            </div>

        </div>
    </div>

</div>
@endsection

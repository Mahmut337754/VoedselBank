{{-- Voorraad overzicht - User Story 09: Read --}}
@extends('layouts.app')

@section('title', 'Voorraad Overzicht - Voedselbank Maaskantje')

@section('content')
<div class="container">

    {{-- Succesmelding --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Foutmelding --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border shadow-sm">
        <div class="card-body p-3">

            {{-- Header: titel links, filter rechts --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <a href="{{ route('voorraad.index') }}" class="text-decoration-none" style="color: #2e7d32;">
                        Overzicht Productvoorraden
                    </a>
                </h5>

                {{-- Categorie filter --}}
                <form method="GET" action="{{ route('voorraad.index') }}" class="d-flex align-items-center gap-2">
                    <select name="categorie_id" class="form-select form-select-sm" style="min-width: 180px;">
                        <option value="">Selecteer Categorie</option>
                        @foreach($categorieen as $cat)
                            <option value="{{ $cat->id }}"
                                {{ (string)$geselecteerdeCategorie === (string)$cat->id ? 'selected' : '' }}>
                                {{ $cat->naam }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" name="toon_voorraad" value="1" class="btn btn-sm btn-secondary">
                        Toon Voorraad
                    </button>
                </form>
            </div>

            {{-- Scenario_02: geen producten voor geselecteerde categorie --}}
            @if($gefiltered && $geselecteerdeCategorie && $voorraad->isEmpty())
                <div class="alert alert-warning mb-3" role="alert">
                    Er zijn geen producten bekent die behoren bij de geselecteerde productcategorie
                </div>
            @endif

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Productnaam</th>
                            <th>Categorie</th>
                            <th>Eenheid</th>
                            <th>Aantal</th>
                            <th>Houdbaarheidsdatum</th>
                            <th>Voorraad Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($voorraad as $item)
                            <tr>
                                <td>{{ $item->naam }}</td>
                                <td>{{ $item->categorie ?? '-' }}</td>
                                <td>{{ $item->verpakkings_eenheid ?? '-' }}</td>
                                <td>{{ $item->aantal ?? 0 }}</td>
                                <td>{{ $item->houdbaarheids_datum ? \Carbon\Carbon::parse($item->houdbaarheids_datum)->format('d-m-Y') : '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('voorraad.edit', $item->id) }}"
                                       class="btn btn-sm btn-outline-primary p-1"
                                       title="Bewerk {{ $item->naam }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            @if(!$gefiltered || !$geselecteerdeCategorie)
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        Geen voorraad gevonden.
                                    </td>
                                </tr>
                            @endif
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Home knop rechtsonder --}}
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">home</a>
            </div>

        </div>
    </div>

</div>
@endsection

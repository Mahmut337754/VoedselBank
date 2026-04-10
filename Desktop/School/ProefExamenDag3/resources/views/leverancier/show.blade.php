{{-- Overzicht producten per leverancier – User Story 08: stap 1 --}}
@extends('layouts.app')

@section('title', 'Overzicht producten - Voedselbank Maaskantje')

@section('content')
<div class="container">

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3">

            <h5 class="mb-3">
                <a href="#" class="text-decoration-none" style="color: #2e7d32;">
                    Overzicht producten
                </a>
            </h5>

            {{-- Leveranciersinfo --}}
            <table class="table table-bordered table-sm mb-3" style="max-width: 400px;">
                <tr>
                    <th style="width: 160px;">Naam:</th>
                    <td>{{ $leverancier->naam }}</td>
                </tr>
                <tr>
                    <th>Leveranciernummer:</th>
                    <td>{{ $leverancier->leverancier_nummer }}</td>
                </tr>
                <tr>
                    <th>Leveranciertype:</th>
                    <td>{{ $leverancier->leverancier_type ?? '-' }}</td>
                </tr>
            </table>

            {{-- Flash meldingen --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Producten tabel --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Naam</th>
                            <th>Soort Allergie</th>
                            <th>Barcode</th>
                            <th>Houdbaarheidsdatum</th>
                            @if(Auth::user()->rol === 'manager')
                                <th>Wijzig Product</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producten as $product)
                            <tr>
                                <td>{{ $product->naam }}</td>
                                <td>{{ $product->soort_allergie ?? '-' }}</td>
                                <td>{{ $product->barcode ?? '-' }}</td>
                                <td>
                                    {{ $product->houdbaarheids_datum
                                        ? \Carbon\Carbon::parse($product->houdbaarheids_datum)->format('d-m-Y')
                                        : '-' }}
                                </td>
                                @if(Auth::user()->rol === 'manager')
                                    <td class="text-center">
                                        <a href="{{ route('leverancier.edit', [$leverancier->id, $product->id]) }}"
                                           class="btn btn-sm btn-outline-primary p-1"
                                           title="Wijzig houdbaarheidsdatum van {{ $product->naam }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->rol === 'manager' ? 5 : 4 }}"
                                    class="text-center text-muted py-3">
                                    Geen producten gevonden voor deze leverancier.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Knoppen rechtsonder --}}
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('leverancier.index') }}" class="btn btn-sm btn-secondary">terug</a>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">home</a>
            </div>

        </div>
    </div>

</div>
@endsection

{{-- Voorraad bewerken - Wireframe-07: Wijzig Product Details --}}
@extends('layouts.app')

@section('title', 'Wijzig Product Details - Voedselbank Maaskantje')

@section('content')
<div class="container" style="max-width: 700px;">

    <h4 class="mb-3" style="color: #2e7d32; text-decoration: underline;">
        Wijzig Product Details {{ $item->product_naam }}
    </h4>

    {{-- Melding: productgegevens zijn readonly --}}
    <div class="alert" style="background-color: #f8d7da; border: none; color: #333; border-radius: 4px;" role="alert">
        De productgegevens kunnen niet worden gewijzigd
    </div>

    {{-- Foutmelding server-side --}}
    @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('voorraad.update', $item->id) }}" method="POST" id="editForm" novalidate>
        @csrf
        @method('PUT')

        {{-- Productnaam (readonly) --}}
        <div class="row mb-3 align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Productnaam</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" value="{{ $item->product_naam }}" readonly
                       style="background-color: #e8f0fe;">
            </div>
        </div>

        {{-- Houdbaarheidsdatum (readonly) --}}
        <div class="row mb-3 align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Houdbaarheidsdatum</label>
            <div class="col-sm-8">
                <input type="text" class="form-control"
                       value="{{ $item->houdbaarheidsdatum ? \Carbon\Carbon::parse($item->houdbaarheidsdatum)->format('d-m-Y') : '' }}"
                       readonly style="background-color: #e8f0fe;">
            </div>
        </div>

        {{-- Barcode (readonly) --}}
        <div class="row mb-3 align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Barcode</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" value="{{ $item->barcode ?? '' }}" readonly
                       style="background-color: #e8f0fe;">
            </div>
        </div>

        {{-- Magazijn Locatie (readonly dropdown) --}}
        <div class="row mb-3 align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Magazijn Locatie</label>
            <div class="col-sm-8">
                <select class="form-select" disabled style="background-color: #e8f0fe;">
                    <option>{{ $item->magazijn_locatie ?? '-' }}</option>
                </select>
            </div>
        </div>

        {{-- Ontvangstdatum (readonly) --}}
        <div class="row mb-3 align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Ontvangstdatum</label>
            <div class="col-sm-8">
                <input type="text" class="form-control"
                       value="{{ $item->ontvangstdatum ? \Carbon\Carbon::parse($item->ontvangstdatum)->format('d-m-Y') : '' }}"
                       readonly style="background-color: #e8f0fe;">
            </div>
        </div>

        {{-- Aantal uitgeleverde producten (bewerkbaar) --}}
        <div class="row mb-1 align-items-center">
            <label for="aantal_uitgeleverd" class="col-sm-4 col-form-label fw-semibold">
                Aantal uitgeleverde producten:
            </label>
            <div class="col-sm-8">
                <input type="number"
                       class="form-control @error('aantal_uitgeleverd') is-invalid @enderror"
                       id="aantal_uitgeleverd"
                       name="aantal_uitgeleverd"
                       value="{{ old('aantal_uitgeleverd', 0) }}"
                       min="0"
                       max="{{ $item->aantal }}"
                       required>
                @error('aantal_uitgeleverd')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Waarschuwing: meer uitgeleverd dan voorraad --}}
        <div class="row mb-3">
            <div class="col-sm-8 offset-sm-4">
                <small id="voorraadWaarschuwing" class="text-danger" style="display:none;">
                    Er worden meer producten uitgeleverd dan er in voorraad zijn
                </small>
            </div>
        </div>

        {{-- Uitleveringsdatum (bewerkbaar) --}}
        <div class="row mb-3 align-items-center">
            <label for="uitleveringsdatum" class="col-sm-4 col-form-label fw-semibold">
                Uitleveringsdatum
            </label>
            <div class="col-sm-8">
                <input type="date"
                       class="form-control @error('uitleveringsdatum') is-invalid @enderror"
                       id="uitleveringsdatum"
                       name="uitleveringsdatum"
                       value="{{ old('uitleveringsdatum', $item->uitleveringsdatum ? \Carbon\Carbon::parse($item->uitleveringsdatum)->format('Y-m-d') : '') }}"
                       required>
                @error('uitleveringsdatum')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Aantal op voorraad (readonly, berekend) --}}
        <div class="row mb-4 align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Aantal op voorraad</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="aantalOpVoorraad"
                       value="{{ $item->aantal }}"
                       readonly style="background-color: #e8f0fe;">
            </div>
        </div>

        {{-- Knoppen --}}
        <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-primary">
                Wijzig Product Details
            </button>
            <div class="d-flex gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">terug</a>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">home</a>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const aantalInput     = document.getElementById('aantal_uitgeleverd');
    const voorraadDisplay = document.getElementById('aantalOpVoorraad');
    const waarschuwing    = document.getElementById('voorraadWaarschuwing');
    const totaalAantal    = {{ $item->aantal }};

    function updateVoorraad() {
        const uitgeleverd = parseInt(aantalInput.value) || 0;
        const opVoorraad  = totaalAantal - uitgeleverd;

        voorraadDisplay.value = opVoorraad >= 0 ? opVoorraad : 0;

        if (uitgeleverd > totaalAantal) {
            waarschuwing.style.display = 'block';
            aantalInput.classList.add('is-invalid');
        } else {
            waarschuwing.style.display = 'none';
            aantalInput.classList.remove('is-invalid');
        }
    }

    aantalInput.addEventListener('input', updateVoorraad);

    // Formulier validatie
    document.getElementById('editForm').addEventListener('submit', function (e) {
        const uitgeleverd = parseInt(aantalInput.value) || 0;
        if (uitgeleverd > totaalAantal) {
            e.preventDefault();
            waarschuwing.style.display = 'block';
            aantalInput.classList.add('is-invalid');
        }
    });
})();
</script>
@endpush

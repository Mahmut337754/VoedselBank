{{-- Wijzig Product – User Story 08: stap 2 --}}
@extends('layouts.app')

@section('title', 'Wijzig Product - Voedselbank Maaskantje')

@section('content')
<div class="container" style="max-width: 700px;">

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <h5 class="mb-3">
                <a href="#" class="text-decoration-none" style="color: #2e7d32;">
                    Wijzig Product
                </a>
            </h5>

            {{-- Succesmelding (Wireframe-05: groene achtergrond) --}}
            @if(session('success'))
                <div class="alert mb-3" style="background-color: #d4edda; border: none; color: #333; border-radius: 4px;" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Foutmelding (Wireframe-06: roze achtergrond) --}}
            @if(session('error'))
                <div class="alert mb-3" style="background-color: #f8d7da; border: none; color: #333; border-radius: 4px;" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('leverancier.update', [$leverancier->id, $product->id]) }}"
                  method="POST" id="editForm">
                @csrf
                @method('PUT')

                {{-- Houdbaarheidsdatum --}}
                <div class="row mb-1 align-items-center">
                    <label for="nieuwe_datum" class="col-sm-4 col-form-label fw-bold">
                        Houdbaarheidsdatum:
                    </label>
                    <div class="col-sm-8">
                        <input type="date"
                               class="form-control"
                               id="nieuwe_datum"
                               name="nieuwe_datum"
                               value="{{ old('nieuwe_datum', $product->houdbaarheids_datum
                                   ? \Carbon\Carbon::parse($product->houdbaarheids_datum)->format('Y-m-d')
                                   : '') }}"
                               required>
                    </div>
                </div>

                {{-- Client-side waarschuwing (rood, onder het veld) --}}
                <div class="row mb-3">
                    <div class="col-sm-8 offset-sm-4">
                        <small id="datumWaarschuwing" class="text-danger"
                               style="{{ session('error') ? 'display:block' : 'display:none' }}">
                            De houdbaarheidsdatum mag met maximaal 7 dagen worden verlengd
                        </small>
                    </div>
                </div>

                {{-- Knoppen --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="submit" class="btn btn-secondary">
                        Wijzig Houdbaarheidsdatum
                    </button>
                    <div class="d-flex gap-2">
                        <a href="{{ route('leverancier.show', $leverancier->id) }}" class="btn btn-primary">Terug</a>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Home</a>
                    </div>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // Toon client-side waarschuwing maar blokkeer NIET het formulier
    // Server-side validatie geeft de volledige foutmelding terug
    const huidigeDatumStr = '{{ $product->houdbaarheids_datum ?? '' }}';
    const datumInput      = document.getElementById('nieuwe_datum');
    const waarschuwing    = document.getElementById('datumWaarschuwing');

    if (huidigeDatumStr) {
        const huidigeDatum = new Date(huidigeDatumStr);
        const maxDatum     = new Date(huidigeDatum);
        maxDatum.setDate(maxDatum.getDate() + 7);

        datumInput.addEventListener('change', function () {
            if (new Date(this.value) > maxDatum) {
                waarschuwing.style.display = 'block';
            } else {
                waarschuwing.style.display = 'none';
            }
        });
    }
})();
</script>
@endpush

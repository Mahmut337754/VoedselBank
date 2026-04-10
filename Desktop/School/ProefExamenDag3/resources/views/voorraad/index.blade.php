{{-- Voorraad overzicht - User Story: Read --}}
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

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <a href="{{ route('voorraad.index') }}" class="text-decoration-none" style="color: #2e7d32;">
                        Overzicht Productvoorraden
                    </a>
                </h5>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">home</a>
            </div>

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
                            <th>Magazijn</th>
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
                                <td>{{ $item->locatie ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('voorraad.edit', $item->id) }}"
                                       class="btn btn-sm btn-outline-primary p-1"
                                       title="Bewerk {{ $item->naam }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
                                    Geen voorraad gevonden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection

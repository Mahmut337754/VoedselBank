{{-- Dashboard / Homepagina - Voedselbank Maaskantje --}}
@extends('layouts.app')

@section('title', 'Dashboard - Voedselbank Maaskantje')

@section('content')
<div class="container">

    {{-- Welkomstbanner --}}
    <div class="p-4 mb-4 rounded-3" style="background: linear-gradient(135deg, #2c3e50, #3d5166); color: white;">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-basket2-fill" style="font-size: 2.5rem; color: #e87722;"></i>
            <div>
                <h2 class="mb-0 fw-bold">Welkom, {{ Auth::user()->name }}</h2>
                <p class="mb-0 opacity-75">
                    Ingelogd als
                    <span class="badge
                        @if(Auth::user()->rol === 'manager') bg-danger
                        @elseif(Auth::user()->rol === 'medewerker') bg-warning text-dark
                        @else bg-secondary
                        @endif">
                        {{ ucfirst(Auth::user()->rol) }}
                    </span>
                    &mdash; {{ now()->format('d-m-Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Flash meldingen --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Functionaliteiten overzicht --}}
    <h5 class="mb-3 text-muted">Beschikbare functionaliteiten</h5>
    <div class="row g-4">

        {{-- Voorraad - alleen voor admin en medewerker --}}
        @if(Auth::user()->isMedewerker())
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('voorraad.index') }}" class="text-decoration-none">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="mb-3" style="font-size: 2.5rem; color: #e87722;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h6 class="card-title fw-bold">Voorraad</h6>
                        <p class="card-text text-muted small">Bekijk en beheer de voorraad van de voedselbank.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center pb-3">
                        <span class="btn btn-sm btn-outline-warning">Openen <i class="bi bi-arrow-right"></i></span>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Leveranciers - alleen voor admin en medewerker --}}
        @if(in_array(Auth::user()->rol, ['manager', 'medewerker']))
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('leverancier.index') }}" class="text-decoration-none">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="mb-3" style="font-size: 2.5rem; color: #2e7d32;">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h6 class="card-title fw-bold">Overzicht Leveranciers</h6>
                        <p class="card-text text-muted small">Bekijk leveranciers en wijzig houdbaarheidsdatums.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center pb-3">
                        <span class="btn btn-sm btn-outline-success">Openen <i class="bi bi-arrow-right"></i></span>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Klanten (placeholder) --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm opacity-50">
                <div class="card-body text-center py-4">
                    <div class="mb-3" style="font-size: 2.5rem; color: #6c757d;">
                        <i class="bi bi-people"></i>
                    </div>
                    <h6 class="card-title fw-bold">Klanten</h6>
                    <p class="card-text text-muted small">Beheer klantgegevens en uitgifte.</p>
                </div>
                <div class="card-footer bg-transparent border-0 text-center pb-3">
                    <span class="badge bg-secondary">Binnenkort beschikbaar</span>
                </div>
            </div>
        </div>

        {{-- Leveringen (placeholder) --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm opacity-50">
                <div class="card-body text-center py-4">
                    <div class="mb-3" style="font-size: 2.5rem; color: #6c757d;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h6 class="card-title fw-bold">Leveringen</h6>
                    <p class="card-text text-muted small">Registreer inkomende leveringen.</p>
                </div>
                <div class="card-footer bg-transparent border-0 text-center pb-3">
                    <span class="badge bg-secondary">Binnenkort beschikbaar</span>
                </div>
            </div>
        </div>

        {{-- Rapportages (placeholder) --}}
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm opacity-50">
                <div class="card-body text-center py-4">
                    <div class="mb-3" style="font-size: 2.5rem; color: #6c757d;">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <h6 class="card-title fw-bold">Rapportages</h6>
                    <p class="card-text text-muted small">Bekijk statistieken en rapporten.</p>
                </div>
                <div class="card-footer bg-transparent border-0 text-center pb-3">
                    <span class="badge bg-secondary">Binnenkort beschikbaar</span>
                </div>
            </div>
        </div>

    </div>{{-- end row --}}

    {{-- Toegang geweigerd melding voor vrijwilligers --}}
    @if(Auth::user()->rol === 'vrijwilliger')
    <div class="alert alert-info mt-4" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        Als vrijwilliger heeft u beperkte toegang. Neem contact op met een medewerker voor meer informatie.
    </div>
    @endif

</div>
@endsection

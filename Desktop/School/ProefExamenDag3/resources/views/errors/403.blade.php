{{-- 403 Toegang geweigerd pagina --}}
@extends('layouts.app')

@section('title', 'Toegang geweigerd - Voedselbank Maaskantje')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 text-center">
            <div style="font-size: 5rem; color: #e87722;">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h2 class="fw-bold mt-3">Toegang geweigerd</h2>
            <p class="text-muted">U heeft niet de juiste rechten om deze pagina te bekijken.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-warning mt-2">
                <i class="bi bi-house me-1"></i>Terug naar dashboard
            </a>
        </div>
    </div>
</div>
@endsection

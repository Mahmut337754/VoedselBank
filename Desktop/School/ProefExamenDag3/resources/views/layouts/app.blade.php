<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Voedselbank Maaskantje')</title>
    {{-- Bootstrap 5 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Voedselbank huisstijl kleuren */
        :root {
            --vb-oranje: #e87722;
            --vb-donker: #2c3e50;
            --vb-licht:  #f8f9fa;
        }

        body {
            background-color: var(--vb-licht);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.95rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        /* Navbar stijl */
        .navbar-voedselbank {
            background-color: var(--vb-donker);
            border-bottom: 3px solid var(--vb-oranje);
        }

        .navbar-voedselbank .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .navbar-voedselbank .navbar-brand span {
            color: var(--vb-oranje);
        }

        .navbar-voedselbank .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-size: 0.9rem;
        }

        .navbar-voedselbank .nav-link:hover,
        .navbar-voedselbank .nav-link.active {
            color: var(--vb-oranje) !important;
        }

        /* Rol badge */
        .rol-badge {
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* Footer */
        footer {
            background-color: var(--vb-donker);
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
            padding: 12px 0;
            margin-top: 40px;
        }

        /* Card hover effect */
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            transition: all 0.2s ease;
        }
    </style>
</head>
<body>

    {{-- Navigatiebalk --}}
    <nav class="navbar navbar-expand-lg navbar-voedselbank">
        <div class="container">
            {{-- Logo / Brand --}}
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-basket2-fill me-2"></i>Voedselbank <span>Maaskantje</span>
            </a>

            {{-- Hamburger menu voor mobiel --}}
            <button class="navbar-toggler border-secondary" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarMain"
                    aria-controls="navbarMain" aria-expanded="false" aria-label="Menu openen">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navigatie links --}}
            <div class="collapse navbar-collapse" id="navbarMain">
                @auth
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door me-1"></i>Dashboard
                        </a>
                    </li>

                    {{-- Voorraad - alleen voor admin en medewerker --}}
                    @if(Auth::user()->isMedewerker())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('voorraad.*') ? 'active' : '' }}"
                           href="{{ route('voorraad.index') }}">
                            <i class="bi bi-box-seam me-1"></i>Voorraad
                        </a>
                    </li>
                    @endif

                    {{-- Placeholder links voor andere functionaliteiten van het scrumteam --}}
                    <li class="nav-item">
                        <a class="nav-link text-muted" href="#" title="Nog niet beschikbaar">
                            <i class="bi bi-people me-1"></i>Klanten
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-muted" href="#" title="Nog niet beschikbaar">
                            <i class="bi bi-truck me-1"></i>Leveringen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-muted" href="#" title="Nog niet beschikbaar">
                            <i class="bi bi-bar-chart me-1"></i>Rapportages
                        </a>
                    </li>
                </ul>

                {{-- Gebruikersmenu rechts --}}
                <div class="d-flex align-items-center gap-3">
                    {{-- Gebruikersnaam en rol --}}
                    <div class="text-end d-none d-lg-block">
                        <div class="text-white fw-semibold" style="font-size:0.85rem;">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                        </div>
                        <div>
                            @php $rol = Auth::user()->rol; @endphp
                            <span class="rol-badge
                                @if($rol === 'manager') bg-danger
                                @elseif($rol === 'medewerker') bg-warning text-dark
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($rol) }}
                            </span>
                        </div>
                    </div>

                    {{-- Uitloggen --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Uitloggen
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hoofdinhoud --}}
    <main class="py-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer>
        <div class="container text-center">
            &copy; {{ date('Y') }} Voedselbank Maaskantje &mdash; Voedselbank applicatie v1.0
        </div>
    </footer>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - Voedselbank Maaskantje</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            width: 100%;
            max-width: 460px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .register-header {
            background: linear-gradient(135deg, #2c3e50, #3d5166);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 28px 24px;
            text-align: center;
        }

        .register-header .brand-icon {
            font-size: 2.5rem;
            color: #e87722;
        }

        .btn-register {
            background-color: #e87722;
            border-color: #e87722;
            color: white;
            font-weight: 600;
        }

        .btn-register:hover {
            background-color: #cf6a1a;
            border-color: #cf6a1a;
            color: white;
        }
    </style>
</head>
<body>

    <div class="register-card card">
        {{-- Header --}}
        <div class="register-header">
            <div class="brand-icon mb-2">
                <i class="bi bi-basket2-fill"></i>
            </div>
            <h4 class="mb-0 fw-bold">Voedselbank Maaskantje</h4>
            <p class="mb-0 opacity-75 small">Nieuw account aanmaken</p>
        </div>

        {{-- Formulier --}}
        <div class="card-body p-4">
            <h5 class="mb-4 text-center text-muted">Registreren</h5>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                {{-- Naam --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">
                        <i class="bi bi-person me-1"></i>Volledige naam
                    </label>
                    <input id="name"
                           type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Voor- en achternaam"
                           required
                           autofocus
                           autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- E-mailadres --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">
                        <i class="bi bi-envelope me-1"></i>E-mailadres
                    </label>
                    <input id="email"
                           type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="naam@voedselbank.nl"
                           required
                           autocomplete="email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Wachtwoord --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">
                        <i class="bi bi-lock me-1"></i>Wachtwoord
                    </label>
                    <input id="password"
                           type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimaal 8 tekens, letters en cijfers"
                           required
                           autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Wachtwoord bevestigen --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">
                        <i class="bi bi-lock-fill me-1"></i>Wachtwoord bevestigen
                    </label>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           class="form-control"
                           placeholder="Herhaal uw wachtwoord"
                           required
                           autocomplete="new-password">
                </div>

                {{-- Rol --}}
                <div class="mb-4">
                    <label for="rol" class="form-label fw-semibold">
                        <i class="bi bi-shield me-1"></i>Rol
                    </label>
                    <select id="rol"
                            name="rol"
                            class="form-select @error('rol') is-invalid @enderror"
                            required>
                        <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Selecteer een rol</option>
                        <option value="medewerker" {{ old('rol') === 'medewerker' ? 'selected' : '' }}>Medewerker</option>
                        <option value="vrijwilliger" {{ old('rol') === 'vrijwilliger' ? 'selected' : '' }}>Vrijwilliger</option>
                        <option value="manager" {{ old('rol') === 'manager' ? 'selected' : '' }}>Manager</option>
                    </select>
                    @error('rol')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Registreerknop --}}
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-register btn-lg">
                        <i class="bi bi-person-plus me-2"></i>Account aanmaken
                    </button>
                </div>

                {{-- Link naar inloggen --}}
                <div class="text-center">
                    <small class="text-muted">
                        Al een account?
                        <a href="{{ route('login') }}" class="text-decoration-none" style="color: #e87722;">
                            Inloggen
                        </a>
                    </small>
                </div>
            </form>
        </div>

        <div class="card-footer text-center text-muted small py-3 bg-light rounded-bottom">
            &copy; {{ date('Y') }} Voedselbank Maaskantje
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

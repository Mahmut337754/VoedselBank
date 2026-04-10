<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - Voedselbank Maaskantje</title>
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

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .login-header {
            background: linear-gradient(135deg, #2c3e50, #3d5166);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 28px 24px;
            text-align: center;
        }

        .login-header .brand-icon {
            font-size: 2.5rem;
            color: #e87722;
        }

        .btn-login {
            background-color: #e87722;
            border-color: #e87722;
            color: white;
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: #cf6a1a;
            border-color: #cf6a1a;
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-card card">
        {{-- Header --}}
        <div class="login-header">
            <div class="brand-icon mb-2">
                <i class="bi bi-basket2-fill"></i>
            </div>
            <h4 class="mb-0 fw-bold">Voedselbank Maaskantje</h4>
            <p class="mb-0 opacity-75 small">Medewerkers portaal</p>
        </div>

        {{-- Formulier --}}
        <div class="card-body p-4">
            <h5 class="mb-4 text-center text-muted">Inloggen</h5>

            {{-- Foutmeldingen --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

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
                           autofocus
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
                           placeholder="Uw wachtwoord"
                           required
                           autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Onthoud mij --}}
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-muted" for="remember">Onthoud mij</label>
                </div>

                {{-- Inlogknop --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-login btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Inloggen
                    </button>
                </div>
            </form>

            {{-- Link naar registreren --}}
            <div class="text-center mt-3">
                <small class="text-muted">
                    Nog geen account?
                    <a href="{{ route('register') }}" class="text-decoration-none" style="color: #e87722;">
                        Registreren
                    </a>
                </small>
            </div>
        </div>

        {{-- Footer van de kaart --}}
        <div class="card-footer text-center text-muted small py-3 bg-light rounded-bottom">
            Nog geen account?
            <a href="{{ route('register') }}" class="text-decoration-none" style="color: #e87722;">Registreren</a>
            &nbsp;&mdash;&nbsp;
            &copy; {{ date('Y') }} Voedselbank Maaskantje
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

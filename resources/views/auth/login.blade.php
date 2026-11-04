<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Kasir Kafe</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --coffee: #40291f;
            --coffee-light: #624331;
            --cream: #f8f5f0;
            --gold: #c49a60;
            --text: #29231f;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            overflow-x: hidden;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 35px 20px;
            position: relative;
            overflow: hidden;
        }

        .background-glow {
            position: absolute;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: rgba(196, 154, 96, .13);
            filter: blur(100px);
            top: -150px;
            right: -100px;
            animation: float 8s ease-in-out infinite;
        }

        .background-glow.bottom {
            top: auto;
            right: auto;
            bottom: -180px;
            left: -120px;
            background: rgba(91, 56, 40, .10);
            animation-delay: 2s;
        }

        .login-container {
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 80px rgba(54, 37, 26, .12);
            position: relative;
            z-index: 2;
            animation: slideUp .8s ease both;
        }

        /* BRAND PANEL */
       
.brand-panel {
    position: relative;
    overflow: hidden;
    color: white;
    padding: 55px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    isolation: isolate;
}

/* Layer foto */
.brand-panel::before,
.brand-panel::after {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -2;
    background-size: cover;
    background-position: center;
    animation: coffeeSlideshow 12s infinite ease-in-out;
}

/* Foto pertama */
.brand-panel::before {
    background-image: url('https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=1200&q=85');
    animation-delay: 0s;
}

/* Foto kedua */
.brand-panel::after {
    background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=85');
    animation-delay: -6s;
}

/* Overlay gelap */
.brand-panel .brand-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        135deg,
        rgba(39, 25, 18, .72),
        rgba(39, 25, 18, .88)
    );
    z-index: -1;
}

/* Animasi foto */
@keyframes coffeeSlideshow {
    0% {
        opacity: 0;
        transform: scale(1);
    }

    8% {
        opacity: 1;
    }

    42% {
        opacity: 1;
        transform: scale(1.08);
    }

    50% {
        opacity: 0;
        transform: scale(1.1);
    }

    100% {
        opacity: 0;
        transform: scale(1);
    }
}

        .brand-top {
            animation: fadeIn 1s ease .3s both;
        }

        .brand-icon {
            width: 55px;
            height: 55px;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 26px;
            margin-bottom: 25px;
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(8px);
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 43px;
            line-height: 1.2;
            margin-bottom: 18px;
        }

        .brand-title span {
            color: var(--gold);
        }

        .brand-description {
            color: rgba(255,255,255,.72);
            line-height: 1.8;
            font-size: 14px;
            max-width: 330px;
        }

        .brand-footer {
            color: rgba(255,255,255,.55);
            font-size: 12px;
            border-top: 1px solid rgba(255,255,255,.15);
            padding-top: 20px;
        }

        /* LOGIN PANEL */
        .login-panel {
            padding: 65px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: fadeIn 1s ease .4s both;
        }

        .welcome-label {
            color: var(--gold);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .login-heading {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 600;
            color: var(--coffee);
            margin-bottom: 10px;
        }

        .login-subtitle {
            font-size: 13px;
            color: #999;
            margin-bottom: 35px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #55463d;
            margin-bottom: 9px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 23px;
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #aa9585;
            z-index: 3;
            font-size: 16px;
        }

        .form-control {
            height: 52px;
            border: 1px solid #eee8e1;
            border-radius: 11px !important;
            background: #fcfbf9;
            padding-left: 45px;
            padding-right: 45px;
            font-size: 13px;
            transition: all .3s ease;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: var(--gold);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(196,154,96,.12) !important;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #aa9585;
            z-index: 4;
            cursor: pointer;
        }

        .toggle-password:hover {
            color: var(--coffee);
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: -4px;
            margin-bottom: 27px;
        }

        .form-check-input:checked {
            background-color: var(--coffee);
            border-color: var(--coffee);
        }

        .form-check-label {
            font-size: 12px;
            color: #8c817a;
        }

        .btn-login {
            height: 53px;
            width: 100%;
            border: none;
            border-radius: 11px;
            color: #fff;
            background: linear-gradient(135deg, var(--coffee), #6a4733);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .5px;
            position: relative;
            overflow: hidden;
            transition: all .35s ease;
            box-shadow: 0 8px 20px rgba(64,41,31,.18);
        }

        .btn-login:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(64,41,31,.25);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            margin-left: 7px;
            transition: transform .3s ease;
        }

        .btn-login:hover i {
            transform: translateX(5px);
        }

        .login-bottom {
            text-align: center;
            color: #b0a69e;
            font-size: 11px;
            margin-top: 35px;
        }

        .alert {
            border: none;
            border-radius: 10px;
            font-size: 12px;
        }

        /* ANIMATION */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(35px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(35px); }
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 450px;
                min-height: auto;
            }

            .brand-panel {
                min-height: 220px;
                padding: 30px;
            }

            .brand-icon {
                width: 45px;
                height: 45px;
                margin-bottom: 15px;
                font-size: 21px;
            }

            .brand-title {
                font-size: 29px;
                margin-bottom: 8px;
            }

            .brand-description {
                font-size: 12px;
                margin-bottom: 15px;
            }

            .brand-footer {
                display: none;
            }

            .login-panel {
                padding: 40px 30px;
            }

            .login-heading {
                font-size: 28px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="background-glow"></div>
    <div class="background-glow bottom"></div>

    <div class="login-container">

        <!-- BRAND PANEL -->
        <div class="brand-panel">

    <div class="brand-overlay"></div>

            <div class="brand-top">
                <div class="brand-icon">
                    <i class="bi bi-cup-hot"></i>
                </div>

                <h1 class="brand-title">
                    Good Coffee,<br>
                    <span>Good Moments.</span>
                </h1>

                <p class="brand-description">
                    Kelola operasional cafe dengan lebih mudah,
                    cepat, dan terorganisir melalui satu sistem
                    manajemen kasir.
                </p>
            </div>

            <div class="brand-footer">
                <i class="bi bi-c-circle"></i>
                {{ date('Y') }} sitedigipiro. All rights reserved.
            </div>

        </div>

        <!-- LOGIN PANEL -->
        <div class="login-panel">

            <div class="welcome-label">Welcome Back</div>

            <h2 class="login-heading">Selamat Datang!</h2>

            <p class="login-subtitle">
                Silakan masuk untuk mengakses sistem kasir.
            </p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="login" class="form-label">
                        Username atau Email
                    </label>

                    <div class="input-group-custom">
                        <i class="bi bi-person input-icon"></i>

                        <input
                            type="text"
                            name="login"
                            id="login"
                            class="form-control @error('login') is-invalid @enderror"
                            value="{{ old('login') }}"
                            placeholder="Masukkan username atau email"
                            autocomplete="username"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="input-group-custom">
                        <i class="bi bi-lock input-icon"></i>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Tampilkan password"
                        >
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            name="remember"
                            class="form-check-input"
                            id="remember"
                        >
                        <label class="form-check-label" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    Masuk ke Dashboard
                    <i class="bi bi-arrow-right"></i>
                </button>

            </form>

            <div class="login-bottom">
                Secure Access · Cafe Management System
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';

        eyeIcon.classList.toggle('bi-eye', !isPassword);
        eyeIcon.classList.toggle('bi-eye-slash', isPassword);

        togglePassword.setAttribute(
            'aria-label',
            isPassword ? 'Sembunyikan password' : 'Tampilkan password'
        );
    });
</script>

</body>
</html>

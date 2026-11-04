
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Kasir Kafe') | Kasir Kafe</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --coffee: #5b3828;
            --cream: #f7f3ef;
        }

        body {
            background: var(--cream);
            color: #292522;
        }

        .kiosk-layout {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        body:has(.kiosk-layout) {
            background: #f7f5f1;
        }

        .navbar-coffee {
            background: var(--coffee);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: .2px;
        }

        .card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 5px 22px rgba(48,32,20,.06);
        }

        .btn-coffee {
            background: var(--coffee);
            color: white;
        }

        .btn-coffee:hover {
            background: #43291e;
            color: white;
        }

        .page-title {
            font-weight: 750;
        }

        .table thead th {
            background: #f4eee8;
            white-space: nowrap;
        }

        .money {
            font-variant-numeric: tabular-nums;
        }

        @media print {
            .no-print, nav, .alert {
                display: none !important;
            }

            body {
                background: white;
            }

            .card {
                box-shadow: none;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

@php
    $isKioskPage = request()->routeIs(
        'customer.order',
        'customer.receipt'
    );
@endphp

{{-- NAVBAR: hanya tampil di halaman admin dan kasir --}}
@if (!$isKioskPage)

<nav class="navbar navbar-expand-lg navbar-dark navbar-coffee no-print">
    <div class="container">

        <a class="navbar-brand" href="{{ route('dashboard') }}">
            ☕ Kasir Kafe
        </a>

        <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <div class="navbar-nav ms-auto align-items-center">

                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('kasir.create') }}">Kasir</a>
                <a class="nav-link" href="{{ route('transaksi.index') }}">Transaksi</a>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a class="nav-link" href="{{ route('produk.index') }}">Menu (Produk)</a>
                    <a class="nav-link" href="{{ route('laporan.index') }}">Laporan</a>
                @endif

                @auth
                    <span class="nav-link text-warning-emphasis ms-lg-2">
                        <strong>{{ auth()->user()->name }}</strong>
                        <span class="badge bg-light text-dark ms-1">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </span>

                    <form action="{{ route('logout') }}"
                        method="POST"
                        class="d-inline ms-lg-2">
                        @csrf

                        <button class="btn btn-sm btn-outline-light" type="submit">
                            Logout
                        </button>
                    </form>
                @endauth

            </div>
        </div>
    </div>
</nav>

@endif

{{-- KONTEN HALAMAN --}}
<main class="{{ $isKioskPage ? 'kiosk-layout' : 'container py-4' }}">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show no-print">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show no-print">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger no-print">
            <strong>Periksa kembali:</strong>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>

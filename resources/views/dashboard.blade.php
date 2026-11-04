@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 page-title mb-1">Dashboard</h1>
        <div class="text-secondary">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }}).
        </div>
    </div>
    <a class="btn btn-coffee" href="{{ route('kasir.create') }}">＋ Transaksi Baru</a>
</div>

{{-- GRAFIK DASHBOARD --}}
<div class="row g-3 mb-4">

    {{-- Grafik Omzet --}}
    @if(auth()->user()->role === 'admin')
    <div class="col-lg-8">
        <div class="card p-4 h-100 chart-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h5 fw-bold mb-1">Grafik Omzet</h2>
                    <p class="text-secondary small mb-0">
                        Pendapatan selama 7 hari terakhir
                    </p>
                </div>
                <span class="badge rounded-pill bg-light text-dark">
                    <i class="bi bi-calendar3 me-1"></i> 7 Hari
                </span>
            </div>

            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    @endif

    {{-- Grafik Jumlah Transaksi --}}
    <div class="{{ auth()->user()->role === 'admin' ? 'col-lg-4' : 'col-lg-12' }}">
        <div class="card p-4 h-100 chart-card">
            <div class="mb-4">
                <h2 class="h5 fw-bold mb-1">Aktivitas Transaksi</h2>
                <p class="text-secondary small mb-0">
                    Jumlah transaksi 7 hari terakhir
                </p>
            </div>

            <div class="chart-container">
                <canvas id="transactionChart"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- JIKA ADMIN: Tampilkan Statistik Lengkap --}}
@if(auth()->user()->role === 'admin')
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-4">
                <div class="text-secondary">Omzet Hari Ini</div>
                <div class="fs-3 fw-bold money">Rp {{ number_format($todayRevenue,0,',','.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <div class="text-secondary">Total Transaksi Hari Ini</div>
                <div class="fs-3 fw-bold">{{ number_format($todayTransactions) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <div class="text-secondary">Jumlah Menu Aktif</div>
                <div class="fs-3 fw-bold">{{ number_format($productCount) }}</div>
            </div>
        </div>
    </div>

{{-- JIKA PENGGUNA / KASIR: Tampilkan ringkasan operasional kasir saja --}}
@else
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card p-4 bg-white">
                <div class="text-secondary">Total Transaksi Hari Ini</div>
                <div class="fs-3 fw-bold text-primary">{{ number_format($todayTransactions) }} Pesanan</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 bg-white">
                <div class="text-secondary">Menu Kasir Cepat</div>
                <div class="mt-2">
                    <a href="{{ route('kasir.create') }}" class="btn btn-sm btn-coffee me-2">Buka Layar Kasir</a>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-outline-secondary">Riwayat Transaksi</a>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Tabel Transaksi Terakhir --}}
<div class="card p-3 p-md-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Transaksi Terbaru Hari Ini</h2>
        <a href="{{ route('transaksi.index') }}">Lihat semua</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>No. Nota</th>
                    <th>Tanggal</th>
                    <th>Pembayaran</th>
                    <th class="text-end">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $trx)
                <tr>
                    <td>{{ $trx->invoice_number }}</td>
                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td><span class="badge bg-secondary">{{ strtoupper($trx->payment_method) }}</span></td>
                    <td class="text-end money">Rp {{ number_format($trx->total,0,',','.') }}</td>
                    <td><a class="btn btn-sm btn-outline-secondary" href="{{ route('transaksi.show',$trx) }}">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada transaksi hari ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const labels = @json($chartLabels);
    const revenue = @json($chartRevenue);
    const transactions = @json($chartTransactions);

    // Grafik Omzet Admin
    const revenueCanvas = document.getElementById('revenueChart');

    if (revenueCanvas) {
        new Chart(revenueCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omzet',
                    data: revenue,
                    borderColor: '#624331',
                    backgroundColor: 'rgba(196, 154, 96, 0.15)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#c49a60'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Omzet: Rp ' +
                                    new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' +
                                    new Intl.NumberFormat('id-ID', {
                                        notation: 'compact'
                                    }).format(value);
                            }
                        },
                        grid: {
                            color: '#f1ede8'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    // Grafik Transaksi Admin dan User
    const transactionCanvas = document.getElementById('transactionChart');

    if (transactionCanvas) {
        new Chart(transactionCanvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: transactions,
                    backgroundColor: '#b58a5a',
                    hoverBackgroundColor: '#624331',
                    borderRadius: 7,
                    maxBarThickness: 35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#f1ede8'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

});
</script>
@endpush
@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')

<div class="container py-4">

    {{-- Tombol navigasi dan cetak --}}
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="{{ route('transaksi.index') }}" class="text-decoration-none">
            ← Riwayat Transaksi
        </a>

        <button onclick="window.print()" class="btn btn-outline-dark">
            <i class="bi bi-printer"></i> Cetak Struk
        </button>
    </div>

    {{-- Area struk --}}
    <div class="card shadow-sm border-0 p-4 mx-auto receipt-card">

        {{-- Header --}}
        <div class="text-center mb-4">
            <h1 class="h3 fw-bold mb-1">KASIR KAFE</h1>
            <div class="text-secondary">Struk Pembayaran</div>
        </div>

        {{-- Informasi transaksi --}}
        <div class="receipt-info">
            <div class="d-flex justify-content-between gap-3 mb-2">
                <div> <strong>No. Antrean</strong>
            <h2>{{ $transaction->order_number ?? '-' }}</h2>
            </div>

            <div>
             <strong>No. Nota</strong>
                {{ $transaction->invoice_number }}
            </div>

            <div class="d-flex justify-content-between gap-3 mb-2">
                <span>Tanggal</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
            </div>

            <div class="d-flex justify-content-between gap-3 mb-2">
                <span>Pelanggan</span>
                <span>{{ $transaction->customer_name ?: '-' }}</span>
            </div>
        </div>

        <hr>

        {{-- Daftar menu --}}
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($transaction->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>

                            <td class="text-end">
                                {{ $item->quantity }}
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr>

        {{-- Ringkasan pembayaran --}}
        <div class="ms-auto receipt-summary">

            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal</span>
                <span>
                    Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                </span>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>Diskon</span>
                <span>
                    Rp {{ number_format($transaction->discount, 0, ',', '.') }}
                </span>
            </div>

            <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-2 mt-2 mb-3">
                <span>Total</span>
                <span>
                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                </span>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>Metode Pembayaran</span>
                <span>{{ strtoupper($transaction->payment_method) }}</span>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>Diterima</span>
                <span>
                    Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}
                </span>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>Kembalian</span>
                <span>
                    Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
                </span>
            </div>

        </div>

        {{-- Catatan transaksi --}}
        @if($transaction->notes)
            <hr>

            <div class="mt-2">
                <strong>Catatan:</strong>
                {{ $transaction->notes }}
            </div>
        @endif

        {{-- Footer struk --}}
        <div class="text-center mt-4 receipt-footer">
            <p class="mb-2 fw-semibold">
                Terima kasih, sampai jumpa kembali!
            </p>

            <div class="small text-secondary">
                <div>WiFi: HilltopsBatu</div>
                <div>Password: hilltopsamericano</div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
    .receipt-card {
        max-width: 760px;
        width: 100%;
        border-radius: 12px;
    }

    .receipt-summary {
        max-width: 320px;
        width: 100%;
    }

    .receipt-info {
        font-size: 14px;
    }

    .receipt-footer {
        border-top: 1px dashed #ddd;
        padding-top: 20px;
    }

    @media print {
        body {
            background: #fff !important;
        }

        .no-print,
        nav,
        header,
        footer {
            display: none !important;
        }

        .container {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }

        .receipt-card {
            max-width: 100% !important;
            width: 100% !important;
            border: none !important;
            box-shadow: none !important;
            padding: 10px !important;
        }

        .table {
            font-size: 12px;
        }

        .receipt-info {
            font-size: 12px;
        }

        @page {
            size: auto;
            margin: 10mm;
        }
    }
</style>
@endpush
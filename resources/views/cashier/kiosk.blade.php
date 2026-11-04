@extends('layouts.app')

@section('title', 'Antrean Kiosk')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Antrean Pembayaran Kiosk</h1>
            <div class="text-secondary">
                Pesanan yang menunggu pembayaran di kasir.
            </div>
        </div>

        <a href="{{ route('kasir.create') }}" class="btn btn-outline-secondary">
            Kembali ke Kasir
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
    <div class="alert alert-warning">
        {{ session('error') }}
    </div>
@endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            @forelse($transactions as $transaction)
                <div class="border rounded p-3 mb-3">

                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="text-secondary small">Nomor Antrean</div>
                            <h3 class="fw-bold mb-1">
                                {{ $transaction->order_number }}
                            </h3>
                        </div>

                        <div class="col-md-3">
                            <div class="text-secondary small">Pelanggan</div>
                            <strong>
                                {{ $transaction->customer_name ?: '-' }}
                            </strong>
                        </div>

                        <div class="col-md-3">
                            <div class="text-secondary small">Total Tagihan</div>
                            <strong class="text-success">
                                Rp {{ number_format($transaction->total, 0, ',', '.') }}
                            </strong>
                            <div class="small text-secondary">
                                {{ strtoupper($transaction->payment_method) }}
                            </div>
                        </div>

                        <div class="col-md-3 text-md-right mt-3 mt-md-0">
                            <a href="{{ route('kasir.kiosk.detail', $transaction->id) }}"
                               class="btn btn-warning">
                                Proses Pembayaran
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="small text-secondary">
                        {{ $transaction->items->sum('quantity') }} item
                        &bull;
                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                    </div>

                </div>
            @empty
                <div class="text-center py-5">
                    <h5>Tidak ada antrean pembayaran.</h5>
                    <p class="text-secondary mb-0">
                        Pesanan kiosk yang belum dibayar akan muncul di sini.
                    </p>
                </div>
            @endforelse

            <div class="mt-3">
                {{ $transactions->links() }}
            </div>

        </div>
    </div>
</div>
@endsection

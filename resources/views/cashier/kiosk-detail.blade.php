@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <a href="{{ route('kasir.kiosk') }}" class="text-decoration-none">
            &larr; Kembali ke Antrean
        </a>

        <h1 class="h3 mt-3 mb-1">Konfirmasi Pembayaran</h1>
        <div class="text-secondary">
            Periksa pesanan sebelum menerima pembayaran.
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm p-4 mb-3">
                <h5 class="mb-3">Informasi Pesanan</h5>

                <div class="row mb-3">
                    <div class="col-6 text-secondary">Nomor Antrean</div>
                    <div class="col-6 font-weight-bold">
                        {{ $transaction->order_number }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6 text-secondary">Nomor Invoice</div>
                    <div class="col-6">
                        {{ $transaction->invoice_number }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6 text-secondary">Nama Pelanggan</div>
                    <div class="col-6">
                        {{ $transaction->customer_name ?: '-' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6 text-secondary">Metode Pembayaran</div>
                    <div class="col-6">
                        {{ strtoupper($transaction->payment_method) }}
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Detail Menu</h5>

                @foreach($transaction->items as $item)
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <strong>{{ $item->product_name }}</strong>
                            <div class="small text-secondary">
                                {{ $item->quantity }} x
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <strong>
                            Rp {{ number_format($item->line_total, 0, ',', '.') }}
                        </strong>
                    </div>
                @endforeach

                <hr>

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

                <div class="d-flex justify-content-between border-top pt-3 mt-2">
                    <h5>Total Tagihan</h5>
                    <h5 class="text-success">
                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-lg-5">

            <div class="card border-0 shadow-sm p-4">
                <h5 class="mb-3">Pembayaran</h5>

                @if($transaction->payment_method === 'qris')
                    <div class="alert alert-info">
                        Pastikan pembayaran QRIS sudah diterima sebelum
                        mengonfirmasi transaksi.
                    </div>
                @else
                    <div class="alert alert-warning">
                        Masukkan nominal uang yang diterima dari pelanggan.
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('kasir.kiosk.confirm', $transaction->id) }}"
                      id="paymentForm">

                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Total Tagihan</label>
                        <input type="text"
                               class="form-control"
                               value="Rp {{ number_format($transaction->total, 0, ',', '.') }}"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Uang Diterima (Rp)</label>

                        <input type="number"
                               class="form-control"
                               name="amount_paid"
                               id="amountPaid"
                               min="{{ $transaction->total }}"
                               value="{{ $transaction->payment_method === 'qris' ? $transaction->total : '' }}"
                               required>

                        @error('amount_paid')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Kembalian</label>
                        <h3 class="text-success" id="changeAmount">
                            Rp 0
                        </h3>
                    </div>

                    <button type="submit"
                            class="btn btn-success btn-lg w-100"
                            onclick="return confirm('Pastikan pembayaran sudah diterima. Konfirmasi sekarang?')">
                        Konfirmasi Pembayaran
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const total = {{ (int) $transaction->total }};
    const amountInput = document.getElementById('amountPaid');
    const changeElement = document.getElementById('changeAmount');
    const isCash = @json($transaction->payment_method === 'cash');

    function formatRupiah(value) {
        return 'Rp ' + Number(value).toLocaleString('id-ID');
    }

    function calculateChange() {
        const paid = Number(amountInput.value || 0);

        const change = isCash
            ? Math.max(0, paid - total)
            : 0;

        changeElement.textContent = formatRupiah(change);
    }

    amountInput.addEventListener('input', calculateChange);

    calculateChange();
</script>
@endpush

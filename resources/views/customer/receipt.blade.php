
@extends('layouts.app')

@section('title', 'Bukti Pesanan - Kasir Kafe')

@section('content')

<style>
    body {
        background: #f7f5f1;
    }

    .receipt-wrapper {
        max-width: 650px;
        margin: 40px auto;
        padding: 0 15px;
    }

    .receipt-card {
        background: white;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 5px 25px rgba(0,0,0,.07);
    }

    .receipt-header {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 2px dashed #ded2c7;
    }

    .receipt-header h2 {
        color: #654936;
        font-weight: 800;
    }

    .queue-number {
        background: #654936;
        color: white;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        margin: 25px 0;
    }

    .queue-number small {
        display: block;
        opacity: .85;
    }

    .queue-number h1 {
        font-size: 42px;
        font-weight: 800;
        margin: 5px 0 0;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin: 12px 0;
    }

    .receipt-item {
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }

    .receipt-total {
        border-top: 2px dashed #ded2c7;
        padding-top: 18px;
        margin-top: 18px;
        font-size: 20px;
        font-weight: 800;
        color: #654936;
    }

    .payment-info {
        background: #faf8f5;
        padding: 16px;
        border-radius: 12px;
        margin-top: 20px;
    }

    .btn-print {
        background: #654936;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 700;
    }

    .btn-print:hover {
        background: #432f24;
        color: white;
    }

    @media print {
        body {
            background: white;
        }

        .receipt-wrapper {
            margin: 0 auto;
        }

        .receipt-card {
            box-shadow: none;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

<div class="receipt-wrapper">

    <div class="receipt-card">

        <div class="receipt-header">
            <h2>☕ KASIR KAFE</h2>
            <p class="text-muted mb-1">Bukti Pemesanan</p>
            <small>{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
        </div>

        <div class="queue-number">
            <small>NOMOR ANTREAN KAMU</small>
            <h1>{{ $transaction->order_number }}</h1>
        </div>

        <div class="receipt-row">
            <span>Nomor Invoice</span>
            <strong>{{ $transaction->invoice_number }}</strong>
        </div>

        <div class="receipt-row">
            <span>Nama Pelanggan</span>
            <strong>{{ $transaction->customer_name }}</strong>
        </div>

        <div class="receipt-row">
            <span>Metode Pembayaran</span>
            <strong>
                {{ $transaction->payment_method == 'cash' ? 'Bayar di Kasir' : 'QRIS' }}
            </strong>
        </div>

        <hr>

        <h5 class="font-weight-bold">Detail Pesanan</h5>

        @foreach ($transaction->items as $item)
            <div class="receipt-item">
                <div class="receipt-row mb-1">
                    <strong>{{ $item->product_name }}</strong>
                    <strong>
                        Rp {{ number_format($item->line_total, 0, ',', '.') }}
                    </strong>
                </div>

                <small class="text-muted">
                    {{ $item->quantity }} x
                    Rp {{ number_format($item->price, 0, ',', '.') }}
                </small>
            </div>
        @endforeach

        <div class="receipt-row receipt-total">
            <span>Total</span>
            <span>
                Rp {{ number_format($transaction->total, 0, ',', '.') }}
            </span>
        </div>

        <div class="payment-info">

    <strong>Status Pembayaran:</strong>

   <span id="paymentStatusBadge" class="badge bg-warning text-dark">
    Menunggu Pembayaran
</span>

    <p id="paymentInstruction" class="mt-3 mb-0">
        Silakan menuju kasir untuk melakukan pembayaran.
    </p>


    <div id="paymentSuccess"
         class="alert alert-success mt-3"
         style="display:none;">

        <h5 class="font-weight-bold">
            ✓ Pembayaran Berhasil!
        </h5>

        <p class="mb-1">
            Pesanan kamu sudah dikonfirmasi oleh kasir.
        </p>

        <p class="mb-0">
            Kembali ke menu dalam
            <strong id="countdown">10</strong> detik.
        </p>
    </div>

</div>

</div>

<script>
    const statusUrl = @json($statusUrl);
    const orderUrl = @json(route('customer.order'));

    let pollingTimer = null;
    let countdownTimer = null;
    let remainingSeconds = 30;

    async function checkPaymentStatus() {
        try {
            const response = await fetch(statusUrl, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal memeriksa status pesanan.');
            }

            const result = await response.json();

            if (result.payment_status === 'paid') {
                clearInterval(pollingTimer);
                showPaymentSuccess();
            }

            if (
                result.payment_status === 'failed' ||
                result.order_status === 'cancelled'
            ) {
                clearInterval(pollingTimer);

                document.getElementById('paymentStatusBadge')
    .className = 'badge bg-danger';

                document.getElementById('paymentStatusBadge')
                    .textContent = 'Pesanan Dibatalkan';

                document.getElementById('paymentInstruction')
                    .textContent =
                    'Silakan hubungi kasir untuk informasi lebih lanjut.';
            }

        } catch (error) {
            console.error(error);
        }
    }

    function showPaymentSuccess() {
        const badge = document.getElementById('paymentStatusBadge');

        badge.className = 'badge bg-success';
        badge.textContent = 'LUNAS';

        document.getElementById('paymentInstruction')
            .textContent = 'Terima kasih sudah melakukan pembayaran!';

        document.getElementById('paymentSuccess').style.display = 'block';

        countdownTimer = setInterval(function() {
            remainingSeconds--;

            document.getElementById('countdown').textContent =
                remainingSeconds;

            if (remainingSeconds <= 0) {
                clearInterval(countdownTimer);
                window.location.href = orderUrl;
            }
        }, 1000);
    }

    // Periksa status pertama kali
    checkPaymentStatus();

    // Periksa kembali setiap 3 detik
    pollingTimer = setInterval(checkPaymentStatus, 3000);

            <div class="payment-info">

            <strong>Status Pembayaran:</strong>

            <span id="paymentStatusBadge" class="badge bg-warning text-dark">
                Menunggu Pembayaran
            </span>

            <p id="paymentInstruction" class="mt-3 mb-0">
                Silakan menuju kasir untuk melakukan pembayaran.
            </p>

            <div id="paymentSuccess"
                 class="alert alert-success mt-3"
                 style="display:none;">

                <h5 class="fw-bold">
                    ✓ Pembayaran Berhasil!
                </h5>

                <p class="mb-1">
                    Pesanan kamu sudah dikonfirmasi oleh kasir.
                </p>

                <p class="mb-0">
                    Kembali ke menu dalam
                    <strong id="countdown">30</strong> detik.
                </p>
            </div>

        </div>

    </div>
</div>
</script>

@endsection


@extends('layouts.app')

@section('title', 'Struk Pesanan - Kasir Kafe')

@section('content')

<style>

    .payment-status {
    text-align: center;
    border: 1px solid #eab308;
    background: #fef9c3;
    color: #854d0e;
    padding: 10px;
    font-weight: bold;
    margin: 12px 0;
    border-radius: 5px;
    letter-spacing: 1px;
}

/* Animasi saat menunggu pembayaran */
.payment-status.waiting {
    animation: pulseWaiting 1.5s infinite;
}

@keyframes pulseWaiting {
    0% {
        box-shadow: 0 0 0 0 rgba(234, 179, 8, .45);
        opacity: 1;
    }

    70% {
        box-shadow: 0 0 0 8px rgba(234, 179, 8, 0);
        opacity: .75;
    }

    100% {
        box-shadow: 0 0 0 0 rgba(234, 179, 8, 0);
        opacity: 1;
    }
}

/* Status lunas */
.payment-status.paid {
    background: #dcfce7;
    color: #166534;
    border-color: #16a34a;
    animation: none;
}

/* Status dibatalkan */
.payment-status.cancelled {
    background: #fee2e2;
    color: #991b1b;
    border-color: #dc2626;
    animation: none;
}

    body {
        background: #e9e9e9;
        color: #111;
        font-family: Arial, sans-serif;
    }

    .receipt-page {
        padding: 35px 15px;
        display: flex;
        justify-content: center;
    }

    .thermal-receipt {
        width: 340px;
        background: #fff;
        padding: 25px 20px;
        box-shadow: 0 3px 15px rgba(0,0,0,.12);
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #111;
    }

    .center {
        text-align: center;
    }

    .brand {
        font-family: Arial, sans-serif;
        font-size: 23px;
        font-weight: 900;
        letter-spacing: 2px;
        margin-bottom: 4px;
    }

    .brand-subtitle {
        font-size: 10px;
        letter-spacing: 1px;
    }

    .receipt-logo {
        font-size: 27px;
        margin-bottom: 8px;
    }

    .divider {
        border-top: 1px dashed #111;
        margin: 13px 0;
    }

    .double-divider {
        border-top: 2px solid #111;
        margin: 13px 0;
    }

    .row-receipt {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        margin: 5px 0;
    }

    .row-receipt span:last-child {
        text-align: right;
        overflow-wrap: anywhere;
    }

    .queue-box {
        border: 2px solid #111;
        padding: 12px 5px;
        margin: 15px 0;
        text-align: center;
    }

    .queue-label {
        font-size: 10px;
        font-weight: bold;
        letter-spacing: 2px;
    }

    .queue-number {
        font-size: 31px;
        font-weight: 900;
        margin-top: 5px;
        letter-spacing: 2px;
    }

    .section-label {
        font-weight: bold;
        margin-bottom: 8px;
    }

    .item {
        margin: 10px 0;
    }

    .item-name {
        font-weight: bold;
        overflow-wrap: anywhere;
    }

    .item-detail {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        margin-top: 3px;
    }

    .total-row {
        font-size: 15px;
        font-weight: 900;
        margin-top: 10px;
    }

    .payment-status {
        text-align: center;
        border: 1px solid #111;
        padding: 8px;
        font-weight: bold;
        margin: 12px 0;
    }

    .account-box {
        border: 1px dashed #111;
        padding: 9px;
        margin-top: 10px;
    }

    .receipt-footer {
        text-align: center;
        line-height: 1.7;
        font-size: 11px;
    }

    .print-actions {
        width: 340px;
        margin: 0 auto 35px;
    }

    .btn-print {
        background: #654936;
        color: white;
        border: none;
        width: 100%;
        padding: 13px;
        border-radius: 8px;
        font-weight: bold;
    }

    .btn-print:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    #returnMessage {
        display: none;
        text-align: center;
        font-size: 12px;
        margin-top: 12px;
    }

    
.qris-payment {
    text-align: center;
    margin: 18px 0;
    padding: 12px;
    border: 1px dashed #654936;
    border-radius: 8px;
}

.qris-payment img {
    display: block;
    width: 210px;
    max-width: 100%;
    height: auto;
    margin: 12px auto;
}

.qris-payment .qris-amount {
    font-size: 17px;
    font-weight: bold;
}

.qris-payment small {
    display: block;
    margin-top: 8px;
    line-height: 1.5;
}

@media print {
    .qris-payment {
        display: none !important;
    }
}


    @media print {
        @page {
            size: 80mm auto;
            margin: 0;
        }

        html, body {
            width: 80mm !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        body * {
            visibility: hidden;
        }

        .receipt-page,
        .receipt-page * {
            visibility: visible;
        }

        .receipt-page {
            display: block;
            width: 80mm !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .thermal-receipt {
            width: 80mm !important;
            max-width: 80mm !important;
            padding: 5mm 4mm !important;
            margin: 0 !important;
            box-shadow: none !important;
            font-size: 11px;
        }

        .print-actions {
            display: none !important;
        }
    }
</style>

<div class="receipt-page">
    <div class="thermal-receipt">

        {{-- HEADER KAFE --}}
        <div class="center">
            <div class="receipt-logo">☕</div>

            <div class="brand">KASIR KAFE</div>

            <div class="brand-subtitle">
                COFFEE • FOOD • GOOD MOOD
            </div>

            <div style="margin-top:8px;">
                BUKTI PEMBAYARAN
            </div>
        </div>

        <div class="double-divider"></div>

        {{-- INFORMASI TRANSAKSI --}}
        <div class="row-receipt">
            <span>Tanggal</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>

        <div class="row-receipt">
            <span>Invoice</span>
            <span>{{ $transaction->invoice_number }}</span>
        </div>

        <div class="row-receipt">
            <span>Pelanggan</span>
            <span>{{ $transaction->customer_name }}</span>
        </div>

        <div class="row-receipt">
            <span>Pembayaran</span>
            <span>
                {{ $transaction->payment_method == 'cash' ? 'Tunai / Kasir' : 'QRIS' }}
            </span>
        </div>

        {{-- NOMOR ANTREAN --}}
        <div class="queue-box">
            <div class="queue-label">NOMOR ANTREAN</div>
            <div class="queue-number">
                {{ $transaction->order_number }}
            </div>
        </div>

        {{-- DETAIL PESANAN --}}
        <div class="section-label">DETAIL PESANAN</div>

        <div class="divider"></div>

        @foreach ($transaction->items as $item)
            <div class="item">
                <div class="item-name">
                    {{ $item->product_name }}
                </div>

                <div class="item-detail">
                    <span>
                        {{ $item->quantity }} x
                        {{ number_format($item->price, 0, ',', '.') }}
                    </span>

                    <span>
                        {{ number_format($item->line_total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @endforeach

        <div class="divider"></div>

        {{-- TOTAL --}}
        <div class="row-receipt total-row">
            <span>TOTAL</span>
            <span>
                Rp {{ number_format($transaction->total, 0, ',', '.') }}
            </span>
        </div>

       
{{-- STATUS PEMBAYARAN --}}
<div class="payment-status waiting" id="paymentStatusBadge">
    <i class="fas fa-clock"></i>
    MENUNGGU PEMBAYARAN
</div>

{{-- INSTRUKSI TUNAI --}}
<div
    class="center"
    id="paymentInstruction"
    @if($transaction->payment_method === 'qris') style="display:none;" @endif
>
    Silakan menuju kasir untuk melakukan pembayaran.
</div>

{{-- QRIS --}}
@if($transaction->payment_method === 'qris')
    <div class="qris-payment" id="qrisPayment">
        <strong>SCAN QRIS UNTUK MEMBAYAR</strong>

        @if($transaction->qris_url)
            <img
                src="{{ $transaction->qris_url }}"
                alt="QR Code Pembayaran"
                id="qrisImage"
            >
        @else
            <p>QR Code belum tersedia. Silakan hubungi kasir.</p>
        @endif

        <div class="qris-amount">
            Rp {{ number_format($transaction->total, 0, ',', '.') }}
        </div>

        <small>
            Buka aplikasi pembayaran dan scan QR Code di atas.
            <br>
            Halaman ini akan memperbarui status pembayaran otomatis.
        </small>
    </div>
@endif


        {{-- PEMBAYARAN BERHASIL --}}
        <div id="paymentSuccess" style="display:none;">

            <div class="double-divider"></div>

            <div class="center" style="font-weight:bold;">
                *** PEMBAYARAN BERHASIL ***
            </div>

            <p class="center" style="margin-top:8px;">
                Terima kasih atas pesanan Anda!
            </p>

            <div class="account-box">
                <div class="center" style="font-weight:bold; margin-bottom:7px;">
                    INFORMASI AKUN
                </div>

                <div class="row-receipt">
                    <span>Username</span>
                    <strong>HilltopsBatu</strong>
                </div>

                <div class="row-receipt">
                    <span>Password</span>
                    <strong>Americano</strong>
                </div>
            </div>
        </div>

        <div class="double-divider"></div>

        {{-- FOOTER --}}
        <div class="receipt-footer">
            Simpan struk ini sebagai bukti pesanan.

            <br><br>

            Terima kasih telah berkunjung!

            <br>

            <strong>KASIR KAFE</strong>

            <br><br>

            ***** HAVE A NICE DAY *****
        </div>

    </div>
</div>

{{-- TOMBOL CETAK --}}
<div class="print-actions no-print">
    <button
        type="button"
        class="btn-print"
        id="printButton"
        onclick="printReceipt()"
        disabled>

        <i class="fas fa-print"></i>
        Cetak Struk
    </button>

    <p id="returnMessage">
        Kembali ke halaman pemesanan dalam
        <strong id="returnCountdown">10</strong> detik...
    </p>
</div>

<script>
    
const statusUrl = @json($statusUrl);
const orderUrl = @json(route('customer.order'));

const paymentMethod = @json($transaction->payment_method);

let pollingTimer = null;
let countdownTimer = null;
let paymentIsPaid = false;
let returnCountdownStarted = false;

function printReceipt() {
    if (!paymentIsPaid) {
        alert('Pembayaran belum dikonfirmasi.');
        return;
    }

    window.print();
}

window.addEventListener('afterprint', function () {
    if (paymentIsPaid) {
        startReturnCountdown();
    }
});

function startReturnCountdown() {
    if (returnCountdownStarted) return;

    returnCountdownStarted = true;

    const message = document.getElementById('returnMessage');
    const countdown = document.getElementById('returnCountdown');

    let seconds = 10;

    message.style.display = 'block';

    countdownTimer = setInterval(function () {
        seconds--;
        countdown.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(countdownTimer);
            window.location.href = orderUrl;
        }
    }, 1000);
}

function setPaid() {
    if (paymentIsPaid) return;

    paymentIsPaid = true;

    clearInterval(pollingTimer);

    const badge = document.getElementById('paymentStatusBadge');

    badge.className = 'payment-status paid';
    badge.innerHTML =
        '<i class="fas fa-check-circle"></i> LUNAS';

    document.getElementById('paymentInstruction')
        .textContent = 'Pembayaran telah berhasil dikonfirmasi.';

    document.getElementById('paymentInstruction')
        .style.display = 'block';

    document.getElementById('paymentSuccess')
        .style.display = 'block';

    document.getElementById('printButton')
        .disabled = false;

    // Sembunyikan QRIS setelah pembayaran berhasil
    const qrisPayment = document.getElementById('qrisPayment');

    if (qrisPayment) {
        qrisPayment.style.display = 'none';
    }

    // Untuk sementara tampilkan tombol cetak.
    // Cetak otomatis tanpa dialog akan dihubungkan
    // setelah konfigurasi QZ Tray selesai.
}

function setCancelled() {
    clearInterval(pollingTimer);

    const badge = document.getElementById('paymentStatusBadge');

    badge.className = 'payment-status cancelled';
    badge.innerHTML =
        '<i class="fas fa-times-circle"></i> DIBATALKAN';

    document.getElementById('paymentInstruction')
        .textContent =
        'Pesanan dibatalkan atau pembayaran gagal. Silakan hubungi kasir.';

    document.getElementById('paymentInstruction')
        .style.display = 'block';

    document.getElementById('printButton').disabled = true;

    const qrisPayment = document.getElementById('qrisPayment');

    if (qrisPayment) {
        qrisPayment.style.display = 'none';
    }
}

async function checkPaymentStatus() {
    if (paymentIsPaid) return;

    try {
        const response = await fetch(statusUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Gagal mengambil status pembayaran.');
        }

        const result = await response.json();

        if (result.payment_status === 'paid') {
            setPaid();
            return;
        }

        if (
            result.payment_status === 'failed' ||
            result.order_status === 'cancelled'
        ) {
            setCancelled();
            return;
        }

    } catch (error) {
        console.error('Pengecekan pembayaran:', error);
    }
}

// Cek langsung ketika halaman dibuka,
// lalu periksa setiap 3 detik.
checkPaymentStatus();

pollingTimer = setInterval(checkPaymentStatus, 3000);

</script>

@endsection

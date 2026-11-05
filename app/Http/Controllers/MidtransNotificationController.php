<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Ambil Server Key dari konfigurasi
        $serverKey = config('services.midtrans.server_key');

        // Validasi signature dari Midtrans
        $signature = hash(
            'sha512',
            ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            $serverKey
        );

        if (
            empty($serverKey) ||
            !hash_equals($signature, $payload['signature_key'] ?? '')
        ) {
            return response()->json([
                'message' => 'Invalid signature'
            ], 403);
        }

        // Cari transaksi berdasarkan invoice_number
        $transaction = Transaction::where(
            'invoice_number',
            $payload['order_id'] ?? ''
        )->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        // Pastikan transaksi memang QRIS dari kiosk
        if (
            $transaction->order_type !== 'kiosk' ||
            $transaction->payment_method !== 'qris'
        ) {
            return response()->json([
                'message' => 'Invalid transaction type'
            ], 422);
        }

        // Pastikan nominal sesuai dengan transaksi
        if (
            (float) $payload['gross_amount'] !==
            (float) number_format($transaction->total, 2, '.', '')
        ) {
            return response()->json([
                'message' => 'Amount mismatch'
            ], 422);
        }

        $midtransStatus = $payload['transaction_status'] ?? '';

        DB::transaction(function () use ($transaction, $payload, $midtransStatus) {

            $locked = Transaction::where('id', $transaction->id)
                ->lockForUpdate()
                ->first();

            // Idempotent: jangan memproses transaksi lunas berulang
            if ($locked->payment_status === 'paid') {
                return;
            }

            $locked->midtrans_transaction_id =
                $payload['transaction_id'] ?? $locked->midtrans_transaction_id;

            $locked->midtrans_status = $midtransStatus;

            // QRIS berhasil
            if (
                $midtransStatus === 'settlement' &&
                ($payload['status_code'] ?? '') === '200' &&
                strtolower($payload['fraud_status'] ?? 'accept') === 'accept'
            ) {
                $locked->payment_status = 'paid';
                $locked->status = 'paid';
                $locked->order_status = 'waiting';
                $locked->amount_paid = $locked->total;
                $locked->change_amount = 0;
            }

            // Pembayaran kedaluwarsa atau ditolak
            elseif (in_array($midtransStatus, ['expire', 'deny', 'cancel'])) {
                $locked->payment_status = 'failed';
                $locked->status = 'cancelled';
                $locked->order_status = 'cancelled';
            }

            $locked->save();
        });

        return response()->json([
            'message' => 'Notification processed'
        ]);
    }}
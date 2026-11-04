<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\OrderNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CashierController extends Controller
{
    public function create()
    {
        $products = Product::with('category')
            ->where('is_available', true)->where('stock', '>', 0)
            ->orderBy('name')->get();

        return view('cashier.create', compact('products'));
    }

   public function store(Request $request, OrderNumberService $orderNumberService)
    {
        $data = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cash,qris,transfer'],
            'amount_paid' => ['nullable', 'integer', 'min:0'],
            'discount' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'string'],
        ]);

        $items = json_decode($data['items'], true);
        if (!is_array($items) || count($items) < 1) {
            throw ValidationException::withMessages(['items' => 'Keranjang masih kosong atau tidak valid.']);
        }

        $transaction = DB::transaction(function () use ($data, $items, $orderNumberService) {
            $subtotal = 0;
            $prepared = [];

            foreach ($items as $item) {
                $productId = filter_var($item['id'] ?? null, FILTER_VALIDATE_INT);
                $quantity = filter_var($item['quantity'] ?? null, FILTER_VALIDATE_INT);
                if (!$productId || !$quantity || $quantity < 1) {
                    throw ValidationException::withMessages(['items' => 'Item atau jumlah produk tidak valid.']);
                }

                $product = Product::whereKey($productId)->lockForUpdate()->first();
                if (!$product || !$product->is_available) {
                    throw ValidationException::withMessages(['items' => 'Salah satu produk tidak tersedia. Muat ulang halaman kasir.']);
                }
                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages(['items' => "Stok {$product->name} tidak mencukupi. Stok saat ini: {$product->stock}."]);
                }

                $lineTotal = $product->price * $quantity;
                $subtotal += $lineTotal;
                $prepared[] = compact('product', 'quantity', 'lineTotal');
            }

            $discount = (int) ($data['discount'] ?? 0);
            if ($discount > $subtotal) {
                throw ValidationException::withMessages(['discount' => 'Diskon tidak boleh lebih besar daripada subtotal.']);
            }
            $total = $subtotal - $discount;
            $amountPaid = $data['payment_method'] === 'cash' ? (int) ($data['amount_paid'] ?? 0) : $total;

            if ($amountPaid < $total) {
                throw ValidationException::withMessages(['amount_paid' => 'Uang yang diterima kurang dari total pembayaran.']);
            }

            $orderNumber = $orderNumberService->generate();
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'customer_name' => $data['customer_name'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'amount_paid' => $amountPaid,
                'change_amount' => $data['payment_method'] === 'cash' ? $amountPaid - $total : 0,
                'status' => 'paid',
                'notes' => $data['notes'] ?? null,
                'order_number' => $orderNumber,
                'order_type' => 'cashier',
                'payment_status' => 'paid',
                'order_status' => 'completed',
            ]);

            foreach ($prepared as $row) {
                /** @var \App\Models\Product $product */
                $product = $row['product'];
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $row['quantity'],
                    'price' => $product->price,
                    'line_total' => $row['lineTotal'],
                ]);
                $product->decrement('stock', $row['quantity']);
            }

            return $transaction;
        });

        return redirect()->route('transaksi.show', $transaction)->with('success', 'Transaksi berhasil disimpan.');
    }

    // DAFTAR PESANAN KIOSK YANG BELUM DIBAYAR
public function kioskOrders()
{
    $transactions = Transaction::with('items')
        ->where('order_type', 'kiosk')
        ->where('payment_status', 'pending')
        ->where('status', 'pending')
        ->orderBy('created_at', 'asc')
        ->paginate(15);

    return view('cashier.kiosk', compact('transactions'));
}


// DETAIL PESANAN KIOSK SEBELUM PEMBAYARAN
public function kioskDetail(Transaction $transaction)
{
    abort_unless(
        $transaction->order_type === 'kiosk' &&
        $transaction->payment_status === 'pending',
        404
    );

    $transaction->load('items');

    return view('cashier.kiosk-detail', compact('transaction'));
}


// KONFIRMASI PEMBAYARAN KIOSK
public function confirmKioskPayment(
    Request $request,
    Transaction $transaction
) {
    $data = $request->validate([
        'amount_paid' => ['required', 'integer', 'min:0'],
    ]);

    DB::transaction(function () use ($transaction, $data) {

        $order = Transaction::whereKey($transaction->id)
            ->lockForUpdate()
            ->firstOrFail();

        abort_unless(
            $order->order_type === 'kiosk' &&
            $order->payment_status === 'pending' &&
            $order->status === 'pending',
            404
        );

        $amountPaid = (int) $data['amount_paid'];
        $total = (int) $order->total;

        if ($amountPaid < $total) {
            throw ValidationException::withMessages([
                'amount_paid' => 'Uang yang diterima kurang dari total tagihan.'
            ]);
        }

        $order->update([
            'amount_paid' => $amountPaid,
            'change_amount' => $order->payment_method === 'cash'
                ? $amountPaid - $total
                : 0,

            'status' => 'paid',
            'payment_status' => 'paid',
        ]);
    });

    return redirect()
        ->route('transaksi.show', $transaction->id)
        ->with('success', 'Pembayaran berhasil dikonfirmasi.');
}

    public function index()
    {
        $transactions = Transaction::latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }

  public function show(Transaction $transaction)
{
    // Pesanan kiosk yang belum dibayar tidak boleh melihat struk
    if (
        $transaction->order_type === 'kiosk' &&
        $transaction->payment_status !== 'paid'
    ) {
        return redirect()
            ->route('kasir.kiosk')
            ->with('error', 'Struk belum dapat dilihat karena pembayaran belum dikonfirmasi.');
    }

    $transaction->load('items');

    return view('transactions.show', compact('transaction'));
}
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Services\OrderNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerOrderController extends Controller
{
    // Menampilkan menu customer
    public function index()
    {
        $products = Product::with('category')
            ->where('is_available', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $productData = $products->map(function ($product) {
            return [
                'id' => (int) $product->id,
                'name' => $product->name,
                'price' => (int) $product->price,
                'stock' => (int) $product->stock,
                'category' => optional($product->category)->name ?: 'Lainnya',
            ];
        })->values();

        return view('customer.order', compact('products', 'productData'));
    }

    // Menyimpan pesanan customer
    public function checkout(
        Request $request,
        OrderNumberService $orderNumberService
    ) {
        $data = $request->validate([
            'customer_name' => 'required|string|max:100',
            'payment_method' => 'required|in:cash,qris',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|distinct|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $transaction = DB::transaction(function () use (
            $data,
            $orderNumberService
        ) {
            $subtotal = 0;
            $orderItems = [];

            // Urutkan ID agar penguncian stok konsisten
            $items = collect($data['items'])->sortBy('id');

            foreach ($items as $item) {
                $product = Product::where('id', $item['id'])
                    ->lockForUpdate()
                    ->first();

                if (
                    !$product ||
                    !$product->is_available ||
                    $product->stock < $item['quantity']
                ) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok menu ' .
                            ($product ? $product->name : '') .
                            ' tidak mencukupi. Silakan periksa pesanan.',
                    ]);
                }

                $price = (int) $product->price;
                $quantity = (int) $item['quantity'];
                $lineTotal = $price * $quantity;

                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'line_total' => $lineTotal,
                ];

                // Reservasi stok ketika pesanan dibuat
                $product->decrement('stock', $quantity);
            }

            // Nomor antrean bersama dengan transaksi kasir
            $orderNumber = $orderNumberService->generate();

            // Nomor invoice
            $invoiceNumber = 'INV-' .
                now()->format('YmdHis') . '-' .
                Str::upper(Str::random(4));

          $transaction = Transaction::create([
    'invoice_number' => $invoiceNumber,
    'order_number' => $orderNumber,
    'order_date' => now()->toDateString(),
    'order_type' => 'kiosk',
    'customer_name' => $data['customer_name'],
    'subtotal' => $subtotal,
    'discount' => 0,
    'total' => $subtotal,
    'payment_method' => $data['payment_method'],
    'amount_paid' => 0,
    'change_amount' => 0,
    'status' => 'pending',
    'payment_status' => 'pending',
    'order_status' => 'waiting',
    'notes' => 'Pesanan dari Customer Kiosk',
]);
            // Simpan detail produk pesanan
            $transaction->items()->createMany($orderItems);

            return $transaction;
        });

        // Link bukti pesanan dengan masa berlaku 2 jam
        $receiptUrl = URL::temporarySignedRoute(
            'customer.receipt',
            now()->addHours(2),
            ['transaction' => $transaction->id]
        );

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat.',
            'invoice_number' => $transaction->invoice_number,
            'order_number' => $transaction->order_number,
            'redirect_url' => $receiptUrl,
        ]);
    }

public function receipt(Transaction $transaction)
{
    abort_unless($transaction->order_type === 'kiosk', 404);

    $transaction->load('items');

    $statusUrl = URL::temporarySignedRoute(
        'customer.status',
        now()->addHours(2),
        ['transaction' => $transaction->id]
    );

    return view('customer.receipt', compact('transaction', 'statusUrl'));
}

public function status(Transaction $transaction)
{
    abort_unless($transaction->order_type === 'kiosk', 404);

    return response()->json([
        'success' => true,
        'payment_status' => $transaction->payment_status,
        'order_status' => $transaction->order_status,
        'order_number' => $transaction->order_number,
        'total' => $transaction->total,
    ]);
}}
@extends('layouts.app')
@section('title', 'Kasir')

@section('content')

<div class="mb-4">
    <h1 class="h3 page-title mb-1">Kasir</h1>
    <div class="text-secondary">Pilih menu untuk dimasukkan ke keranjang.</div>
</div>

<form method="POST" action="{{ route('kasir.store') }}" id="checkoutForm">
    @csrf
    <input type="hidden" name="items" id="itemsInput">

    <div class="row g-4">

        {{-- DAFTAR PRODUK --}}
        <div class="col-lg-7">
            <div class="card p-3 p-md-4">

                <input id="productSearch"
                    class="form-control mb-3"
                    placeholder="Cari nama menu...">

                <div class="row g-3" id="productGrid">

                    @forelse($products as $product)

                    <div class="col-sm-6 col-md-6 col-xl-4 product-card"
                        data-name="{{ strtolower($product->name) }}">

                        <button type="button"
                            class="product-item add-product"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock }}"
                            data-image="{{ $product->image ? asset('storage/'.$product->image) : '' }}">

                            {{-- GAMBAR PRODUK --}}
                            <div class="product-image-wrapper">
                                @if($product->image)
                                    <img
                                        src="{{ asset('storage/'.$product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="product-image">
                                @else
                                    <div class="product-placeholder">
                                        ☕
                                    </div>
                                @endif
                            </div>

                            {{-- INFORMASI PRODUK --}}
                            <div class="product-info">
                                <div class="fw-semibold product-name">
                                    {{ $product->name }}
                                </div>

                                <div class="small text-secondary">
                                    {{ $product->category->name }}
                                </div>

                                <div class="small text-secondary mt-1">
                                    Stok: {{ $product->stock }}
                                </div>

                                <div class="mt-2 fw-bold text-success">
                                    Rp {{ number_format($product->price,0,',','.') }}
                                </div>
                            </div>

                        </button>
                    </div>

                    @empty
                        <div class="col-12 text-secondary">
                            Tidak ada menu aktif dengan stok tersedia.
                            Tambahkan stok pada halaman Menu.
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

        {{-- KERANJANG --}}
        <div class="col-lg-5">
            <div class="card p-3 p-md-4">

                <h2 class="h5 mb-3">Keranjang Belanja</h2>

                <div id="cartItems" class="mb-3">
                    <div class="text-secondary small">
                        Belum ada menu dipilih.
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong id="subtotalText">Rp 0</strong>
                </div>

                <div class="mb-3">
                    <label class="form-label">Diskon (Rp)</label>
                    <input class="form-control"
                        type="number"
                        name="discount"
                        id="discount"
                        min="0"
                        value="0">
                </div>

                <div class="d-flex justify-content-between fs-5 mb-3">
                    <span>Total</span>
                    <strong id="totalText">Rp 0</strong>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama pelanggan (opsional)</label>
                    <input class="form-control"
                        name="customer_name"
                        maxlength="100">
                </div>

                <div class="mb-3">
                    <label class="form-label">Metode pembayaran</label>
                    <select class="form-select"
                        name="payment_method"
                        id="paymentMethod"
                        required>
                        <option value="cash">Tunai</option>
                        <option value="qris">QRIS (dicatat manual)</option>
                        <option value="transfer">Transfer (dicatat manual)</option>
                    </select>
                </div>

                <div class="mb-3" id="cashWrap">
                    <label class="form-label">Uang diterima (Rp)</label>
                    <input class="form-control"
                        type="number"
                        min="0"
                        name="amount_paid"
                        id="amountPaid"
                        value="0">
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>Kembalian</span>
                    <strong id="changeText">Rp 0</strong>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea class="form-control"
                        name="notes"
                        rows="2"
                        maxlength="500"></textarea>
                </div>

                <button class="btn btn-coffee btn-lg w-100"
                    id="submitBtn">
                    Simpan Transaksi
                </button>

            </div>
        </div>

    </div>
</form>

@endsection

@push('styles')
<style>
    .product-item {
        width: 100%;
        height: 100%;
        padding: 0;
        overflow: hidden;
        text-align: left;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 13px;
        transition: .2s ease;
    }

    .product-item:hover {
        border-color: #a67c52;
        box-shadow: 0 5px 15px rgba(0,0,0,.08);
        transform: translateY(-3px);
    }

    .product-image-wrapper {
        width: 100%;
        height: 145px;
        overflow: hidden;
        background: #f5eee6;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-size: 48px;
        color: #a67c52;
    }

    .product-info {
        padding: 12px;
    }

    .product-name {
        min-height: 24px;
    }

    .cart-image {
        width: 55px;
        height: 55px;
        object-fit: cover;
        border-radius: 9px;
        background: #f5eee6;
        flex-shrink: 0;
    }

    .cart-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 55px;
        height: 55px;
        border-radius: 9px;
        background: #f5eee6;
        font-size: 23px;
        flex-shrink: 0;
    }
</style>
@endpush

@push('scripts')
<script>
const cart = new Map();

const rupiah = n =>
    'Rp ' + Number(n || 0).toLocaleString('id-ID');

const cartItems = document.getElementById('cartItems');
const discount = document.getElementById('discount');
const amountPaid = document.getElementById('amountPaid');
const paymentMethod = document.getElementById('paymentMethod');

function totals() {
    let subtotal = 0;

    cart.forEach(p => {
        subtotal += p.price * p.quantity;
    });

    const disc = Math.max(0, Number(discount.value || 0));
    const total = Math.max(0, subtotal - disc);

    const paid = paymentMethod.value === 'cash'
        ? Number(amountPaid.value || 0)
        : total;

    document.getElementById('subtotalText').textContent = rupiah(subtotal);
    document.getElementById('totalText').textContent = rupiah(total);

    document.getElementById('changeText').textContent =
        rupiah(Math.max(0, paid - total));

    document.getElementById('cashWrap').style.display =
        paymentMethod.value === 'cash' ? '' : 'none';

    document.getElementById('itemsInput').value =
        JSON.stringify(
            [...cart.values()].map(p => ({
                id: p.id,
                quantity: p.quantity
            }))
        );

    document.getElementById('submitBtn').disabled =
        cart.size === 0 || disc > subtotal || paid < total;
}

function renderCart() {

    if (!cart.size) {
        cartItems.innerHTML =
            '<div class="text-secondary small">Belum ada menu dipilih.</div>';

        totals();
        return;
    }

    cartItems.innerHTML = '';

    cart.forEach(p => {

        const row = document.createElement('div');

        row.className =
            'd-flex justify-content-between gap-2 align-items-center border-bottom py-2';

        // GAMBAR PRODUK DI KERANJANG
        if (p.image) {
            const img = document.createElement('img');
            img.src = p.image;
            img.alt = p.name;
            img.className = 'cart-image';
            row.appendChild(img);
        } else {
            const placeholder = document.createElement('div');
            placeholder.className = 'cart-placeholder';
            placeholder.textContent = '☕';
            row.appendChild(placeholder);
        }

        const info = document.createElement('div');
        info.className = 'flex-grow-1';

        const name = document.createElement('div');
        name.className = 'fw-semibold';
        name.textContent = p.name;

        const price = document.createElement('div');
        price.className = 'small text-secondary';
        price.textContent = rupiah(p.price) + ' / item';

        const itemTotal = document.createElement('div');
        itemTotal.className = 'small fw-bold text-success';
        itemTotal.textContent = rupiah(p.price * p.quantity);

        info.append(name, price, itemTotal);

        const controls = document.createElement('div');
        controls.className = 'd-flex align-items-center gap-1';

        const minus = document.createElement('button');
        minus.type = 'button';
        minus.className = 'btn btn-sm btn-outline-secondary';
        minus.textContent = '−';

        minus.onclick = () => {
            p.quantity--;

            if (p.quantity <= 0) {
                cart.delete(p.id);
            }

            renderCart();
        };

        const qty = document.createElement('span');
        qty.className = 'px-1';
        qty.textContent = p.quantity;

        const plus = document.createElement('button');
        plus.type = 'button';
        plus.className = 'btn btn-sm btn-outline-secondary';
        plus.textContent = '+';
        plus.disabled = p.quantity >= p.stock;

        plus.onclick = () => {
            if (p.quantity < p.stock) {
                p.quantity++;
                renderCart();
            }
        };

        controls.append(minus, qty, plus);

        row.append(info, controls);
        cartItems.append(row);
    });

    totals();
}

// TAMBAH PRODUK
document.querySelectorAll('.add-product').forEach(btn => {

    btn.addEventListener('click', () => {

        const id = Number(btn.dataset.id);
        const stock = Number(btn.dataset.stock);

        if (!cart.has(id)) {

            cart.set(id, {
                id,
                name: btn.dataset.name,
                price: Number(btn.dataset.price),
                stock,
                image: btn.dataset.image,
                quantity: 1
            });

        } else if (cart.get(id).quantity < stock) {

            cart.get(id).quantity++;

        }

        renderCart();
    });

});

// PENCARIAN PRODUK
document.getElementById('productSearch').addEventListener('input', e => {

    const q = e.target.value.toLowerCase();

    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display =
            card.dataset.name.includes(q) ? '' : 'none';
    });

});

// PERHITUNGAN
discount.addEventListener('input', totals);
amountPaid.addEventListener('input', totals);
paymentMethod.addEventListener('change', totals);

// CHECKOUT
document.getElementById('checkoutForm').addEventListener('submit', e => {

    if (!cart.size) {
        e.preventDefault();
        alert('Keranjang masih kosong.');
        return;
    }

    if (!confirm('Simpan transaksi ini?')) {
        e.preventDefault();
    }

});

totals();
</script>
@endpush

@extends('layouts.app')

@section('title', 'Self Order - Kasir Kafe')

@section('content')

<style> 
/* SELF ORDER KIOSK */
body {
    background: #f7f5f1;
    color: #30251f;
}

.kiosk-wrapper {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px 35px 45px;
}

/* HEADER */
.kiosk-header {
    background: linear-gradient(120deg, #30251f, #76543d);
    color: #fff;
    padding: 32px;
    border-radius: 18px;
    margin-bottom: 30px;
}

.kiosk-header h2 {
    font-size: 27px;
    font-weight: 800;
    margin-bottom: 8px;
}

.kiosk-header p {
    color: #e9ded5;
    margin: 0;
}

.kiosk-label {
    display: inline-block;
    background: #fff;
    color: #654936;
    padding: 9px 15px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 800;
}

/* SECTION */
.section-title {
    color: #30251f;
    font-size: 22px;
    font-weight: 800;
}

.search-box {
    height: 48px;
    border: 1px solid #e5ddd5;
    border-radius: 12px;
    padding: 10px 16px;
    background: #fff;
}

/* CATEGORY */
.category-btn {
    border: 1px solid #ded2c7;
    background: #fff;
    color: #654936;
    border-radius: 25px;
    padding: 9px 18px;
    margin: 0 6px 8px 0;
    font-size: 13px;
    font-weight: 600;
    transition: .2s;
}

.category-btn:hover,
.category-btn.active {
    background: #654936;
    color: #fff;
    border-color: #654936;
}

/* PRODUCT */
.menu-card {
    height: 100%;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 3px 14px rgba(0,0,0,.05);
    transition: .2s;
}

.menu-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,.09);
}

.menu-image,
.menu-image-placeholder {
    height: 165px;
    width: 100%;
    object-fit: cover;
}

.menu-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eee7df;
    font-size: 48px;
}

.menu-category {
    color: #9b8878;
    font-size: 12px;
    margin-bottom: 5px;
}

.menu-name {
    font-size: 16px;
    font-weight: 750;
    color: #30251f;
    min-height: 24px;
}

.menu-price {
    color: #805738;
    font-size: 16px;
    font-weight: 800;
}

.btn-add {
    background: #654936;
    color: white;
    border: none;
    border-radius: 9px;
    padding: 8px 13px;
    font-size: 13px;
    font-weight: 700;
}

.btn-add:hover {
    background: #432f24;
    color: white;
}

/* CART */
.cart-panel {
    background: #fff;
    padding: 22px;
    border-radius: 17px;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    position: sticky;
    top: 20px;
}

.cart-title {
    font-size: 20px;
    font-weight: 800;
    color: #30251f;
}

.cart-item {
    padding: 14px 0;
    border-bottom: 1px solid #eee8e2;
}

.cart-item-name {
    font-size: 14px;
    font-weight: 700;
    color: #30251f;
}

.quantity-btn {
    width: 30px;
    height: 30px;
    border: none;
    border-radius: 8px;
    background: #eee7df;
    color: #654936;
    font-weight: bold;
}

.quantity-btn:hover {
    background: #654936;
    color: #fff;
}

.cart-total {
    color: #654936;
    font-size: 22px;
    font-weight: 800;
}

.empty-cart {
    text-align: center;
    padding: 30px 10px;
    color: #a99a8e;
}

/* CHECKOUT */
.checkout-form {
    display: none;
    margin-top: 18px;
    padding: 18px;
    background: #faf8f5;
    border: 1px solid #eee5dc;
    border-radius: 12px;
}

.checkout-form h5 {
    color: #30251f;
    font-weight: 800;
    margin-bottom: 15px;
}

.checkout-form label {
    font-weight: 600;
    color: #514136;
}

.payment-option {
    display: block;
    background: white;
    border: 1px solid #e5ddd5;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 10px;
    cursor: pointer;
}

.payment-option:hover {
    border-color: #654936;
}

.btn-submit-order,
.btn-checkout {
    width: 100%;
    background: #654936;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 10px;
    font-weight: 700;
}

.btn-submit-order:hover,
.btn-checkout:hover:not(:disabled) {
    background: #432f24;
    color: white;
}

.btn-checkout:disabled {
    opacity: .5;
    cursor: not-allowed;
}

.checkout-error {
    display: none;
    margin-top: 12px;
}

#noProduct {
    padding: 20px;
}

/* RESPONSIVE */
@media (max-width: 991px) {
    .kiosk-wrapper {
        padding: 20px;
    }

    .cart-panel {
        position: static;
        margin-top: 25px;
    }

    .kiosk-header h2 {
        font-size: 22px;
    }
}

@media (max-width: 576px) {
    .kiosk-wrapper {
        padding: 15px;
    }

    .kiosk-header {
        padding: 22px;
    }

    .kiosk-header h2 {
        font-size: 20px;
    }
}
</style>

<div class="container-fluid kiosk-wrapper">

    {{-- HEADER --}}
    <div class="kiosk-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>WELCOME TO KASIR KAFE ☕</h2>
                <p>Pesan makanan dan minuman favoritmu di sini.</p>
            </div>

           <div class="col-md-4 text-md-right mt-3 mt-md-0">
    <span class="badge badge-light p-2">
        SELF ORDER KIOSK
    </span>
</div>
        </div>
    </div>

    <div class="row">

        {{-- DAFTAR MENU --}}
        <div class="col-lg-8">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="section-title">Jelajahi Menu</h4>
                <small class="text-muted">
                    {{ $products->count() }} menu tersedia
                </small>
            </div>

            {{-- PENCARIAN --}}
            <div class="mb-4">
                <input
                    type="text"
                    id="searchMenu"
                    class="form-control search-box"
                    placeholder="Cari kopi, makanan, atau minuman..."
                >
            </div>

            {{-- KATEGORI --}}
            <div class="mb-4" id="categoryList">
                <button
                    type="button"
                    class="category-btn active"
                    data-category="all">
                    Semua Menu
                </button>

                @foreach ($products->pluck('category.name')->filter()->unique() as $category)
                    <button
                        type="button"
                        class="category-btn"
                        data-category="{{ $category }}">
                        {{ $category }}
                    </button>
                @endforeach
            </div>

            {{-- PRODUK --}}
            <div class="row" id="productList">

                @forelse ($products as $product)

                    <div
                        class="col-sm-6 col-xl-4 mb-4 product-item"
                        data-name="{{ strtolower($product->name) }}"
                        data-category="{{ $product->category->name ?? '' }}"
                    >
                        <div class="card menu-card">

                            @if ($product->image)
                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    class="menu-image"
                                    alt="{{ $product->name }}"
                                >
                            @else
                                <div class="menu-image-placeholder">
                                    ☕
                                </div>
                            @endif

                            <div class="card-body">

                                <div class="menu-category">
                                    {{ $product->category->name ?? 'Lainnya' }}
                                </div>

                                <h5 class="menu-name">
                                    {{ $product->name }}
                                </h5>

                                <div class="d-flex justify-content-between align-items-center mt-3">

                                    <span class="menu-price">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>

                                    <button
                                        type="button"
                                        class="btn-add"
                                        onclick="addToCart({{ $product->id }})">
                                        + Tambah
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>

                @empty

                    <div class="col-12">
                        <div class="alert alert-info">
                            Belum ada menu yang tersedia.
                        </div>
                    </div>

                @endforelse

                <div class="col-12 d-none" id="noProduct">
                    <div class="alert alert-warning">
                        Menu tidak ditemukan.
                    </div>
                </div>

            </div>
        </div>

        {{-- KERANJANG --}}
        <div class="col-lg-4">

            <div class="cart-panel">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h4 class="cart-title mb-0">
                        🛒 Keranjang
                    </h4>

                    <span class="badge badge-warning" id="cartCount">
                        0 item
                    </span>

                </div>

                <div id="cartItems">
                    <div class="empty-cart">
                        <div style="font-size:45px;">🛍️</div>
                        <p class="mt-2 mb-1">Keranjang masih kosong.</p>
                        <small>Yuk, pilih menu favoritmu!</small>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span>Total item</span>
                    <strong id="totalQuantity">0</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Total pembayaran</strong>
                    <span class="cart-total" id="cartTotal">
                        Rp 0
                    </span>
                </div>

                <button
                    type="button"
                    class="btn-checkout"
                    id="checkoutButton"
                    onclick="continueCheckout()"
                    disabled>
                    Lanjut Checkout →
                </button>

                {{-- FORM CHECKOUT --}}
<div class="checkout-form" id="checkoutForm">

    <h5>Detail Pesanan</h5>

    <form id="customerCheckoutForm">

        @csrf

        <div class="form-group">
            <label for="customerName">Nama Pelanggan *</label>

            <input
                type="text"
                id="customerName"
                name="customer_name"
                class="form-control"
                placeholder="Masukkan nama kamu"
                maxlength="100"
                required
            >
        </div>

        <div class="form-group">
            <label>Metode Pembayaran *</label>

            <label class="payment-option">
                <input
                    type="radio"
                    name="payment_method"
                    value="cash"
                    checked
                >
                <strong>Bayar di Kasir</strong>
                <small class="d-block text-muted">
                    Bayar langsung kepada kasir setelah memesan.
                </small>
            </label>

            <label class="payment-option">
                <input
                    type="radio"
                    name="payment_method"
                    value="qris"
                >
                <strong>QRIS</strong>
                <small class="d-block text-muted">
                    Pembayaran menggunakan QRIS.
                </small>
            </label>
        </div>

        <div
            class="alert alert-danger checkout-error"
            id="checkoutError">
        </div>

        <button
            type="submit"
            class="btn-submit-order"
            id="submitOrderButton">
            Buat Pesanan
        </button>

    </form>
</div>
<button
    type="button"
    class="btn btn-light w-100 mt-2"
    onclick="clearCart()">
    Kosongkan Keranjang
</button>

            </div>
        </div>
    </div>
</div>

<script>
   
// Data produk dari database
const products = {!! $productData->toJson(
    JSON_HEX_TAG |
    JSON_HEX_APOS |
    JSON_HEX_AMP |
    JSON_HEX_QUOT
) !!};

let cart = {};

// Format rupiah
function formatRupiah(number) {
    return 'Rp ' + Number(number).toLocaleString('id-ID');
}

// Escape HTML
function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, function(char) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char];
    });
}

// Tambah produk
function addToCart(id) {
    const product = products.find(p => p.id === id);

    if (!product) return;

    if (cart[id]) {
        if (cart[id].quantity >= product.stock) {
            alert('Jumlah pesanan melebihi stok tersedia.');
            return;
        }

        cart[id].quantity++;
    } else {
        if (product.stock < 1) {
            alert('Stok produk habis.');
            return;
        }

        cart[id] = {
            ...product,
            quantity: 1
        };
    }

    renderCart();
}

// Tambah jumlah
function increaseQuantity(id) {
    const product = products.find(p => p.id === id);

    if (!product || !cart[id]) return;

    if (cart[id].quantity >= product.stock) {
        alert('Stok tidak mencukupi.');
        return;
    }

    cart[id].quantity++;
    renderCart();
}

// Kurangi jumlah
function decreaseQuantity(id) {
    if (!cart[id]) return;

    cart[id].quantity--;

    if (cart[id].quantity <= 0) {
        delete cart[id];
    }

    renderCart();
}

// Render keranjang
function renderCart() {
    const cartItems = document.getElementById('cartItems');
    const items = Object.values(cart);

    if (items.length === 0) {
        cartItems.innerHTML = `
            <div class="empty-cart">
                <div style="font-size:45px;">🛍️</div>
                <p class="mt-2 mb-1">Keranjang masih kosong.</p>
                <small>Yuk, pilih menu favoritmu!</small>
            </div>
        `;
    } else {
        cartItems.innerHTML = items.map(item => `
            <div class="cart-item">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="cart-item-name">
                            ${escapeHtml(item.name)}
                        </div>
                        <small class="text-muted">
                            ${formatRupiah(item.price)}
                        </small>
                    </div>

                    <strong>
                        ${formatRupiah(item.price * item.quantity)}
                    </strong>
                </div>

                <div class="d-flex align-items-center mt-2">
                    <button type="button"
                        class="quantity-btn"
                        onclick="decreaseQuantity(${item.id})">−</button>

                    <span class="mx-3 font-weight-bold">
                        ${item.quantity}
                    </span>

                    <button type="button"
                        class="quantity-btn"
                        onclick="increaseQuantity(${item.id})">+</button>
                </div>
            </div>
        `).join('');
    }

    const quantity = items.reduce(
        (sum, item) => sum + item.quantity, 0
    );

    const total = items.reduce(
        (sum, item) => sum + item.price * item.quantity, 0
    );

    document.getElementById('cartCount').textContent =
        quantity + ' item';

    document.getElementById('totalQuantity').textContent = quantity;

    document.getElementById('cartTotal').textContent =
        formatRupiah(total);

    document.getElementById('checkoutButton').disabled =
        items.length === 0;
}

// Kosongkan keranjang
function clearCart() {
    if (Object.keys(cart).length === 0) return;

    if (confirm('Yakin ingin mengosongkan keranjang?')) {
        cart = {};
        renderCart();
    }
}

// Filter kategori
document.querySelectorAll('.category-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        this.classList.add('active');
        filterProducts();
    });
});

// Pencarian
document.getElementById('searchMenu').addEventListener(
    'input',
    filterProducts
);

function filterProducts() {
    const keyword = document.getElementById('searchMenu')
        .value.toLowerCase();

    const activeCategory = document.querySelector(
        '.category-btn.active'
    ).dataset.category;

    let visible = 0;

    document.querySelectorAll('.product-item').forEach(item => {
        const matchName = item.dataset.name.includes(keyword);

        const matchCategory =
            activeCategory === 'all' ||
            item.dataset.category === activeCategory;

        const show = matchName && matchCategory;

        item.style.display = show ? '' : 'none';

        if (show) visible++;
    });

    document.getElementById('noProduct')
        .classList.toggle('d-none', visible > 0);
}

// Tampilkan form checkout
function continueCheckout() {
    if (Object.keys(cart).length === 0) return;

    const form = document.getElementById('checkoutForm');

    form.style.display = 'block';

    form.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
}

// Kirim pesanan ke Laravel
// Kirim pesanan ke Laravel
document.getElementById('customerCheckoutForm')
    .addEventListener('submit', async function(e) {

        e.preventDefault();

        const customerName = document
            .getElementById('customerName')
            .value.trim();

        const paymentMethod = document.querySelector(
            'input[name="payment_method"]:checked'
        ).value;

        const errorBox = document.getElementById('checkoutError');
        const submitButton = document.getElementById('submitOrderButton');

        errorBox.style.display = 'none';

        if (!customerName) {
            errorBox.textContent = 'Nama pelanggan wajib diisi.';
            errorBox.style.display = 'block';
            return;
        }

        if (Object.keys(cart).length === 0) {
            errorBox.textContent = 'Keranjang masih kosong.';
            errorBox.style.display = 'block';
            return;
        }

        const token = document.querySelector(
            'meta[name="csrf-token"]'
        ).getAttribute('content');

        const payload = {
            customer_name: customerName,
            payment_method: paymentMethod,
            items: Object.values(cart).map(item => ({
                id: item.id,
                quantity: item.quantity
            }))
        };

        submitButton.disabled = true;
        submitButton.textContent = 'Memproses pesanan...';

        try {

            const response = await fetch(
                "{{ route('customer.checkout') }}",
                {
                    method: 'POST',

                    credentials: 'same-origin',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },

                    body: JSON.stringify(payload)
                }
            );

            const result = await response.json();

            if (!response.ok) {
                const message = result.errors
                    ? Object.values(result.errors).flat().join('\n')
                    : result.message || 'Pesanan gagal dibuat.';

                throw new Error(message);
            }

            if (result.success && result.redirect_url) {
                window.location.href = result.redirect_url;
                return;
            }

            throw new Error('Respons server tidak sesuai.');

        } catch (error) {

            errorBox.textContent = error.message;
            errorBox.style.display = 'block';

            submitButton.disabled = false;
            submitButton.textContent = 'Buat Pesanan';

        }
    });

// Kondisi awal
renderCart();

</script>

@endsection

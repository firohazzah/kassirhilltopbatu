@extends('layouts.app')

@section('title', 'Menu')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h1 class="h3 page-title mb-1">Menu Kafe</h1>
        <div class="text-secondary">Kelola produk, harga, dan stok.</div>
    </div>

    <a href="{{ route('produk.create') }}" class="btn btn-coffee">
        ＋ Tambah Menu
    </a>
</div>

<div class="card p-3 p-md-4">

    <div class="table-responsive">
        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Menu</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($products as $product)

                <tr>
                    {{-- Gambar Produk --}}
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}"
                                alt="{{ $product->name }}"
                                class="product-thumbnail">
                        @else
                            <div class="product-placeholder">
                                <span>☕</span>
                            </div>
                        @endif
                    </td>

                    {{-- Nama Menu --}}
                    <td class="fw-semibold">
                        {{ $product->name }}
                    </td>

                    {{-- Kategori --}}
                    <td>
                        {{ $product->category->name ?? '-' }}
                    </td>

                    {{-- Harga --}}
                    <td class="text-end money">
                        Rp {{ number_format($product->price,0,',','.') }}
                    </td>

                    {{-- Stok --}}
                    <td class="text-end">
                        {{ $product->stock }}
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="badge {{ $product->is_available ? 'text-bg-success':'text-bg-secondary' }}">
                            {{ $product->is_available ? 'Aktif':'Nonaktif' }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex gap-2">

                            <a href="{{ route('produk.edit',$product) }}"
                                class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>

                            <form method="POST"
                                action="{{ route('produk.destroy',$product) }}"
                                onsubmit="return confirm('Hapus menu ini?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger">
                                    Hapus
                                </button>

                            </form>

                        </div>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center py-4">
                        Belum ada menu.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    {{ $products->links() }}

</div>

<style>
    .product-thumbnail {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #eee;
    }

    .product-placeholder {
        width: 65px;
        height: 65px;
        border-radius: 10px;
        background: #f5eee6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }
</style>

@endsection

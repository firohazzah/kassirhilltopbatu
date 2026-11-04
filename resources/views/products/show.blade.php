@extends('layouts.app')

@section('title', 'Detail Menu')

@section('content')
<div class="card p-4">
    <h3>{{ $product->name }}</h3>

    @if($product->image)
        <img
            src="{{ asset('storage/'.$product->image) }}"
            alt="{{ $product->name }}"
            style="width:200px; height:200px; object-fit:cover;"
        >
    @endif

    <p>Kategori: {{ $product->category->name }}</p>
    <p>Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
    <p>Stok: {{ $product->stock }}</p>

    <a href="{{ route('produk.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>
@endsection
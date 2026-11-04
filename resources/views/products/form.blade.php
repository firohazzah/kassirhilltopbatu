@extends('layouts.app')

@section('title', $isEdit ? 'Edit Menu' : 'Tambah Menu')

@section('content')

<div class="mb-4">
    <a href="{{ route('produk.index') }}" class="text-decoration-none">← Kembali ke menu</a>
    <h1 class="h3 page-title mt-2">
        {{ $isEdit ? 'Edit Menu' : 'Tambah Menu' }}
    </h1>
</div>

<div class="card p-4" style="max-width:720px">

    <form method="POST"
        action="{{ $isEdit ? route('produk.update',$product) : route('produk.store') }}"
        enctype="multipart/form-data">

        @csrf

        @if($isEdit)
            @method('PUT')
        @endif

        {{-- Nama Menu --}}
        <div class="mb-3">
            <label class="form-label">Nama menu</label>
            <input name="name"
                class="form-control"
                required
                maxlength="150"
                value="{{ old('name',$product->name) }}">
        </div>

        {{-- Kategori --}}
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
                <option value="">Pilih kategori</option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(old('category_id',$product->category_id)==$category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Gambar Menu --}}
        <div class="mb-3">
            <label class="form-label">Gambar Menu</label>

            @if($isEdit && $product->image)
                <div class="mb-3">
                    <p class="text-muted small mb-2">Gambar saat ini:</p>
                    <img src="{{ asset('storage/'.$product->image) }}"
                        alt="{{ $product->name }}"
                        style="width:150px;height:120px;object-fit:cover;border-radius:10px;">
                </div>
            @endif

            <input type="file"
                name="image"
                id="image"
                class="form-control"
                accept="image/png,image/jpeg,image/jpg,image/webp">

            <small class="text-muted">
                Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                @if($isEdit)
                    Kosongkan jika tidak ingin mengganti gambar.
                @endif
            </small>

            {{-- Preview gambar baru --}}
            <div class="mt-3">
                <img id="imagePreview"
                    src=""
                    alt="Preview gambar"
                    style="display:none;width:150px;height:120px;object-fit:cover;border-radius:10px;">
            </div>

            @error('image')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Harga dan Stok --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number"
                    min="0"
                    name="price"
                    class="form-control"
                    required
                    value="{{ old('price',$product->price) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Stok</label>
                <input type="number"
                    min="0"
                    name="stock"
                    class="form-control"
                    required
                    value="{{ old('stock',$product->stock ?? 0) }}">
            </div>
        </div>

        {{-- Status Menu --}}
        <div class="form-check form-switch mb-4">
            <input type="checkbox"
                class="form-check-input"
                role="switch"
                name="is_available"
                value="1"
                id="available"
                @checked(old('is_available',$product->exists ? $product->is_available : true))>

            <label class="form-check-label" for="available">
                Menu aktif / tersedia untuk dijual
            </label>
        </div>

        {{-- Tombol --}}
        <button class="btn btn-coffee" type="submit">
            Simpan Menu
        </button>

        <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">
            Batal
        </a>

    </form>
</div>

{{-- Preview gambar otomatis --}}
<script>
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});
</script>

@endsection
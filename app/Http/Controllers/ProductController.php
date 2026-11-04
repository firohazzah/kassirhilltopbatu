<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name')->paginate(12);
        return view('products.index', compact('products'));
    }

    public function show(Product $produk)
    {
    return view('products.show', [
        'product' => $produk
    ]);
}


    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.form', ['product' => new Product(), 'categories' => $categories, 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
    'name' => 'required|max:150',
    'category_id' => 'required',
    'price' => 'required|numeric|min:0',
    'stock' => 'required|integer|min:0',
    'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
]);

if ($request->hasFile('image')) {
    $data['image'] = $request->file('image')->store('products', 'public');
}

$data['is_available'] = $request->has('is_available');

Product::create($data);
return redirect()->route('produk.index')
    ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Product $produk)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.form', ['product' => $produk, 'categories' => $categories, 'isEdit' => true]);
    }

   public function update(Request $request, Product $produk)
{
    $data = $request->validate([
        'name' => 'required|max:150',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {

        // Hapus gambar lama
        if ($produk->image) {
            Storage::disk('public')->delete($produk->image);
        }

        // Simpan gambar baru
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $data['is_available'] = $request->has('is_available');

    $produk->update($data);

    return redirect()->route('produk.index')
        ->with('success', 'Menu berhasil diperbarui.');
}

    public function destroy(Product $produk)
    {
        if ($produk->transactionItems()->exists()) {
            return back()->with('error', 'Menu sudah tercatat pada transaksi dan tidak dapat dihapus. Nonaktifkan menu saja.');
        }

        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Menu berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Season;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 並び替え
        if ($request->filled('sort')) {
            $direction = $request->input('sort') === 'asc' ? 'asc' : 'desc';
            $query->orderBy('price', $direction);
        } else {
            $query->latest();
        }

        $products = $query->paginate(6)->appends($request->all());
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $seasons = Season::all();
        return view('products.create', compact('seasons'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // 画像アップロード
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path;
        }

        // 商品登録
        $product = Product::create($data);

        if (isset($data['seasons'])) {
            $product->seasons()->attach($data['seasons']);
        }

        return redirect()->route('products.index')->with('success', '商品を登録しました');
    }

    public function show(Product $product)
    {
        $product->load('seasons');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $seasons = Season::all();
        $selectedSeasons = $product->seasons->pluck('id')->toArray();
        return view('products.edit', compact('product', 'seasons', 'selectedSeasons'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'seasons' => 'nullable|array',
            'seasons.*' => 'exists:seasons,id',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);
        $product->seasons()->sync($validated['seasons'] ?? []);

        return redirect()->route('products.index')->with('success', '商品を更新しました');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->seasons()->detach();
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品を削除しました');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $sort = $request->input('sort');

        $query = Product::query();

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
        }

        if ($sort === 'asc' || $sort === 'desc') {
            $query->orderBy('price', $sort);
        }

        $products = $query->paginate(6)->appends($request->all());
        return view('products.index', compact('products', 'keyword'));
    }
}

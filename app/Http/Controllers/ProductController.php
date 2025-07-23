<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Season;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path;
        }

        $product = Product::create($data);

        if (isset($data['seasons'])) {
            $product->seasons()->attach($data['seasons']);
        }

        return redirect()->route('products.index')->with('success', '商品を登録しました');
    }

    public function show(Product $product)
    {
        $product->load('seasons');
        $seasons = Season::all();

        return view('products.show', compact('product', 'seasons'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $noChanges =
            $product->name === $validated['name'] &&
            $product->price === $validated['price'] &&
            $product->description === ($validated['description'] ?? '') &&
            $product->seasons->pluck('id')->sort()->values()->all() === collect($validated['seasons'] ?? [])->sort()->values()->all() &&
            !$request->hasFile('image');

        if ($noChanges) {
            return redirect()->route('products.index')->with('message', '変更はありませんでした');
        }

        // 画像がアップロードされていれば一時保存
        if ($request->hasFile('image')) {
            $tempPath = $request->file('image')->store('temp', 'public');
            session()->put('temp_image', $tempPath);
        }

        // 一時保存された画像があれば移動して保存
        if (session()->has('temp_image')) {
            $filename = basename(session('temp_image'));
            $newPath = 'images/' . $filename;

            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            Storage::disk('public')->move(session('temp_image'), $newPath);
            $product->image = $newPath;

            session()->forget('temp_image');
        }

        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->description = $validated['description'] ?? '';
        $product->save();

        $product->seasons()->sync($validated['seasons'] ?? []);

        return redirect()->route('products.index')->with('message', '商品を更新しました');
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

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->seasons()->detach();
        $product->delete();

        return redirect()->route('products.index')->with('message', '商品を削除しました');
    }
}

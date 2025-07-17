<?php

// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Season;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
    $products = Product::all();
    return view('products.index', compact('products'));
    }
// ... 他のメソッド ...

    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'seasons' => 'nullable|array',
            'seasons.*' => 'exists:seasons,id',
        ]);

        // 画像の保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }

        // 商品の作成
        $product = Product::create($validated);

        // 中間テーブルに季節を登録
        if (isset($validated['seasons'])) {
            $product->seasons()->attach($validated['seasons']);
        }

        return redirect()->route('products.index')->with('success', '商品を登録しました');
    }

    public function create()
    {
    $seasons = Season::all(); // 季節一覧を取得
    return view('products.create', compact('seasons'));
    }// ... 他のメソッド ...

// ProductController.php
    public function show(Product $product)
    {
        $product->load('seasons');
        return view('products.show', compact('product'));
    }


}

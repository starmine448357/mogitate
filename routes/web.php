<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// 商品一覧
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 商品登録フォーム表示
Route::get('/products/register', [ProductController::class, 'create'])->name('products.create');

// 商品登録処理
Route::post('/products/register', [ProductController::class, 'store'])->name('products.store');

// 商品検索
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// 商品詳細（編集画面）
Route::get('/products/{product}/update', [ProductController::class, 'edit'])->name('products.edit');

// 商品更新処理
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

// 商品詳細閲覧）
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');

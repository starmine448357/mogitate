<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/register', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/register', [ProductController::class, 'store'])->name('products.store');

Route::get('/products/search', [ProductController::class, 'search'])->name('products.search'); 

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/update', [ProductController::class, 'edit'])->name('products.edit');
Route::post('/products/{product}/update', [ProductController::class, 'update'])->name('products.update');
Route::post('/products/{product}/delete', [ProductController::class, 'destroy'])->name('products.delete');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');

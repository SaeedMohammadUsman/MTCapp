<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderItemController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect()->route('home');  // Redirect to /home
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('departments', DepartmentController::class);
Route::resource('vendors', VendorController::class);
Route::resource('inventory_items', InventoryItemController::class);
Route::resource('stock_adjustments', StockAdjustmentController::class);

// Resource routes for managing purchase orders
Route::resource('purchase_orders', PurchaseOrderController::class);

// Shallow resource routes for purchase order items (create, store only)
// Route::resource('purchase_orders.items', PurchaseOrderItemController::class)->shallow();

// Nested routes for purchase order items (show, edit, update, delete)
Route::get('purchase_orders/{purchase_order}/items/{item}', [PurchaseOrderItemController::class, 'show'])
    ->name('purchase_orders.items.show');

Route::get('purchase_orders/{purchase_order}/items/{item}/edit', [PurchaseOrderItemController::class, 'edit'])
    ->name('purchase_orders.items.edit');

Route::put('purchase_orders/{purchase_order}/items/{item}', [PurchaseOrderItemController::class, 'update'])
    ->name('purchase_orders.items.update');

Route::delete('purchase_orders/{purchase_order}/items/{item}', [PurchaseOrderItemController::class, 'destroy'])
    ->name('purchase_orders.items.destroy');


Route::resource('categories', CategoryController::class);
Route::post('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');

// Resource routes for managing items
Route::resource('items', ItemController::class);

// Route to restore soft-deleted items
Route::post('items/{item}/restore', [ItemController::class, 'restore'])->name('items.restore');

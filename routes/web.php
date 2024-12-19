<?php

use App\Http\Controllers\BatchItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InventoryBatchController;
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


Route::prefix('purchase_orders')->name('purchase_orders.')->group(function () {
    Route::resource('/', PurchaseOrderController::class)->parameters(['' => 'purchase_order']);
    Route::get('{purchase_order}/pdf', [PurchaseOrderController::class, 'generatePdf'])->name('pdf');
    Route::prefix('{purchase_order}/items')->name('items.')->group(function () {
        //Route::get('{item}', [PurchaseOrderItemController::class, 'show'])->name('show');
        Route::get('{item}/edit', [PurchaseOrderItemController::class, 'edit'])->name('edit');
        Route::put('{item}', [PurchaseOrderItemController::class, 'update'])->name('update');
        Route::delete('{item}', [PurchaseOrderItemController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('{purchase_order}/items')->name('items.')->group(function () {
        Route::get('create', [PurchaseOrderItemController::class, 'create'])->name('create');
        Route::post('/', [PurchaseOrderItemController::class, 'store'])->name('store');
    });
});




// // Resource routes for managing purchase orders
// Route::resource('purchase_orders', PurchaseOrderController::class);
// // Shallow resource routes for purchase order items (create, store only)
// Route::resource('purchase_orders.items', PurchaseOrderItemController::class)->shallow();

// // Nested routes for purchase order items (show, edit, update, delete)
// Route::get('purchase_orders/{purchase_order}/items/{item}', [PurchaseOrderItemController::class, 'show'])
//     ->name('purchase_orders.items.show');

// Route::get('purchase_orders/{purchase_order}/items/{item}/edit', [PurchaseOrderItemController::class, 'edit'])
//     ->name('purchase_orders.items.edit');

// Route::put('purchase_orders/{purchase_order}/items/{item}', [PurchaseOrderItemController::class, 'update'])
//     ->name('purchase_orders.items.update');

// Route::delete('purchase_orders/{purchase_order}/items/{item}', [PurchaseOrderItemController::class, 'destroy'])
//     ->name('purchase_orders.items.destroy');

// Route::get('purchase_orders/{purchase_order}/pdf', [PurchaseOrderController::class, 'generatePdf'])
//     ->name('purchase_orders.pdf');





Route::resource('categories', CategoryController::class);
Route::post('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');

// Resource routes for managing items
Route::resource('items', ItemController::class);


// Route to restore soft-deleted items
Route::post('items/{item}/restore', [ItemController::class, 'restore'])->name('items.restore');



Route::prefix('batches')->name('batches.')->group(function () {
    Route::resource('/', InventoryBatchController::class)->parameters(['' => 'batch']);
    Route::get('/{id}', [InventoryBatchController::class, 'show'])->name('show');

    // Batch Items Routes
    Route::post('/{batch}/items', [BatchItemController::class, 'store'])->name('items.store');
    Route::get('/{batch}/items/create', [BatchItemController::class, 'create'])->name('items.create');


    Route::get('/{batch}/items/{batch_item}/edit', [BatchItemController::class, 'edit'])->name('items.edit');

    Route::put('/{batch}/items/{batch_item}', [BatchItemController::class, 'update'])->name('items.update');
    Route::delete('/{batch}/items/{batch_item}', [BatchItemController::class, 'destroy'])->name('items.destroy');
});

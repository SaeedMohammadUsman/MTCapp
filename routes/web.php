<?php

use App\Http\Controllers\BatchItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryBatchController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderItemController;
use App\Http\Controllers\ReceivedGoodController;
use App\Http\Controllers\ReceivedGoodDetailController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockTransactionController;
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
    Route::post('{purchase_order}/restore', [PurchaseOrderController::class, 'restore'])->name('restore');
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




Route::prefix('received-goods')->name('received_goods.')->group(function () {
    Route::resource('/', ReceivedGoodController::class)->except(['show'])->parameters(['' => 'received_good']);
    Route::get('/{received_good}', [ReceivedGoodController::class, 'show'])->name('show');
    Route::post('/{received_good}/restore', [ReceivedGoodController::class, 'restore'])->name('restore');



    Route::resource('/{received_good}/details', ReceivedGoodDetailController::class)->except(['index', 'show']);

    // Route::get('/{received_good}/details/create', [ReceivedGoodDetailController::class, 'create'])->name('details.create');
    // Route::post('/{received_good}/details', [ReceivedGoodDetailController::class, 'store'])->name('details.store');
    // Route::get('/{received_good}/details/{received_good_detail}/edit', [ReceivedGoodDetailController::class, 'edit'])->name('details.edit');
    // Route::delete('/{received_good}/details/{received_good_detail}', [ReceivedGoodDetailController::class, 'destroy'])->name('details.destroy');
});

Route::prefix('stock-transactions')->name('stock_transactions.')->group(function () {
    Route::resource('/', StockTransactionController::class);
    
});

<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderItemController;
use App\Http\Controllers\ReceivedGoodController;
use App\Http\Controllers\ReceivedGoodDetailController;
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






Route::prefix('received-goods')->name('received_goods.')->group(function () {
    Route::resource('/', ReceivedGoodController::class)->except(['show'])->parameters(['' => 'received_good']);
    Route::get('/{received_good}', [ReceivedGoodController::class, 'show'])->name('show');
    Route::post('/{received_good}/restore', [ReceivedGoodController::class, 'restore'])->name('restore');



    Route::resource('/{received_good}/details', ReceivedGoodDetailController::class)->except(['index', 'show']);
   
    Route::get('/{received_good}/details', [ReceivedGoodController::class, 'getDetails'])->name('details');
    Route::post('/{received_good}/stock-in', [ReceivedGoodController::class, 'stockIn'])->name('stock_in');
});

Route::prefix('stock-transactions')->name('stock_transactions.')->group(function () {
    Route::resource('/', StockTransactionController::class);
    Route::get('/{id}', [StockTransactionController::class, 'show'])->name('show');
});


// Customer Routes
Route::prefix('customers')->name('customers.')->group(function () {
    Route::resource('/', CustomerController::class)->except(['show'])->parameters(['' => 'customer']);
   
    Route::post('/{customer}/restore', [CustomerController::class, 'restore'])->name('restore');
});

Route::prefix('packages')->name('packages.')->group(function () {
    Route::resource('/', PackageController::class)->parameters(['' => 'package']);
    Route::post('/{package}/restore', [PackageController::class, 'restore'])->name('restore');
});

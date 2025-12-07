<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardWebController;
use App\Http\Controllers\Admin\ProductWebController;
use App\Http\Controllers\Admin\OrderWebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function() {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [DashboardWebController::class, 'index'])->name('dashboard');
    
    // Products CRUD
    Route::resource('products', ProductWebController::class);
    
    // Orders Management
    Route::get('/orders', [OrderWebController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderWebController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OrderWebController::class, 'updateStatus'])->name('orders.updateStatus');
});

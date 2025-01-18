<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('admin.adminDashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Customers
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customer_form', [CustomerController::class, 'store'])->name('data.customer_form');
    Route::post('/editCustomer', [CustomerController::class, 'edit']);
    Route::post('/updateCustomer', [CustomerController::class, 'update']);
    Route::post('/deleteCustomer', [CustomerController::class, 'destroy']);
});

require __DIR__.'/auth.php';

route::get('supervisor/dashboard', [HomeController::class, 'index']) ->
middleware(['auth', 'supervisor']);
;



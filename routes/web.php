<?php

use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\SubscriptionController as SubscribeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('planes');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Subscriptions
Route::get('/dashboard/subscription', [SubscriptionController::class, 'index'])->middleware('auth')->name('susbscription');
Route::get('/dashboard/projobi', [SubscriptionController::class, 'projobi'])->middleware('auth')->name('projobi');

Route::prefix('subscribe')
        ->name('subscribe.')
        ->group(function () {
            Route::get('/', [SubscribeController::class, 'show'])->name('show');
            Route::post('/', [SubscribeController::class, 'store'])->name('store');
            Route::get('/approval', [SubscribeController::class, 'approval'])->name('approval');
            Route::get('/cancelled', [SubscribeController::class, 'cancelled'])->name('cancelled');
    
});
// Payment
Route::post('/payment/pay', [PaymentController::class, 'pay'])->name('pay');
Route::get('/payment/approval', [PaymentController::class, 'approval'])->name('approval');
Route::get('/payment/cancelled', [PaymentController::class, 'cancelled'])->name('cancelled');

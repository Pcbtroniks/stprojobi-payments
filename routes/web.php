<?php

use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\HandShakeController;
use App\Http\Controllers\SubscriptionController as SubscribeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
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
Route::get('/dashboard/{user}/{activate}', [SubscriptionController::class, 'activate'])->middleware('auth')->name('activate');
Route::get('/projobi/{userID}/', [SubscriptionController::class, 'setProjobiUser'])->middleware('auth')->name('projobi.set.user');
Route::get('/projobi/{userID}/get', [SubscriptionController::class, 'projobiUser'])->middleware('auth')->name('projobi.get.user');
Route::get('/projobi/session/delete', [SubscriptionController::class, 'deleteProjobiSession'])->name('projobi.session.delete');

Route::prefix('subscribe')
        ->name('subscribe.')
        ->group(function () {
            Route::get('/', [SubscribeController::class, 'show'])->name('show');
            Route::post('/', [SubscribeController::class, 'store'])->name('store');
            Route::get('/plan-x', [SubscribeController::class, 'planX'])->name('plan.x');
            Route::get('/approval', [SubscribeController::class, 'approval'])->name('approval');
            Route::get('/cancelled', [SubscribeController::class, 'cancelled'])->name('cancelled');
    
});

Route::get('/handshake',  [HandShakeController::class, 'handShake'])->name('handshake')->middleware('projobi.user');
Route::match(array('GET', 'POST'),'/webhook',  [WebhookController::class, 'webhook'])->name('webhook');
Route::match(array('GET', 'POST'),'/expired-subscriptors',  [WebhookController::class, 'getExpiredSubscriptors'])->name('expired.subscriptors');

// Payment
Route::post('/payment/pay', [PaymentController::class, 'pay'])->name('pay');
Route::get('/payment/approval', [PaymentController::class, 'approval'])->name('approval');
Route::get('/payment/cancelled', [PaymentController::class, 'cancelled'])->name('cancelled');

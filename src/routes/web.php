<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/register', [RegisteredUserController::class, 'add']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/verify/notice', [EmailController::class, 'notice']);
Route::post('/email/resend', [EmailController::class, 'resend']);
Route::get('/', [ProductController::class, 'index']);
Route::get('/search', [ProductController::class, 'search']);
Route::get('/item/{item_id}', [ProductController::class, 'detail']);
Route::get('/purchase/success', [ProductController::class, 'purchaseSuccess'])
    ->name('purchase.success');
Route::get('/purchase/cancel', function () {
    return '決済をキャンセルしました。';
});
Route::get('/purchase/pending', [ProductController::class, 'purchasePending']);
Route::post('/stripe/webhook', [ProductController::class, 'handleWebhook']);

Route::middleware('auth')->group(
    function () {
        Route::get('/email/verify/{id}/{hash}', [EmailController::class, 'verify'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
        Route::get('/setting', [ProfileController::class, 'showSetting']);
        Route::post('/setting', [ProfileController::class, 'saveSetting']);
        Route::post('/item/comment/{item_id}', [ProductController::class, 'postComment']);
        Route::post('/item/like/{item_id}', [ProductController::class, 'toggle']);
        Route::get('/purchase/{item_id}', [ProductController::class, 'getPurchase'])->name('purchase');
        Route::get('/purchase/address/{item_id}', [ProductController::class, 'editAddress']);
        Route::post('/purchase/address/{item_id}', [ProductController::class, 'updateAddress']);
        Route::post('/purchase/{item_id}', [ProductController::class, 'postPurchase']);
        Route::get('/mypage', [ProfileController::class, 'show']);
        Route::get('/mypage/profile', [ProfileController::class, 'edit']);
        Route::post('/mypage/profile', [ProfileController::class, 'update']);
        Route::get('/sell', [ProductController::class, 'getSell']);
        Route::post('/sell', [ProductController::class, 'postSell']);
    }
);

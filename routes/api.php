<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Profile\EditProfileController;
use App\Http\Controllers\API\Product\SizeController;
use App\Http\Controllers\API\Product\CategoryController;
use App\Http\Controllers\API\Product\ProductController;
use App\Http\Controllers\API\Product\MaterialController;
use App\Http\Controllers\CheckoutController;

/*
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::put('user/update', [EditProfileController::class, 'updateInfo'])->name('user.updateInfo');

    Route::put('user/update-password', [EditProfileController::class, 'updatePassword'])->name('user.updatePassword');

    Route::delete('user/delete', [EditProfileController::class, 'destroy'])->name('user.destroy');

    Route::get('sizes', [SizeController::class, 'index']);
    Route::post('sizes', [SizeController::class, 'store']);
    Route::get('sizes/{id}', [SizeController::class, 'show']);
    Route::put('sizes/{id}', [SizeController::class, 'update']);
    Route::delete('sizes/{id}', [SizeController::class, 'destroy']);

    Route::get('categorys', [CategoryController::class, 'index']);
    Route::post('categorys', [CategoryController::class, 'store']);
    Route::get('categorys/{id}', [CategoryController::class, 'show']);
    Route::put('categorys/{id}', [CategoryController::class, 'update']);
    Route::delete('categorys/{id}', [CategoryController::class, 'destroy']);

    Route::get('materials', [MaterialController::class, 'index']);
    Route::post('materials', [MaterialController::class, 'store']);
    Route::get('materials/{id}', [MaterialController::class, 'show']);
    Route::put('materials/{id}', [MaterialController::class, 'update']);
    Route::delete('materials/{id}', [MaterialController::class, 'destroy']);

    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('products/{id}/update', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    Route::get('/checkout/products', [CheckoutController::class, 'getAvailableProducts']);
    Route::post('/checkout/create', [CheckoutController::class, 'createCheckout']);
    Route::put('/checkout/{checkoutId}/update-quantity', [CheckoutController::class, 'updateCheckoutQuantity']);
    Route::get('/checkout/{id}/details', [CheckoutController::class, 'getCheckoutDetails']);
    Route::get('/checkout/history', [CheckoutController::class, 'getCheckoutHistory']);
    Route::get('/checkout/history/{checkoutId}', [CheckoutController::class, 'getCheckoutDetails']);
    Route::delete('/checkout/history/{code}', [CheckoutController::class, 'destroy']);
});

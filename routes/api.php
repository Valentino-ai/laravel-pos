<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Profile\EditProfileController;
use App\Http\Controllers\API\Product\SizeController;
use App\Http\Controllers\API\Product\CategoryController;
use App\Http\Controllers\API\Product\ProductController;
use App\Http\Controllers\API\Product\MaterialController; // Import MaterialController

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

// Public routes for authentication
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

// Protected routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    // Route to get authenticated user data
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    // Route to update user profile information
    Route::put('user/update', [EditProfileController::class, 'updateInfo'])->name('user.updateInfo');

    // Route to update user password
    Route::put('user/update-password', [EditProfileController::class, 'updatePassword'])->name('user.updatePassword');

    // Route to delete user account
    Route::delete('user/delete', [EditProfileController::class, 'destroy'])->name('user.destroy');

    // Routes for size management
    Route::get('sizes', [SizeController::class, 'index']);
    Route::post('sizes', [SizeController::class, 'store']);
    Route::get('sizes/{id}', [SizeController::class, 'show']);
    Route::put('sizes/{id}', [SizeController::class, 'update']);
    Route::delete('sizes/{id}', [SizeController::class, 'destroy']);

    // Routes for category management
    Route::get('categorys', [CategoryController::class, 'index']);
    Route::post('categorys', [CategoryController::class, 'store']);
    Route::get('categorys/{id}', [CategoryController::class, 'show']);
    Route::put('categorys/{id}', [CategoryController::class, 'update']);
    Route::delete('categorys/{id}', [CategoryController::class, 'destroy']);

    // Routes for product management
    Route::get('products', [ProductController::class, 'index']);       // Get all products
    Route::post('products', [ProductController::class, 'store']);      // Create a new product
    Route::get('products/{id}', [ProductController::class, 'show']);   // Get a specific product
    Route::put('products/{id}', [ProductController::class, 'update']); // Update a specific product
    Route::delete('products/{id}', [ProductController::class, 'destroy']); // Delete a specific product

    // Routes for material management
    Route::get('materials', [MaterialController::class, 'index']);      // Get all materials
    Route::post('materials', [MaterialController::class, 'store']);     // Create a new material
    Route::get('materials/{id}', [MaterialController::class, 'show']);  // Get a specific material
    Route::put('materials/{id}', [MaterialController::class, 'update']); // Update a specific material
    Route::delete('materials/{id}', [MaterialController::class, 'destroy']); // Delete a specific material
});

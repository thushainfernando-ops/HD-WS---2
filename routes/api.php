<?php

use App\Http\Controllers\Api\AdminPortalController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerPortalController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC ROUTES - Accessible without authentication END POINT FOR API
// ==========================================

// Menu routes - publicly accessible
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/menu/{id}', [MenuController::class, 'show']);

// Payment routes - get Stripe public key
Route::get('/payment/stripe-key', [PaymentController::class, 'getStripeKey']);

// Location routes - public access
Route::post('/location/calculate-delivery', [LocationController::class, 'calculateDelivery']);
Route::post('/location/geocode', [LocationController::class, 'geocodeAddress']);
Route::post('/location/reverse-geocode', [LocationController::class, 'reverseGeocode']);

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);

// ==========================================
// PROTECTED ROUTES - Require Sanctum authentication
// ==========================================

Route::middleware('auth:sanctum')->group(function () {
    // User authentication check endpoint
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Authentication logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Customer portal routes
    Route::get('/customer/cart', [CustomerPortalController::class, 'cart']);
    Route::get('/customer/orders', [CustomerPortalController::class, 'orders']);

    // ==============================
    // PAYMENT API ENDPOINTS
    // ==============================
    Route::post('/payment/process', [PaymentController::class, 'processPayment']);
    Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent']);
    Route::get('/payment/{payment}', [PaymentController::class, 'getPaymentStatus']);

    // ==============================
    // IMAGE API ENDPOINTS
    // ==============================
    Route::post('/image/upload', [ImageController::class, 'uploadImage']);
    Route::post('/image/get-url', [ImageController::class, 'getOptimizedUrl']);
    Route::post('/image/metadata', [ImageController::class, 'getImageMetadata']);
    Route::delete('/image/{public_id}', [ImageController::class, 'deleteImage']);

    // ==============================
    // ADMIN ROUTES - Require authentication + admin role
    // ==============================
    Route::middleware('admin')->group(function () {
        Route::get('/admin/summary', [AdminPortalController::class, 'summary']);
    });
});

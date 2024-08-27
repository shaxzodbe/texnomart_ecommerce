<?php

use App\Http\Controllers\Api\Admin\V1\OrderAdminController;
use App\Http\Controllers\Api\Admin\V1\UserAdminController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('api')->group(function () {
    Route::post('/register-or-login', [AuthController::class, 'registerOrLogin']
    );
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::post(
        'calculate-order-price',
        [OrderController::class, 'calculateOrderPrice']
    );
    Route::post('create-order', [OrderController::class, 'createOrder']);
    Route::post('cancel-order/{order}', [OrderController::class, 'cancelOrder']
    );

    Route::group(['prefix' => 'admin/', 'middleware' => 'isAdmin'],
        function () {
            Route::apiResource('users', UserAdminController::class);
            Route::apiResource('orders', OrderController::class);

            Route::post(
                'orders/{order}/status',
                [OrderAdminController::class, 'updateOrderStatus']
            );
            Route::delete(
                'orders/{order}',
                [OrderAdminController::class, 'deleteOrder']
            );
        });
});

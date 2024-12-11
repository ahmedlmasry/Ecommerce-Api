<?php

use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\User\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::get('products', [MainController::class, 'products']);
});

Route::group(['prefix' => 'v1/user','namespace'=>'Api'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('new-order', [MainController::class, 'newOrder']);
        Route::get('my-orders', [MainController::class, 'myOrders']);


    });
});


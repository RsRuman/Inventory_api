<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use App\Http\Controllers\Api\V1\Inventory\InventoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::group(['prefix' => 'v1/', 'middleware' => 'api'], function () {
    Route::post('sign-up', [AuthenticationController::class, 'signUp']);
    Route::post('login', [AuthenticationController::class, 'login']);
});


Route::group(['prefix' => 'v1/', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'inventories'], function () {
        Route::get('/', [InventoryController::class, 'index']);
        Route::get('/{id}', [InventoryController::class, 'show']);
        Route::post('/', [InventoryController::class, 'create']);
        Route::put('/{id}', [InventoryController::class, 'update']);
        Route::delete('/{id}', [InventoryController::class, 'destroy']);
    });
});

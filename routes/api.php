<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use App\Http\Controllers\Api\V1\Inventory\InventoryController;
use App\Http\Controllers\Api\V1\Item\ItemController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::group(['prefix' => 'v1/', 'middleware' => 'api'], function () {
    Route::post('sign-up', [AuthenticationController::class, 'signUp']);
    Route::post('login', [AuthenticationController::class, 'login']);
});

Route::group(['prefix' => 'v1/', 'middleware' => ['auth:api']], function () {
    // Logout
    Route::post('logout', [AuthenticationController::class, 'logout']);

    // Inventory
    Route::group(['prefix' => 'inventories'], function () {
        Route::get('/', [InventoryController::class, 'index']);
        Route::get('/{id}', [InventoryController::class, 'show']);
        Route::post('/', [InventoryController::class, 'create']);
        Route::put('/{id}', [InventoryController::class, 'update']);
        Route::delete('/{id}', [InventoryController::class, 'destroy']);

        // Item
        Route::group(['prefix' => '/'], function () {
            Route::get('/{invId}/items', [ItemController::class, 'index']);
            Route::get('/{invId}/items/{id}', [ItemController::class, 'show']);
            Route::post('/{invId}/items', [ItemController::class, 'create']);
            Route::put('/{invId}/items/{id}', [ItemController::class, 'update']);
            Route::delete('/{invId}/items/{id}', [ItemController::class, 'destroy']);
        });
    });
});

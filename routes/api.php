<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/create-order', function (Request $request) {
        return 'create-order';
    })->middleware('auth:sanctum', 'create-order');

    Route::post('/finish-order', function (Request $request) {
        return 'finihh-order';
    })->middleware('auth:sanctum', 'finish-order');

    Route::post('/user', [UserController::class, 'store'])->middleware('create-user');

    Route::get('/item', [ItemController::class, 'index']);
    Route::post('/item', [ItemController::class, 'store'])->middleware('create-update-item');
    Route::patch('/item/{id}', [ItemController::class, 'update'])->middleware('create-update-item');
});
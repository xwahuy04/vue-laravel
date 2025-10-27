<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/create-order', function (Request $request) {
    return 'create-order';
})->middleware('auth:sanctum', 'create-order');

Route::get('/finish-order', function (Request $request) {
    return 'finihh-order';
})->middleware('auth:sanctum', 'finish-order');
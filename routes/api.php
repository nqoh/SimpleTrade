<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;



Route::middleware('guest')->group(function(){
      Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth:sanctum')->group(function(){

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/profile', [OrderController::class, 'profile']);
    Route::post('/orders', [OrderController::class,'store']);
    Route::get('/trades', [OrderController::class,'trades']);
    Route::post('/orders/{id}/cancel', [OrderController::class,'cancel']);
    Route::get('/logout', [AuthController::class, 'logout']);
    
});
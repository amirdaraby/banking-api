<?php

use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/users')->group(function () {
    Route::get("/", [UserController::class, 'index']);
    Route::post("/", [UserController::class, 'store']);
    Route::get("/{id}", [UserController::class, 'show']);
    Route::put("/{id}", [UserController::class, 'update']);
    Route::delete("/{id}", [UserController::class, 'destroy']);
});

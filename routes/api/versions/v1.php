<?php

use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::name('users.')->prefix('/users')->group(function () {
    Route::get("/", [UserController::class, 'index'])->name('index');
    Route::post("/", [UserController::class, 'store'])->name('store');
    Route::get("/{user_id}", [UserController::class, 'show'])->name('show');
    Route::put("/{user_id}", [UserController::class, 'update'])->name('update');
    Route::delete("/{user_id}", [UserController::class, 'destroy'])->name('destroy');
});

Route::name('accounts.')->prefix('/accounts')->group(function () {
    Route::get("/", [AccountController::class, 'index'])->name('index');
    Route::post("/", [AccountController::class, 'store'])->name('store');
    Route::get("/{account_id}", [AccountController::class, 'show'])->name('show');
    Route::put("/{account_id}", [AccountController::class, 'update'])->name('update');
    Route::delete("/{account_id}", [AccountController::class, 'destroy'])->name('destroy');
});

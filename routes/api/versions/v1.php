<?php

use App\Http\Controllers\Api\v1\AccountController;
use App\Http\Controllers\Api\v1\CardController;
use App\Http\Controllers\Api\v1\TransactionController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;


Route::name('transactions.')->prefix('/transactions')->group(function () {
    Route::post('/card-to-card', [TransactionController::class, 'cardToCard'])->name('mamad');
});

Route::name('users.')->prefix('/users')->group(function () {
    Route::get("/", [UserController::class, 'index'])->name('index');
    Route::post("/", [UserController::class, 'store'])->name('store');
    Route::get("/{id}", [UserController::class, 'show'])->name('show');
    Route::put("/{id}", [UserController::class, 'update'])->name('update');
    Route::delete("/{id}", [UserController::class, 'destroy'])->name('destroy');
});

Route::name('accounts.')->prefix('/accounts')->group(function () {
    Route::get("/", [AccountController::class, 'index'])->name('index');
    Route::post("/", [AccountController::class, 'store'])->name('store');
    Route::get("/{id}", [AccountController::class, 'show'])->name('show');
    Route::put("/{id}", [AccountController::class, 'update'])->name('update');
    Route::delete("/{id}", [AccountController::class, 'destroy'])->name('destroy');
});

Route::name('cards.')->prefix('/cards')->group(function () {
    Route::get("/", [CardController::class, 'index'])->name('index');
    Route::post("/", [CardController::class, 'store'])->name('store');
    Route::get("/{id}", [CardController::class, 'show'])->name('show');
    Route::put("/{id}", [CardController::class, 'update'])->name('update');
    Route::delete("/{id}", [CardController::class, 'destroy'])->name('destroy');
});

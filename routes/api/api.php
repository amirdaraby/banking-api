<?php

use Illuminate\Support\Facades\Route;

Route::name('v1.')->prefix('/v1')->group(base_path('routes/api/versions/v1.php'));

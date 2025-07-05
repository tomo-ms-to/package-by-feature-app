<?php

use Illuminate\Support\Facades\Route;
use Package\Message\Http\Controllers\MessageController;

Route::prefix('message')->group(function () {
    Route::get('/', MessageController::class);
});
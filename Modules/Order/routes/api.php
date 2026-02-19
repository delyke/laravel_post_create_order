<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderStoreController;

Route::prefix('api/v1')->group(function () {
    Route::post('/orders', OrderStoreController::class);
});

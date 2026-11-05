
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransNotificationController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Webhook Midtrans
Route::post('/midtrans/notification', [
    MidtransNotificationController::class,
    'handle'
])->name('midtrans.notification');

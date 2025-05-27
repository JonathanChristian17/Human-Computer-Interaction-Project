<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\API\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/rooms/{room}/check-availability', [RoomController::class, 'checkAvailability']);
Route::get('/rooms/{room}/unavailable-dates', [RoomController::class, 'getUnavailableDates']);

// Midtrans webhook route - no middleware
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->withoutMiddleware(['api', 'web']);

// Test routes for debugging
Route::get('/midtrans/test', function() {
    return response()->json(['status' => 'success', 'message' => 'Webhook endpoint is accessible']);
});

Route::post('/midtrans/test-webhook', function(Request $request) {
    \Log::info('Test webhook received', [
        'headers' => $request->headers->all(),
        'body' => $request->all()
    ]);
    return response()->json(['status' => 'success', 'message' => 'Test webhook received successfully']);
});

Route::get('transactions/{orderId}/check-status', [TransactionController::class, 'checkStatus']); 
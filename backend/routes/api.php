<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaymentMethodController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'Backend Laravel terhubung',
    ]);
});

Route::get('/payment-methods', [PaymentMethodController::class, 'index']);

Route::get('/invoices', [InvoiceController::class, 'index']);
Route::post('/invoices', [InvoiceController::class, 'store']);
Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
    ->whereNumber('invoice');

Route::get('/public/invoices/{token}', [
    InvoiceController::class,
    'showPublic',
]);
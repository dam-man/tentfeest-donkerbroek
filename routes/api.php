<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('mollie/webhook', [PaymentController::class, 'webhook'])->name('mollie.webhook');

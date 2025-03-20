<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('scan/tickets/{id}', [ScanController::class, 'ticket'])->name('scan.tickets');
Route::get('scan/munten/{id}', [ScanController::class, 'munten'])->name('scan.vouchers');
Route::get('betaling/{token}/voltooid', [PaymentController::class, 'completed'])->name('payment.completed');

Volt::route('/', 'frontend.home')->name('home');
Volt::route('/nieuws', 'frontend.news')->name('news');
Volt::route('/bestellen', 'frontend.order')->name('order');
Volt::route('/login-of-registreren', 'frontend.payment')->name('payment');
Volt::route('/betaling/{token}', 'frontend.payment-return')->name('payment.return');
Volt::route('/betalen', 'frontend.payment')->name('payment');

Route::get('/login', function () {
	return view('welcome');
})->name('login');


Volt::route('admin', 'administrator.auth.login')->name('administrator.auth.login');

require __DIR__.'/auth.php';
<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::view('/tickets', 'tickets', ['name' => 'John']);

Route::get('/ticket', function () {
	$qrCode = QrCode::size(200)
	                ->color(190, 24, 93)
	                ->generate('240627-259750');

	return view('tickets', compact('qrCode'));
});

Route::get('scan/tickets/{id}', [ScanController::class, 'ticket'])->name('scan.tickets');
Route::get('scan/munten/{id}', [ScanController::class, 'munten'])->name('scan.vouchers');
Route::get('betaling/{token}/voltooid', [PaymentController::class, 'completed'])->name('payment.completed');

Volt::route('/', 'frontend.home')->name('home');
Volt::route('/nieuws', 'frontend.news')->name('news');
Volt::route('/bestellen', 'frontend.order')->name('order');
Volt::route('/login-of-registreren', 'frontend.payment')->name('payment');
Volt::route('password/reset/{token}', 'auth.reset-password')->name('password.reset');
Volt::route('/betaling/{token}', 'frontend.payment-return')->name('payment.return');
Volt::route('/betalen', 'frontend.payment')->name('payment');
Volt::route('/404', 'frontend.404')->name('frontend.404');

Volt::route('admin', 'administrator.auth.login')->name('administrator.auth.login');

require __DIR__ . '/auth.php';
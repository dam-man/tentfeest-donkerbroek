<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'frontend.home')->name('home');
Volt::route('/nieuws', 'frontend.news')->name('news');
Volt::route('/bestellen', 'frontend.order')->name('order');
Volt::route('/login-of-registreren', 'frontend.payment')->name('payment');
Volt::route('/betalen', 'frontend.payment')->name('payment');

Route::get('/login', function () {
	return view('welcome');
})->name('login');


Volt::route('admin', 'administrator.auth.login')->name('administrator.auth.login');

require __DIR__.'/auth.php';
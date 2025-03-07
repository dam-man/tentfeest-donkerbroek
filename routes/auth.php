<?php

use Livewire\Volt\Volt;



Route::middleware(['auth', 'admin'])->group(function () {

	Volt::route('administrator/403', 'administrator.globals.403')->name('administrator.unauthenticated');
	Volt::route('administrator/404', 'administrator.globals.404')->name('administrator.not.found.page');

	Volt::route('administrator/menu', 'administrator.menu.index')->name('administrator.menu.index');
	Volt::route('administrator/coupons', 'administrator.coupons.index')->name('administrator.coupons.index');
	Volt::route('administrator/gebruikers', 'administrator.users.index')->name('administrator.users.index');

	// Orders routes
	Volt::route('administrator/bestelling/{order}/details', 'administrator.orders.form')->name('administrator.orders.form');
	Volt::route('administrator/bestellingen', 'administrator.orders.index')->name('administrator.orders.index');

	// News routes
	Volt::route('administrator/nieuws/{article}/bewerken', 'administrator.news.form')->name('administrator.news.edit');
	Volt::route('administrator/nieuws/toevoegen', 'administrator.news.form')->name('administrator.news.add');
	Volt::route('administrator/nieuws', 'administrator.news.index')->name('administrator.news.index');

	// Events routes
	Volt::route('administrator/tickets/{event}/bewerken', 'administrator.events.form')->name('administrator.events.form');
	Volt::route('administrator/tickets/toevoegen', 'administrator.events.form')->name('administrator.events.add');
	Volt::route('administrator/tickets', 'administrator.events.index')->name('administrator.events.index');

	Route::fallback(function () {
		return response()->redirectTo(route('administrator.orders.index'));
	});
});
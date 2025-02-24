<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
	protected $appends = ['url', 'middleware'];

	protected $fillable = [
		'name',
		'route',
		'role',
		'icon',
		'type',
		'ordering',
		'publish_at',
		'unpublish_at',
		'published',
	];

	protected function middleware(): Attribute
	{
		$routes = Route::getRoutes()->getRoutesByName();

		$middleware = ! empty($this->route) && ! empty($routes[$this->route]) ? $routes[$this->route]->action['middleware'] : [];

		if (in_array('admin', $middleware))
		{
			$middleware = [
				'name'  => 'Admin',
				'color' => 'red',
			];
		}
		elseif (in_array('auth', $middleware))
		{
			$middleware = [
				'name'  => 'Users',
				'color' => 'indigo',
			];
		}
		else
		{
			$middleware = [
				'name'  => 'Public',
				'color' => 'green',
			];
		}

		return new Attribute(
			get: fn() => $middleware,
		);
	}

	protected function url(): Attribute
	{
		$routes = Route::getRoutes()->getRoutesByName();

		return new Attribute(
			get: fn() => ! empty($this->route) && ! empty($routes[$this->route]) ? $routes[$this->route]->uri : '#',
		);
	}
}

<?php

namespace App\Livewire\Forms;

use App\Models\Menu;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class MenuForm extends Form
{
	public ?Menu $menu;

	public string $name;
	public string $icon;
	public string $route;
	public string $type;
	public string $role;
	public int    $ordering;
	public string $publish_at;
	public string $unpublish_at;

	public function setForm($menuId): void
	{
		$this->menu = Menu::whereId($menuId)->first();

		$this->name         = $this->menu->name ?? '';
		$this->icon         = $this->menu->icon ?? '';
		$this->route        = $this->menu->route ?? '';
		$this->type         = $this->menu->type ?? '';
		$this->role         = $this->menu->role ?? '';
		$this->ordering     = $this->menu->ordering ?? 0;
		$this->publish_at   = $this->menu->publish_at ?? '';
		$this->unpublish_at = $this->menu->unpublish_at ?? '';
	}

	/**
	 * @throws ValidationException
	 */
	public function update(): bool
	{
		$menu = $this->validate();

		if(empty($menu['unpublish_at']))
		{
			unset($menu['unpublish_at']);
		}

		if ($this->menu->update($menu))
		{
			return true;
		}

		return false;
	}

	/**
	 * @throws ValidationException
	 */
	public function store(): bool
	{
		$menu = $this->validate();

		if(empty($menu['unpublish_at']))
		{
			unset($menu['unpublish_at']);
		}

		if (Menu::create($menu))
		{
			return true;
		}

		return false;
	}

	public function rules(): array
	{
		return [
			'name'         => ['required', 'string', 'max:255'],
			'icon'         => ['required', 'string', 'max:255'],
			'route'        => ['required', 'string', 'max:255'],
			'type'         => ['required', 'string', 'in:frontend,backend'],
			'role'         => ['required', 'string', 'in:admin,registered,guest'],
			'publish_at'   => ['required', 'date'],
			'unpublish_at' => ['nullable', 'date', 'after:publish_at'],
		];
	}

	public function messages(): array
	{
		return [
			'name.required'      => 'De naam is verplicht',
			'name.string'        => 'De naam moet een tekst zijn',
			'icon.required'      => 'Het icoon is verplicht',
			'icon.string'        => 'Het icoon moet een tekst zijn',
			'route.required'     => 'De route is verplicht',
			'route.string'       => 'De route moet een tekst zijn',
			'type.required'      => 'Het type is verplicht',
			'type.string'        => 'Het type moet een tekst zijn',
			'type.in'            => 'Ongeldig type menu',
			'role.required'      => 'De rol is verplicht',
			'role.string'        => 'De rol moet een tekst zijn',
			'role.in'            => 'Ongeldige rol',
			'publish_at.date'    => 'De publicatiedatum moet een datum zijn',
			'unpublish_at.date'  => 'De einddatum moet een datum zijn',
			'unpublish_at.after' => 'De einddatum moet na de publicatiedatum liggen',
		];
	}
}

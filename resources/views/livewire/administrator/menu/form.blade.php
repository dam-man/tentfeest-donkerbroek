<?php

use App\Livewire\Forms\MenuForm;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;

new class extends Component {

	#[Reactive]
	public int $menuId;

	public MenuForm $form;

	public function mount($menuId = 0): void
	{
		$this->menuId = $menuId;
		$this->form->setForm($menuId);
	}

	public function store(): void
	{
		if ($this->form->store())
		{
			Flux::toast(
				text: 'Menu is toegevoegd.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.menu.index'), navigate: true);
		}
	}

	public function update(): void
	{
		if ($this->form->update())
		{
			Flux::toast(
				text: 'Menu is bijgewerkt.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.menu.index'), navigate: true);
		}
	}

	public function with(): array
	{
		$results = [];
		$routes  = Route::getRoutes();

		foreach ($routes->getRoutesByName() as $route)
		{
			if (
				str_contains($route->action['as'], 'livewire')
				|| str_contains($route->action['as'], 'add')
				|| str_contains($route->action['as'], 'edit')
				|| str_contains($route->uri, '{')
			)
			{
				continue;
			}

			$results[$route->action['as']] = $route->uri === '/' ? 'home' : $route->uri;
		}

		return [
			'routes' => $results,
		];
	}
};

?>

<div>
	<div class="mb-8">
		<flux:heading size="lg">{{$menuId ? 'Bewerk Menu Item' : 'Menu Item Toevoegen'}}</flux:heading>
	</div>

	<form wire:submit="{{$menuId ? 'update' : 'store'}}">
		<div class="space-y-3">
			<flux:input wire:model="form.name" label="Naam" placeholder="Naam"/>
			<flux:input wire:model="form.icon" label="Icoon" placeholder="Icoon"/>

			<flux:select wire:model.live="form.route" variant="listbox" label="URL" placeholder="URL">
				@foreach($routes as $route => $url)
					<flux:option value="{{$route}}">{{$url}}</flux:option>
				@endforeach
			</flux:select>

			<flux:radio.group wire:model="form.type" label="Type Menu" variant="segmented">
				<flux:radio value="frontend" label="Frontend" />
				<flux:radio value="backend" label="Backend" />
			</flux:radio.group>

			<flux:radio.group wire:model="form.role" label="Zichtbaarheid" variant="segmented">
				<flux:radio value="admin" label="Administrator" />
				<flux:radio value="registered" label="Geregistreerd" />
				<flux:radio value="guest" label="Publiek | Gast" />
			</flux:radio.group>

			<flux:input wire:model="form.publish_at" type="date" max="2999-12-31" label="Zichtbaarheid Vanaf"/>

			<flux:input wire:model="form.unpublish_at" type="date" max="2999-12-31" label="Geldig tot datum"/>

			<div class="flex pt-4">
				<flux:spacer/>
				<flux:button type="submit" class="!bg-green-600">
					Opslaan
				</flux:button>
			</div>
		</div>
	</form>
</div>

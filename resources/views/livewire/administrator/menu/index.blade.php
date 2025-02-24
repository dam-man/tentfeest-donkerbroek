<?php

use App\Models\Menu;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {

	use WithPagination;

	public int    $menuId = 0;
	public string $search = '';

	protected $listeners = ['searchUpdated'];

	public function searchUpdated($search): void
	{
		$this->resetPage();
		$this->search = $search;
	}

	public function edit($id): void
	{
		$this->menuId = $id;
		$this->modal('menu-form')->show();
	}

	public function addMenuItem(): void
	{
		$this->menuId = 0;
		$this->modal('menu-form')->show();
	}

	public function with(): array
	{
		$search = $this->search ?? '';

		$items = Menu::query()
		             ->when($search, function ($q) use ($search) {
			             $q->where('name', 'LIKE', '%' . $search . '%')
			               ->orWhere('route', 'LIKE', '%' . $search . '%');
		             })
		             ->orderByDesc('type')
		             ->orderBy('role')
		             ->orderBy('ordering')
		             ->paginate(15);

		return [
			'items' => $items,
		];
	}
};

?>

<div>
	<livewire:components.administrator.header
			title="Menu Beheer"
			subTitle="Beheert alle menu items van de website."
			:showSearch="true"
			:showAddButton="true"
			@add="addMenuItem"
	/>

	<flux:table :paginate="$items">
		<flux:columns>
			<flux:column>
				Naam
			</flux:column>
			<flux:column>
				Icoon
			</flux:column>
			<flux:column>
				Route
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Type
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Authenticatie
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Publicatie Datum
				</div>
			</flux:column>
			<flux:column></flux:column>
		</flux:columns>

		<flux:rows>
			@foreach($items as $item)
				<flux:row>
					<flux:cell>{{$item->name}}</flux:cell>
					<flux:cell>
						@if($item->icon)
							<flux:badge size="sm" icon="{{$item->icon}}">{{$item->icon}}</flux:badge>
						@endif
					</flux:cell>
					<flux:cell>{{$item->url === '/' ? 'home' : $item->url}}</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							@if($item->type == 'backend')
								<flux:badge color="red" size="sm" inset="top bottom">
									Backend
								</flux:badge>
							@else
								<flux:badge color="green" size="sm" inset="top bottom">
									Frontend
								</flux:badge>
							@endif
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							<flux:badge color="{{$item->middleware['color']}}" size="sm" inset="top bottom">
								{{$item->middleware['name']}}
							</flux:badge>
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							{{Carbon::parse($item->publish_at)->format('d-m-Y')}}
						</div>
					</flux:cell>
					<flux:cell class="float-right mr-4">
						<flux:dropdown>
							<flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>
							<flux:menu>
								<flux:menu.item wire:click="edit({{$item->id}})">Bewerken</flux:menu.item>
								<flux:menu.separator/>
								<flux:menu.item wire:click="showDeleteConfirmation({{$item->id}})" variant="danger">Verwijderen</flux:menu.item>
							</flux:menu>
						</flux:dropdown>
					</flux:cell>
				</flux:row>
			@endforeach
		</flux:rows>
	</flux:table>

	<flux:modal name="menu-form" variant="flyout" class="space-y-6">
		<livewire:administrator.menu.form :menuId="$menuId" :key="$menuId"/>
	</flux:modal>
</div>

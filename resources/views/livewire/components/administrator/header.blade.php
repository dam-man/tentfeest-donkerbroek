<?php

use Livewire\Volt\Component;

new class extends Component {

	public string $search = '';

	public string $title         = '';
	public string $subTitle      = '';
	public bool   $showSearch    = false;
	public bool   $showAddButton = false;
	public object $options;
	public        $filter;
	public bool   $keyValuePair  = false;

	public function updatedSearch(): void
	{
		$this->dispatch('searchUpdated', $this->search);
	}

	public function updatedFilter(): void
	{
		$this->dispatch('filterUpdated', $this->filter);
	}

	public function add(): void
	{
		$this->dispatch('add');
	}
};

?>

<div>
	<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">

		<div class="flex-grow mb-4 md:mb-0">
			<flux:heading size="xl">{{$title}}</flux:heading>
			@if($subTitle)
				<flux:subheading>{{$subTitle}}</flux:subheading>
			@endif
		</div>

		@if($showSearch)
			<div class="w-full md:w-72 text-right mb-4 md:mb-0">
				<flux:input.group class="float-right">
					<flux:input wire:model.live.debounce="search" placeholder="Zoekterm..." clearable/>
					<flux:button icon="magnifying-glass"></flux:button>
				</flux:input.group>
			</div>
		@endif

		@if($options)
			<div class="w-full md:w-64 text-right md:ml-4">
				<flux:select wire:model.live="filter" variant="listbox" placeholder="Filter" class="float-right" clearable>
					@foreach ($options as $option)
						<flux:option value="{{ is_object($option) ? $option->id : $option }}">
							{{is_object($option) ? $option->name : ucfirst($option)}}
						</flux:option>
					@endforeach
				</flux:select>
			</div>
		@endif

		@if($showAddButton)
			<div class="w-full md:w-36 text-right">
				<flux:button wire:click="add" icon="plus" variant="primary">Toevoegen</flux:button>
			</div>
		@endif

	</div>
</div>

<?php

use App\Models\Menu;
use Livewire\Volt\Component;

new class extends Component {

	public function with(): array
	{
		$mainMenuItems = Menu::query()
		                     ->where('type', 'frontend')
		                     ->where('role', 'guest')
		                     ->orderBy('ordering')
		                     ->get();

		return [
			'mainMenu' => $mainMenuItems,
		];
	}

};

?>

<div>
	<flux:navbar class="-mb-px max-lg:hidden font-family-changa">
		@foreach($mainMenu as $menu)
			<flux:navbar.item href="{{route($menu->route)}}" :accent="false">
				<span class="text-xl">{{$menu->name}}</span>
			</flux:navbar.item>
		@endforeach
	</flux:navbar>



</div>

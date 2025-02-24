<?php

use App\Models\Menu;
use Livewire\Volt\Component;

new class extends Component {

	public function with()
	{
		$items = Menu::query()
		             ->where('type', 'backend')
		             ->orderBy('ordering')
		             ->get();

		return [
			'items' => $items,
		];
	}

};

?>

<div>
	<flux:navlist>
		@foreach($items as $item)
			<flux:navlist.item icon="{{$item->icon}}" href="{{route($item->route)}}">{{$item->name}}</flux:navlist.item>
		@endforeach
	</flux:navlist>
</div>

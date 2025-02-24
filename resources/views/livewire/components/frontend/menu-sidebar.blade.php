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
    <flux:navlist variant="outline">
        @foreach($mainMenu as $menuItem)
            <flux:navlist.item icon="{{$menuItem->icon}}" :accent="false" href="{{route($menuItem->route)}}" class="font-family-changa">
                <span class="text-xl hover:text-gray-400">{{$menuItem->name}}</span>
            </flux:navlist.item>
        @endforeach
    </flux:navlist>
</div>

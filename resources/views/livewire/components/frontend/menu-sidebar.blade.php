<?php

use App\Models\Menu;
use Livewire\Volt\Component;

new class extends Component {

    public function logout(): void
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        $this->redirect(route('home'), navigate: true);
    }

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

            @if(auth()->check())
                <flux:navlist.item icon="x-circle" wire:click="logout" :accent="false" class="font-family-changa">
                    <span class="text-xl">Uitloggen</span>
                </flux:navlist.item>
            @else
                <flux:modal.trigger name="show-login-form">
                    <flux:navlist.item icon="user" :accent="false" class="font-family-changa">
                        <span class="text-xl">Inloggen</span>
                    </flux:navlist.item>
                </flux:modal.trigger>
            @endif
    </flux:navlist>
</div>

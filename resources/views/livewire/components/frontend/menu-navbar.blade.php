<?php

use App\Livewire\Forms\LoginForm;
use App\Models\Menu;
use Livewire\Volt\Component;

new class extends Component {

	public LoginForm $form;

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
	<flux:navbar class="-mb-px max-lg:hidden font-family-changa">

		@foreach($mainMenu as $menu)
			<flux:navbar.item href="{{route($menu->route)}}" :accent="false">
				<span class="text-xl">{{$menu->name}}</span>
			</flux:navbar.item>
		@endforeach

		@if(auth()->check())
			<flux:navbar.item wire:click="logout" :accent="false">
				<span class="text-xl">Uitloggen</span>
			</flux:navbar.item>
		@else
			<flux:modal.trigger name="show-login-form">
				<flux:navbar.item :accent="false">
					<span class="text-xl">Inloggen</span>
				</flux:navbar.item>
			</flux:modal.trigger>
		@endif

	</flux:navbar>

	<flux:modal name="show-login-form" class="!bg-blue w-96 md:w-96 ma-6">
		<div class="space-y-6">
			<div>
				<flux:heading size="lg">Inloggen</flux:heading>
				<flux:subheading>
					Log in op onze website en bestel in een razende rapje je tickets. Wij kunnen niet wachten om je te zien.
					Ga je voor het eerst bestellen? Dan kan je tijdens het bestellen een account aanmaken.
				</flux:subheading>
			</div>

			<livewire:components.frontend.auth.login/>
		</div>
	</flux:modal>

</div>

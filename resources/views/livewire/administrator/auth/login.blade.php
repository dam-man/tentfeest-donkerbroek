<?php

use App\Livewire\Forms\LoginForm;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.login')] class extends Component {

	public LoginForm $form;

	public function login(): void
	{
		$this->validate();

		$this->form->authenticate();

		Session::regenerate();

		$this->redirectIntended(default: route('administrator.orders.index'),navigate: true);
	}
} ?>

<div>
	<div class="flex items-center justify-center h-screen -mt-20">
		<div class="p-4 w-full md:w-1/5 border border-zinc-500 rounded-md shadow-sm">

			<div class="flex justify-center items-center mb-5 mt-5">
				<img src="https://raw.githubusercontent.com/dam-man/tickets-tentfeest/main/logo.png" class="h20 w-20"/>
			</div>

			<flux:spacer/>

			<form wire:submit="login">

				<div class="space-y-4">
					<flux:input wire:model="form.email" label="Email" placeholder="mail@example.com"/>
					<flux:input wire:model="form.password" type="password" label="Wachtwoord" placeholder="Wachtwoord" viewable/>

					<flux:checkbox wire:model="form.remember" label="Onthoud mijn login"/>
				</div>

				<flux:button
						type="submit"
						variant="primary"
						class="w-full !bg-green-600 mt-8 mb-8 !text-white !h-15"
				>Inloggen
				</flux:button>

			</form>

		</div>
	</div>
</div>

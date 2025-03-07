<?php

use App\Livewire\Forms\RegistrationForm;
use Livewire\Volt\Component;

new class extends Component {

	public RegistrationForm $form;

	public function register(): void
	{
		if ($this->form->store())
		{
			Flux::toast(
				text: 'Je account is aangemaakt.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('payment'), navigate: true);
		}
	}

}; ?>

<div>
	<form wire:submit="register">

		<div class="space-y-4">
			<flux:input
					wire:model="form.name"
					label="Naam"
					type="name"
					class="frontend"
			/>

			<flux:input
					wire:model="form.email"
					label="Email"
					type="email"
					class="frontend"
			/>

			<flux:input
					wire:model="form.password"
					label="Wachtwoord"
					class="frontend"
					type="password"
					viewable
			/>

			<flux:input
					wire:model="form.password_confirmation"
					label="Herhaal Wachtwoord"
					class="frontend"
					type="password"
					viewable
			/>
		</div>

		<div class="mt-4">
			<flux:button type="submit" variant="primary" class="!bg-green-700 text-white w-full h-12">
				REGISTREER GRATIS ACCOUNT
			</flux:button>
		</div>

	</form>
</div>

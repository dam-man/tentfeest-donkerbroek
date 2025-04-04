<?php

use App\Livewire\Forms\LoginForm;
use App\Traits\HasShoppingCartSession;
use Livewire\Volt\Component;

new class extends Component {

	use HasShoppingCartSession;

	public LoginForm $form;

	public string $route = '';
	public string $email = '';

	public bool $showResetTokenForm = false;

	public function mount(): void
	{
		$this->route =  Route::currentRouteName();
	}

	public function login(): void
	{
		$this->validate();

		if ($this->form->authenticate())
		{
			Flux::toast(
				text: 'Welkom terug ' . auth()->user()->name,
				heading: 'Succes',
				variant: 'success',
			);

			Session::regenerate();

			Flux::toast(
				text: 'Yes, je bent nu ingelogd!',
				heading: 'Hoppa!',
				variant: 'success',
			);

			Flux::modal('show-login-form')->close();

			$this->redirect(route($this->route), navigate: true);
		}
	}

	public function sendPasswordResetLink(): void
	{
		$this->validate([
			'email' => ['required', 'string', 'email'],
		]);

		Password::sendResetLink($this->only('email'));

		Flux::toast(
			text: 'Je ontvangt binnen enkele minuten een email met een reset token.',
			heading: 'Whooochh!!!',
			variant: 'success',
		);

		$this->redirect(route('home'), navigate: true);
	}

}; ?>

<div>
	<form wire:submit="login">

		<div class="space-y-4">
			<flux:input
					wire:model="form.email"
					label="Email"
					type="email"
					class="frontend"
			/>

			<flux:field>

				<div class="mb-3 flex justify-between">
					<flux:label>Password</flux:label>
					<flux:modal.trigger name="forgotten-password">
						<flux:link href="#" variant="subtle" class="text-xs pt-1">Wachtwoord Vergeten?</flux:link>
					</flux:modal.trigger>
				</div>

				<flux:input wire:model="form.password" type="password" class="frontend"/>
				<flux:error name="password"/>
			</flux:field>

			<flux:checkbox wire:model="form.remember" label="Onthoud mijn login"/>

			<div class="mt-6">
				<flux:button
						type="submit"
						variant="primary"
						class="!bg-green-700 text-white w-full h-12"
				>
					INLOGGEN
				</flux:button>
			</div>
		</div>
	</form>

	<flux:modal name="forgotten-password" class="!bg-blue md:w-96">
		<div class="space-y-6">
			<div>
				<flux:heading size="lg">Wachtwoord vergeten?</flux:heading>
				<flux:subheading>
					Geen probleem, voer je email adres in en we sturen je een link.
					Hiermee kan je je wachtwoord resetten.
				</flux:subheading>
			</div>

			<form wire:submit="sendPasswordResetLink">
				<flux:input wire:model="email" placeholder="Email Adres"/>

				<div class="flex">
					<flux:spacer/>
					<flux:button type="submit" variant="primary" class="!bg-pink mt-6">
						Verstuur Reset Token
					</flux:button>
				</div>
			</form>

		</div>
	</flux:modal>
</div>

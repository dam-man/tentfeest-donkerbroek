<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.frontend')] class extends Component {

	#[Locked]
	public string $token                 = '';
	public string $email                 = '';
	public string $password              = '';
	public string $password_confirmation = '';

	public function mount(string $token): void
	{
		$this->token = $token;
		$this->email = request()->string('email');

		if ( ! DB::table('password_reset_tokens')->where('email', $this->email)->exists())
		{
			Flux::toast(
				text: 'De wachtwoord token is ongeldig, probeer het nogmaals.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirectRoute('frontend.404', navigate: true);
		}
	}

	protected function messages(): array
	{
		return [
			'password.required' => 'Het wachtwoord is verplicht.',
			'confirmed'         => 'Opgegeven wachtwoorden komen niet overeen.',
			'password'          => [
				'letters'       => 'Je wachtwoord moet minimaal 1 letter bevatten.',
				'mixed'         => 'Het wachtwoord moet minimaal 1 letter en 1 hoofdletter bevatten.',
				'numbers'       => 'Het wachtwoord moet minimaal 1 cijfer bevatten.',
				'symbols'       => 'Het wachtwoord moet minimaal 1 speciaal teken bevatten.',
				'uncompromised' => 'Dit wachtwoord komt voor in een data-breach, kies een ander wachtwoord.',
			],
			'min'               => [
				'string' => 'Het wachtwoord bestaat uit minimaal 8 karakters.',
			],
		];
	}

	public function resetPassword(): void
	{
		$this->validate([
			'token'    => ['required'],
			'email'    => ['required', 'string', 'email'],
			'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
		], messages: $this->messages());

		$status = Password::reset(
			$this->only('email', 'password', 'password_confirmation', 'token'),
			function ($user) {
				$user->forceFill([
					'password'       => Hash::make($this->password),
					'remember_token' => Str::random(60),
				])->save();

				Auth::login($user);

				event(new PasswordReset($user));
			}
		);

		// If the password was successfully reset, we will redirect the user back to
		// the application's home authenticated view. If there is an error we can
		// redirect them back to where they came from with their error message.
		if ($status != Password::PasswordReset)
		{
			dump($status);
			$this->addError('email', __($status));

			return;
		}

		Flux::toast(
			text: 'Wachtwoord is hersteld, voor het gemak hebben we je meteen ook ingelogd!',
			heading: 'Whooochh!!!',
			variant: 'success',
		);

		$this->redirectRoute('home', navigate: true);
	}
}; ?>

<div class="flex flex-col items-center">
	<div class="w-full md-1/4 lg:w-1/3">

		<flux:card class="space-y-6">
			<div>
				<flux:heading size="lg">Wachtwoord herstellen</flux:heading>
				<flux:text class="mt-2">Geef hieronder je nieuwe wachtwoord in, daarna kan je op "Wachtwoord Herstellen" klikken!</flux:text>
			</div>

			<form wire:submit="resetPassword" class="flex flex-col gap-6">
				<!-- Email Address -->
				<flux:input
						wire:model="email"
						id="email"
						label="Email"
						type="email"
						name="email"
						required
						autocomplete="email"
				/>

				<!-- Password -->
				<flux:input
						wire:model="password"
						id="password"
						label="Nieuwe Wachtwoord"
						type="password"
						name="password"
						required
						autocomplete="new-password"
						placeholder="Password"
				/>

				<!-- Confirm Password -->
				<flux:input
						wire:model="password_confirmation"
						id="password_confirmation"
						label="Herhaal Wachtwoord"
						type="password"
						name="password_confirmation"
						required
						autocomplete="new-password"
				/>

				<div class="flex items-center justify-end">
					<flux:button type="submit" variant="primary" class="!bg-green-800 w-full">
						Wachtwoord Herstellen
					</flux:button>
				</div>
			</form>

		</flux:card>
	</div>
</div>
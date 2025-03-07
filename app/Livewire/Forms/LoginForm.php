<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
	#[Validate('required|string|email')]
	public string $email = '';

	#[Validate('required|string')]
	public string $password = '';

	#[Validate('boolean')]
	public bool $remember = false;

	public function messages(): array
	{
		return [
			'email.required'    => 'Voer een email adres is.',
			'email.email'       => 'Dit is een ongeldig email adres.',
			'password.required' => 'Voer je wachtwoord in.',
		];
	}

	/**
	 * Attempt to authenticate the request's credentials.
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function authenticate(): void
	{
		$this->ensureIsNotRateLimited();

		$user = User::query()->where('email', $this->email)->first();

		if ( ! Auth::attempt($this->only(['email', 'password']), $this->remember))
		{
			RateLimiter::hit($this->throttleKey());

			throw ValidationException::withMessages([
				'form.email' => 'Gebruikersnaam/wachtwoord incorrect.',
			]);
		}

		$user->last_login = now();
		$user->save();

		RateLimiter::clear($this->throttleKey());
	}

	/**
	 * Ensure the authentication request is not rate limited.
	 * @throws ValidationException
	 */
	protected function ensureIsNotRateLimited(): void
	{
		if ( ! RateLimiter::tooManyAttempts($this->throttleKey(), 5))
		{
			return;
		}

		event(new Lockout(request()));

		$seconds = RateLimiter::availableIn($this->throttleKey());

		throw ValidationException::withMessages([
			'form.email' => trans('auth.throttle', [
				'seconds' => $seconds,
				'minutes' => ceil($seconds / 60),
			]),
		]);
	}

	/**
	 * Get the authentication rate limiting throttle key.
	 */
	protected function throttleKey(): string
	{
		return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
	}
}

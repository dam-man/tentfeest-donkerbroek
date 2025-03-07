<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RegistrationForm extends Form
{
	#[Validate]
	public string $name       = 'Elfi';
	public string $role       = 'guest';
	public string $ip_address = '';
	public string $email      = 'e.m.dijkers@gmail.com';
	public string $password   = '';

	public string $password_confirmation = '';

	/**
	 * @throws ValidationException
	 */
	public function store(): bool
	{
		$user = $this->validate();

		$user['password']   = Hash::make($user['password']);
		$user['ip_address'] = request()->ip();

		if ($user = User::create($user))
		{
			Session::regenerate();

			$user->last_login = now();
			$user->save();

			if ( ! Auth::attempt($this->only(['email', 'password']), true))
			{
				return false;
			}

			return true;
		}

		return false;
	}

	public function rules(): array
	{
		return [
			'name'                  => ['required', 'string', 'max:255'],
			'role'                  => ['required', 'string'],
			'email'                 => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
			'password'              => ['required', 'string', 'confirmed', Password::default()],
			'password_confirmation' => ['required', 'string'],
		];
	}

	public function messages(): array
	{
		return [
			'name.required'     => 'De naam is verplicht',
			'email.required'    => 'Het email adres is verplicht.',
			'email.email'       => 'Het email adres is ongeldig.',
			'email.unique'      => 'Dit email adres bestaat reeds in het systeem.',
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
				'string' => 'Het wachtwoord moet minimaal 8 karakters bevatten.',
			],
		];
	}
}

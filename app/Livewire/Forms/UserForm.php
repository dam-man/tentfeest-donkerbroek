<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class UserForm extends Form
{
	public ?User $user;

	public string $name;
	public string $email;
	public string $role;
	public string $password;
	public string $password_confirmation;

	public function setForm($userid): void
	{
		$this->user = User::whereId($userid)->first();

		$this->name                  = $this->user->name ?? '';
		$this->email                 = $this->user->email ?? '';
		$this->role                  = $this->user->role ?? '';
		$this->password              = '';
		$this->password_confirmation = '';
	}

	/**
	 * @throws ValidationException
	 */
	public function update(): bool
	{
		$user = $this->validate();

		if ( ! empty($validated['password']))
		{
			$user['password'] = Hash::make($validated['password']);
		}

		if ($this->user->update($user))
		{
			return true;
		}

		return false;
	}

	/**
	 * @throws ValidationException
	 */
	public function store(): bool
	{
		$user = $this->validate();

		$user['password'] = Hash::make($user['password']);

		if (User::create($user))
		{
			return true;
		}

		return false;
	}

	/**
	 * @throws ValidationException
	 */
	public function validated(): array
	{
		return $this->validate($this->rules(), $this->messages());
	}

	public function rules(): array
	{
		if (isset($this->user) && $this->user->id > 0)
		{
			return [
				'name'      => ['required', 'string', 'max:255'],
				'role'      => ['required', 'string', 'in:admin,guest'],
				'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
				'password'  => ['nullable', 'string', 'confirmed', Password::default()],
			];
		}

		return [
			'name'      => ['required', 'string', 'max:255'],
			'role'      => ['required', 'string', 'in:admin,guest'],
			'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
			'password'  => ['required', 'string', 'confirmed', Password::default()],
		];
	}

	public function messages(): array
	{
		return [
			'name.required'      => 'De naam is verplicht',
			'status_id.required' => 'Geef de medewerker status op.',
			'role.required'      => 'Je dient deze medewerker een rol te geven',
			'email.required'     => 'Het email adres is verplicht.',
			'email.email'        => 'Het email adres is ongeldig.',
			'email.unique'       => 'Dit email adres bestaat reeds in het systeem.',
			'password.required'  => 'Het wachtwoord is verplicht.',
			'confirmed'          => 'Opgegeven wachtwoorden komen niet overeen.',
			'password'           => [
				'letters'       => 'Je wachtwoord moet minimaal 1 letter bevatten.',
				'mixed'         => 'Het wachtwoord moet minimaal 1 letter en 1 hoofdletter bevatten.',
				'numbers'       => 'Het wachtwoord moet minimaal 1 cijfer bevatten.',
				'symbols'       => 'Het wachtwoord moet minimaal 1 speciaal teken bevatten.',
				'uncompromised' => 'Dit wachtwoord komt voor in een data-breach, kies een ander wachtwoord.',
			],
			'min' => [
				'string' => 'Het wachtwoord bestaat uit minimaal 8 karakters.',
			],
		];
	}
}

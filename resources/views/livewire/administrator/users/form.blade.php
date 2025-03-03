<?php

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;

new class extends Component {

	#[Reactive]
	public $userId;

	public ?User    $user;
	public UserForm $form;
	public string   $name;

	public function mount($userId = 0): void
	{
		$this->userId = $userId;

		$this->form->setForm($this->userId);
	}

	public function store(): void
	{
		if ($this->form->store())
		{
			Flux::toast(
				text: 'Gebruiker is toegevoegd.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.users.index'), navigate: true);
		}
	}

	public function update(): void
	{
		if ($this->form->update())
		{
			Flux::toast(
				text: 'Gebruiker is bijgewerkt.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.users.index'), navigate: true);
		}
	}

}; ?>

<div>
	<div class="mb-8">
		<flux:heading size="lg">{{$userId ? 'Bewerk Gebruiker' : 'Gebruiker Toevoegen'}}</flux:heading>
		<flux:subheading>
			Beheer de gebruikers van het systeem, <br/>voeg nieuwe gebruikers toe of bewerk bestaande gebruikers.
		</flux:subheading>
	</div>

	<form wire:submit="{{ $userId ? 'update' : 'store'}}">
		<div class="space-y-5">
			<flux:input wire:model="form.name" label="Naam" placeholder="Naam"/>

			<flux:select wire:model="form.role" variant="listbox" label="Status" placeholder="Type Account">
				<flux:option value="admin">Administrator</flux:option>
				<flux:option value="guest">Gebruiker</flux:option>
			</flux:select>

			<flux:input wire:model="form.email" label="Email" placeholder="Email"/>
			<flux:input wire:model="form.password" type="password" label="Wachtwoord" placeholder="Wachtwoord" viewable/>
			<flux:input wire:model="form.password_confirmation" type="password" label="Herhaal Wachtwoord" placeholder="Herhaal Wachtwoord" viewable/>

			<div class="flex mt-8">
				<flux:spacer/>
				<flux:button type="submit" class="!bg-green-600">
					Opslaan
				</flux:button>
			</div>
		</div>
	</form>
</div>
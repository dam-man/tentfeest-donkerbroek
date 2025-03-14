<?php

use App\Livewire\Forms\LoginForm;
use Livewire\Volt\Component;

new class extends Component {

    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        if($this->form->authenticate())
        {
            Flux::toast(
                    text: 'Welkom terug ' . auth()->user()->name,
                    heading: 'Succes',
                    variant: 'success',
            );

            Session::regenerate();

            $this->redirect(route('payment'), navigate: true);
		}
    }

}; ?>

<div>
    <form wire:submit="login">

        <div class="space-y-4">
            <flux:input wire:model="form.email"
                        label="Email"
                        type="email"
                        class="frontend"/>

            <flux:field>

                <div class="mb-3 flex justify-between">
                    <flux:label>Password</flux:label>
                    <flux:link href="#" variant="subtle" class="text-xs pt-1">Wachtwoord Vergeten?</flux:link>
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
</div>

<?php

use App\Livewire\Forms\LoginForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.frontend')] class extends Component {

	public LoginForm $form;

	public function login(): void
	{
		$this->validate();

		$this->form->authenticate();

		Session::regenerate();

		$this->redirectIntended(default: route('administrator.orders.index'), navigate: true);
	}

};

?>

<div>
	<livewire:components.frontend.title
			title="Afrekenen"
			mobile="Afrekenen"
			color="#0710db"
			align="center"
	/>


	<div class="flex flex-wrap mt-1 mb-10 md:mb-0 md:mt-8">
		<div class="w-full md:w-1/3 p-1">
			<flux:card class="space-y-6">
				<div>
					<flux:heading size="lg">Inloggen of Registreren?</flux:heading>
				</div>

				<div x-data="{ expanded: false }" class="space-y-6">

					<flux:tabs variant="segmented" class="w-full">
						<flux:tab x-on:click="expanded = ! expanded" icon="lock-closed" :accent="false">Inloggen</flux:tab>
						<flux:tab x-on:click="expanded = ! expanded" icon="user-plus" :accent="false">Registreren</flux:tab>
					</flux:tabs>

					<div x-show="expanded">

						<div class="space-y-6">
							<flux:input label="Naam" type="name" class="frontend"/>
							<flux:input label="Email" type="email" class="frontend"/>
							<flux:input label="Wachtwoord" type="email" class="frontend"/>
							<flux:input label="Herhaal Wachtwoord" type="email" class="frontend"/>
						</div>

						<div class="space-y-2">
							<flux:button variant="primary" class="!bg-green-700 text-white w-full h-12">
								Registreer Account
							</flux:button>
						</div>

					</div>

					<!-- Show login Form -->
					<div x-show="!expanded">

						<flux:input label="Email" type="email" class="frontend"/>

						<flux:field>
							<div class="mb-3 flex justify-between">
								<flux:label>Password</flux:label>
								<flux:link href="#" variant="subtle" class="text-xs pt-1">Wachtwoord Vergeten?</flux:link>
							</div>
							<flux:input type="password" class="frontend"/>
							<flux:error name="password"/>
						</flux:field>

						<div class="space-y-2">
							<flux:button variant="primary" class="!bg-green-700 text-white w-full h-12">
								Inloggen
							</flux:button>
						</div>

					</div>

				</div>

			</flux:card>
		</div>
		<div class="w-full md:w-1/3 p-1">
			<flux:card class="space-y-6">
				<div>
					<flux:heading size="lg">Ik ben nieuw hier!</flux:heading>
					<flux:subheading>Maak een account aan, en voltooi je bestelling!</flux:subheading>
				</div>

				<div class="space-y-6">
					<flux:input label="Naam" type="name" class="frontend"/>
					<flux:input label="Email" type="email" class="frontend"/>
					<flux:input label="Wachtwoord" type="email" class="frontend"/>
					<flux:input label="Herhaal Wachtwoord" type="email" class="frontend"/>
				</div>

				<div class="space-y-2">
					<flux:button variant="primary" class="!bg-green-700 text-white w-full h-12">
						Registreer Account
					</flux:button>
				</div>
			</flux:card>
		</div>
		<div class="w-full md:w-1/3 p-1">
			Shopping Card
		</div>
	</div>
</div>

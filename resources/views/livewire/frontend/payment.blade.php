<?php

use App\Livewire\Forms\LoginForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.frontend')] class extends Component {

};

?>

<div>
	<livewire:components.frontend.title
			title="Afrekenen"
			mobile="Afrekenen"
			color="#0710db"
			align="center"
	/>

	<div class="flex items-center justify-center">
		<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full md:w-3/4">
			<div>

				<div class="rounded-lg h-full bg-blue font-family-changa space-y-4">
					<div class="p-6 text-center">
						<h3 class="text-3xl">
							<div class="text-pink font-bold  uppercase">
								Jouw Account
							</div>
							<div class="uppercase text-white text-lg">
								<strong>Inloggen of Registreren</strong>
							</div>
						</h3>
					</div>

					<div x-data="{ register: false }" class="space-y-4 p-6 pt-0">

						<flux:tabs variant="segmented" class="w-full">
							<flux:tab x-on:click="register = ! register" icon="lock-closed" :accent="false">Inloggen</flux:tab>
							<flux:tab x-on:click="register = ! register" icon="user-plus" :accent="false">Registreren</flux:tab>
						</flux:tabs>

						<!-- Show Register Form -->
						<div x-show="register">

							<div class="space-y-4">
								<flux:input label="Naam" type="name" class="frontend"/>
								<flux:input label="Email" type="email" class="frontend"/>
								<flux:input label="Wachtwoord" type="email" class="frontend"/>
								<flux:input label="Herhaal Wachtwoord" type="email" class="frontend"/>
							</div>

							<div class="mt-4">
								<flux:button variant="primary" class="!bg-green-700 text-white w-full h-12">
									REGISTREER GRATIS ACCOUNT
								</flux:button>
							</div>

						</div>

						<div x-show="!register" class="space-y-4">

							<livewire:components.frontend.login/>

						</div>

					</div>

				</div>
			</div>
			<div class=" h-full">
				<livewire:components.frontend.shoppingcart page="payment"/>
			</div>
		</div>
	</div>

</div>

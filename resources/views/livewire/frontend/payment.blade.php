<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.frontend')] class extends Component {

};

?>

<div>
	@if(auth()->check())

		<div class="flex flex-col items-center">
			<div class="w-full md-1/4 lg:w-1/3 md:pt-20">
				<livewire:components.frontend.shoppingcart page="payment"/>
			</div>
		</div>

	@else

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
								<livewire:components.frontend.auth.register/>
							</div>

							<!-- Show Login Form -->
							<div x-show="!register" class="space-y-4">
								<livewire:components.frontend.auth.login/>
							</div>

						</div>

					</div>
				</div>
				<div class=" h-full">
					<livewire:components.frontend.shoppingcart page="payment"/>
				</div>
			</div>
		</div>
	@endif
</div>

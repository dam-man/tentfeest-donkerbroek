<?php

use App\Livewire\Forms\EventForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Event;
use Livewire\WithFileUploads;

new #[Layout('layouts.admin')] class extends Component {

	use WithFileUploads;

	public ?Event    $event;
	public EventForm $form;

	public function mount($event = 0): void
	{
		if ($event)
		{
			$this->event = $event;

			$this->form->setForm($event);
		}
	}

	public function cancel(): void
	{
		$this->redirect(route('administrator.events.index'), navigate: true);
	}

	public function store(): void
	{
		if ($this->form->store())
		{
			Flux::toast(
				text: 'Ticket is toegevoegd.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.events.index'), navigate: true);
		}
	}

	public function update(): void
	{
		if ($this->form->update())
		{
			Flux::toast(
				text: 'Ticket is bijgewerkt.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.events.index'), navigate: true);
		}
	}
};

?>

<div>
	<div class="mb-8">
		<flux:heading size="xl">{{$event ? 'Bewerk Ticket' : 'Ticket Toevoegen'}}</flux:heading>
		<flux:subheading>
			Beheer de tickets binnen het systeem, voeg nieuwe tickets toe of bewerk bestaande tickets.
		</flux:subheading>
	</div>

	<form wire:submit="{{$event ? 'update' : 'store'}}">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
			<div>
				<flux:card class="space-y-6 h-full">

					<flux:input wire:model="form.name" label="Weergave Ticket" placeholder="Weergave Ticket"/>
					<flux:input wire:model="form.description" label="Interne Omschrijving" placeholder="Interne Omschrijving"/>

					<flux:input wire:model="form.date" type="date" label="Datum" placeholder="Datum"/>

					<flux:radio.group wire:model="form.type" label="Type Ticket" variant="segmented" size="sm">
						<flux:radio value="munten" label="Munten"/>
						<flux:radio value="toegangskaart" label="Toegangskaart"/>
					</flux:radio.group>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
						<div>
							<flux:input.group label="Eenheidsprijs">
								<flux:input.group.prefix>€</flux:input.group.prefix>
								<flux:input wire:model="form.price" placeholder="Prijs"/>
							</flux:input.group>
						</div>
						<div>
							<flux:input wire:model="form.available" label="Beschikbaar" placeholder="Beschikbaar"/>
						</div>
					</div>

					<flux:input
							wire:model="form.unpublish_at"
							type="date"
							label="Automatisch Deactiveren (Datum)"
							placeholder="Automatisch Deactiveren (Datum)"
					/>

					<flux:input type="file" wire:model="form.image" label="Afbeelding (Homepage)"/>

				</flux:card>
			</div>

			<div>
				<flux:card class="space-y-6 h-full">

					<flux:radio.group wire:model="form.pdf_format" label="PDF Formaat" variant="segmented" size="sm">
						<flux:radio value="A4" label="A4"/>
						<flux:radio value="A5" label="A5"/>
					</flux:radio.group>

					<flux:radio.group wire:model="form.pdf_orientation" label="PDF Orientatie" variant="segmented" size="sm">
						<flux:radio value="portrait" label="Staand"/>
						<flux:radio value="landscape" label="Liggend"/>
					</flux:radio.group>

					<flux:input type="file" wire:model="form.pdf_source" label="Upload PDF Achtergrond"/>

				</flux:card>
			</div>

			<div>
				<flux:card class="space-y-6 h-full">

					<flux:textarea
							wire:model="form.bullets"
							label="Bullets (Scheidingsteken: ;)"
							placeholder="Bullets (Scheidingsteken: ;)"
					/>

					<div class="flex mt-8">
						<flux:spacer/>
						<flux:button wire:click="cancel" variant="danger" class="mr-2">
							Annuleren
						</flux:button>
						<flux:button type="submit" class="!bg-green-600">
							Opslaan
						</flux:button>
					</div>

				</flux:card>
			</div>
		</div>
	</form>
</div>

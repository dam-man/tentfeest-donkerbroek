<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Event;

new #[Layout('layouts.admin')] class extends Component {

	use WithPagination;

	public string $search  = '';
	public int    $eventId = 0;

	protected $listeners = ['searchUpdated'];

	public function searchUpdated($search): void
	{
		$this->resetPage();
		$this->search = $search;
	}

	public function edit($event): void
	{
		$this->redirect(route('administrator.events.form', ['event' => $event]), navigate: true);
	}

	public function deactivationConfirmation($id): void
	{
		$this->eventId = $id;

		Flux::modal('confirmation')->show();
	}

	public function deactivate(): void
	{
		$event = Event::find($this->eventId);

		$event->update([
			'published' => 0,
		]);

		Flux::toast(
			text: 'Ticket is gedeactiveerd.',
			heading: 'Succes',
			variant: 'success',
		);
	}

	public function activate($id): void
	{
		$event = Event::find($id);

		$event->update([
			'published' => 1,
		]);

		Flux::toast(
			text: 'Ticket is geactiveerd.',
			heading: 'Succes',
			variant: 'success',
		);
	}

	public function add(): void
	{
		$this->redirect(route('administrator.events.add'), navigate: true);
	}

	public function with(): array
	{
		$search = $this->search ?? '';

		$events = Event::query()
		               ->withCount('paidOrders as completed')
		               ->withCount('scannedOrders as scanned')
		               ->when($search, function ($q) use ($search) {
			               $q->where(function ($query) use ($search) {
				               $query->where('name', 'LIKE', '%' . $search . '%')
				                     ->orWhere('bullets', 'LIKE', '%' . $search . '%')
				                     ->orWhere('description', 'LIKE', '%' . $search . '%');
			               });
		               })
		               ->orderByDesc('type')
		               ->orderByDesc('date')
		               ->paginate(15);

		return [
			'events' => $events,
		];
	}
};

?>

<div>
	<livewire:components.administrator.header
			title="Toegangskaarten"
			subTitle="Overzicht van de te verkopen toegangskaarten."
			:showSearch="true"
			:showAddButton="true"
			@add="add"
	/>

	<flux:table :paginate="$events">
		<flux:columns>
			<flux:column>
				Omschrijving
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Datum</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Prijs</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Totaal</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Verkocht</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Verkocht %</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Scan %</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Ticket
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Status
				</div>
			</flux:column>
			<flux:column>
				<!-- Empty for action column -->
			</flux:column>
		</flux:columns>

		<flux:rows>

			@foreach($events as $event)
				<flux:row>
					<flux:cell>
						{{$event->description}}
					</flux:cell>
					<flux:cell class="!text-center">
						{{$event->date}}
					</flux:cell>
					<flux:cell class="!text-center">
						{{Number::currency($event->price/100, 'EUR', 'nl_NL')}}
					</flux:cell>
					<flux:cell class="!text-center">
						{{$event->available}}
					</flux:cell>
					<flux:cell class="!text-center">
						{{$event->completed}}
					</flux:cell>
					<flux:cell class="!text-center">
						@if($event->completed > 0)
							{{round(($event->completed/$event->available) * 100)}}%
						@endif
					</flux:cell>
					<flux:cell class="!text-center">
						@if($event->completed > 0)
							{{round(($event->scanned/$event->completed) * 100)}}%
						@endif
					</flux:cell>
					<flux:cell class="!text-center">
						<flux:badge color="{{$event->type === 'toegangskaart' ? 'green' : 'indigo'}}" size="sm" inset="top bottom">
							{{$event->type === 'toegangskaart' ? 'Entree' : 'Munten'}}
						</flux:badge>
					</flux:cell>
					<flux:cell class="!text-center">
						<flux:badge color="{{$event->published ? 'green' : 'red'}}" size="sm" inset="top bottom">
							{{$event->published ? 'Actief' : 'Inactief'}}
						</flux:badge>
					</flux:cell>
					<flux:cell class="float-right mr-4">
						<flux:dropdown>
							<flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>
							<flux:menu>
								<flux:menu.item wire:click="edit({{$event->id}})">Bewerken</flux:menu.item>
								@if(!$event->published)
									<flux:menu.item wire:click="activate({{$event->id}})">Activeren</flux:menu.item>
								@else
									<flux:menu.item wire:click="deactivationConfirmation({{$event->id}})" variant="danger">Deactiveren</flux:menu.item>
								@endif
							</flux:menu>
						</flux:dropdown>
					</flux:cell>
				</flux:row>
			@endforeach

		</flux:rows>

	</flux:table>

	<livewire:components.administrator.confirmation
			@confirmed="deactivate"
			content="Je staat op het punt om een ticket te deactiveren. Weet je zeker dat je dit wilt doen?"
	/>
</div>

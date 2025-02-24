<?php

use App\Traits\HasShoppingCartSession;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Event;

new #[Layout('layouts.frontend')] class extends Component {

	use HasShoppingCartSession;

	#[On('addedToCart')]
	public function addToCart($data): void
	{
		$isdUpdated = collect($this->getCartItems())->firstWhere('id', $data['id']);

		$this->addProduct($data['id'], $data['name'], $data['type'], $data['quantity'], $data['price']);

		$this->dispatch('updateShoppingCart');

		$message = $isdUpdated ? 'Tickets bijgewerkt in de winkelwagen!' : 'Tickets toegevoegd aan de winkelwagen!';

		if ($data['type'] === 'munten')
		{
			$message = $isdUpdated ? 'Munten bijgewerkt in de winkelwagen!' : 'Munten toegevoegd aan de winkelwagen!';
		}

		Flux::toast(
			text: $message,
			heading: 'Succes',
			variant: 'success',
		);
	}

	#[On('removedFromCart')]
	public function removeFromCart($eventId): void
	{
		$shoppingCart = collect($this->getCartItems())->firstWhere('id', $eventId);

		$this->removeProduct($eventId);

		Flux::toast(
			text: $shoppingCart['type'] === 'toegangskaart' ? 'Tickets verwijderd uit de winkelwagen!' : 'Munten verwijderd uit de winkelwagen!',
			heading: 'Succes',
			variant: 'success',
		);

		$this->dispatch('updateShoppingCart');
	}

	public function with(): array
	{
		$events = Event::query()
		               ->where('unpublish_at', '>', now())
		               ->orderBy('unpublish_at')
		               ->get();

		return [
			'events' => $events,
		];
	}
};

?>

<div>
	<livewire:components.frontend.title
			title="Tickets Bestellen"
			mobile="Tickets Bestellen"
			color="#0710db"
	/>

	<div class="grid lg:grid-cols-4 gap-3 xl:gap-x-6">
		@foreach($events as $event)
			<livewire:components.frontend.order-event :event="$event" :key="$event->id"/>
		@endforeach
		<livewire:components.frontend.shoppingcart/>
	</div>
</div>

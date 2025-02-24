<?php

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {

	use WithPagination;

	public int    $orderId = 0;
	public string $search  = '';
	public string $status  = '';

	protected $listeners = ['searchUpdated', 'filterUpdated'];

	public function searchUpdated($search): void
	{
		$this->resetPage();
		$this->search = $search;
	}

	public function filterUpdated($value): void
	{
		$this->resetPage();
		$this->status = $value;
	}

	public function edit($order): void
	{
		$this->redirect(route('administrator.orders.form', ['order' => $order]), navigate: true);
	}

	public function with(): array
	{
		$search = $this->search ?? '';
		$status = $this->status ?? '';

		$options = Order::query()
		                ->groupBy('status')
		                ->pluck('status');

		$orders = Order::query()
		               ->with('user')
		               ->withCount('events as tickets')
		               ->when($status, function ($q) use ($status) {
			               $q->whereStatus($status);
		               })
		               ->when($search, function ($q) use ($search) {
			               $q->where('payment_id', 'LIKE', '%' . $search . '%');
			               $q->orWhere('id', 'LIKE', '%' . $search . '%');
			               $q->orWhereRelation('user', 'name', 'LIKE', '%' . $search . '%');
			               $q->orWhereRelation('user', 'email', 'LIKE', '%' . $search . '%');
			               $q->orWhereHas('events', function ($q) use ($search) {
				               $q->where('barcode', 'like', '%' . $search . '%');
			               });
		               })
		               ->orderByDesc('id')
		               ->paginate(15);

		return [
			'orders'  => $orders,
			'options' => $options,
		];
	}
};
?>

<div>
	<livewire:components.administrator.header
			title="Bestellingen"
			subTitle="Overzicht van de bestellingen binnen het systeem."
			:showSearch="true"
			:$options
	/>

	<flux:table :paginate="$orders">
		<flux:columns>
			<flux:column class="!text-center w-20">
				Bestelling
			</flux:column>
			<flux:column>
				Gebruiker
			</flux:column>
			<flux:column>
				Email
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Tickets
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Bruto Bedrag
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Korting
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Netto Bedrag
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Betaal Status
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Email Status
				</div>
			</flux:column>
			<flux:column>

			</flux:column>
		</flux:columns>

		<flux:rows>

			@foreach($orders as $order)
				<flux:row>
					<flux:cell>
						<div class="w-full text-center">
							{{$order->id}}
						</div>
					</flux:cell>
					<flux:cell>
						{{$order->user->name}}
					</flux:cell>
					<flux:cell>
						{{$order->user->email}}
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							{{$order->tickets}}
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							{{Number::currency($order->amount/100, 'EUR')}}
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							@if($order->discount)
								{{Number::currency($order->discount/100, 'EUR')}}
							@endif
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							{{Number::currency($order->total/100, 'EUR')}}
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							@if($order->status === 'paid')
								<flux:badge color="green" size="sm" inset="top bottom">
									Betaald
								</flux:badge>
							@elseif($order->status === 'pending')
								<flux:badge color="yellow" size="sm" inset="top bottom">
									In Afwachting
								</flux:badge>
							@elseif($order->status === 'expired')
								<flux:badge color="red" size="sm" inset="top bottom">
									Verlopen
								</flux:badge>
							@elseif($order->status === 'canceled')
								<flux:badge color="red" size="sm" inset="top bottom">
									Geannuleerd
								</flux:badge>
							@else
								{{$order->status}}
							@endif
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							<flux:badge color="{{$order->emailDeliveryStatus() === 'Geopend' ? 'green' : 'red'}}" size="sm" inset="top bottom">
								{{$order->emailDeliveryStatus()}}
							</flux:badge>
						</div>
					</flux:cell>
					<flux:cell class="float-right mr-4">
						<flux:dropdown>
							<flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>
							<flux:menu>
								<flux:menu.item wire:click="edit({{$order->id}})">Bekijk Details</flux:menu.item>
								<flux:menu.item wire:click="showUserForm({{$order->id}})">Download Ticket</flux:menu.item>
								<flux:menu.separator/>
								<flux:menu.item wire:click="showUserForm({{$order->id}})">Markeer Als Betaald</flux:menu.item>
								<flux:menu.item wire:click="showUserForm({{$order->id}})">Opnieuw Verzenden</flux:menu.item>
								<flux:menu.separator/>
								<flux:menu.item wire:click="showDeleteUserConfirmation({{$order->id}})" variant="danger">Verwijderen</flux:menu.item>
							</flux:menu>
						</flux:dropdown>
					</flux:cell>
				</flux:row>
			@endforeach
		</flux:rows>

	</flux:table>
</div>

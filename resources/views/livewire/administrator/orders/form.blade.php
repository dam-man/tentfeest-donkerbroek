<?php

use App\Models\EventOrder;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.admin')] class extends Component {

	public Order $order;

	public bool $scanned = false;
	public int  $ticket;

	public function mount(Order $order): void
	{
		$this->order = $order;
		$this->order->load('user', 'events', 'delivery');
	}

	public function changeScanningStateConfirmation($ticket, $scanned): void
	{
		$this->ticket  = $ticket;
		$this->scanned = (bool) $scanned;

		$this->modal('confirm-scanning-state')->show();
	}

	public function changeScanningState(): void
	{
		try
		{
			$ticket = EventOrder::find($this->ticket);

			$ticket->update([
				'scanned' => ! $this->scanned,
			]);

			$this->modal('confirm-scanning-state')->close();

			Flux::toast(
				text: 'Scanstatus is aangepast.',
				heading: 'Succes',
				variant: 'success',
			);
		}
		catch (\Exception $e)
		{
			Flux::toast(
				text: $e->getMessage(),
				heading: 'Er is een fout opgetreden.',
				variant: 'danger',
			);
		}
	}
};

?>

<div>
	<flux:heading size="xl">Bestelling {{$order->id}}</flux:heading>
	<flux:subheading>Geplaatst door {{$order->user->name}} ({{$order->user->email}})</flux:subheading>

	<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
		<div>
			<flux:card class="space-y-6 h-full">
				<flux:legend>Details Bestelling</flux:legend>

				<flux:table>
					<flux:rows>
						<flux:row>
							<flux:cell>Datum Bestelling</flux:cell>
							<flux:cell class="float-right">{{\Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i')}}</flux:cell>
						</flux:row>

						<flux:row>
							<flux:cell>Gebruiker</flux:cell>
							<flux:cell class="float-right">{{$order->user->name}}</flux:cell>
						</flux:row>

						<flux:row>
							<flux:cell>Emailadres</flux:cell>
							<flux:cell class="float-right">{{$order->user->email}}</flux:cell>
						</flux:row>

						<flux:row>
							<flux:cell>Totaal Bedrag</flux:cell>
							<flux:cell class="float-right">{{Number::currency($order->total/100, 'EUR')}}</flux:cell>
						</flux:row>

						<flux:row>
							<flux:cell>Betaalstatus</flux:cell>
							<flux:cell class="float-right">{{$order->status}}</flux:cell>
						</flux:row>

						<flux:row>
							<flux:cell>Bijlage Verzonden</flux:cell>
							<flux:cell class="float-right mr-2">
								<flux:badge color="{{$order->delivery->has_attachment ? 'green' : 'red'}}" size="sm">
									{{$order->delivery->has_attachment ? $order->id . '.pdf' : 'Mislukt'}}
								</flux:badge>
							</flux:cell>
						</flux:row>

						<flux:row>
							<flux:cell>Verzendstatus</flux:cell>
							<flux:cell class="float-right mr-2">
								@if($this->order->delivery->opened)
									<flux:badge color="green" size="sm">Geopend</flux:badge>
								@elseif($this->order->delivery->delivered)
									<flux:badge color="orange" size="sm">Afgeleverd</flux:badge>
								@else
									<flux:badge color="red" size="sm">Niet Verzonden</flux:badge>
								@endif
							</flux:cell>
						</flux:row>
					</flux:rows>
				</flux:table>

			</flux:card>
		</div>
		<div>
			<flux:card class="space-y-6 h-full">
				<flux:fieldset>
					<flux:legend>Overzicht Bestelde Tickets</flux:legend>

					<div class="space-y-4">
						@foreach($order->events as $event)
							<flux:card
									wire:click="changeScanningStateConfirmation({{$event->pivot->id}}, {{$event->pivot->scanned}})"
									class="space-y-2 cursor-pointer"
							>
								<flux:button icon="qr-code" class="float-right  {{$event->pivot->scanned ? '!bg-green-700' : '!bg-red-700'}}"></flux:button>
								<span class="text-white text-sm">{{$event->pivot->quantity}} x {{$event->name}} - {{Carbon::parse($event->date)->format('d-m-Y')}}</span><br/>
								<span class="text-sm text-gray-300 -mt-4">Barcode: {{$event->pivot->barcode}}</span>
							</flux:card>
						@endforeach
					</div>
				</flux:fieldset>
			</flux:card>
		</div>

	</div>

	<flux:modal name="confirm-scanning-state" class="min-w-[22rem]">
		<form wire:submit="changeScanningState" class="space-y-6">
			<div>
				<flux:heading size="lg">Wijzig Status?</flux:heading>
				<flux:subheading>
					<p>Je gaat het ticket veranderen naar <span class="font-extrabold">{{!$scanned ? '"GESCAND"' : '"NIET GESCAND"'}}</span></p>
					<p>Klik op "Bevestigen" om verder te gaan of "Annuleren" om te stoppen.</p>
				</flux:subheading>
			</div>

			<div class="flex gap-2">
				<flux:spacer/>
				<flux:modal.close>
					<flux:button variant="ghost">Annuleren</flux:button>
				</flux:modal.close>
				<flux:button type="submit" class="!bg-green-600">Bevestigen</flux:button>
			</div>
		</form>
	</flux:modal>
</div>

<?php

use App\Traits\HasShoppingCartSession;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Models\Event;

new class extends Component {

	use HasShoppingCartSession;

	public Event $event;

	public null|int $quantity;

	public function mount(): void
	{
		$item = collect($this->getCartItems())->firstWhere('id', $this->event->id);

		$this->quantity = $item['quantity'] ?? null;
	}

	public function updatedQuantity(): void
	{
		$this->updateCart();
	}

	#[On('removedFromCart')]
	public function removedFromCart($id): void
	{
		if ($this->event->id === $id)
		{
			$this->quantity = null;
		}
	}

	public function updateCart(): true
	{
		if ($this->quantity)
		{
			$this->dispatch('addedToCart', [
				'id'       => $this->event->id,
				'name'     => $this->event->name,
				'type'     => $this->event->type,
				'quantity' => $this->quantity,
				'price'    => $this->event->price,
			]);

			return true;
		}

		$this->dispatch('removedFromCart', $this->event->id);

		return true;
	}

	public function with(): array
	{
		$type = $this->event->type ?? 'munten';

		$options = [
			'0' => '--Selecteer een aantal--',
			'1' => $type === 'munten' ? '10 munten' : '1 Toegangskaart',
			'2' => $type === 'munten' ? '20 munten' : '2 Toegangskaarten',
			'3' => $type === 'munten' ? '30 munten' : '3 Toegangskaarten',
			'4' => $type === 'munten' ? '40 munten' : '4 Toegangskaarten',
			'5' => $type === 'munten' ? '50 munten' : '5 Toegangskaarten',
			'6' => $type === 'munten' ? '60 munten' : '6 Toegangskaarten',
			'7' => $type === 'munten' ? '70 munten' : '7 Toegangskaarten',
			'8' => $type === 'munten' ? '80 munten' : '8 Toegangskaarten',
			'9' => $type === 'munten' ? '90 munten' : '9 Toegangskaarten',
		];

		return [
			'options' => $options,
		];
	}
};

?>

<div>
	<div class="rounded-lg h-full bg-blue">
		<div class="p-6 text-center">

			<h3 class="text-3xl mb-3">
				<div class="text-pink font-bold font-family-changa uppercase">
					{{ $event->name }}
				</div>
				<div class="uppercase text-white font-family-changa text-lg">
					<strong>{{ $event->type === 'munten' ? 'consumpties' : Carbon::parse($event->date)->format('d-m-Y') }}</strong>
				</div>
			</h3>

			<h3 class="text-3xl text-white mb-6 font-family-changa">
				<strong>{{ Number::currency($event->price/100, 'EUR') }}</strong>
				<small class="text-gray-300 text-sm"> /per {{ $event->type === 'munten' ? 10 : '' }} {{ $event->type }}</small>
			</h3>

			<form wire:submit.prevent="updateCart">

				<flux:select
						wire:model.live="quantity"
						clearable
						variant="listbox"
						placeholder="Gewenste Aantal {{ $event->type === 'munten' ? 'Munten' : 'Tickets' }}"
						class="mt-4"
				>
					@foreach($options as $key => $value)
						<flux:option value="{{ $key }}">{{ $value }}</flux:option>
					@endforeach
				</flux:select>

				<flux:button type="submit" variant="primary" class="!bg-pink w-full mt-4">Toevoegen</flux:button>

			</form>
			<div class="p-3 mt-5">
				<ol class="list-inside">

					@foreach(explode(';', $event->bullets) as $bullet)
						<li v-for="bullet in event.bullets" class="mb-4 flex items-center text-sm font-family-changa">
							<svg
									aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
									class="text-pink w-4 h-4 mr-2" role="img" xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 512 512"
							>
								<path
										fill="currentColor"
										d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"
								>
								</path>
							</svg>
							<span class="text-white">{{$bullet}}</span>
						</li>
					@endforeach

				</ol>
			</div>

		</div>
	</div>
</div>

<?php

use App\Models\Coupon;
use App\Service\MolliePaymentService;
use App\Traits\HasOrder;
use App\Traits\HasShoppingCartSession;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

	use HasOrder, HasShoppingCartSession;

	public string $page       = 'shoppingcart';
	public string $coupon     = '';
	public bool   $isLoggedIn = false;

	public function removeFromCart($id): void
	{
		$this->dispatch('removedFromCart', $id);
	}

	public function applyCoupon(): bool
	{
		if (empty($this->coupon))
		{
			Flux::toast(
				text: 'Je moet wel een kortingscode invullen.',
				heading: 'Ai..',
				variant: 'warning',
			);

			return false;
		}

		$coupon = Coupon::query()
		                ->whereDate('published_from', '<', now())
		                ->whereDate('published_to', '>', now())
		                ->where('code', $this->coupon)
		                ->first();

		if ( ! $coupon)
		{
			Flux::toast(
				text: 'Ongeldige kortingscode.',
				heading: 'Helaas',
				variant: 'danger',
			);

			return true;
		}

		$products = is_string($coupon->products) ? json_decode($coupon->products, true) : $coupon->products;

		// Unlimited Coupon
		if ($coupon->limit === 0)
		{
			$this->applyDiscount($coupon->id, $coupon->name, $coupon->type, $coupon->amount, $products);
		}
		elseif ($coupon->limit != 0 && ($coupon->usage < $coupon->limit))
		{
			$this->applyDiscount($coupon->id, $coupon->name, $coupon->type, $coupon->amount, $products);
		}
		else
		{
			Flux::toast(
				text: 'Deze kortingscode is niet (meer) geldig.',
				heading: 'Helaas',
				variant: 'danger',
			);

			return false;
		}

		$this->coupon = '';

		$this->dispatch('updateShoppingCart');

		Flux::toast(
			text: 'Kortingscode toegepast.',
			heading: 'Succes',
			variant: 'success',
		);

		return true;
	}

	public function startPayment()
	{
		if ( ! $this->hasCartItems() || ! auth()->check())
		{
			Flux::toast(
				text: 'Je bent niet ingelogd of er zitten geen producten in je winkelwagen.',
				heading: 'Er is iets mis gegaan.',
				variant: 'danger',
			);

			return false;
		}

		// Check if we could save the order
		if ($order = $this->storeOrderDetails())
		{
			// Check if a coupon is applied
			$couponId = $order->coupon_id;

			// Generate Tickets..

			if ($this->cartTotal(true) > 0)
			{
				// Instantiate payment provider.
				$payment = new MolliePaymentService();

				// Getting the payment URL for this order.
				$paymentUrl = $payment->getPaymentUrl($order);

				// Redirect to the payment URL.
				return redirect()->away($paymentUrl);
			}

			if ($couponId)
			{
				Coupon::whereId($couponId)->increment('usage');
			}

			$order->status = 'paid';
			$order->save();

			// Clearing cart contents
			$this->clearCart();

			Flux::toast(
				text: 'De tickets worden binnen enkele minuten naar je e-mailadres gestuurd.',
				heading: 'Yes!',
				variant: 'success',
			);

			return $this->redirect(route('order'), navigate: true);
		}
	}

	#[On('updateShoppingCart')]
	public function with(): array
	{
		if (Auth::check())
		{
			$this->isLoggedIn = true;
		}

		return [
			'items'       => $this->getCartItems(),
			'total'       => $this->cartTotal(),
			'discount'    => $this->getDiscount(),
			'hasDiscount' => $this->hasDiscount(),
		];
	}
};

?>

<div class="rounded-lg max-h-full bg-blue font-family-changa">
	<div class="p-6 text-center">
		<h3 class="text-3xl">
			<div class="text-pink font-bold  uppercase">
				{{$page == 'shoppingcart' ? 'Winkelwagen' : 'Betalen'}}
			</div>
			<div class="uppercase text-white  text-lg">
				<strong>{{$page == 'shoppingcart' ? 'Jouw bestelling' : 'Betaal  eenvoudig Met IDEAL'}}</strong>
			</div>
		</h3>
	</div>
	<div class="pl-6 pr-6">
		@if(!$items)
			<p class="text-white text-center mb-8 pb-8">
				Voeg tickets toe aan je bestelling, dan tonen we hier de totaal kosten! Ben je klaar met je bestelling dan kan je gemakkelijk
				betalen met IDEAL!
			</p>
		@endif
		@if(isset($items) && count($items) > 0)
			<flux:table>

				<flux:columns>
					<flux:column class="w-3/4">
						<span class="text-pink text-lg">Ticket</span>
					</flux:column>
					<flux:column>
						<div class="w-full text-center text-pink text-lg">
							Prijs
						</div>
					</flux:column>
				</flux:columns>

				<flux:rows>
					@foreach($items as $order)
						<flux:row>
							<flux:cell>
								@if($page === 'shoppingcart')
									<flux:icon.x-circle wire:click="removeFromCart({{$order['id']}})" variant="mini" class="float-right cursor-pointer"/>
								@endif
								@if($order['type'] == 'munten')
									{{$order['quantity'] * 10}} Munten
								@else
									{{$order['quantity'] .' x '}} {{$order['name']}}
								@endif
							</flux:cell>
							<flux:cell class="text-center">
								{{Number::currency($order['total']/100, 'EUR')}}
							</flux:cell>
						</flux:row>
					@endforeach
					@if($hasDiscount && $discount['total'] != 0)
						<flux:row>
							<flux:cell>
								{{$discount['name']}}
							</flux:cell>
							<flux:cell class="text-center">
								- {{Number::currency($discount['total']/100, 'EUR')}}
							</flux:cell>
						</flux:row>
					@endif
					<flux:row>
						<flux:cell>
							Totaal
						</flux:cell>
						<flux:cell class="text-center">
							{{$total}}
						</flux:cell>
					</flux:row>
				</flux:rows>
			</flux:table>

			<flux:input wire:model="coupon" placeholder="Voer kortingscode in.." class="mt-8"/>

			<flux:button wire:click="applyCoupon" variant="primary" class="!bg-pink w-full mt-4 uppercase">
				Korting Toepassen
			</flux:button>
			@if($page === 'shoppingcart')
				@if($isLoggedIn)
					<flux:button wire:click="startPayment" variant="primary" class="!bg-green-700 text-white w-full mt-8 mb-8 uppercase">
						IDEAL Betaling Voltooien
					</flux:button>
				@else
					<flux:button href="{{route('payment')}}" type="submit" variant="primary" class="!bg-green-700 uppercase text-white w-full mt-8 mb-8">
						Ik wil afrekenen
					</flux:button>
				@endif
			@else
				@if($isLoggedIn)
					<flux:button wire:click="startPayment" variant="primary" class="!bg-green-700 text-white w-full mt-8 mb-8 uppercase">
						IDEAL Betaling Voltooien
					</flux:button>
				@else
					<flux:button disabled variant="primary" class="!bg-green-700 text-white w-full mt-8 mb-8 uppercase">
						IDEAL Betaling Voltooien
					</flux:button>
				@endif
			@endif

		@endif
	</div>
</div>

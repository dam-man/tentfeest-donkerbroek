<?php

use App\Models\Coupon;
use Carbon\Carbon;
use Livewire\Volt\Component;

new class extends Component {

	public function with(): array
	{
		$discount = null;

		$coupon = Coupon::query()
		                ->where('auto_apply', true)
		                ->whereDate('published_from', '<=', now())
		                ->whereDate('published_to', '>=', now())
		                ->first();

		if ($coupon && $coupon->type === 'amount')
		{
			$discount = Number::currency($coupon->amount / 100, 'EUR');
		}

		if ($coupon && $coupon->type === 'percent')
		{
			$discount = $coupon->amount . '%';
		}

		return [
			'discount' => $discount,
			'coupon'   => $coupon,
			'friday'   => Carbon::create(now()->year, 7)->firstOfMonth(Carbon::FRIDAY)->format('d'),
			'saturday' => Carbon::create(now()->year, 7)->firstOfMonth(Carbon::SATURDAY)->format('d'),
		];
	}

}; ?>

<div>
	<div class="grid lg:grid-cols-2 pt-20 gap-4">

		<div class="flex flex-col gap-y-5">
			<div class="text-6xl ml-3 lg:text-8xl uppercase text-center md:text-left text-gray-50 font-bold tracking-wide font-family-changa">
				{{$friday}} & {{$saturday}} JULI
			</div>
			<div class="-mt-6 text-5xl md:text-5xl text-center md:text-left uppercase text-gray-50 font-bold tracking-wider font-family-changa">
				<span class="ml-3">TENTFEEST</span>
			</div>
			<div class="-mt-6 text-5xl md:text-5xl text-center md:text-left uppercase text-gray-50 font-bold tracking-wider font-family-changa">
				<span class="ml-3">DONKERBROEK</span>
			</div>
		</div>

		<div class="flex flex-col gap-y-5">
			<!-- image or video -->
		</div>

	</div>
	<div>
		<livewire:components.frontend.countdown :$discount/>
	</div>
</div>

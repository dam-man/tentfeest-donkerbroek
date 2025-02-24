<?php

use Carbon\Carbon;
use Livewire\Volt\Component;
use App\Models\Event;

new class extends Component {

	public string|null $discount;

	public function navigate(): void
	{
		$this->redirect(route('order'));
	}

	public function with(): array
	{
		$days    = 0;
		$hours   = 0;
		$minutes = 0;

		$upcoming = Event::query()
		                 ->whereDate('date', '>=', now())
		                 ->where('published', true)
		                 ->where('type', 'toegangskaart')
		                 ->first();

		if ($upcoming)
		{
			$targetDate = strtotime(Carbon::create($upcoming->date)->setTime(21, 0)->format('Y-m-d H:i:s'));
			$difference = $targetDate - strtotime(now());

			$days    = floor($difference / (60 * 60 * 24));
			$hours   = floor(($difference % (60 * 60 * 24)) / (60 * 60));
			$minutes = floor(($difference % (60 * 60)) / 60);
		}

		return [
			'days'     => $days,
			'hours'    => $hours,
			'minutes'  => $minutes,
			'upcoming' => $upcoming,
		];
	}
};

?>

<div>

	<div wire:poll.20000ms.visible class="w-full mt-40">

		<div class="p-4 flex bg-blue rounded-md float-left w-full">
			<div class="w-1/3 md:w-1/8 bg-yellow-300 mx-2 p-4 rounded-md text-center">
				<span class="text-3xl text-pink text-center font-family-changa">{{$days}}</span><br/>
				<span class="text-lg text-pink font-family-changa">DAGEN</span>
			</div>
			<div class="w-1/3 md:w-1/8 bg-yellow-300 mx-2 p-4 rounded-md text-center">
				<span class="text-3xl text-pink text-center font-family-changa">{{$hours}}</span><br/>
				<span class="text-lg text-pink font-family-changa">UREN</span>
			</div>
			<div class="w-1/3 md:w-1/8 bg-yellow-300 mx-2 p-4 rounded-md text-center">
				<span class="text-3xl text-pink text-center font-family-changa">{{$minutes}}</span><br/>
				<span class="text-lg text-pink font-family-changa">MINUTEN</span>
			</div>

			<div class="w-full hidden md:block justify-items-end">
				@if($upcoming)
					<div wire:click="navigate" class="pt-4 pb-4 pr-8 pl-8 cursor-pointer rounded bg-yellow-300 font-family-changa text-3xl text-pink font-semibold">
						<a
								wire:navigate href="{{route('order')}}"
								type="button"
								class="flex rounded bg-yellow-300 font-family-changa text-3xl text-pink font-semibold"
						>
							<span class="italic">KOOP NU JE KAARTEN!</span>
						</a>
						<div class="text-lg text-blue w-full text-center">
							@if($discount)
								Koop je tickets nu met {{$discount}} korting!
							@else
								Ga snel naar onze shop voor tickets!
							@endif
						</div>
					</div>
				@else
					<div class="pt-4 pb-4 pr-8 pl-8 rounded bg-yellow-300 font-family-changa text-3xl text-pink font-semibold">
						<div>KAART VERKOOP START BIJNA!</div>
						<div class="text-lg text-blue w-full text-center">Houdt de site goed in de gaten!</div>
					</div>
				@endif
			</div>


		</div>

	</div>

</div>
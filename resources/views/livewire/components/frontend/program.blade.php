<?php

use Carbon\Carbon;
use Livewire\Volt\Component;

new class extends Component {

	public function with(): array
	{
		$events = \App\Models\Event::query()
		                           ->where('type', 'toegangskaart')
		                           ->where('published', true)
		                           ->orderBy('date')
		                           ->get();

		return [
			'events' => $events,
		];
	}

}; ?>

<div>

	<livewire:components.frontend.title
			title="Line-up Tentfeest 2025"
			mobile="Onze Line-up!"
	/>

	<div class="text-center font-family-changa text-white">
		Ook voor deze editie hebben we weer ons best gedaan om een mooie line-up voor jullie neer te zetten!
		Hieronder zie je de artiesten die we dit jaar hebben vast gelegd. Uiteraard hebben we ook voor een goede deejay gezorgd, deze zal tijdens de pauzes van bands het feest voortzetten!
	</div>

	<div class="flex flex-wrap mt-1 mb-10 md:mb-0 md:mt-8">

		@foreach($events as $event)
			<div class="w-full md:w-1/2 p-1">

				<div class="relative flex justify-center items-center h-full p-3 md:p-3">
					<div class="relative">
						<img
								src="{{Storage::url('public/tickets/'.$event->image)}}"
								class="w-full rounded-t-xl rounded-2xl {{$loop->iteration % 2 == 0 ? 'mt-6' : ''}}"
								alt="{{$event->description}} op tentfeest Donkerbroek"
						/>
						<div class="absolute -bottom-2 {{$loop->iteration % 2 == 0 ? '-left-2' : '-right-2'}} text-center bg-blue w-24 h-24 rounded-xl">
							<div class="pt-3 text-4xl text-white font-semibold font-family-changa">
								{{Carbon::parse($event->date)->format('d')}}
							</div>
							<div class="text-4xl -mt-2 text-white font-semibold font-family-changa">
								JULI
							</div>
						</div>
						<div class="absolute top-0 text-center transform origin-center text-3xl font-semibold font-family-changa text-white bg-pink p-4 w-full rounded-t-xl">
							{{strtoupper($event->description)}}
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>

	<div class="hidden md:block text-center text-4xl text-white font-semibold font-family-changa mb-10">
		21:00 UUR
	</div>

</div>

<?php

use App\Models\Article;
use Livewire\Volt\Component;

new class extends Component {

	public function with(): array
	{
		$articles = Article::query()
		                   ->select(['id', 'title', 'slug', 'image', 'publish_at'])
		                   ->whereDate('publish_at', '<', now())
		                   ->orderByDesc('id')
		                   ->take(3)
		                   ->get();

		return [
            'articles' => $articles,
        ];
	}
};

?>

<div>

    <livewire:components.frontend.title
            title="Laatste Feest Nieuws!"
            mobile="Laatste Nieuws!"
    />

	<div class="pt-4 text-white text-center font-family-changa">
		Wil jij altijd op de hoogte blijven van het laatste nieuws? Kom dan met enige regelmaat terug naar de
		website van HD Tentfeest Donkerbroek! Als er nieuws te melden is, dan zullen wij dit hier vermelden! Natuurlijk kan je ook onze socials in de gaten houden!
	</div>

	<div class="grid lg:grid-cols-3 pt-14 gap-5">
		@foreach($articles as $article)
			<div class="flex flex-col gap-y-5 border rounded-xl overflow-hidden" style="border-color: #e6007e">

				<div class="absolute mt-4 ml-5 text-center bg-pink w-16 h-16 rounded-xl">
					<div class="pt-1 text-2xl text-white font-semibold font-family-changa">
						{{\Carbon\Carbon::parse($article->publish_at)->format('d')}}
					</div>
					<div class="text-md -mt-2 text-white font-semibold font-family-changa">
						{{\Carbon\Carbon::parse($article->publish_at)->format('M')}}
					</div>
				</div>

				<img src="{{Storage::url('articles/' . $article->image)}}" style="height: 225px;" alt="{{$article->title}}">
				<div class="pl-5 pr-5 pb-5">
					<div class="text-sm text-gray-200 pb-3 font-family-changa">Geschreven op {{\Carbon\Carbon::parse($article->publish_at)->format('d-m-Y')}}</div>
					<h3 class="text-2xl text-white font-family-changa">{{$article->title}}</h3>
				</div>
			</div>
		@endforeach
	</div>
</div>

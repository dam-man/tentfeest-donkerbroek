<?php

use App\Models\Article;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {

	use WithPagination;

	public string $search = '';
	public int    $articleId;

	protected $listeners = ['searchUpdated'];

	public function searchUpdated($search): void
	{
		$this->resetPage();
		$this->search = $search;
	}

	public function addArticle(): void
	{
		$this->redirect(route('administrator.news.add'), navigate: true);
	}

	public function editArticle($id): void
	{
		$this->redirect(route('administrator.news.edit', ['article' => $id]), navigate: true);
	}

	public function showDeleteConfirmation($id): void
	{
		$this->articleId = $id;

		Flux::modal('confirmation')->show();
	}

	public function activate($id): void
	{
		$article = Article::whereId($id)->withTrashed()->first();
		$article->restore();

		Flux::toast(
			text: 'Nieuwsbericht is geactiveerd.',
			heading: 'Succes',
			variant: 'success',
		);
	}

	public function destroy(): void
	{
		try
		{
			Article::find($this->articleId)->delete();

			Flux::toast(
				text: 'Nieuwsbericht is verwijderd.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->modal('article-remove-confirmation')->close();
		}
		catch (\Exception $e)
		{
			dd($e->getMessage());
		}
	}

	public function with(): array
	{
		$search = $this->search ?? '';

		$items = Article::query()
		                ->when($search, function ($q) use ($search) {
			                $q->where('title', 'LIKE', '%' . $search . '%');
			                $q->orWhere('text', 'LIKE', '%' . $search . '%');
		                })
		                ->orderByDesc('id')
		                ->withTrashed()
		                ->paginate(15);

		return [
			'items' => $items,
		];
	}
};

?>

<div>
	<livewire:components.administrator.header
			title="Nieuwsberichten"
			subTitle="Overzicht van alle nieuwsberichten."
			:showSearch="true"
			:showAddButton="true"
			@add="addArticle"
	/>

	<flux:table :paginate="$items">
		<flux:columns>
			<flux:column>
				Titel
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Startdatum
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Einddatum
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Aanmaakdatum
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Status
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center"></div>
			</flux:column>
		</flux:columns>

		<flux:rows>

			@foreach($items as $item)
				<flux:row>
					<flux:cell>
						{{$item->title}}
					</flux:cell>
					<flux:cell class="!text-center">
						{{Carbon::parse($item->publish_at)->format('d-m-Y')}}
					</flux:cell>
					<flux:cell class="!text-center">
						{{Carbon::parse($item->unpublish_at)->format('d-m-Y')}}
					</flux:cell>
					<flux:cell class="!text-center">
						{{Carbon::parse($item->created_at)->format('d-m-Y H:i')}}
					</flux:cell>
					<flux:cell class="!text-center">
						<flux:badge color="{{$item->deleted_at ? 'red' : 'green'}}" size="sm" inset="top bottom">
							{{$item->deleted_at ? 'Inactief' : 'Actief'}}
						</flux:badge>
					</flux:cell>
					<flux:cell class="float-right mr-4">
						<flux:dropdown>
							<flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>
							<flux:menu>
								<flux:menu.item wire:click="editArticle({{$item->id}})">Bewerken</flux:menu.item>
								@if(!$item->deleted_at)
									<flux:menu.item wire:click="showDeleteConfirmation({{$item->id}})" variant="danger">Deactiveren</flux:menu.item>
								@else
									<flux:menu.item wire:click="activate({{$item->id}})" variant="danger">Activeren</flux:menu.item>
								@endif
							</flux:menu>
						</flux:dropdown>
					</flux:cell>
				</flux:row>
			@endforeach

		</flux:rows>
	</flux:table>

	<livewire:components.administrator.confirmation
			@confirmed="destroy"
			content="Je staat op het punt om een artikel te deactiveren. Weet je zeker dat je dit wilt doen?"
	/>
</div>

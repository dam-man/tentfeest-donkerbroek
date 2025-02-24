<?php

use App\Livewire\Forms\ArticleForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.admin')] class extends Component {

	use WithFileUploads;

	public ArticleForm $form;

	public int $articleId;

	public function mount($article = 0)
	{
		if ($article)
		{
			$this->articleId = $article;
			$this->form->setForm($article);
		}
	}

	public function cancel(): void
	{
		$this->redirect(route('administrator.news.index'), navigate: true);
	}

	public function store(): void
	{
		if ($this->form->store())
		{
			Flux::toast(
				text: 'Nieuwsbericht is toegevoegd.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.news.index'), navigate: true);
		}
	}

	public function update(): void
	{
		if ($this->form->update())
		{
			Flux::toast(
				text: 'Nieuwsbericht is bijgewerkt.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.news.index'), navigate: true);
		}
	}

};

?>

<div>

	<div class="mb-8">
		<flux:heading size="xl">{{$articleId ? 'Bewerk' : 'Toevoegen'}}</flux:heading>
		<flux:subheading>Voeg een nieuwsbericht toe aan het blog.</flux:subheading>
	</div>

	<form wire:submit="{{$articleId ? 'update' : 'store'}}" enctype="multipart/form-data">


			<div class="space-y-4 w-1/2">
				<flux:card class="space-y-4 h-full">
				<flux:input wire:model="form.title" label="Titel"/>

				<flux:input wire:model="form.slug" label="Slug"/>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
					<div>
						<flux:input type="date" wire:model="form.publish_at" label="Startdatum"/>
					</div>
					<div>
						<flux:input type="date" wire:model="form.unpublish_at" label="Einddatum"/>
					</div>
				</div>

				<flux:editor wire:model="form.text" label="Schrijf je nieuwsbericht hieronder..."/>

				<flux:input type="file" wire:model="form.image" label="Afbeelding"/>

				<div class="flex mt-8">
					<flux:spacer/>
					<flux:button wire:click="cancel" variant="danger" class="mr-2">
						Annuleren
					</flux:button>
					<flux:button type="submit" class="!bg-green-600">
						Opslaan
					</flux:button>
				</div>
				</flux:card>
			</div>


	</form>

</div>

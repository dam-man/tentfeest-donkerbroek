<?php

use Livewire\Volt\Component;

new class extends Component {

	public string $content;

	public function confirm(): void
	{
		$this->dispatch('confirmed');

		$this->modal('confirmation')->close();
	}
};
?>

<div>
	<flux:modal name="confirmation" class="min-w-[22rem] max-w-[26rem]">
		<form wire:submit="confirm" class="space-y-6">
			<div>
				<flux:heading size="lg">Bevestiging Vereist</flux:heading>
				<flux:subheading>
					<p>
						{{$content}}
					</p>
				</flux:subheading>
			</div>

			<div class="flex gap-2">
				<flux:spacer/>
				<flux:modal.close>
					<flux:button variant="ghost">Cancel</flux:button>
				</flux:modal.close>
				<flux:button type="submit" class="!bg-green-600">Bevestigen</flux:button>
			</div>
		</form>
	</flux:modal>
</div>

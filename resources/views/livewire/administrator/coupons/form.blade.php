<?php

use App\Livewire\Forms\CouponForm;
use Carbon\Carbon;
use Livewire\Attributes\Reactive;
use Livewire\Volt\Component;

new class extends Component {

	#[Reactive]
	public int $couponId;

	public CouponForm $form;

	public function mount($couponId = 0): void
	{
		$this->couponId = $couponId;
		$this->form->setForm($couponId);
	}

	public function store(): void
	{
		if ($this->form->store())
		{
			Flux::toast(
				text: 'Coupon is toegevoegd.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.coupons.index'), navigate: true);
		}
	}

	public function update(): void
	{
		if ($this->form->update())
		{
			Flux::toast(
				text: 'Coupon is bijgewerkt.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->redirect(route('administrator.coupons.index'), navigate: true);
		}
	}

	public function with()
	{
		return [
			'products' => \App\Models\Event::whereType('toegangskaart')->get(),
		];
	}
}; ?>

<div>
	<div class="mb-8">
		<flux:heading size="lg">{{$couponId ? 'Bewerk Coupon' : 'Coupon Toevoegen'}}</flux:heading>
	</div>

	<form wire:submit="{{$couponId ? 'update' : 'store'}}">

		<div class="space-y-3">
			<flux:input wire:model="form.name" label="Naam" placeholder="Naam"/>
			<flux:input wire:model="form.code" label="Code" placeholder="Code"/>

			<flux:field variant="inline" class="w-full flex justify-between. mt-2 mb-2">
				<flux:switch wire:model.live="form.auto_apply" />
				<flux:label>Automatisch toepassen</flux:label>
				<flux:error name="form.auto_apply" />
			</flux:field>

			<flux:select wire:model.live="form.products" variant="listbox" multiple label="Geldig voor tickets." placeholder="Geldig voor tickets">
				@foreach($products as $product)
					<flux:option value="{{$product->id}}">[{{Carbon::parse($product->date)->format('d-m-Y')}}] {{$product->name}}</flux:option>
				@endforeach
			</flux:select>

			<div class=mb-4">
				<flux:select wire:model.live="form.type" variant="listbox" label="Coupon Type & Bedrag/Percentage" placeholder="Coupon Type">
					<flux:option value="amount">Bedrag</flux:option>
					<flux:option value="percent">Percentage</flux:option>
				</flux:select>
			</div>

			<div class=pt-4">
				<flux:input.group>
					<flux:input.group.prefix>
						{{$form->type === 'percent' ? '%' : '€'  }}
					</flux:input.group.prefix>
					<flux:input wire:model="form.amount" placeholder="Amount"/>
				</flux:input.group>
			</div>

			<flux:input
					wire:model="form.limit" label="Limiet op gebruik" placeholder="Gelimiteerde Coupon"
					description="Maximum aantal gebruik. (0 voor oneindig)"
			/>

			<flux:input wire:model="form.published_from" type="date" max="2999-12-31" label="Geldig vanaf datum"/>

			<flux:input
					wire:model="form.published_to" type="date" max="2999-12-31" label="Geldig tot datum"
			/>

			<div class="flex mt-8">
				<flux:spacer/>
				<flux:button type="submit" class="!bg-green-600">
					Opslaan
				</flux:button>
			</div>
		</div>

	</form>

</div>

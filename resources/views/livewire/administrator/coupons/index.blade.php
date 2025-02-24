<?php

use App\Models\Coupon;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {

	use WithPagination;

	public int    $couponId = 0;
	public string $search   = '';

	protected $listeners = ['searchUpdated'];

	public function searchUpdated($search): void
	{
		$this->resetPage();
		$this->search = $search;
	}

	public function edit($id): void
	{
		$this->couponId = $id;

		$this->modal('coupon-form')->show();
	}

	public function addCoupon(): void
	{
		$this->couponId = 0;

		$this->modal('coupon-form')->show();
	}

	public function showDeleteConfirmation($id): void
	{
		$this->couponId = $id;

		Flux::modal('confirmation')->show();
	}

	public function destroy(): void
	{
		try
		{
			Coupon::find($this->couponId)->delete();

			Flux::toast(
				text: 'Coupon is verwijderd.',
				heading: 'Succes',
				variant: 'success',
			);

			$this->modal('coupon-remove-confirmation')->close();
		}
		catch (\Exception $e)
		{
			dd($e->getMessage());
		}
	}

	public function with(): array
	{
		$search = $this->search ?? '';

		$coupons = Coupon::query()
		                 ->when($search, function ($q) use ($search) {
			                 $q->where('code', 'LIKE', '%' . $search . '%');
		                 })
		                 ->orderByDesc('id')
		                 ->paginate(15);

		return [
			'coupons' => $coupons,
		];
	}
}
?>

<div>
	<livewire:components.administrator.header
			title="Coupons"
			subTitle="Overzicht van alle kortingscodes."
			:showSearch="true"
			:showAddButton="true"
			@add="addCoupon"
	/>

	<flux:table :paginate="$coupons">
		<flux:columns>
			<flux:column>Naam</flux:column>
			<flux:column>Code</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Amount
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Type
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Limiet
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Gebruik
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Toepassing
				</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">
					Specifiek
				</div>
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
			<flux:column></flux:column>
		</flux:columns>

		<flux:rows>
			@foreach($coupons as $coupon)
				<flux:row>
					<flux:cell>{{$coupon->name}}</flux:cell>
					<flux:cell>{{$coupon->code}}</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							@if($coupon->type == 'amount')
							{{Number::currency($coupon->amount/100, 'EUR')}}
							@else
							{{$coupon->amount}}%
							@endif
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							<flux:badge color="{{$coupon->type == 'amount' ? 'indigo' : 'teal'}}" size="sm" inset="top bottom">
								{{$coupon->type == 'amount' ? 'Bedrag' : 'Percentage'}}
							</flux:badge>
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							{{$coupon->limit}}
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							{{$coupon->usage}}
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							@if($coupon->auto_apply)
								<flux:badge color="green" size="sm" inset="top bottom">
									Automatisch
								</flux:badge>
							@endif
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							@if($coupon->products)
								<flux:badge color="green" size="sm" inset="top bottom">
									{{count(json_decode($coupon->products, true))}} Tickets
								</flux:badge>
							@else
								<flux:badge color="blue" size="sm" inset="top bottom">
									Tickets
								</flux:badge>
							@endif
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							{{Carbon::parse($coupon->published_from)->format('d-m-Y')}}
						</div>
					</flux:cell>
					<flux:cell>
						<div class="w-full text-center">
							{{Carbon::parse($coupon->published_to)->format('d-m-Y')}}
						</div>
					</flux:cell>
					<flux:cell class="float-right mr-4">
						<flux:dropdown>
							<flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>
							<flux:menu>
								<flux:menu.item wire:click="edit({{$coupon->id}})">Bewerken</flux:menu.item>
								<flux:menu.separator/>
								<flux:menu.item wire:click="showDeleteConfirmation({{$coupon->id}})" variant="danger">Verwijderen</flux:menu.item>
							</flux:menu>
						</flux:dropdown>
					</flux:cell>
				</flux:row>
			@endforeach
		</flux:rows>
	</flux:table>

	<livewire:components.administrator.confirmation
			@confirmed="destroy"
			content="Je staat op het punt om een ticket te deactiveren. Weet je zeker dat je dit wilt doen?"
	/>

	<flux:modal name="coupon-form" variant="flyout" class="space-y-6">
		<livewire:administrator.coupons.form :couponId="$couponId" :key="$couponId"/>
	</flux:modal>

</div>

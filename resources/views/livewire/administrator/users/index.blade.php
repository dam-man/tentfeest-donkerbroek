<?php

use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {

	use WithPagination;

	public int    $userId = 0;
	public string $search = '';
	public string $key;

	protected $listeners = ['searchUpdated'];

	public function searchUpdated($search): void
	{
		$this->resetPage();
		$this->search = $search;
	}

	public function showUserForm($id): void
	{
		$this->userId = $id;

		$this->modal('user-form')->show();
	}

	public function showDeleteUserConfirmation($id): void
	{
		$this->userId = $id;

		Flux::modal('confirmation')->show();
	}

	public function activate($id): void
	{
		try
		{
			$user = User::withTrashed()->where('id', $id)->first();
			$user->restore();

			Flux::toast(
				text: 'Gebruiker weer geactiveerd.',
				heading: 'Succes',
				variant: 'success',
			);
		}
		catch (Exception $e)
		{
			Flux::toast(
				text: $e->getMessage(),
				heading: 'Er is een fout opgetreden.',
				variant: 'danger',
			);
		}
	}

	public function delete(): void
	{
		try
		{
			$user = User::findOrFail($this->userId);
			$user->delete();

			Flux::toast(
				text: 'Gebruiker verwijderd.',
				heading: 'Succes',
				variant: 'success',
			);
		}
		catch (Exception $e)
		{
			Flux::toast(
				text: $e->getMessage(),
				heading: 'Er is een fout opgetreden.',
				variant: 'danger',
			);
		}

		$this->modal('delete-user')->close();
	}

	public function with(): array
	{
		$search = $this->search ?? '';

		$users = User::query()
		             ->when($search, function ($q) use ($search) {
			             $q->where(function ($query) use ($search) {
				             $query->where('name', 'LIKE', '%' . $search . '%')
				                   ->orWhere('email', 'LIKE', '%' . $search . '%');
			             });
		             })
		             ->withTrashed()
		             ->paginate(15);

		return [
			'users' => $users,
		];
	}
}; ?>

<div>
	<livewire:components.administrator.header
			title="Gebruikers"
			subTitle="Overzicht van de gebruikers binnen het systeem."
			:showSearch="true"
			:showAddButton="true"
			@add="showUserForm(0)"
	/>

	<flux:table :paginate="$users">
		<flux:columns>
			<flux:column>
				Naam
			</flux:column>
			<flux:column>
				Email
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Account Type</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Registratie Datum</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Laatste Login</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center">Status</div>
			</flux:column>
			<flux:column>
				<div class="w-full text-center"></div>
			</flux:column>
		</flux:columns>

		<flux:rows>

			@foreach($users as $user)
				<flux:row>
					<flux:cell>
						{{$user->name}}
					</flux:cell>
					<flux:cell>
						{{$user->email}}
					</flux:cell>
					<flux:cell class="!text-center">
						<flux:badge color="{{$user->role === 'admin' ? 'green' : 'indigo'}}" size="sm" inset="top bottom">
							{{$user->role === 'admin' ? 'Administrator' : 'Gebruiker'}}
						</flux:badge>
					</flux:cell>
					<flux:cell variant="strong" class="!text-center">
						{{Carbon::parse($user->created_at)->format('d-m-Y H:i')}}
					</flux:cell>
					<flux:cell variant="strong" class="!text-center">
						@if($user->last_login)
							{{Carbon::parse($user->last_login)->format('d-m-Y H:i')}}
						@endif
					</flux:cell>
					<flux:cell class="!text-center">
						<flux:badge color="{{$user->deleted_at ? 'red' : 'green'}}" size="sm" inset="top bottom">
							{{$user->deleted_at ? 'Inactief' : 'Actief'}}
						</flux:badge>
					</flux:cell>
					<flux:cell class="float-right mr-4">
						<flux:dropdown>
							<flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom"/>
							<flux:menu>
								<flux:menu.item wire:click="showUserForm({{$user->id}})">Bewerken</flux:menu.item>
								@if($user->deleted_at)
									<flux:menu.item wire:click="activate({{$user->id}})">Activeren</flux:menu.item>
								@else
									<flux:menu.item wire:click="showDeleteUserConfirmation({{$user->id}})" variant="danger">Deactiveren</flux:menu.item>
								@endif
							</flux:menu>
						</flux:dropdown>
					</flux:cell>
				</flux:row>
			@endforeach

		</flux:rows>
	</flux:table>

	<flux:modal name="user-form" variant="flyout" class="space-y-6">
		<livewire:administrator.users.form :userId="$userId" :key="$userId"/>
	</flux:modal>

	<livewire:components.administrator.confirmation
			@confirmed="delete"
			content="Je staat op het punt om een gebruiker te verwijderen.. Weet je zeker dat je dit wilt doen?"
	/>
</div>

<?php

use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.frontend')] class extends Component {

	public string $token;

	public function paymentStatus($paymentStatus)
	{
		return match ($paymentStatus)
		{
			'paid' => 'Voltooid',
			'open' => 'Nog Open',
			'pending' => 'In Afwachting',
			'expired' => 'Verlopen',
			'canceled' => 'Geannuleerd',
			default => 'Onbekend',
		};
	}

	public function with(): array
	{
		$payment = Payment::query()->whereToken($this->token)->first();

		if($payment) {
			$status = $this->paymentStatus($payment->status);
		} else {
			$status = 'Niet Gevonden';
		}

		return [
			'status' => $status,
		];
	}
};

?>
<div>
	<livewire:components.frontend.title
			title="Betaling {{$status}}"
			mobile="Afrekenen"
			color="#0710db"
			align="left"
	/>


</div>

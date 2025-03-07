<?php

namespace App\Service;

use App\Models\Payment;
use Illuminate\Support\Str;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Laravel\Facades\Mollie;

class MolliePaymentService
{
	private string $token;
	public object  $order;

	public function __construct()
	{
		$this->token = Str::random(10);
	}

	/**
	 * @throws ApiException
	 */
	public function getTransaction($id): \Mollie\Api\Resources\Payment
	{
		return Mollie::api()->payments->get($id);
	}

	/**
	 * @throws ApiException
	 */
	public function getPaymentUrl($order): ?string
	{
		$this->order = $order;

		$payment = Mollie::api()->payments->create([
			'amount'      => [
				'currency' => 'EUR',
				'value'    => $this->getPaymentAmount(),
			],
			'description' => 'HD Tentfeest - Bestelling #' . $this->order->id,
			'redirectUrl' => route('payment.completed', ['token' => $this->token]),
			'webhookUrl'  => $this->getWebhookUrl(),
			'metadata'    => [
				'order_id' => $this->order->id,
			],
		]);

		$this->storePayment($payment);

		return $payment->getCheckoutUrl();
	}

	private function getPaymentAmount(): string
	{
		return number_format($this->order->total / 100, 2, '.', '');
	}

	private function storePayment($payment): void
	{
		$this->order->payment_id = $payment->id;
		$this->order->save();

		Payment::create([
			'token'      => $this->token,
			'payment_id' => $payment->id,
			'order_id'   => $this->order->id,
			'amount'     => $this->order->total,
			'status'     => $payment->status,
			'created_at' => now(),
		]);
	}

	private function getWebhookUrl(): string
	{
		// Testing webhook requires valid URL
		$webhook = 'https://google.com';

		// Live webhook will be our own site.
		if (config('app.env') == 'production' || config('app.env') == 'staging')
		{
			$webhook = route('mollie.webhook');
		}

		return $webhook;
	}
}
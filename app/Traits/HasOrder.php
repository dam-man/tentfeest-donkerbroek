<?php

namespace App\Traits;

use App\Models\EventOrder;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait HasOrder
{
	use HasShoppingCartSession;

	/**
	 * @throws NotFoundExceptionInterface
	 * @throws ContainerExceptionInterface
	 */
	public function storeOrderDetails()
	{
		$items = $this->getCartItems();

		if ($this->hasDiscount())
		{
			$discount = $this->getDiscount();
		}

		$order = Order::create([
			'user_id'   => Auth::id(),
			'coupon_id' => $discount['id'] ?? null,
			'amount'    => $this->getCartTotalWithoutDiscount(true),
			'discount'  => $discount['total'] ?? null,
			'total'     => $this->cartTotal(true),
		]);

		foreach ($items as $item)
		{
			$this->addTickets($order, $item['id'], $item['quantity']);
		}

		return $order;
	}

	private function addTickets($order, $eventId, $quantity): bool
	{
		// Consumptions
		if ($eventId === 3)
		{
			$order->events()->attach($eventId, [
				'user_id'  => Auth::id(),
				'event_id' => $eventId,
				'quantity' => $quantity,
				'barcode'  => date('ymd') . '-' . Auth::id() . $this->generateBarcode(),
				'scanned'  => false,
			]);

			return true;
		}

		for ($amt = 0; $amt < $quantity; $amt++)
		{
			$order->events()->attach($eventId, [
				'user_id'  => Auth::id(),
				'event_id' => $eventId,
				'quantity' => 1,
				'barcode'  => date('ymd') . '-' . Auth::id() . $this->generateBarcode(),
				'scanned'  => false,
			]);
		}

		return true;
	}

	public function generateBarcode($length = 6): string
	{
		$characters       = '123456789';
		$charactersLength = strlen($characters);

		do
		{
			$uuid = '';
			for ($i = 0; $i < $length; $i++)
			{
				$uuid .= $characters[rand(0, $charactersLength - 1)];
			}
		} while (EventOrder::where('barcode', $uuid)->exists());

		return $uuid;
	}
}

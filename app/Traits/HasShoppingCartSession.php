<?php

namespace App\Traits;

use Illuminate\Support\Number;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Str;

trait HasShoppingCartSession
{
	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function addProduct($id, $name, $type, $quantity = 1, $price = 0): true
	{
		if ( ! session()->has('cart'))
		{
			session()->put('cart.products', []);
			session()->put('cart.discount', []);
		}

		if ($this->productExistsInCart($id))
		{
			$this->removeProduct($id);
		}

		session()->push('cart.products', [
			'id'       => $id,
			'name'     => $name,
			'type'     => $type,
			'quantity' => (int) $quantity,
			'price'    => $price,
			'total'    => $price * $quantity,
		]);

		$this->calculateDiscount();

		return true;
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function applyDiscount($id, $name, $type, $amount, $products): void
	{
		session()->put('cart.discount', []);

		session()->put('cart.discount', [
			'id'       => $id,
			'name'     => $name,
			'type'     => $type,
			'amount'   => $amount,
			'products' => $products ? array_map('intval', $products) : null,
			'total'    => 0,
		]);

		$this->calculateDiscount();
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function removeDiscount(): void
	{
		$cart = collect($this->getCartItems());

		$items = $cart->reject(function ($item) {
			return $item['type'] === 'discount';
		});

		session()->put('cart.products', $items->toArray());
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	private function calculateDiscount(): int
	{
		$totalDiscount = 0;

		if ( ! $this->hasDiscount())
		{
			return $totalDiscount;
		}

		$products = collect($this->getCartItems());
		$discount = session()->get('cart.discount');

		// Get cart products that are in the discount array.
		$cartProducts = $products->filter(function ($item) use ($discount) {
			if (is_array($discount['products']))
			{
				return in_array($item['id'], $discount['products']);
			}

			return $item;
		});

		// Total amount of the order based on products in the cart.
		$totalOrderPrice = $cartProducts->sum('total');

		// Calculate the discount based on the discount type.
		$totalDiscount = intval(round($cartProducts->sum('quantity') * $discount['amount']));

		// If percentage discount, recalculate.
		if ($discount['type'] === 'percent')
		{
			$totalDiscount = intval(round(($totalOrderPrice / 100) * $discount['amount']));
		}

		session()->put('cart.discount.total', $totalDiscount);

		return $totalDiscount;
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function removeProduct($id): true
	{
		$cart = collect($this->getCartItems());

		$items = $cart->reject(function ($item) use ($id) {
			return $item['id'] === $id;
		});

		session()->put('cart.products', $items->toArray());

		$this->calculateDiscount();

		return true;
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	private function productExistsInCart($id): bool
	{
		$items = collect($this->getCartItems());

		return $items->where('id', $id)->count() > 0;
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function refreshCart(): void
	{
		$this->calculateDiscount();
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function cartTotal($cents = false)
	{
		$products   = collect($this->getCartItems());
		$discount   = $this->calculateDiscount();
		$orderTotal = $products->sum('total') - $discount;

		if ($discount > $products->sum('total'))
		{
			$orderTotal = 0;
		}

		return $cents ? $orderTotal : Number::currency($orderTotal / 100, 'EUR');
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function getCartTotalWithoutDiscount($cents = false)
	{
		$products   = collect($this->getCartItems());
		$orderTotal = $products->sum('total');

		return $cents ? $orderTotal : Number::currency($orderTotal / 100, 'EUR');
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function cartItemsTotal(): int
	{
		$products = collect($this->getCartItems());

		return $products->count();
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function hasCartItems(): bool
	{
		$items = collect($this->getCartItems());

		return $items->count() > 0;
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function hasDiscount(): bool
	{
		$items = session()->get('cart.discount');

		return isset($items['type']);
	}

	public function clearCart(): void
	{
		session()->forget('cart');
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function getCartItems()
	{
		return session()->get('cart.products');
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function getDiscount()
	{
		return session()->get('cart.discount');
	}
}

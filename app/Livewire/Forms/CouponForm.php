<?php

namespace App\Livewire\Forms;

use App\Models\Coupon;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class CouponForm extends Form
{
	public ?Coupon $coupon;

	public string      $name;
	public string      $code;
	public string      $amount;
	public string      $type;
	public array       $products;
	public bool        $auto_apply     = false;
	public int|null    $limit          = 0;
	public string|null $published_to   = null;
	public string|null $published_from = null;

	public function setForm($couponId): void
	{
		$this->coupon = Coupon::whereId($couponId)->first();

		$this->name           = $this->coupon->name ?? '';
		$this->code           = $this->coupon->code ?? '';
		$this->auto_apply     = $this->coupon->auto_apply ?? false;
		$this->amount         = $this->coupon->amount ?? '';
		$this->type           = $this->coupon->type ?? 'amount';
		$this->limit          = $this->coupon->limit ?? 0;
		$this->published_to   = $this->coupon->published_to ?? null;
		$this->published_from = $this->coupon->published_from ?? null;

		if ($this->coupon && $this->coupon->products)
		{
			$this->products = json_decode($this->coupon->products, true);
		}

		if ($this->coupon && $this->coupon->type === 'amount')
		{
			$this->amount = number_format($this->coupon->amount / 100, 2, ',', '.');
		}

		if ($this->coupon && $this->coupon->type === 'percent')
		{
			$this->amount = $this->coupon->amount > 100 ? 100 : round($this->coupon->amount);
		}
	}

	/**
	 * @throws ValidationException
	 */
	public function update(): bool
	{
		$coupon = $this->validated();

		if ($this->coupon->update($coupon))
		{
			return true;
		}

		return false;
	}

	/**
	 * @throws ValidationException
	 */
	public function store(): bool
	{
		$coupon = $this->validated();

		if (Coupon::create($coupon))
		{
			return true;
		}

		return false;
	}

	/**
	 * @throws ValidationException
	 */
	public function validated(): array
	{
		$coupon = $this->validate();

		$coupon['products']   = (count($coupon['products']) > 0) ? json_encode($coupon['products']) : null;
		$coupon['limit']      = $coupon['limit'] ?? 0;
		$coupon['auto_apply'] = $coupon['auto_apply'] ?? false;

		if ($coupon['type'] === 'amount')
		{
			$coupon['amount'] = round(str_replace(',', '.', $coupon['amount']) * 100);
		}

		return $coupon;
	}

	public function rules(): array
	{
		return [
			'name'           => ['required', 'string', 'max:255'],
			'code'           => ['required', 'string', 'max:255'],
			'type'           => ['required', 'string', 'in:amount,percent'],
			'amount'         => ['required', 'string', 'max:255'],
			'auto_apply'     => ['required', 'bool'],
			'products'       => ['nullable', 'array'],
			'limit'          => ['nullable', 'int'],
			'published_from' => ['nullable', 'date'],
			'published_to'   => ['nullable', 'date', 'after:published_from'],
		];
	}

	public function messages(): array
	{
		return [
			'name.required'        => 'De naam is verplicht',
			'code.required'        => 'De code is verplicht.',
			'code.unique'          => 'Deze code bestaat reeds.',
			'type.required'        => 'Het tye code is verplicht.',
			'type.in'              => 'Ongeldig type coupon.',
			'amount.required'      => 'Het bedrag is verplicht.',
			'limit.int'            => 'Het limiet kan enkel een getal zijn.',
			'published_from.date'  => 'Vul een correcte datum in.',
			'published_from.after' => 'De datum moet in de toekomst liggen.',
			'published_to.date'    => 'Vul een correcte datum in.',
			'published_to.after'   => 'De datum moet na de startdatum zijn.',
		];
	}
}

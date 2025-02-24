<?php

namespace App\Livewire\Forms;

use App\Models\Article;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class EventForm extends Form
{
	use WithFileUploads;

	public ?Event $event;

	public string $name;
	public string $description;
	public string $bullets;
	public string $type;
	public string $date;
	public string $price;
	public        $image;
	public string $available;
	public string $unpublish_at;

	public string $pdf_format;
	public        $pdf_source;
	public string $pdf_orientation;

	public function setForm($event): void
	{
		$this->event = $event;

		$this->name            = $this->event->name ?? '';
		$this->description     = $this->event->description ?? '';
		$this->bullets         = $this->event->bullets ?? '';
		$this->type            = $this->event->type ?? '';
		$this->date            = $this->event->date ?? '';
		$this->price           = $this->event->price ? number_format($this->event->price / 100, 2, ',', '.') : '';
		$this->available       = $this->event->available ?? '';
		$this->unpublish_at    = $this->event->unpublish_at ? Carbon::parse($this->event->unpublish_at)->format('Y-m-d') : '';
		$this->pdf_format      = $this->event->pdf_format ?? '';
		$this->pdf_orientation = $this->event->pdf_orientation ?? '';
	}

	/**
	 * @throws ValidationException
	 */
	public function store()
	{
		$ticket = $this->validated();

		if ($this->pdf_source)
		{
			$ticket['pdf_source'] = $this->pdf_source->getClientOriginalName();

			$this->pdf_source->storeAs(path: 'tickets', name: $ticket['pdf_source']);
		}

		if ($this->image)
		{
			$filename = Str::slug($ticket['description']) . '.' . $this->image->extension();
			$ticket['image'] = $filename;

			$this->image->storeAs(path: 'public/tickets', name: $filename);
		}

		return Event::create($ticket);
	}

	/**
	 * @throws ValidationException
	 */
	public function update(): bool
	{
		$ticket = $this->validated();

		if ($this->event->update($ticket))
		{
			if ($this->pdf_source)
			{
				$filename = Str::slug($ticket['description']) . '.' . $this->image->extension();

				$this->event->pdf_source = $filename;
				$this->event->save();

				$this->pdf_source->storeAs(path: 'tickets', name: $filename);
			}

			if ($this->image)
			{
				$filename = Str::slug($ticket['description']) . '.' . $this->image->extension();

				$this->event->image = $filename;
				$this->event->save();

				$this->image->storeAs(path: 'public/tickets', name: $filename);
			}

			return true;
		}

		return false;
	}

	/**
	 * @throws ValidationException
	 */
	public function validated(): array
	{
		$ticket = $this->validate($this->rules(), $this->messages());

		$ticket['created_by'] = 1;
		$ticket['updated_by'] = 1;

		$ticket['price'] = round(str_replace(',', '.', $ticket['price']) * 100);

		$ticket['unpublish_at'] = ! empty($ticket['unpublish_at'])
			? Carbon::parse($ticket['unpublish_at'])->format('Y-m-d H:i:s')
			: Carbon::parse('2099-12-31')->format('Y-m-d H:i:s');

		return $ticket;
	}

	public function rules(): array
	{
		$rules = [
			'name'            => ['required', 'string', 'max:255'],
			'description'     => ['required', 'string', 'max:255'],
			'bullets'         => ['required', 'string', 'max:255'],
			'date'            => ['required', 'date'],
			'unpublish_at'    => ['nullable', 'date'],
			'type'            => ['required', 'string', 'in:munten,toegangskaart'],
			'pdf_format'      => ['required', 'string', 'in:A4,A5'],
			'pdf_orientation' => ['required', 'string', 'in:portrait,landscape'],
			'price'           => ['required', 'string'],
			'available'       => ['required', 'integer'],
		];

		if ($this->image)
		{
			$rules['image'] = ['sometimes', 'image', 'mimes:jpg,png,webp', 'max:1024'];
		}

		if ($this->pdf_source)
		{
			$rules['pdf_source'] = ['sometimes', 'image', 'mimes:jpg,png', 'max:3024'];
		}

		return $rules;
	}

	public function messages(): array
	{
		return [
			'name.required'            => 'De naam is verplicht',
			'description.required'     => 'Je dient een omschrijving in te vullen.',
			'bullets.required'         => 'De bullets zijn verplicht',
			'pdf_source.image'         => 'Dit bestand is geen afbeelding',
			'pdf_source.mimes'         => 'Dit bestand is geen afbeelding',
			'pdf_source.max'           => 'De afbeelding mag maximaal 1MB groot zijn',
			'date.date'                => 'Dit is een ongeldige datum',
			'unpublish_at.date'        => 'Dit is een ongeldige datum',
			'unpublish_at.after'       => 'De deactivatie datum moet na de datum liggen',
			'pdf_format.required'      => 'Kies een formaat voor de PDF',
			'pdf_format.in'            => 'Kies een geldig formaat voor de PDF',
			'pdf_orientation.required' => 'Kies een orientatie voor de PDF',
			'pdf_orientation.in'       => 'Kies een geldige orientatie voor de PDF',
			'price.required'           => 'De prijs is verplicht',
			'available.required'       => 'Het aantal beschikbare tickets is verplicht',
		];
	}
}

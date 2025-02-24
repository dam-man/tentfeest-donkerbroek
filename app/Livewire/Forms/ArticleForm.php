<?php

namespace App\Livewire\Forms;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Form;
use Livewire\WithFileUploads;

class ArticleForm extends Form
{
	use WithFileUploads;

	public ?Article $article;

	public string $title;
	public string $slug;
	public string $text;
	public        $image;
	public string $publish_at;
	public string $unpublish_at;

	public function setForm($articleId): void
	{
		$this->article = Article::whereId($articleId)->first();

		$this->title        = $this->article->title ?? '';
		$this->slug         = $this->article->slug ?? '';
		$this->text         = $this->article->text ?? '';
		$this->publish_at   = $this->article->publish_at ? Carbon::parse($this->article->publish_at)->format('Y-m-d') : now()->format('Y-m-d');
		$this->unpublish_at = $this->article->publish_at ? Carbon::parse($this->article->unpublish_at)->format('Y-m-d') : '';
	}

	/**
	 * @throws ValidationException
	 */
	public function store()
	{
		$article = $this->validated();

		$filename = Str::slug($article['title']) . '.' . $this->image->extension();

		$article['image'] = $filename;

		$this->image->storeAs(path: 'public/news', name: $filename);

		return Article::create($article);
	}

	/**
	 * @throws ValidationException
	 */
	public function update(): bool
	{
		$article = $this->validated();

		if ($this->article->update($article))
		{
			if ($this->image)
			{
				$filename = Str::slug($article['title']) . '.' . $this->image->extension();

				$this->article->image = $filename;
				$this->article->save();

				$this->image->storeAs(path: 'public/articles', name: $filename);
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
		$article = $this->validate($this->rules(), $this->messages());

		$article['slug'] = ! empty($article['slug'])
			? Str::slug($article['slug'])
			: Str::slug($article['title']);

		$article['created_by'] = 1;
		$article['updated_by'] = 1;

		$article['unpublish_at'] = ! empty($article['unpublish_at'])
			? Carbon::parse($article['unpublish_at'])->format('Y-m-d')
			: Carbon::parse('2099-12-31')->format('Y-m-d');

		return $article;
	}

	public function rules(): array
	{
		$rules = [
			'title'        => ['required', 'string', 'max:255'],
			'slug'         => ['required', 'string', 'max:255', Rule::unique('articles', 'slug')->ignore($this->article->id ?? null)],
			'text'         => ['required', 'string'],
			'publish_at'   => ['required', 'date'],
			'unpublish_at' => ['nullable', 'date', 'after:publish_at'],
		];

		if ($this->image)
		{
			$rules['image'] = ['sometimes', 'image', 'mimes:jpg,png,webp', 'max:1024'];
		}

		return $rules;
	}

	public function messages(): array
	{
		return [
			'title.required'     => 'De titel is verplicht',
			'slug.required'      => 'Je dient een slug in te vullen.',
			'slug.unique'        => 'Deze slug bestaat reeds in de database.',
			'text.required'      => 'De tekst is verplicht',
			'image.image'        => 'Dit bestand is geen afbeelding',
			'image.mimes'        => 'Dit bestand is geen afbeelding',
			'image.max'          => 'De afbeelding mag maximaal 1MB groot zijn',
			'publish_at.date'    => 'Dit is een ongeldige datum',
			'unpublish_at.date'  => 'Dit is een ongeldige datum',
			'unpublish_at.after' => 'De einddatum moet na de startdatum liggen',
		];
	}
}

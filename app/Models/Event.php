<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'description',
		'pdf_source',
		'pdf_orientation',
		'pdf_format',
		'date',
		'image',
		'price',
		'available',
		'sold',
		'published',
		'unpublish_at',
		'created_by',
		'updated_by',
		'created_at',
		'updated_at',
	];

	public function getBullets($value): array
	{
		return $value ? explode(';', $value) : [];
	}

	public function orders(): BelongsToMany
	{
		return $this->belongsToMany(Order::class, 'event_order')
		            ->withPivot(['id', 'quantity', 'barcode', 'scanned', 'scanned_at']);
	}

	public function paidOrders(): BelongsToMany
	{
		return $this->belongsToMany(Order::class, 'event_order')
		            ->where('status', 'paid');
	}

	public function scannedOrders(): BelongsToMany
	{
		return $this->belongsToMany(Order::class, 'event_order')
		            ->where('event_order.scanned', 1);
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'code',
		'amount',
		'type',
		'limit',
		'usage',
		'auto_apply',
		'published_from',
		'published_to',
		'products',
	];

	protected $casts = [
		'auto_apply' => 'boolean',
	];
}

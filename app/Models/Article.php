<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

	protected $fillable= [
		'title',
		'slug',
		'text',
		'image',
		'publish_at',
		'unpublish_at',
		'created_by',
		'updated_by',
	];

    protected $table = 'articles';

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select(['id','name']);
    }
}

<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'payment_id',
        'coupon_id',
        'amount',
        'total',
        'discount',
        'total',
        'status',
        'completed',
    ];

    protected $appends = ['ticket'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function emailDeliveryStatus(): string
    {
        if ( ! $this->email)
        {
            return 'Onbekend';
        }
        if ( ! empty($this->email->opened))
        {
            return 'Geopend';
        }
        if ( ! empty($this->email->delivered))
        {
            return 'Afgeleverd';
        }

        return 'Onbekend';
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_order')->withPivot(['id', 'quantity', 'barcode', 'scanned', 'scanned_at']);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'name', 'email', 'last_login');
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Email::class, 'order_id', 'id');
    }

    public function email(): HasOne
    {
        return $this->hasOne(Email::class, 'order_id', 'id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'payment_id', 'payment_id');
    }
}

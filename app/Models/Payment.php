<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = [
        'token',
        'payment_id',
        'order_id',
        'iban',
        'amount',
        'status',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'status'     => PaymentStatusEnum::class,
        ];
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id','order_id')->with('user');
    }
}

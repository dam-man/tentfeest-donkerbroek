<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'message_id',
        'subject',
        'has_attachment',
        'user_id',
        'order_id',
        'delivered',
        'failed',
        'opened',
        'clicked',
        'unsubscribed',
        'complained',
    ];
}

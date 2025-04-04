<?php

namespace App\Models;

use App\Notifications\SendPasswordResetNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'password_reset_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'created_at'        => 'datetime',
            'last_login'        => 'datetime',
            'password'          => 'hashed',
        ];
    }

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new SendPasswordResetNotification($token));
	}

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}

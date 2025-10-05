<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Students extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'enrollment_date',
        'expiry_date',
        'total_amount',
        'paid_amount',
        'payment_status',
        'hall',
        'seat',
        'profile_image',
        'phone',
        'address',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'expiry_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'last_login_at' => 'datetime',
    ];

    // अगर future में relations चाहिए हों (जैसे orders, subscriptions), तो यहाँ define कर सकते हैं
}

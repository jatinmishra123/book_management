<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Vendor extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'vendors';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        // ✅ Old columns
        'vendor_name',
        'company',
        'phone_number',
        'email',
        'address',
        'hall',
        'floor',
        'seat',

        // ✅ New API columns
        'full_name',
        'library_name',
        'mobile_number',
        'password',
    ];

    /**
     * Hide sensitive fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}

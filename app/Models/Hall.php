<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    protected $fillable = [
        'vendor_id',
        'hall_name',
        'total_seats',
        'type',
        'facilities',
    ];

    // 👇 facilities ko JSON array ke roop me cast karna
    protected $casts = [
        'facilities' => 'array',
    ];
}

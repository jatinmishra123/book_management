<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'vendor_id',
        'ac',
        'nonac',
        'type3',
        'plan'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

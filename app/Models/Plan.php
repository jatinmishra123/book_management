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

    // Plan ko JSON array ke रूप में cast karna
    protected $casts = [
        'plan' => 'array',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

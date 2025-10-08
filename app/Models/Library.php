<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Library extends Model
{
    protected $fillable = [
        'vendor_id',      // ✅ vendor id add karo
        'library_name',
        'start_time',
        'end_time',
        'address',
        'city',
        'locality',
        'state',
        'pincode',
        'logo',
        'photo',
    ];

    // ✅ Relation: ek library ek vendor ke under hai
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // ✅ Logo ka full URL return karega
    public function getLogoAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }

    // ✅ Photo ka full URL return karega
    public function getPhotoAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}

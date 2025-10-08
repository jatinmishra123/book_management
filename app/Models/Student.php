<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'student_id',
        'seat',
        'start_date',
        'end_date',
    ];
}

<?php
// app/Models/Book.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_name',
        'vendor',
        'quantity',
        'price',
        'purchase_date',
        'invoice',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'price' => 'decimal:2',
    ];
}

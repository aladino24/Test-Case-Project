<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'quantity',
        'selling_price',
        'total_price',
        'description',
        'sold_at'
    ];

    //relasi ke product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

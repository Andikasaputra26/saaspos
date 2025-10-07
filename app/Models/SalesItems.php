<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'store_product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id');
    }


    public function storeProduct()
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id');
    }   
}

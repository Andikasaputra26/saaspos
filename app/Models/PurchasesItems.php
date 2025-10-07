<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasesItems extends Model
{
    use HasFactory;

     protected $fillable = [
        'purchase_id',
        'store_product_id',
        'quantity',
        'subtotal',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function storeProduct()
    {
        return $this->belongsTo(StoreProduct::class);
    }
}

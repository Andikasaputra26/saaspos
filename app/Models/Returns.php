<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $table = 'returns'; 

    protected $fillable = [
        'store_id',
        'sale_id',
        'store_product_id',
        'quantity',
        'reason',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }

    public function storeProduct()
    {
        return $this->belongsTo(StoreProduct::class);
    }
}

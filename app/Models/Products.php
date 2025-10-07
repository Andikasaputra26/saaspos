<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'sku',
        'category',
        'price',
        'stock',
        'description',
        'image',
    ];
    public function storeProducts()
    {
        return $this->hasMany(StoreProduct::class, 'product_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: ambil URL gambar produk
    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/no-image.png');
    }
}

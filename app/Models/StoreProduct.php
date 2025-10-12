<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'product_id',
        'price',
        'stock',
        'is_active',
    ];

    

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    /**
     * Relasi ke item transaksi penjualan
     */
    public function saleItems()
    {
        return $this->hasMany(SalesItems::class, 'store_product_id');
    }

    /**
     * Relasi ke item pembelian (stok masuk)
     */
    public function purchaseItems()
    {
        return $this->hasMany(PurchasesItems::class, 'store_product_id');
    }

    /**
     * Relasi ke mutasi stok (stok keluar/masuk)
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'store_product_id');
    }

    /**
     * Helper: ambil nama produk global langsung
     */
    public function getProductNameAttribute()
    {
        return $this->product->name ?? '-';
    }
}

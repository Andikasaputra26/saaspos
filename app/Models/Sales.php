<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'invoice_number',
        'total',
        'payment_method',
    ];

    public function items()
    {
        // relasi ke SalesItems
        return $this->hasMany(SalesItems::class, 'sale_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

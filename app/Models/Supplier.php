<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'phone',
        'email',
        'address',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}

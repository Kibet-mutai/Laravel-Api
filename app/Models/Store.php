<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'description',
        'product_id',
        'product_quantity',
    ];


    public function seller() {
        return $this->belongsTo(Seller::class);
    }

    public function product_items() {
        return $this->hasMany(ProductItems::class, 'product_id', 'id');
    }
}

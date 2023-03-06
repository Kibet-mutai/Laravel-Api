<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItems extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'product_id',
        'image',
        'price',
        'quantity'
    ];

    public function products (){
        return $this->belongsTo(Store::class);
    }
}

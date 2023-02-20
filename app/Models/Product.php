<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description',
        'image',
        'quantity'
    ];

    protected $table = 'products';

    public function category(){
        return $this->belongsTo(Category::class);
    }
}

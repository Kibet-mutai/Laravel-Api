<?php

namespace App\Models;

use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sub-category'];

    protected $table= 'category';

    public function products(){
        return $this->hasMany(Products::class);
    }
}

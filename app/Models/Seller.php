<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        // 'email',
        // 'phone_no',
        'address',
        'nearest_town'
    ];
    protected $table = 'sellers';
    public function store(){
        return $this->hasOne(Store::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

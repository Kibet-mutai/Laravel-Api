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

    public function store(){
        return $this->hasOne(Store::class);
    }
}

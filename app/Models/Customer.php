<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'order_id',
        'balance'
    ];


    public function user() {
        return $this->hasOne(User::class);
    }


    public function order(){
        return $this->hasOne(OrderItem::class);
    }
}

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
        'balance',
        'phone_no',
        'email',
        'nearest_town',
        'county',
        'payment_method',
        'payment_id',
        'address'
    ];

    public function order(){
        return $this->hasOne(OrderItem::class);
    }
}

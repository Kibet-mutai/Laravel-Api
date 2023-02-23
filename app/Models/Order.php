<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_no',
        'email',
        'zipcode',
        'sub_county',
        'county',
        'payment_id',
        'payment_method',
        'tracking_no',
        'is_delivered'
    ];


    public function order_items() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}

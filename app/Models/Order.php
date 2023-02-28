<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'payment_id',
        'tracking_no',
        'is_delivered'
    ];


    public function order_items() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}

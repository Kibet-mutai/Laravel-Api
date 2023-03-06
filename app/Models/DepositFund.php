<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositFund extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'payment_method',
        'deposit_id'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTempWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_refer',
        'date',
        'particulars',
        'payment_type',
        'order_id',
        'order_product_id',
        'amount',
    ];


    
   
}

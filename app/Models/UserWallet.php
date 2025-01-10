<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'user_id',
        'date',
        'particulars',
        'payment_type',
        'order_id',
        'amount',
        'current_balance',
        'updated_balance',
    ];
   
}

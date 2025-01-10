<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitShareLog extends Model
{
    use HasFactory;

    protected $table = 'user_profit_sharing_logs';

    protected $fillable = [
        'user_id',
        'date',
        'amount',
        'create_by',
        'particulars',
    ];
}

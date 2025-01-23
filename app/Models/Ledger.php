<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Ledger extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id','invest_id','date', 'rate_of_intrest', 'description', 'credit', 'debit', 'balance'
    ];

    protected $dates = ['deleted_at'];

}

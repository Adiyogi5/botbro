<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Investment extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'invest_no','invest_amount','user_id', 'date', 'customer_name', 'customer_email', 'customer_mobile','payment_type','payment_status','screenshot','is_approved'
    ];

    protected $dates = ['deleted_at'];

}

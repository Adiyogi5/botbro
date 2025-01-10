<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRewardLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reward_id',
        'reward_name',
        'date',
        'particulars',
        'reward_status',
        'purchase_count',
        'created_at',
        'updated_at',
    ];
}

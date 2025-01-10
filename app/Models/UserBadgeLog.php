<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadgeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'badge_id',
        'date',
        'purchase_count',
        'particulars',
        'created_at',
        'updated_at',
    ];
}

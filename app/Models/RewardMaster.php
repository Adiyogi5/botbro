<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewardMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'min_product',
    ];
}

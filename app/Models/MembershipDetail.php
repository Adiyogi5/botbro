<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name','mobile','email','subject','message'
    ];

    protected $dates = ['deleted_at'];
}

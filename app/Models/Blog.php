<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id','title','slug', 'image', 'description', 'sort_order', 'status',
    ];

    protected $dates = ['deleted_at'];
}

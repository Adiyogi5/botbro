<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Testimonial extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name','designation','message','sort_order','image','status',
     ];

     protected $dates = ['deleted_at']; 
}


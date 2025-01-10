<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class State extends Model
{
    use HasFactory ,SoftDeletes;


     protected $fillable = [
        'name','country_id','status',
     ];

     protected $dates = ['deleted_at']; 
} 
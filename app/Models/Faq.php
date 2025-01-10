<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Faq extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'faq_type_id','question','answer','sort_order','status',
     ];

     protected $dates = ['deleted_at']; 

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Cms extends Model
{
     use HasFactory;

     protected $fillable = [
        'name','slug','cms_title','meta_title','meta_keyword', 'meta_description','cms_contant','image','status',
     ];

}

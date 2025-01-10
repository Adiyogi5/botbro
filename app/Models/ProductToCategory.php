<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductToCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'category_id',
    ];

    public function category() {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }
}

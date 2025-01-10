<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductImage;
use App\Models\ProductToCategory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'model',
        'price',
        'image',
        'description',
        'stock',
        'referral_price',
        'refer_price',
        'sort_order',
        'status',
    ];

    protected $dates = ['deleted_at'];

    public function product_image() {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    
    public function productCategory() {
        return $this->hasMany(ProductToCategory::class, 'product_id');
    }

    public function getProductImage()
    {
        return $this->hasOne(ProductImage::class)->where('sort_order','0');
    }

    public function get_product_image()
    {
        return $this->hasMany(ProductImage::class);
    }
}

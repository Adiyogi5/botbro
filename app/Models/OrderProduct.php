<?php

namespace App\Models;

use App\Observers\OrderProductObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'product_name','model', 'quantity', 'unit_price', 'total_price','discount','referral_price','refer_price'
    ];

    public static function boot()
    {
        parent::boot();
        self::observe(new OrderProductObserver);
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    
    
}

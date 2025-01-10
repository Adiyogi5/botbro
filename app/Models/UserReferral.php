<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReferral extends Model
{
    use HasFactory;

    protected  $table = 'user_referrals';

    protected $fillable = [
        'refer_id',
        'referral_id'
    ];


    public function referraluser()
    {
        return $this->hasOne(User::class,'id','referral_id')->withTrashed();
    }
}

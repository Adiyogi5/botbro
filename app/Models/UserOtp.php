<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;



class UserOtp extends Authenticatable  
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mobile_no', 'otp', 'time'];

    protected  $table = "user_otps";

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates = []; 
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];



}

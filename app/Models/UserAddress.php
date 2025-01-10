<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'address_1',
        'address_2',
        'postcode',
        'country_id',
        'state_id',
        'city_id',
        'default_id',
    ];

    public function get_Cities($id){
        $procat = City::where([['id',$id]])->first()->toArray(); 
        return $procat['name'];
    }
    public function get_States($id){
        $procat = State::where([['id',$id]])->first()->toArray(); 
        return $procat['name'];
    }
    public function get_Country($id){
        $procat = Country::where([['id',$id]])->first()->toArray(); 
        return $procat['name'];
    }
}

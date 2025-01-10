<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting_type', 'setting_name', 'filed_label', 'filed_name', 'filed_type', 'filed_value','field_options', 'is_require'
    ];


    public function get_general_parent_settings(){
        return array(
        'type'=>array(
            '1'=>"basic", 
           // '2'=>"email", 
            '3'=>"social",  
            '4'=>"extensions", 
           // '6'=>"payment", 
        ),
        'name'=>array(
            '1'=>"General Setting", 
            //'2'=>"Email Setting", 
            '3'=>"Social Setting", 
            '4'=>"Extensions",
           // '6'=>"Payment", 
        ) );
    }

    public function get_general_settings($name=NULL){
        return GeneralSetting::where(function($query) use($name) {  
                if(!empty($name)){
                    $query->where('setting_name','=',$name);
                }
            })->get(); 
    }
      

}

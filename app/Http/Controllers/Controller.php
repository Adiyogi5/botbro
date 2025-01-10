<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\GeneralSetting;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        //General Settings
        $global_data = [];
        $GeneralSetting = new GeneralSetting();
        $general_settings_data = $GeneralSetting->get_general_settings();
        
        foreach ($general_settings_data as $skey => $svalue) {
           $global_data['general_settings'][$svalue['setting_name']]= $svalue['filed_value'];
        } 
        $this->general_settings = $global_data['general_settings']; 
        
        //User Global Premission
        $global_premission = [];
        
    }

}

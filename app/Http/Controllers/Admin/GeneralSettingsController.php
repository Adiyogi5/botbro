<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;

class GeneralSettingsController extends Controller
{
    /**
     * Only Authenticated users for "admin" guard 
     * are allowed.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        checkPermission($this, 101);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $GeneralSetting = new GeneralSetting();
        $data['parent_setting'] = $GeneralSetting->get_general_parent_settings();
        $general_settings_data = $GeneralSetting->get_general_settings();
        $generalsettings = json_decode($general_settings_data); 
        foreach ($generalsettings as $skey => $svalue) {
           $data['general_settings'][$svalue->setting_type][]= (array)$svalue;
        } 
        $data['title'] = 'General Setting';   
        return view('admin.general_settings')->with(['parent_setting' => $data['parent_setting'], 'general_settings' => $data['general_settings'], 'title'=>$data['title']]);
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $filedold=$_POST['SettingOld']['filedval'];
        $filedval=$_POST['Setting']['filedval'];
        ///$filedfile= $_FILES;
        $filedfile= $request->file(); 
        $error = "";
        foreach ($filedval as $key => $value) { 
                try {
                    if($key==4){
                        $timezone = date_default_timezone_set($filedval[4]);
                    }
                    GeneralSetting::where('id', '=', $key)->update(array('filed_value' => $value));
                } catch (\Exception $e) {            
                    $error = $e->getMessage();
                } 
        } 
        if(empty($error)){
            /// Manage Old file and New
            foreach ($filedold as $key => $value) {
                $oldfile[$key]= $value;
            }  
            $path= UPLOADFILES."setting/"; 
            $i=1;   
            foreach ($filedfile as $key => $value) { 
                if(!empty($_FILES[$key]['name']))
                {   
                    $akey = explode('_',$key);
                    $pagId = array_pop($akey); 
                    // if(!empty($oldfile[$i])){  
                    //     delete_file($oldfile[$i]);
                    // } 
                    $status = true;               
                    $file = $request->file($key); 
                    $filename = rand().time().'.'.$file->getClientOriginalExtension();
                    // Upload file
                    $file->move($path,$filename);
                    if($status){
                        GeneralSetting::where('id', '=', $pagId)->update(array('filed_value' => $path.$filename));
                    
                    }else{
                        return redirect('admin/general_settings')->with('error', $result['msg']);
                    }
                    $i++;   
                }
            }
            return redirect('admin/general_settings')->with('success','Settings Update Successfully!!');
        }else{
            return redirect('admin/general_settings')->with('error',$error);
        }
    }
 
}

<?php

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Twilio\Rest\Client;

function getRoles()
{
 
    $roles = DB::table('roles')->where('id','!=',1)->where('id','!=',5)->where('id','!=',6)->pluck('display_name','id'); 
    return $roles;

} 

function getEmployeeId($user_id){

    return App\User::where('id',$user_id)->first()->employee_id;
}

function RandcardStr($length){

    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "01234567899876543210";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}


function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

function firebaseNotification($fcm_ids, $data, $setting, $image){
    if($setting['firebasekey'] != '')
    {
        $image = !empty($image)?$image:asset($setting['logo']);
        $url = "https://fcm.googleapis.com/fcm/send";
        $title = $data['title'];
        $body = $data['message'];
        $notification = array('title' =>$title ,'name'=> $setting['application_name'],'body' => $body, 'sound' => 'default', 'badge' => '1','image'=>$image);
        $arrayToSend = array('registration_ids' => $fcm_ids, 'notification' => $notification,'priority'=>'high');
      
        $json = json_encode($arrayToSend); 
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $setting['firebasekey'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //Send the request
        $result = curl_exec($ch);   
        
        curl_close($ch);    
    } 
}

function imageexist($image_path)
{
   
    if ($image_path == '' || !file_exists(public_path('storage/' . $image_path))) {
        return asset('public/images/favicon-upayliving.png');
    } else {
        return asset('public/storage/' . $image_path);
    }
}

function get_image($path, $type = 'default', $resize = 1)
{

    $default_images = [
        'product_grid' => ['img' => 'images/favicon-upayliving.png', 'width' => 450, 'height' => 450],
        'product_detail' => ['img' => 'images/favicon-upayliving.png', 'width' => 546, 'height' => 391],
        'product_detail_thumb' => ['img' => 'images/favicon-upayliving.png', 'width' => 130, 'height' => 90],
        'default' => ['img' => 'images/favicon-upayliving.png', 'width' => 450, 'height' => 450],
    ];

    $type = empty($default_images[$type]) ? 'default' : $type;

    $full_path = public_path('storage/' . $path);

    $height = $default_images[$type]['height'];
    $width = $default_images[$type]['width'];

    $new_path = file_exists($full_path) && !empty($path) ? public_path('storage/' . $path) : public_path($default_images[$type]['img']);
    $path = file_exists($full_path) && !empty($path) ? $path : $default_images[$type]['img'];

    if ($resize) {
        return image_resize($new_path, $path, $width, $height);
    } else {
        return asset('storage/' . $path);
    }
}

function image_resize($filename, $name, $width, $height)
{
    include_once base_path() . '/image.php';
    $image_old = $filename;
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $image_new = 'public/cache/' . $name . '-' . (int) $width . 'x' . (int) $height . '.' . $extension;

    $root_path = base_path() . "/";

    if (!is_file($root_path . $image_new) && is_file($image_old)) {

        // Get new dimensions
        list($width_orig, $height_orig, $image_type) = getimagesize($image_old);

        $path = '';
        $directories = explode('/', dirname($image_new));
        foreach ($directories as $directory) {
            $path = $path . '/' . $directory;
            if (!is_dir($root_path . $path)) {
                @mkdir($root_path . $path, 0777);
            }
        }
        if ($width_orig != $width || $height_orig != $height) {
            $image = new Image($image_old);
            $image->resize($width, $height);
            $image->save($root_path . $image_new);
        } else {
            copy($image_old, $root_path . $image_new);
        }
    }
    $image_new = str_replace(' ', '%20', $image_new);

    return asset($image_new);
}


function numberToWord( $num = '' )
{
    $num    = ( string ) ( ( int ) $num );
 
    if( ( int ) ( $num ) && ctype_digit( $num ) )
    {
        $words  = array( );
       
        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
       
        $list1  = array('','one','two','three','four','five','six','seven',
            'eight','nine','ten','eleven','twelve','thirteen','fourteen',
            'fifteen','sixteen','seventeen','eighteen','nineteen');
       
        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
            'seventy','eighty','ninety','hundred');
       
        $list3  = array('','thousand','million','billion','trillion',
            'quadrillion','quintillion','sextillion','septillion',
            'octillion','nonillion','decillion','undecillion',
            'duodecillion','tredecillion','quattuordecillion',
            'quindecillion','sexdecillion','septendecillion',
            'octodecillion','novemdecillion','vigintillion');
       
        $num_length = strlen( $num );
        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
        $max_length = $levels * 3;
        $num    = substr( '00'.$num , -$max_length );
        $num_levels = str_split( $num , 3 );
       
        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';
           
            if( $tens < 20 )
            {
                $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
            }
            else
            {
                $tens   = ( int ) ( $tens / 10 );
                $tens   = ' ' . $list2[$tens] . ' ';
                $singles    = ( int ) ( $num_part % 10 );
                $singles    = ' ' . $list1[$singles] . ' ';
            }
            $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        }
       
        $commas = count( $words );
       
        if( $commas > 1 )
        {
            $commas = $commas - 1;
        }
       
        $words  = implode( ', ' , $words );
       
        //Some Finishing Touch
        //Replacing multiples of spaces with one space
        $words  = trim( str_replace( ' ,' , ',' , trimAll( ucwords( $words ) ) ) , ', ' );
        if( $commas )
        {
            $words  = strReplaceLast( ',' , ' and' , $words );
        }
       
        return $words;
    }
    else if( ! ( ( int ) $num ) )
    {
        return 'Zero';
    }
    return '';
}

function validateUser($slug)
{
if($slug == Auth::user()->slug)
    return TRUE;
return FALSE;
}

function prepareBlockUserMessage($request)
{
 $request->session()->flash('Ooops..!', 'you_have_no_permission_to_access', 'error');
 return '';
}


function pageNotFound($request)
{
 $request->session()->flash('Ooops..!', 'page_not_found', 'error');
 return '';
} 

function isActive($active_class = '', $value = '')
{
    $value = isset($active_class) ? ($active_class == $value) ? 'active' : '' : '';
    if($value)
        return "class = ".$value;
    return $value; 
}

function pr($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function prd($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}
    
function html_escape($var, $double_encode = TRUE)
{
    if (empty($var))
    {
        return $var;
    }

    if (is_array($var))
    {
        foreach (array_keys($var) as $key)
        {
            $var[$key] = html_escape($var[$key], $double_encode);
        }

        return $var;
    }

    return htmlspecialchars($var, ENT_QUOTES);
}    

function delete_file($filepath){
    // unlink($filepath);
    if (File::exists(storage_path('app\public\\' . $filepath))) {
        File::delete(storage_path('app\public\\' . $filepath));
    }
}

function checkPermission($current, $module_id = null)
{
    $method = Route::current()->getActionMethod();
    switch ($method) {
        case 'index':
            $current->middleware('isAllow:'.$module_id.',can_view');
            break;
        case 'create':
            $current->middleware('isAllow:'.$module_id.',can_add');
            break;
        case 'store':
            $current->middleware('isAllow:'.$module_id.',can_add');
            break;
        case 'edit':
            $current->middleware('isAllow:'.$module_id.',can_edit');
            break;
        case 'update':
            $current->middleware('isAllow:'.$module_id.',can_edit');
            break;
        case 'destroy':
            $current->middleware('isAllow:'.$module_id.',can_delete');
            break;
        case 'change_status':
            $current->middleware('isAllow:'.$module_id.',can_edit');
            break;
        case 'adddetails':
            $current->middleware('isAllow:'.$module_id.',can_edit');
            break;
    }

}

function userCan($module_id = [], $type = "can_view"): bool
{
    if (gettype($module_id) == 'array') {
        $module = (array) $module_id;
    } else {
        $module = [$module_id];
    }

    $permission = request()->permission;
    $result = array_filter($permission, function ($item) use ($module, $type) {
        if (in_array($item['module_id'], $module) && ($item['allow_all'] == 1 || $item[$type] == 1)) {
            return $item;
        }
        return false;
    });

    if (count($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function userAllowed($module_id = 0, $type = ['can_edit', 'can_delete'])
{
    $permission = request()->permission;
    $result = array_filter($permission, function ($item) use ($module_id) {
        if ($item['module_id'] == $module_id) {
            return $item;
        }
        return false;
    });

    if (count($result) > 0) {
        $item = array_values($result)[0];
        $result = array_filter($type, function ($row) use ($item) {
            if (!empty($item[$row])) {
                return $item;
            }
            return false;
        });

        if ($item['allow_all'] == 1  || count($result) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function get_address_icon($type)
{
    switch ($type) {
        case '1':
            return "fa-solid fa-house faa-modal";
            break;
        default:
            return "fa-solid fa-city faa-modal";
            break;
    }
}

function sendSms(array $numbers, string $message, array $site_settings)
{
    try { 
        $apiKey     = urlencode($site_settings['textlocal_key']);
        $sender     = urlencode($site_settings['textlocal_sender']);

        // Message details
        $numbers    = urlencode(implode(',', $numbers));
        $message    = urlencode($message);

        // Send the GET request with cURL
        $response = Http::get($site_settings['textlocal_url'], [
            'apikey'    => $apiKey,
            'sender'    => $sender,
            'numbers'   => $numbers,
            'message'   => $message,
        ]);

        if ($response->successful()) {
            $data = $response->json(); 
            if ($data['status'] == 'success') return true;
            else return false;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        return false;
    }
} 
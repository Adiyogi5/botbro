<?php

define('BASE_PATH', 'http://localhost/botbro/'); 
define('DOC_PATH', dirname(__DIR__) . '/');

define('ONLINESTATUS', false);

if(!ONLINESTATUS)
{
	define('PREFIX1', BASE_PATH.'public/');  
	define('UPLOADFILES', 'public/uploads/'); 
	define('ASSETS', BASE_PATH.'public/assets/');
}else{
	define('PREFIX1', BASE_PATH);  
	define('UPLOADFILES', 'uploads/'); 
	define('ASSETS', BASE_PATH.'assets/');
}

define('DASHBOARD', BASE_PATH.'dashboard');

//Design Source File Paths
define('CSS', PREFIX1.'css/');
define('JS', PREFIX1.'js/'); 
define('IMAGES', PREFIX1.'images/');
define('VENDOR', BASE_PATH.'vendor/');
define('CKEDITOR', PREFIX1 . 'ckeditor/');

define('RETURNACTIONS',['1'=>'Refunded','2'=>'Replacement','3'=>'Reject Request']);

define('RETURNTYPE',['1'=>'Refunded','3'=>'Reject Request']);
define('REPLACETYPE',['2'=>'Replacement']);

define('RETURNSTATUS',['1'=>'Pending','3'=>'Completed']);

define('TRANSFER_FEE','2'); 

define('CURRENCY_SYMBOL','₹');

define('STATUS_TYPE',[1=>"Assign",2=>"Accepted",3=>"Reject",4=>"No Reply"]);
define('PAYMENT_TYPE',[1=>"Online",2=>"Cash On Delivery"]);
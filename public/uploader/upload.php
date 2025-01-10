<?php
/*ini_set('display_errors',1);
error_reporting(E_ALL);*/
include_once('../../app/constants.php');    

if(isset($_FILES['upload']['name']))
{
 $file = $_FILES['upload']['tmp_name'];
 $file_name = $_FILES['upload']['name'];
 $file_name_array = explode(".", $file_name);
 $extension = end($file_name_array);
 $new_image_name = rand() . '.' . $extension;
 
 $allowed_extension = array("jpg", "gif", "png");
 if(in_array($extension, $allowed_extension))
 {  
    $moved =  move_uploaded_file($file, $_SERVER['DOCUMENT_ROOT'].DIRPATH.'/'. UPLOADFILES .'ckimage/'. $new_image_name);

    $function_number = $_GET['CKEditorFuncNum'];
    $url = BASE_PATH.UPLOADFILES .'ckimage/'. $new_image_name;
    $message = '';
    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
 }
}

?>
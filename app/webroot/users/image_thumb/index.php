<?php
date_default_timezone_set('UTC');
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('WWW_ROOT')) {
    $dir = dirname(__FILE__);
    define('WWW_ROOT', substr($dir,0,stripos($dir,'webroot')+strlen('webroot')). DS);
}
error_reporting(0);
include("../../../Config/constants.php");
use ImageComponent as Image;

require_once('ImageComponent.php');

/*********** Image Thumb ***********/
function image_thumb()  {
	//$this->autoRender = false;
	$save_to_file = true;
	$image_quality = 100;
	$image_type = -1;
	$max_x = 100;
	$max_y = 100;
	$cut_x = 0;
	$cut_y = 0;
	$images_folder = '';
	$thumbs_folder = '';
	$to_name = '';
	
	if($_REQUEST['type'] == "photos") {
		$images_folder = DIR_USER_PHOTOS;
		if(defined('USE_S3') && USE_S3 && urldecode($_REQUEST['file'])!='user.png'){
			$images_folder = DIR_USER_PHOTOS_S3;
		}
	} elseif($_REQUEST['type'] == "company") {
		$images_folder = DIR_FILES.'company/';
		if(defined('USE_S3') && USE_S3){
			$images_folder = DIR_USER_COMPANY_S3;
		}
	} else {
		$images_folder = DIR_CASE_FILES;
	}
	if(isset($_REQUEST['nocache'])) {
	  $save_to_file = intval($_REQUEST['nocache']) == 1;
	}
	if(isset($_REQUEST['file'])) {
	  $from_name = urldecode($_REQUEST['file']);
	}
	if(isset($_REQUEST['dest']))  {
	  $to_name = urldecode($_REQUEST['dest']);
	}
	if(isset($_REQUEST['quality']))  {
	  $image_quality = intval($_REQUEST['quality']);
	}
	if (isset($_REQUEST['t']))  {
	  $image_type = intval($_REQUEST['t']);
	}
	if (isset($_REQUEST['sizex'])) {
	  $max_x = intval($_REQUEST['sizex']);
	}
	if (isset($_REQUEST['sizey'])) {
	  $max_y = intval($_REQUEST['sizey']);
	}
	if (isset($_REQUEST['size'])) {
	  $max_x = intval($_REQUEST['size']);
	}
    if(file_exists($images_folder.$from_name)){
        $file_path = $images_folder.$from_name;
    }else{
        $file_path = DIR_IMAGES."no-image.png";
    }        
    #echo $file_path;exit;
	ini_set('memory_limit', '-1');//echo $images_folder.$from_name;//exit;
	//$this->Image->GenerateThumbFile($images_folder.$from_name, $to_name,$max_x,$max_y);
	$image = new ImageComponent();
	//print $images_folder.$from_name.'---'.$to_name.'----'.$max_x.'---'.$max_y.'----'.$from_name;exit;
	$image->GenerateThumbFile($file_path, $to_name,$max_x,$max_y,$from_name);
}
image_thumb();
?>

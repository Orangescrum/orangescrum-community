<?php  
// print"<pre>"; 
set_time_limit(0);
ini_set('post_max_size', '1024M');
ini_set('upload_max_filesize', '1024M');
include_once("config.php");
include_once("merge.php");
include_once("../../Config/constants.php");
include_once("../../Config/database.php");
$file_path = "files".DS;
$config= new DATABASE_CONFIG();
$settings = $config->{'default'};
$newui_version = 'AddonInstaller-V'.NEWUI_VERSION.'.zip';
$exeui_version = 'ExecutiveDashboard-V'.EXEUI_VERSION.'.zip';
//print_r($_FILES); exit;
if(!empty($_FILES['addon_installer']['name'])){
	$cfg["db_host"] = $dbhost= $settings['host'];
	$cfg["db_user"] = $dbuser =  $settings['login'];
	$cfg["db_pass"] = $dbpass = $settings['password'];
	$cfg["db_name"] = $dbname = $settings['database'];
	$conn = mysqli_connect($cfg["db_host"], $cfg["db_user"], $cfg["db_pass"]);
	$arr = explode('.',$_FILES['addon_installer']['name']);
	$file_ext=strtolower(end($arr));
	$expensions= array("zip");
	if(in_array($file_ext,$expensions)=== false){
		$redirectUrl =HTTP_ROOT."install".DS."index.php";
		echo '<h1 style="color:red;text-align:center;">Oops ! Please upload zip file.</h1>';
		echo '<div style="display:block;color:red;text-align:center;"><a href="'.$redirectUrl.'">Try Again</a></div>';
		exit;
	 }else if(!empty($_FILES['addon_installer']['name']) && $_FILES['addon_installer']['name'] !=$newui_version && $_FILES['addon_installer']['name'] !=$exeui_version){
	 	$redirectUrl =HTTP_ROOT."install".DS."index.php";
		echo '<h1 style="color:red;text-align:center;">Oops ! Please Choose '.$newui_version.' Or '.$exeui_version.' file.</h1>';
		echo '<div style="display:block;color:red;text-align:center;"><a href="'.$redirectUrl.'">Try Again</a></div>';
		exit;
	 }
	$file_info = basename($_FILES['addon_installer']['name'], ".zip");
	$errors= array();
	$file_name = $_FILES['addon_installer']['name'];
	$file_size =$_FILES['addon_installer']['size'];
	$file_tmp =$_FILES['addon_installer']['tmp_name'];
	$file_type=$_FILES['addon_installer']['type'];
	// SETP 1 COPY ZIP FILE
	copy($file_tmp,$file_path.$file_name);
	$zip = new ZipArchive;
	$res = $zip->open($file_path.$file_name);
	if ($res) {
	  	// STEP 2 EXTRACT ZIP FILE
	  	$zip->extractTo($file_path);
	  	// STEP 3 BACKUP EXISTING DATABASE
	  	$conn = mysqli_connect($cfg["db_host"], $cfg["db_user"], $cfg["db_pass"]);
	 	if (!$conn) {
	 		echo '<h1 style="color:red;text-align:center;">Oops ! Database Connection Failed.</h1>';
			echo '<div style="display:block;color:red;text-align:center;"><a href="'.$redirectUrl.'">Try Again</a></div>';
	    	exit;
		}
		$db = mysqli_select_db($conn,$cfg["db_name"]);
	    if (!$db) {
	    	echo '<h1 style="color:red;text-align:center;">Oops ! Database '.$cfg["db_name"].' does not exist.</h1>';
			echo '<div style="display:block;color:red;text-align:center;"><a href="'.$redirectUrl.'">Try Again</a></div>';
	    	exit;
	    }
	    $newDB = $cfg["db_name"]."-backup-".date('d-m-Y');
	    $oldDB =$originalDB= $cfg["db_name"];
	    $tables = "SELECT table_name FROM information_schema.tables WHERE table_schema = '{$oldDB}'AND table_type = 'BASE TABLE'";
	    $getTables  =   mysqli_query($conn,$tables)  or die('Query failed: ' . mysqli_error()); 
	    $originalDBs = [];
	    if (mysqli_num_rows($getTables) > 0) {
	    	while($row = mysqli_fetch_assoc($getTables)) {
	            $originalDBs[] = $row['table_name'];
	        } 
	     } 
	     // exit;
	    mysqli_query($conn,"DROP DATABASE IF EXISTS `$newDB` ")  or die('Query failed: ' . mysqli_error());
	    mysqli_query($conn,"CREATE DATABASE IF NOT EXISTS `$newDB`")  or die('Query failed: ' . mysql_error());
	    foreach($originalDBs as $tab ) {
	        mysqli_select_db($conn, $newDB )  or die('Query failed: ' . mysql_error() );
	        mysqli_query($conn,"CREATE TABLE $tab LIKE ".$originalDB.".".$tab)  or die('Query failed: ' . mysqli_error() );
	    	mysqli_query($conn,"INSERT INTO $tab SELECT * FROM ".$originalDB.".".$tab)  or die('Query failed: ' . mysqli_error());
	    }

	    // STEP 4 IMPORT SQL FILE
	    $db = mysqli_select_db($conn,$cfg["db_name"]);
	    if (!$db) {
	        echo '<h1 style="color:red;text-align:center;">Oops ! Database '.$cfg["db_name"].' does not exist.</h1>';
			echo '<div style="display:block;color:red;text-align:center;"><a href="'.$redirectUrl.'">Try Again</a></div>';
	    	exit;
	    }
	    $addons_query = mysqli_query($conn,"SELECT * FROM addons WHERE name ='NEWUI' LIMIT 1")  or die('Query failed: ' . mysqli_error());
		$addons = mysqli_fetch_row($addons_query);
		if(empty($addons) &&  $_FILES['addon_installer']['name'] ==$exeui_version){
			$templine = '';
			$lines = file($file_path.$file_info.DS."installer.sql");
			foreach ($lines as $line){
				if (substr($line, 0, 2) == '--' || $line == '')
		    		continue;

				$templine .= $line;
				if (substr(trim($line), -1, 1) == ';'){
		    		mysqli_query($conn,$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
		    		$templine = '';
				}
			}
		}
	   	$templine = '';
	   	if($_FILES['addon_installer']['name'] ==$newui_version){
	   		mysqli_query($conn,"INSERT INTO `addons` (`name`, `isactive`, `dt_created`) VALUES ('NEWUI', '1', '2017-03-30 09:31:32');")  or die('Query failed: ' . mysqli_error());
	    	$lines = file($file_path.$file_info.DS."installer.sql");
	   	}else if($_FILES['addon_installer']['name'] ==$exeui_version){
	   		$lines = file($file_path.$file_info.DS."executive.sql");
	   	}
	    foreach ($lines as $line){
			if (substr($line, 0, 2) == '--' || $line == '')
	    		continue;

			$templine .= $line;
			if (substr(trim($line), -1, 1) == ';'){
	    		mysqli_query($conn,$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error() . '<br /><br />');
	    		$templine = '';
			}
		}
		// STEP 5 BACKUP APP FOLDER
		$src = ROOT;
		$dst = $_SERVER['DOCUMENT_ROOT'].DS.SUB_FOLDER.'app-backup-'.date('d-m-Y');
		recurse_copy($src,$dst);
		// STEP 6 BACKUP APP FOLDER
		$src2 = WWW_ROOT.$file_path.$file_info."".DS."app";
		$dst2 = ROOT;
		recurse_merge($src2,$dst2);
		// STEP 6 CACHE CLEAR
		$models =  ROOT.DS."tmp".DS."cache".DS."models".DS."*"; 
		$models_files = glob($models); // get all file names
		foreach($models_files as $file){ // iterate files
		  if(is_file($file))
		    unlink($file); // delete file
		}
		$persistent =  ROOT.DS."tmp".DS."cache".DS."persistent".DS."*"; 
		$persistent_files = glob($persistent); // get all file names
		foreach($persistent_files as $file){ // iterate files
		  if(is_file($file))
		    unlink($file); // delete file
		}
		$zip->close();
		if($_FILES['addon_installer']['name'] ==$newui_version){
			$dir = WWW_ROOT.$file_path.'AddonInstaller-V'.NEWUI_VERSION;
			delete_dir($dir);
			$zipfile = WWW_ROOT.$file_path.'AddonInstaller-V'.NEWUI_VERSION.'.zip';
			if(is_file($zipfile)){
			    unlink($zipfile);
			}
		}else if($_FILES['addon_installer']['name'] ==$exeui_version){
			$dir = WWW_ROOT.$file_path.'ExecutiveDashboard-V'.EXEUI_VERSION;
			delete_dir($dir);
			$zipfile = WWW_ROOT.$file_path.'ExecutiveDashboard-V'.EXEUI_VERSION.'.zip';
			if(is_file($zipfile)){
			    unlink($zipfile);
			}
		}
		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Location: '. HTTP_ROOT);

	} else {
		echo '<h1 style="color:red;text-align:center;">Oops ! '.$file_name.' file is missing </h1>';
		echo '<div style="display:block;color:red;text-align:center;"><a href="'.$redirectUrl.'">Try Again</a></div>';
		exit;
	}
}else{
	$redirectUrl =HTTP_ROOT."install".DS."index.php";
	echo '<h1 style="color:red;text-align:center;">Oops ! Please Choose '.$newui_version.' Or '.$exeui_version.' file.</h1>';
	echo '<div style="display:block;color:red;text-align:center;"><a href="'.$redirectUrl.'">Try Again</a></div>';
	exit;
}

?>
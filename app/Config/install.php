<?php
/*********************************************************************************
 * Orangescrum Community Edition is a web based Project Management software developed by
 * Orangescrum. Copyright (C) 2013-2014
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact Orangescrum, 2059 Camden Ave. #118, San Jose, CA - 95124, US. 
   or at email address support@orangescrum.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * Orangescrum" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by Orangescrum".
 ********************************************************************************/
?>
<?php
include("database.php");
$config= new DATABASE_CONFIG();
$name = 'default';
$settings = $config->{$name};
if(trim($settings['database']) == "") {
    ?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta name="robots" content="noindex,nofollow" />
		<link rel="shortcut icon" href="images/favicon_new.ico"/>
		<title>Orangescrum Setup Wizard</title>
		<style>
		*{
			padding:5;
			margin:5;	
			font-family:Tahoma, Verdana;
		}
		.link:hover {
			text-decoration:underline;
		}
		h4 {
			font-size:14px;
		}
		</style>
	</head>
	<body>
		<div id="container">
			<div id="content">
				<table width="100%" align="center"><tr><td align="center">
				<table cellpadding="8" cellspacing="8" style="border:1px solid #999999;color:#000000" align="center" width="520px">
					<tr>
						<td align="center" style="border-bottom:1px solid #999999">
							<h3 style="color:#245271">4 simple steps to get started with Orangescrum</h3>
						</td>
					</tr>
					<tr>
						<td align="left" style="padding-top:10px">
							<h4>Step1: <span style="font-weight:normal;">Create a new MySQL database (`utf8_unicode_ci` collation)</span></h4>
							<h4>Step2: <span style="font-weight:normal;">Add your database connection details and the database name in `app/Config/database.php` page</span></h4>
							<h4>Step3: <span style="font-weight:normal;">Get the `database.sql` file from the root directory and import that to your database.</span></h4>
							<h4>Step4: <span style="font-weight:normal;">Provide the details of SMTP email sending options in `app/Config/constants.php`</span></h4>
						</td>
					</tr>
                    <tr>
						<td align="center">
							<h4 style="color:#FF0000">Make sure that you have write permission (777) to `app/tmp` and `app/webroot` folders</h4>
						</td>
					</tr>
				</table>
				</td></tr></table>
			</div>
		</div>
	</body>
	</html>
	<?php
	exit;
} else {
	include (CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'Model' . DS. 'ConnectionManager.php');
	if (class_exists('DATABASE_CONFIG')){
    $dbConfigName1 = 'default'; //<-- PUT YOUR DATABASE CONFIG VARIABLE NAME HERE

    $dbConfig1 = new DATABASE_CONFIG();
    #print_r( $dbConfig1);exit;

    if( isset( $dbConfig1->$dbConfigName1)){                       

        //Now check wether the database configuration, its alive!
        //pr( $dbConfig1->$dbConfigName1);
		try{
			$dbTemp1 =ConnectionManager::getDataSource($dbConfigName1);
			$tables = $dbTemp1->listSources();
			if(isset($tables) && empty($tables)){ 
			$subfolder = check_subfolder(); ?>
			<!DOCTYPE html>
	<html>
	<head>
		<meta name="robots" content="noindex,nofollow" />
		<link rel="shortcut icon" href="images/favicon_new.ico"/>
		<title>Orangescrum Setup Wizard</title>
		<style>
		*{
			padding:5;
			margin:5;	
			font-family:Tahoma, Verdana;
}
		.link:hover {
			text-decoration:underline;
		}
		h4 {
			font-size:14px;
		}
		</style>
	</head>
	<body>
		<div id="container">
			<div id="content">
				<table width="100%" align="center"><tr><td align="center">
				<table cellpadding="8" cellspacing="8" style="border:1px solid #999999;color:#000000" align="center" width="520px">
					<tr>
						<td align="center" style="border-bottom:1px solid #999999">
							<h3 style="color:#245271">2 simple steps to get started with Orangescrum</h3>
						</td>
					</tr>
					<tr>
						<td align="left" style="padding-top:10px">
							<h4>Step1: <span style="font-weight:normal;">You have created a database named "<b>Orangescrum</b>". But you have no tables in that database. Get the "<b>database.sql</b>" file from the root directory and import that to your database.</span></h4>
							<h4 style="margin-bottom:0">Step2: <span style="font-weight:normal;">Provide the following details of SMTP configuration options in `app/Config/constants.php`</span>
							<ul>
								<li>SMTP_UNAME</li>
								<li>SMTP_PWORD</li>
								<li>FROM_EMAIL_NOTIFY</li>
								<li>SUPPORT_EMAIL</li>
							</ul></h4>
						</td>
					</tr>
					<tr>
						<td>
							<h4 style="margin:0">Note:
								<span style="font-weight:normal;">At any point you can change your database credentials in `app/Config/database.php` page.</span>
							</h4>
							<h4><span style="font-weight:normal;">For more information please visit <a href="http://www.orangescrum.org/general-installation-guide" target="_blank">Installation Guide</a>.</span></h4>
						</td>
					</tr>
					<tr>
						<td align="center">
							<h4 style="color:#FF0000;margin-top:0">Make sure that you have write permission (777) to `app/tmp` and `app/webroot` folders</h4>
						</td>
					</tr>
				</table>
			</td></tr></table>
			</div>
	</div>
</body>
</html>
		<?php exit; }else if(isset($tables) && !empty($tables)){
		check_subfolder(); 
		checkDebug();
		}
		}catch (Exception $e){
			check_subfolder(); ?>
			<!DOCTYPE html>
	<html>
	<head>
		<meta name="robots" content="noindex,nofollow" />
		<link rel="shortcut icon" href="images/favicon_new.ico"/>
		<title>Orangescrum Setup Wizard</title>
		<style>
		*{
			padding:5;
			margin:5;	
			font-family:Tahoma, Verdana;
		}
		.link:hover {
			text-decoration:underline;
		}
		h4 {
			font-size:14px;
		}
		</style>
	</head>
	<body>
		<div id="container">
			<div id="content">
			<table width="100%" align="center"><tr><td align="center">
				<table cellpadding="8" cellspacing="8" style="border:1px solid #999999;color:#000000" align="center" width="520px">
					<tr>
						<td align="center" style="border-bottom:1px solid #999999">
							<h3 style="color:#245271">4 simple steps to get started with Orangescrum</h3>
						</td>
					</tr>
					<tr>
						<td align="left" style="padding-top:10px">
							<h4>Step1: <span style="font-weight:normal;">Create a new MySQL database  named "<b>orangescrum</b>"(`utf8_unicode_ci` collation)</span></h4>
							<h4>Step2: <span style="font-weight:normal;">Update your database password in DATABASE_CONFIG section of `app/Config/database.php` page</span></h4>
							<h4>Step3: <span style="font-weight:normal;">Get the database.sql file from the root directory and import that to your database</span></h4>
							<h4 style="margin-bottom:0">Step4: <span style="font-weight:normal;">Provide the following details of SMTP configuration options in `app/Config/constants.php`</span>
							<ul>
								<li>SMTP_UNAME</li>
								<li>SMTP_PWORD</li>
								<li>FROM_EMAIL_NOTIFY</li>
								<li>SUPPORT_EMAIL</li>
							</ul></h4>
						</td>
					</tr>
					<tr>
						<td>
							<h4 style="margin:0">Note:
								<span style="font-weight:normal;">At any point you can change your database credentials in `app/Config/database.php` page.</span>
							</h4>
							<h4><span style="font-weight:normal;">For more information please visit <a href="http://www.orangescrum.org/general-installation-guide" target="_blank">Installation Guide</a>.</span></h4>
						</td>
					</tr>
					<tr>
						<td align="center">
							<h4 style="color:#FF0000;margin-top:0">Make sure that you have write permission (777) to `app/tmp` and `app/webroot` folders</h4>
						</td>
					</tr>
				</table>
				</td></tr></table>
				</div>
	</div>
</body>
</html>
	<?php
		exit;}
    }
    else{

        //The database configuration does not exist
        echo "HAS NO DATABASE CONFIGURATION WITH NAME '$dbConfigName1'";
    }
}
else{

    //The database.php file not even exist. How can this be? IMPOSIBIEBERBEL!
    echo "CANNOT FIND THE 'database.php' CONFIGURATION FILE";
}
}
?>
<?php
function check_subfolder(){
	include_once("constants.php");
	include_once(CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'Utility' . DS. 'File.php');
	include_once(CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'Utility' . DS. 'Folder.php');
	$root = dirname(dirname(dirname(__FILE__)));
	$config_dir = $root.DS.'app'.DS.'Config'.DS;
	$folders = explode(DS, $root);
	$sub_folder = $folders[count($folders) - 1].'/';
	$vhosted_folders = explode('/', $_SERVER['DOCUMENT_ROOT']);
	$vhosted_folder = $vhosted_folders[count($vhosted_folders) - 1] == ''?$vhosted_folders[count($vhosted_folders) - 2].'/':$vhosted_folders[count($vhosted_folders) - 1].'/';
	if($vhosted_folders[count($vhosted_folders) - 1] == '' && $vhosted_folder == $sub_folder){
		$sub_folder = '';
	}else if($vhosted_folders[count($vhosted_folders) - 1] != '' && $vhosted_folder == $sub_folder){
		$sub_folder = '/';
	}
	if($sub_folder != SUB_FOLDER){
		$path = $root.DS.'app'.DS.'Config'.DS.'constants.php';
		$tmppath = $root.DS.'app'.DS.'Config'.DS.'constants_tmp.php';
		$File = new File($path, true, 0777);
		$tmpfile = new File($tmppath, true, 0777);
		$originalContent = $File->read();
		$replacement = "$sub_folder";
		$newContent = str_replace('@SUB_FOLDER', $replacement, $originalContent);
		$tmpfile->write($newContent);
		if($tmpfile->copy($path,true)){
			$tmpfile->delete();
			$File->close();
		}
	}
}
function checkDebug(){
	ini_set('display_errors', 0);
	$debug = Configure::read('debug');
	if($debug == 2){
		$root = dirname(dirname(dirname(__FILE__)));
		$path = $root.DS.'app'.DS.'Config'.DS.'core.php';
		$tmppath = $root.DS.'app'.DS.'Config'.DS.'core_tmp.php';
		$File = new File($path, true, 0777);
		$TmpFile = new File($tmppath, true, 0777);
		$originalContent = $File->read('a');
		$pattern = "/Configure::write\(\'debug\',2\);/";
		$replacement = "Configure::write('debug',0);";
		$newContent = preg_replace($pattern, $replacement, $originalContent);
		$TmpFile->write($newContent);
		if($TmpFile->copy($path,true)){
			$TmpFile->delete();
			$File->close();
		}
	}
}
?>
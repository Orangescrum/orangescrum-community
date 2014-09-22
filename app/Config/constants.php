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
 
##################### Email Sending Options ############################
define('SENDGRID_USERNAME', ''); //xxxxxxxxxxxxxxx (REQUIRED)
define("SENDGRID_PASSWORD", ''); //xxxxxxxx (REQUIRED)
// Sendgrid mail sending through both API and SMPT. (Controller/Component/SendgridComponent.php)

define('FROM_EMAIL_NOTIFY', 'notify@mycompany.com'); //(REQUIRED)
define('SUPPORT_EMAIL', 'support@mycompany.com'); //(REQUIRED)

define("DEV_EMAIL", 'developer@mycompany.com'); // Developer Email ID to report the application error
define('FROM_EMAIL',  'Orangescrum<'.SUPPORT_EMAIL.'>');
define('EMAIL_SUBJ', '[Orangescrum]');

define('RELEASE',1); //Increase the release version on every CSS/JS changes to remove cache

##################### Domain and URL Constants ############################
define('SUB_FOLDER', ''); //If your application URL followed by a folder name like: http://your-site.com/folder_name, put your folder name as 'folder_name/'

if(php_sapi_name() === "cli") {
	define('PROTOCOL', "http://");
	define('DOMAIN', "www.my-orangescrum.com/"); // Please set your application domain (REQUIRED)
}else{
	$ht = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')?"https://":"http://";
	define('PROTOCOL', $ht);
	define('DOMAIN', $_SERVER['SERVER_NAME']."/");
}
define('HTTP_SERVER',PROTOCOL.DOMAIN);
define('HTTP_ROOT', HTTP_SERVER.SUB_FOLDER);
define('DOMAIN_COOKIE', $_SERVER['SERVER_NAME']);
define('HTTP_APP', PROTOCOL.DOMAIN.SUB_FOLDER);
define('HTTPS_HOME', PROTOCOL.DOMAIN.SUB_FOLDER);
define('HTTP_HOME', "http://".DOMAIN.SUB_FOLDER);

/**Require socket.io and node.js for instance messaging.**/
//define("NODEJS_HOST",'http://www.your-application.com:3002'); //enable this, if you have Node.js setup in the server

##################### Google Keys ############################
define("CLIENT_ID", "XXXXXXXXXXXX.apps.googleusercontent.com");
define("CLIENT_ID_NUM", "XXXXXXXXXXXX");
define("CLIENT_SECRET", "xXxXXxxxx_xXxXXxxxx");
define("API_KEY", "xXxXXxxxxxXXXXXXXXXXXXXxXXxxxx");
define("REDIRECT_URI", HTTP_ROOT."users/googleConnect");

define("CLIENT_ID_SIGNUP", "XXXXXXXXXXXX-xXxXXxxxxxxXXxxxx.apps.googleusercontent.com");
define("CLIENT_SECRET_SIGNUP", "xXxXXxxxx-xXxXXxxxx");
define("REDIRECT_URI_SIGNUP", HTTP_ROOT."users/googleSignup");

##################### Dropbox Key ############################
define("DROPBOX_KEY", "xXxxXxxxXx");

##################### AWS S3 Bucket ############################
define('USE_S3',0); //Set this parameter to 1 to use AWS S3 Bucket
define('BUCKET_NAME', 'Bucket Name');
define('DOWNLOAD_BUCKET_NAME', 'Download Bucket Name');
define('awsAccessKey', 'XXXXXXXXXXXXXX');
define('awsSecretKey', 'XXXX/XXXXXXXXXXXXXX/+XXXXXXXXXXXXXX');
define('DOWNLOAD_S3_TASK_PATH', 'DownloadTask/zipTask/');

define('USE_LOCAL',1); //Set this parameter to 0, to load the 3rd party JavaScript from CDN

define('DIR_CASE_FILES_S3','https://s3.amazonaws.com/'.BUCKET_NAME.'/files/case_files/');
define('DIR_USER_PHOTOS_S3','https://s3.amazonaws.com/'.BUCKET_NAME.'/files/photos/');
define('DIR_USER_PHOTOS_S3_TEMP','https://s3.amazonaws.com/'.BUCKET_NAME.'/files/temp/');
define('DIR_USER_COMPANY_S3','https://s3.amazonaws.com/'.BUCKET_NAME.'/files/company/');
define('DIR_USER_PHOTOS_S3_FOLDER','files/photos/');
define('DIR_USER_COMPANY_S3_FOLDER','files/company/');
define('DIR_CASE_FILES_S3_FOLDER','files/case_files/');
define('DIR_CASE_FILES_S3_FOLDER_TEMP','files/case_files/temp/');

define('MAX_FILE_SIZE', 200); //In Mb
define('CASE_PAGE_LIMIT', 30); // Task Listing Page
define('MILESTONE_PAGE_LIMIT', 5);
define('PROJECT_PAGE_LIMIT', 30);
define('MILE_PAGE_LIMIT', 30);
define('USER_PAGE_LIMIT', 30);
define('ARC_PAGE_LIMIT', 30);
define('MAX_SPACE_USAGE', 1024);
define('MILESTONE_PER_PAGE', 3);
define('ARC_CASE_PAGE_LIMIT',10);
define('ARC_FILE_PAGE_LIMIT',10);
define('TEMP_PROJECT_PAGE_LIMIT', 10);
define('TEMP_TASK_PAGE_LIMIT', 10);

define('TIMELIMIT',3000);

define('ONBORDING',1);
define('ONBORDING_DAILY_UPDATE',1);
define('ONBORDING_DATE','2014-01-01');

define('GMT_DATETIME', gmdate('Y-m-d H:i:s'));
define('GMT_DATE', gmdate('Y-m-d'));
define('GMT_TIME', gmdate('H:i:s'));

########## Cookie Settings ############
if(isset($_COOKIE['REMEMBER']) && $_COOKIE['REMEMBER']) {
define('COOKIE_TIME', time()+3600*24*7);
}
else {
define('COOKIE_TIME',time()+3600*2);
}
define('COOKIE_REM',time()+3600*24*30);

define('CSS_PATH', HTTP_ROOT.'css/');
define('JS_PATH', HTTP_ROOT.'js/');


//print "gggggggggggg".WWW_ROOT.$_SERVER['DOCUMENT_ROOT'];exit;
if(WWW_ROOT == 'WWW_ROOT'){
    $w_root = $_SERVER['DOCUMENT_ROOT'].'/'.SUB_FOLDER.'app/webroot/';
    define('DIR_IMAGES', $w_root.'img/');
    define('CSV_PATH', $w_root.'csv/');
    define('DOWNLOAD_TASK_PATH', $w_root.'DownloadTask/');
    define('DIR_FILES', $w_root.'files/');
    define('DIR_CASE_FILES', DIR_FILES.'case_files/');
    define('DIR_USER_PHOTOS', DIR_FILES.'photos/');
}else{
    define('DIR_IMAGES', WWW_ROOT.'img/');
    define('CSV_PATH', WWW_ROOT.'csv/');
    define('DOWNLOAD_TASK_PATH', WWW_ROOT.'DownloadTask/');
    define('DIR_FILES', WWW_ROOT.'files/');
    define('DIR_CASE_FILES', DIR_FILES.'case_files/');
    define('DIR_USER_PHOTOS', DIR_FILES.'photos/');
}
define('DIR_USER_PHOTOS_TEMP', 'files/temp/');
define('DIR_USER_PHOTOS_THUMB', 'files/thumb/');

define('HTTP_IMAGES', HTTP_ROOT.'img/');
define('HTTP_FILES', HTTP_ROOT.'files/');
define('HTTP_CASE_FILES', HTTP_FILES.'case_files/');
define('HTTP_USER_PHOTOS', HTTP_FILES.'photos/');

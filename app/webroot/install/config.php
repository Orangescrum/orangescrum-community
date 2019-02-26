<?php
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('ROOT')) {
	define('ROOT', dirname(dirname(dirname(__FILE__))));
}

 
if (!defined('APP_DIR')) {
	define('APP_DIR', basename(dirname(dirname(__FILE__))));
}

if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
	define('WWW_ROOT', dirname(__FILE__) . DS);
}
if (!defined('NEWUI_VERSION')) {
	define('NEWUI_VERSION', '1.6');
}
if (!defined('EXEUI_VERSION')) {
	define('EXEUI_VERSION', '1.0');
}

?>
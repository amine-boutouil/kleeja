<?php
/**
*
* @package install
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


// Report all errors, except notices
@error_reporting(E_ALL ^ E_NOTICE);

/**
* include important files
*/
define('IN_COMMON', true);
$_path = "../";
if(file_exists($_path . 'config.php'))
{
	include_once ($_path . 'config.php');
}

include_once ($_path . 'includes/functions.php');

switch ($db_type)
{
	case 'mysqli':
		include_once ($_path . 'includes/mysqli.php');
	break;
	default:
		include_once ($_path . 'includes/mysql.php');
}
include_once ('includes/functions_install.php');



/**
* print header
*/
if (!isset($_POST['lang']))
{
	echo gettpl('header.html');
}

if(!isset($_GET['step']))
{
	$_GET['step'] = 'language';
}

/**
* Navigation ..
*/
switch ($_GET['step']) 
{
default:
case 'language':

	if(isset($_POST['lang']) && !empty($_POST['lang']))
	{
		echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'] . '?step=choose&lang=' . htmlspecialchars($_POST['lang']) . '">';
		exit;
	}

	echo gettpl('lang.html');

break;
case 'choose' :

	$install_or_no	= $php_ver = true;

	//check version of PHP 
	if (!function_exists('version_compare') || version_compare(PHP_VERSION, MIN_PHP_VERSION, '<'))
	{
		$php_ver = false;
	}

	if(file_exists($_path . 'config.php'))
	{
		include_once $_path . 'config.php';
		if(!empty($dbuser) && !empty($dbname))
		{
			$d = inst_get_config('language');
			if(!empty($d))
			{
				$install_or_no = false;
			}
		}
	}

	echo gettpl('choose.html');
	
break;
}


/**
* print footer
*/
gettpl('footer.html');


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
#include important files
$_path = '../';
$is_there_config = false;
$db_type = 'mysqli';

define('IN_COMMON', true);
define('PATH', $_path);

if(file_exists(PATH . 'config.php'))
{
	$is_there_config = true;
	include PATH . 'config.php';
}

include PATH . 'includes/functions/functions_display.php';
include PATH . 'includes/functions/functions.php';

switch ($db_type)
{
	case 'mysqli':
		include PATH . 'includes/classes/mysqli.php';
	break;
	default:
		include PATH . 'includes/classes/mysql.php';
}
include  'includes/functions_install.php';


/**
* print header
*/
if (!isset($_POST['lang']))
{
	echo gettpl('header.html');
}

if(!isset($_GET['step']))
{
	$_GET['step'] = 'choose';
}

/**
* Navigation ..
*/
switch ($_GET['step']) 
{
default:
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
echo gettpl('footer.html');



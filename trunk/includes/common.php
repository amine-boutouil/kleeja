<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/

// not for directly open
if (!defined('IN_INDEX'))
{
	exit();
}

//we are in the common file 
define ('IN_COMMON', true);

//
//development stage;  developers stage
//
define('DEV_STAGE', true);

// Report all errors, except notices
defined('DEV_STAGE') ? @error_reporting( E_ALL ) : @error_reporting(E_ALL ^ E_NOTICE);
//Just to check
define('IN_PHP6', (version_compare(PHP_VERSION, '6.0.0-dev', '>=') ? true : false));
//filename of config.php
define('KLEEJA_CONFIG_FILE', 'config.php');


if(@extension_loaded('apc'))
{
	define('APC_CACHE', true);
}

//if sessions is started before, let's destroy it!
if(isset($_SESSION))
{
	@session_unset(); // fix bug with php4
	@session_destroy();
}

// start session
$s_time = 86400 * 2; // 2 : two days 
if(defined('IN_ADMIN'))
{
	//session_set_cookie_params($admintime);
	if (function_exists('session_set_cookie_params'))
	{
    	session_set_cookie_params($adm_time, $adm_path);
  	} 
	elseif (function_exists('ini_set'))
	{
    	ini_set('session.cookie_lifetime', $adm_time);
    	ini_set('session.cookie_path', $adm_path);
  	}
}

if(function_exists('ini_set'))
{
	if (version_compare(PHP_VERSION, '5.0.0', 'ge') && substr(PHP_OS, 0 ,3) != 'WIN')
	{
		ini_set('session.hash_function', 1);
		ini_set('session.hash_bits_per_character', 6);
	}
	ini_set('session.use_only_cookies', false);
	ini_set('session.auto_start', false);
	ini_set('session.use_trans_sid', true);
	ini_set('session.cookie_lifetime', $s_time);
	ini_set('session.gc_maxlifetime', $s_time);
	//& is not valid xhtml, so we replaced with &amp;
	ini_set('arg_separator.output', '&amp;');
	//
	//this will help people with some problem with their sessions path
	//
	//session_save_path('./cache/');
}


/**
* functions for start
*/
function kleeja_show_error($errno, $errstr = '', $errfile = '', $errline = '')
{
	switch ($errno)
	{
		case E_NOTICE: case E_WARNING: case E_USER_WARNING: case E_USER_NOTICE: case E_STRICT: break;
		default:
			header('HTTP/1.1 503 Service Temporarily Unavailable');
			echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">' . "\n<head>\n";
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
			echo '<title>Kleeja Error</title>' . "\n" . '<style type="text/css">' . "\n\t";
			echo '* { margin: 0; padding: 0; }' . "\n\t" . 'body { background: #fff;margin: 0 auto;padding: 50px;width: 767px;}' . "\n\t";
			echo '.error {color: #333;background:#ffebe8;border: 1px solid #dd3c10; padding: 10px;font-family:tahoma,arial;font-size: 12px;}' . "\n";
			echo "</style>\n</head>\n<body>\n\t" . '<div class="error">' . "\n\n\t\t<h2>Kleeja error  : </h2><br />" . "\n";
			echo "\n\t\t<strong> [ " . $errno . ':' . basename($errfile) . ':' . $errline . ' ] </strong><br /><br />' . "\n\t\t" . $errstr . "\n\t";
			echo "\n\t\t" . '<br /><br /><small>Visit <a href="http://www.kleeja.com/" title="kleeja">Kleeja</a> Website for more details.</small>' . "\n\t";
			echo "</div>\n</body>\n</html>";
			global $SQL;
			if(isset($SQL))
			{
				@$SQL->close();
			}
			exit;
		break;
    }
}
set_error_handler('kleeja_show_error');

function stripslashes_our($value)
{
	return is_array($value) ? array_map('stripslashes_our', $value) : stripslashes($value);  
}
function kleeja_clean_string($value)
{
	if(is_array($value))
	{
		return array_map('kleeja_clean_string', $value);
	}
	$value = str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $value);
	//$value = preg_replace('/[\x80-\xFF]/', '?', $value); //allow only ASCII (0-127)
	return $value;
}
//unsets all global variables set from a superglobal array
function unregister_globals() 
{
	$register_globals = @ini_get('register_globals');
	if ($register_globals === "" || $register_globals === "0" || strtolower($register_globals) === "off")
	{
		return;
	}

	if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS']))
	{
		exit('Kleeja is queen of candies ...');
	}

	$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
	$no_unset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
	
	foreach ($input as $k => $v)
	{
		if (!in_array($k, $no_unset) && isset($GLOBALS[$k]))
		{
			unset($GLOBALS[$k]);
			unset($GLOBALS[$k]);//make sure
		}
	}
		
	unset($input);
}
//time of start and end and wutever
function get_microtime()
{
	list($usec, $sec) = explode(' ', microtime());	return ((float)$usec + (float)$sec);
}
//is bot ?
function is_bot($bots = array('googlebot', 'yahoo' ,'msnbot'))
{
	return preg_match('/(' . implode('|', $bots) . ')/i', ($_SERVER['HTTP_USER_AGENT'] ? 	$_SERVER['HTTP_USER_AGENT'] : @getenv('HTTP_USER_AGENT'))) ? true : false;
}

$IS_BOT = is_bot();
$starttm = get_microtime();


//Kill globals varibles
unregister_globals();

if(!is_bot())
{
	@session_name('sid');
	@session_start();
}

//try close it
if (@get_magic_quotes_runtime())
{
	@set_magic_quotes_runtime(0);
}

if(@get_magic_quotes_gpc())
{
	$_GET	= stripslashes_our($_GET); 
	$_POST	= stripslashes_our($_POST);
	$_COOKIE	= stripslashes_our($_COOKIE); 
	$_REQUEST	= stripslashes_our($_REQUEST);//we use this sometime
}

//clean string and remove bad chars
$_GET		= kleeja_clean_string($_GET);
$_POST		= kleeja_clean_string($_POST);
$_REQUEST	= kleeja_clean_string($_REQUEST);
$_COOKIE	= kleeja_clean_string($_COOKIE);


//path 
if(!defined('PATH'))
{
	define('PATH', './');
}

// no config
if (!file_exists(PATH . KLEEJA_CONFIG_FILE))
{
	header('Location: ' . PATH . 'install/index.php');
	exit;
}

// there is a config
require (PATH . KLEEJA_CONFIG_FILE);

//no enough data
if (!$dbname || !$dbuser)
{
	header('Location: ' . PATH . 'install/index.php');
	exit;
}

//include files .. & classes ..
//$path = dirname(__file__) . '/';
$root_path = PATH;
$adminpath = isset($adminpath) ? $adminpath : './admin/index.php';
!defined('ADMIN_PATH') ? define('ADMIN_PATH', $adminpath) : null;
$db_type = isset($db_type) ? $db_type : 'mysql';

require (PATH . 'includes/functions_alternative.php');
require (PATH . 'includes/version.php');

switch ($db_type)
{
	case 'mysqli':
		require (PATH . 'includes/mysqli.php');
	break;
	default:
		require (PATH . 'includes/mysql.php');
}
require (PATH . 'includes/style.php');
require (PATH . 'includes/KljUploader.php');
require (PATH . 'includes/usr.php');
require (PATH . 'includes/pager.php');
require (PATH . 'includes/functions.php');
require (PATH . 'includes/functions_display.php');
	
//fix intregation problems
if(empty($script_encoding))
{
	$script_encoding = 'widnows-1256';
}

// start classes ..
$SQL	= new SSQL($dbserver, $dbuser, $dbpass, $dbname);
//no need after now 
unset($dbpass);
$tpl	= new kleeja_style;
$kljup	= new KljUploader;
$usrcp	= new usrcp;

//then get caches
require (PATH . 'includes/cache.php');

//check user or guest
$usrcp->kleeja_check_user();


//no tpl caching in dev stage  
if(defined('DEV_STAGE'))
{
	$tpl->caching = false;
}

//check if admin (true/false)
$is_admin = $usrcp->admin();

//kleeja session id
$klj_session = $SQL->escape(session_id());

// for gzip : php.net
//fix bug # 181
//we stopped this in development stage cuz it's will hide notices
$do_gzip_compress = false; 
if ($config['gzip'] == '1' && !defined('IN_DOWNLOAD') && !defined('IN_ADMIN') && !defined('DEV_STAGE') && !defined('IN_SUBMIT_UPLOADING')) 
{
	function compress_output($output)
	{
		return gzencode($output, 5, FORCE_GZIP);
	}
		
	// Check if the browser supports gzip encoding, HTTP_ACCEPT_ENCODING
	if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false && !headers_sent() && @extension_loaded('zlib') && !defined('IN_DOWNLOAD'))
	{
		$do_gzip_compress = true; 
		// Start output buffering, and register compress_output()
		if(function_exists('gzencode') )
		{
			@ob_start("compress_output");
		}
		else
		{
			@ob_start();
		}
			
		// Tell the browser the content is compressed with gzip
		header("Content-Encoding: gzip");
	}
}

// header .
header('Content-type: text/html; charset=UTF-8');	
header('Cache-Control: private, no-cache="set-cookie"');
header('Expires: 0');
header('Pragma: no-cache');	

//check lang
if(!$config['language'] || empty($config['language']))
{
	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strlen($_SERVER['HTTP_ACCEPT_LANGUAGE']) > 2)
	{
		$config['language'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		if(!file_exists(PATH . 'lang/' . $config['language'] . '/common.php'))
		{
			$config['language'] = 'en';
		}
	}
}

//check style
if(!$config['style'] || empty($config['style']))
{
	$config['style'] = 'default';
}

//check h_kay, important for kleeja
if(empty($config['h_key']))
{
	$h_k = sha1(microtime() . rand(1000,9999));
	if(!update_config('h_key', $h_k))
	{
		add_config('h_key', $h_k);
	}
}

//Global vars for Kleeja
$STYLE_PATH = PATH . 'styles/' . (trim($config['style_depend_on']) == '' ? $config['style'] : $config['style_depend_on']) . '/';
$STYLE_PATH_ADMIN  = PATH . 'admin/admin_style/';
$THIS_STYLE_PATH = PATH . 'styles/' . $config['style'] . '/';
	
//get languge of common
get_lang('common');
//ban system 
get_ban();


//install.php exists
if (file_exists(PATH . 'install') && !defined('IN_ADMIN') && !defined('IN_LOGIN') && !defined('DEV_STAGE')) 
{
	kleeja_info($lang['WE_UPDATING_KLEEJA_NOW'], $lang['SITE_CLOSED']);
}

//site close ..
$login_page = '';
if ($config['siteclose'] == '1' && !$usrcp->admin() && !defined('IN_LOGIN') && !defined('IN_ADMIN'))
{
	//if download, images ?
	if(defined('IN_DOWNLOAD') && (isset($_GET['img']) || isset($_GET['thmb']) || isset($_GET['thmbf']) || isset($_GET['imgf'])))
	{
		@$SQL->close();
		$fullname = "images/not_exists.jpg";
		$filesize = filesize($fullname);
		header("Content-length: $filesize");
		header("Content-type: image/jpg");
		readfile($fullname);
		exit;
	}

	// Send a 503 HTTP response code to prevent search bots from indexing the maintenace message
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	kleeja_info($config['closemsg'], $lang['SITE_CLOSED']);
}

//exceed total size 
if (($stat_sizes >= ($config['total_size'] *(1048576))) && !defined('IN_LOGIN') && !defined('IN_ADMIN'))// convert megabytes to bytes
{ 
	// Send a 503 HTTP response code to prevent search bots from indexing the maintenace message
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	kleeja_info($lang['SIZES_EXCCEDED'], $lang['STOP_FOR_SIZE']);
}

//calculate  onlines ...  
if ((int) $config['allow_online'] == '1' && defined('IN_REAL_INDEX'))
{
	//it's only work in index page .. so we reduce some loads
	KleejaOnline();
}


//check for page numbr
if(empty($perpage) || intval($perpage) == 0)
{
	$perpage = 14;
}

//site url must end with /
if($config['siteurl'])
{
	$config['siteurl'] = ($config['siteurl'][strlen($config['siteurl'])-1] != '/') ? $config['siteurl'] . '/' : $config['siteurl'];
}

//captch file 
$captcha_file_path = $config['siteurl'] . 'includes/captcha.php';

//clean files
if((int) $config['del_f_day'] > 0 && PATH == './')
{
	klj_clean_old_files($config['klj_clean_files_from']);
}

($hook = kleeja_run_hook('end_common')) ? eval($hook) : null; //run hook

#<-- EOF

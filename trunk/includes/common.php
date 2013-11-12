<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


/**
* @ignore
*/
if (!defined('IN_KLEEJA'))
{
	exit();
}

/**
 * Files in includes folder need this to be accessible
 */
define('IN_COMMON', true);


/**
 * Development stage, KLeeja will treats you as a developer
 */
define('DEV_STAGE', true);

/**
 * Error reporting in Development stage are agressive
 */
defined('DEV_STAGE') ? @error_reporting(E_ALL) : @error_reporting(E_ALL ^ E_NOTICE);

/**
 * The path of the configuration file of Kleeja
 */
define('KLEEJA_CONFIG_FILE', 'config.php');


/**
 * @ignore
 */
if(!defined('PATH'))
{
	if(!defined('__DIR__'))
	{
		define('__DIR__', dirname(__FILE__)); 
	}
	define('PATH', str_replace(DIRECTORY_SEPARATOR . 'includes', '', __DIR__) . DIRECTORY_SEPARATOR);
}


#start session after setting it right
$s_time = 86400 * 2; // 2 : two days 
if(function_exists('ini_set'))
{
	if (version_compare(PHP_VERSION, '5.0.0', 'ge') && substr(PHP_OS, 0 ,3) != 'WIN')
	{
		ini_set('session.hash_function', 1);
		ini_set('session.hash_bits_per_character', 6);
	}
	ini_set('session.use_only_cookies', true);
	ini_set('session.cookie_httponly', true);
	ini_set('session.use_trans_sid', false);
	ini_set('session.cookie_lifetime', $s_time);
	ini_set('session.gc_maxlifetime', $s_time);
	//& is not valid xhtml, so we replaced with &amp;
	ini_set('arg_separator.output', '&amp;');
	
	#session of upload progress
	ini_set('session.upload_progress.enabled', true);
}

@session_name('sid');
@session_start();


/**
* Get the current microtime, to calculate page speed
*/
function get_microtime()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$usec + (float)$sec);
}

$starttm = get_microtime();



#if no configuration file exists? then go installation
if (!file_exists(PATH . KLEEJA_CONFIG_FILE))
{
	header('Location: ./install/index.php');
	exit;
}

#load Kleeja configuration file
include PATH . KLEEJA_CONFIG_FILE;

#if no enough config. params, go installation
if (!$dbname || !$dbuser)
{
	header('Location: ./install/index.php');
	exit;
}

#initiate classes and load functions
$root_path = PATH;
$db_type = isset($db_type) ? $db_type : 'mysqli';

include PATH . 'includes/functions/functions_alternative.php';
include PATH . 'includes/version.php';

switch ($db_type)
{
	default:
	case 'mysqli':
		include PATH . 'includes/classes/mysqli.php';
	break;
}
include PATH . 'includes/classes/style.php';
include PATH . 'includes/classes/user.php';
include PATH . 'includes/classes/pagination.php';
include PATH . 'includes/classes/cache.php';
include PATH . 'includes/functions/functions.php';
include PATH . 'includes/functions/functions_display.php';

if(defined('IN_ADMIN'))
{
	include PATH . 'includes/functions/functions_adm.php';
}


#fix intregation problems
if(empty($script_encoding))
{
	$script_encoding = 'windows-1256';
}

#initiate classes
$SQL	= new database($dbserver, $dbuser, $dbpass, $dbname);
unset($dbpass);
$tpl	= new kleeja_style;
$usrcp = $user	= new user;
$cache = new cache;


#return to the default user system if this given
if(defined('DISABLE_INTR'))
{
	$config['user_system'] = 1;
}

#load cached data
include PATH . 'includes/cache_data.php';


#getting dynamic configs
$query = array(
				'SELECT'	=> 'c.name, c.value',
				'FROM'		=> "{$dbprefix}config c",
				'WHERE'		=> 'c.dynamic = 1',
			);

$result = $SQL->build($query);

while($row=$SQL->fetch($result))
{
	$config[$row['name']] = $row['value'];
}

$SQL->free($result);

#check user or guest
$usrcp->kleeja_check_user();

#+ configs of the current group
$config = array_merge($config, (array) $d_groups[$user->data['group_id']]['configs']);



#no tpl caching in dev stage  
if(defined('DEV_STAGE'))
{
	$tpl->caching = false;
}

#admin path
!defined('ADMIN_PATH') ? define('ADMIN_PATH', $config['siteurl'] . 'admin/index.php') : null;

#Admin style name
!defined('ADMIN_STYLE_NAME') ? define('ADMIN_STYLE_NAME', 'marya') : null;

#site url must end with /
if(!empty($config['siteurl']))
{
	$config['siteurl'] = ($config['siteurl'][strlen($config['siteurl'])-1] != '/') ? $config['siteurl'] . '/' : $config['siteurl'];
}


#set display headers
header('Content-type: text/html; charset=UTF-8');	
header('Cache-Control: private, no-cache="set-cookie"');
header('Expires: 0');
header('Pragma: no-cache');	

#check the current laguage package
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

#check the current style
if(!$config['style'] || empty($config['style']))
{
	$config['style'] = 'default';
}

#check h_kay, important for kleeja
if(empty($config['h_key']))
{
	$h_k = sha1(microtime() . rand(0, 100));
	if(!update_config('h_key', $h_k))
	{
		add_config('h_key', $h_k);
	}
}

$config['style'] = 'default2';

#style of Kleeja
define('STYLE_PATH', $config['siteurl'] . 'styles/' . $config['style'] . '/');
define('STYLE_PATH_ABS', PATH . 'styles/' . $config['style'] . '/');
define('PARENT_STYLE_PATH', $config['siteurl'] . 'styles/' . (trim($config['style_depend_on']) == '' ? $config['style'] : $config['style_depend_on']) . '/');
define('PARENT_STYLE_PATH_ABS', PATH . 'styles/' . (trim($config['style_depend_on']) == '' ? $config['style'] : $config['style_depend_on']) . '/');

#style for admin
define('ADMIN_STYLE_PATH', $config['siteurl'] . 'admin/' . ADMIN_STYLE_NAME . '/');
define('ADMIN_STYLE_PATH_ABS', PATH . 'admin/' . ADMIN_STYLE_NAME . '/');

#get languge of common
get_lang('common');

#ban system 
get_ban();


#install.php exists, raise a message 
if (file_exists(PATH . 'install') && !defined('IN_ADMIN') && !defined('IN_LOGIN') && !defined('DEV_STAGE')) 
{
	#Different message for admins! delete install folder 
	kleeja_info((user_can('enter_acp') ? $lang['DELETE_INSTALL_FOLDER'] : $lang['WE_UPDATING_KLEEJA_NOW']), $lang['SITE_CLOSED']);
}


#site close message if enabled
$login_page = '';
if ($config['siteclose'] == '1' && !user_can('enter_acp') && !defined('IN_LOGIN') && !defined('IN_ADMIN'))
{
	#if download, images ?
	if(defined('IN_DOWNLOAD') && (ig('img') || ig('thmb') || ig('thmbf') || ig('imgf')))
	{
		@$SQL->close();
		$fullname = "images/site_closed.jpg";
		$filesize = filesize($fullname);
		header("Content-length: $filesize");
		header("Content-type: image/jpg");
		readfile($fullname);
		exit;
	}

	#Send a 503 HTTP response code to prevent search bots from indexing the maintenace message
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	kleeja_info($config['closemsg'], $lang['SITE_CLOSED']);
}

#exceed total size 
if (($stat_sizes >= ($config['total_size'] *(1048576))) && !defined('IN_LOGIN') && !defined('IN_ADMIN'))
{ 
	// Send a 503 HTTP response code to prevent search bots from indexing the maintenace message
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	kleeja_info($lang['SIZES_EXCCEDED'], $lang['STOP_FOR_SIZE']);
}


#check for rows per page number
if(empty($perpage) || intval($perpage) == 0)
{
	$perpage = 14;
}

#captcha file path
$captcha_file_path = $config['siteurl'] . 'captcha.php';


($hook = kleeja_run_hook('end_common')) ? eval($hook) : null; //run hook


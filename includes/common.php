<?php
##################################################
#						Kleeja 
#
# Filename : common.php 
# purpose :  all things came from here ..:
# copyright 2008-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author$ , $Rev$,  $Date::                           $
##################################################

		
	// not for directly open
	if (!defined('IN_INDEX'))
	{
		exit('no directly opening : ' . __file__);
	}

	//we are in the common file 
	define ('IN_COMMON', true);
	
	//
	//development stage;  developers stage
	//
	define('DEV_STAGE', true);
		 
		
	// Report all errors, except notices
	defined('DEV_STAGE') ? @error_reporting(E_ALL) : @error_reporting(E_ALL ^ E_NOTICE);


	// start session
	$s_time = 86400 * 2; // 2 : two days 
	$s_key = (!empty($_SERVER['REMOTE_ADDR'])) ? strtolower($_SERVER['REMOTE_ADDR']) : ((!empty($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : @getenv('SERVER_NAME'));
	$s_key .= (!empty($_SERVER['HTTP_USER_AGENT'])) ? strtolower($_SERVER['HTTP_USER_AGENT']) : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : @getenv('SERVER_NAME'));
	$s_sid = 'klj_' . substr('_' . md5($s_key), 0, 8);
	session_set_cookie_params($s_time);
	//this will help people with some problem with their sessions path
	//session_save_path('./cache/');
	session_name($s_sid);
	session_start();

	if (isset($_COOKIE[$s_sid]))
	{
		setcookie($s_sid, $_COOKIE[$s_sid], time() + $s_time, "/");
	}
	
	function stripslashes_our(&$value)
	{
		return is_array($value) ? array_map('stripslashes_our', $value) : stripslashes($value);  
	} 
	//unsets all global variables set from a superglobal array
	function unregister_globals() 
	{ 
		foreach (func_get_args() as $name)
		{
			foreach ($GLOBALS[$name] as $key=>$value)
			{
				if (isset($GLOBALS[$key]))
				{
					unset($GLOBALS[$key]);
				}
			}
		}
	}

	if (@ini_get('register_globals'))
	{
		unregister_globals('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES'); 
	}
	
	if( (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || 
		(@ini_get('magic_quotes_sybase') && (strtolower(@ini_get('magic_quotes_sybase')) != "off")) )
	{ 
		stripslashes_our($_GET); 
		stripslashes_our($_POST); 
		stripslashes_our($_COOKIE); 
	}
		
	
	//time of start and end and wutever
	function get_microtime()
	{
		list($usec, $sec) = explode(' ', microtime());	return ((float)$usec + (float)$sec);
	}
	
	$starttm = get_microtime();

	 
	// no config
	if (!file_exists('config.php'))
	{
		header('Location: ./install/index.php');
		exit;
	}
	
	// there is a config
	require ('config.php');
	
	//no enough data
	if (!$dbname || !$dbuser)
	{
		header('Location: ./install/index.php');
		exit;
	}
	
	//include files .. & classes ..
	$path =	dirname(__FILE__) . DIRECTORY_SEPARATOR;
	$root_path	=	'./';
	require ($path . 'style.php');
	require ($path . 'mysql.php');
	require ($path . 'KljUploader.php');
	require ($path . 'usr.php');
	require ($path . 'pager.php');
	require ($path . 'ocr_captcha.php');
	require ($path . 'functions.php');

	//. install.php exists
	if (file_exists($root_path . 'install') && !defined('IN_ADMIN') && !defined('IN_LOGIN') && !defined('DEV_STAGE'))
	{
		big_error('install folder exists!', '<b>Install</b> folder detected! please delete it OR install <b>Kleeja</b> if you haven\'t done so yet...<br/><br/><a href="'.$root_path.'install">Click to Install</a><br/><br/>');
	}
	

     
	// start classes ..
	$SQL	= new SSQL($dbserver, $dbuser, $dbpass, $dbname);
	$tpl	= new kleeja_style;
	$kljup	= new KljUploader;
	$usrcp	= new usrcp;			
	
	//no need after now 
	unset($dbpass);

	//then get caches
	require ($path . 'cache.php');
	
	//no tpl caching in dev stage  
	if(defined('DEV_STAGE'))
	{
		$tpl->caching = false;
	}
	
	//check if admin (true/false)
	$is_admin = $usrcp->admin();
	
	// for gzip : php.net
	//fix bug # 181
	$do_gzip_compress = false; 
	if ($config['gzip'] == '1' && !defined('IN_DOWNLOAD') && !defined('IN_ADMIN')) 
	{
	    function compress_output($output)
		{
			return gzencode($output, 5, FORCE_GZIP);
		}
		
	    // Check if the browser supports gzip encoding, HTTP_ACCEPT_ENCODING
	    if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false && !headers_sent() && @extension_loaded('zlib'))
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

	// ...header ..  i like it ;)
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
			if(!file_exists($root_path . 'lang/' . $config['language'] . '/common.php'))
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
	
	$STYLE_PATH = $root_path . 'styles/' . $config['style'] . '/';
	$STYLE_PATH_ADMIN  =  $root_path  . 'includes/admin_style/';
	
	//get languge of common
	get_lang('common');
	
	//ban system 
	get_ban();
	
	//some languages have copyrights !
	$S_TRANSLATED_BY = false;
	if(isset($lang['S_TRANSLATED_BY']) && strlen($lang['S_TRANSLATED_BY']) > 2)
	{
		$S_TRANSLATED_BY = true;
	}
	
	//site close ..
	$login_page = '';
	if ($config['siteclose'] == '1' && !$usrcp->admin() && !defined('IN_LOGIN') && !defined('IN_ADMIN'))
	{
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
	if ($config['allow_online'] == '1')
	{
		KleejaOnline();
	}
	
	// claculate for counter ..
	 // of course , its not printable function , its just for calculating :)
	//visit_stats();
	
	//check for page numbr
	if(empty($perpage) || intval($perpage) == 0)
	{
		$perpage = 10;
	}
	
	//site url must end with /
	if($config['siteurl'])
	{
		$config['siteurl'] = ($config['siteurl'][strlen($config['siteurl'])-1] != '/') ? $config['siteurl'] . '/' : $config['siteurl'];
	}
	
	
	//clean files
	if((int) $config['del_f_day'] > 0)
	{
		klj_clean_old_files($config['klj_clean_files_from']);
	}

	($hook = kleeja_run_hook('end_common')) ? eval($hook) : null; //run hook

?>

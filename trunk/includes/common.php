<?php
##################################################
#						Kleeja 
#
# Filename : common.php 
# purpose :  all things came from here ..:
# copyright 2007-2008 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : saanina
##################################################

		
	// not for directly open
	if (!defined('IN_INDEX'))
	{
		exit('no directly opening : ' . __file__);
	}
		  
	//we are in the common file 
	define ('IN_COMMON' , true);
		 
		 
	// Report all errors, except notices
	error_reporting(E_ALL ^ E_NOTICE);

	$expireTime = 60*60*24*7; // 7 days
	session_set_cookie_params($expireTime);
	// start session
	session_start();


	//time of start and end and wutever
	function get_microtime()
	{
		list($usec, $sec) = explode(' ', microtime());	return ((float)$usec + (float)$sec);
	}
	
	$starttm = get_microtime();

	//php must be newer than this
	 if (phpversion() < '4.1.0') exit('Your php version is too old !');
	 
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
	$path		=	dirname(__FILE__) . DIRECTORY_SEPARATOR;
	$root_path	=	'./';
	require ($path . 'style.php');
	require ($path . 'mysql.php');
	require ($path . 'KljUploader.php');
	require ($path . 'usr.php');
	require ($path . 'pager.php');
	require ($path . 'ocr_captcha.php');
	require ($path . 'functions.php');

	//. install.php exists
	if (file_exists($root_path . 'install')) 
	{
		//big_error('install folder exists!', '<b>Install</b> folder detected! please delete it OR install <b>Kleeja</b> if you haven\'t done so yet...<br/><br/><a href="'.$root_path.'install">Click to Install</a><br/><br/>');
	}

	//gd 
	if(!function_exists('imagecreatetruecolor'))
	{
		big_error('No GD Library!', '<b>imagecreatetruecolor</b> function Doesnt exists , That mean GD is disabled or it\'s very very old. <br/> If you don\'t want this feature, then delete this error from file <i>'. __file__ . '</i> in line </i>' . __line__ .'</i>');
	}
	

     
	// start classes ..
	$SQL	= new SSQL($dbserver, $dbuser, $dbpass, $dbname);
	$tpl	= new kleeja_style;		# Depend on easytemplate::daif
	$kljup	= new KljUploader;		#  Depend on Nadorino class
	$usrcp	= new usrcp;			
	
	//no need after now 
	unset($dbpass);


	//then get
	require ($path . 'cache.php');
	
	// ...header ..  i like it ;)
	header('Content-type: text/html; charset=UTF-8');
	header('Cache-Control: private, no-cache="set-cookie"');
	header('Expires: 0');
	header('Pragma: no-cache');	
	
	// for gzip : php.net
	$do_gzip_compress = false; 
	if ($config['gzip'] == '1') 
	{ 
	    function compress_output($output) {return gzencode($output,5, FORCE_GZIP);}
	    // Check if the browser supports gzip encoding, HTTP_ACCEPT_ENCODING
	    if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') || strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip'))
		{
			$do_gzip_compress = true; 
	        // Start output buffering, and register compress_output()
	        ob_start("compress_output");
	        // Tell the browser the content is compressed with gzip
	        header("Content-Encoding: gzip");
	    }
	}

	
	//ban system 
	get_ban();
	
	
	//anti floods  system
	// we will improve it in the future .. 
	//if ( $usrcp->admin() === false )
	//{
		//antifloods(7, 700); // (number of floods, per seconds ) 
	//}
	
	//site close ..
	$login_page = '';
	if ($config['siteclose'] == '1' && !$usrcp->admin() &&  $_GET['go']!='login' && $_GET['go']!='logout' && !defined('IN_ADMIN'))
	{
		// Send a 503 HTTP response code to prevent search bots from indexing the maintenace message
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		kleeja_info($config['closemsg'], $lang['SITE_CLOSED']);
	}
	
	//exceed total size 
	if (($stat_sizes >= ($config['total_size'] *(1048576))) && $_GET['go']!='login' && $_GET['go']!='logout' && !defined('IN_ADMIN'))// convert megabytes to bytes
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
	visit_stats();
	
	//check for page numbr
	if(!$perpage || intval($perpage)==0)
	{
		$perpage = 10;
	}
	
	//site url must end with /
	if($config['siteurl'])
	{
		$config['siteurl'] = ($config['siteurl'][strlen($config['siteurl'])-1] != '/') ? $config['siteurl'] . '/' : $config['siteurl'];
	}
	
	//some languages have copyrights !
	$S_TRANSLATED_BY = false;
	if(isset($lang['S_TRANSLATED_BY']) && strlen($lang['S_TRANSLATED_BY']) > 2)
	{
		$S_TRANSLATED_BY = true;
	}
	
	($hook = kleeja_run_hook('end_common')) ? eval($hook) : null; //run hook

?>

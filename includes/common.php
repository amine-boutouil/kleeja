<?php
##################################################
#						Kleeja 
#
# Filename : common.php 
# purpose :  all things came from here ..:
# copyright 2007-2008 Kleeja.com ..
# last edit by : saanina
##################################################

	// start session
	session_start();
		
	// not for directly open
	 if (!defined('IN_INDEX'))
	{
		exit();
	}
		  
	//
	//we are in the common file 
	//
	define ('IN_COMMON' , true);
		 
		 
	// Report all errors, except notices
	error_reporting(E_ALL ^ E_NOTICE);

	//time of start and end and wutever
	function get_microtime(){	list($usec, $sec) = explode(' ', microtime());	return ((float)$usec + (float)$sec);	}
	
	$starttm	=	get_microtime();

	
	//php must be newer than this
	 if (phpversion() < '4.1.0') exit('Your php version is too old !');
	 
	 
	//include files .. & classes ..
	$path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

	require ($path.'config.php');
	require ($path.'style.php');
	require ($path.'mysql.php');
	require ($path.'KljUploader.php');
	require ($path.'usr.php');
	require ($path.'pager.php');
	require ($path.'ocr_captcha.php');
	require ($path.'functions.php');
	
	
	//no data.. install.php exists
	if (!$dbname || !$dbuser)
	{
		big_error('No Data In config.php !', 'Edit the information  in <i>config.php</i> OR install kleeja if you haven\'t done so yet...<br/><br/><a href="./install">Click to Install</a><br/><br/>');
	}
	elseif (file_exists('./install')) 
	{
		big_error('install folder exists!', '<b>Install</b> folder detected! please delete it OR install kleeja if you haven\'t done so yet...<br/><br/><a href="./install">Click to Install</a><br/><br/>');
	}

	//gd 
	if(!function_exists('imagecreatetruecolor'))
	{
		big_error('No GD !', '<b>imagecreatetruecolor</b> function Doesnt exists , That mean GD is disabled or it\'s very very old. <br/> If you dont want this feature, then delete this error from file <i>'. __file__ . '</i> in line </i>' . __line__ .'</i>');
	}
     
	// start classes ..
	$SQL	= new SSQL($dbserver,$dbuser,$dbpass,$dbname);
	$tpl	= new kleeja_style;		# Depend on easytemplate::daif
	$kljup	= new KljUploader;		#  Depend on Nadorino class
	$usrcp	= new usrcp;			
	
	//no need after now 
	unset($dbpass);


	//then get
	require ($path.'cache.php');
	
	// for gzip
	$do_gzip_compress = false; 
	if ($config['gzip']) 
	{ 
		if (@extension_loaded('zlib'))
		{
			$do_gzip_compress = true; 
			ob_start('ob_gzhandler');
		}
	
	} ## end gzip 
	

	
	// ...header ..  i like it ;)
	header('Content-type: text/html; charset=UTF-8');
	header('Cache-Control: private, no-cache="set-cookie"');
	header('Expires: 0');
	header('Pragma: no-cache');	
	

	
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
	if ($config['siteclose'] == 1 && !$usrcp->admin() &&  $_GET['go']!='login' && $_GET['go']!='logout' && !IN_ADMIN)
	{
		kleeja_info($config['closemsg'],$lang['SITE_CLOSED']);
	}
	
	//exceed total size 
	if (($stat_sizes >= ($config['total_size'] *(1048576))) && $_GET['go']!='login' && $_GET['go']!='logout' && !IN_ADMIN)// convert megabytes to bytes
	{ 
		kleeja_info($lang['SIZES_EXCCEDED'],$lang['STOP_FOR_SIZE']);
	}
	
	//calculate  onlines ...  
	if ($config['allow_online'] == 1)
	{
		KleejaOnline();
	}
	
	// claculate for counter ..
	 // of course , its not printable function , its just for calculating :)
	visit_stats();
	
	

	($hook = kleeja_run_hook('end_common')) ? eval($hook) : null; //run hook

?>
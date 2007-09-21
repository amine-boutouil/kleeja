<?
##################################################
#						Kleeja 
#
# Filename : common.php 
# purpose :  all things came from here ..:
# copyright 2007 Kleeja.com ..
#
##################################################

	// start session
	session_start();
	
	// for security ..
	  if (!defined('IN_INDEX'))
	  {
	  echo '<strong><br /><span style="color:red">[NOTE]: This Is Dangrous Place !! [2007 saanina@gmail.com]</span></strong>';
	  exit();
	  }
	 define ( 'IN_COMMON' , true);
	 
	 
// Report all errors, except notices
error_reporting(E_ALL ^ E_NOTICE);


    // support for php older than 4.1.0 
    if ( phpversion() < '4.1.0' ){
        $_GET 			= $HTTP_GET_VARS;
        $_POST 			= $HTTP_POST_VARS;
        $_COOKIE 		= $HTTP_COOKIE_VARS;
        $_SESSION		= $HTTP_SESSION_VARS;
        $_SERVER 		= $HTTP_SERVER_VARS;
		$_ENV 			= $HTTP_ENV_VARS;
		$_FILES 		= $HTTP_POST_FILES;
     }
	  
	##  important process
	if (!@ini_get('register_globals')) {  
		extract($_GET);  
	    extract($_POST);  
		extract($_COOKIE); 
		extract($_SESSION); 
		extract($_SERVER);  
		extract($_FILES);  
	}  
	  
	  
	//include files .. & classes ..
	$path = dirname(__FILE__).'/';

	include ($path.'config.php');
	include ($path.'easytemplate.php');
	include ($path.'mysql.php');
	include ($path.'class.AksidSars.php');
	include ($path.'usr.php');
	
	// start classes ..
	$SQL	= new SSQL;				# Author : MaaSTaaR & me
	$tpl	= new EasyTemplate;		# Author : daif
	$tahmil	= new AksidSars;		# Author : Nadorino
	$usrcp	= new usrcp;			# Author : saanina
	
	//no data
	if (!$dbserver && !$dbname) {echo 'يبدو أنك لم تقم بتثبيت السكربت !!'; exit();}
	
	//some of classes need .. 
	#connect     
    $SQL->setinfo($dbserver,$dbuser,$dbpass,$dbname);
    $SQL->connect();
    $SQL->selectdb();
	unset($dbpass); // We do not need this any longer, unset for safety purposes

	//get caches .. 
	require ($path.'cache.php');
	
	// for gzip
	$do_gzip_compress = FALSE; 
	if ( $config[gzip] ) 
	{ 
	   $phpver = phpversion(); 

	   $useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT; 

	   if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) ) 
	   { 
	      if ( extension_loaded('zlib') ) 
	      { 
	         ob_start('ob_gzhandler'); 
	      } 
	   } 
	   else if ( $phpver > '4.0' ) 
	   { 
	      if ( strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') ) 
	      { 
	         if ( extension_loaded('zlib') ) 
	         { 
	            $do_gzip_compress = TRUE; 
	            ob_start(); 
	            ob_implicit_flush(0); 

	            header('Content-Encoding: gzip'); 
	         } 
	      } 
	   } 
	} ## end gzip 
	
	
	# specify folder of style
	$tpl->Temp = "styles/".$config['style'];
	$tpl->Cache = "cache";
	$stylepath = $tpl->Temp;
	
	
	// ...header ..  i like it ;)
	header('Content-type: text/html; charset=UTF-8');
	header('Cache-Control: private, no-cache="set-cookie"');
	header('Expires: 0');
	header('Pragma: no-cache');	
	
	
	//site close ..
	if ($config[siteclose] == 1) {
	Saaheader("الموقع مغلق !");
	$text = $config[closemsg];
	 print $tpl->display("info.html");
	Saafooter();
	exit();
	}
?>
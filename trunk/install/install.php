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


//
// Kleeja must be safe ..
//
if(!empty($dbuser) && !empty($dbname) && !(isset($_GET['step']) && in_array($_GET['step'], array('plugins', 'end', 'wizard'))))
{
	$d = inst_get_config('language');
	if(!empty($d))
	{
		header('Location: ../');
		exit;
	}
}

if(!isset($_GET['step']))
{
	//if anyone request this file directly without passing index.php we will return him to index.php
	header('Location: index.php');
}

/**
* Print header
*/
if(isset($_POST['dbsubmit']) && !is_writable($_path))
{
	// soon
}
else
{
	echo gettpl('header.html');
}



/*
//nvigate ..
*/
switch ($_GET['step']) 
{
default:
case 'license':

	$contentof_license = @file_get_contents('../docs/license.txt');
	if (strlen($contentof_license) < 3)
	{
		$contentof_license = "license.txt is empty or not found, got to Kleeja.com and read the license content from there ...";
	}

	echo gettpl('license.html');

break;

case 'f':

	$check_ok = true;
	$advices = $register_globals = $get_magic_quotes_gpc = $iconv = false;
	
	if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
	{
		$register_globals = true;
	}
	if( (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || 
	(@ini_get('magic_quotes_sybase') && (strtolower(@ini_get('magic_quotes_sybase')) != "off")) )
	{
		$get_magic_quotes_gpc = true;
	}
	if (!function_exists('iconv'))
	{
		$iconv = true;
	}

	if($register_globals || $get_magic_quotes_gpc || $iconv)
	{
		$advices = true;
	}

	echo gettpl('check.html');

break;
case 'c':
	
	// after submit, generate config file
	if(isset($_POST['dbsubmit']))
	{
		//lets do it
		do_config_export(
						$_POST['db_type'],
						$_POST['db_server'],
						$_POST['db_user'],
						$_POST['db_pass'],
						$_POST['db_name'],
						$_POST['db_prefix']
						);
	}

	$no_config		= !file_exists($_path . 'config.php') ? false : true;
	$writeable_path	= is_writable($_path) ? true : false;

	echo gettpl('configs.html');

break;

case 'check':

	$submit_disabled = $no_connection = $mysql_ver = false;

	//config,php
	if (isset($dbname) && isset($dbuser))
	{
		//connect .. for check
		$SQL = new SSQL($dbserver, $dbuser, $dbpass, $dbname);

		if (!$SQL->connect_id)
		{
			$no_connection = true;
		}
		else
		{
			if (version_compare($SQL->mysql_version(), MIN_MYSQL_VERSION, '<'))
			{
				$mysql_ver = $SQL->mysql_version();
			}
		}
	}

	//try to chmod them
	if(function_exists('chmod'))
	{	
		@chmod($_path . 'cache', 0777);
		@chmod($_path . 'uploads', 0777);
		@chmod($_path . 'uploads/thumbs', 0777);
	}

	echo gettpl('check_all.html');

break;

case 'data' :

	if (isset($_POST['datasubmit']))
	{

		//check data ...
		if (empty($_POST['sitename']) || empty($_POST['siteurl']) || empty($_POST['sitemail'])
			 || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) )
		{
			echo $lang['EMPTY_FIELDS'];
			echo $footer_inst;
			exit();
		}

		 if (strpos($_POST['email'],'@') === false)
		 {
			echo $lang['WRONG_EMAIL'];
			echo $footer_inst;
			exit();
		}

		//connect .. for check
		$SQL = new SSQL($dbserver, $dbuser, $dbpass, $dbname);
		 
		include_once  '../includes/usr.php';
		$usrcp = new usrcp;

		$user_salt			= substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
		$user_pass 			= $usrcp->kleeja_hash_password($_POST['password'] . $user_salt);
		$user_name 			= $SQL->escape($_POST['username']);
		$user_mail 			= $SQL->escape($_POST['email']);
		$config_sitename	= $SQL->escape($_POST['sitename']);
		$config_siteurl		= $SQL->escape($_POST['siteurl']);
		$config_sitemail	= $SQL->escape($_POST['sitemail']);
		$config_style		= $SQL->escape($_POST['style']);
		$config_urls_type	= in_array($_POST['urls_type'], array('id', 'filename', 'direct')) ? $_POST['urls_type'] : 'id';
		$clean_name			= $usrcp->cleanusername($SQL->escape($user_name));

		 /// ok .. we will get sqls now ..
		include 'includes/install_sqls.php';

		$err = $dots = 0;
		$errors = '';

		//do important alter before
		$SQL->query($install_sqls['ALTER_DATABASE_UTF']);
		
		$sqls_done = array();
		foreach($install_sqls as $name=>$sql_content)
		{
			if($name == 'DROP_TABLES' || $name == 'ALTER_DATABASE_UTF')
			{
				continue;
			}

			if($SQL->query($sql_content))
			{
				if ($name == 'call') $sqls_done[] = $lang['INST_CRT_CALL'];
				elseif ($name == 'reports')	$sqls_done[] = $lang['INST_CRT_REPRS'];
				elseif ($name == 'stats')	$sqls_done[] = $lang['INST_CRT_STS'];
				elseif ($name == 'users')	$sqls_done[] = $lang['INST_CRT_USRS'];
				elseif ($name == 'users')	$sqls_done[] = $lang['INST_CRT_ADM'];
				elseif ($name == 'files')	$sqls_done[] = $lang['INST_CRT_FLS'];
				elseif ($name == 'config')	$sqls_done[] = $lang['INST_CRT_CNF'];
				elseif ($name == 'exts')	$sqls_done[] = $lang['INST_CRT_EXT'];
				elseif ($name == 'online')	$sqls_done[] = $lang['INST_CRT_ONL'];
				elseif ($name == 'hooks')	$sqls_done[] = $lang['INST_CRT_HKS'];
				elseif ($name == 'plugins')	$sqls_done[] = $lang['INST_CRT_PLG'];
				elseif ($name == 'lang')	$sqls_done[] = $lang['INST_CRT_LNG'];
				else
				{
					//$sqls_done[] = '...';
				}
			}
			else
			{
				$errors  = implode(':', $SQL->get_error()) . '' . "\n___\n";
				echo '<span style="color:red;"> [' .$name . '] : ' . $lang['INST_SQL_ERR'] . '</span><br />';
				$err++;
			}

		}#for
		
		echo gettpl('sqls_done.html');

	}
	else
	{
		$urlsite =  'http://' . $_SERVER['HTTP_HOST'] . str_replace('install', '', dirname($_SERVER['PHP_SELF']));
		echo gettpl('data.html');
	}

break;
case 'plugins' :
	//connect .. for check
	$SQL = new SSQL($dbserver, $dbuser, $dbpass, $dbname);
	//install built in plugins
	$pl_path = "includes/plugins";
	if (isset($_POST['datasubmit']))
	{
			$p = $_POST['plugin_file'];
			if(empty($p))
			{
				header('Location: ' . $_SERVER['PHP_SELF'] . '?step=end&' . getlang(1));
			}
			
			//search for plugins
			foreach($p as $file)
			{				
				if(file_exists($pl_path . '/' . $file)) //only plugins ;)
				{
					$contents 	= @file_get_contents($pl_path . '/' . $file);
					$gtree 		= xml_to_array($contents);
				
					if($gtree != false) //great !! it's well-formed xml 
					{
						$installed_plugins[] = array(
						'p_file' => $file,
						'p_name' =>  $SQL->escape($gtree['kleeja']['info']['plugin_name']['value']),
						'p_ver'  => $SQL->escape($gtree['kleeja']['info']['plugin_version']['value']),
						'p_des'  => $SQL->escape($gtree['kleeja']['info']['plugin_description']['value']),
						//'p_size' => @filesize($pl_path . '/' . $file),
						);
						//install them
						creat_plugin_xml($contents);
					}
				}
			}
			//clean cache
			delete_cache(null, true);
			echo gettpl('plugins_done.html');
	}
	else
	{
		$dh = opendir($pl_path);
		while (($file = readdir($dh)) !== false)
		{
			$e	= @explode(".", $file);
			$e	= strtolower($e[sizeof($e)-1]);
			if($e == "xml") //only plugins ;)
			{
				$contents 	= @file_get_contents($pl_path . '/' . $file);
				$gtree 		= xml_to_array($contents);
				
				if($gtree != false) //great !! it's well-formed xml 
				{
					$plugins[]	= array(
					'p_file' => $file,
					'p_name' =>  $SQL->escape($gtree['kleeja']['info']['plugin_name']['value']),
					'p_ver'  => $SQL->escape($gtree['kleeja']['info']['plugin_version']['value']),
					'p_des'  => $SQL->escape($gtree['kleeja']['info']['plugin_description']['value']),
					//'p_size' => @filesize($pl_path . '/' . $file),
					);
				}
			}			
		}
		echo gettpl('plugins_options.html');
	}
break;

case 'end' :

		echo gettpl('end.html');
		//for safe ..
		//@rename("install.php", "install.lock");
break;
}


/**
* print footer
*/
echo gettpl('footer.html');


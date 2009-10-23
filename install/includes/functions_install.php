<?php
/**
*
* @package install
* @version $Id: func_inst.php 1187 2009-10-18 23:10:13Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/

/*
* Requirements of Kleeja
*/
define('MIN_PHP_VERSION', '4.3.0');
define('MIN_MYSQL_VERSION', '4.1.2');
//version of latest changes at db
define ('LAST_DB_VERSION' , '7');
//set no errors
define('MYSQL_NO_ERRORS', true);


// Detect choosing another lang while installing
if(isset($_GET['change_lang']))
{
	if (!empty($_POST['lang']))
	{
		header('Location: ' . $_SERVER['PHP_SELF'] . '?step=' . $_POST['step_is'] . '&lang=' . $_POST['lang']); 
	}
}

// Including current language
include ($_path . 'lang/' . getlang() . '/install.php');


/**
* Return current language of installing wizard 
*/
function getlang ($link = false)
{
	global $_path;

	if (isset($_GET['lang']))
	{
		$_GET['lang'] = empty($_GET['lang']) ? 'en' : preg_replace('/[^a-z0-9]/i', '', $_GET['lang']);

		$ln	= file_exists($_path . 'lang/' . $_GET['lang'] . '/install.php') ? $_GET['lang'] : 'en';
	}
	else
	{
		$ln	= 'en';
	}

	return $link ? 'lang=' . $ln : $ln;
}

function getjquerylink()
{
	global $_path;

	if(file_exists($_path . 'admin/admin_style/js/jquery.js'))
	{
		return $_path . 'admin/admin_style/js/jquery.js';
	}
	else
	{
		return 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js';
	}
}


/**
* Parsing installing templates
*/
function gettpl($tplname)
{
	global $lang, $_path;

	$tpl = preg_replace('/{{([^}]+)}}/', '<?php \\1 ?>', file_get_contents('style/' . $tplname));
	ob_start();
	eval('?> ' . $tpl . '<?php ');
	$stpl = ob_get_contents();
	ob_end_clean();
	
	return $stpl;
}

/**
* Export config 
*/
function do_config_export($type, $srv, $usr, $pass, $nm, $prf, $fpath = '')
{
		global $_path;
		
		if(!in_array($type, array('mysql', 'mysqli')))
		{
			$type = 'mysql';
		}
		
		$data	= '<?php'."\n\n" . '//fill those varaibles with your data' . "\n";
		$data	.= '$db_type		= \'' . $type . "'; //mysqli or mysql \n";
		$data	.= '$dbserver		= \'' . str_replace("'", "\'", $srv) . "'; //database server \n";
		$data	.= '$dbuser			= \'' . str_replace("'", "\'", $usr) . "' ; // database user \n";
		$data	.= '$dbpass			= \'' . str_replace("'", "\'", $pass) . "'; // database password \n";
		$data	.= '$dbname			= \'' . str_replace("'", "\'", $nm) . "'; // database name \n";
		$data	.= '$dbprefix		= \'' . str_replace("'", "\'", $prf) . "'; // if you use perfix for tables , fill it \n";
		//$data	.= '$adminpath		= \'admin.php\';// if you renamed your acp file , please fill the new name here \n';
		//$data	.= "\n\n\n";
		//$data	.= "//for integration with script  must change user systen from admin cp  \n";
		//$data	.= '$script_path	= \'' . str_replace("'", "\'", $fpath) . "'; // path of script (./forums)  \n";
		//$data	.= "\n\n";
		//$data	.= '?'.'>';
	
		$written = false;
		if (is_writable($_path))
		{
			$fh = @fopen($_path . 'config.php', 'wb');
			if ($fh)
			{
				fwrite($fh, $data);
				fclose($fh);

				$written = true;
			}
		}
		
		if(!$written)
		{
			header('Content-Type: text/x-delimtext; name="config.php"');
			header('Content-disposition: attachment; filename=config.php');
			echo $data;
			exit;
		}
		
		return true;
}	


/**
* Usefull to caluculte time of execution
*/
function get_microtime()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float) $usec + (float) $sec);
}

/**
* Get config value from database directly, if not return false.
*/
function inst_get_config($name)
{
	global $SQL, $dbprefix;
	
	if(!is_resource($SQL))
	{
		global $dbserver, $dbuser, $dbpass, $dbname;
		if(!isset($dbserver))
		{
			return false;
		}
		$SQL = new SSQL($dbserver, $dbuser, $dbpass, $dbname);
	}
	
	$SQL->show_errors = false;
	$sql = "SELECT value FROM `{$dbprefix}config` WHERE `name` = '" . $name . "'";
	$result	= $SQL->query($sql);
	if($SQL->num_rows($result) == 0)
	{
		return false;
	}
	else
	{
		$current_ver  = $SQL->fetch_array($result);
		return $current_ver['value'];
	}
}


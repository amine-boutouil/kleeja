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
//set no errors
define('MYSQL_NO_ERRORS', true);


//for lang 
if(isset($_GET['change_lang']))
{
	if (!empty($_POST['lang']))
	{
		//Redirect browser
		header("Location:" . $_SERVER['PHP_SELF'] . "?step=" . $_POST['step_is'] . "&lang=" . $_POST['lang']); 
	}
}

function getlang ($link=false)
{
	global $_path;
	if (isset($_GET['lang']))
	{ 
		if(empty($_GET['lang']))
		{
			$_GET['lang'] = 'en';
		}			
		if(file_exists($_path . 'lang/' . htmlspecialchars($_GET['lang']) . '/install.php'))
		{
			$ln	=  htmlspecialchars($_GET['lang']);
		}
		else
		{
			$ln = 'en';
		}
	}
	else
	{
		$ln	= 'en';
	}

	return $link != false ? 'lang=' . $ln : $ln;
}

//for language //	
include ($_path . 'lang/' . getlang() . '/install.php');



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



function get_microtime()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float) $usec + (float) $sec);
}

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

#<-- EOF

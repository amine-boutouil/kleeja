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



// Exceptions for development
if(file_exists('.svn/entries') || file_exists('dev.txt'))
{
	define('DEV_STAGE', true);
}

$order_update_files = array(
'RC2_to_RC3' => 3,
'RC4_to_RC5' => 5,
'RC5_to_RC6' => 6,
'RC6_to_1.0.0'=>7,
); 

$SQL = new SSQL($dbserver, $dbuser, $dbpass, $dbname);
			
//
// Is current db is up-to-date ?
//
$config['db_version'] = inst_get_config('db_version');
if($config['db_version'] == false)
{
	$SQL->query("INSERT INTO `{$dbprefix}config` (`name` ,`value`)VALUES ('db_version', '')");
}

if(!isset($_GET['step']))
{
	$_GET['step'] = 'action_file';
}

/**
* print header
*/
if (!isset($_POST['action_file_do']))
{
	echo gettpl('header.html');
}



/**
* Navigation ..
*/
switch ($_GET['step'])
{
default:
case 'action_file':

	if (isset($_POST['action_file_do']))
	{
		if (!empty($_POST['action_file_do']))
		{
			echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'] . '?step=update_now&action_file_do=' . htmlspecialchars($_POST['action_file_do']) . '&amp;' . getlang(1) . '">';
		}
	}
	else
	{
		//get fles
		$s_path = "includes/update_files";
		$dh = opendir($s_path);
		$upfiles = array();
		while (($file = readdir($dh)) !== false)
		{
			if($file != "." && $file != ".."  && $file != "index.html" && $file != ".svn")
			{
				$file = str_replace('.php','', $file);
				$db_ver = $order_update_files[$file];

				if((empty($config['db_version']) or $db_ver > $config['db_version']) or defined('DEV_STAGE'))
				{
					$upfiles[$db_ver] = $file;
				}
			}
		}
		@closedir($dh);

		ksort($upfiles); 
		
		echo gettpl('update_list.html');
	}

break;

case 'update_now':
	
		if(!isset($_GET['action_file_do']))
		{
			echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'].'?step=action_file&' . getlang(1) . '">';
			exit();
		}
		
		if(isset($_GET['complet_up_func']))
		{
			define('C_U_F', true);
		}
		
		$file_for_up = 'includes/update_files/' . preg_replace('/[^a-z0-9_\-\.]/i', '', $_GET['action_file_do']) . '.php';
		if(!file_exists($file_for_up))
		{
			echo '<span style="color:red;">' . $lang['INST_ERR_NO_SELECTED_UPFILE_GOOD'] . ' [ ' . $file_for_up . ' ]</span><br />';
		}
		else
		{	
			//get it
			require $file_for_up;
			$complete_upate = true;
			
			if($config['db_version'] >= DB_VERSION && !defined('DEV_STAGE'))
			{
				echo '<br /><br /><span style="color:green;">' . $lang['INST_UPDATE_CUR_VER_IS_UP']. '</span><br />';
				$complete_upate = false;
			}
			
			//
			//is there any sqls 
			//
			if(($complete_upate or defined('DEV_STAGE')) && !defined('C_U_F'))
			{
				$SQL->show_errors = false;
				if(isset($update_sqls) && sizeof($update_sqls) > 0)
				{
					$err = '';
					foreach($update_sqls as $name=>$sql_content)
					{
						$err = '';
						$SQL->query($sql_content);
						$err = $SQL->get_error();
						
						if(strpos($err[1], 'Duplicate') !== false || $err[0] == '1062' || $err[0] == '1060')
						{
							$sql = "UPDATE `{$dbprefix}config` SET `value` = '" . DB_VERSION . "' WHERE `name` = 'db_version'";
							$SQL->query($sql);
							echo '<br /><br /><span style="color:green;">' . $lang['INST_UPDATE_CUR_VER_IS_UP']. '</span><br />';
							$complete_upate = false;
						}
					}
				}
			}

			//
			//is there any functions 
			//
			if($complete_upate or defined('DEV_STAGE') or defined('C_U_F'))
			{
				if(isset($update_functions) && sizeof($update_functions) > 0)
				{
					foreach($update_functions as $n)
					{
						eval('' . $n . '; ');
					}
				}
			}
			
			//
			//is there any notes 
			//
			if($complete_upate or defined('DEV_STAGE'))
			{
				if(isset($update_notes) && sizeof($update_notes) > 0)
				{
					echo '<br /><span style="color:blue;"><b>' . $lang['INST_NOTES_UPDATE'] . ' :</b> </span><br />';
					
					$i=1;
					foreach($update_notes as $n)
					{
						echo '  [<b>' . $i . '</b>] <br /><span style="color:black;">' . $n. ' </span><br />';
						++$i;
					}

				}
			}

			if($complete_upate)
			{
				//check plugins
				$pl_path = "includes/plugins";
				$dh = opendir($pl_path);
				while (($file = readdir($dh)) !== false)
				{
					$e	= strtolower(array_pop(@explode('.', $file)));

					if($e == "xml") //only plugins ;)
					{
						$contents 	= @file_get_contents($pl_path . '/' . $file);
						$gtree 		= xml_to_array($contents);

						if($gtree != false) //great !! it's well-formed xml 
						{
							$plugin_name = preg_replace("/[^a-z0-9-_]/", "-", $gtree['kleeja']['info']['plugin_name']['value']);

							if(updating_exists_plugin($plugin_name) || (isset($must_installing_plugins) && in_array($file, $must_installing_plugins)))
							{
								creat_plugin_xml($contents);
							}
						}
					}
				}

				delete_cache(null, true);
			}

			echo gettpl('update_end.html');
		}

break;
}

/**
* print footer
*/
echo gettpl('footer.html');

<?php
//
// kleeja updater ...
// $Author$ , $Rev$,  $Date::                           $
//

// Report all errors, except notices
@error_reporting(E_ALL ^ E_NOTICE);


/*
include important files
*/
define ('IN_COMMON' , true);
$path = "../includes/";
(file_exists('../config.php')) ? include_once ('../config.php') : null;
include_once ($path . 'functions.php');
include_once ($path . 'mysql.php');
include_once ('func_inst.php');

//exception for development
if(file_exists('.svn/entries'))
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
//is current db is up-to-date !
//
$config['db_version'] = inst_get_config('db_version');
if($config['db_version'] == false)
{
	$SQL->query("INSERT INTO `{$dbprefix}config` (`name` ,`value`)VALUES ('db_version', '')");
}
			
/*
//print header
*/
if (!isset($_POST['action_file_do']))
{
	echo $header_inst;
}

if(!isset($_GET['step']))
{
	$_GET['step'] = 'check';
}

/*
//nvigate ..
*/
switch ($_GET['step'])
{
default:
case 'check':

	$submit_wh = '';


	//config,php
	if (!$dbname || !$dbuser)
	{
		echo '<span style="color:red;">' . $lang['INST_CHANG_CONFIG'] . '</span><br />';
		$submit_wh = 'disabled="disabled"';
	}

	//connect .. for check
	$texterr = '';
	$connect = @mysql_connect($dbserver, $dbuser, $dbpass);
	if (!$connect) 
		$texterr .= '<span style="color:red;">' . $lang['INST_CONNCET_ERR'] . '</span><br />';
		
	$select = @mysql_select_db($dbname);
	if (!$select) 
		$texterr .= '<span style="color:red;">' . $lang['INST_SELECT_ERR'] . '</span><br />';
		
	if ( !is_writable('../cache') ) {$texterr .= '<span style="color:red;">[cache]: ' . $lang['INST_NO_WRTABLE'] . '</span><br />';};
	if ( !is_writable('../uploads') ) {$texterr .= '<span style="color:red;">[uploads]: ' . $lang['INST_NO_WRTABLE'] . '</span><br />';};
	if ( !is_writable('../uploads/thumbs') ) {$texterr .= '<span style="color:red;">[uploads/thumbs]: ' . $lang['INST_NO_WRTABLE'] . '</span><br />';};
	if ($texterr !='')
	{
		echo $texterr;
		$submit_wh = 'disabled="disabled"';
	}

	if($submit_wh == '')
	{
		echo '<br /><span style="color:green;"><b>[ ' . $lang['INST_GOOD_GO'] . ' ]</b></span><br /><br />';
	}

	echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=action_file&' . getlang(1) . '">
	<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" ' . $submit_wh . '/>
	</form>';

break;

case 'action_file':

	if (isset($_POST['action_file_do']))
	{
			if (!empty($_POST['action_file_do']))
			{
				//go to .. 2step
				echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'].'?step=update_now&action_file_do='. htmlspecialchars($_POST['action_file_do']) .'&'.getlang(1).'">';
			//	@header("Location:".$_SERVER[PHP_SELF]."?step=check"); /* Redirect browser */
			}

	}
	else
	{
			//get fles
			$s_path = "update_files";
			$dh = opendir($s_path);
			$lngfiles = array();
			while (($file = readdir($dh)) !== false)
			{
			    if($file != "." && $file != ".."  && $file != "index.html" && $file != ".svn")
				{
					$file = str_replace('.php','', $file);
					$db_ver = $order_update_files[$file];

					if((empty($config['db_version']) or $db_ver > $config['db_version']) or defined('DEV_STAGE'))
					{
						$lngfiles[$db_ver] = '<option value="' . $file . '">' . $file . '</option>';
					}
			    }
			}
			closedir($dh);
			
			ksort($lngfiles);

		// show   list ..
		echo '
		<br />
		<br /><form  action="' . $_SERVER['PHP_SELF'] . '?step=action_file&' . getlang(1) . '" method="post">
		' . $lang['INST_CHOOSE_UPDATE_FILE'] . ' 
		<br />';
		if (sizeof($lngfiles)):
			echo '
			<select name="action_file_do" style="width: 352px">
			' . implode("\n", $lngfiles) . '
			</select>
			<br />
			<br />
			<input name="submitlfile" type="submit" value="' . $lang['INST_SUBMIT'] . '" /><br /><br /><br /></form>';
		else :
			echo '<br /><br /><span style="color:green;"><strong>' . $lang['INST_UPDATE_CUR_VER_IS_UP'] . '<br /> [ ' . array_search($config['db_version'], $order_update_files) . ' :: ' . $config['db_version'] . ' ]</strong></span><br /><br /><br />';
		endif;
		
	}//no  else



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
		
		$file_for_up	=	'update_files/' . htmlspecialchars($_GET['action_file_do']) . '.php';
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
				delete_cache(null, true, true);
				echo '<br /><br /><span style="color:green;">' . $lang['INST_UPDATE_IS_FINISH']. '</span><br />';
				echo '<img src="img/home.gif" alt="home" />&nbsp;<a href="../index.php">' . $lang['INDEX'] . '</a><br /><br />';
				echo '<img src="img/adm.gif" alt="admin" />&nbsp;<a href="../admin/">' . $lang['ADMINCP'] . '</a><br /><br />';
				echo '' . $lang['INST_KLEEJADEVELOPERS'] . '<br /><br />';
				echo '<a href="http://www.kleeja.com">www.kleeja.com</a><br /><br />';
			}
			else
			{
				echo '<br /><br /><span style="color:orange;"><a href="./update.php?step=action_file&' . getlang(1) . '">' . $lang['INST_UPDATE_SELECT_ONTHER_UPDATES']. '</span><br />';
				echo '<br /><br /><img src="img/home.gif" alt="home" />&nbsp;<a href="../index.php">' . $lang['INDEX'] . '</a><br />';
				echo '<img src="img/adm.gif" alt="admin" />&nbsp;<a href="../admin.php">' . $lang['ADMINCP'] . '</a><br /><br />';
				echo '<br /><a href="http://www.kleeja.com">www.kleeja.com</a><br /><br />';	
			}
			
		}

break;


}//end switch
/*
//print footer
*/
echo $footer_inst;

?>

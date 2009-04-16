<?php
# KLEEJA UPDATOR ...


// Report all errors, except notices
@error_reporting(E_ALL ^ E_NOTICE);


/*
include important files
*/
define ( 'IN_COMMON' , true);
$path = "../includes/";
(file_exists('../config.php')) ? include ('../config.php') : null;
include ($path . 'functions.php');
include ($path . 'mysql.php');
include ('func_inst.php');

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
	$connect = @mysql_connect($dbserver,$dbuser,$dbpass);
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
	{ //no 

		//get fles
			$path = "update_files";
			$dh = opendir($path);
			$lngfiles = '';
			$i=1;
			while (($file = readdir($dh)) !== false)
			{
			    if($file != "." && $file != ".."  && $file != "index.html")
				{
					$file = str_replace('.php','', $file);
					$lngfiles .= '<option value="' . $file . '">' . $file . '</option>';
			        $i++;
			    }
			}
			closedir($dh);

		// show   list ..
		echo '
		<br />
		<br /><form  action="' . $_SERVER['PHP_SELF'] . '?step=action_file&' . getlang(1) . '" method="post">
		'.$lang['INST_CHOOSE_UPDATE_FILE'].' 
		<br />
		<select name="action_file_do" style="width: 352px">
		' . $lngfiles . '
		</select>
		<br />
		<br />
		<input name="submitlfile" type="submit" value="' . $lang['INST_SUBMIT'] . '" /><br /><br /><br /></form>';

	}//no  else



break;

case 'update_now':
	
		if(!isset($_GET['action_file_do']))
		{
			echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'].'?step=action_file&' . getlang(1) . '">';
			exit();
		}
		
		$file_for_up	=	'update_files/'.htmlspecialchars($_GET['action_file_do']) . '.php';
		if(!file_exists($file_for_up))
		{
			echo '<span style="color:red;">' . $lang['INST_ERR_NO_SELECTED_UPFILE_GOOD'] . ' [ ' . $file_for_up . ' ]</span><br />';
		}
		else
		{	
			//get it
			require $file_for_up;
			$complete_upate = true;
			
			$SQL	= new SSQL($dbserver, $dbuser, $dbpass, $dbname);
			
			
			//
			//is current db is up-to-date !
			//
			$sql = "SELECT value FROM `{$dbprefix}config` WHERE `name` = 'db_version'";
			$result	= $SQL->query($sql);
			if($SQL->num_rows($result) == 0)
			{
				$SQL->query("INSERT INTO `{$dbprefix}config` (`name` ,`value`)VALUES ('db_version', '')");
			}
			else
			{
				$current_ver  = $SQL->fetch_array($result);
				$current_ver  = $current_ver['value'];
				
				if($current_ver >= DB_VERSION)
				{
					echo '<br /><br /><span style="color:green;">' . $lang['INST_UPDATE_CUR_VER_IS_UP']. '</span><br />';
					$complete_upate = false;
				}
			}
			
			//
			//is there any sqls 
			//
			if($complete_upate)
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
			if($complete_upate)
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
			if($complete_upate)
			{
				if(isset($update_notes) && sizeof($update_notes) > 0)
				{
					echo '<br /><span style="color:blue;"><b>' . $lang['INST_NOTES_UPDATE'] . ' :</b> </span><br />';
					
					$i=1;
					foreach($update_notes as $n)
					{
						echo '  [<b>' . $i . '</b>] <br /><span style="color:black;">' . $n. ' : </span><br />';
						++$i;
					}

				}
			}
			
			
			if($complete_upate)
			{
				delete_cache(null, true, true);
				echo '<br /><br /><span style="color:green;">' . $lang['INST_UPDATE_IS_FINISH']. '</span><br />';
				echo '<img src="img/home.gif" alt="home" />&nbsp;<a href="../index.php">' . $lang['INDEX'] . '</a><br /><br />';
				echo '<img src="img/adm.gif" alt="admin" />&nbsp;<a href="../admin.php">' . $lang['ADMINCP'] . '</a><br /><br />';
				echo '' . $lang['INST_KLEEJADEVELOPERS'] . '<br /><br />';
				echo '<a href="http://www.kleeja.com">www.kleeja.com</a><br /><br /></fieldset>';
			}
			else
			{
				echo '<br /><br /><span style="color:orange;"><a href="./update.php?step=action_file&' . getlang(1) . '">' . $lang['INST_UPDATE_SELECT_ONTHER_UPDATES']. '</span><br />';
				echo '<br /><br /><img src="img/home.gif" alt="home" />&nbsp;<a href="../index.php">' . $lang['INDEX'] . '</a><br />';
				echo '<img src="img/adm.gif" alt="admin" />&nbsp;<a href="../admin.php">' . $lang['ADMINCP'] . '</a><br /><br />';
				echo '<br /><a href="http://www.kleeja.com">www.kleeja.com</a><br /><br /></fieldset>';	
			}
			
		}

break;


}//end switch
/*
//print footer
*/
echo $footer_inst;

?>
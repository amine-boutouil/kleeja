<?php
# KLEEJA UPDATOR ...
# last edit by : saanina

/*
include important files
*/



	
	define ( 'IN_COMMON' , true);
	$path = "../includes/";
	(file_exists('../config.php')) ? include ('../config.php') : null;
	include ($path.'functions.php');
	include ($path.'mysql.php');
	include ('func_inst.php');
	



/*
//print header
*/
if (!isset($_POST['action_file_do']))
{
	print $header_inst;
}

/*
//nvigate ..
*/
switch ($_GET['step']) {
default:
case 'check':

	$submit_wh = '';


	//config,php
	if (!$dbname || !$dbuser)
	{
		print '<span style="color:red;">' . $lang['INST_CHANG_CONFIG'] . '</span><br />';
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
		print $texterr;
		$submit_wh = 'disabled="disabled"';
	}

	if($submit_wh == '')
	{
		print '<br /><span style="color:green;"><b>[ ' . $lang['INST_GOOD_GO'] . ' ]</b></span><br /><br />';
	}

	print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=action_file&'.get_lang(1).'">
	<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" ' . $submit_wh . '/>
	</form>';

break;

case 'action_file':

	if (isset($_POST['action_file_do']))
	{
			if (!empty($_POST['action_file_do']))
			{
				//go to .. 2step
				echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'].'?step=update_now&action_file_do='. htmlspecialchars($_POST['action_file_do']) .'&'.get_lang(1).'">';
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
		print '
		<br />
		<br /><form  action="' . $_SERVER['PHP_SELF'] . '?step=action_file&'.get_lang(1).'" method="post">
		'.$lang['INST_CHOOSE_UPDATE_FILE'].' 
		<br />
		<select name="action_file_do" style="width: 352px">
		' . $lngfiles . '
		</select>
		<br />
		<br />
		<input name="submitlfile" type="submit" value="'.$lang['INST_SUBMIT'].'" /><br /><br /><br /></form>';

	}//no  else



break;

case 'update_now':
	
		if(!isset($_GET['action_file_do']))
		{
			echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'].'?step=action_file&'.get_lang(1).'">';
			exit();
		}
		
		$file_for_up	=	'update_files/'.htmlspecialchars($_GET['action_file_do']).'.php';
		if(!file_exists($file_for_up))
		{
			print '<span style="color:red;">' . $lang['INST_ERR_NO_SELECTED_UPFILE_GOOD'] . ' [ '.$file_for_up.' ]</span><br />';
		}
		else
		{	
			//get it
			require $file_for_up;

			$SQL	= new SSQL($dbserver,$dbuser,$dbpass,$dbname);
			
			//
			//is there any sqls 
			//
			if(sizeof($update_sqls) > 0)
			{
				foreach($update_sqls as $name=>$sql_content)
				{
					$do_it	= $SQL->query($sql_content);
					
					if(!$do_it)
						print '<span style="color:red;"> [' .$name .'] : ' . $lang['INST_SQL_ERR'] . '</span><br />';
				}
			}
			
			//
			//is there any functions 
			//
			if(sizeof($update_functions) > 0)
			{
				foreach($update_functions as $n)
				{
					eval('' . $n .'; ');
				}
			}
			
			//
			//is there any notes 
			//
			if(sizeof($update_notes) > 0)
			{
				print '<br /><span style="color:blue;"><b>' . $lang['INST_NOTES_UPDATE'] . ' :</b> </span><br />';
				
				$i=1;
				foreach($update_notes as $n)
				{
					print '  [<b>' . $i .'</b>] <br /><span style="color:black;">' . $n. ' : </span><br />';
					++$i;
				}

			}
			
			print '<br /><br /><span style="color:green;">' . $lang['INST_UPDATE_IS_FINISH']. '</span><br />';
		
		}

break;


}//end switch
/*
//print footer
*/
print $footer_inst;

?>
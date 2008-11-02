<?php
##################################################
#						Kleeja
#
# Filename : go.php
# purpose :  File for Navigataion .
# copyright 2007-2008 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : saanina
##################################################

// security ..
define ('IN_INDEX' , true);
//include imprtant file ..
include ('includes/common.php');

($hook = kleeja_run_hook('begin_go_page')) ? eval($hook) : null; //run hook

switch ($_GET['go'])
{
	case "guide" : 

	$stylee	= "guide";
	$titlee	= $lang['GUIDE'];

	//make it loop
	$gusts_data = array();
	foreach($g_exts as $s)
	{
		$gusts_data[]	= array(	'ext'	=> $s,
									'num'	=> Customfile_size($g_sizes[$s])//format size as kb, mb,...
									);
	}

	//make it loop
	$users_data = array();
	foreach($u_exts as $s )
	{
		$users_data[]	=	array(	'ext' => $s,
									'num' => Customfile_size($u_sizes[$s])//format size as kb, mb,...
									);
	}
	
	($hook = kleeja_run_hook('guide_go_page')) ? eval($hook) : null; //run hook
	
	break;
	
	case "report" :

	//start captcha class
	$ch = new ocr_captcha;

	if (!isset($_POST['submit']))
	{
			$stylee	= "report";
			$titlee	= $lang['REPORT'];
			$url_id	= ($config['mod_writer']) ? $config['siteurl'] . "download" . intval($_GET['id']) . ".html" : $config['siteurl'] . "download.php?id=" . intval($_GET['id']);
			$action	= "./go.php?go=report";
			$code	= $ch->display_captcha(true);
			$id_d	= intval($_GET['id']);
			
			// first
			if (!$_GET['id'])
			{
				kleeja_err($lang['NO_ID']);
			}
			
			($hook = kleeja_run_hook('no_submit_report_go_page')) ? eval($hook) : null; //run hook
	}
	else
	{
		$ERRORS	=	'';
		($hook = kleeja_run_hook('submit_report_go_page')) ? eval($hook) : null; //run hook

		if (empty($_POST['rname']) || empty($_POST['rmail']) || empty($_POST['rurl']) )
		{
			$ERRORS[]	=	$lang['EMPTY_FIELDS'];
		}
		else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim(strtolower($_POST['rmail']))))
		{
			$ERRORS[]	=	$lang['WRONG_EMAIL'];
		}
		else if (strlen($_POST['rtext']) > 300)
		{
			$ERRORS[]	=	$lang['NO_ME300RES'];
		}
		else if (!$ch->check_captcha($_POST['public_key'], $_POST['code_answer']))
		{
			$ERRORS[]	=	$lang['WRONG_VERTY_CODE'];
		}
		
		//no error , lets do process
		if(empty($ERRORS))
		{
				$name	= (string) $SQL->escape($_POST['rname']);
				$text	= (string) $SQL->escape($_POST['rtext']);
				$mail	= (string) strtolower($_POST['rmail']);
				$url	= (string) $_POST['rurl'];
				$time 	= (int) time();
				$rid	= (int) $_POST['rid'];
				$ip		=	(getenv('HTTP_X_FORWARDED_FOR')) ? getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');


				$insert_query = array(
										'INSERT'	=> '`name` ,`mail` ,`url` ,`text` ,`time` ,`ip`',
										'INTO'		=> "`{$dbprefix}reports`",
										'VALUES'	=> "'$name', '$mail', '$url', '$text', '$time', '$ip'"
									);
				
				($hook = kleeja_run_hook('qr_insert_new_report')) ? eval($hook) : null; //run hook
		
				if (!$SQL->build($insert_query))
				{
					kleeja_err($lang['CANT_INSERT_SQL']);
				}
				else
				{
					kleeja_info($lang['THNX_REPORTED']);
				}
				
				//update number of reports
				$update_query = array(
											'UPDATE'	=> "{$dbprefix}files",
											'SET'			=> 'report=report+1',
											'WHERE'	=> 'id=' . $rid,
										);
							
				($hook = kleeja_run_hook('qr_update_no_file_report')) ? eval($hook) : null; //run hook
				
				if (!$SQL->build($update_query)){ die($lang['CANT_UPDATE_SQL']);}
		}
		else
		{
				foreach($ERRORS as $r)
				{
							$errs	.= '- ' . $r . ' <br/>';
				}			
				kleeja_err($errs);
		}
	}
	
	($hook = kleeja_run_hook('report_go_page')) ? eval($hook) : null; //run hook
	
	break; 
	
	
	case "rules" :
	
	$stylee	= "rules";
	$titlee	= $lang['RULES'];
	
	//prevent empty !!
	if (strlen($ruless) > 3)
	{
		$contents = stripslashes($ruless);
	}
	else
	{
		$contents	= $lang['NO_RULES_NOW'];
	}
	
	($hook = kleeja_run_hook('rules_go_page')) ? eval($hook) : null; //run hook
	
	break;
	
	
	case "call" : 
	
	//start  captcha class
	$ch = new ocr_captcha;

	if (!isset($_POST['submit']))
	{
		$stylee	= "call";
		$titlee	= $lang['CALL'];
		$action	= "./go.php?go=call";
		$code	= $ch->display_captcha(true);
		
		($hook = kleeja_run_hook('no_submit_call_go_page')) ? eval($hook) : null; //run hook
	}
	else
	{
		//after sumit
		$ERRORS	=	'';
		($hook = kleeja_run_hook('submit_call_go_page')) ? eval($hook) : null; //run hook
		
		if (empty($_POST['cname']) || empty($_POST['cmail']) || empty($_POST['ctext']) )
		{
			$ERRORS[]	= $lang['EMPTY_FIELDS'];
		}
		else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim(strtolower($_POST['cmail']))))
		{
			$ERRORS[]	= $lang['WRONG_EMAIL'];
		}
		else if (strlen($_POST['ctext']) > 300)
		{
			$ERRORS[]	= $lang['NO_ME300TEXT'];
		}
		else if (!$ch->check_captcha($_POST['public_key'], $_POST['code_answer']))
		{
			$ERRORS[]	= $lang['WRONG_VERTY_CODE'];
		}
		
		//no errors ,lets do process
		if(empty($ERRORS))
		{
			$name	= (string) $SQL->escape($_POST['cname']);
			$text	= (string) $SQL->escape($_POST['ctext']);
			$mail	= (string) strtolower($_POST['cmail']);
			$timee	= (int)	time();
			$ip		= (int)	(getenv('HTTP_X_FORWARDED_FOR')) ? getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');
				

			$insert_query = array(
									'INSERT'	=> "`name` ,`text` ,`mail` ,`time` ,`ip`",
									'INTO'		=> "`{$dbprefix}call`",
									'VALUES'	=> "'$name', '$text', '$mail', '$timee', '$ip'"
									);
				
			($hook = kleeja_run_hook('qr_insert_new_call')) ? eval($hook) : null; //run hook
		
			if (!$SQL->build($insert_query))
			{
				kleeja_err($lang['CANT_INSERT_SQL']);
			}
			else
			{
				kleeja_info($lang['THNX_CALLED']);
			}
		}
		else
		{
			foreach($ERRORS as $r)
			{
				$errs	.= '- ' . $r . '. <br/>';
			}				
			kleeja_err($errs);
		}
	}
	
	($hook = kleeja_run_hook('call_go_page')) ? eval($hook) : null; //run hook

	break;
	
	
	case "down" :
	
	($hook = kleeja_run_hook('begin_down_go_page')) ? eval($hook) : null; //run hook	
	
	//maybe ..
	function saff ($var)
	{
		 return str_replace(array('./','http', ':','//','www.','>', '<', '.com', '.net', '.org'), '', $var);
	}


	if (isset($_GET['i']))
	{
		//for safe
		$id	= intval($_GET['i']);
		$REFERER = !empty($_SERVER['HTTP_REFERER']) ? strtolower($_SERVER['HTTP_REFERER']) : strtolower(getenv('HTTP_REFERER'));
		if ($REFERER != '' && strpos($_SERVER['HTTP_REFERER'], 'download') === false)
		{
			$linkoo	= ($config['mod_writer']) ?	'./download' . $id . '.html' : './download.php?id=' . $id;
			header('Location: ' . $linkoo);
		
		}
		else
		{
			//updates ups ..
			$update_query = array(
									'UPDATE'	=> "{$dbprefix}files",
									'SET'		=> 'uploads=uploads+1, last_down=' . time(),
									'WHERE'		=> "id='" . $id . "'",
								);

			($hook = kleeja_run_hook('qr_update_no_uploads_down')) ? eval($hook) : null; //run hook
			
			if (!$SQL->build($update_query)) die($lang['CANT_UPDATE_SQL']);

			//for safe !!!
			$n	= saff($_GET['n']);
			$f	= saff($_GET['f']);

			($hook = kleeja_run_hook('down_go_page')) ? eval($hook) : null; //run hook	
			
			//start download ,,
			header("Location: ./$f/$n");

		}//elser efer

		exit; // we doesnt need style
	}
	else
	{
		die('No requested file');
	}

	break;
	
	case "del" :

	($hook = kleeja_run_hook('del_go_page')) ? eval($hook) : null; //run hook
	
	//stop .. check first ..
	if (!$config['del_url_file'])
	{
			kleeja_info($lang['NO_DEL_F'], $lang['E_DEL_F']);
	}

	//ok .. go on
	$cd	= $SQL->escape($_GET['cd']); // may.. will protect

	if (!$cd)
	{
		kleeja_err($lang['WRONG_URL']);
	}
	else
	{
		$query = array(
							'SELECT'=> 'f.name, f.folder',
							'FROM'	=> "{$dbprefix}files f",
							'WHERE'	=> "f.code_del='" . $cd . "'"
						);
				
		($hook = kleeja_run_hook('qr_select_file_with_code_del')) ? eval($hook) : null; //run hook	
			
		$result	=	$SQL->build($query);

		if ($SQL->num_rows($result) == 0)
		{
			kleeja_err($lang['CANT_DEL_F']);
		}
		else
		{
			while($row=$SQL->fetch_array($sql))
			{
					@unlink ( $row['folder'] . "/" . $row['name'] );
					//delete thumb
					if (file_exists($row['folder'] . "/thumbs/" . $row['name']))
					{
						@unlink ( $row['folder'] . "/thumbs/" . $row['name'] );
					}
					
					$query_del = array(
										'DELETE'	=> "{$dbprefix}files",
										'WHERE'	=> "id='" . $row['id'] . "'"
										);
							
					($hook = kleeja_run_hook('qr_del_file_with_code_del')) ? eval($hook) : null; //run hook	
					
					if ($SQL->build($query_del))
					{
						kleeja_info($lang['DELETE_SUCCESFUL']);
					}
					else
					{
						die($lang['CANT_DELETE_SQL']);
					}
			}
				$SQL->freeresult($result);
		}

	}#else

	break;
	
	
	case "stats" :

		//stop .. check first ..
		if (!$config['allow_stat_pg'])
		{
			kleeja_info($lang['STATS_CLOSED'], $lang['STATS_CLOSED']);
		}

		//ok .. go on
		$titlee		= $lang['STATS'];
		$stylee		= "stats";
		$files_st	= $stat_files;
		$users_st	= $stat_users;
		$sizes_st	= Customfile_size($stat_sizes);	
		//$lstfle_st	= $stat_last_file;
		$lst_dl_st	= date("d-m-Y H:a", $stat_last_f_del);
		$s_c_t		= $stat_counter_today;
		$s_c_y		= $stat_counter_yesterday;
		$s_c_a		= $stat_counter_all;
		
		($hook = kleeja_run_hook('stats_go_page')) ? eval($hook) : null; //run hook
		
	break; 

	
	default:
	($hook = kleeja_run_hook('default_go_page')) ? eval($hook) : null; //run hook	
	
	kleeja_err($lang['ERROR_NAVIGATATION']);
	
	break;
}#end switch

($hook = kleeja_run_hook('end_go_page')) ? eval($hook) : null; //run hook

	//show style ...
	//header
	Saaheader($titlee);
		//tpl
		print $tpl->display($stylee);
	//footer
	Saafooter();
?>

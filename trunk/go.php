<?php
##################################################
#						Kleeja
#
# Filename : go.php
# purpose :  File for Navigataion .
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author$ , $Rev$,  $Date::                           $
##################################################

// security ..
define ('IN_INDEX' , true);
define ('IN_GO' , true);

//include imprtant file ..
include ('includes/common.php');

($hook = kleeja_run_hook('begin_go_page')) ? eval($hook) : null; //run hook

if(!isset($_GET['go']))
{
	$_GET['go'] = null;	
}

switch ($_GET['go'])
{
	case 'guide' : 

		$stylee	= 'guide';
		$titlee	= $lang['GUIDE'];
		
		//re oreder exts by group_id 
		uasort($g_exts, "group_id_order");
		uasort($u_exts, "group_id_order");

		
		//make it loop
		$gusts_data = array();
		$last_gg_id_was = 0;
		foreach($g_exts as $ext=>$data)
		{
			$group_d = kleeja_mime_groups($data['group_id']);
			
			$gusts_data[]	= array('ext'	=> $ext,
									'num'	=> Customfile_size($data['size']), //format size as kb, mb,...
									'group'	=> $data['group_id'],
									'group_name'	=> $group_d['name'],
									'realy_first_row'		=> $last_gg_id_was == 0 ? true : false,
									'is_first_row'	=> $last_gg_id_was != $data['group_id'] ? true :false,
									);
			$last_gg_id_was = $data['group_id'];
		}

		//make it loop
		$users_data = array();
		$last_gu_id_was = 0;
		foreach($u_exts as $ext=>$data)
		{
			$group_d = kleeja_mime_groups($data['group_id']);
			
			$users_data[]	= array('ext'	=> $ext,
									'num'	=> Customfile_size($data['size']), //format size as kb, mb,...
									'group'	=> $data['group_id'],
									'group_name'	=> $group_d['name'],
									'realy_first_row'	=> $last_gu_id_was == 0 ? true : false,
									'is_first_row'	=> $last_gu_id_was != $data['group_id'] ? true :false,
									);
			$last_gu_id_was = $data['group_id'];		
		}
		
		($hook = kleeja_run_hook('guide_go_page')) ? eval($hook) : null; //run hook
	
	break;
	
	case 'report' :
		
		//page info
		$stylee	= 'report';
		$titlee	= $lang['REPORT'];
		$id_d	= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$url_id	= ($config['mod_writer']) ? $config['siteurl'] . 'download' . $id_d . '.html' : $config['siteurl'] . 'download.php?id=' . $id_d;
		$action	= './go.php?go=report';
		$H_FORM_KEYS = kleeja_add_form_key('report');
		$NOT_USER = !$usrcp->name() ? true : false; 
		//no error yet 
		$ERRORS = false;
		
		//_post
		$t_rname = isset($_POST['rname']) ? htmlspecialchars($_POST['rname']) : ''; 
		$t_rmail = isset($_POST['rmail']) ? htmlspecialchars($_POST['rmail']) : ''; 
		$t_rtext = isset($_POST['rtext']) ? htmlspecialchars($_POST['rtext']) : ''; 
		
		if (!isset($_POST['submit']))
		{
			// first
			if (!isset($_GET['id']) || intval($_GET['id']) == 0)
			{
				kleeja_err($lang['NO_ID']);
			}
				
			($hook = kleeja_run_hook('no_submit_report_go_page')) ? eval($hook) : null; //run hook
		}
		else
		{
			$ERRORS	= array();
			
			($hook = kleeja_run_hook('submit_report_go_page')) ? eval($hook) : null; //run hook
			
			//check for form key
			if(!kleeja_check_form_key('report'))
			{
				$ERRORS[] = $lang['INVALID_FORM_KEY'];
			}
			if(!kleeja_check_captcha())
			{
				$ERRORS[]	= $lang['WRONG_VERTY_CODE'];
			}
			if ((empty($_POST['rname']) && $NOT_USER) || empty($_POST['rurl']))
			{
				$ERRORS[]	= $lang['EMPTY_FIELDS'] . ' : ' . (empty($_POST['rname']) && $NOT_USER ? ' [ ' . $lang['YOURNAME'] . ' ] ' : '')  
									. (empty($_POST['rurl']) ? '  [ ' . $lang['URL']  . ' ] ': '');
			}
			if(empty($_POST['rid']))
			{
				$ERRORS[]	= $lang['NO_ID'];
			}
			if (isset($_POST['rmail']) &&  !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", trim(strtolower($_POST['rmail']))) && $NOT_USER)
			{
				$ERRORS[]	= $lang['WRONG_EMAIL'];
			}
			if (strlen($_POST['rtext']) > 300)
			{
				$ERRORS[]	= $lang['NO_ME300RES'];
			}
			
			//no error , lets do process
			if(empty($ERRORS))
			{
				$name	= $NOT_USER ? (string) $SQL->escape($_POST['rname']) : $usrcp->name();
				$text	= (string) $SQL->escape($_POST['rtext']);
				$mail	= $NOT_USER ? (string) strtolower(trim($SQL->escape($_POST['rmail']))) : $usrcp->mail();
				$url	= (string) $SQL->real_escape($_POST['rurl']);
				$time 	= (int) time();
				$rid	= (int) intval($_POST['rid']);
				$ip		=  get_ip();


				$insert_query	= array('INSERT'	=> '`name` ,`mail` ,`url` ,`text` ,`time` ,`ip`',
										'INTO'		=> "`{$dbprefix}reports`",
										'VALUES'	=> "'$name', '$mail', '$url', '$text', '$time', '$ip'"
										);
					
				($hook = kleeja_run_hook('qr_insert_new_report')) ? eval($hook) : null; //run hook
			
				$SQL->build($insert_query);
					
				//update number of reports
				$update_query	= array('UPDATE'	=> "{$dbprefix}files",
										'SET'		=> 'report=report+1',
										'WHERE'		=> 'id=' . $rid,
											);
								
				($hook = kleeja_run_hook('qr_update_no_file_report')) ? eval($hook) : null; //run hook
					
				$SQL->build($update_query);
					
				$to = $config['sitemail2']; //administrator e-mail
				$message = $text . "\n\n\n\n" . 'URL :' . $url . ' - TIME : ' . date("d-m-Y h:i a", $time) . ' - IP:' . $ip;
				$subject = $lang['REPORT'];
				send_mail($to, $message, $subject, $mail, $name);
					
				kleeja_info($lang['THNX_REPORTED']);
					
			}
		}
		
		($hook = kleeja_run_hook('report_go_page')) ? eval($hook) : null; //run hook
	
	break; 
	
	
	case 'rules' :
	
		$stylee	= 'rules';
		$titlee	= $lang['RULES'];
		$contents = (strlen($ruless) > 3) ? stripslashes($ruless) : $lang['NO_RULES_NOW'];
		
		($hook = kleeja_run_hook('rules_go_page')) ? eval($hook) : null; //run hook
	
	break;
	
	
	case 'call' : 
		
		//page info
		$stylee	= 'call';
		$titlee	= $lang['CALL'];
		$action	= './go.php?go=call';
		$H_FORM_KEYS = kleeja_add_form_key('call');
		$NOT_USER = !$usrcp->name() ? true : false; 
		//no error yet 
		$ERRORS = false;
			
		//_post
		$t_cname = isset($_POST['cname']) ? htmlspecialchars($_POST['cname']) : ''; 
		$t_cmail = isset($_POST['cmail']) ? htmlspecialchars($_POST['cmail']) : ''; 
		$t_ctext = isset($_POST['ctext']) ? htmlspecialchars($_POST['ctext']) : ''; 
		
		($hook = kleeja_run_hook('no_submit_call_go_page')) ? eval($hook) : null; //run hook
		
		if (isset($_POST['submit']))
		{
			//after sumit
			$ERRORS	= array();
			
			($hook = kleeja_run_hook('submit_call_go_page')) ? eval($hook) : null; //run hook
			
			//check for form key
			if(!kleeja_check_form_key('call'))
			{
				$ERRORS[] = $lang['INVALID_FORM_KEY'];
			}
			if(!kleeja_check_captcha())
			{
				$ERRORS[] = $lang['WRONG_VERTY_CODE'];
			}
			if ((empty($_POST['cname']) && $NOT_USER)  || empty($_POST['ctext']) )
			{
				$ERRORS[]	= $lang['EMPTY_FIELDS'] . ' : ' . (empty($_POST['cname']) && $NOT_USER ? ' [ ' . $lang['YOURNAME'] . ' ] ' : '') 
								. (empty($_POST['ctext']) ? '  [ ' . $lang['TEXT']  . ' ] ': '');
			}
			if (isset($_POST['cmail']) && !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", trim(strtolower($_POST['cmail']))) && $NOT_USER)
			{
				$ERRORS[] = $lang['WRONG_EMAIL'];
			}
			if (strlen($_POST['ctext']) > 300)
			{
				$ERRORS[] = $lang['NO_ME300TEXT'];
			}
			
			//no errors ,lets do process
			if(empty($ERRORS))
			{
				$name	= $NOT_USER ? (string) $SQL->escape($_POST['cname']) : $usrcp->name();
				$text	= (string) $SQL->escape($_POST['ctext']);
				$mail	= $NOT_USER ? (string) strtolower(trim($SQL->escape($_POST['cmail']))) : $usrcp->mail();
				$timee	= (int)	time();
				$ip		=  get_ip();
					

				$insert_query	= array('INSERT'	=> "`name` ,`text` ,`mail` ,`time` ,`ip`",
										'INTO'		=> "`{$dbprefix}call`",
										'VALUES'	=> "'$name', '$text', '$mail', '$timee', '$ip'"
										);
					
				($hook = kleeja_run_hook('qr_insert_new_call')) ? eval($hook) : null; //run hook
			
				if ($SQL->build($insert_query))
				{
					send_mail($config['sitemail2'], $text  . "\n\n\n\n" . 'TIME : ' . date("d-m-Y h:i a", $timee) . ' - IP:' . $ip, $lang['CALL'], $mail, $name);
					kleeja_info($lang['THNX_CALLED']);
				}
			}
		}
		
		($hook = kleeja_run_hook('call_go_page')) ? eval($hook) : null; //run hook

	break;
	
	case 'del' :

		($hook = kleeja_run_hook('del_go_page')) ? eval($hook) : null; //run hook
		
		//stop .. check first ..
		if (!$config['del_url_file'])
		{
			kleeja_info($lang['NO_DEL_F'], $lang['E_DEL_F']);
		}

		//examples : 
		//f2b3a82060a22a80283ed961d080b79f
		//aa92468375a456de21d7ca05ef945212
		//
		$cd	= preg_replace('/[^0-9a-z]/i', '', $SQL->escape($_GET['cd'])); // may.. will protect

		if (empty($cd))
		{
			kleeja_err($lang['WRONG_URL']);
		}
		else
		{
			//to check
			if(isset($_GET['sure']) && $_GET['sure'] == 'ok')
			{
				$query = array('SELECT'=> 'f.id, f.name, f.folder, f.size',
								'FROM'	=> "{$dbprefix}files f",
								'WHERE'	=> "f.code_del='" . $cd . "'",
								'LIMIT'	=> '1',
							);
					
				($hook = kleeja_run_hook('qr_select_file_with_code_del')) ? eval($hook) : null; //run hook	
				
				$result	= $SQL->build($query);

				if ($SQL->num_rows($result) != 0)
				{
					while($row=$SQL->fetch_array($result))
					{
						@kleeja_unlink ($row['folder'] . "/" . $row['name']);
						//delete thumb
						if (file_exists($row['folder'] . "/thumbs/" . $row['name']))
						{
							@kleeja_unlink ($row['folder'] . "/thumbs/" . $row['name']);
						}
						
						$query_del = array(
											'DELETE' => "{$dbprefix}files",
											'WHERE'	=> "id='" . $row['id'] . "'"
											);
								
						($hook = kleeja_run_hook('qr_del_file_with_code_del')) ? eval($hook) : null; //run hook	
						
						if ($SQL->build($query_del))
						{
							//update number of stats
							$update_query	= array('UPDATE'	=> "{$dbprefix}stats",
													'SET'		=> 'files=files-1,sizes=sizes-' . $row['size'],
												);
							
							$SQL->build($update_query);
							kleeja_info($lang['DELETE_SUCCESFUL']);
						}
						
						break;//to prevent divel actions
					}
				
					$SQL->freeresult($result);
				}
			}
			else
			{
				kleeja_info($lang['ARE_YOU_SURE_DO_THIS'] . '<script type="text/javascript">
						function confirm_from()
						{
						if(confirm(\'' . $lang['ARE_YOU_SURE_DO_THIS'] . '\'))
							window.location = "go.php?go=del&sure=ok&cd=' . $cd . '";
						else
							window.location = "index.php";
						}
					</script>
				<body onload="javascript:confirm_from()">');
			}
		}#else

	break;
	
	
	case 'stats' :

		//stop .. check first ..
		if (!$config['allow_stat_pg'])
		{
			kleeja_info($lang['STATS_CLOSED'], $lang['STATS_CLOSED']);
		}
		
		$most_online = $stat_most_user_online_ever; 
		$on_muoe	 = gmdate("d-m-Y H:i a", $stat_last_muoe);
		//ok .. go on
		$titlee		= $lang['STATS'];
		$stylee		= 'stats';
		$files_st	= $stat_files;
		$users_st	= $stat_users;
		$sizes_st	= Customfile_size($stat_sizes);	
		$lst_dl_st	= ((int)$config['del_f_day'] <= 0) ? ' [ ' . $lang['CLOSED_FEATURE'] . ' ] ' : gmdate("d-m-Y H:i a", $stat_last_f_del);
		$lst_reg	= (empty($stat_last_user)) ? $lang['UNKNOWN'] : $stat_last_user;
		
		($hook = kleeja_run_hook('stats_go_page')) ? eval($hook) : null; //run hook
		
	break; 
	
	case 'down':
	
		#depreacted from 1rc6+, see download.php
		//go.php?go=down&n=$1&f=$2&i=$3
		if(isset($_GET['n']))
		{
			$url_file = ($config['mod_writer']) ? $config['siteurl'] . "download" . intval($_GET['i']) . ".html" : $config['siteurl'] . "download.php?id=" . intval($_GET['n']);
		}
		else
		{
			$url_file = $config['siteurl'];
		}
		
		redirect($url_file);
		$SQL->close();
		exit;
		
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
		echo $tpl->display($stylee);
	//footer
	Saafooter();

#<-- EOF

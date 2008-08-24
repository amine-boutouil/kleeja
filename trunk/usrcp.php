<?php
##################################################
#						Kleeja 
#
# Filename : usrcp.php 
# purpose :  every things for users.
# copyright 2007-2008 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : saanina
##################################################

// security .. 
define ( 'IN_INDEX' , true);
//include imprtant file .. 
include ('includes/common.php');

($hook = kleeja_run_hook('begin_usrcp_page')) ? eval($hook) : null; //run hook

//now we will navigate ;)
switch ($_GET['go'])
{ 	
	case "login" : 
	
			$stylee					= "login";
			$titlee					= $lang['LOGIN'];
			$action					= "usrcp.php?go=login";
			$forget_pass_link	= "usrcp.php?go=get_pass";
			
			($hook = kleeja_run_hook('login_before_submit')) ? eval($hook) : null; //run hook
			
			//logon before !
			if ($usrcp->name())
			{
				($hook = kleeja_run_hook('login_logon_before')) ? eval($hook) : null; //run hook
				
				$text	= $lang['LOGINED_BEFORE'].' ..<br / > <a href="usrcp.php?go=logout">' . $lang['LOGOUT'] . '</a>';
				kleeja_info($text);
			}
			elseif (isset($_POST['submit']))
			{
					($hook = kleeja_run_hook('login_after_submit')) ? eval($hook) : null; //run hook
					
					//for onlines
					$ip	=	(getenv('HTTP_X_FORWARDED_FOR')) ?  getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');
					if ($config['allow_online'] == 1)
					{
						$query_del = array(
													'DELETE'	=> "{$dbprefix}online",
													'WHERE'		=> "ip='" . $ip . "'"
													);
						($hook = kleeja_run_hook('qr_delete_onlines_in_login')) ? eval($hook) : null; //run hook
						
						if (!$SQL->build($query_del)) {die($lang['CANT_DELETE_SQL']);}
					}

					//login
					$ERRORS	=	'';
					if (empty($_POST['lname']) || empty($_POST['lpass']))
					{
						$ERRORS[]	=	$lang['EMPTY_FIELDS'];
					}
					elseif(!$usrcp->data($_POST['lname'],$_POST['lpass']))
					{
						$ERRORS[]	=	$lang['LOGIN_ERROR'];
					}
				
				
					if(empty($ERRORS))
					{
						$text	= $lang['LOGIN_SUCCESFUL'].' <br /> <a href="./index.php">'. $lang['HOME'] . '</a>';
						kleeja_info($text);
					}
					else
					{
						$errs	=	'';
						foreach($ERRORS as $r)
						{
								$errs	.= '- ' . $r . '. <br/>';
						}
						kleeja_err($errs);
					}
			}
	
		break;
		
		
		case "register" : 
		
			//config register
			if (!$config['register'] || $config['user_system'] !=1)
			{
				kleeja_info($lang['REGISTER_CLOSED'],$lang['PLACE_NO_YOU']);
			}
			

			//start check class
			$ch = new ocr_captcha;

			//logon before !
			if ($usrcp->name())
			{
				($hook = kleeja_run_hook('register_logon_before')) ? eval($hook) : null; //run hook
				kleeja_info($lang['REGISTERED_BEFORE']);
			}

			//no submit ! lets show form of register
			if (!isset($_POST['submit']))
			{
					$stylee	= "register";
					$titlee	= $lang['REGISTER'];
					$action	= "usrcp.php?go=register";
					$code	= $ch->display_captcha(true);
				
					($hook = kleeja_run_hook('register_no_submit')) ? eval($hook) : null; //run hook
			}
			else // submit
			{			
						$ERRORS	=	'';
			
						($hook = kleeja_run_hook('register_submit')) ? eval($hook) : null; //run hook
						
						if (empty($_POST['lname']) || empty($_POST['lpass']) || empty($_POST['lmail']) )
						{
							$ERRORS[]	=	$lang['EMPTY_FIELDS'];
						}	
						else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['lmail'])))
						{
							$ERRORS[]	=	$lang['WRONG_EMAIL'];
						}
						else if (strlen($_POST['lname']) < 4 || strlen($_POST['lname']) > 30)
						{
							$ERRORS[]	=	$lang['WRONG_NAME'];
						}
						else if ( !$ch->check_captcha($_POST['public_key'],$_POST['code_answer']) )
						{
							$ERRORS[]	=	$lang['WRONG_VERTY_CODE'];
						}
						else if ($SQL->num_rows($SQL->query("select * from `{$dbprefix}users` where name='$_POST[lname]' ")) !=0 )
						{
							$ERRORS[]	=	$lang['EXIST_NAME'];
						}
						else if ($SQL->num_rows($SQL->query("select * from `{$dbprefix}users` where mail='$_POST[lmail]' ")) !=0 )
						{
							$ERRORS[]	=	$lang['EXIST_EMAIL'];
						}
						
						//no errors, lets do process
						if(empty($ERRORS))	 
						{
							$name			= (string) $SQL->escape($_POST['lname']);
							$pass			= (string) md5($SQL->escape($_POST['lpass']));
							$mail			= (string) $_POST['lmail'];
							$session_id	= (string)  session_id();
							
							$insert_query = array(
													'INSERT'	=> 'name ,password ,mail,admin, session_id',
													'INTO'		=> "{$dbprefix}users",
													'VALUES'	=> "'$name', '$pass', '$mail','0','$session_id'"
												);
							
							($hook = kleeja_run_hook('qr_insert_new_user_register')) ? eval($hook) : null; //run hook

							if (!$SQL->build($insert_query))
							{
								kleeja_err($lang['CANT_INSERT_SQL']);	
							}	
							else
							{
								$text	= $lang['REGISTER_SUCCESFUL'] . '<a href="usrcp.php?go=login">' . $lang['LOGIN'] . '</a>';
								kleeja_info($text);
							}
							
							//update number of stats
							$update_query = array(
													'UPDATE'	=> "{$dbprefix}stats",
													'SET'			=> 'users=users+1',
												);
							
							($hook = kleeja_run_hook('qr_update_no_users_register')) ? eval($hook) : null; //run hook
							if (!$SQL->build($update_query))	die($lang['CANT_UPDATE_SQL']);
						}
						else
						{
							$errs	=	'';
							foreach($ERRORS as $r)
							{
									$errs	.= '- ' . $r . '. <br/>';
							}
							kleeja_err($errs);
						}
			}
		
		break;
		
		
		case "logout" :
	
			($hook = kleeja_run_hook('begin_logout')) ? eval($hook) : null; //run hook
			
			if ($usrcp->logout())
			{
				if ($config['allow_online'] == 1)
				{
					//for onlines
					$ip	=	(getenv('HTTP_X_FORWARDED_FOR')) ?  getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');
					
					$query_del = array(
												'DELETE'	=> "{$dbprefix}online",
												'WHERE'	=> "ip='" . $SQL->escape($ip) . "'"
												);
					($hook = kleeja_run_hook('qr_delete_onlines_in_logout')) ? eval($hook) : null; //run hook

					if (!$SQL->build($query_del))	die($lang['CANT_DELETE_SQL']);
				}
				
				$text	= $lang['LOGOUT_SUCCESFUL'] . '<br /> <a href="index.php">' . $lang['HOME'] . '</a>';
				kleeja_info($text, '', 1);
			}
			else
			{
				kleeja_err($lang['LOGOUT_ERROR']);
			}
		
			($hook = kleeja_run_hook('end_logout')) ? eval($hook) : null; //run hook
			
		break;
		
		
		case "fileuser" : 
		
			($hook = kleeja_run_hook('begin_fileuser')) ? eval($hook) : null; //run hook
			
			//fileuser is closed ?
			if ($config['enable_userfile'] !=1 && $usrcp->admin()==false)
			{
				kleeja_info($lang['USERFILE_CLOSED'],$lang['CLOSED_FEATURE']);
			}
			
			//some vars
			$stylee	= "fileuser";
			$titlee	= $lang['FILEUSER'];
			
			$user_id	= intval($_GET['id']);
			$user_id	= (!$user_id && $usrcp->id()!==false) ? $usrcp->id() : $user_id;
			
			//te get userdata!!
			$data_user = $usrcp->get_data('name, show_my_filecp', $user_id);
			$user_name		=   (!$data_user['name']) ? false : $data_user['name'];
			$show_my_filecp	=  $data_user['show_my_filecp'];
			
			//new feature 1rc5
			if($show_my_filecp == 1 && ($usrcp->id() != $user_id))
			{
				kleeja_info($lang['USERFILE_CLOSED'],$lang['CLOSED_FEATURE']);		
			}
			
			$query = array(
								'SELECT'		=> 'f.id, f.name, f.folder, f.type',
								'FROM'			=> "{$dbprefix}files f",
								'WHERE'		=> "f.user='" . $user_id . "'",
								'ORDER BY'	=>	'f.id DESC',
								);
						
			/////////////pager 
			$result_p			= $SQL->build($query);
			$nums_rows		= $SQL->num_rows($result_p);
			$currentPage		= (isset($_GET['page']))? intval($_GET['page']) : 1;
			$Pager				= new SimplePager($perpage,$nums_rows,$currentPage);
			$start				= $Pager->getStartRow();
			
			$your_fileuser	=  $config['siteurl'].(($config['mod_writer'])? 'fileuser_'.$usrcp->id().'.html' : 'usrcp.php?go=fileuser&amp;id='.$usrcp->id() );
			$filecp_link		= ($user_id==$usrcp->id()) ?  $config['siteurl'].(($config['mod_writer'])? 'filecp.html':'usrcp.php?go=filecp' ) : false;
			$total_pages		= $Pager->getTotalPages(); 
			$linkgoto			= ($config['mod_writer']) ? $config['siteurl'].'fileuser_'.$user_id.'.html' : $config['siteurl'].'usrcp.php?go=fileuser&amp;id='.$user_id;
			$page_nums		= $Pager->print_nums($linkgoto); 
				
			$no_results = false;
			if($nums_rows != 0)
			{			
				$query['LIMIT']		=	 "$start, $perpage";
				($hook = kleeja_run_hook('qr_select_files_in_fileuser')) ? eval($hook) : null; //run hook
				
				$result	=	$SQL->build($query);
				while($row=$SQL->fetch_array($result))
				{
					//make new lovely arrays !!
					$arr[] = array(	'id'			=>$row['id'],
											'name'		=>'<a href="'.(($config[mod_writer])?  $config[siteurl].'download'.$row['id'].'.html': $config[siteurl]."download.php?id=".$row['id']  ).'" target="blank">'.$row['name'].'</a>',
											'icon_link'	=>(file_exists("images/filetypes/".$row['type'].".gif"))? "images/filetypes/".$row['type'].".gif" : 'images/filetypes/file.gif',
											'file_type'	=> $row['type']
							);
				}
				$SQL->freeresult($result);
			}
			else #nums_rows
			{ 
				$no_results = true;
			}
		
		($hook = kleeja_run_hook('end_fileuser')) ? eval($hook) : null; //run hook
		
		break;
		
		case "filecp" :
		
			($hook = kleeja_run_hook('begin_filecp')) ? eval($hook) : null; //run hook
			
			$stylee		= "filecp";
			$titlee		= $lang['FILECP'];
			$action		= "usrcp.php?go=filecp";
			

			//te get files and update them !!
			$query = array(
						'SELECT'		=> 'f.id ,f.name, f.folder',
						'FROM'			=> "{$dbprefix}files f",
						'WHERE'		=> 'f.user='.$usrcp->id(),
						'ORDER BY'	=> 'f.id DESC',
						);
						
			/////////////pager 
			$result_p		= $SQL->build($query);
			$nums_rows	= $SQL->num_rows($result_p);
			$currentPage	= (isset($_GET['page']))? intval($_GET['page']) : 1;
			$Pager			= new SimplePager($perpage, $nums_rows, $currentPage);
			$start			= $Pager->getStartRow();
			$linkgoto 		= ($config['mod_writer']) ? $config['siteurl'].'filecp.html' : $config['siteurl'].'usrcp.php?go=filecp';
			$page_nums	= $Pager->print_nums($linkgoto); 
			$total_pages	= $Pager->getTotalPages(); 
			
			//now, there is no result
			$no_results = false;
			
			if($nums_rows != 0)
			{
				$query['LIMIT']		=	 "$start, $perpage";
				($hook = kleeja_run_hook('qr_select_files_in_filecp')) ? eval($hook) : null; //run hook
				
				$result	=	$SQL->build($query);
				while($row=$SQL->fetch_array($result))
				{
					$del[$row['id']] = (isset($_POST["del_".$row['id']])) ? $_POST["del_".$row['id']] : "";
					
					//make new lovely arrays !!
					$arr[] = array(	'id'		=> $row['id'],
											'name'	=> '<a href="' . (($config['mod_writer']) ?  $config['siteurl'] . 'download' . $row['id'] . '.html' : $config['siteurl']. "download.php?id=" . $row['id']  ) . '" target="blank">' . $row['name'] . '</a>'
										);
										
						//when submit !!
						if (isset($_POST['submit_files']))
						{
							($hook = kleeja_run_hook('submit_in_filecp')) ? eval($hook) : null; //run hook	
								
							if ($del[$row['id']])
							{
								$query_del = array(
													'DELETE'		=> "{$dbprefix}files",
													'WHERE'		=> "id='".intval($row['id'])."'"
												);
												
								($hook = kleeja_run_hook('qr_del_files_in_filecp')) ? eval($hook) : null; //run hook	
								if (!$SQL->build($query_del)) {die($lang['CANT_DELETE_SQL']);}		
								
								//delete from folder .. 
								@unlink ($row['folder'] . "/" . $row['name'] );
								//delete thumb
								if (file_exists($row['folder'] . "/thumbs/" . $row['name'] ))
								{
									@unlink ($row['folder'] . "/thumbs/" . $row['name'] );
								}
								
							}
							//show msg
							kleeja_info($lang['FILES_UPDATED']);
						}
				}
				$SQL->freeresult($result);
				
				($hook = kleeja_run_hook('end_filecp')) ? eval($hook) : null; //run hook
		}
		else #nums_rows
		{
			$no_results = true;
		}
				
		break;
		
		case "profile" : 
		
			//no logon before 
			if (!$usrcp->name())
			{
				kleeja_info($lang['USER_PLACE'],$lang['PLACE_NO_YOU']);
			}

			$stylee					= "profile";
			$titlee					= $lang['PROFILE'];
			$action					= "usrcp.php?go=profile";
			$name					= $usrcp->name();
			$mail					= $usrcp->mail();
			$show_my_filecp	= $usrcp->get_data('show_my_filecp');
			$data_forum			= ($config[user_system]==1) ? true : false ;
			$goto_forum_link	= $forum_path;
			
			($hook = kleeja_run_hook('no_submit_profile')) ? eval($hook) : null; //run hook
			
			//
			// after submit
			//
			if (isset($_POST['submit_data']))
			{	
				$ERRORS	=	'';
				
				($hook = kleeja_run_hook('submit_profile')) ? eval($hook) : null; //run hook
				
				if(empty($_POST['pname']) || empty($_POST['pmail']))
				{
					$ERRORS[]	=	$lang['EMPTY_FIELDS'];
				}	
				elseif(!empty($_POST['ppass_new'])  && (($_POST['ppass_new'] !=  $_POST['ppass_new2']) 
						||  empty($_POST['ppass_old']) || (!$usrcp->data($usrcp->name(), $_POST['ppass_old']))))
				{
					$ERRORS[]	=	$lang['PASS_O_PASS2'];
				}
				else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['pmail'])))
				{
					$ERRORS[]	=	$lang['WRONG_EMAIL'];
				}
				
				//no errors , do it
				if(empty($ERRORS))
				{
						$mail					= "mail='" . $SQL->escape($_POST['pmail']) . "',";
						$show_my_filecp	= "show_my_filecp='" . intval($_POST['show_my_filecp']) . "'";
						$pass					= (!empty($_POST['ppass_new'])) ? "password='" . md5($SQL->escape($_POST['ppass_new'])) . "'" : "";
						$comma				= (!empty($_POST['ppass_new']))? "," : "";
						$id						= (int)		$usrcp->id();
						
						$update_query = array(
													'UPDATE'	=> "{$dbprefix}users",
													'SET'			=> $mail.$show_my_filecp.$comma.$pass,
													'WHERE'		=> "id='" . $id . "'",
												);
								
						($hook = kleeja_run_hook('qr_update_data_in_profile')) ? eval($hook) : null; //run hook
						if (!$SQL->build($update_query))	die($lang['CANT_UPDATE_SQL']);
						
						//msg
						kleeja_info($lang['DATA_CHANGED_O_LO']);
				}
				else
				{
							$errs	=	'';
							foreach($ERRORS as $r)
							{
									$errs	.= '- ' . $r . '. <br/>';
							}
							kleeja_err($errs);
				}

		}#else submit
		
		($hook = kleeja_run_hook('end_profile')) ? eval($hook) : null; //run hook
		
		break; 
		
		
		case "get_pass" : 
		
			//config register
			if ($config['user_system'] !=1)
			{
				$text = '<a href="' . $forum_path . '">' . $lang['LOST_PASS_FORUM'] . '</a>';
				kleeja_info($text,$lang['PLACE_NO_YOU']);
			}
			
			//logon before ?
			if ($usrcp->name())
			{
				($hook = kleeja_run_hook('get_pass_logon_before')) ? eval($hook) : null; //run hook
				
				kleeja_info($lang['LOGINED_BEFORE']);
			}
			
			//no submit
			if (!isset($_POST['submit']))
			{
				$stylee		= "get_pass";
				$titlee		= $lang['GET_LOSTPASS'];
				$action		= "usrcp.php?go=get_pass";
				
				($hook = kleeja_run_hook('no_submit_get_pass')) ? eval($hook) : null; //run hook
			}
			else // submit
			{ 
			
				$ERRORS	=	'';
				($hook = kleeja_run_hook('submit_get_pass')) ? eval($hook) : null; //run hook
				
				if (empty($_POST['rmail']))
				{
					$ERRORS[]	=	$lang['EMPTY_FIELDS'];
				}	
				else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['rmail'])))
				{
					$ERRORS[]	=	$lang['WRONG_EMAIL'];
				}
				else if ($SQL->num_rows($SQL->query("select * from `{$dbprefix}users` where mail='$_POST[rmail]' ")) ==0 )
				{
					$ERRORS[]	=	$lang['WRONG_DB_EMAIL'];
				}
				
				//no errors, lets do it
				if(empty($ERRORS))
				{
							$query = array(
												'SELECT'	=> 'u.*',
												'FROM'		=> "{$dbprefix}users u",
												'WHERE'	=> "u.mail='" . $_POST['rmail'] . "'"
											);
									
							($hook = kleeja_run_hook('qr_select_mail_get_pass')) ? eval($hook) : null; //run hook
							$result	=	$SQL->build($query);
							
							while($row=$SQL->fetch_array($result))
							{
								$newpass	= substr(md5(time()),0,5);
								$to			= $row['mail'];
								$subject	= $lang['GET_LOSTPASS'] . ':' . $config['sitename'];
								$message	= "\n " . $lang['WELCOME'] . " " . $row['name']."\r\n " . $lang['GET_LOSTPASS_MSG'] . "\r\n " . $lang['PASSWORD'] . " : " . $newpass . "\r\n\r\n kleeja Script";
								$headers	=	'From: ' . $config['sitename']. '<' . $config['sitemail'] . '>' . "\r\n" .
												'MIME-Version: 1.0' . "\r\n" .
												'X-Mailer: PHP/' . phpversion();
												
								$id			= (int)		$row['id'];
								
								$update_query = array(
														'UPDATE'	=> "{$dbprefix}users",
														'SET'			=> "password = '" . md5($SQL->escape($newpass)) . "'",
														'WHERE'	=> 'id=' . $id,
													);
										
								($hook = kleeja_run_hook('qr_update_newpass_get_pass')) ? eval($hook) : null; //run hook
								if (!$SQL->build($update_query)){ die($lang['CANT_UPDATE_SQL']);}
							}
							
							//send it
							$send =  @mail($to, $subject, $message, $headers);
							
							if (!$send)
							{
								kleeja_err($lang['CANT_SEND_NEWPASS']);
							}	
							else
							{
								$text	= $lang['OK_SEND_NEWPASS'] . '<a href="usrcp.php?go=login">' . $lang['LOGIN'] . '</a>';
								kleeja_info($text);	
							}
							
							//no need of this var
							unset($newpass);
					}
					else
					{
							$errs	=	'';
							foreach($ERRORS as $r)
							{
									$errs	.= '- ' . $r . '. <br/>';
							}
							kleeja_err($errs);
					}
			}
			
		($hook = kleeja_run_hook('end_get_pass')) ? eval($hook) : null; //run hook
		
		break; 
		
		($hook = kleeja_run_hook('another_case_usrcp_page')) ? eval($hook) : null; //run hook
		
		default:
		
		($hook = kleeja_run_hook('default_usrcp_page')) ? eval($hook) : null; //run hook

		kleeja_err($lang['ERROR_NAVIGATATION']);
		
		break;
	}#end switch
	
	($hook = kleeja_run_hook('end_usrcp_page')) ? eval($hook) : null; //run hook
	//show style ...
	if (!$titlee) $titlee = $lang['USERS_SYSTEM'];
	
	//header
	Saaheader($titlee);
		//tpl
		print $tpl->display($stylee);
	//footer
	Saafooter();
?>
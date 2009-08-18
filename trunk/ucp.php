<?php
##################################################
#						Kleeja 
#
# Filename : ucp.php 
# purpose :  every things for users.
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author$ , $Rev$,  $Date::                           $
##################################################

// security .. 
define ( 'IN_INDEX' , true);

if(isset($_GET['go']))
{
	switch($_GET['go'])
	{
		case 'login': 
			define ('IN_LOGINPAGE' , true);
		case 'logout': case 'get_pass':
			define ('IN_LOGIN' , true);
		break;
		case 'register':
			define ('IN_REGISTER' , true);
		break;
	}
}

if(isset($_GET['go']) && $_GET['go'] == 'login' && isset($_POST['submit']))
{
	define('IN_LOGIN_POST', true);
}

//include imprtant file .. 
include ('includes/common.php');

($hook = kleeja_run_hook('begin_usrcp_page')) ? eval($hook) : null; //run hook

//now we will navigate ;)
if(!isset($_GET['go']))
{
	$_GET['go'] = null;	
}

switch ($_GET['go'])
{ 	
	//
	//login page
	//
	case 'login' : 
			
			//page info
			$stylee					= 'login';
			$titlee					= $lang['LOGIN'];
			$action					= 'ucp.php?go=login' . (isset($_GET['return']) ? '&amp;return=' . htmlspecialchars($_GET['return']) : '');
			$forget_pass_link		= 'ucp.php?go=get_pass';
			$H_FORM_KEYS			= kleeja_add_form_key('login');
			//no error yet 
			$ERRORS = false;
			
			//_post
			$t_lname = isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : ''; 
			$t_lpass = isset($_POST['lpass']) ? htmlspecialchars($_POST['lpass']) : ''; 
			
			($hook = kleeja_run_hook('login_before_submit')) ? eval($hook) : null; //run hook
			
			//logon before !
			if ($usrcp->name())
			{
				($hook = kleeja_run_hook('login_logon_before')) ? eval($hook) : null; //run hook
				
				$errorpage = true;
				$text	= $lang['LOGINED_BEFORE'] . ' ..<br /> <a href="' . $config['siteurl']  . ($config['mod_writer'] ?  'logout.html' : 'ucp.php?go=logout') . '">' . $lang['LOGOUT'] . '</a>';
				kleeja_info($text);
			}
			elseif (isset($_POST['submit']))
			{
				$ERRORS	= array();
					
				($hook = kleeja_run_hook('login_after_submit')) ? eval($hook) : null; //run hook

				//check for form key
				if(!kleeja_check_form_key('login'))
				{
					$ERRORS[] = $lang['INVALID_FORM_KEY'];
				}
				if (empty($_POST['lname']) || empty($_POST['lpass']))
				{
					$ERRORS[] = $lang['EMPTY_FIELDS'];
				}
				elseif(!$usrcp->data($_POST['lname'], $_POST['lpass'], false, $_POST['remme']))
				{
					$ERRORS[] = $lang['LOGIN_ERROR'];
				}
					
				
				if(empty($ERRORS))
				{
					//delete him from online as guest
					if ($config['allow_online'] == '1')
					{
						$query_del	= array('DELETE'	=> "{$dbprefix}online",
											'WHERE'		=> "ip='" . get_ip() . "'"
											);
											
						($hook = kleeja_run_hook('qr_delete_onlines_in_login')) ? eval($hook) : null; //run hook
							
						$SQL->build($query_del);
					}
						
					if(isset($_GET['return']))
					{
						redirect('./' . str_replace(array('ooklj1oo', 'ooklj2oo', 'ooklj3oo'), array('?', '/', '='), urlencode($_GET['return'])));
						$SQL->close();
						exit;
					}
						
					$errorpage = true;
					($hook = kleeja_run_hook('login_data_no_error')) ? eval($hook) : null; //run hook
					$text	= $lang['LOGIN_SUCCESFUL'] . ' <br /> <a href="' . $config['siteurl'] . '">' . $lang['HOME'] . '</a>';
					kleeja_info($text, '', true, 'index.php');
				}
			}
			
	
		break;
		
		//
		//register page
		//
		case 'register' : 
		
			//page info
			$stylee	= 'register';
			$titlee	= $lang['REGISTER'];
			$action	= 'ucp.php?go=register';
			$H_FORM_KEYS = kleeja_add_form_key('register');
			//no error yet 
			$ERRORS = false;
			
			//config register
			if ($config['register'] != '1' &&  $config['user_system'] == '1')
			{
				kleeja_info($lang['REGISTER_CLOSED'], $lang['PLACE_NO_YOU']);
			}
			else if ($config['user_system'] != '1')
			{
				($hook = kleeja_run_hook('register_not_default_sys')) ? eval($hook) : null; //run hook
				$forum_path = (empty($script_register_url)) ? ($script_path[0] == '/' ? '..' : '../') .  $script_path : $script_register_url;
				kleeja_info('<a href="' . $forum_path . '" title="' . $lang['REGISTER'] . '">' . $lang['REGISTER']. '</a>', $lang['REGISTER']);
			}
			
			//logon before !
			if ($usrcp->name())
			{
				($hook = kleeja_run_hook('register_logon_before')) ? eval($hook) : null; //run hook
				kleeja_info($lang['REGISTERED_BEFORE']);
			}
			
			
			//_post
			$t_lname = isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : ''; 
			$t_lpass = isset($_POST['lpass']) ? htmlspecialchars($_POST['lpass']) : ''; 
			$t_lmail = isset($_POST['lmail']) ? htmlspecialchars($_POST['lmail']) : ''; 
			
			//no submit 
			if (!isset($_POST['submit']))
			{
				($hook = kleeja_run_hook('register_no_submit')) ? eval($hook) : null; //run hook
			}
			else // submit
			{			
				$ERRORS = array();
			
				($hook = kleeja_run_hook('register_submit')) ? eval($hook) : null; //run hook
						
				//check for form key
				if(!kleeja_check_form_key('register'))
				{
					$ERRORS[] = $lang['INVALID_FORM_KEY'];
				}
				if(!kleeja_check_captcha())
				{
					$ERRORS[] = $lang['WRONG_VERTY_CODE'];
				}
				if (trim($_POST['lname'])=='' || trim($_POST['lpass'])=='' || trim($_POST['lmail'])=='')
				{
					$ERRORS[] = $lang['EMPTY_FIELDS'];
				}	
				if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['lmail'])))
				{
					$ERRORS[] = $lang['WRONG_EMAIL'];
				}
				if (strlen(trim($_POST['lname'])) < 4 || strlen(trim($_POST['lname'])) > 20)
				{
					$ERRORS[] = $lang['WRONG_NAME'];
				}
				else if ($SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE clean_name='" . trim($SQL->escape($usrcp->cleanusername($_POST["lname"]))) . "'")) !=0 )
				{
					$ERRORS[] = $lang['EXIST_NAME'];
				}
				else if ($SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE mail='" . strtolower(trim($SQL->escape($_POST["lmail"]))) . "'")) !=0 )
				{
					$ERRORS[] = $lang['EXIST_EMAIL'];
				}
						
				//no errors, lets do process
				if(empty($ERRORS))	 
				{
					$name			= (string) $SQL->escape(trim($_POST['lname']));
					$user_salt		= (string) substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
					$pass			= (string) $usrcp->kleeja_hash_password($SQL->escape(trim($_POST['lpass'])) . $user_salt);
					$mail			= (string) strtolower(trim($SQL->escape($_POST['lmail']))); // security ;)
					$session_id		= (string) session_id();
					$clean_name		= (string) $usrcp->cleanusername($name);
							
					$insert_query	= array('INSERT'	=> 'name ,password, password_salt ,mail,admin, session_id, clean_name',
											'INTO'		=> "{$dbprefix}users",
											'VALUES'	=> "'$name', '$pass', '$user_salt', '$mail','0','$session_id','$clean_name'"
												);
							
					($hook = kleeja_run_hook('qr_insert_new_user_register')) ? eval($hook) : null; //run hook

					if ($SQL->build($insert_query))
					{
						$last_user_id = $SQL->insert_id();
								
						($hook = kleeja_run_hook('ok_added_users_register')) ? eval($hook) : null; //run hook
						$text	= $lang['REGISTER_SUCCESFUL'] . '<a href="' .  $config['siteurl']  . ($config['mod_writer'] ?  'login.html' : 'ucp.php?go=login') . '">' . $lang['LOGIN'] . '</a>';
						//update number of stats
						$update_query	= array('UPDATE'	=> "{$dbprefix}stats",
												'SET'		=> "users=users+1,lastuser='$name'",
												);
												
						($hook = kleeja_run_hook('qr_update_no_users_register')) ? eval($hook) : null; //run hook
						$SQL->build($update_query);
						kleeja_info($text);
					}
				}
			}
		
		break;
		
		//
		//logout action
		//
		case 'logout' :
		
			($hook = kleeja_run_hook('begin_logout')) ? eval($hook) : null; //run hook
			
			if ($usrcp->logout())
			{
				if ($config['allow_online'] == 1)
				{
					$query_del	= array('DELETE'	=> "{$dbprefix}online",
										'WHERE'		=> "ip='" .  get_ip() . "'"
									);
									
					($hook = kleeja_run_hook('qr_delete_onlines_in_logout')) ? eval($hook) : null; //run hook

					$SQL->build($query_del);
				}
				
				$text	= $lang['LOGOUT_SUCCESFUL'] . '<br /> <a href="' .  $config['siteurl']  . '">' . $lang['HOME'] . '</a>';
				kleeja_info($text, '', 1);
			}
			else
			{
				kleeja_err($lang['LOGOUT_ERROR']);
			}
		
			($hook = kleeja_run_hook('end_logout')) ? eval($hook) : null; //run hook
			
		break;
		
		//
		//files user page
		//
		case 'fileuser' : 
			
			($hook = kleeja_run_hook('begin_fileuser')) ? eval($hook) : null; //run hook
			
			//fileuser is closed ?
			if ($config['enable_userfile'] != '1' && !$usrcp->admin())
			{
				kleeja_info($lang['USERFILE_CLOSED'], $lang['CLOSED_FEATURE']);
			}
			
			//some vars
			$stylee	= 'fileuser';
			$titlee	= $lang['FILEUSER'];
			
			$user_id_get	= (isset($_GET['id'])) ? intval($_GET['id']) : null;
			$user_id		= (!$user_id_get && $usrcp->id()) ? $usrcp->id() : $user_id_get;
			
			//no logon before 
			if (!$usrcp->name() && !isset($_GET['id']))
			{
				kleeja_err($lang['USER_PLACE'], $lang['PLACE_NO_YOU'], true, 'index.php');
			}
			
			//to get userdata!!
			$data_user = ($config['user_system'] == 1) ? $usrcp->get_data('name, show_my_filecp', $user_id) : array('name' => $usrcp->name(), 'show_my_filecp' => '1');
			
			if(!$data_user['name'])
			{
				kleeja_err($lang['NOT_EXSIT_USER'], $lang['PLACE_NO_YOU']);
			}
			
			if(!$data_user['show_my_filecp'] && ($usrcp->id() != $user_id) && !$usrcp->admin())
			{
				kleeja_info($lang['USERFILE_CLOSED'], $lang['CLOSED_FEATURE']);
			}
			
			$query	= array(
							'SELECT'	=> 'f.id, f.name, f.real_filename, f.folder, f.type, f.uploads, f.last_down',
							'FROM'		=> "{$dbprefix}files f",
							'WHERE'		=> "f.user='" . $user_id . "'",
							'ORDER BY'	=> 'f.id DESC'
						);
						
			//pager 
			$result_p			= $SQL->build($query);
			$nums_rows			= $SQL->num_rows($result_p);
			$currentPage		= (isset($_GET['page'])) ? intval($_GET['page']) : 1;
			$Pager				= new SimplePager($perpage,$nums_rows,$currentPage);
			$start				= $Pager->getStartRow();
			
			$your_fileuser		= $config['siteurl'] . ($config['mod_writer'] ? 'fileuser-' . $usrcp->id() . '.html' : 'ucp.php?go=fileuser&amp;id=' . $usrcp->id());
			$filecp_link		= $user_id == $usrcp->id() ? $config['siteurl'] . ($config['mod_writer'] ? 'filecp.html' : 'ucp.php?go=filecp') : false;
			$total_pages		= $Pager->getTotalPages(); 
			$linkgoto			= $config['siteurl'] . ($config['mod_writer'] ?  'fileuser-' . $user_id : 'ucp.php?go=fileuser&amp;id=' . $user_id);
			$page_nums			= $Pager->print_nums($linkgoto); 
				
			$no_results = false;
			if($nums_rows == 0) 
			{
				if($config['user_system'] != '1' && ($usrcp->id() != $user_id))
				{
					$data_user['name'] = $usrcp->usernamebyid($user_id);
				}
				$user_name = (!$data_user['name']) ? false : $data_user['name'];
			}
			if($nums_rows != 0)
			{			
				$query['LIMIT'] = "$start, $perpage";
				($hook = kleeja_run_hook('qr_select_files_in_fileuser')) ? eval($hook) : null; //run hook
				
				$result	= $SQL->build($query);
				if($config['user_system'] != '1' && ($usrcp->id() != $user_id))
				{
					$data_user['name'] = $usrcp->usernamebyid($user_id);
				}
				$user_name = (!$data_user['name']) ? false : $data_user['name'];
				$i = ($currentPage * $perpage) - $perpage;
				while($row=$SQL->fetch_array($result))
				{
					++$i;
					$file_info = array('::ID::' => $row['id'], '::NAME::' => $row['name'], '::DIR::' => $row['folder'], '::FNAME::' => $row['real_filename']);
					
					$is_image = in_array(strtolower(trim($row['type'])), array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'tiff', 'tif')) ? true : false;
					$url = ($is_image) ? kleeja_get_link('image', $file_info) : kleeja_get_link('file', $file_info);
					
					//make new lovely arrays !!
					$arr[] = array(	'id'		=> $row['id'],
									'name'		=> '<a title="' . ($row['real_filename'] == '' ? $row['name'] : $row['real_filename']) . '"  href="' . $url . '" target="blank">' . ($row['real_filename'] == '' ? ((strlen($row['name']) > 40) ? substr($row['name'], 0, 40) . '...' : $row['name']) : ((strlen($row['real_filename']) > 40) ? substr($row['real_filename'], 0, 40) . '...' : $row['real_filename'])) . '</a>',
									'icon_link'	=>(file_exists("images/filetypes/".  $row['type'] . ".png"))? "images/filetypes/" . $row['type'] . ".png" : 'images/filetypes/file.png',
									'file_type'	=> $row['type'],
									'image_path'=> $is_image ? $url : '',
									'uploads'	=> $row['uploads'],
									'last_down'	=> !empty($row['last_down']) ? date("d-m-Y h:i a", $row['last_down']) : '...',
									'i'=> $i,
							);
				}
				
				$SQL->freeresult($result_p);
				$SQL->freeresult($result);
			}
			else #nums_rows
			{ 
				$no_results = true;
			}
		
		($hook = kleeja_run_hook('end_fileuser')) ? eval($hook) : null; //run hook
		
		break;
		
		//
		//control files page
		//
		case 'filecp' :
		
			($hook = kleeja_run_hook('begin_filecp')) ? eval($hook) : null; //run hook
			
			$stylee		= 'filecp';
			$titlee		= $lang['FILECP'];
			$H_FORM_KEYS = kleeja_add_form_key('filecp');
			
			//no logon before
			if (!$usrcp->name())
			{
				kleeja_info($lang['PLACE_NO_YOU'], $lang['USER_PLACE']);
			}

			//te get files and update them !!
			$query = array(
							'SELECT'	=> 'f.id ,f.name, f.real_filename, f.type, f.folder, f.size',
							'FROM'		=> "{$dbprefix}files f",
							'WHERE'		=> 'f.user=' . $usrcp->id(),
							'ORDER BY'	=> 'f.id DESC',
						);
				
			//pager 
			$result_p		= $SQL->build($query);
			$nums_rows		= $SQL->num_rows($result_p);
			$currentPage	= (isset($_GET['page']))? intval($_GET['page']) : 1;
			$Pager			= new SimplePager($perpage, $nums_rows, $currentPage);
			$start			= $Pager->getStartRow();
			$linkgoto		= $config['siteurl'] . ($config['mod_writer'] ? 'filecp' : 'ucp.php?go=filecp');
			$page_nums		= $Pager->print_nums($linkgoto); 
			$action			= "ucp.php?go=filecp&page={$currentPage}";
			$total_pages	= $Pager->getTotalPages(); 
			
			//now, there is no result
			$no_results = false;
			
			if($nums_rows != 0)
			{
				$query['LIMIT']	 = "$start, $perpage";
				($hook = kleeja_run_hook('qr_select_files_in_filecp')) ? eval($hook) : null; //run hook
				
				$result	= $SQL->build($query);
				
				$sizes = $num = 0;
				$i = ($currentPage * $perpage) - $perpage;
				while($row=$SQL->fetch_array($result))
				{
					$del[$row['id']] = (isset($_POST['del_' . $row['id']])) ? $_POST['del_' . $row['id']] : '';
				
					$file_info = array('::ID::' => $row['id'], '::NAME::' => $row['name'], '::DIR::' => $row['folder'], '::FNAME::' => $row['real_filename']);
					
					$is_image = in_array(strtolower(trim($row['type'])), array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'tiff', 'tif')) ? true : false;
					$url = ($is_image) ? kleeja_get_link('image', $file_info) : kleeja_get_link('file', $file_info);
					++$i;
					//make new lovely arrays !!
					$arr[] = array(	'id'	=> $row['id'],
									'name'	=> '<a title="' . ($row['real_filename'] == '' ? $row['name'] : $row['real_filename']) . '" href="' .  $url . '" target="blank">' . ($row['real_filename'] == '' ? ((strlen($row['name']) > 40) ? substr($row['name'], 0, 40) . '...' : $row['name']) : ((strlen($row['real_filename']) > 40) ? substr($row['real_filename'], 0, 40) . '...' : $row['real_filename'])) . '</a>',
									'i'		=> $i,
									'icon_link'	=>(file_exists('images/filetypes/' . $row['type'] . '.png'))? 'images/filetypes/' . $row['type'] . '.png' : 'images/filetypes/file.png',
									'file_type'	=> $row['type'],
								);
							
						//when submit !!
						if (isset($_POST['submit_files']))
						{
							($hook = kleeja_run_hook('submit_in_filecp')) ? eval($hook) : null; //run hook	
								
							//check for form key
							if(!kleeja_check_form_key('filecp'))
							{
								kleeja_info($lang['INVALID_FORM_KEY']);
							}
							
							if ($del[$row['id']])
							{
								//delete from folder .. 
								@kleeja_unlink ($row['folder'] . "/" . $row['name'] );
								
								//delete thumb
								if (file_exists($row['folder'] . "/thumbs/" . $row['name'] ))
								{
									@kleeja_unlink ($row['folder'] . "/thumbs/" . $row['name'] );
								}
								
								$ids[] = $row['id'];
								$num++;		
								$sizes += $row['size'];
							}
						}
				}
				if (isset($_POST['submit_files']))
				{
					//no files to delete
					if(isset($ids) && !empty($ids))
					{
						$query_del = array(	'DELETE'	=> "{$dbprefix}files",
											'WHERE'	=> "id IN (" . implode(',', $ids) . ")"
											);
												
						($hook = kleeja_run_hook('qr_del_files_in_filecp')) ? eval($hook) : null; //run hook	
						$SQL->build($query_del);
								
						//update number of stats
						$update_query	= array(
												'UPDATE'	=> "{$dbprefix}stats",
												'SET'		=> "sizes=sizes-$sizes,files=files-$num",
												);
							
						$SQL->build($update_query);
					}
				}
				
				$SQL->freeresult($result_p);
				$SQL->freeresult($result);
				
				($hook = kleeja_run_hook('end_filecp')) ? eval($hook) : null; //run hook
		}
		else #nums_rows
		{
			$no_results = true;
		}
		
		//after submit 
		if (isset($_POST['submit_files']))
		{
			//show msg
			if(isset($ids) && !empty($ids))
			{
				kleeja_info($lang['FILES_DELETED'], '', true, $action);
			}
			else
			{
				redirect($action);
			}
		}
				
		break;
		
		case 'profile' : 
		
			//no logon before 
			if (!$usrcp->name())
			{
				kleeja_info($lang['USER_PLACE'], $lang['PLACE_NO_YOU']);
			}

			$stylee		= 'profile';
			$titlee		= $lang['PROFILE'];
			$action		= 'ucp.php?go=profile';
			$name		= $usrcp->name();
			$mail		= $usrcp->mail();
			$show_my_filecp	= $usrcp->get_data('show_my_filecp');
			$data_forum		= ($config['user_system'] == 1) ? true : false ;
			$goto_forum_link= !empty($forum_path) ? $forum_path : '';
			$H_FORM_KEYS = kleeja_add_form_key('profile');
			//no error yet 
			$ERRORS = false;
			
			//_post
			$t_pppass_old = isset($_POST['pppass_old']) ? htmlspecialchars($_POST['pppass_old']) : ''; 
			$t_ppass_old = isset($_POST['ppass_old']) ? htmlspecialchars($_POST['ppass_old']) : ''; 
			$t_ppass_new = isset($_POST['ppass_new']) ? htmlspecialchars($_POST['ppass_new']) : ''; 
			$t_ppass_new2 = isset($_POST['ppass_new2']) ? htmlspecialchars($_POST['ppass_new2']) : ''; 
			
			($hook = kleeja_run_hook('no_submit_profile')) ? eval($hook) : null; //run hook
			
			//
			// after submit
			//
			if (isset($_POST['submit_data']))
			{	
				$ERRORS	= array();
				
				($hook = kleeja_run_hook('submit_profile')) ? eval($hook) : null; //run hook
				
				//check for form key
				if(!kleeja_check_form_key('profile'))
				{
					$ERRORS[] = $lang['INVALID_FORM_KEY'];
				}
				if(!empty($_POST['ppass_new'])  && (($_POST['ppass_new'] !=  $_POST['ppass_new2']) 
						||  empty($_POST['ppass_old']) || (!$usrcp->data($usrcp->name(), $_POST['ppass_old'], false, 900))))
				{
					$ERRORS[] = $lang['PASS_O_PASS2'];
				}
				if (!empty($_POST['pppass_old'])  && (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['pmail'])) || empty($_POST['pmail']) || (!$usrcp->data($usrcp->name(), $_POST['pppass_old'], false, 900))))
				{
					$ERRORS[] = $lang['WRONG_EMAIL'];
				}
				
				//no errors , do it
				if(empty($ERRORS))
				{
						$user_salt 		= substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
						$mail			= (!empty($_POST['pppass_old'])) ? "mail='" . $SQL->escape($_POST['pmail']) . "'," : '';
						$show_my_filecp	= "show_my_filecp='" . intval($_POST['show_my_filecp']) . "'";
						$pass			= (!empty($_POST['ppass_new'])) ? "password='" . $usrcp->kleeja_hash_password($SQL->escape($_POST['ppass_new']) . $user_salt) . "', password_salt='" . $user_salt . "'" : "";
						$comma			= (!empty($_POST['ppass_new']))? "," : "";
						$id				= (int) $usrcp->id();
						
						$update_query	= array('UPDATE'	=> "{$dbprefix}users",
												'SET'		=> $mail . $show_my_filecp . $comma . $pass, //comma mean "," char
												'WHERE'		=> "id='" . $id . "'",
												);
								
						($hook = kleeja_run_hook('qr_update_data_in_profile')) ? eval($hook) : null; //run hook
						
						$SQL->build($update_query);
						kleeja_info($lang['DATA_CHANGED_O_LO'], '', true, $action);
				}

		}#else submit
		
		($hook = kleeja_run_hook('end_profile')) ? eval($hook) : null; //run hook
		
		break; 
		
		//
		//reset password page
		//
		case 'get_pass' : 

			//if not default system, let's give him a link for integrated script
			if ($config['user_system'] != '1')
			{
				$text = '<a href="' . $forum_path . '">' . $lang['LOST_PASS_FORUM'] . '</a>';
				kleeja_info($text, $lang['PLACE_NO_YOU']);
			}
			
			//page info
			$stylee		= 'get_pass';
			$titlee		= $lang['GET_LOSTPASS'];
			$action		= 'ucp.php?go=get_pass';
			$H_FORM_KEYS = kleeja_add_form_key('get_pass');
			//no error yet 
			$ERRORS = false;
			
			
			//after sent mail .. come here 
			if(isset($_GET['activation_key']) && isset($_GET['uid']))
			{
				($hook = kleeja_run_hook('get_pass_activation_key')) ? eval($hook) : null; //run hook
				
				$h_key = htmlspecialchars($_GET['activation_key']);
				$u_id = intval($_GET['uid']);
				
				$result = $SQL->query("SELECT new_password FROM `{$dbprefix}users` WHERE hash_key='" . $SQL->escape($h_key) . "' AND id='" . $u_id . "'");
				if($SQL->num_rows($result))
				{
					$npass = $SQL->fetch_array($result);
					$npass = $npass['new_password'];
					//password now will be same as new password
					$update_query = array(
											'UPDATE'=> "{$dbprefix}users",
											'SET'	=> "password = '" . $npass . "', new_password = '', hash_key = ''",
											'WHERE'	=> 'id=' . $u_id,
										);
										
					($hook = kleeja_run_hook('qr_update_newpass_activation')) ? eval($hook) : null; //run hook
					$SQL->build($update_query);
					
					$text = $lang['OK_APPLY_NEWPASS'] . '<br /><a href="' . $config['siteurl']  . ($config['mod_writer'] ?  'login.html' : 'ucp.php?go=login') . '">' . $lang['LOGIN'] . '</a>';
					kleeja_info($text);
					exit;
				}
				
				//no else .. just do nothing cuz it's wrong and wrong mean spams !
				redirect($config['siteurl'], true, true);
				exit;//i dont trust functions :)
				
			}
			
			//logon before ?
			if ($usrcp->name())
			{
				($hook = kleeja_run_hook('get_pass_logon_before')) ? eval($hook) : null; //run hook
				kleeja_info($lang['LOGINED_BEFORE']);
			}
			
			//_post
			$t_rmail = isset($_POST['rmail']) ? htmlspecialchars($_POST['rmail']) : ''; 
			
			//no submit
			if (!isset($_POST['submit']))
			{
				
				($hook = kleeja_run_hook('no_submit_get_pass')) ? eval($hook) : null; //run hook
			}
			else // submit
			{ 
			
				$ERRORS	= array();
				
				($hook = kleeja_run_hook('submit_get_pass')) ? eval($hook) : null; //run hook
				//check for form key
				if(!kleeja_check_form_key('get_pass'))
				{
					$ERRORS[] = $lang['INVALID_FORM_KEY'];
				}
				if(!kleeja_check_captcha())
				{
					$ERRORS[] = $lang['WRONG_VERTY_CODE'];
				}
				if (empty($_POST['rmail']))
				{
					$ERRORS[] = $lang['EMPTY_FIELDS'];
				}	
				if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim(strtolower($_POST['rmail']))))
				{
					$ERRORS[] = $lang['WRONG_EMAIL'];
				}
				else if ($SQL->num_rows($SQL->query("SELECT name FROM `{$dbprefix}users` WHERE mail='" . $SQL->escape(strtolower($_POST['rmail'])) . "'")) == 0)
				{
					$ERRORS[] = $lang['WRONG_DB_EMAIL'];
				}
				
				//no errors, lets do it
				if(empty($ERRORS))
				{
							$query = array(	'SELECT'=> 'u.*',
											'FROM'	=> "{$dbprefix}users u",
											'WHERE'	=> "u.mail='" .  $SQL->escape(strtolower($_POST['rmail'])) . "'"
											);
									
							($hook = kleeja_run_hook('qr_select_mail_get_pass')) ? eval($hook) : null; //run hook
							$result	=	$SQL->build($query);
							
							while($row=$SQL->fetch_array($result))
							{
								//generate password
								$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
								$newpass = '';
								for ($i = 0; $i < 7; ++$i)
								{
									$newpass .= substr($chars, (mt_rand() % strlen($chars)), 1);
								}
								$hash_key = md5($newpass . time());
								
								$to			= $row['mail'];
								$subject	= $lang['GET_LOSTPASS'] . ':' . $config['sitename'];
								$activation_link = $config['siteurl'] . 'ucp.php?go=get_pass&activation_key=' . urlencode($hash_key) . '&uid=' . $row['id'];
								$message	= "\n " . $lang['WELCOME'] . " " . $row['name'] . "\r\n " . sprintf($lang['GET_LOSTPASS_MSG'], $activation_link, $newpass)  . "\r\n\r\n kleeja.com";

								$update_query = array(
														'UPDATE'=> "{$dbprefix}users",
														'SET'	=> "new_password = '" . md5($SQL->escape($newpass)) . "', hash_key = '" . $hash_key . "'",
														'WHERE'	=> 'id=' . $row['id'],
													);
										
								($hook = kleeja_run_hook('qr_update_newpass_get_pass')) ? eval($hook) : null; //run hook
								$SQL->build($update_query);
							}
							
							$SQL->freeresult($result);
							
							//send it
							$send =  send_mail($to, $message, $subject, $config['sitemail'], $config['sitename']);
							
							if (!$send)
							{
								kleeja_err($lang['CANT_SEND_NEWPASS']);
							}	
							else
							{
								$text	= $lang['OK_SEND_NEWPASS'] . '<br /><a href="' . $config['siteurl']  . ($config['mod_writer'] ?  'login.html' : 'ucp.php?go=login') . '">' . $lang['LOGIN'] . '</a>';
								kleeja_info($text);	
							}
							
							//no need of this var
							unset($newpass);
					}
			}
			
		($hook = kleeja_run_hook('end_get_pass')) ? eval($hook) : null; //run hook
		
		break; 
		
		//
		//add your own code here
		//
		default:
		
			($hook = kleeja_run_hook('default_usrcp_page')) ? eval($hook) : null; //run hook

			kleeja_err($lang['ERROR_NAVIGATATION']);
		
		break;
	}#end switch
	
	($hook = kleeja_run_hook('end_usrcp_page')) ? eval($hook) : null; //run hook
	
	
	//
	//show style ...
	//
	
	$titlee = (!$titlee) ? $lang['USERS_SYSTEM'] : $titlee;

	//
	//if it's not a default user system let's send custom charset and look for iconv 
	//
	if($config['user_system'] != '1' && isset($script_encoding) && $_GET['go'] == 'login' && function_exists('iconv') && !preg_match('/utf/i',strtolower($script_encoding)) && !defined('DISABLE_INTR'))
	{
		//send custom chaeset header
		header("Content-type: text/html; charset={$script_encoding}");
		//header
		Saaheader($titlee, true);
		//change login page encoding if kleeja is integrated with other script
		 echo iconv("UTF-8", strtoupper($script_encoding) . "//IGNORE", $tpl->display($stylee));	
		 $errorpage = false;
		 //footer
		Saafooter(true);	
	}
	else 
	{			
		//header
		Saaheader($titlee);
		echo $tpl->display($stylee);
		//footer
		Saafooter();
	}
	
#<-- EOF

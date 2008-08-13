<?php
##################################################
#						Kleeja 
#
# Filename : usr.php
# purpose :  get user data ..even from board database, its complicated ...: supoort many types of forums ..
# copyright 2007-2008 Kleeja.com ..
#class by : Saanina [@gmail.com]
# last edit by : saanina
##################################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}
  
class usrcp
{


				// this function like  traffic sign :)
				function data ($name, $pass)
				{
					global $config;
						
						($hook = kleeja_run_hook('data_func_usr_class')) ? eval($hook) : null; //run hook
						
						if ($config['user_system'] == 1) //normal 
						{
							return $this->normal($name,$pass);
						}
						elseif ($config['user_system'] ==2 )  // phpbb
						{
							return $this->phpbb($name,$pass);
						}
						elseif ($config['user_system'] == 3)  // vb [ worst forum]
						{
							return $this->vb($name,$pass);
						}
						elseif ($config['user_system'] == 4)  // mysmartbb
						{
							return $this->mysmartbb($name,$pass);
						}
						

				}
				
					
				//now ..  .. our table
				function normal ($name,$pass)
				{
					global $SQL,$dbprefix;
					
					$pass = md5($pass);
					
					$query = array(
								'SELECT'	=> '*',
								'FROM'		=> "`{$dbprefix}users`",
								'WHERE'		=> "name='$name' AND password='$pass'"
								);
								
					($hook = kleeja_run_hook('qr_select_usrdata_n_usr_class')) ? eval($hook) : null; //run hook			
					$result = $SQL->build($query);
					

					
					if ($SQL->num_rows($result) != 0 ) 
					{
						while($row=$SQL->fetch_array($result))
						{
							$_SESSION['USER_ID']	=	$row['id'];
							$_SESSION['USER_NAME']	=	$row['name'];
							$_SESSION['USER_MAIL']	=	$row['mail'];
							$_SESSION['USER_ADMIN']	=	$row['admin'];
							$_SESSION['USER_SESS']	=	session_id();
							$_SESSION['LAST_VISIT']	=	$row['last_visit'];
							($hook = kleeja_run_hook('qr_while_usrdata_n_usr_class')) ? eval($hook) : null; //run hook	
							
							//update session_id
							$id 		= (int) 	$row['id'];
							$session_id = (string)  session_id();
							
							$update_query = array(
												'UPDATE'	=> "`{$dbprefix}users`",
												'SET'		=> "session_id='" . $SQL->escape($session_id) . "' ,last_visit='". time() ."'",
												'WHERE'		=>	"id='" . $id ."'"
										);

							if (!$SQL->build($update_query)){ die($lang['CANT_UPDATE_SQL']);}
						
						}
						$SQL->freeresult($result);   
						unset($pass);

						return true;
					}
					else
					{
						return false;
					}
				
				}
				
				
				function phpbb ($name,$pass)
				{
					global $forum_srv,$forum_user,$forum_pass,$forum_db;
					global $forum_prefix,$forum_path;
				
				
					
					
					//fix bug .. 
					if(empty($forum_srv) || empty($forum_user) || empty($forum_db)) return;
					
					
					if(file_exists($forum_path . '/adm/index.php'))
					{
						include($forum_path . "/includes/functions.php");
						$pass = phpbb_hash($pass);
						$where_sql	=		"username_clean='" . strtolower($name) ."' AND user_password='$pass'";
						$row_leve		=	'user_type';
						$admin_level	=	3;
					}
					else
					{
						$pass = md5($pass);
						$where_sql	=		"username='$name' AND user_password='$pass'";
						$row_leve		=	'user_level';
						$admin_level	=	1;
					}
					
					$SQLBB	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db);
					unset($forum_pass); // We do not need this any longe
					
					$query = array(
								'SELECT'	=> '*',
								'FROM'		=> "`{$forum_prefix}users`",
								'WHERE'		=>$where_sql	
								);
								
					($hook = kleeja_run_hook('qr_select_usrdata_php_usr_class')) ? eval($hook) : null; //run hook		
					$result = $SQLBB->build($query);
					
				
					if ($SQLBB->num_rows($result) != 0) 
					{
					
						while($row=$SQLBB->fetch_array($result))
						{
							$_SESSION['USER_ID']	=	$row['user_id'];
							$_SESSION['USER_NAME']	=	$row['username'];
							$_SESSION['USER_MAIL']	=	$row['user_email'];
							$_SESSION['USER_ADMIN']	=	($row[$row_leve] == $admin_level) ? 1 : 0;
							$_SESSION['USER_SESS']	=	session_id();
							($hook = kleeja_run_hook('qr_while_usrdata_php_usr_class')) ? eval($hook) : null; //run hook
							
							/* I cant thinking now .. help me :)
							//update session_id
							$user_id 		= (int)	$row['user_id'];
							$session_id 	= (string)	session_id();
							$last_visit 	= (int) 0;
							$current_time 	= (int) time();
							$login		 	= (int) 1;
							$admin 		 	= (int) $_SESSION['USER_ADMIN'];
							$page_id		= (int) 1;
							if (getenv('HTTP_X_FORWARDED_FOR')){$ip= getenv('HTTP_X_FORWARDED_FOR');}else {$ip= getenv('REMOTE_ADDR');}
							$user_ip= (string)  $this->encode_ip($ip); // <<< i delete this function  

							
							$sql = "UPDATE `{$forum_prefix}sessions`
								SET session_user_id = $user_id, session_start = $current_time, session_time = $current_time, session_page = $page_id, session_logged_in = $login, session_admin = $admin
								WHERE session_id = '" . $session_id . "' 
									AND session_ip = '$user_ip'";
							if ( !$SQLBB->query($sql) || !$SQLBB->num_rows($sql) )
							{
								$session_id = session_id();

								$sql = "INSERT INTO `{$forum_prefix}sessions`
									(session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in, session_admin)
									VALUES ('$session_id', $user_id, $current_time, $current_time, '$user_ip', $page_id, $login, $admin)";
								if ( !$SQLBB->query($sql) )
								{
									die('Error creating new session phpbb');
								}
							}
							*/

						}
						$SQLBB->freeresult($result);   
						unset($pass);
						$SQLBB->close();
						
						
						return true;
					}
					else
					{
						return false;
					}
				}
				function vb ($name,$pass)
				{
				// i hate vb .. i cant feel my self use it ... 
				global $forum_srv,$forum_user,$forum_pass,$forum_db;
				global $forum_prefix;

					//header("Content-Type: text/html; charset=Windows-1256");
					$pass = md5($pass);
					
					//fix bug .. 
					if(empty($forum_srv) || empty($forum_user) || empty($forum_db)) return;
					
					//$SQLVB	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db, true);
					$SQLVB	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db);
					unset($forum_pass); // We do not need this any longe
					
					$query_salt = array(
								'SELECT'	=> 'salt',
								'FROM'		=> "`{$forum_prefix}user`",
								'WHERE'		=> "username='$name'"
								);
								
					($hook = kleeja_run_hook('qr_select_usrdata_vb_usr_class')) ? eval($hook) : null; //run hook				
					$result_salt = $SQLVB->build($query_salt);
				
					if ($SQLVB->num_rows($result_salt) != 0  ) 
					{
						while($row1=$SQLVB->fetch_array($sql))
						{
							
							$pass = md5($pass . $row1[salt]);  // without normal md5
							
							$query = array(
										'SELECT'	=> '*',
										'FROM'		=> "`{$forum_prefix}user`",
										'WHERE'		=> "username='$name' AND password='$pass'"
										);
											
							$result = $SQLVB->build($query);
							
						
							if ($SQLVB->num_rows($result) != 0  ) 
							{
								while($row=$SQLVB->fetch_array($sql2))
								{
									$_SESSION['USER_ID']	=	$row['userid'];
									$_SESSION['USER_NAME']	=	$row['username'];
									$_SESSION['USER_MAIL']	=	$row['email'];
									$_SESSION['USER_ADMIN']	=	($row['usergroupid'] == 6) ? 1 : 0;
									$_SESSION['USER_SESS']	=	session_id();
									($hook = kleeja_run_hook('qr_while_usrdata_vb_usr_class')) ? eval($hook) : null; //run hook
								}
								$SQLVB->freeresult($result);   
							
							}#nums_sql2
							else
							{
								return false;
							}
						}#whil1
					
						$SQLVB->freeresult($result_salt); 
						
						unset($pass);
						$SQLVB->close();
						
						
						return true;
					}
					else
					{
					return false;
					}
				}
				//mysmartbb
				function mysmartbb ($name,$pass)
				{
				global $forum_srv,$forum_user,$forum_pass,$forum_db;
				global $forum_prefix;
				
				
					$pass = md5($pass);
					
					//fix bug .. 
					if(empty($forum_srv) || empty($forum_user) || empty($forum_db)) return;
					
					
					$SQLMS	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db);

					unset($forum_pass); // We do not need this any longe
					
					$query = array(
								'SELECT'	=> '*',
								'FROM'		=> "`{$forum_prefix}member`",
								'WHERE'		=> "username='$name' AND password='$pass'"
								);
								
					($hook = kleeja_run_hook('qr_select_usrdata_mysbb_usr_class')) ? eval($hook) : null; //run hook	
					$result = $SQLMS->build($query);
					
				
					if ($SQLMS->num_rows($result) != 0) 
					{
					
						while($row=$SQLMS->fetch_array($result))
						{
							$_SESSION['USER_ID']	=	$row['id'];
							$_SESSION['USER_NAME']	=	$row['username'];
							$_SESSION['USER_MAIL']	=	$row['email'];
							$_SESSION['USER_ADMIN']	=	($row['usergroup'] == 1) ? 1 : 0;
							$_SESSION['USER_SESS']	=	session_id();
							($hook = kleeja_run_hook('qr_while_usrdata_mysbb_usr_class')) ? eval($hook) : null; //run hook
							
						}
						$SQLMS->freeresult($result);   
						unset($pass);
						$SQLMS->close();
						
						
						return true;
					}
					else
					{
						return false;
					}
				}
				
				/*
					get user data
					new function 1rc5
				*/
				function get_data($type="*", $user_id=false)
				{
					global $dbprefix, $SQL;
					
					if(!$user_id) $user_id	=	$this->id();
					
					//te get files and update them !!
					$query_name = array(
									'SELECT'	=> $type,
									'FROM'		=> "{$dbprefix}users",
									'WHERE'		=> "id='". $user_id ."'"
								);
								
					($hook = kleeja_run_hook('qr_select_userdata_in_usrclass')) ? eval($hook) : null; //run hook
					$data_user = $SQL->fetch_array($SQL->build($query_name));
						
					return $data_user;
				}
				
				/*
				user ids
				*/
				function id ()
				{
					($hook = kleeja_run_hook('id_func_usr_class')) ? eval($hook) : null; //run hook
					
					if ($_SESSION['USER_SESS'] == session_id())
					{
						if ($_SESSION['USER_ID'])
						{
							return $_SESSION['USER_ID'];
						}
						else
						{
							return false;
						}
					}
					else
					{
						return false;
					}
				}
				
				/*
				user name
				*/
				function name ()
				{
					($hook = kleeja_run_hook('name_func_usr_class')) ? eval($hook) : null; //run hook
					
					if ($_SESSION['USER_SESS'] == session_id())
					{
						if ($_SESSION['USER_NAME'])
						{
							return $_SESSION['USER_NAME'];
						}
						else
						{
							return false;
						}
					}
					else
					{
					return false;
					}
				}
				
				/*
				user mail
				*/
				function mail ()
				{
					($hook = kleeja_run_hook('mail_func_usr_class')) ? eval($hook) : null; //run hook
					
					if ($_SESSION['USER_SESS'] == session_id() )
					{
						if ($_SESSION['USER_MAIL'])
						{
							return $_SESSION['USER_MAIL'];
						}
						else
						{
							return false;
						}
					}
					else
					{
						return false;
					}
				}
				
				/*
				is user admin ?
				*/
				function admin ()
				{
					($hook = kleeja_run_hook('admin_func_usr_class')) ? eval($hook) : null; //run hook
					
					if ($_SESSION['USER_SESS'] == session_id())
						{
							if ($_SESSION['USER_ADMIN'])
							{
								return $_SESSION['USER_ADMIN'];
							}
							else
							{
								return false;
							}
						}
						else
						{
						return false;
						}
				}

				/*
				logout func
				*/
				function logout()
				{
					($hook = kleeja_run_hook('logout_func_usr_class')) ? eval($hook) : null; //run hook
					
					unset($_SESSION['USER_ID']);
					unset($_SESSION['USER_NAME']);
					unset($_SESSION['USER_MAIL']);
					unset($_SESSION['USER_ADMIN']);
					unset($_SESSION['USER_SESS']);
					return true;
				}
				
				/*
				logut just from acp
				*/
				function logout_cp()
				{
					($hook = kleeja_run_hook('logout_cp_func_usr_class')) ? eval($hook) : null; //run hook
					
					unset($_SESSION['USER_ADMIN']);
					return true;
				}
}#end class



?>

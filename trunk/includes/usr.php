<?php
##################################################
#						Kleeja 
#
# Filename : usr.php
# purpose :  get user data ..even from board database, its complicated ...: support many types of forums ..
# copyright 2007-2008 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : saanina
##################################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}
  
class usrcp
{


				// this function like a traffic sign :)
				function data ($name, $pass)
				{
					global $config, $path;
						
						//we need this in future 
						if(defined('IGNORE_USER_SYSTEM'))
						{
							$config['user_system'] = '1';
						}
						
						
						//fix it 
						if($config['user_system'] == '' || empty($config['user_system']))
						{
							$config['user_system'] = '1';
						}
						
						
						($hook = kleeja_run_hook('data_func_usr_class')) ? eval($hook) : null; //run hook
						
						
						if($config['user_system'] != '1')
						{
							if(file_exists($path . 'auth_integration/' . trim($config['user_system']) . '.php'))
							{	
								include_once ($path . 'auth_integration/' . trim($config['user_system']) . '.php');
								return kleeja_auth_login($name, $pass);
							}
						}
						
						
						//normal 
						return $this->normal($name, $pass);
					
				}
				
					
				//now ..  .. our table
				function normal ($name,$pass)
				{
					global $SQL,$dbprefix;
					
					$pass = md5($pass);
					
					$query = array(
								'SELECT'	=> '*',
								'FROM'		=> "`{$dbprefix}users`",
								'WHERE'		=> "name='". $SQL->escape($name) . "' AND password='$pass'"
								);
								
					($hook = kleeja_run_hook('qr_select_usrdata_n_usr_class')) ? eval($hook) : null; //run hook			
					$result = $SQL->build($query);
					

					
					if ($SQL->num_rows($result) != 0 ) 
					{
						while($row=$SQL->fetch_array($result))
						{
							$_SESSION['USER_ID']	= $row['id'];
							$_SESSION['USER_NAME']	= $row['name'];
							$_SESSION['USER_MAIL']	= $row['mail'];
							$_SESSION['USER_ADMIN']	= $row['admin'];
							$_SESSION['USER_SESS']	= session_id();
							$_SESSION['LAST_VISIT']	= $row['last_visit'];
							($hook = kleeja_run_hook('qr_while_usrdata_n_usr_class')) ? eval($hook) : null; //run hook	
							
							//update session_id
							$id 		= (int) $row['id'];
							$session_id = (string) session_id();
							
							$update_query = array(
												'UPDATE'	=> "`{$dbprefix}users`",
												'SET'		=> "session_id='" . $SQL->escape($session_id) . "' ,last_visit='" . time() . "'",
												'WHERE'		=>	"id='" . $id ."'"
										);

							if (!$SQL->build($update_query))
							{
								die($lang['CANT_UPDATE_SQL']);
							}
						
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
			
			
				
				/*
					get user data
					new function:1rc5+
				*/
				function get_data($type="*", $user_id=false)
				{
					global $dbprefix, $SQL;
					
					if(!$user_id) $user_id	= $this->id();
					
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
					unset($_SESSION['LAST_VISIT']);
					return true;
				}
				
				/*
				logut just from acp
				*/
				function logout_cp()
				{
					($hook = kleeja_run_hook('logout_cp_func_usr_class')) ? eval($hook) : null; //run hook
					
					unset($_SESSION['USER_ADMIN']);
					unset($_SESSION['LAST_VISIT']);
					return true;
				}
}#end class

?>

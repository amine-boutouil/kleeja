<?php
##################################################
#						Kleeja 
#
# Filename : usr.php
# purpose :  get user data ..even from board database, its complicated ...: support many types of forums ..
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author$ , $Rev$,  $Date::                           $
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
						
						
						if($config['user_system'] != '1' && !defined('DISABLE_INTR'))
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
				
				//get username by id
				function usernamebyid ($user_id) 
				{
					global $config, $path;
					if($config['user_system'] != '1' && !defined('DISABLE_INTR'))
					{
						if(file_exists($path . 'auth_integration/' . trim($config['user_system']) . '.php'))
						{	
							include_once ($path . 'auth_integration/' . trim($config['user_system']) . '.php');
							$username = kleeja_auth_username($user_id);
						}
					}
					return $username;	
				}
				
					
				//now ..  .. our table
				function normal ($name,$pass)
				{
					global $SQL,$dbprefix;
										
					$query = array(
								'SELECT'	=> '*',
								'FROM'		=> "`{$dbprefix}users`",
								'WHERE'		=> "clean_name='". $SQL->escape($this->cleanusername($name)) . "'"
								);
								
					($hook = kleeja_run_hook('qr_select_usrdata_n_usr_class')) ? eval($hook) : null; //run hook			
					$result = $SQL->build($query);
					

					
					if ($SQL->num_rows($result) != 0 ) 
					{
						while($row=$SQL->fetch_array($result))
						{
							//CHECK IF IT'S MD5 PASSWORD
							if(strlen($row['password']) == '32' && empty($row['password_salt']))   
							{
								$passmd5 = md5($pass);
								
								//update old md5 hash to phpass hash
								if($row['password'] == $passmd5)
								{
									//new salt
									$new_salt = substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
									//new password hash
									$new_password = $this->kleeja_hash_password(trim($pass) . $new_salt);
									
								
									($hook = kleeja_run_hook('qr_update_usrdata_md5_n_usr_class')) ? eval($hook) : null; //run hook	
									
									//update now !!
									$update_query = array(
												'UPDATE'	=> "`{$dbprefix}users`",
												'SET'		=> "password='" . $new_password . "' ,password_salt='" . $new_salt . "'",
												'WHERE'		=>	"id='" . intval($row['id']) ."'"
										);
										
									$SQL->build($update_query);
								}
								else //if the password is wrong
								{
									return false;
								}
							}
							else if($this->kleeja_hash_password($pass . $row['password_salt'], $row['password']) != true)
							{
								return false;
							}
							
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
										
							$SQL->build($update_query);
						
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
					
					if (isset($_SESSION['USER_SESS']) && $_SESSION['USER_SESS'] == session_id())
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
					
					if (!empty($_SESSION['USER_SESS']) && $_SESSION['USER_SESS'] == session_id())
					{
						if (!empty($_SESSION['USER_NAME']))
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
					
					if (!empty($_SESSION['USER_SESS']) && $_SESSION['USER_SESS'] == session_id() )
					{
						if (!empty($_SESSION['USER_MAIL']))
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
					
					if (!empty($_SESSION['USER_SESS']) && $_SESSION['USER_SESS'] == session_id())
						{
							if (!empty($_SESSION['USER_ADMIN']))
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
					unset($_SESSION['ADMINLOGIN']);
					return true;
				}
				
				/*
				logut just from acp
				*/
				function logout_cp()
				{
					($hook = kleeja_run_hook('logout_cp_func_usr_class')) ? eval($hook) : null; //run hook
					
					unset($_SESSION['ADMINLOGIN']);
					unset($_SESSION['LAST_VISIT']);
					return true;
				}
				
				//clean nicknames
				function cleanusername ($uname) 
				{
					$clean_chars = array(
					'أ' => 'ا',
					'إ' => 'ا',
					'ؤ' => 'و',
					'ـ' => '',
					'ً' => '',
					'ٌ' => '',
					'ُ' => '',
					'َ' => '',
					'ِ' => '',
					'ْ' => '',
					'آ' => 'ا',
					'á'=> 'a',
					'à'=> 'a',
					'â'=> 'a',
					'ã'=> 'a',
					'ª'=> 'a',
					'Á'=> 'a',
					'À'=> 'a',
					'Â'=> 'a',
					'Ã'=> 'a',
					'é'=> 'e',
					'è'=> 'e',
					'ê'=> 'e',
					'É'=> 'e',
					'È'=> 'e',
					'Ê'=> 'e',
					'í'=> 'i',
					'ì'=> 'i',
					'î'=> 'i',
					'Í'=> 'i', 
					'Ì'=> 'i',
					'Î'=> 'i',
					'ò'=> 'o',
					'ó'=> 'o',
					'ô'=> 'o',
					'õ'=> 'o',
					'º'=> 'o',
					'Ó'=> 'o',
					'Ò'=> 'o',
					'Ô'=> 'o',
					'Õ'=> 'o',
					'ú'=> 'u',
					'ù'=> 'u',
					'û'=> 'u',
					'Ú'=> 'u',
					'Ù'=> 'u',
					'Û'=> 'u',
					'ç'=> 'c',
					'Ç'=> 'c',
					'Ñ'=> 'n',
					'ñ'=> 'n',
					'ÿ' => 'y',
					'Ë' => 'e',
					'Ø' => 'o',
					'Å' => 'a',
					'å' => 'a',
					'ï' => 'i',
					'Ï' => 'i',
					'ø' => 'o',
					'ë' => 'e',
					);
    				$uname = str_replace(array_keys($clean_chars), array_values($clean_chars), $uname);
    				$uname = strtolower($uname);
    				return $uname;
				}
				
				function kleeja_hash_password($password, $check_pass = false)
				{
					include_once('phpass.php');
	
					$return = false;
					$hasher = new PasswordHash(8, true);
					$return = $hasher->HashPassword($password);
	
					//check
					if($check_pass != false)
					{
						$return = $hasher->CheckPassword($password, $check_pass);
					}
			
					return $return;
			}
}#end class

?>

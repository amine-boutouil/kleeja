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
				function data ($name, $pass, $hashed = false, $expire)
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
						
						//expire
						$expire = time() + intval($expire);
							
						($hook = kleeja_run_hook('data_func_usr_class')) ? eval($hook) : null; //run hook
						
						
						if($config['user_system'] != '1' && !defined('DISABLE_INTR'))
						{
							if(file_exists($path . 'auth_integration/' . trim($config['user_system']) . '.php'))
							{	
								include_once ($path . 'auth_integration/' . trim($config['user_system']) . '.php');
								return kleeja_auth_login($name, $pass, $hashed, $expire);
							}
						}
						
						
						//normal 
						return $this->normal($name, $pass, $hashed, $expire);
					
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
				function normal ($name, $pass, $hashed = false, $expire)
				{
					global $SQL, $dbprefix, $config;
					
					$query = array(
								'SELECT'	=> '*',
								'FROM'		=> "`{$dbprefix}users`",
								);
								
					if($hashed)
					{
						$query['WHERE'] = "id='" . $SQL->escape(intval($name)) . "' and password='" . $SQL->escape($pass) . "'";
					}
					else
					{
						$query['WHERE'] = "clean_name='". $SQL->escape($this->cleanusername($name)) . "'";
					}
					
					($hook = kleeja_run_hook('qr_select_usrdata_n_usr_class')) ? eval($hook) : null; //run hook			
					$result = $SQL->build($query);
					
					if ($SQL->num_rows($result) != 0 ) 
					{
						while($row=$SQL->fetch_array($result))
						{
							if(empty($row['password'])) //more security
							{
								return false;
							}
						
							$phppass = ($hashed) ?  $pass : $pass . $row['password_salt'];
							
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
							else if(($phppass != $row['password'] && $hashed) || ($this->kleeja_hash_password($phppass, $row['password']) != true && $hashed == false))
							{
								return false;
							}
							
							define('USER_ID', $row['id']);
							define('USER_NAME', $row['name']);
							define('USER_MAIL', $row['mail']);
							define('USER_ADMIN', $row['admin']);
							define('LAST_VISIT', $row['last_visit']);
							
							if(!$hashed)
							{
								$hash_key_expire = sha1(md5($config['h_key']) .  $expire);
								$this->kleeja_set_cookie('ulogu', base64_encode(base64_encode(base64_encode($row['id'] . '|' . $row['password'] . '|' . $expire . '|' . $hash_key_expire))), $expire);
							}
					
							($hook = kleeja_run_hook('qr_while_usrdata_n_usr_class')) ? eval($hook) : null; //run hook
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
					
					
						if (defined('USER_ID'))
						{
							return USER_ID;
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
					
						if (defined('USER_NAME'))
						{
							return USER_NAME;
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
					
						if (defined('USER_MAIL'))
						{
							return USER_MAIL;
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
					
						if (defined('USER_ADMIN'))
						{
							return USER_ADMIN;
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
					
					//adm
					if(defined('USER_ADMIN') && USER_ADMIN == 1)
					{ 
						redirect('./admin.php?cp=lgoutcp');
					}
										
					//is ther any cookies	
					$this->kleeja_set_cookie('ulogu', '', time() - 31536000);//31536000 = year
					
					return true;
				}
				
				/*
				logut just from acp
				*/
				function logout_cp()
				{
					($hook = kleeja_run_hook('logout_cp_func_usr_class')) ? eval($hook) : null; //run hook
					
					if(!empty($_SESSION['ADMINLOGIN']))
					{
						unset($_SESSION['ADMINLOGIN']);
						unset($_SESSION['USER_SESS']);
						//unset($_SESSION['LAST_VISIT']);
					}
					
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
				
				//depand on phpass class
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
				
				//kleeja cookie
				function kleeja_set_cookie($name, $value, $expire)
				{
					global $config;
	
	
					if ($config['cookie_domain'] == '')
					{
						$config['cookie_domain'] = (!empty($_SERVER['HTTP_HOST'])) ? strtolower($_SERVER['HTTP_HOST']) : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME'));
					}
					
					if(strpos($config['cookie_domain'], 'localhost') !== false)
					{
						$config['cookie_domain'] = '';
					}
		
					if($config['cookie_domain'] != '')
					{
						// Fix the domain to accept domains with and without 'www.'.
						if (strtolower(substr($config['cookie_domain'], 0, 4) ) == 'www.')
						{
							$config['cookie_domain'] = substr($config['cookie_domain'], 4);
						}
						// Add the dot prefix to ensure compatibility with subdomains
						if (substr($config['cookie_domain'], 0, 1) != '.' )
						{
							$config['cookie_domain'] = '.' . $config['cookie_domain'];
						}
						// Remove port information.
						$port = strpos($config['cookie_domain'], ':');
						
						if ($port !== false)
						{
							$config['cookie_domain'] = substr($config['cookie_domain'], 0, $port);
						}
					}
		
					// Enable sending of a P3P header
					header('P3P: CP="CUR ADM"');
	
					if (version_compare(PHP_VERSION, '5.2.0', '>='))
					{
						setcookie($config['cookie_name'] . '_' . $name, $value, $expire, $config['cookie_path'], $config['cookie_domain'], $config['cookie_secure'], true);
					}
					else
					{
						setcookie($config['cookie_name'] . '_' . $name, $value, $expire, $config['cookie_path'] . '; HttpOnly', $config['cookie_domain'], $config['cookie_secure']);
					}
				}
				
				//
				//get cookie
				//
				function kleeja_get_cookie($name)
				{
					global $config;
	
					if(isset($_COOKIE[$config['cookie_name'] . '_' . $name]))
					{
						return $_COOKIE[$config['cookie_name'] . '_' . $name];
					}
					
					return false;
				}
				
				//check if user is admin or not 
				//return : mean return true or false, but if return is false will show msg
				function kleeja_check_user()
				{
					global $config, $SQL, $dbprefix;
					
					//create h_key unique key [saanina idia ;_)]
					if(empty($config['h_key']))
					{
						$config['h_key'] = sha1(microtime() . rand(1000,9999));
						$insert_query	= array('INSERT'	=> 'name ,value',
												'INTO'		=> "{$dbprefix}config",
												'VALUES'	=> "'h_key', '" . $config['h_key'] . "'"
											);
						
						$SQL->build($insert_query);
							
						delete_cache('data_config');
					}
						
					//if login up
					if($this->kleeja_get_cookie('ulogu'))
					{
						$user_data = false;

						list($user_id, $hashed_password, $expire_at, $hashed_expire) =  @explode('|', base64_decode(base64_decode(base64_decode($this->kleeja_get_cookie('ulogu')))));

						//if not expire 
						if(($hashed_expire == sha1(md5($config['h_key']) . $expire_at)) && ($expire_at > time()))
						{
							$user_data = $this->data($user_id, $hashed_password, true, $expire_at);
						}
						
						if($user_data == false)
						{
							$this->logout();
						}
						else
						{
							return $user_data;
						}
					}
					else
					{
						return false;//nothing
					}
}
}#end class

?>

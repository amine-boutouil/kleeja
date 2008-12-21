<?php
##################################################
#						Kleeja 
#
# Filename : usr.php
# purpose :  get user data ..even from board database, its complicated ...: supoort many types of forums ..
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
					global $config;
						
						($hook = kleeja_run_hook('data_func_usr_class')) ? eval($hook) : null; //run hook
						
						//fix it 
						if($config['user_system'] == '') $config['user_system'] = 1;
						
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
								'WHERE'		=> "name='". $SQL->escape($name) . "' AND password='$pass'"
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
					global $forum_prefix,$forum_path,$SQLBB ,$phpEx,$phpbb_root_path;
				
					//fix bug .. 
					if(empty($forum_srv) || empty($forum_user) || empty($forum_db)) return;
					
					//check for last / 
					if($forum_path[strlen($forum_path)] == '/')
						$forum_path = substr($forum_path, 0, strlen($forum_path));
					
					if($forum_path[0] == '/')
							$forum_path = '..' . $forum_path;
					else
							$forum_path = '../'.$forum_path;
					
					//conecting ...		
					$SQLBB	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db);
					$charset_db = mysql_client_encoding($SQLBB);
					
					unset($forum_pass); // We do not need this any longe
					
					//phpbb3
					if(file_exists($forum_path . '/includes/functions_transfer.php'))
					{
						
						//get utf tools
						define('IN_PHPBB',true);
						$phpbb_root_path = $forum_path .'/';
						$phpEx = 'php';
						include_once($forum_path . '/includes/utf/utf_tools.'.$phpEx);
						
						$row_leve		=	'user_type';
						$admin_level	=	3;
						
						$query2 = array(
											'SELECT'	=> '*',
											'FROM'		=> "`{$forum_prefix}users`",
											'WHERE'		=>"username_clean='" . utf8_clean_string($name) . "'"
											);
											
						$result2 = $SQLBB->build($query2);					
						while($row=$SQLBB->fetch_array($result2))
						{
							if(phpbb_check_hash($pass, $row['user_password']))
							{
								$query = $query2;
							}
							else
							{
								$query = "";
							}
						}
					}
					else//phpbb2
					{

						$row_leve		= 'user_level';
						$admin_level	= 1;
						
						//change it with iconv, i dont care if you enabled it or not 
						if(strpos(strtolower($charset_db), 'utf8') === false)
						{
							//no iconv !
							if(!function_exists('iconv'))
							{
								big_error('No support for ICONV', 'You must enable the ICONV library to integrate kleeja with your forum. You can solve your problem by changing your forum db charset to UTF8.');
							}
							else
							{
								$name_b = iconv(strtoupper($charset_db), "UTF-8", $name);
								$pass_b = iconv(strtoupper($charset_db), "UTF-8", $pass);
							}
						}
						else
						{
							$name_b = $name;
							$pass_b = $pass;
						}
						
						$query = array(
											'SELECT'	=> '*',
											'FROM'		=> "`{$forum_prefix}users`",
											'WHERE'		=>"username='". $SQLBB->escape($name_b) ."' AND user_password='" . md5($pass_b) . "'"
											);
								
					}
					
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

						}
						$SQLBB->freeresult($result);   
						unset($pass);
						$SQLBB->close();
						
						
						return true;
					}
					else
					{
						$SQLBB->close();
						return false;
					}
				}
				
				
				function vb ($name, $pass)
				{
					// ok, i dont hate vb .. but i cant feel my self use it ... 
					global $forum_srv,$forum_user,$forum_pass,$forum_db;
					global $forum_prefi
					
					//fix bug .. 
					if(empty($forum_srv) || empty($forum_user) || empty($forum_db)) return;
					
					$SQLVB	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db);
					$charset_db = mysql_client_encoding($SQLVB);
					unset($forum_pass); // We do not need this any longe
					
						//change it with iconv, i dont care if you enabled it or not 
						if(strpos(strtolower($charset_db), 'utf8') === false)
						{
							//no iconv !
							if(!function_exists('iconv'))
							{
								big_error('No support for ICONV', 'You must enable the ICONV library to integrate kleeja with your forum. You can solve your problem by changing your forum db charset to UTF8.');
							}
							else
							{
								$name_b = iconv(strtoupper($charset_db), "UTF-8", $name);
								$pass_b = iconv(strtoupper($charset_db), "UTF-8", $pass);
							}
						}
						else
						{
							$name_b = $name;
							$pass_b = $pass;
						}
						
					$query_salt = array(
								'SELECT'	=> 'salt',
								'FROM'		=> "`{$forum_prefix}user`",
								'WHERE'		=> "username='". $SQLVB->escape($name_b) ."'"
								);
								
					($hook = kleeja_run_hook('qr_select_usrdata_vb_usr_class')) ? eval($hook) : null; //run hook				
					$result_salt = $SQLVB->build($query_salt);
				
					if ($SQLVB->num_rows($result_salt) != 0) 
					{
						while($row1=$SQLVB->fetch_array($result_salt))
						{
							
							$pass = md5($pass . $row1['salt']);  // without normal md5
							
							$query = array(
										'SELECT'	=> '*',
										'FROM'		=> "`{$forum_prefix}user`",
										'WHERE'		=> "username='". $SQLVB->escape($name)."' AND password='" . md5($pass_b) ."'"
										);
											
							$result = $SQLVB->build($query);
							
						
							if ($SQLVB->num_rows($result) != 0) 
							{
								while($row=$SQLVB->fetch_array($result))
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
						$SQLVB->close();
						return false;
					}
				}
				
				
				//mysmartbb
				function mysmartbb ($name, $pass)
				{
					global $forum_srv,$forum_user,$forum_pass,$forum_db;
					global $forum_prefix;
				
					
					//fix bug .. 
					if(empty($forum_srv) || empty($forum_user) || empty($forum_db)) return;
					
					
					$SQLMS	= new SSQL($forum_srv, $forum_user, $forum_pass, $forum_db);
					$charset_db = mysql_client_encoding($SQLMS);
					unset($forum_pass); // We do not need this any longe
					
						//change it with iconv, i dont care if you enabled it or not 
						if(strpos(strtolower($charset_db), 'utf8') === false)
						{
							//no iconv !
							if(!function_exists('iconv'))
							{
								big_error('No support for ICONV', 'You must enable the ICONV library to integrate kleeja with your forum. You can solve your problem by changing your forum db charset to UTF8.');
							}
							else
							{
								$name_b = iconv(strtoupper($charset_db), "UTF-8", $name);
								$pass_b = iconv(strtoupper($charset_db), "UTF-8", $pass);
							}
						}
						else
						{
							$name_b = $name;
							$pass_b = $pass;
						}
					
					$query = array(
								'SELECT'	=> '*',
								'FROM'		=> "`{$forum_prefix}member`",
								'WHERE'		=> "username='" . $SQLMS->escape($name_b) . "' AND password='" . md5($pass_b) . "'"
								);
								
					($hook = kleeja_run_hook('qr_select_usrdata_mysbb_usr_class')) ? eval($hook) : null; //run hook	
					$result = $SQLMS->build($query);
					
				
					if ($SQLMS->num_rows($result) != 0) 
					{
					
						while($row=$SQLMS->fetch_array($result))
						{
							$_SESSION['USER_ID']	= $row['id'];
							$_SESSION['USER_NAME']	= $row['username'];
							$_SESSION['USER_MAIL']	= $row['email'];
							$_SESSION['USER_ADMIN']	= ($row['usergroup'] == 1) ? 1 : 0;
							$_SESSION['USER_SESS']	= session_id();
							($hook = kleeja_run_hook('qr_while_usrdata_mysbb_usr_class')) ? eval($hook) : null; //run hook
							
						}
						$SQLMS->freeresult($result);   
						unset($pass);
						$SQLMS->close();
						
						
						return true;
					}
					else
					{
						$SQLMS->close();
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

/*
important function to integrration btw kleeja and phpbb3 !
*/

/**
*
* @version Version 0.1 / slightly modified for phpBB 3.0.x (using $H$ as hash type identifier)
*
* Portable PHP password hashing framework.
*
* Written by Solar Designer <solar at openwall.com> in 2004-2006 and placed in
* the public domain.
*
* There's absolutely no warranty.
*
* The homepage URL for this framework is:
*
*	http://www.openwall.com/phpass/
*
* Please be sure to update the Version line if you edit this file in any way.
* It is suggested that you leave the main version number intact, but indicate
* your project name (after the slash) and add your own revision information.
*
* Please do not change the "private" password hashing method implemented in
* here, thereby making your hashes incompatible.  However, if you must, please
* change the hash type identifier (the "$P$") to something different.
*
* Obviously, since this code is in the public domain, the above are not
* requirements (there can be none), but merely suggestions.
*
*
* Hash the password
*/
function phpbb_hash($password)
{
	$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

	$random_state = unique_id();
	$random = '';
	$count = 6;

	if (($fh = @fopen('/dev/urandom', 'rb')))
	{
		$random = fread($fh, $count);
		fclose($fh);
	}

	if (strlen($random) < $count)
	{
		$random = '';

		for ($i = 0; $i < $count; $i += 16)
		{
			$random_state = md5(unique_id() . $random_state);
			$random .= pack('H*', md5($random_state));
		}
		$random = substr($random, 0, $count);
	}

	$hash = _hash_crypt_private($password, _hash_gensalt_private($random, $itoa64), $itoa64);

	if (strlen($hash) == 34)
	{
		return $hash;
	}

	return md5($password);
}

/**
* Check for correct password
*/
function phpbb_check_hash($password, $hash)
{
	$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	if (strlen($hash) == 34)
	{
		return (_hash_crypt_private($password, $hash, $itoa64) === $hash) ? true : false;
	}

	return (md5($password) === $hash) ? true : false;
}

/**
* Generate salt for hash generation
*/
function _hash_gensalt_private($input, &$itoa64, $iteration_count_log2 = 6)
{
	if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
	{
		$iteration_count_log2 = 8;
	}

	$output = '$H$';
	$output .= $itoa64[min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
	$output .= _hash_encode64($input, 6, $itoa64);

	return $output;
}

/**
* Encode hash
*/
function _hash_encode64($input, $count, &$itoa64)
{
	$output = '';
	$i = 0;

	do
	{
		$value = ord($input[$i++]);
		$output .= $itoa64[$value & 0x3f];

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 8;
		}

		$output .= $itoa64[($value >> 6) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 16;
		}

		$output .= $itoa64[($value >> 12) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		$output .= $itoa64[($value >> 18) & 0x3f];
	}
	while ($i < $count);

	return $output;
}

/**
* The crypt function/replacement
*/
function _hash_crypt_private($password, $setting, &$itoa64)
{
	$output = '*';

	// Check for correct hash
	if (substr($setting, 0, 3) != '$H$')
	{
		return $output;
	}

	$count_log2 = strpos($itoa64, $setting[3]);

	if ($count_log2 < 7 || $count_log2 > 30)
	{
		return $output;
	}

	$count = 1 << $count_log2;
	$salt = substr($setting, 4, 8);

	if (strlen($salt) != 8)
	{
		return $output;
	}

	/**
	* We're kind of forced to use MD5 here since it's the only
	* cryptographic primitive available in all versions of PHP
	* currently in use.  To implement our own low-level crypto
	* in PHP would result in much worse performance and
	* consequently in lower iteration counts and hashes that are
	* quicker to crack (by non-PHP code).
	*/
	if (PHP_VERSION >= 5)
	{
		$hash = md5($salt . $password, true);
		do
		{
			$hash = md5($hash . $password, true);
		}
		while (--$count);
	}
	else
	{
		$hash = pack('H*', md5($salt . $password));
		do
		{
			$hash = pack('H*', md5($hash . $password));
		}
		while (--$count);
	}

	$output = substr($setting, 0, 12);
	$output .= _hash_encode64($hash, 16, $itoa64);

	return $output;
}

/**
* Return unique id
* @param string $extra additional entropy
*/
function unique_id($extra = 'c')
{
	global $forum_prefix;
	global $forum_srv,$forum_user,$forum_pass,$forum_db;
	
	$SQLBB	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db);
	unset($forum_pass); // We do not need this any longe
	$query = array(
							'SELECT'	=> 'config_value',
							'FROM'		=> "`{$forum_prefix}config`",
							'WHERE'		=>"config_name='rand_seed'"	
					);
								
	($hook = kleeja_run_hook('qr_select_rand_seed_usr_class')) ? eval($hook) : null; //run hook		
	$result	= $SQLBB->build($query);
	$config	=	$SQLBB->fetch_array($result);
	
	$val = $config['config_value'] . microtime();
	$val = md5($val);

	unset($SQLBB);
	unset($config);

	return substr($val, 4, 16);
}

?>

<?php
/**
*
* @package auth
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}
 
//
//Path of config file in phpBB3
//
define('SCRIPT_CONFIG_PATH', '/config.php');


function kleeja_auth_login ($name, $pass, $hashed = false, $expire, $loginadm = false, $return_name = false)
{
	global $lang, $config, $usrcp, $userinfo;
	global $script_path, $script_encoding, $script_srv, $script_db, $script_user, $script_pass, $script_prefix;
				
	//check for last slash / 
	if(isset($script_path))
	{
		if(isset($script_path[strlen($script_path)]) && $script_path[strlen($script_path)] == '/')
		{
			$script_path = substr($script_path, 0, strlen($script_path));
		}

		//get some useful data from phbb config file
		if(file_exists(PATH . $script_path . SCRIPT_CONFIG_PATH))
		{
			include (PATH . $script_path . SCRIPT_CONFIG_PATH);

			$forum_srv	= $dbhost;
			$forum_db	= $dbname;
			$forum_user	= $dbuser;
			$forum_pass	= $dbpasswd;
			$forum_prefix = $table_prefix;
		}
		else
		{
			big_error('Forum path is not correct', sprintf($lang['SCRIPT_AUTH_PATH_WRONG'], 'phpBB3'));
		}
		
		
	}
	else 
	{
		$forum_srv	= $script_srv;
		$forum_db	= $script_db;
		$forum_user	= $script_user;
		$forum_pass	= $script_pass;
		$forum_prefix = $script_prefix;
	}

	//if no variables of db
	if(empty($forum_srv) || empty($forum_user) || empty($forum_db))
	{
		return;
	}

	//conecting ...		
	$SQLBB	= new SSQL($forum_srv, $forum_user, $forum_pass, $forum_db, true);

	$SQLBB->set_names('UTF-8');

	unset($forum_pass); // We do not need this any longer

	//get utf tools
	global $phpbb_root_path, $phpEx;
	$phpbb_root_path = PATH . $script_path . '/';
	$phpEx = 'php';
	define('IN_PHPBB', true);
	include_once (PATH . $script_path . '/includes/utf/utf_tools.' . $phpEx);

	$row_leve = 'user_type';
	$admin_level = 3;					
	$query2 = array(
					'SELECT'	=> '*',
					'FROM'		=> "`{$forum_prefix}users`",
				);
	
	$query2['WHERE'] = $hashed ?  "user_id=" . intval($name) . "  AND user_password='" . $SQLBB->real_escape($pass) . "' " : "username_clean='" . utf8_clean_string($name) . "'";
		
	if($return_name)
	{
		$query2['SELECT'] = "username";
		$query2['WHERE'] = "user_id=" . intval($name);
	}
		
	$query = '';

	if(!$hashed)
	{
		$result2 = $SQLBB->build($query2);					
		while($row=$SQLBB->fetch_array($result2))
		{
			if($return_name)
			{
				return $row['username'];
			}
			else
			{
				if(phpbb_check_hash($pass, $row['user_password']))
				{
					$query = $query2;
				}
			}
		}
			$SQLBB->freeresult($result2);   
	}
	else
	{
		$query = $query2;
	}

	if(empty($query))
	{
		$SQLBB->close();
		return false;
	}

	($hook = kleeja_run_hook('qr_select_usrdata_phpbb_usr_class')) ? eval($hook) : null; //run hook		
	$result = $SQLBB->build($query);


	if ($SQLBB->num_rows($result) != 0) 
	{
		while($row=$SQLBB->fetch_array($result))
		{
			if($SQLBB->num_rows($SQLBB->query("SELECT ban_userid FROM `{$forum_prefix}banlist` WHERE ban_userid=" . intval($row['user_id']))) == 0)
			{
				if(!$loginadm)
				{
					define('USER_ID', $row['user_id']);
					define('USER_NAME', $row['username']);
					define('USER_MAIL',$row['user_email']);
					define('USER_ADMIN',($row[$row_leve] == $admin_level) ? 1 : 0);
				}

				$userinfo = $row;
				$user_y = kleeja_base64_encode(serialize(array('id'=>$row['user_id'], 'name'=>$row['username'], 'mail'=>$row['user_email'], 'last_visit'=>time())));

				if(!$hashed && !$loginadm)
				{
					$usrcp->kleeja_set_cookie('ulogu', $usrcp->en_de_crypt($row['user_id'] . '|' . $row['user_password'] . '|' . $expire . '|' . sha1(md5($config['h_key'] . $row['user_password']) .  $expire) . '|' . (defined('USER_ADMIN') ? '1': '0') . '|' . $user_y), $expire);
				}

				($hook = kleeja_run_hook('qr_while_usrdata_phpbb_usr_class')) ? eval($hook) : null; //run hook
			}
			else
			{
				//he is banned from phpBB
				$SQLBB->freeresult($result);   
				unset($pass);
				$SQLBB->close();
				return false;
			}

		}

		$SQLBB->freeresult($result);   
		unset($pass);
		$SQLBB->close();
		return true;

	}
	else
	{
		$SQLBB->freeresult($result);
		$SQLBB->close();
		return false;
	}
	
	//dont know why they come here !
	return false;
}

function kleeja_auth_username ($user_id)
{
	return kleeja_auth_login ($user_id, false, false, 0, false, true);
}


/**
* Check for correct password
*/
function phpbb_check_hash($password, $hash)
{
	$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	if (strlen($hash) == 34)
		return (_hash_crypt_private($password, $hash, $itoa64) === $hash) ? true : false;
	return (md5($password) === $hash) ? true : false;
}
function _hash_gensalt_private($input, &$itoa64, $iteration_count_log2 = 6)
{
	if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
		$iteration_count_log2 = 8;
	$output = '$H$';
	$output .= $itoa64[min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
	$output .= _hash_encode64($input, 6, $itoa64);
	return $output;
}
function _hash_encode64($input, $count, &$itoa64)
{
	$output = '';
	$i = 0;
	do
	{
		$value = ord($input[$i++]);
		$output .= $itoa64[$value & 0x3f];
		if ($i < $count)
			$value |= ord($input[$i]) << 8;
		$output .= $itoa64[($value >> 6) & 0x3f];
		if ($i++ >= $count)
			break;
		if ($i < $count)
			$value |= ord($input[$i]) << 16;
		$output .= $itoa64[($value >> 12) & 0x3f];
		if ($i++ >= $count)
			break;
		$output .= $itoa64[($value >> 18) & 0x3f];
	}
	while ($i < $count);
	return $output;
}
function _hash_crypt_private($password, $setting, &$itoa64)
{
	$output = '*';
	if (substr($setting, 0, 3) != '$H$')
		return $output;
	$count_log2 = strpos($itoa64, $setting[3]);
	if ($count_log2 < 7 || $count_log2 > 30)
		return $output;
	$count = 1 << $count_log2;
	$salt = substr($setting, 4, 8);
	if (strlen($salt) != 8)
		return $output;

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
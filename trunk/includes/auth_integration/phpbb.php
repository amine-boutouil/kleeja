<?php
//
//auth integration phpbb with kleeja
//
//copyright 2007-2009 Kleeja.com ..
//license http://opensource.org/licenses/gpl-license.php GNU Public License
//$Author$ , $Rev$,  $Date::                           $
//


//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}
 
//
//Path of config file in phpBB
//
define('PHPBB_CONFIG_PATH', '/config.php');


function kleeja_auth_login ($name, $pass, $hashed = false, $expire, $loginadm = false)
{
	//global $forum_srv, $forum_user, $forum_pass, $forum_db, $forum_charset;
	global $script_path, $SQLBB, $phpEx, $phpbb_root_path, $lang, $script_encoding, $script_srv, $script_db, $script_user, $script_pass, $script_prefix, $config, $usrcp, $userinfo;
				
	//check for last slash / 
	if(isset($script_path))
	{
		if(isset($script_path[strlen($script_path)]) && $script_path[strlen($script_path)] == '/')
		{
			$script_path = substr($script_path, 0, strlen($script_path));
		}

		$script_path = ($script_path[0] == '/' ? '..' : '../') . $script_path;

		$script_path = PATH .  $script_path;

		//get some useful data from phbb config file
		if(file_exists($script_path . PHPBB_CONFIG_PATH))
		{
			require_once ($script_path . PHPBB_CONFIG_PATH);
			
			$forum_srv	= $dbhost;
			$forum_db	= $dbname;
			$forum_user	= $dbuser;
			$forum_pass	= $dbpasswd;
			$forum_prefix = $table_prefix;
		}
		else
		{
			big_error('Forum path is not correct', sprintf($lang['SCRIPT_AUTH_PATH_WRONG'], 'phpBB'));
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
	$SQLBB	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db,true);
	
	//if(!preg_match('/utf/i',strtolower($script_encoding)))
	//{
	$charset_db = $SQLBB->client_encoding();
	$SQLBB->set_names($charset_db);
	//}

	unset($forum_pass); // We do not need this any longer

	//phpbb3
	if(file_exists($script_path . '/includes/functions_transfer.php'))
	{
		//get utf tools
		define('IN_PHPBB', true);
		$phpbb_root_path = $script_path . '/';
		$phpEx = 'php';
		include_once($script_path . '/includes/utf/utf_tools.' . $phpEx);

		$row_leve = 'user_type';
		$admin_level = 3;					
		$query2 = array(
						'SELECT'	=> '*',
						'FROM'		=> "`{$forum_prefix}users`",
					);
		$query2['WHERE'] = $hashed ?  "user_id='" . intval($name) . "'  AND user_password='" . $SQLBB->real_escape($pass) . "' " : "username_clean='" . utf8_clean_string($name) . "'";

		$query = '';

		if(!$hashed)
		{
			$result2 = $SQLBB->build($query2);					
			while($row=$SQLBB->fetch_array($result2))
			{
				if(phpbb_check_hash($pass, $row['user_password']))
				{
					$query = $query2;
				}
			}
			$SQLBB->freeresult($result2);   
		}
		else
		{
			$query = $query2;
		}
	}
	else//phpbb2
	{
		if(!function_exists('iconv') && !preg_match('/utf/i',strtolower($script_encoding)))
		{
			big_error('No support for ICONV', 'You must enable the ICONV library to integrate kleeja with your forum. You can solve your problem by changing your forum db charset to UTF8.'); 
		}

		$row_leve = 'user_level';
		$admin_level = 1;

		$query = array(
						'SELECT'	=> '*',
						'FROM'		=> "`{$forum_prefix}users`",
						'WHERE'		=>"username='" . $SQLBB->real_escape($name) . "' AND user_password='" . md5($pass) . "'"
					);

		$query['WHERE'] = $hashed ?  "user_id='" . intval($name) . "'  AND user_password='" . $SQLBB->real_escape($pass) . "' " : "username='" . $SQLBB->real_escape($name) . "' AND user_password='" . md5($pass) . "'";
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
			if($SQLBB->num_rows($SQLBB->query("SELECT ban_userid FROM `{$forum_prefix}banlist` WHERE ban_userid='" . intval($row['user_id']) . "'")) == 0)
			{
				if(!$loginadm)
				{
					define('USER_ID', $row['user_id']);
					define('USER_NAME', preg_match('/utf/i', strtolower($script_encoding)) ? $row['username'] : iconv(strtoupper($script_encoding), "UTF-8//IGNORE", $row['username']));
					define('USER_MAIL',$row['user_email']);
					define('USER_ADMIN',($row[$row_leve] == $admin_level) ? 1 : 0);
				}

				//define('LAST_VISIT',$row['last_visit']);
				$userinfo = $row;

				if(!$hashed)
				{	
					$hash_key_expire = sha1(md5($config['h_key']) .  $expire);
					if(!$loginadm)
					{
						$usrcp->kleeja_set_cookie('ulogu', $usrcp->en_de_crypt($row['user_id'] . '|' . $row['user_password'] . '|' . $expire . '|' . $hash_key_expire), $expire);
					}
				}

				($hook = kleeja_run_hook('qr_while_usrdata_phpbb_usr_class')) ? eval($hook) : null; //run hook
			}
			else
			{
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
		$SQLBB->close();
		return false;
	}
}

function kleeja_auth_username ($user_id)
{
	global $script_path, $SQLBB, $phpEx, $phpbb_root_path, $lang, $script_encoding, $script_srv, $script_db, $script_user, $script_pass, $script_prefix;
				
	//check for last slash / 
	if(isset($script_path))
	{
		if($script_path[strlen($script_path)] == '/')
		{
			$script_path = substr($script_path, 0, strlen($script_path));
		}

		$script_path = ($script_path[0] == '/' ? '..' : '../') . $script_path;

		$script_path = PATH .  $script_path;

		//get some useful data from phbb config file
		if(file_exists($script_path . PHPBB_CONFIG_PATH))
		{
			require_once ($script_path . PHPBB_CONFIG_PATH);

			$forum_srv	= $dbhost;
			$forum_db	= $dbname;
			$forum_user	= $dbuser;
			$forum_pass	= $dbpasswd;
			$forum_prefix = $table_prefix;
		} 
		else
		{
			big_error('Forum path is not correct', sprintf($lang['SCRIPT_AUTH_PATH_WRONG'], 'phpBB'));
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
	$SQLBB	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db,TRUE);
	//$charset_db = @mysql_client_encoding($SQLBB->connect_id);
	unset($forum_pass); // We do not need this any longe

	if(!function_exists('iconv') && !preg_match('/utf/i', strtolower($script_encoding)))
 	{
 		big_error('No support for ICONV', 'You must enable the ICONV library to integrate kleeja with your forum. You can solve your problem by changing your forum db charset to UTF8.'); 
 	}

	$query_name = array(
						'SELECT'	=> 'username',
						'FROM'		=> "`{$forum_prefix}users`",
						'WHERE'		=> "user_id='" . intval($user_id) . "'"
				);

	($hook = kleeja_run_hook('qr_select_usrname_phpbb_usr_class')) ? eval($hook) : null; //run hook				
	$result_name = $SQLBB->build($query_name);

	if ($SQLBB->num_rows($result_name) > 0) 
	{
		while($row = $SQLBB->fetch_array($result_name))
		{
			$returnname = (preg_match('/utf/i',strtolower($script_encoding))) ? $row['username'] : iconv(strtoupper($script_encoding),"UTF-8//IGNORE",$row['username']);
		}
		$SQLBB->freeresult($result_name); 
		$SQLBB->close();
		return $returnname;
	}
	else
	{
		$SQLBB->close();
		return false;
	}
}


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
	global $script_prefix;
	global $script_srv,$script_user,$script_pass,$script_db;
	
	$SQLBB	= new SSQL($script_srv,$script_user,$script_pass,$script_db);
	unset($script_pass); // We do not need this any longe
	$query = array(
							'SELECT'	=> 'config_value',
							'FROM'		=> "`{$script_prefix}config`",
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


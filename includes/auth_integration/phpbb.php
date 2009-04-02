<?php
//
//auth integration phpbb with kleeja
//


//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}
  

function kleeja_auth_login ($name, $pass)
{
	//global $forum_srv, $forum_user, $forum_pass, $forum_db, $forum_charset;
	global $forum_path, $SQLBB, $phpEx, $phpbb_root_path, $lang;
				
		//check for last slash / 
	if($forum_path[strlen($forum_path)] == '/')
	{
			$forum_path = substr($forum_path, 0, strlen($forum_path));
	}
					
	$forum_path = ($forum_path[0] == '/' ? '..' : '../') . $forum_path;

	//get some useful data from phbb config file
	if(file_exists($forum_path . '/config.php'))
	{
		require ($forum_path . '/config.php');
		
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
	
	//if no variables of db
	if(empty($forum_srv) || empty($forum_user) || empty($forum_db))
	{
		return;
	}
								
	//conecting ...		
	$SQLBB	= new SSQL($forum_srv,$forum_user,$forum_pass,$forum_db);
	$charset_db = @mysql_client_encoding($SQLBB->connect_id);
					
	unset($forum_pass); // We do not need this any longe
					
	//phpbb3
	if(file_exists($forum_path . '/includes/functions_transfer.php'))
	{
		//get utf tools
		define('IN_PHPBB', true);
		$phpbb_root_path = $forum_path . '/';
		$phpEx = 'php';
		include_once($forum_path . '/includes/utf/utf_tools.' . $phpEx);
							
		$row_leve = 'user_type';
		$admin_level = 3;					
		$query2 = array('SELECT'	=> '*',
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
	
		//must be utf8 !
		if(strpos(strtolower($charset_db), 'utf') === false)
		{
			big_error(sprintf($lang['AUTH_INTEGRATION_N_UTF8_T'], 'phpBB2'), sprintf($lang['AUTH_INTEGRATION_N_UTF8'], 'phpBB2'));
		}
	
		$row_leve = 'user_level';
		$admin_level = 1;
	
		$query = array('SELECT'	=> '*',
						'FROM'		=> "`{$forum_prefix}users`",
						'WHERE'		=>"username='" . $SQLBB->escape($name) . "' AND user_password='" . md5($pass) . "'"
					);
								
	}
					
	($hook = kleeja_run_hook('qr_select_usrdata_phpbb_usr_class')) ? eval($hook) : null; //run hook		
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
			($hook = kleeja_run_hook('qr_while_usrdata_phpbb_usr_class')) ? eval($hook) : null; //run hook

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

<?php
//
//auth integration vb with kleeja
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
//Path of config file in vb
//
define('VB_CONFIG_PATH', '/includes/config.php');

function kleeja_auth_login ($name, $pass, $hashed = false, $expire, $loginadm = false)
{
	// ok, i dont hate vb .. but i cant feel my self use it ... 
	global $script_path, $lang, $script_encoding, $script_srv, $script_db, $script_user, $script_pass, $script_prefix, $config, $usrcp, $userinfo, $script_db_charset;

	if(isset($script_path))
	{				
		//check for last slash
		if(isset($script_path[strlen($script_path)]) && $script_path[strlen($script_path)] == '/')
		{
			$script_path = substr($script_path, 0, strlen($script_path));
		}

		$script_path = ($script_path[0] == '/' ? '..' : '../') . $script_path;
		
		$script_path = PATH .  $script_path;

		//get some useful data from vb config file
		if(file_exists($script_path . VB_CONFIG_PATH))
		{
			require_once ($script_path . VB_CONFIG_PATH);

			//
			//get config from config file
			//
			$forum_srv	= $config['MasterServer']['servername'];
			$forum_db	= $config['Database']['dbname'];
			$forum_user	= $config['MasterServer']['username'];
			$forum_pass	= $config['MasterServer']['password'];
			$forum_prefix= $config['Database']['tableprefix'];

			//some people change their db charset 
			if(isset($config['Mysqli']['charset']))
			{
				$forum_db_charset = $config['Mysqli']['charset'];
			}
		} 
		else
		{
			big_error('Forum path is not correct', sprintf($lang['SCRIPT_AUTH_PATH_WRONG'], 'Vbulletin'));
		}
	}
	else
	{
		//
		//custom config data
		//
		$forum_srv	= $script_srv;
		$forum_db	= $script_db;
		$forum_user	= $script_user;
		$forum_pass	= $script_pass;
		$forum_prefix = $script_prefix;

		//some people change their db charset 
		if(isset($script_db_charset))
		{
			$forum_db_charset = $script_db_charset;
		}
	}

	if(empty($forum_srv) || empty($forum_user) || empty($forum_db))
	{
		return;
	}

	$SQLVB	= new SSQL($forum_srv, $forum_user, $forum_pass, $forum_db, true);

	//if(!preg_match('/utf/i',strtolower($script_encoding)))
	//{
	if(isset($forum_db_charset))
	{	//config
		$SQLVB->set_names($forum_db_charset);
	}
	else //auto
	{
		$charset_db = $SQLVB->client_encoding();
		$SQLVB->set_names($charset_db);
	}
	//}
	//$mysql_version = @mysql_get_server_info($SQLVB->connect_id);

	unset($forum_pass); // We do not need this any longer

	if(!function_exists('iconv') && !preg_match('/utf/i',strtolower($script_encoding)))
 	{
 		big_error('No support for ICONV', 'You must enable the ICONV library to integrate kleeja with your forum. You can solve your problem by changing your forum db charset to UTF8.'); 
 	}

	$query_salt = array(
						'SELECT'	=> $hashed ? '*' : 'salt', 
						'FROM'		=> "`{$forum_prefix}user`",
					);

	$query_salt['WHERE'] = $hashed ? "userid='" . intval($name) . "'  AND password='" . $SQLVB->real_escape($pass) . "' AND usergroupid != '8'" :  "username='" . $SQLVB->real_escape($name) . "' AND usergroupid != '8'";

	($hook = kleeja_run_hook('qr_select_usrdata_vb_usr_class')) ? eval($hook) : null; //run hook				
	$result_salt = $SQLVB->build($query_salt);

	if ($SQLVB->num_rows($result_salt) > 0) 
	{
		while($row1=$SQLVB->fetch_array($result_salt))
		{
			if(!$hashed)
			{
				$pass = md5(md5($pass) . $row1['salt']);  // without normal md5

				$query	= array(
								'SELECT'	=> '*',
								'FROM'	=> "`{$forum_prefix}user`",
								'WHERE'	=> "username='" . $SQLVB->real_escape($name) . "' AND password='" . $SQLVB->real_escape($pass) . "' AND usergroupid != '8'"
						);
		
				$result = $SQLVB->build($query);

				if ($SQLVB->num_rows($result) != 0)
				{
					while($row=$SQLVB->fetch_array($result))
					{
						if(!$loginadm)
						{
							define('USER_ID', $row['userid']);
							define('USER_NAME', preg_match('/utf/i', strtolower($script_encoding)) ? $row['username'] : iconv(strtoupper($script_encoding), "UTF-8//IGNORE", $row['username']));
							define('USER_MAIL', $row['email']);
							define('USER_ADMIN', $row['usergroupid'] == 6 ? 1 : 0);
						}

						//define('LAST_VISIT',$row['last_visit']);

						$userinfo = $row;
						$hash_key_expire = sha1(md5($config['h_key']) .  $expire);
						if(!$loginadm)
						{
							$usrcp->kleeja_set_cookie('ulogu', $usrcp->en_de_crypt($row['userid'] . '|' . $row['password'] . '|' . $expire . '|' . $hash_key_expire), $expire);
						}

					($hook = kleeja_run_hook('qr_while_usrdata_vb_usr_class')) ? eval($hook) : null; //run hook
				}
				$SQLVB->freeresult($result);
			}#nums_sql2
			else
			{
				$SQLVB->close();
				return false;
			}
			}
			else
			{
				if(!$loginadm)
				{
					define('USER_ID', $row1['userid']);
					define('USER_NAME', preg_match('/utf/i', strtolower($script_encoding)) ? $row1['username'] : iconv(strtoupper($script_encoding),"UTF-8//IGNORE",$row1['username']));
					define('USER_MAIL',$row1['email']);
					define('USER_ADMIN',($row1['usergroupid'] == 6) ? 1 : 0);
					$userinfo = $row1;
				}
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

function kleeja_auth_username ($user_id)
{
	// ok, i dont hate vb .. but i cant feel my self use it ... 
	global $script_path, $lang, $script_encoding, $script_srv, $script_db, $script_user, $script_pass, $script_prefix;

	if(isset($script_path))
	{				
		//check for last slash
		if($script_path[strlen($script_path)] == '/')
		{
			$script_path = substr($script_path, 0, strlen($script_path));
		}

		$script_path = ($script_path[0] == '/' ? '..' : '../') . $script_path;
		$script_path = PATH .  $script_path;

		//get some useful data from vb config file
		if(file_exists($script_path . VB_CONFIG_PATH))
		{
			require_once ($script_path . VB_CONFIG_PATH);
			$forum_srv	= $config['MasterServer']['servername'];
			$forum_db	= $config['Database']['dbname'];
			$forum_user	= $config['MasterServer']['username'];
			$forum_pass	= $config['MasterServer']['password'];
			$forum_prefix= $config['Database']['tableprefix'];
		} 
		else
		{
			big_error('Forum path is not correct', sprintf($lang['SCRIPT_AUTH_PATH_WRONG'], 'Vbulletin'));
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

	if(empty($forum_srv) || empty($forum_user) || empty($forum_db))
	{
		return;
	}

	$SQLVB	= new SSQL($forum_srv, $forum_user, $forum_pass, $forum_db, TRUE);
	unset($forum_pass); // We do not need this any longe

	if(!function_exists('iconv') && !preg_match('/utf/i',strtolower($script_encoding)))
 	{
 		big_error('No support for ICONV', 'You must enable the ICONV library to integrate kleeja with your forum. You can solve your problem by changing your forum db charset to UTF8.'); 
 	}

	$query_name = array(
					'SELECT'	=> 'username',
					'FROM'		=> "`{$forum_prefix}user`",
					'WHERE'		=> "userid='" . intval($user_id) . "'"
				);

	($hook = kleeja_run_hook('qr_select_usrname_vb_usr_class')) ? eval($hook) : null; //run hook				
	$result_name = $SQLVB->build($query_name);

	if ($SQLVB->num_rows($result_name) > 0) 
	{
		while($row = $SQLVB->fetch_array($result_name))
		{
			$returnname = preg_match('/utf/i', strtolower($script_encoding)) ? $row['username'] : iconv(strtoupper($script_encoding), "UTF-8//IGNORE", $row['username']);
		}

		$SQLVB->freeresult($result_name); 
		$SQLVB->close();
		return $returnname;
	}
	else
	{
		$SQLVB->close();
		return false;
	}
}

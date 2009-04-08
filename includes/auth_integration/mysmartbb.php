<?php
//
//auth integration mysmbb with kleeja
//


//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}
  

function kleeja_auth_login ($name, $pass)
{
	global $forum_path, $lang;
	
	//check for last slash / 
	if($forum_path[strlen($forum_path)] == '/')
	{
		$forum_path = substr($forum_path, 0, strlen($forum_path));
	}

	$forum_path = ($forum_path[0] == '/' ? '..' : '../') .  $forum_path;
	
	
	//get database data from mysmartbb config file
	if(file_exists($forum_path . '/engine/config.php')) 
	{
		require ($forum_path . '/engine/config.php');
		$forum_srv	= $config['db']['server'];
		$forum_db	= $config['db']['name'];
		$forum_user	= $config['db']['username'];
		$forum_pass	= $config['db']['password'];
		$forum_prefix = $config['db']['prefix'];
	} 
	else
	{
		big_error('Forum path is not correct', sprintf($lang['SCRIPT_AUTH_PATH_WRONG'], 'MySmartBB'));
	}

	if(empty($forum_srv) || empty($forum_user) || empty($forum_db))
	{
		return;
	}
	
	$SQLMS	= new SSQL($forum_srv, $forum_user, $forum_pass, $forum_db);
	$charset_db = @mysql_client_encoding($SQLMS->connect_id);
	unset($forum_pass); // We do not need this any longe
	/*
	//must be utf8 !
	if(strpos(strtolower($charset_db), 'utf') === false)
	{
		big_error(sprintf($lang['AUTH_INTEGRATION_N_UTF8_T'], 'MySmartBB'), sprintf($lang['AUTH_INTEGRATION_N_UTF8'], 'MySmartBB'));
	}
	*/
	
	$query = array('SELECT'	=> '*',
					'FROM'	=> "`{$forum_prefix}member`",
					'WHERE'	=> "username='" . $SQLMS->real_escape($name) . "' AND password='" . md5($pass) . "'"
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
	
?>

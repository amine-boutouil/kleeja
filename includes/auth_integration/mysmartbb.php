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
	global $forum_path, $forum_charset;
			//check for last slash / 
	if($forum_path[strlen($forum_path)] == '/')
	{
			$forum_path = substr($forum_path, 0, strlen($forum_path));
	}
					
	if($forum_path[0] == '/')
	{
		$forum_path = '..' . $forum_path;
	}
	else
	{
		$forum_path = '../' . $forum_path;
	}			
	
	//get database data from mysmartbb config file
	if(file_exists($forum_path . '/engine/config.php')) {
	require ($forum_path . '/engine/config.php');
	$forum_prefix = $config['db']['prefix'];
	$forum_db = $config['db']['name'];
	$forum_user = $config['db']['username'];
	$forum_pass = $config['db']['password'];
	$forum_srv = $config['db']['server'];
	} 
	else
	 {
		big_error('Forum path is not correct', 'Please check your forum path correctly to integrate kleeja with your forum.');
	}
	//global $forum_srv,$forum_user,$forum_pass,$forum_db;
	global $forum_path, $forum_charset;


	if(empty($forum_srv) || empty($forum_user) || empty($forum_db))
	{
		return;
	}
	
	$SQLMS	= new SSQL($forum_srv, $forum_user, $forum_pass, $forum_db);
	$charset_db = empty($forum_charset) ? @mysql_client_encoding() : $forum_charset;
	unset($forum_pass); // We do not need this any longe
	
		//change it with iconv, i dont care if you enabled it or not 
		if(strpos(substr(0, 3, strtolower($charset_db)), 'utf') === false)
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
	
	$query = array('SELECT'	=> '*',
					'FROM'	=> "`{$forum_prefix}member`",
					'WHERE'	=> "username='" . $SQLMS->escape($name_b) . "' AND password='" . md5($pass_b) . "'"
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

<?php
//
//auth integration vb with kleeja
//


//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}
  

function kleeja_auth_login ($name, $pass)
{
	// ok, i dont hate vb .. but i cant feel my self use it ... 
	global $forum_path, $lang;
	
					
	//check for last slash
	if($forum_path[strlen($forum_path)] == '/')
	{
		$forum_path = substr($forum_path, 0, strlen($forum_path));
	}
					
	$forum_path = ($forum_path[0] == '/' ? '..' : '../') . $forum_path;
	
	//get some useful data from phbb config file
	if(file_exists($forum_path . '/includes/config.php'))
	{
		require ($forum_path . '/includes/config.php');
		
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
	
	if(empty($forum_srv) || empty($forum_user) || empty($forum_db))
	{
		return;
	}
				
	$SQLVB	= new SSQL($forum_srv, $forum_user, $forum_pass, $forum_db);
	$charset_db = @mysql_client_encoding($SQLVB->connect_id);
				
	unset($forum_pass); // We do not need this any longe
/*
	//must be utf8 !
	if(strpos(strtolower($charset_db), 'utf') === false)
	{
		big_error(sprintf($lang['AUTH_INTEGRATION_N_UTF8_T'], 'Vbulletin'), sprintf($lang['AUTH_INTEGRATION_N_UTF8'], 'Vbulletin'));
	}
	*/
	//header("Content-Type: text/html; charset=iso-8859-1"); 
	$query_salt = array(
					'SELECT'	=> 'salt',
					'FROM'		=> "`{$forum_prefix}user`",
					'WHERE'		=> "username='" . $SQLVB->real_escape($name) . "'"
				);
			
	($hook = kleeja_run_hook('qr_select_usrdata_vb_usr_class')) ? eval($hook) : null; //run hook				
	$result_salt = $SQLVB->build($query_salt);
				
	if ($SQLVB->num_rows($result_salt) > 0) 
	{
		while($row1=$SQLVB->fetch_array($result_salt))
		{

			$pass = md5(md5($pass) . $row1['salt']);  // without normal md5

			$query = array('SELECT'	=> '*',
							'FROM'	=> "`{$forum_prefix}user`",
							'WHERE'	=> "username='" . $SQLVB->real_escape($name) . "' AND password='" . $pass . "'"
							);
		
			$result = $SQLVB->build($query);
			
		
			if ($SQLVB->num_rows($result) != 0) 
			{
				while($row=$SQLVB->fetch_array($result))
				{
					$_SESSION['USER_ID']	= $row['userid'];
					$_SESSION['USER_NAME']	= $row['username'];
					$_SESSION['USER_MAIL']	= $row['email'];
					$_SESSION['USER_ADMIN']	= ($row['usergroupid'] == 6) ? 1 : 0;
					$_SESSION['USER_SESS']	= session_id();
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
	
	
	
?>

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
	global $forum_srv,$forum_user,$forum_pass,$forum_db;
	global $forum_prefix, $forum_charset;

	
	if(empty($forum_srv) || empty($forum_user) || empty($forum_db))
	{
		return;
	}
				
	$SQLVB	= new SSQL($forum_srv, $forum_user, $forum_pass, $forum_db);
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
	
	$query_salt = array(
					'SELECT'	=> 'salt',
					'FROM'		=> "`{$forum_prefix}user`",
					'WHERE'		=> "username='" . $SQLVB->escape($name_b) . "'"
				);
			
	($hook = kleeja_run_hook('qr_select_usrdata_vb_usr_class')) ? eval($hook) : null; //run hook				
	$result_salt = $SQLVB->build($query_salt);
				
	if ($SQLVB->num_rows($result_salt) > 0) 
	{
		
		while($row1=$SQLVB->fetch_array($result_salt))
		{

			$pass_b = md5(md5($pass_b) . $row1['salt']);  // without normal md5

			$query = array('SELECT'	=> '*',
							'FROM'	=> "`{$forum_prefix}user`",
							'WHERE'	=> "username='" . $SQLVB->escape($name_b) . "' AND password='" . $pass_b . "'"
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

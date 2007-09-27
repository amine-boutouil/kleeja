<?
##################################################
#						Kleeja 
#
# Filename : usr.php
# purpose :  get user data ..even from board database, its comlicated ...: supoort many types of forums ..
# copyright 2007 Kleeja.com ..
#class by : Saanina [@gmail.com]
##################################################

	  if (!defined('IN_COMMON'))
	  {
	  echo '<strong><br /><span style="color:red">[NOTE]: This Is Dangrous Place !! [2007 saanina@gmail.com]</span></strong>';
	  exit();
	  }
  
class usrcp {


				// this function like  traffic sign :)
				function data ($name, $pass)
				{
				global $config;
						
						
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
					global $SQL;
					
					$pass = md5($pass);
					$sql	=	$SQL->query("select * from `{$dbprefix}users` where name='$name' and password='$pass' ");

					if ($SQL->num_rows($sql) != 0  ) 
					{
					
					while($row=$SQL->fetch_array($sql)){
					$_SESSION['USER_ID']	=	$row['id'];
					$_SESSION['USER_NAME']	=	$row['name'];
					$_SESSION['USER_MAIL']	=	$row['mail'];
					$_SESSION['USER_ADMIN']	=	$row['admin'];
					$_SESSION['USER_SESS']	=	session_id();
					
					//update session_id
					$id 		= (int) 	$row['id'];
					$session_id = (string)  session_id();
					
					$update = $SQL->query("UPDATE `{$dbprefix}users` SET 
						session_id = '" . $session_id . "'
						WHERE id='" . $id . "'");
					if (!$update) {die("áÇíãßä ÊÍÏíË ÑÞã ÇáÌáÓå !!");}
					
					}
					$SQL->freeresult($sql);   
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
				global $forum_prefix;
				
				
					$pass = md5($pass);
					
					$SQLBB	= new SSQL;
				    $SQLBB->setinfo($forum_srv,$forum_user,$forum_pass,$forum_db);
				    $SQLBB->connect();
				    $SQLBB->selectdb();
					unset($forum_pass); // We do not need this any longe
					
					$sql	=	$SQLBB->query("SELECT * FROM `{$forum_prefix}users` WHERE username='$name' AND user_password='$pass' ");
				
					if ($SQLBB->num_rows($sql) != 0  ) 
					{
					
					while($row=$SQLBB->fetch_array($sql)){
					$_SESSION['USER_ID']	=	$row['user_id'];
					$_SESSION['USER_NAME']	=	$row['username'];
					$_SESSION['USER_MAIL']	=	$row['user_email'];
					$_SESSION['USER_ADMIN']	=	($row['user_level'] == 1) ? 1 : 0;
					$_SESSION['USER_SESS']	=	session_id();
					
					/* I cant thinking now .. help me :)
					//update session_id
					$user_id 		= (int)	$row['user_id'];
					$session_id 	= (string)	session_id();
					$last_visit 	= (int) 0;
					$current_time 	= (int) time();
					$login		 	= (int) 1;
					$admin 		 	= (int) $_SESSION['USER_ADMIN'];
					$page_id		= (int) 1;
					if (getenv('HTTP_X_FORWARDED_FOR')){$ip= getenv('HTTP_X_FORWARDED_FOR');}else {$ip= getenv('REMOTE_ADDR');}
					$user_ip= (string)  $this->encode_ip($ip); // <<< i delete this function  

					
					$sql = "UPDATE `{$forum_prefix}sessions`
						SET session_user_id = $user_id, session_start = $current_time, session_time = $current_time, session_page = $page_id, session_logged_in = $login, session_admin = $admin
						WHERE session_id = '" . $session_id . "' 
							AND session_ip = '$user_ip'";
					if ( !$SQLBB->query($sql) || !$SQLBB->num_rows($sql) )
					{
						$session_id = session_id();

						$sql = "INSERT INTO `{$forum_prefix}sessions`
							(session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in, session_admin)
							VALUES ('$session_id', $user_id, $current_time, $current_time, '$user_ip', $page_id, $login, $admin)";
						if ( !$SQLBB->query($sql) )
						{
							die('Error creating new session phpbb');
						}
					}
					*/

					}
					$SQLBB->freeresult($sql);   
					unset($pass);
					$SQLBB->close();
					
					
					return true;
					}
					else
					{
					return false;
					}
				}
				function vb ($name,$pass)
				{
				// i hate vb .. i cant feel my self use it ... 
				global $forum_srv,$forum_user,$forum_pass,$forum_db;
				global $forum_prefix;

					
					#$pass = ...... // without normal md5 .. as in mad-house ... vb is the worst
					
					$SQLVB	= new SSQL;
				    $SQLVB->setinfo($forum_srv,$forum_user,$forum_pass,$forum_db);
				    $SQLVB->connect();
				    $SQLVB->selectdb();
					unset($forum_pass); // We do not need this any longe
					
					$sql	=	$SQLVB->query("SELECT salt FROM `{$forum_prefix}user` WHERE username='$name' ");
				
					if ($SQLVB->num_rows($sql) != 0  ) 
					{
					while($row1=$SQLVB->fetch_array($sql)){
					
					$pass = md5($pass . $row1[salt]); 
					
					$sql2	=	$SQLVB->query("SELECT * FROM `{$forum_prefix}user` WHERE username='$name' AND password='$pass' ");
				
					if ($SQLVB->num_rows($sql2) != 0  ) 
					{
				
					
					while($row=$SQLVB->fetch_array($sql2)){
					$_SESSION['USER_ID']	=	$row['userid'];
					$_SESSION['USER_NAME']	=	$row['username'];
					$_SESSION['USER_MAIL']	=	$row['email'];
					$_SESSION['USER_ADMIN']	=	($row['usergroupid'] == 6) ? 1 : 0;
					$_SESSION['USER_SESS']	=	session_id();

					}
					$SQLVB->freeresult($sql2);   
					
					}#nums_sql2
					else
					{
					return false;
					}
					}#whil1
				
					$SQLVB->freeresult($sql); 
					
					unset($pass);
					$SQLVB->close();
					
					
					return true;
					}
					else
					{
					return false;
					}
				}
				//mysmartbb
				function mysmartbb ($name,$pass)
				{
				global $forum_srv,$forum_user,$forum_pass,$forum_db;
				global $forum_prefix;
				
				
					$pass = md5($pass);
					
					$SQLMS	= new SSQL;
				    $SQLMS->setinfo($forum_srv,$forum_user,$forum_pass,$forum_db);
				    $SQLMS->connect();
				    $SQLMS->selectdb();
					unset($forum_pass); // We do not need this any longe
					
					$sql	=	$SQLMS->query("SELECT * FROM `{$forum_prefix}member` WHERE username='$name' AND password='$pass' ");
				
					if ($SQLMS->num_rows($sql) != 0  ) 
					{
					
					while($row=$SQLMS->fetch_array($sql)){
					$_SESSION['USER_ID']	=	$row['id'];
					$_SESSION['USER_NAME']	=	$row['username'];
					$_SESSION['USER_MAIL']	=	$row['email'];
					$_SESSION['USER_ADMIN']	=	($row['usergroup'] == 1) ? 1 : 0;
					$_SESSION['USER_SESS']	=	session_id();
					

					}
					$SQLMS->freeresult($sql);   
					unset($pass);
					$SQLMS->close();
					
					
					return true;
					}
					else
					{
					return false;
					}
				}
				
				
				//name ..... mail ... admin 
				function id ()
				{
					if ($_SESSION['USER_SESS'] == session_id() )
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
					{
					return false;
					}
				}
				function name ()
				{
					if ($_SESSION['USER_SESS'] == session_id() )
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
					{
					return false;
					}
				}
				function mail ()
				{
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
					{
					return false;
					}
				}
				function admin ()
				{
				if ($_SESSION['USER_SESS'] == session_id() )
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
					{
					return false;
					}
				}
				
				function logout()
				{
				unset( $_SESSION['USER_ID'] );
				unset( $_SESSION['USER_NAME'] );
				unset( $_SESSION['USER_MAIL'] );
				unset( $_SESSION['USER_ADMIN'] );
				unset( $_SESSION['USER_SESS'] );
				return true;
				}
				
				function logout_cp()
				{
				unset( $_SESSION['USER_ADMIN'] );
				return true;
				}
}#end class



?>
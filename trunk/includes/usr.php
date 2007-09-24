<?
##################################################
#						Kleeja 
#
# Filename : usr.php
# purpose :  get user data ..even from board database:
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
						
						//normal 
						if ($config['user_system'] == 1) 
						{
						return $this->normal($name,$pass);
						}
						elseif ($config['user_system'] ==2 )  // phpbb
						{
						return $this->phpbb($name,$pass);
						}
						elseif ($config['user_system'] == 3)  // vb
						{
						return $this->vb($name,$pass);
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
				return false;
				}
				function vb ($name,$pass)
				{
				return false;
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
}#end class



?>
<?php
	//search
	//part of admin extensions
	//search about files or users
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $
	
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	

	//for style ..
	$stylee = "admin_search";
	//search files
	$action1 			= ADMIN_PATH . "?cp=files";
	//search users
	$action2 			= ADMIN_PATH . "?cp=users";	
	//wut the default user system
	$default_user_system = $config['user_system'] == 1 ? true : false;
		
?>

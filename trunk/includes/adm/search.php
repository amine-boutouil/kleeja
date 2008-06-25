<?php
	//search
	//part of admin extensions
	//search about files or users
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit();
	}
	

		//for style ..
		$stylee = "admin_search";
		//search files
		$action1 			= "admin.php?cp=files";
		//search users
		$action2 			= "admin.php?cp=users";	
		//wut the default user system
		$default_user_system= ($config['user_system']==1) ? true : false;
		
?>
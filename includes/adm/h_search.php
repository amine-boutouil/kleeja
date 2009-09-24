<?php
	//search
	//part of admin extensions
	//search about files or users
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author: saanina $ , $Rev: 618 $,  $Date:: 2009-07-22 10:49:40 +0300#$
	
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	

	//for style ..
	$stylee = "admin_search";
	//search files
	$action1 			= basename(ADMIN_PATH) . "?cp=files";
	//search users
	$action2 			= basename(ADMIN_PATH) . "?cp=users";	
	//wut the default user system
	$default_user_system = $config['user_system'] == 1 ? true : false;
		
?>

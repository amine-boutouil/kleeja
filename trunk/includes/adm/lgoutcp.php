<?php
	//lgoutcp
	//part of admin extensions
	//delete admin session
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	

		//remove just .. .administator session 
		if ($usrcp->logout_cp())
		{
			$text	= $lang['LOGOUT_CP_OK'];
			$stylee	= "admin_info";
		}

?>
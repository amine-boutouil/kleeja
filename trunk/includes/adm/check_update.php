<?php
	//check_update
	//part of admin extensions
	//is there any new update !
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}


	$version_data =	fetch_remote_file('http://www.kleeja.com/check_vers/ver_klj1.txt');
	
		
	if ($version_data === false)
	{
			$text	= $lang['ERROR_CHECK_VER'];
			$stylee	= "admin_err";
	}
	else
	{
		//
		// there is a file that we brought it !
		//
		
		$version_data = trim(htmlspecialchars($version_data));
		
		if (version_compare(strtolower(KLEEJA_VERSION),strtolower($version_data), '<'))
		{
			$text	= $lang['UPDATE_KLJ_NOW'];
			$stylee	= "admin_err";
		}
		else if (version_compare(strtolower(KLEEJA_VERSION),strtolower($version_data), '='))
		{
			$text	= $lang['U_LAST_VER_KLJ'];
			$stylee	= "admin_info";
		}
		else if (version_compare(strtolower(KLEEJA_VERSION),strtolower($version_data), '>'))
		{
			$text	= 'Pre-release version , click <a href="http://www.kleeja.com/bugs/">here</a> to tell us about any bug you face it.';
			$stylee	= "admin_info";
		}
	}




?>

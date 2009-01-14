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
		
		if (version_compare(strtolower(KLEEJA_VERSION), strtolower($version_data), '<'))
		{
			$text	= $lang['UPDATE_KLJ_NOW'];
			$stylee	= "admin_err";
		}
		else if (version_compare(strtolower(KLEEJA_VERSION), strtolower($version_data), '='))
		{
			
			//check if there is any pre-release
			$pre_version_data =	fetch_remote_file('http://www.kleeja.com/check_vers/ver_pre.txt');
			
			if(trim($pre_version_data) != '')
			{
				$version_data = $pre_version_data;
			}
			
			
			$text	= $lang['U_LAST_VER_KLJ'];
			$stylee	= "admin_info";
			
		}
		else if (version_compare(strtolower(KLEEJA_VERSION), strtolower($version_data), '>'))
		{
			$text	= 'You are using pre-release version , click <a href="http://www.kleeja.com/bugs/">here</a> to tell us about any bug you face it.';
			$stylee	= "admin_info";
		}
		
		//lets recore it
		$v = unserialize($config['new_version']);
	
		if(version_compare(strtolower($v['version_number']), strtolower($version_data), '<') || isset($_GET['show_msg']))
		{
			$data	= array('version_number'	=> $version_data,
							'last_check'		=> time(),
							'msg_appeared'		=> isset($_GET['show_msg']) ? true : false, 
							'pre_release'		=> (trim($pre_version_data) != '')? true : false
						);
			

			$data = serialize($data);
			
			$update_query = array(
									'UPDATE'	=> "{$dbprefix}config",
									'SET'		=> "value='"  . addslashes($data) . "'",
									'WHERE'		=> "name='new_version'"
									);

			if (!$SQL->build($update_query)) die($lang['CANT_UPDATE_SQL']);
			
			//clean cache
			delete_cache('data_config');
			
			
			//then go back  to start
			if(isset($_GET['show_msg']))
			{
				header('location: ./admin.php');
			}
		}	
	}




?>

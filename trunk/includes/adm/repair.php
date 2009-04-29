<?php
//repair
//part of admin extensions
//repaires tables and delete caches
//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	
	//
	//fix tables ..
	//
	$query	= "SHOW TABLE STATUS";
	$result	= $SQL->query($query);
	$text = '';
	
	while($row=$SQL->fetch_array($result))
	{
		//fix
		$queryf	=	"REPAIR TABLE `" . $row['Name'] . "`";
		$resultf = $SQL->query($queryf);
		if ($resultf)
		{
			$text .= $lang['REPAIRE_TABLE'] . $row['Name'] . '<br />';
		}

	}
	
	$SQL->freeresult($result);
	
	//
	//fix stats ..
	//

	//ge all files sizes
	$query_s = array(
					'SELECT'	=> 'size',
					'FROM'		=> "`{$dbprefix}files`"
				);
							
	$result_s = $SQL->build($query_s);

	$files_number = 0;
	$files_sizes = 0;
	while($row=$SQL->fetch_array($result_s))
	{
		//stats files
		$files_number++;
		$files_sizes = $files_sizes+$row['size'];
	}
	
	$SQL->freeresult($result_s);

	//get all users number
	$query_w	= array('SELECT'	=> 'name',
						'FROM'		=> "`{$dbprefix}users`"
						);
							
	$result_w = $SQL->build($query_w);
		
	$user_number = 0;
	while($row=$SQL->fetch_array($result_w))
	{
		//stats files
		$user_number++;
	}
	
	$SQL->freeresult($result_w);

	$update_query	= array('UPDATE'	=> "{$dbprefix}stats",
							'SET'		=> "files='" . $files_number . "',
											sizes='" . $files_sizes . "',
											users='" . $user_number . "'"
						);

	if ($SQL->build($update_query))
	{
		$text .=  $lang['REPAIRE_F_STAT'] . "<br />";
		$text .= $lang['REPAIRE_S_STAT'] . "<br />";
	}

	//clear cache
	delete_cache('', true);
	$text .= $lang['REPAIRE_CACHE'];


	$stylee = "admin_info";
?>
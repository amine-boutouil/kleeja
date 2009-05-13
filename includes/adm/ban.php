<?php
	//ban
	//part of admin extensions
	//conrtoll bans
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	

		//for style ..
		$stylee 	= "admin_ban";
		$action 	= "admin.php?cp=ban";

		$query = array(
					'SELECT'	=> 'ban',
					'FROM'		=> "{$dbprefix}stats"
					);
						
		$result = $SQL->build($query);

		while($row=$SQL->fetch_array($result))
		{

			$ban = ( isset($_POST["ban_text"]) ) ? $_POST['ban_text'] : $row['ban'];
			
			//when submit !!
			if (isset($_POST['submit']))
			{
				//update
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "ban='" . $SQL->escape($ban) . "'"
								);

				if ($SQL->build($update_query))
				{
					//delete cache ..
					delete_cache('data_ban');
				}
			}
		}
		
		$SQL->freeresult($result);


		//after submit 
		if (isset($_POST['submit']))
		{
			$text	= $lang['BAN_UPDATED'];
			$stylee	= "admin_info";
		}

?>

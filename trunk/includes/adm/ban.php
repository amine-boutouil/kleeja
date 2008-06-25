<?php
	//ban
	//part of admin extensions
	//conrtoll bans
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit();
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

			$ban = ( isset($_POST["ban_text"]) ) ? $_POST["ban_text"] : $row['ban'];
			
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
					if (file_exists('cache/data_ban.php'))
					{
						@unlink('cache/data_ban.php');
					}
				}
				else
				{
					die($lang['CANT_UPDATE_SQL']);
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
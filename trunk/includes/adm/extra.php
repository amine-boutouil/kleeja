<?php
	//extra
	//part of admin extensions
	//conrtoll extra heaer and footer
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit();
	}
	


		//for style ..
		$stylee 	= "admin_extra";
		$action 	= "admin.php?cp=extra";
		
		$query = array(
					'SELECT'	=> 'ex_header,ex_footer',
					'FROM'		=> "{$dbprefix}stats"
					);
						
		$result = $SQL->build($query);
		
		while($row=$SQL->fetch_array($result))
		{
			$ex_headere = ( isset($_POST["ex_header"]) ) ? $_POST["ex_header"] : $row['ex_header'];
			$ex_footere = ( isset($_POST["ex_footer"]) ) ? $_POST["ex_footer"] : $row['ex_footer'];
			
			$ex_header = htmlspecialchars($ex_headere);
			$ex_footer = htmlspecialchars($ex_footere);
				
			//when submit !!
			if (isset($_POST['submit']))
			{
				//update
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "ex_header = '". $ex_headere ."', ex_footer = '". $ex_footere ."'"
								);

				if ($SQL->build($update_query))
				{
					//delete cache ..
					if (file_exists('cache/data_extra.php'))
					{
						@unlink('cache/data_extra.php');
						//@unlink('cache/' . $config['style'] . '_header.php');
						//@unlink('cache/' . $config['style'] . '_footer.php');
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
			$text	= $lang['EXTRA_UPDATED'];
			$stylee	= "admin_info";
		}
		
?>
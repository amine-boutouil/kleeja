<?php
	//extra
	//part of admin extensions
	//conrtoll extra heaer and footer
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
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
			$ex_headere = ( isset($_POST["ex_header"]) ) ? $_POST['ex_header'] : $row['ex_header'];
			$ex_footere = ( isset($_POST["ex_footer"]) ) ? $_POST['ex_footer'] : $row['ex_footer'];
			
			$ex_header = htmlspecialchars($ex_headere);
			$ex_footer = htmlspecialchars($ex_footere);
				
			//when submit !!
			if (isset($_POST['submit']))
			{
				//update
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "ex_header = '" . $ex_headere . "', ex_footer = '" . $ex_footere . "'"
								);

				if ($SQL->build($update_query))
				{
					//delete cache ..
					delete_cache('data_extra');
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

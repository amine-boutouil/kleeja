<?php
	//extra
	//part of admin extensions
	//conrtoll extra heaer and footer
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author: saanina $ , $Rev: 1076 $,  $Date:: 2009-09-12 08:18:51 +0300#$
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	


		//for style ..
		$stylee 	= "admin_extra";
		$action 	= basename(ADMIN_PATH) . "?cp=extra";
		
		$query = array(
					'SELECT'	=> 'ex_header,ex_footer',
					'FROM'		=> "{$dbprefix}stats"
					);
						
		$result = $SQL->build($query);
		
		//is there any change !
		$AFFECTED = false;
		
		while($row=$SQL->fetch_array($result))
		{
			$ex_header = isset($_POST["ex_header"]) ? $_POST['ex_header'] : $row['ex_header'];
			$ex_footer = isset($_POST["ex_footer"]) ? $_POST['ex_footer'] : $row['ex_footer'];


			//when submit !!
			if (isset($_POST['submit']))
			{
				$ex_header = htmlspecialchars_decode($ex_header);
				$ex_footer = htmlspecialchars_decode($ex_footer);

				//update
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "ex_header = '" . $SQL->real_escape($ex_header) . "', ex_footer = '" . $SQL->real_escape($ex_footer) . "'"
								);

				$SQL->build($update_query);

				if($SQL->affected())
				{
					$AFFECTED = true;
					//delete cache ..
					delete_cache('data_extra');
				}
			}
			else
			{
				$ex_header = htmlspecialchars($ex_header);
				$ex_footer = htmlspecialchars($ex_footer);
			}
		}
		$SQL->freeresult($result);


		//after submit 
		if (isset($_POST['submit']))
		{
			$text	= $AFFECTED ? $lang['EXTRA_UPDATED'] : $lang['NO_UP_CHANGE_S'];
			$stylee	= "admin_info";
		}
		
#<--- EOF
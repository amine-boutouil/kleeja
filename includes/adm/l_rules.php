<?php
	//rules
	//part of admin extensions
	//conrtoll rules
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author: saanina $ , $Rev: 618 $,  $Date:: 2009-07-22 10:49:40 +0300#$
	
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	

		
		//for style ..
		$stylee 		= "admin_rules";
		$action 		= basename(ADMIN_PATH) . "?cp=rules";

		$query = array(
					'SELECT'	=> 'rules',
					'FROM'		=> "{$dbprefix}stats"
					);
						
		$result = $SQL->build($query);

		while($row=$SQL->fetch_array($result))
		{
			$rulesw = isset($_POST['rules_text']) ? $_POST['rules_text'] : $row['rules'];
			$rules = htmlspecialchars($rulesw);
			
			//when submit !!
			if (isset($_POST['submit']))
			{
				//update
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "rules = '". $rulesw ."'"
								);

				if ($SQL->build($update_query))
				{
					//delete cache ..
					delete_cache('data_rules');
				}
			}
		}
		$SQL->freeresult($result);


		//after submit 
		if (isset($_POST['submit']))
		{
			$text	= $lang['RULES_UPDATED'];
			$stylee	= "admin_info";
		}
?>

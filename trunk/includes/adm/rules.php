<?php
	//rules
	//part of admin extensions
	//conrtoll rules
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	

		
		//for style ..
		$stylee 		= "admin_rules";
		$action 		= "admin.php?cp=rules";

		$query = array(
					'SELECT'	=> 'rules',
					'FROM'		=> "{$dbprefix}stats"
					);
						
		$result = $SQL->build($query);

		while($row=$SQL->fetch_array($result))
		{
			$rulesw = ( isset($_POST["rules_text"]) ) ? $_POST["rules_text"] : $row['rules'];
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
					if (file_exists('cache/data_rules.php'))
					{
						@unlink('cache/data_rules.php');
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
			$text	= $lang['RULES_UPDATED'];
			$stylee	= "admin_info";
		}
?>
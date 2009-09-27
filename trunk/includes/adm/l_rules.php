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
$stylee	= "admin_rules";
$action	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php');

$affected = false;

$query	= array(
				'SELECT'	=> 'rules',
				'FROM'		=> "{$dbprefix}stats"
			);

$result = $SQL->build($query);

while($row=$SQL->fetch_array($result))
{
	$rulesw = isset($_POST['rules_text']) ? $_POST['rules_text'] : $row['rules'];
	$rules = htmlspecialchars($rulesw);
			
	//when submit
	if (isset($_POST['submit']))
	{
		//update
		$update_query	= array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> "rules = '" . $SQL->real_escape($rulesw) . "'"
							);

		$SQL->build($update_query);
		if($SQL->affected())
		{
			$affected = true;
			delete_cache('data_rules');
		}
	}
}

$SQL->freeresult($result);


//after submit 
if (isset($_POST['submit']))
{
	$text	= ($affected ? $lang['RULES_UPDATED'] : $lang['NO_UP_CHANGE_S']) . '<meta HTTP-EQUIV="REFRESH" content="0; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";
	$stylee	= "admin_info";
}
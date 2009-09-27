<?php
//ban
//part of admin extensions
//conrtoll bans

//copyright 2007-2009 Kleeja.com ..
//license http://opensource.org/licenses/gpl-license.php GNU Public License
//$Author: saanina $ , $Rev: 893 $,  $Date:: 2009-08-24 00:23:58 +0300#$

// not for directly open
if (!defined('IN_ADMIN'))
{
	exit('no directly opening : ' . __file__);
}

//for style ..
$stylee	= "admin_ban";
$action	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php');

$affected = false;

$query	= array(
				'SELECT'	=> 'ban',
				'FROM'		=> "{$dbprefix}stats"
			);

$result = $SQL->build($query);

while($row=$SQL->fetch_array($result))
{
	$ban = isset($_POST["ban_text"]) ? htmlspecialchars($_POST['ban_text']) : $row['ban'];

	//when submit
	if (isset($_POST['submit']))
	{
		//update
		$update_query	= array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> "ban='" . $SQL->escape($ban) . "'"
							);

		$SQL->build($update_query);
		if($SQL->affected())
		{
			$affected = true;
			delete_cache('data_ban');
		}
	}
}

$SQL->freeresult($result);

//after submit 
if (isset($_POST['submit']))
{
	$text	= ($affected ? $lang['BAN_UPDATED'] : $lang['NO_UP_CHANGE_S']) . '<meta HTTP-EQUIV="REFRESH" content="0; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";
	$stylee	= "admin_info";
}

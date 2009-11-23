<?php
/**
*
* @package adm
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/

	
// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}
	

//for style ..
$stylee		= "admin_extra";
$action		= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php');
$H_FORM_KEYS	= kleeja_add_form_key('adm_extra');

//
// Check form key
//
if (isset($_POST['submit']))
{
	if(!kleeja_check_form_key('adm_extra'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}

$query	= array(
				'SELECT'	=> 'ex_header,ex_footer',
				'FROM'		=> "{$dbprefix}stats"
			);

$result = $SQL->build($query);
		
//is there any change !
$affected = false;

while($row=$SQL->fetch_array($result))
{
	$ex_header = isset($_POST['ex_header']) ? $_POST['ex_header'] : $row['ex_header'];
	$ex_footer = isset($_POST['ex_footer']) ? $_POST['ex_footer'] : $row['ex_footer'];


	//when submit !!
	if (isset($_POST['submit']))
	{
		$ex_header = htmlspecialchars_decode($ex_header);
		$ex_footer = htmlspecialchars_decode($ex_footer);

		//update
		$update_query	= array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> "ex_header = '" . $SQL->real_escape($ex_header) . "', ex_footer = '" . $SQL->real_escape($ex_footer) . "'"
							);

		$SQL->build($update_query);

		if($SQL->affected())
		{
			$affected = true;
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
	$text	= $affected ? $lang['EXTRA_UPDATED'] : $lang['NO_UP_CHANGE_S'];
	$stylee	= "admin_info";
}

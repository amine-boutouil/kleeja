<?php
//check_update
//part of admin extensions
//is there any new update !

//copyright 2007-2009 Kleeja.com ..
//license http://opensource.org/licenses/gpl-license.php GNU Public License
//$Author: saanina $ , $Rev: 943 $,  $Date:: 2009-08-29 06:37:05 +0300#$

// not for directly open
if (!defined('IN_ADMIN'))
{
	exit('no directly opening : ' . __file__);
}

$stylee	= "admin_check_update";
$error = false;
$update_link = $config['siteurl'] . 'install/update.php?lang=' . $config['language'];

//get data from kleeja database
$b_data = fetch_remote_file('http://www.kleeja.com/check_vers/?i=' . urlencode($_SERVER['SERVER_NAME']) . '&v=' . KLEEJA_VERSION, false, 6);

if ($b_data === false && !isset($_GET['show_msg']))
{
	$text	= $lang['ERROR_CHECK_VER'];
	$error	= true;
}
else
{
	//
	// there is a file that we brought it !
	//
	$b_data = @explode('|', $b_data);

	$version_data = trim(htmlspecialchars($b_data[0]));

	if (version_compare(strtolower(KLEEJA_VERSION), strtolower($version_data), '<'))
	{
		$text	= sprintf($lang['UPDATE_NOW_S'] , KLEEJA_VERSION, strtolower($version_data)) . '<br /><br />' . $lang['UPDATE_KLJ_NOW'];
		$error	= true;
	}
	else if (version_compare(strtolower(KLEEJA_VERSION), strtolower($version_data), '='))
	{
		$text	= $lang['U_LAST_VER_KLJ'];
	}
	else if (version_compare(strtolower(KLEEJA_VERSION), strtolower($version_data), '>'))
	{
		$text	= $lang['U_USE_PRE_RE'];
	}

	//lets recore it
	$v = @unserialize($config['new_version']);

	//To prevent expected error [ infinit loop ]
	if(isset($_GET['show_msg']))
	{
		$query_get	= array(
							'SELECT'	=> '*',
							'FROM'		=> "{$dbprefix}config",
							'WHERE'		=> "name = 'new_version'"
						);

		$result_get =  $SQL->build($query_get);

		if(!$SQL->num_rows($result_get))
		{
			//add new config value
			add_config('new_version', '');
		}
	}

	$data	= array(
					'version_number'	=> $version_data,
					'last_check'		=> time(),
					'msg_appeared'		=> isset($_GET['show_msg']) ? true : false,
					'copyrights'		=> !empty($b_data[1]) && strpos($b_data[1], 'yes') !== false ? true : false,
				);

	$data = serialize($data);

	update_config('new_version', $SQL->real_escape($data), false);
}
	
//then go back  to start
if(isset($_GET['show_msg']))
{
	redirect(basename(ADMIN_PATH) . '?update_done=1');
	$SQL->close();
	exit;
}

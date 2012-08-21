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


//check _GET Csrf token
if(!kleeja_check_form_key_get('GLOBAL_FORM_KEY'))
{
	kleeja_admin_err($lang['INVALID_GET_KEY'], true, $lang['ERROR'], true, basename(ADMIN_PATH), 2);
}


// We, I mean developrts and support team anywhere, need sometime
// some inforamtion about the status of Kleeja .. this will give 
// a zip file contain those data ..
if(isset($_GET['third_august_1987']))
{
	include PATH . 'includes/plugins.php';
	$zip = new zipfile();
	
	#kleeja version
	$zip->create_file(KLEEJA_VERSION, 'kleeja_version.txt');

	#grab configs
	$d_config = $config;
	unset($d_config['h_key'], $d_config['ftp_info']);
	$zip->create_file(var_export($d_config, true), 'config_vars.txt');
	unset($d_config);

	#php info
	ob_start();
	@phpinfo();
	$phpinfo = ob_get_contents();
	ob_end_clean();
	$zip->create_file($phpinfo, 'phpinfo.html');
	unset($phpinfo);

	#config file data
	$config_file_data = file_get_contents(PATH . 'config.php');
	$cvars = array('dbuser', 'dbpass', 'dbname', 'script_user', 'script_pass', 'script_db');
	$config_file_data = preg_replace('!\$(' . implode('|', $cvars). ')(\s*)=(\s*)["|\']([^\'"]+)["|\']!', '$\\1\\2=\\3"******"', $config_file_data);
	$zip->create_file($config_file_data, 'config.php');
	unset($config_file_data);

	#kleeja log
	if(file_exists(PATH . 'cache/kleeja_log.log'))
	{
		$zip->create_file(file_get_contents(PATH . 'cache/kleeja_log.log'), 'kleeja_log.log');
	}
	
	#EXTs enabled ?
	$zip->create_file(var_export($g_exts, true), 'guest_exts.txt');
	$zip->create_file(var_export($g_exts, true), 'user_exts.txt');
	
	#eval test, Im not so sure about this test, must be
	#tried at real situation.
	$t = 'OFF';
	@eval('$t = "ON";');
	$zip->create_file($t, 'evalTest.txt');

	#plugins info
	$zip->create_file(var_export($all_plg_hooks, true), 'hooks_info.txt');
	$zip->create_file(var_export($all_plg_plugins, true), 'plugins_info.txt');
	
	#ban info
	$zip->create_file(var_export($banss, true), 'ban_info.txt');

	#stats
	$stat_vars = array('stat_files', 'stat_sizes', 'stat_users', 'stat_last_file', 'stat_last_f_del',
				'stat_last_google', 'stat_last_bing', 'stat_google_num', 'stat_bing_num', 'stat_last_user');
	$zip->create_file(var_export(compact($stat_vars), true), 'stats.txt');
	unset($stat_vars);

	#push it
	header('Content-Type: application/zip');
	header('X-Download-Options: noopen');
	header('Content-Disposition: attachment; filename="KleejaDataForSupport' .  date('dmY'). '.zip"');
	echo $zip->zipped_file();
	$SQL->close();
	exit;
}


//
//fix tables ..
//
$query	= "SHOW TABLE STATUS";
$result	= $SQL->query($query);
$text = '';
	
while($row=$SQL->fetch_array($result))
{
	$queryf	=	"REPAIR TABLE `" . $row['Name'] . "`";
	$resultf = $SQL->query($queryf);
	if ($resultf)
	{
		$text .= '<li>' . $lang['REPAIRE_TABLE'] . $row['Name'] . '</li>';
	}
}
	
$SQL->freeresult($result);
	
//
//fix stats ..
//

//ge all files sizes
$query_s	= array(
					'SELECT'	=> 'size',
					'FROM'		=> "`{$dbprefix}files`"
				);

$result_s = $SQL->build($query_s);

$files_number = $files_sizes = 0;

while($row=$SQL->fetch_array($result_s))
{
	$files_number++;
	$files_sizes = $files_sizes+$row['size'];
}

$SQL->freeresult($result_s);

//get all users number
$query_w	= array(
					'SELECT'	=> 'name',
					'FROM'		=> "`{$dbprefix}users`"
				);

$result_w = $SQL->build($query_w);
		
$user_number = 0;
while($row=$SQL->fetch_array($result_w))
{
	$user_number++;
}
	
$SQL->freeresult($result_w);

$update_query	= array(
						'UPDATE'	=> "{$dbprefix}stats",
						'SET'		=> "files='" . $files_number . "', sizes='" . $files_sizes . "', users='" . $user_number . "'"
					);

if ($SQL->build($update_query))
{
	$text .= '<li>' . $lang['REPAIRE_F_STAT'] . '</li>';
	$text .= '<li>' . $lang['REPAIRE_S_STAT'] . '</li>';
}

//
//clear all cache ..
//
delete_cache('', true);
$text .= '<li>' . $lang['REPAIRE_CACHE'] . '</li>';

$stylee = "admin_info";


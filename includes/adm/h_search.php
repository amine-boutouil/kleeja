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
$stylee = "admin_search";
//search files
$action1 		= basename(ADMIN_PATH) . "?cp=c_files";
//search users
$action2 		= basename(ADMIN_PATH) . "?cp=g_users&amp;smt=show_su";	
//wut the default user system
$default_user_system = (int) $config['user_system'] == 1 ? true : false;

$H_FORM_KEYS	= kleeja_add_form_key('adm_files_search');
$H_FORM_KEYS2	= kleeja_add_form_key('adm_users_search');

$current_smt	= isset($_GET['smt']) ? (preg_match('![a-z0-9_]!i', trim($_GET['smt'])) ? trim($_GET['smt']) : 'files') : 'files';

#filling the inputs automatically via GET
$filled_ip = $filled_username = '';
if(isset($_GET['s_input']))
{
	if((int) $_GET['s_input'] == 2)
	{
		$filled_username = htmlspecialchars($_GET['s_value']);
	}
	elseif((int) $_GET['s_input'] == 1)
	{
		$filled_ip = htmlspecialchars($_GET['s_value']);
	}
}

//secondary menu
$go_menu = array(
				'files' => array('name'=>$lang['R_SEARCH'], 'link'=> basename(ADMIN_PATH) . '?cp=h_search&amp;smt=files', 'goto'=>'files', 'current'=> $current_smt == 'files'),
				#'sep1' => array('class'=>'separator'),
				'users' => array('name'=>$lang['SEARCH_USERS'], 'link'=> basename(ADMIN_PATH) . '?cp=h_search&amp;smt=users', 'goto'=>'users', 'current'=> $current_smt == 'users'),
				#'sep2' => array('class'=>'separator'),
	);
	
if(!$default_user_system)
{
	unset($go_menu['users']);
}

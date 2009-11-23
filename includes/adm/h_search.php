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
$action2 		= basename(ADMIN_PATH) . "?cp=g_users";	
//wut the default user system
$default_user_system = (int) $config['user_system'] == 1 ? true : false;

$H_FORM_KEYS	= kleeja_add_form_key('adm_files_search');
$H_FORM_KEYS2	= kleeja_add_form_key('adm_users_search');
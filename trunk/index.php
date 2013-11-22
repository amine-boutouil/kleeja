<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/

 
/**
 * We are in index.php file, useful for exceptions
 */
define('IN_REAL_INDEX', true);

/**
 * We are in middle uploading process, useful for exceptions
 */
define('IN_SUBMIT_UPLOADING', (isset($_POST['submitr']) || isset($_POST['submittxt'])));


/**
 * @ignore
 */
define('IN_KLEEJA', true);
define('PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
include PATH . 'includes/common.php';
include PATH . 'includes/classes/uploading.php';

$kljup	= new uploading;
$kljup->allowed_extensions = $d_groups[$user->data['group_id']]['exts'];

($hook = kleeja_run_hook('begin_index_page')) ? eval($hook) : null; //run hook

#Is kleeja only for memebers ?! 
if(empty($d_groups[2]['exts']) && !$user->is_user())
{
	kleeja_info($lang['SITE_FOR_MEMBER_ONLY'], $lang['HOME']);
}


if(ip('submit_files'))
{	
	$kljup->process();
	
	#show errors and info
	$ERRORS = sizeof($kljup->errors) ? $kljup->errors : false;

	#results
	$RESULTS = $kljup->results;

	#after sumbit template
	$current_template = 'uploading_results.php';
}
else
{
	#default template
	$current_template = 'index_body.php';
}


#how many inputs should be shown
$FILES_NUM_LOOP = array();
foreach(range(1, $config['filesnum']) as $i)
{
	$FILES_NUM_LOOP[] = array('i' => $i, 'show'=>($i == 1 || (!empty($config['filesnum_show']) && (int) $config['filesnum_show'] == 1) ? true : false));
}


#some words for template
$welcome_msg	= $config['welcome_msg'];
$filecp_link	= $user->is_user() ? $config['siteurl'] . ($config['mod_writer'] ? 'filecp.html' : 'ucp.php?go=filecp') : false;
$terms_msg		= sprintf($lang['AGREE_RULES'], '<a href="' . ($config['mod_writer'] ? 'rules.html' : 'go.php?go=rules') . '">' , '</a>');


//who online now feature 
//I dont like this feature and I prefer to disable it
$show_online = $config['allow_online'] == 1 ? true : false;
if ($show_online)
{
	$usersnum	=	0;
	$online_names	= array();
	$timeout		= 30; //30 second  
	$timeout2		= time()-$timeout;  

	//put another bot name
	($hook = kleeja_run_hook('anotherbots_online_index_page')) ? eval($hook) : null; //run hook

	$query = array(
					'SELECT'	=> 'u.name',
					'FROM'		=> "{$dbprefix}users u",
					'WHERE'		=> "u.last_visit > $timeout2"
				);

	($hook = kleeja_run_hook('qr_select_online_index_page')) ? eval($hook) : null; //run hook

	$result	= $SQL->build($query); 

	while($row=$SQL->fetch_array($result))
	{
		($hook = kleeja_run_hook('while_qr_select_online_index_page')) ? eval($hook) : null; //run hook	

		$usersnum++; 
		$online_names[$row['name']] = $row['name'];
	}#while

	$SQL->freeresult($result);

	//check & update most ever users and vistors was online
	if(empty($config['most_user_online_ever']) || trim($config['most_user_online_ever']) == '')
	{
		$most_online	= $usersnum;
		$on_muoe		= time();
	}
	else
	{
		list($most_online, $on_muoe) = @explode($config['most_user_online_ever']);
	}

	if((int) $most_online < $allnumbers || (empty($config['most_user_online_ever']) || trim($config['most_user_online_ever']) == ''))
	{
		update_config('most_user_online_ever', $usersnum . ':' . time());
	}

	$on_muoe = date('d-m-Y h:i a', $on_muoe);

	if(!$usersnum)
	{
		$show_online = false;
	}

	($hook = kleeja_run_hook('if_online_index_page')) ? eval($hook) : null; //run hook	
}#allow_online


($hook = kleeja_run_hook('end_index_page')) ? eval($hook) : null; //run hook	


#header
kleeja_header();
#index template
include get_template_path($current_template);
#footer
kleeja_footer();



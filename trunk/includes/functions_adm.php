<?php
/**
*
* @package adm
* @version $Id: functions.php 1910 2012-08-28 01:50:53Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}

/**
* Print cp error function handler
*
* For admin
*/
function kleeja_admin_err($msg, $navigation = true, $title='', $exit = true, $redirect = false, $rs = 5, $style = 'admin_err')
{
	global $text, $tpl, $SHOW_LIST, $adm_extensions, $adm_extensions_menu;
	global $STYLE_PATH_ADMIN, $lang, $olang, $SQL, $MINI_MENU;

	($hook = kleeja_run_hook('kleeja_admin_err_func')) ? eval($hook) : null; //run hook

	#Exception for ajax
	if(isset($_GET['_ajax_']))
	{
		$text = $msg  . "\n" . '<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . str_replace('&amp;', '&', $redirect) . '\');", 2000);</script>';
		echo_ajax(1, $tpl->display($style));
		$SQL->close();
		exit();
	}

	// assign {text} in err template
	$text		= $msg . ($redirect != false ? redirect($redirect, false, false, $rs, true) : '');
	$SHOW_LIST	= $navigation;

	//header
	echo $tpl->display("admin_header");
	//show tpl
	echo $tpl->display($style);
	//footer
	echo $tpl->display("admin_footer");
		
	if($exit)
	{
		$SQL->close();
		exit();
	}
}


/**
* Print inforamtion message on admin panel
*
* @adm
*/
function kleeja_admin_info($msg, $navigation=true, $title='', $exit=true, $redirect = false, $rs = 2)
{
	($hook = kleeja_run_hook('kleeja_admin_info_func')) ? eval($hook) : null; //run hook

	return kleeja_admin_err($msg, $navigation, $title, $exit, $redirect, $rs, 'admin_info');
}

/**
* generate a filter..
* @adm
*/
function insert_filter($type, $value, $time = false, $user = false, $status = '')
{
	global $SQL, $dbprefix, $userinfo;

	$user = !$user ? $userinfo['id'] : $user;
	$time = !$time ? time() : $time;

	$insert_query	= array(
							'INSERT'	=> '`filter_uid`, `filter_type` ,`filter_value` ,`filter_time` ,`filter_user`, `filter_status`',
							'INTO'		=> "{$dbprefix}filters",
							'VALUES'	=> "'" . uniqid() . "', '" . $SQL->escape($type) . "','" . $SQL->escape($value) . "', " . intval($time) . "," . intval($user) . ",'" . $SQL->escape($status) . "'"
						);
	($hook = kleeja_run_hook('insert_sql_insert_filter_func')) ? eval($hook) : null; //run hook

	$SQL->build($insert_query);

	return $SQL->insert_id();

	return false;
}

/**
* get filter from db..
* @adm
*/
function get_filter($item, $get_by = 'filter_id')
{
	global $dbprefix, $SQL;

	$query = array(
					'SELECT'	=> 'f.*',
					'FROM'		=> "{$dbprefix}filters f",
					'WHERE'		=> "f." . $get_by . " = " . ($get_by == 'filter_id' ? intval($item) : "'" . $SQL->escape($item) . "'")
				);

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);

	($hook = kleeja_run_hook('get_filter_func')) ? eval($hook) : null; //run hook
	return $v;
}


/**
* costruct a query for the searches..
* @adm
*/
function build_search_query($search)
{

	if(!is_array($search))
	{
		return '';
	}

	global $SQL;

	$search['filename'] = !isset($search['filename']) ? '' : $search['filename']; 
	$search['username'] = !isset($search['username']) ? '' : $search['username'];
	$search['than']		= !isset($search['than']) ? 1 : $search['than'];
	$search['size']		= !isset($search['size']) ? '' : $search['size'];
	$search['ups']		= !isset($search['ups']) ? '' : $search['ups'];
	$search['uthan']	= !isset($search['uthan']) ? 1 : $search['uthan'];
	$search['rep']		= !isset($search['rep']) ? '' : $search['rep'];
	$search['rthan']	= !isset($search['rthan']) ? 1 : $search['rthan'];
	$search['lastdown'] = !isset($search['lastdown']) ? '' : $search['lastdown'];
	$search['ext']		= !isset($search['ext']) ? '' : $search['ext'];
	$search['user_ip']	= !isset($search['user_ip']) ? '' : $search['user_ip'];

	$file_namee	= $search['filename'] != '' ? 'AND f.real_filename LIKE \'%' . $SQL->escape($search['filename']) . '%\' ' : ''; 
	$usernamee	= $search['username'] != '' ? 'AND u.name LIKE \'%' . $SQL->escape($search['username']) . '%\'' : ''; 
	$size_than	= ' f.size ' . ($search['than']!=1 ? '<=' : '>=') . (intval($search['size']) * 1024) . ' ';
	$ups_than	= $search['ups'] != '' ? 'AND f.uploads ' . ($search['uthan']!=1 ? '<' : '>') . intval($search['ups']) . ' ' : '';
	$rep_than	= $search['rep'] != '' ? 'AND f.report ' . ($search['rthan']!=1 ? '<' : '>') . intval($search['rep']) . ' ' : '';
	$lstd_than	= $search['lastdown'] != '' ? 'AND f.last_down =' . (time()-(intval($search['lastdown']) * (24 * 60 * 60))) . ' ' : '';
	$exte		= $search['ext'] != '' ? "AND f.type IN ('" . implode("', '", @explode(",", $SQL->escape($search['ext']))) . "')" : '';
	$ipp		= $search['user_ip'] != '' ? 'AND f.user_ip LIKE \'%' . $SQL->escape($search['user_ip']) . '%\' ' : '';

	return "$size_than $file_namee $ups_than $exte $rep_than $usernamee $lstd_than $exte $ipp";
}

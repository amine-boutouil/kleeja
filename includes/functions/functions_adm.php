<?php
/**
*
* @package adm
* @version $Id: functions.php 1910 2012-08-28 01:50:53Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


/**
 * @ignore
 */
if (!defined('IN_COMMON'))
{
	exit();
}



/**
 * Print admin area errors
 *
 * @param string $msg The message of error
 * @param bool $navigation [optional] Show the side mneu or not
 * @param string $title [optional] The title of the error
 * @param bool $exit [optional] halt after showing the error
 * @param bool|string $redirect [optional] if link given it will redirected to it after $rs seconds
 * @param int $rs [optional] if $redirected is given and not false, this will be the time in seconds
 * @param string $style [optional] this is just here to use it inside kleeja_admin_info to use admin_info
 */
function kleeja_admin_err($msg, $navigation = true, $title='', $exit = true, $redirect = false, $rs = 5, $style = 'admin_err')
{
	global $text, $tpl, $SHOW_LIST, $adm_extensions, $adm_extensions_menu;
	global $STYLE_PATH_ADMIN, $lang, $olang, $SQL, $MINI_MENU;

	($hook = kleeja_run_hook('kleeja_admin_err_func')) ? eval($hook) : null; //run hook

	#Exception for ajax
	if(isset($_GET['_ajax_']))
	{
		$text = $msg  . ($redirect ?  "\n" . '<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . str_replace('&amp;', '&', $redirect) . '\');", ' . ($rs*1000) . ');</script>' : '');
		echo_ajax(1, $tpl->display($style));
		$SQL->close();
		exit();
	}

	#assign {text} in err template
	$text		= $msg . ($redirect != false ? redirect($redirect, false, false, $rs, true) : '');
	$SHOW_LIST	= $navigation;

	#header
	echo $tpl->display("admin_header");
	#show tpl
	echo $tpl->display($style);
	#footer
	echo $tpl->display("admin_footer");

	#if exit, clean it
	if($exit)
	{
		garbage_collection();
		exit();
	}
}


/**
 * Print admin area inforamtion messages
 *
 * @param string $msg The message of information
 * @param bool $navigation [optional] Show the side mneu or not
 * @param string $title [optional] The title of the message
 * @param bool $exit [optional] halt after showing the message
 * @param bool|string $redirect [optional] if link given it will redirected to it after $rs seconds
 * @param int $rs [optional] if $redirected is given and not false, this will be the time in seconds
 */
function kleeja_admin_info($msg, $navigation=true, $title='', $exit=true, $redirect = false, $rs = 2)
{
	($hook = kleeja_run_hook('kleeja_admin_info_func')) ? eval($hook) : null; //run hook

	#since info message and error message are the same, we use one function callback
	return kleeja_admin_err($msg, $navigation, $title, $exit, $redirect, $rs, 'admin_info');
}


/**
 * Generate a filter, filiter is a value stored in the database to use it later
 * 
 * @param string $type Unique name to connect multiple filters together if you want
 * @param string $value The stored value
 * @param bool|int $time [optional] timestamp if this filter depends on time  or leave it
 * @param bool|int $user [optional] user ID if this filter depends on user or leave it
 * @param string $status [optional] if this filter has status, then fill it or leave it
 * @param bool|string $uid [optional] filter uid of your choice or leave it for random one
 * @return int
 */
function insert_filter($type, $value, $time = false, $user = false, $status = '', $uid = false)
{
	global $SQL, $dbprefix, $userinfo;

	$user = !$user ? $userinfo['id'] : $user;
	$time = !$time ? time() : $time;

	$insert_query	= array(
							'INSERT'	=> 'filter_uid, filter_type ,filter_value ,filter_time ,filter_user, filter_status',
							'INTO'		=> "{$dbprefix}filters",
							'VALUES'	=> "'" . ($uid ? $uid : uniqid()) . "', '" . $SQL->escape($type) . "','" . $SQL->escape($value) . "', " . intval($time) . "," . intval($user) . ",'" . $SQL->escape($status) . "'"
						);
	($hook = kleeja_run_hook('insert_sql_insert_filter_func')) ? eval($hook) : null; //run hook

	$SQL->build($insert_query);

	return $SQL->insert_id();
}



/**
 * Update filter value..
 * 
 * @param int|string $id_or_uid Number of filter_id or the unique id string of filter_uid
 * @param string $value The modified value of filter
 * @return bool
 */
function update_filter($id_or_uid, $value)
{
	global $SQL, $dbprefix;


	$update_query	= array(
							'UPDATE'	=> "{$dbprefix}filters",
							'SET'		=> "filter_value='" . $SQL->escape($value) . "'",
							'WHERE'		=> strval(intval($id_or_uid)) == strval($id_or_uid) ? 'filter_id=' . intval($id_or_uid) : "filter_uid='" . $SQL->escape($id_or_uid) . "'"
					);

	($hook = kleeja_run_hook('update_filter_func')) ? eval($hook) : null; //run hook

	$SQL->build($update_query);
	if($SQL->affected())
	{
		return true;
	}

	return false;
}

/**
 * Get filter from db..
 * 
 * @param string|int $item The value of $get_by, to get the filter depend on it
 * @param string $get_by The name of filter column we want to get the filter value from
 * @param bool $just_value If true the return value should be just filter_value otherwise all filter rows
 * @return mixed  
 */
function get_filter($item, $get_by = 'filter_id', $just_value = false)
{
	global $dbprefix, $SQL;

	$query = array(
					'SELECT'	=> $just_value ? 'f.filter_value' : 'f.*',
					'FROM'		=> "{$dbprefix}filters f",
					'WHERE'		=> "f." . $get_by . " = " . ($get_by == 'filter_id' ? intval($item) : "'" . $SQL->escape($item) . "'")
				);

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);

	($hook = kleeja_run_hook('get_filter_func')) ? eval($hook) : null; //run hook
	
	$SQL->free($result);
	if($just_value)
	{
		return $v['filter_value'];
	}

	return $v;
}

/**
 * check if filter exists or not
 *
 * @param string|int $item The value of $get_by, to find the filter depend on it
 * @param string $get_by The name of filter column we want to get the filter from
 * @return bool|int 
 */
function filter_exists($item, $get_by = 'filter_id')
{
	global $dbprefix, $SQL;

	$query = array(
					'SELECT'	=> 'f.filter_id',
					'FROM'		=> "{$dbprefix}filters f",
					'WHERE'		=> "f." . $get_by . " = " . ($get_by == 'filter_id' ? intval($item) : "'" . $SQL->escape($item) . "'")
				);

	($hook = kleeja_run_hook('filter_exists_func')) ? eval($hook) : null; //run hook

	$result	= $SQL->build($query);				
	return $SQL->num($result);
}


/**
 * Costruct a query for the file search
 *
 * @param array $search The Array of the search values
 * @return string
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
	$search['than']		= !isset($search['than']) ? '' : $search['than'];
	$search['size']		= !isset($search['size']) ? '' : $search['size'];
	$search['ups']		= !isset($search['ups']) ? '' : $search['ups'];
	$search['uthan']	= !isset($search['uthan']) ? '' : $search['uthan'];
	$search['rep']		= !isset($search['rep']) ? '' : $search['rep'];
	$search['rthan']	= !isset($search['rthan']) ? '' : $search['rthan'];
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

/**
 * To re-count the total files, without making the server goes down
 *
 * @param bool $files [optional] If true, function will just count files; false, just images
 * @param bool|int $start This value is used in couning in segments, in loop every refresh 
 * @return bool|int
 */
function sync_total_files($files = true, $start = false)
{
	global $SQL, $dbprefix;

	$query	= array(
				'SELECT'	=> 'MIN(f.id) as min_file_id, MAX(f.id) as max_file_id',
				'FROM'		=> "{$dbprefix}files f",
		);

	#!files == images
	$img_types = array('gif','jpg','png','bmp','jpeg','GIF','JPG','PNG','BMP','JPEG');
	$query['WHERE'] = "f.type" . ($files  ? ' NOT' : '') ." IN ('" . implode("', '", $img_types) . "')";

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);
	$SQL->free($result);
	
	#if no data, turn them to number
	$min_id = (int) $v['min_file_id'];
	$max_id = (int) $v['max_file_id'];

	#every time batch
	$batch_size = 1500;

	#no start? start = min
	$first_loop = !$start ? true : false;
	$start	= !$start ? $min_id : $start;
	$end	= $start + $batch_size;

	#now lets get this step's files number 
	unset($v, $result);

	$query['SELECT'] = 'COUNT(f.id) as num_files';
	$query['WHERE'] .= ' AND f.id BETWEEN ' . $start . ' AND ' . $end;

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);
	$SQL->free($result);

	$this_step_count = $v['num_files'];
	if($this_step_count == 0)
	{
		return false;
	}

	#update stats table

	$update_query = array(
							'UPDATE'	=> "{$dbprefix}stats"
							);

	#make it zero, firstly
	if($first_loop)
	{
		$update_query['SET'] = ($files ? 'files' : 'imgs') . "= 0"; 
		$SQL->build($update_query);
	}
	
	$update_query['SET'] = ($files ? 'files' : 'imgs') . "=" . ($files ? 'files' : 'imgs') . '+' . $this_step_count;
	$SQL->build($update_query);


	return $end;
}

/**
 * Get the *right* now number of the given stat from stats table
 *
 * @param string $name The name of stats you want get from the DB
 * @return string|int 
 */
function get_actual_stats($name)
{
	global $dbprefix, $SQL;

	$query = array(
					'SELECT'	=> 's.' . $name,
					'FROM'		=> "{$dbprefix}stats s"
			);

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);

	($hook = kleeja_run_hook('get_actual_stats_func')) ? eval($hook) : null; //run hook
	
	$SQL->free($result);

	return $v[$name];
}

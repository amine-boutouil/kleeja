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


#hook to use it for start page, also there is a hook at admin/index.php that you
#can use it with all includes/adm files, just hook your codes and gooo...
($hook = kleeja_run_hook('default_admin_page')) ? eval($hook) : null; //run hook 


//style of
$stylee			= "admin_start";
$h_lst_files	= basename(ADMIN_PATH) . '?cp=c_files&amp;last_visit=';
$h_lst_imgs		= basename(ADMIN_PATH) . '?cp=d_img_ctrl&amp;last_visit=';
$current_smt	= isset($_GET['smt']) ? (preg_match('![a-z0-9_]!i', trim($_GET['smt'])) ? trim($_GET['smt']) : 'general') : 'general';
$GET_FORM_KEY	= kleeja_add_form_key_get('adm_start_actions');

//data
$lst_reg			= empty($stat_last_user) ? $lang['UNKNOWN'] : $stat_last_user;
$files_number 		= $stat_files + $stat_imgs;
$files_sizes 		= Customfile_size($stat_sizes);
$users_number 		= $stat_users;
$last_del_fles 		= (int) $config['del_f_day'] <= 0 ? $lang['CLOSED_FEATURE'] : kleeja_date($stat_last_f_del);
$php_version 		= isset($NO_PHPINFO) || !function_exists('phpinfo') ? phpversion() : '<a href="' . basename(ADMIN_PATH) . '?cp=php_info" title="php_info" onclick="javascript:get_kleeja_link(\'' . basename(ADMIN_PATH) . '?cp=php_info\', \'#content\'); return false;">php ' . phpversion() . '</a>';
$mysql_version 		= 'MYSQL ' . $SQL->mysql_version();
$max_execution_time = function_exists('ini_get') ?  @ini_get('max_execution_time') : @get_cfg_var('max_execution_time');
$upload_max_filesize= function_exists('ini_get') ?  @ini_get('upload_max_filesize') : @get_cfg_var('upload_max_filesize');
$post_max_size 		= function_exists('ini_get') ?  @ini_get('post_max_size') : @get_cfg_var('post_max_size');
$memory_limit 		= function_exists('ini_get') ?  @ini_get('memory_limit') : @get_cfg_var('memory_limit');
$s_last_google		= ($stat_last_google == 0) ? '[ ? ]' : kleeja_date($stat_last_google);
$s_google_num		= $stat_google_num;
$s_last_bing		= ($stat_last_bing == 0) ? '[ ? ]' : kleeja_date($stat_last_bing);
$s_bing_num			= $stat_bing_num;
$usernamelang		= sprintf($lang['KLEEJA_CP_W'], $username);

//size board by percent
$per	= $stat_sizes / ($config['total_size'] * 1048576);
$per1	= round($per*100, 2);
$per1	= $per1 >= 100 ? 100 : $per1;

//ppl must know about kleeja version!
$kleeja_version	 = '<a href="' . basename(ADMIN_PATH) . '?cp=p_check_update" onclick="javascript:get_kleeja_link(this.href, \'#content\'); return false;" title="' . $lang['R_CHECK_UPDATE'] . '">' . KLEEJA_VERSION . '</a>';

//admin messages system
$ADM_NOTIFICATIONS = array();

//useing IE6 ! and he is admin ?  omg !
$u_agent = (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars((string) strtolower($_SERVER['HTTP_USER_AGENT'])) : (function_exists('getenv') ? getenv('HTTP_USER_AGENT') : '');
if(is_browser('ie6') && !is_browser('ie8, ie7'))
{
	$ADM_NOTIFICATIONS[]  = array('id' => 'IE6', 'msg_type'=> 'error', 'title'=> $lang['NOTE'], 'msg'=> $lang['ADMIN_USING_IE6']);
}

//if upgrading from 1rc6 to 1.0, some files must be deleted ! 
if(file_exists(PATH . 'includes/adm/files.php') || file_exists(PATH . 'admin.php'))
{
	$ADM_NOTIFICATIONS[]  = array('id' => 'old_files', 'msg_type'=> 'info', 'title'=> $lang['NOTE'], 'msg'=> $lang['ADM_UNWANTED_FILES']);
}

//if html url is enabled but .htaccess is not available in the root dir !
if(!file_exists(PATH . '.htaccess') && (int) $config['mod_writer'] == 1)
{
	$ADM_NOTIFICATIONS[]  = array('id' => 'htmlurlshtaccess', 'msg_type'=> 'info', 'title'=> $lang['NOTE'], 'msg'=> $lang['HTML_URLS_ENABLED_NO_HTCC']);
}

//updating
$v = @unserialize($config['new_version']);
if(version_compare(strtolower(KLEEJA_VERSION), strtolower($v['version_number']), '<'))
{
	$ADM_NOTIFICATIONS[]  = array(
									'id' => 'up_ver_klj',//this not so important row 
									'msg_type'=> 'error', 'title'=> $lang['R_CHECK_UPDATE'], 
									'msg'=> sprintf($lang['UPDATE_NOW_S'] , KLEEJA_VERSION, $v['version_number']) . '<br />' . '<a href="http://www.kleeja.com/">www.kleeja.com</a>'
							);

	($hook = kleeja_run_hook('admin_update_now')) ? eval($hook) : null; //run hook 
}


//check upload_max_filesize
if(isset($u_exts)  && isset($g_exts) && is_array($u_exts) && !is_array($g_exts))
{
	$u_e_s = array_values($u_exts);
	$g_e_s = array_values($g_exts);
	asort($u_e_s);
	asort($g_e_s);
	if(strpos($upload_max_filesize, 'M') !== false)
	{
		$upload_max_filesize_s = ((int) trim(str_replace('M', '', $upload_max_filesize))) * 1048576;
	}
	else if(strpos($upload_max_filesize, 'G') !== false)
	{
		$upload_max_filesize_s = ((int) trim(str_replace('G', '', $upload_max_filesize))) * 1073741824;
	}

	$big_size_is = isset($u_e_s[0]['size']) && ($u_e_s[0]['size'] > $g_e_s[0]['size']) ? $u_e_s[0]['size'] : (isset($g_e_s[0]['size']) ? $g_e_s[0]['size'] : 0);
	if(!empty($upload_max_filesize) && $upload_max_filesize_s < $big_size_is)
	{
		$ADM_NOTIFICATIONS[]  = array(
										'id' => 'file_size_ini_low',
										'msg_type'=> 'info', 'title'=> $lang['NOTE'], 
										'msg'=> sprintf($lang['PHPINI_FILESIZE_SMALL'] , Customfile_size($big_size_is), Customfile_size($upload_max_filesize_s))
									);
	}

	//check post_max_size
	if(strpos($post_max_size, 'M') !== false)
	{
		$post_max_size_s = ((int) trim(str_replace('M', '', $post_max_size))) * 1048576;
	}
	else if(strpos($post_max_size, 'G') !== false)
	{
		$post_max_size_s = ((int) trim(str_replace('G', '', $post_max_size))) * 1073741824;
	}

	$post_max_size_s_must_be = ($config['filesnum'] * $big_size_is) + 5242880;//+ 5 mega to make sure it's ok

	if(!empty($post_max_size) && $post_max_size_s < $post_max_size_s_must_be)
	{
				$ADM_NOTIFICATIONS[]  = array(
										'id' => 'post_m_size_ini_low',
										'msg_type'=> 'info', 'title'=> $lang['NOTE'], 
										'msg'=> sprintf($lang['PHPINI_MPOSTSIZE_SMALL'] , $config['filesnum'], Customfile_size($post_max_size_s_must_be))
										);		
	}
}

//
//if 24 hours, lets chcek agian !
//rev: let's say cache is not refreshed, so we will redirect alots of time,
// so update_done will be good solution
//
if(empty($v['last_check']) || ((time() - $v['last_check']) > 86400 && !$v['msg_appeared'] && $_SERVER['SERVER_NAME'] != 'localhost' && !isset($_GET['update_done'])))
{
	redirect(basename(ADMIN_PATH) . '?cp=p_check_update&amp;show_msg=1');
	$SQL->close();
	exit;
}


//cached templates
$there_is_cached = false;
$cached_file = PATH . 'cache/styles_cached.php';
if(file_exists($cached_file))
{
	$ADM_NOTIFICATIONS[]  = array(
								'id' => 'cached_tpl',
								'msg_type'=> 'info', 'title'=> $lang['CACHED_STYLES'],
								'msg'=> sprintf($lang['CACHED_STYLES_DISC'] , '<a href="' . basename(ADMIN_PATH) . '?cp=m_styles&amp;sty_t=cached" onclick="javascript:get_kleeja_link(this.href, \'#content\'); return false;">' . $lang['CLICKHERE'] .'</a>')
							);
}

//if config not safe
if(function_exists('fileperms') && !defined('KLEEJA_NO_CONFIG_CHECK') && strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN' && !@ini_get('safe_mode'))
{
	if((bool) (@fileperms(PATH . KLEEJA_CONFIG_FILE) & 0x0002))
	{
		$ADM_NOTIFICATIONS[]  = array('id' => 'config_perm', 'msg_type'=> 'info', 'title'=> $lang['NOTE'], 'msg'=> $lang['CONFIG_WRITEABLE']);
	}
}
//no htaccess
if(!file_exists(PATH . $config['foldername'] . '/.htaccess'))
{
	$ADM_NOTIFICATIONS[]  = array('id' => 'htaccess_u', 'msg_type'=> 'error', 'title'=> $lang['WARN'], 'msg'=> sprintf($lang['NO_HTACCESS_DIR_UP'], $config['foldername']));
}
if(!file_exists(PATH . $config['foldername'] . '/thumbs/.htaccess'))
{
	$ADM_NOTIFICATIONS[]  = array('id' => 'htaccess_t', 'msg_type'=> 'error', 'title'=> $lang['WARN'], 'msg'=> sprintf($lang['NO_HTACCESS_DIR_UP_THUMB'], $config['foldername'] . '/thumbs'));
}


//check is there any copyright on footer.html if not show pretty msg with peace
/*
if(file_exists($STYLE_PATH . 'footer.html'))
{
	$t_data = file_get_contents($STYLE_PATH . 'footer.html');

	if(strpos($t_data, 'kleeja.com') === false)
	{
		//not guilty or not guilty! we love who use kleeja even witout copyrights 
		//but we are human being, so we need some money to live as a normal people 
		if($v['copyrights'] == false)
		{
			//it's not so usefull ...
			//$copy_msg = sprintf($lang['NO_KLEEJA_COPYRIGHTS'], '<a href="http://www.kleeja.com/buy/">' . $lang['CLICKHERE'] .'</a>');
			//$ADM_NOTIFICATIONS[]  = array('id' => 'copyrights_t', 'msg_type'=> 'error', 'title'=> $lang['NOTE'], 'msg'=> $copy_msg);
		}
	}
}
*/
//there is cleaning files process now
if((int)$config['klj_clean_files_from'] > 0)
{
	$ADM_NOTIFICATIONS[]  = array('id' => 'klj_clean_files', 'msg_type'=> 'info', 'title'=> $lang['NOTE'], 'msg'=> $lang['T_CLEANING_FILES_NOW']);
}

//if there is no thumbs folder
if(!file_exists(PATH . $config['foldername'] . '/thumbs') && (int) $config['thumbs_imgs'] != 0)
{
	$ADM_NOTIFICATIONS[]  = array('id' => 'no_thumbs', 'msg_type'=> 'info', 'title'=> $lang['NOTE'], 'msg'=> sprintf($lang['NO_THUMB_FOLDER'], PATH . $config['foldername'] . '/thumbs'));
}


//if dev stage
$sql_debug = false;
if(defined('DEV_STAGE'))
{
	$sql_debug_c = '';
	if(file_exists(PATH . 'cache/kleeja_log.log'))
	{
		$sql_debug_c = file_get_contents(PATH . 'cache/kleeja_log.log');
	}

	preg_match_all("/\[([^\]]+)\]([^\[]+)\[time : ([^\]]+)\]/", $sql_debug_c, $matches, PREG_SET_ORDER);

	$sql_debug = array();
	$r = 0;
	$color1 = $c = 'green';
	$color2 = 'blue';
	foreach ($matches as $v)
	{
		$c = $v[1] == 'Closing connection' ? ($c == $color1 ? $color2 : $color1) : $c;
		$r++;
		$sql_debug[] = array ('type' => $v[1], 'content' => $v[2], 'time' => $v[3], 'colored' => $c);
		if($r > 50 && $v[1] == 'Connected')
		{
			break;
		}
	}
	unset($sql_debug_c);
}


//is there copyrights for translator ? 
$translator_copyrights = isset($lang['S_TRANSLATED_BY']) ?  $lang['S_TRANSLATED_BY'] : false;


//secondary menu
$go_menu = array(
				'general' => array('name'=>$lang['GENERAL_STAT'], 'link'=> basename(ADMIN_PATH) . '?cp=start&amp;smt=general', 'goto'=>'general', 'current'=> $current_smt == 'general'),
				'other' => array('name'=>$lang['OTHER_INFO'], 'link'=> basename(ADMIN_PATH) . '?cp=start&amp;smt=other', 'goto'=>'other', 'current'=> $current_smt == 'other'),
				'team' => array('name'=>$lang['KLEEJA_TEAM'], 'link'=> basename(ADMIN_PATH) . '?cp=start&amp;smt=team', 'goto'=>'team', 'current'=> $current_smt == 'team'),
	);

if(!$config['firstime'])
{
	//$go_menu['firstime'] = array('name'=>$lang['FIRST_TIME_CP'], 'link'=> basename(ADMIN_PATH) . '?cp=start&amp;smt=firstime', 'goto'=>'firstime', 'current'=> $current_smt == 'firstime');
}

#if this is your first time, let make sure that it's the last
if($current_smt == 'firstime')
{
	update_config('firstime', '1');
}

#is there a last visit of images and files ?
$files_last_visit = filter_exists('f_lastvisit', 'filter_uid') ? get_filter('f_lastvisit', 'filter_uid', true) : false;
$image_last_visit = filter_exists('i_lastvisit', 'filter_uid') ? get_filter('i_lastvisit', 'filter_uid', true) : false;


#hurry, hurry section, get styles
$hurry_style_link	= basename(ADMIN_PATH) . '?cp=m_styles&amp;sty_t=st&amp;method=2&amp;home=1&amp;smt=curstyle&amp;' . $GET_FORM_KEY . '&amp;style_choose=';
$hurry_styles_list	= '';
if ($dh = @opendir(PATH . 'styles'))
{
	while (($file = @readdir($dh)) !== false)
	{
		if(strpos($file, '.') === false && $file != '..' && $file != '.')
		{
			$hurry_styles_list .= '<option value="' . htmlspecialchars($file) . '"' . ($config['style'] == $file ? ' selected="selected"' : '') . '>' . $file . '</option>';
		}
	}
	@closedir($dh);
}

#hurry, hurry section, get languages
$hurry_lang_link	= basename(ADMIN_PATH) . '?cp=g_users&smt=general&amp;smt=group_data&' . $GET_FORM_KEY . '&amp;lang_change=';
$hurry_langs_list	= '';
if ($dh = @opendir(PATH . 'lang'))
{
	while (($file = @readdir($dh)) !== false)
	{
		if(strpos($file, '.') === false && $file != '..' && $file != '.')
		{
			$hurry_langs_list .= '<option value="' . htmlspecialchars($file) . '"' . ($d_groups[$config['default_group']]['configs']['language'] == $file ? ' selected="selected"' : '') . '>' . $file . '</option>';
		}
	}
	@closedir($dh);
}

$hurry_groups_list = '<option value="' . $config['default_group'] . '" selected="selected">' . $lang['DEFAULT_GROUP'] . '</option>';
$hurry_groups_list .= '<option value="-1">' . $lang['ALL'] . '</option>';
foreach($d_groups as $id=>$ddt)
{
	$hurry_groups_list .= '<option value="' . $id . '">' . preg_replace('!{lang.([A-Z0-9]+)}!e', '$lang[\'\\1\']', $d_groups[$id]['data']['group_name']) . '</option>';
}

#hurry, hurry section, links
$del_cache_link		= basename(ADMIN_PATH) . '?cp=r_repair&amp;case=clearc&amp;' . kleeja_add_form_key_get('REPAIR_FORM_KEY');


# get stats filter so we can draw a chart for the user
$stats_chart = false;

$cf_query = array(
					'SELECT'	=> 'f.filter_uid, f.filter_value, f.filter_time',
					'FROM'		=> "{$dbprefix}filters f",
					'WHERE'		=> "f.filter_type = 'stats_for_acp'",
					'ORDER BY'	=> 'f.filter_time DESC',
				);

$cf_result	= $SQL->build($cf_query);
$cf_num	= $SQL->num_rows($cf_result);
if($cf_num > 4)
{
	$stats_chart = 'arrayOfDataMulti = new Array(';
	
	$comma = false;
	#get currently right now stats
	$prv_files = get_actual_stats('files');
	$prev_imgs = get_actual_stats('imgs');
	$prev_date = date('d-n-Y');	
	while($row=$SQL->fetch_array($cf_result))
	{
		#jump today
		if($prev_date == $row['filter_uid'])
		{
			continue;
		}

		#get this row data
		list($s_files, $s_imgs, $s_sizes) = explode(':', $row['filter_value']);
	
		$t_files = $prv_files - $s_files;
		$t_imgs = $prev_imgs - $s_imgs;

		$day = date('d-n-Y') == $prev_date ? $lang['TODAY'] . ' ~ ' . $lang['NOW'] : $prev_date;

		$stats_chart .= ($comma ? ',': '') . "[[$t_files,$t_imgs],'" . ($cf_num > 6 ? str_replace(date('-Y'), '', $day) : $day) . "']";

		$comma = true;
		$prv_files = $s_files;
		$prev_imgs = $s_imgs;
		$prev_date = $row['filter_uid'];
	}

	$stats_chart .= ');';

	$SQL->freeresult($cf_result);

	#clean old chart stats
	if($cf_num > 10)
	{
		$query_del	= array(
								'DELETE'	=> "{$dbprefix}filters",
								'WHERE'		=> "filter_type = 'stats_for_acp' AND filter_time < " . (time() - (3600 * 24 * 10))
							);
		 $SQL->build($query_del);
	}
}

<?php
/**
*
* @package adm
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


/**
 * @ignore
 */
define('PATH' , '../');
define('IN_KLEEJA' , true);
define('IN_ADMIN' , true);
#session params
$s_path = preg_replace('/.*?[\\\\|\/]([0-9a-z-_.]+)[\\\\|\/]([0-9a-z-_.]+)[\\\\|\/]' . preg_quote(basename(__file__), '/') . '/i', '/\\1/\\2/', __file__);
$s_time = 18000;
#include core
require PATH . 'includes/common.php';


($hook = kleeja_run_hook('begin_admin_page')) ? eval($hook) : null; //run hook 

#current page
$go_to = g('cp', 'string', 'start');

#for security, if not a user, redirect to login page
if (!$user->is_user())
{
	($hook = kleeja_run_hook('user_not_admin_admin_page')) ? eval($hook) : null; //run hook 
	redirect($config['siteurl'] . 'ucp.php?go=login&return=' . urlencode(ADMIN_PATH . '?cp=' . $go_to));
}

#get languge of admin
get_lang('acp');


#need to login again
if(
	(empty($_SESSION['ADMINLOGIN']) || $_SESSION['ADMINLOGIN'] != md5(sha1($config['h_key']) . $user->data['name'] . $config['siteurl'] . (!empty($_SERVER['REMOTE_ADDR'])) ? (string) $_SERVER['REMOTE_ADDR'] : '')) || 
	(empty($_SESSION['USER_SESS']) || $_SESSION['USER_SESS'] != session_id()) ||
	(empty($_SESSION['ADMINLOGIN_T']) || $_SESSION['ADMINLOGIN_T'] < time())	 
)
{
	if(g('go') == 'login' && ip('submit')) 
	{
		$ERRORS	= array();
		$pass_field = 'lpass_' . preg_replace('/[^0-9]/', '', sha1(session_id() . sha1($config['h_key']) . p('kid')));

		if(p('lname') == '' || p($pass_field) == '')
		{
			$ERRORS[] = $lang['EMPTY_FIELDS'];
		}
		elseif(!user_can('enter_acp'))
		{
			$ERRORS[] = $lang['U_NOT_ADMIN'];
		}
		elseif(!kleeja_check_form_key('admin_login'))
		{
			$ERRORS[] = $lang['INVALID_FORM_KEY'];
		}

		if(!sizeof($ERRORS))
		{
			if($f = $user->login(p('lname'), p($pass_field), false, $s_time, true))
			{
				$_SESSION['USER_SESS'] = session_id();
				$_SESSION['ADMINLOGIN'] = md5(sha1($config['h_key']) . p('lname') . $config['siteurl'] . (!empty($_SERVER['REMOTE_ADDR'])) ? (string) $_SERVER['REMOTE_ADDR'] : '');
				//to make sure, sometime setting time from fucntions doesnt work
				$_SESSION['ADMINLOGIN_T'] = time() + $s_time;
				redirect(ADMIN_PATH . '?cp=' . $go_to);
				$SQL->close();
				exit;
			}
			else
			{
				# wrong entries
				$ERRORS[] = $lang['LOGIN_ERROR'];
			}
		}
	}

	#show template login .
	$action	= ADMIN_PATH . '?go=login&amp;cp=' . $go_to;
	$KEY_FOR_WEE	= sha1(microtime() . sha1($config['h_key']));
	$KEY_FOR_PASS	= preg_replace('/[^0-9]/', '', sha1(session_id() . sha1($config['h_key']) . $KEY_FOR_WEE)); 
	$not_you		= sprintf($lang['USERNAME_NOT_YOU'], '<a href="' . $config['siteurl'] . 'ucp.php?go=logout">', '</a>');

	#prevent indexing this page by bots
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	#index template
	include get_template_path('login.php');
	garbage_collection();
	exit;
}#end login


//ummm let's say it's illegal action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && defined('STOP_CSRF'))
{
	$t_reff = explode('/', $_SERVER['HTTP_REFERER']);
	$t_host = explode('/', $_SERVER['HTTP_HOST']);
	if ($t_reff[2] != $t_host[0])
	{
		$usrcp->logout_cp();
		redirect($config['siteurl']);
		$SQL->close();
		exit;
	}
}


(!defined('LAST_VISIT')) ? define('LAST_VISIT', time() - 3600*12) : '';
//last visit
$last_visit	= defined('LAST_VISIT') && preg_match('/[0-9]{10}/', LAST_VISIT) ? kleeja_date(LAST_VISIT) : false;

#path of admin extensions
$path_adm = PATH . 'includes/adm';

#what pages need a confirm msg
$ext_confirm = array();
#$ext_confirm[]	= 'repair';	

#formkey extension, Csrf protection
$GET_FORM_KEY_GLOBAL = kleeja_add_form_key_get('GLOBAL_FORM_KEY');
$ext_formkey = array();
//$ext_formkey[] = 'repair';


#We hide list of admin menu and show only if there is auth.
$SHOW_LIST = true;

#admin categories with order
$adm_extensions = array(
	#name of file without .php => path of file's folder
	'options' => $path_adm,
	'files' => $path_adm,
	'images' => $path_adm,
	'calls' => $path_adm,
	'reports' => $path_adm,
	'search' => $path_adm,
	'users' => $path_adm,
	'ban' => $path_adm,
	'rules' => $path_adm,
	'extra' => $path_adm,
	'check_update' => $path_adm,
	'maintenance' => $path_adm,
	'start' => $path_adm,
);


($hook = kleeja_run_hook('adm_extensions_admin_page')) ? eval($hook) : null; //run hook 


#no requst or wrong !
if(!$go_to || empty($go_to) || !array_key_exists($go_to, $adm_extensions))
{
	$go_to = 'start';
}

//make array for menu 
$adm_extensions_menu = array();

$i = 0;
$cr_time = LAST_VISIT > 0 ? LAST_VISIT : time() - 3600*12;


#check calls and reports numbers
if(ig('check_msgs') || !ig('_ajax_')):

// Small bubble system 
// any item can show what is inside it as unread messages
$kbubbles = array();

#for calls and reports
foreach(array('call'=>'calls', 'reports'=>'reports') as $table=>$n)
{
	$query	= array(
					'SELECT'	=> 'COUNT(' . $table[0] . '.id) AS total_rows',
					'FROM'		=> "`{$dbprefix}" . $table . "` " . $table[0]
				);

	$fetched = $SQL->fetch($SQL->build($query));

	$kbubbles[$n] = $fetched['total_rows'];

	$SQL->free();
}

#if ajax, echo differntly
if(ig('check_msgs'))
{
	$SQL->close();
	exit($kbubbles['calls'] . '::' . $kbubbles['reports']);
}

#add your own bubbles here
($hook = kleeja_run_hook('kbubbles_admin_page')) ? eval($hook) : null; //run hook 

endif;


foreach($adm_extensions as $m=>$folder_path)
{

	($hook = kleeja_run_hook('foreach_ext_admin_page')) ? eval($hook) : null; //run hook 
	
	if($m == 'start')
	{
		continue;
	}

	++$i;
	$adm_extensions_menu[$i]	= array(
										'i'			=> $i+1,
										'i2'		=> $i+2,
										'icon'		=> (file_exists(ADMIN_STYLE_PATH_ABS . 'images/menu/' . $m . '_button.png'))	? ADMIN_STYLE_PATH . 'images/menu/' . $m . '_button.png' : ADMIN_STYLE_PATH . 'images/menu/no_icon.png',

										'title'		=> !empty($lang['R_'. strtoupper($m)]) ? $lang['R_'. strtoupper($m)] : (!empty($olang['R_' . strtoupper($m)]) ? $olang['R_' . strtoupper($m)] : strtoupper($m)),
										'link'		=> ADMIN_PATH . '?cp=' . $m . (@in_array($m, $ext_formkey) ? '&amp;' . $GET_FORM_KEY_GLOBAL : ''),
										'confirm'	=> (@in_array($m, $ext_confirm)) ? true : false,
										'current'	=> ($m == $go_to) ? true : false,
										'goto'		=> $m,
										'kbubble'	=> in_array($m, array_keys($kbubbles)) ? '<span class="badge pull-' . ($lang['DIR'] == 'rtl'?'left':'right') . '" id="t_' . $m . '"' . ($kbubbles[$m] == 0 ? ' style="display:none"' : '') . '>' . $kbubbles[$m] . '</span>' : ''
									);
	
	($hook = kleeja_run_hook('endforeach_ext_admin_page')) ? eval($hook) : null; //run hook 
}


#to attach kleeja version in the menu start item
$assigned_klj_ver = preg_replace('!#([a-z0-9]+)!', '', KLEEJA_VERSION);


if (file_exists($adm_extensions[$go_to] . '/' . $go_to . '.php'))
{
	($hook = kleeja_run_hook("require_admin_page_begin_{$go_to}")) ? eval($hook) : null; //run hook 
	include $adm_extensions[$go_to] . '/' . $go_to . '.php';
	($hook = kleeja_run_hook("require_admin_page_end_{$go_to}")) ? eval($hook) : null; //run hook 
}
else
{
	big_error('Loading !', 'Error while loading: ' . $adm_extensions[$go_to] . '/' . $go_to);
}

($hook = kleeja_run_hook('end_admin_page')) ? eval($hook) : null; //run hook 


#no style defined
if(empty($current_template))
{
	$text = $lang['NO_TPL'];
	$current_template = 'info.php';
}

$go_menu_html = '';
if(isset($go_menu))
{
	foreach($go_menu as $m=>$d)
	{
		$go_menu_html .= '<li class="' . ($d['current']?'active':'') .'" id="c_' . $d['goto'] . '"><a href="' . $d['link'] . '" onclick="' . (isset($d['confirm']) && $d['confirm'] ? 'javascript:return confirm_from();' : '') . '">' . $d['name'] . '</a></li>';
	}
}

#header
if(!ig('_ajax_'))
{
	include get_template_path('header.php');
}


#body
if(!ig('_ajax_'))
{
	$is_ajax = 'no';
	include get_template_path($current_template);
}

#footer
if(!ig('_ajax_'))
{
	include get_template_path('footer.php');
}

# at end
garbage_collection();
exit;

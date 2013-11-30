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
	(empty($_SESSION['ADMINLOGIN']) || $_SESSION['ADMINLOGIN'] != md5(sha1($config['h_key']) . $user->data['name'] . $config['siteurl'])) || 
	(empty($_SESSION['USER_SESS']) || $_SESSION['USER_SESS'] != session_id()) ||
	(empty($_SESSION['ADMINLOGIN_T']) || $_SESSION['ADMINLOGIN_T'] < time())	 
)
{
	if(g('go') == 'login') 
	{
		if(ip('submit'))
		{
			$ERRORS	= array();
			$pass_field = 'lpass_' . preg_replace('/[^0-9]/', '', sha1(session_id() . sha1($config['h_key']) . $_POST['kid']));

			if(empty($_POST['lname']) || empty($_POST[$pass_field]))
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
				if($f = $usrcp->data($_POST['lname'], $_POST[$pass_field], false, $adm_time, true))
				{
					$_SESSION['USER_SESS'] = session_id();
					$_SESSION['ADMINLOGIN'] = md5(sha1($config['h_key']) . $usrcp->name() . $config['siteurl']);
					//to make sure, sometime setting time from fucntions doesnt work
					$_SESSION['ADMINLOGIN_T'] = time() + $adm_time;
					redirect('./' . basename(ADMIN_PATH) . '?cp=' . $go_to);
					$SQL->close();
					exit;
				}
				else
				{
					//Wrong entries
					$ERRORS[] = $lang['LOGIN_ERROR'];
				}
			}

			//let's see if there is errors
			if(sizeof($ERRORS))
			{
				$errs =	'';
				foreach($ERRORS as $r)
				{
					$errs .= '- ' . $r . '. <br />';
				}
			}
		}
	}

	//show template login .
	$action	= './' . basename(ADMIN_PATH) . '?go=login&amp;cp=' . $go_to;
	$H_FORM_KEYS	= kleeja_add_form_key('admin_login');
	$KEY_FOR_WEE	= sha1(microtime() . sha1($config['h_key']));
	$KEY_FOR_PASS	= preg_replace('/[^0-9]/', '', sha1(session_id() . sha1($config['h_key']) . $KEY_FOR_WEE)); 
	$not_you		= sprintf($lang['USERNAME_NOT_YOU'], '<a href="' . $config['siteurl'] . 'ucp.php?go=logout">', '</a>');
	$err = false;
	if(!empty($errs))
	{
		$err = true;
	}


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
$path_adm	= PATH . 'includes/adm';

#what pages need a confirm msg
$ext_confirm	= array();
#$ext_confirm[]	= 'repair';	

#formkey extension, Csrf protection
$GET_FORM_KEY_GLOBAL = kleeja_add_form_key_get('GLOBAL_FORM_KEY');
$ext_formkey = array();
//$ext_formkey[] = 'repair';


#We hide list of admin menu and show only if there is auth.
$SHOW_LIST = true;

#admin categories with order
$adm_extensions = array(
	1 => 'options',
	2 => 'files',
	3 => 'images',
	4 => 'calls',
	5 => 'reports',
	6 => 'search',
	7 => 'users',
	8 => 'ban',
	9 => 'rules',
	10 => 'extra',
	11 => 'check_update',
	12 => 'maintenance',
);


($hook = kleeja_run_hook('adm_extensions_admin_page')) ? eval($hook) : null; //run hook 


#no requst or wrong !
if(!$go_to || empty($go_to) ||  !in_array($go_to, $adm_extensions))
{
	$go_to = 'start';
}

//make array for menu 
$adm_extensions_menu =	$adm_topmenu = array();

#$top_menu_items = array('c_files', 'd_img_ctrl', 'h_search', 'i_exts')
$i = 0;
$cr_time = LAST_VISIT > 0 ? LAST_VISIT : time() - 3600*12;


// check calls and reports numbers
if(isset($_GET['check_msgs']) || !isset($_GET['_ajax_'])):

//small bubble system 
//any item can show what is inside it as unread messages
$kbubbles = array();

//for calls and reports
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
if(isset($_GET['check_msgs']))
{
	$SQL->close();
	exit($kbubbles['calls'] . '::' . $kbubbles['reports']);
}

//add your own bubbles here
($hook = kleeja_run_hook('kbubbles_admin_page')) ? eval($hook) : null; //run hook 

endif;

foreach($adm_extensions as $m)
{

	($hook = kleeja_run_hook('foreach_ext_admin_page')) ? eval($hook) : null; //run hook 

	$s = $m;
	$m = isset($m[1]) && $m[1] == '_' ? substr($m , 2) : $m;

	++$i;
	$adm_extensions_menu[$i]	= array(
										'i'			=> $i+1,
										'i2'		=> $i+2,
										'icon'		=> (file_exists($STYLE_PATH_ADMIN_ABS . 'images/menu/' . ($m == 'configs' ? 'options' : $m) . '_button.png'))	? $STYLE_PATH_ADMIN . 'images/menu/' . ($m == 'configs' ? 'options' : $m) . '_button.png' : $STYLE_PATH_ADMIN . 'images/menu/no_icon.png',

										'lang'		=> !empty($lang['R_'. strtoupper($m)]) ? $lang['R_'. strtoupper($m)] : (!empty($olang['R_' . strtoupper($m)]) ? $olang['R_' . strtoupper($m)] : strtoupper($m)),
										'link'		=> './' . basename(ADMIN_PATH) . '?cp=' . ($m == 'configs' ? 'options' : $s) . (@in_array($m, $ext_formkey) ? '&amp;' . $GET_FORM_KEY_GLOBAL : ''),
										'confirm'	=> (@in_array($m, $ext_confirm)) ? true : false,
										'current'	=> ($s == $go_to) ? true : false,
										'goto'		=> str_replace('a_configs', 'options', $s),
										'kbubble'	=> in_array($m, array_keys($kbubbles)) ? '<span class="badge pull-' . ($lang['DIR'] == 'rtl'?'left':'right') . '" id="t_' . $m . '"' . ($kbubbles[$m] == 0 ? ' style="display:none"' : '') . '>' . $kbubbles[$m] . '</span>' : ''
									);

	//add another item to array for title='' in href or other thing
	$adm_extensions_menu[$i]['title'] = $adm_extensions_menu[$i]['lang'];
	
	#if(@in_array($s, $top_menu_items))
	#{
	#	$adm_topmenu[$i] = $adm_extensions_menu[$i];
	#	unset($adm_extensions_menu[$i]);
	#}

	($hook = kleeja_run_hook('endforeach_ext_admin_page')) ? eval($hook) : null; //run hook 
}


#to attach kleeja version in the menu start item
$assigned_klj_ver = preg_replace('!#([a-z0-9]+)!', '', KLEEJA_VERSION);

//get it 
if (file_exists($path_adm . '/' . $go_to . '.php'))
{
	($hook = kleeja_run_hook("require_admin_page_begin_{$go_to}")) ? eval($hook) : null; //run hook 
	include_once ($path_adm . '/' . $go_to . '.php');
	($hook = kleeja_run_hook("require_admin_page_end_{$go_to}")) ? eval($hook) : null; //run hook 
}
else
{
	big_error('In Loading !', 'Error while loading : ' . $go_to);
}

($hook = kleeja_run_hook('end_admin_page')) ? eval($hook) : null; //run hook 


//no style defined
if(empty($stylee))
{
	$text = $lang['NO_TPL'];
	$stylee = 'admin_info';
}


$go_menu_html = '';
if(isset($go_menu))
{
	foreach($go_menu as $m=>$d)
	{
		$go_menu_html .= '<li class="' . ($d['current']?'active':'') .'" id="c_' . $d['goto'] . '"><a href="' . $d['link'] . '" onclick="' . (isset($d['confirm']) && $d['confirm'] ? 'javascript:return confirm_from();' : '') . '">' . $d['name'] . '</a></li>';
	}
}

//header
if(!isset($_GET['_ajax_']))
{
	echo $tpl->display("admin_header");
}


//body
if(!isset($_GET['_ajax_']))
{
	$is_ajax = 'no';
	include get_template_path($stylee);
}

//footer
if(!isset($_GET['_ajax_']))
{
	echo $tpl->display("admin_footer");
}
//close db
$SQL->close();
exit;

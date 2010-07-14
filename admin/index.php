<?php
/**
*
* @package adm
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


//define important constants
define ('PATH' , '../');
define ('IN_INDEX' , true);
define ('IN_ADMIN' , true);


//we are in admin path, session and cookies require this
$adm_path = preg_replace('/.*?[\\\\|\/]([0-9a-z-_.]+)[\\\\|\/]([0-9a-z-_.]+)[\\\\|\/]' . preg_quote(basename(__file__), '/') . '/i', '/\\1/\\2/', __file__);
$adm_time = 18000;

//include imprtant file ..
require_once (PATH . 'includes/common.php');

//go to ..
$go_to		= isset($_GET['cp']) ? htmlspecialchars($_GET['cp']) : 'start';
$username	= $usrcp->name();

//for security
if (!$username)
{
	($hook = kleeja_run_hook('user_not_admin_admin_page')) ? eval($hook) : null; //run hook 
	redirect(PATH . 'ucp.php?go=login&return=' . urlencode(ADMIN_PATH . '?cp=' . $go_to));
}

//get languge of admin
get_lang('acp');

//
//need to login again
//
if(
	(empty($_SESSION['ADMINLOGIN']) || $_SESSION['ADMINLOGIN'] != md5($usrcp->name() . $config['siteurl'])) || 
	(empty($_SESSION['USER_SESS']) || $_SESSION['USER_SESS'] != session_id()) ||
	(empty($_SESSION['ADMINLOGIN_T']) || $_SESSION['ADMINLOGIN_T'] < time())	 
)
{
	if(isset($_GET['go']) && $_GET['go'] == 'login') 
	{
		if (isset($_POST['submit']))
		{
			//for onlines
			$ip	= get_ip();

			if ((int) $config['allow_online'] == 1)
			{
				$query_del	= array(
									'DELETE'	=> "{$dbprefix}online",
									'WHERE'		=> "ip='" . $ip . "'"
								);

				$SQL->build($query_del);
			}

			//login
			$ERRORS	= array();
			$pass_field = 'lpass_' .  preg_replace('/[^0-9]/', '', sha1($klj_session . sha1($config['h_key']) . $_POST['kid']));
			if (empty($_POST['lname']) || empty($_POST[$pass_field]))
			{
				$ERRORS[] = $lang['EMPTY_FIELDS'];
			}
			elseif(USER_ADMIN != 1)
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
					$_SESSION['ADMINLOGIN'] = md5($usrcp->name() . $config['siteurl']);
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
	$KEY_FOR_PASS	= preg_replace('/[^0-9]/', '', sha1($klj_session . sha1($config['h_key']) . $KEY_FOR_WEE)); 

	$err = false;
	if(!empty($errs))
	{
		$err = true;
	}
	
	//prevent indexing this page by bots
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	echo $tpl->display("admin_login");

	$SQL->close();
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

//path of admin extensions
$path_adm	= PATH . 'includes/adm';

//exception extentions
$ext_expt	= array();
$ext_expt[]	= 'start';
$ext_expt[]	= 'php_info';
$ext_expt[]	= 'aupdate';
//confirm msgs
$ext_confirm	= array();
$ext_confirm[]	= 'repair';	
$ext_confirm[]	= 'lgoutcp';	

($hook = kleeja_run_hook('begin_admin_page')) ? eval($hook) : null; //run hook 

//
//We hide list of admin menu and show only if there is auth.
//
$SHOW_LIST = true;

//get adm extensions
if(($dh = @opendir($path_adm)) !== false)
{
	while (($file = readdir($dh)) !== false)
	{
		if(strpos($file, '.php') !== false)
		{
			$adm_extensions[] = str_replace('.php', '', $file);
		}
	}
	closedir($dh);
}

//no extensions ?
if(!$adm_extensions || !is_array($adm_extensions))
{
	big_error('No Extensions', 'Error while loading admin extensions !');
}

/**
* exception of 406 ! dirty hosting
* 'configs' word listed as dangrous requested word
* so we replaced this word with 'options' insted. 
*/
if($go_to == 'options')
{
	$go_to = 'a_configs';
}

//no requst or wrong !
if(!$go_to || empty($go_to) ||  !in_array($go_to, $adm_extensions))
{
	$go_to = 'start';
}

//make array for menu 
$adm_extensions_menu =	array();

//re oreder the items as alphapatic !
sort($adm_extensions);
$i = 0;

// calls numbers
$cr_time = LAST_VISIT > 0 ? LAST_VISIT : time() - 3600*12;

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

	$fetched = $SQL->fetch_array($SQL->build($c_query));
	if($fetched['total_rows'])
	{
		$kbubbles[$n] = $fetched['total_rows'];
	}
	$SQL->freeresult();
}

//add your own bubbles here
($hook = kleeja_run_hook('kbubbles_admin_page')) ? eval($hook) : null; //run hook 


foreach($adm_extensions as $m)
{
	//some exceptions
	if(@in_array($m, $ext_expt))
	{
		continue;
	}

	($hook = kleeja_run_hook('foreach_ext_admin_page')) ? eval($hook) : null; //run hook 

	$s = $m;
	$m = isset($m[1]) && $m[1] == '_' ? substr($m , 2) : $m;

	++$i;
	$adm_extensions_menu[$i]	= array(
										'icon'		=> (file_exists($STYLE_PATH_ADMIN . 'images/menu_icons/' . ($m == 'configs' ? 'options' : $m) . '_button.gif'))	? $STYLE_PATH_ADMIN . 'images/menu_icons/' . ($m == 'configs' ? 'options' : $m) . '_button.gif' : $STYLE_PATH_ADMIN . 'images/menu_icons/no_icon.png',
										'icon_mini'	=> (file_exists($STYLE_PATH_ADMIN . 'images/menu_mini_icons/' . ($m == 'configs' ? 'options' : $m) . '_button.gif'))	? $STYLE_PATH_ADMIN . 'images/menu_mini_icons/' . ($m == 'configs' ? 'options' : $m) . '_button.gif' : $STYLE_PATH_ADMIN . 'images/menu_mini_icons/no_icon.png',
										'lang'		=> !empty($lang['R_'. strtoupper($m)]) ? $lang['R_'. strtoupper($m)] : (!empty($olang['R_' . strtoupper($m)]) ? $olang['R_' . strtoupper($m)] : strtoupper($m)),
										'link'		=> './' . basename(ADMIN_PATH) . '?cp=' . ($m == 'configs' ? 'options' : $s),
										'confirm'	=> (@in_array($m, $ext_confirm)) ? true : false,
										'current'	=> ($s == $go_to) ? true : false
									);
	
	//add another item to array for title='' in href or other thing
	$adm_extensions_menu[$i]['title'] = $adm_extensions_menu[$i]['lang'];

	//is this item has a bubble ?
	if(in_array($m, array_keys($kbubbles)))
	{
		$adm_extensions_menu[$i]['lang'] .= '<span class="kbubbles">' . $kbubbles[$m] . '</span>';
	}

	($hook = kleeja_run_hook('endforeach_ext_admin_page')) ? eval($hook) : null; //run hook 
}


//is mini menu is enabled !
if(isset($_GET['expand_menu']))
{
	update_config('expand_menu', intval($_GET['expand_menu']));
	$config['expand_menu'] = intval($_GET['expand_menu']);
}


if((int) $config['expand_menu'] == 0 || empty($config['expand_menu']))
{
	$MINI_MENU = false;
	$link_expand_menu = basename(ADMIN_PATH) . '?cp=' . $go_to . '&amp;expand_menu=1';
}
else
{
	$MINI_MENU = true;
	$link_expand_menu = basename(ADMIN_PATH) . '?cp=' . $go_to . '&amp;expand_menu=0';
}



//get it 
if (file_exists($path_adm . '/' . $go_to . '.php'))
{
	($hook = kleeja_run_hook("require_admin_page_begin_{$go_to}")) ? eval($hook) : null; //run hook 
	include_once ($path_adm . '/' . $go_to . '.php');
	($hook = kleeja_run_hook("require_admin_page_end_{$go_to}")) ? eval($hook) : null; //run hook 
}
else
{
	big_error('In Loading !', 'Error while loading : ' . $go_to . '');
}

($hook = kleeja_run_hook('end_admin_page')) ? eval($hook) : null; //run hook 


//no style defined
if(empty($stylee))
{
	$text = $lang['NO_TPL_SHOOSED'];
	$stylee = 'admin_info';
}


//header
echo $tpl->display("admin_header");
	//body
	echo $tpl->display($stylee);
//footer
echo $tpl->display("admin_footer");

//close db
$SQL->close();
exit;

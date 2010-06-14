<?php
/**
*
* @package Kleeja
* @version $Id$
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
* Header
* 
* To show header in any page you want .. 
* parameter : title : title of page as in <title></title>
*/	
function Saaheader($title, $outscript = false, $extra = '')
{
	global $tpl, $usrcp, $lang, $olang, $user_is, $username, $config;
	global $extras, $script_encoding, $errorpage, $userinfo, $charset;

	//is user ? and username
	$user_is = ($usrcp->name()) ? true: false;
	$username = ($usrcp->name()) ? $usrcp->name() : $lang['GUST'];


	//our default charset
	$charset = 'utf-8';

	//links for header
	$_LINKS = array(
			//user related
			'login'		=> $config['mod_writer'] ? 'login.html' : 'ucp.php?go=login',
			'logout'	=> $config['mod_writer'] ? 'logout.html' : 'ucp.php?go=logout',
			'register'	=> $config['mod_writer'] ? 'register.html' : 'ucp.php?go=register',
			'profile'	=> $config['mod_writer'] ? 'profile.html' : 'ucp.php?go=profile',
			'fileuser'	=> $config['mod_writer'] ? 'fileuser.html' : 'ucp.php?go=fileuser',
			'filecp'	=> $config['mod_writer'] ? 'filecp.html' : 'ucp.php?go=filecp',
			//another
			'guide'	=> $config['mod_writer'] ? 'guide.html' : 'go.php?go=guide',
			'rules'	=> $config['mod_writer'] ? 'rules.html' : 'go.php?go=rules',
			'call'	=> $config['mod_writer'] ? 'call.html' : 'go.php?go=call',
			'stats'	=> $config['mod_writer'] ? 'stats.html' : 'go.php?go=stats',
		);

	//assign some variables
	$tpl->assign("dir", $lang['DIR']);
	$tpl->assign("title", $title);
	$tpl->assign("_LINKS", $_LINKS);
	$tpl->assign("go_current", (isset($_GET['go']) ? htmlentities($_GET['go']) : false));
	$tpl->assign("go_back_browser", $lang['GO_BACK_BROWSER']);
	$tpl->assign("H_FORM_KEYS_LOGIN", kleeja_add_form_key('login'));
	$tpl->assign("action_login", 'ucp.php?go=login' . (isset($_GET['return']) ? '&amp;return=' . htmlspecialchars($_GET['return']) : ''));
	
	//$extra .= '';

	//check for extra header 
	$extras['header'] = empty($extras['header']) ? false : $extras['header'];

	($hook = kleeja_run_hook('func_Saaheader')) ? eval($hook) : null; //run hook

	$tpl->assign("EXTRA_CODE_META", $extra);

	$header = $tpl->display("header");

	if($config['siteclose'] == '1' && $usrcp->admin() && !defined('IN_ADMIN'))
	{
		//add notification bar 
		$header = preg_replace('/<body([^\>]*)>/i', "<body\\1>\n<!-- site is closed -->\n<p style=\"width: 100%; text-align:center; background:#FFFFA6; color:black; border:thin;top:0;left:0; position:absolute; width:100%;clear:both;\">" . $lang['NOTICECLOSED'] . "</p>\n<!-- #site is closed -->", $header);
	}

	echo $header;
}


/**
* Footer
*
* To show footer of any page you want 
* paramenters : none
*/
function Saafooter($outscript = false)
{
	global $tpl, $SQL, $starttm, $config, $usrcp, $lang, $olang;
	global $do_gzip_compress, $script_encoding, $errorpage, $extras, $userinfo;

	//show stats ..
	$page_stats = '';
	if ($config['statfooter'] != 0) 
	{
		$gzip			= $do_gzip_compress !=0 ?  "Enabled" : "Disabled";
		$hksys			= !defined('STOP_HOOKS') ? "Enabled" : "Disabled";
		$endtime		= get_microtime();
		$loadtime		= number_format($endtime - $starttm , 4);
		$queries_num	= $SQL->query_num;
		$time_sql		= round($SQL->query_num / $loadtime) ;
		$page_url		= preg_replace(array('/([\&\?]+)debug/i', '/&amp;/i'), array('', '&'), kleeja_get_page());
		$link_dbg		= $usrcp->admin() &&  $config['mod_writer'] != '1' ? '[ <a href="' .  str_replace('&', '&amp;', $page_url) . (strpos($page_url, '?') === false ? '?' : '&amp;') . 'debug">More Details ... </a> ]' : null;
		$page_stats		= "<strong>[</strong> GZIP : $gzip - Generation Time: $loadtime Sec  - Queries: $queries_num - Hook System:  $hksys <strong>]</strong>  " . $link_dbg ;
	}
		
	$tpl->assign("page_stats", $page_stats);
		
	//if admin, show admin in the bottom of all page
	$tpl->assign("admin_page", ($usrcp->admin() ? '<a href="' . ADMIN_PATH . '" class="admin_cp_link"><span>' . $lang['ADMINCP'] .  '</span></a>' : ''));

		
	// if google analytics .. //new version 
	//http://www.google.com/support/googleanalytics/bin/answer.py?answer=55488&topic=11126
	$googleanalytics = '';
	if (strlen($config['googleanalytics']) > 4)
	{
		$googleanalytics .= '<script type="text/javascript">' . "\n";
		$googleanalytics .= '<!--' . "\n";
		$googleanalytics .= 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");' . "\n";
		$googleanalytics .= 'document.write("\<script src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'>\<\/script>" );' . "\n";
		$googleanalytics .= '-->' . "\n";
		$googleanalytics .= '</script>' . "\n";
		$googleanalytics .= '<script type="text/javascript">' . "\n";
		$googleanalytics .= '<!--' . "\n";
		$googleanalytics .= 'var pageTracker = _gat._getTracker("' . $config['googleanalytics'] . '");' . "\n";
		$googleanalytics .= 'pageTracker._initData();' . "\n";
		$googleanalytics .= 'pageTracker._trackPageview();' . "\n";
		$googleanalytics .= '-->' . "\n";
		$googleanalytics .= '</script>' . "\n";
	}

	$tpl->assign("googleanalytics", $googleanalytics);	

	//check for extra header 
	if(empty($extras['footer']))
	{
		$extras['footer'] = false;
	}

	($hook = kleeja_run_hook('func_Saafooter')) ? eval($hook) : null; //run hook

	$footer = $tpl->display("footer");

	echo $footer;

	//page analysis 
	if (isset($_GET['debug']) && $usrcp->admin())
	{
		kleeja_debug();
	}

	//at end, close sql connections
	$SQL->close();
}

/**
* print inforamtion message 
* parameters : msg : text that will show as inforamtion
					title : <title>title of page</title>
					exit : stop script after showing msg 
*/
function kleeja_info($msg, $title='', $exit = true, $redirect = false, $rs = 2, $extra_code_header = '')
{
	global $text, $tpl, $SQL;

	($hook = kleeja_run_hook('kleeja_info_func')) ? eval($hook) : null; //run hook

	// assign {text} in info template
	$text = $msg;
	//header
	Saaheader($title, false, $extra_code_header);
	//show tpl
	echo $tpl->display('info');
	//footer
	Saafooter();
	
	//redirect
	if($redirect)
	{
		redirect($redirect, false, $exit, $rs);
	}
	else if($exit)
	{
		$SQL->close();
		exit();
	}
}

/**
* print error message 
* parameters : msg : text that will show as error mressage
					title : <title>title of page</title>
					exit : stop script after showing msg 
*/
function kleeja_err($msg, $title = '', $exit = true, $redirect = false, $rs = 2, $extra_code_header)
{
	global $text, $tpl, $SQL;

	($hook = kleeja_run_hook('kleeja_err_func')) ? eval($hook) : null; //run hook

	// assign {text} in err template
	$text	= $msg;
	//header
	Saaheader($title, false, $extra_code_header);
	//show tpl
	echo $tpl->display('err');
	//footer
	Saafooter();

	//redirect
	if($redirect)
	{
		redirect($redirect, false, $exit, $rs);
	}
	else if($exit)
	{
		$SQL->close();
		exit();
	}
}

/**
* Print cp error function handler
*
* For admin
*/
function kleeja_admin_err($msg, $navigation = true, $title='', $exit = true, $redirect = false, $rs = 2)
{
	global $text, $tpl, $SHOW_LIST, $adm_extensions, $adm_extensions_menu;
	global $STYLE_PATH_ADMIN, $lang, $olang, $SQL, $MINI_MENU;

	($hook = kleeja_run_hook('kleeja_admin_err_func')) ? eval($hook) : null; //run hook

	// assign {text} in err template
	$text		= $msg;
	$SHOW_LIST	= $navigation;

	//header
	echo $tpl->display("admin_header");
	//show tpl
	echo $tpl->display('admin_err');
	//footer
	echo $tpl->display("admin_footer");
		
	//redirect
	if($redirect)
	{
		redirect($redirect, false, $exit, $rs);
	}
	else if($exit)
	{
		$SQL->close();
		exit();
	}
}


/**
* Print inforamtion message on admin panel
*
* For admin
*/
function kleeja_admin_info($msg, $navigation=true, $title='', $exit=true, $redirect = false, $rs = 2)
{
	global $text, $tpl, $SHOW_LIST, $adm_extensions, $adm_extensions_menu;
	global $STYLE_PATH_ADMIN, $lang, $SQL, $MINI_MENU;

	($hook = kleeja_run_hook('kleeja_admin_info_func')) ? eval($hook) : null; //run hook

	// assign {text} in err template
	$text		= $msg;
	$SHOW_LIST	= $navigation;

	//header
	echo $tpl->display("admin_header");
	//show tpl
	echo $tpl->display('admin_info');
	//footer
	echo $tpl->display("admin_footer");
	
	//redirect
	if($redirect)
	{
		redirect($redirect, false, $exit, $rs);
	}
	else if($exit)
	{
		$SQL->close();
		exit();
	}
}

/**
* Show debug information 
* 
* parameters: none
*/
function kleeja_debug ()
{
	global $SQL,$do_gzip_compress, $all_plg_hooks;

	($hook = kleeja_run_hook('kleeja_debug_func')) ? eval($hook) : null; //run hook
	
		//get memory usage ; code of phpbb
		if (function_exists('memory_get_usage'))
		{
				if ($memory_usage = memory_get_usage())
				{
					$base_memory_usage	=	0;
					$memory_usage -= $base_memory_usage;
					$memory_usage = ($memory_usage >= 1048576) ? round((round($memory_usage / 1048576 * 100) / 100), 2) . ' MB' : (($memory_usage >= 1024) ? round((round($memory_usage / 1024 * 100) / 100), 2) . ' KB' : $memory_usage . ' BYTES');
					$debug_output = 'Memory Usage : <em>' . $memory_usage . '</em>';
				}
		}
		
		//thrn show it
		echo '<div class="debug_kleeja">';
		echo '<fieldset  dir="ltr"><legend><br /><br /><em style="font-family: Tahoma; color:red">[Page Analysis]</em></legend>';
		echo '<p>&nbsp;</p>';
		echo '<p><h2><strong>General Information :</strong></h2></p>';
		echo '<p>Gzip : <em>' . (($do_gzip_compress !=0 )?  "Enabled" : "Disabled") . '</em></p>';
		echo '<p>Queries Number :<em> ' .  $SQL->query_num . ' </i></p>';
		echo '<p>Hook System :<em> ' .  ((!defined('STOP_HOOKS'))?  "Enabled" : "Disabled"). ' </em></p>';
		echo '<p>Active Hooks :<em> ' .  sizeof($all_plg_hooks). ' </em></p>';
		echo '<p>' . $debug_output . '</p>';
		echo '<p>&nbsp;</p>';
		echo '<p><h2><strong><em>SQL</em> Information :</strong></h2></p> ';
		
		if(is_array($SQL->debugr))
		{ 
			foreach($SQL->debugr as $key=>$val)
			{
				echo '<fieldset name="sql"  dir="ltr" style="background:white"><legend><em>Query # [' . ($key+1) . '</em>]</legend> ';
				echo '<textarea style="font-family:Courier New,monospace;width:99%; background:#F4F4F4" rows="5" cols="10">' . $val[0] . '';
				echo '</textarea>	<br />';
				echo 'Duration :' . $val[1] . ''; 
				echo '</fieldset>';
				echo '<br /><br />';
			}
		}
		else
		{
			echo '<p><strong>NO SQLs</strong></p>';
		}
		
		echo '<p>&nbsp;</p><p><h2><strong><em>HOOKS</em> Information :</strong></h2></p> ';
		echo '<ul>';
		
		if(sizeof($all_plg_hooks) > 0)
		{ 
				foreach($all_plg_hooks as $k=>$v)
				{
					foreach($v as $p=>$c) $p=$p; $c=$c; // exactly 
					
					echo '<li><em>Plugin  # [' . $p . ']</em>';
					//echo '<textarea style="font-family:Courier New,monospace;width:99%; background:#F4F4F4" rows="5" cols="10">' . htmlspecialchars($c) . '</textarea><br />';
					echo ' : hook_name :' . $k . '</li>';
				}
		}
		else
		{
			echo '<p><strong>NO-HOOKS</strong></p>';
		}
		
		echo '</ul>';
		echo '</div>';
}

/**
* Show error of critical problem !
* 
* parameter: error_title : title of prblem
*			msg_text: message of problem
*/
function big_error ($error_title,  $msg_text, $error = true)
{
	global $SQL; 
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">' . "\n";
	echo '<head>' . "\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
	echo '<title>' . htmlspecialchars($error_title) . '</title>' . "\n";
	echo '<style type="text/css">' . "\n\t";
	echo '* { margin: 0; padding: 0; }' . "\n\t";
	echo 'body { background: #fff;color: #444;font-family:tahoma, verdana, arial, sans-serif;font-size: 11px;margin: 0 auto;padding: 50px;width: 767px;}' . "\n\t";
	echo '.error {color: #333;background:#ffebe8;border: 1px solid #dd3c10;} .info {color: #333;background:#fff9d7;border: 1px solid #e2c822;}' . "\n\t";
	echo '.error,.info {padding: 10px;font-family:"lucida grande", tahoma, verdana, arial, sans-serif;font-size: 12px;}' . "\n";
	echo '</style>' . "\n";
	echo '</head>' . "\n";
	echo '<body>' . "\n\t";
	echo '<div class="' . ($error ? 'error' : 'info') . '">' . "\n";
	echo "\n\t\t<h2>Kleeja " . ($error ? 'error' : 'information message') . " : </h2><br />" . "\n";
	echo "\n\t\t<strong> [ " . $error_title . ' ] </strong><br /><br />' . "\n\t\t" . $msg_text . "\n\t";
	echo "\n\t\t" . '<br /><br /><small>Visit <a href="http://www.kleeja.com/" title="kleeja">Kleeja</a> Website for more details.</small>' . "\n\t";
	echo '</div>' . "\n";
	echo '</body>' . "\n";
	echo '</html>';
	@$SQL->close();
	exit();
}


/**
* Redirect
*
*/
function redirect($url, $header = true, $exit = true, $sec = 0)
{
	global $SQL;

    if (!headers_sent() && $header)
	{
        header('Location: ' . str_replace(array('&amp;'), array('&'), $url)); 
    }
	else
	{
		echo '<script type="text/javascript"> setTimeout("window.location.href = \'' .  str_replace(array('&amp;'), array('&'), $url) . '\'", ' . $sec*1000 . '); </script>';
		echo '<noscript><meta http-equiv="refresh" content="' . $sec .';url=' . $url . '" /></noscript>';
	}

	if($exit)
	{
		$SQL->close();
		exit;
	}
}

/**
*
* todo : make another function for _GET request i.e. ?formkey=2352g23 
* //base64_encode('form_key|time') or another good idea !
*/
function kleeja_add_form_key_get($request_id) {}
function kleeja_check_form_key_get($request_id) {}

/**
* Prevent CSRF, 
*
* This will generate hidden fields for kleeja forms
*/
function kleeja_add_form_key($form_name)
{
	global $config, $klj_session;
	$now = time();
	return '<input type="hidden" name="k_form_key" value="' . sha1($config['h_key'] . $form_name . $now . $klj_session) . '" /><input type="hidden" name="k_form_time" value="' . $now . '" />' . "\n";
}

/**
* Prevent CSRF, 
*
* This will check hidden fields that came from kleeja forms
*/
function kleeja_check_form_key($form_name, $require_time = 150 /*seconds*/ )
{
	global $config, $klj_session;

	if(defined('IN_ADMIN'))
	{
		//we increase it for admin to be a duble 
		$require_time *= 2;
	}

	if (isset($_POST['k_form_key']) && isset($_POST['k_form_time']))
	{
		$key_was = trim($_POST['k_form_key']);
		$time_was = intval($_POST['k_form_time']);
		$different = time() - $time_was;
		
		//check time that user spent in the form 
		if($different && (!$require_time || $require_time >= $different))
		{
			if(sha1($config['h_key'] . $form_name . $time_was . $klj_session) === $key_was)
			{
				return true;
			}
		}
	}
	
	return false;
}

/**
* Link generator 
*
* Files can be many links styles, so this will generate the current style of link
*/

function kleeja_get_link ($pid, $extra = array())
{
	global $config;
	
	$links = array();
	
	//to avoid problems
	$config['id_form'] = empty($config['id_form']) ? 'id' : $config['id_form'];
	
	//for prevent bug with rewrite
	if($config['mod_writer'] && !empty($extra['::NAME::']) && $config['id_form'] != 'direct')
	{
		$extra['::NAME::'] = str_replace('.', '-', $extra['::NAME::']);
	}
	
	switch($config['id_form'])
	{
		case 'id':
			if($config['mod_writer'])
			{
				$links += array(
							'thumb' => 'thumb::ID::.html',
							'image' => 'image::ID::.html',
							'del'	=> 'del::CODE::.html',
							'file'	=> 'download::ID::.html',
						);
			}
			else
			{
				$links += array(
							'thumb' => 'do.php?thmb=::ID::',
							'image' => 'do.php?img=::ID::',
							'del'	=> 'go.php?go=del&amp;cd=::CODE::',
							'file'	=> 'do.php?id=::ID::',
						);
			}
		break;
		case 'filename':
			if($config['mod_writer'])
			{
				$links += array(
							'thumb' => 'thumbf-::NAME::.html',
							'image' => 'imagef-::NAME::.html',
							'del'	=> 'del::CODE::.html',
							'file'	=> 'downloadf-::NAME::.html',
						);
			}
			else
			{
				$links += array(
							'thumb' => 'do.php?thmbf=::NAME::',
							'image' => 'do.php?imgf=::NAME::',
							'del'	=> 'go.php?go=del&amp;cd=::CODE::',
							'file'	=> 'do.php?filename=::NAME::',
						);
			}
		break;
		case 'direct':
			if($config['mod_writer'])
			{
				$links += array(
							'del'	=> 'del::CODE::.html',
						);
			}
			else
			{
				$links += array(
							'del'	=> 'go.php?go=del&amp;cd=::CODE::',
						);
			}
			
			$links += array(
						'thumb' => '::DIR::/thumbs/::NAME::',
						'image' => '::DIR::/::NAME::',
						'file'	=> '::DIR::/::NAME::',
						);
		break;
		default:
			//add another type of links 
			//if $config['id_form']  == 'another things' : do another things .. 
			($hook = kleeja_run_hook('kleeja_get_link_d_func')) ? eval($hook) : null; //run hook
		break;
	}

	($hook = kleeja_run_hook('kleeja_get_link_func')) ? eval($hook) : null; //run hook

	$return = $config['siteurl'] . str_replace(array_keys($extra), array_values($extra), $links[$pid]);
	
	($hook = kleeja_run_hook('kleeja_get_link_func_rerun')) ? eval($hook) : null; //run hook
	
	return $return; 
}

/**
*  Uploading boxes 
*
* Parse template of boxes and print them
*/
function get_up_tpl_box($box_name, $extra = array())
{
	global $STYLE_PATH, $config;
	static $boxes = false;
	
	//prevent loads
	//also this must be cached in future
	if($boxes !== true)
	{
		$tpl_path = $STYLE_PATH . 'up_boxes.html';
		if(!file_exists($tpl_path))
		{
			$depend_on = false;
			if(trim($config['style_depend_on']) != '')
			{
				$depend_on = $config['style_depend_on'];
			}
			else
			{
				$depend_on = 'default';
			}
			
			$tpl_path = str_replace('/' . $config['style'] . '/', '/' . trim($depend_on) . '/', $tpl_path);
		}
	

		$tpl_code = file_get_contents($tpl_path);
		$tpl_code = preg_replace("/\n[\n\r\s\t]*/", '', $tpl_code);//remove extra spaces
		$matches = preg_match_all('#<!-- BEGIN (.*?) -->(.*?)<!-- END (?:.*?) -->#', $tpl_code, $match);
		
		$boxes = array();
		for ($i = 0; $i < $matches; $i++)
		{
			if (empty($match[1][$i]))
			{
				continue;//it's empty , let's leave it
			}

			$boxes[$match[1][$i]] = $match[2][$i];
		}
	}
	
	//extra value 
	$extra += array(
				'siteurl' => $config['siteurl'],
				'sitename' => $config['sitename'],
			);
	
	//return compiled value
	$return = $boxes[$box_name];
	foreach($extra as $var=>$val)
	{
		$return = preg_replace('/{' . $var . '}/', $val, $return);
	}
	
	($hook = kleeja_run_hook('get_up_tpl_box_func')) ? eval($hook) : null; //run hook

	return $return;
}

/**
* secondary function; see go.php?go=guide
*/
function group_id_order($a, $b) 
{ 
	return ($a['group_id'] == $b['group_id']) ? 0 : ($a['group_id'] < $b['group_id'] ? -1 : 1); 
}

/**
* Extract info of a style
*/
function kleeja_style_info($style_name)
{
	$inf_path = PATH . 'styles/' . $style_name . '/info.txt';

	//is info.txt exists or not
	if(!file_exists($inf_path))
	{
		return false;
	}

	$inf_c = file_get_contents($inf_path);
	//some ppl will edit this file with notepad or even with office word :)
	$inf_c = str_replace(array("\r\n", "\r"), array("\n", "\n"), $inf_c);

	//as lines
	$inf_l = @explode("\n", $inf_c);
	$inf_l = array_map('trim', $inf_l);

	$inf_r = array();
	foreach($inf_l as $m)
	{
		//comments
		if(isset($m[0]) && $m[0] == '#' || trim($m) == '')
		{
			continue;
		}

		$t = array_map('trim', @explode('=', $m, 2));
		# ':' mean ummm, mean something secondary as in sub-array
		if(strpos($t[0], ':') !== false)
		{
			$t_t0 = array_map('trim', @explode(':', $t[0]));
			$inf_r[$t_t0[0]][$t_t0[1]] = $t[1];
		}
		else
		{
			$inf_r[$t[0]] = $t[1];
		}
	}

	($hook = kleeja_run_hook('kleeja_style_info_func')) ? eval($hook) : null; //run hook

	return $inf_r;
}

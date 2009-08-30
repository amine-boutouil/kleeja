<?php
##################################################
#						Kleeja 
#
# Filename : functions_display.php 
# purpose :  Output functions.
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author: phpfalcon $ , $Rev: 447 $,  $Date:: 2009-06-29 05:16:14 +0300#$
##################################################

/*
* header of kleeja
* to show header in any page you want .. 
*  parameter : title : title of page as in <title></title>
*/	
function Saaheader($title, $outscript=false)
{
		global $tpl, $usrcp, $lang, $olang, $user_is, $username, $config;
		global $extras, $script_encoding, $errorpage, $userinfo, $is_opera;
		
		//is user ? and username
		$user_is = ($usrcp->name()) ? true: false;
		$username = ($usrcp->name()) ? $usrcp->name() : $lang['GUST'];
		$is_opera = (is_browser('opera')) ? true : false;
		
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
		$tpl->assign("go_back_browser", $lang['GO_BACK_BROWSER']);
		$extra = '';

		//check for extra header 
		$extras['header'] = empty($extras['header']) ? false : $extras['header'];

		($hook = kleeja_run_hook('func_Saaheader')) ? eval($hook) : null; //run hook

		$tpl->assign("EXTRA_CODE_META", $extra);

		if($config['user_system'] != '1' && isset($script_encoding) && function_exists('iconv') && !preg_match('/utf/i',strtolower($script_encoding)) && !$errorpage && $outscript && !defined('DISABLE_INTR')) 
		{
			$header = iconv("UTF-8", strtoupper($script_encoding) . "//IGNORE", $tpl->display("header"));
		}
		else 
		{
			$header = $tpl->display("header");
		}
		
		if($config['siteclose'] == '1' && $usrcp->admin() && !defined('IN_ADMIN'))
		{
			//<style>body {height: 30%;}</style>
			$header = str_replace('<body>', '<body>
<!-- site is closed -->
<p style="width: 100%; text-align:center; background:#FFFFA6; color:black; border:thin;top:0;left:0; position:absolute; width:100%;clear:both;">' . $lang['NOTICECLOSED'] . '</p>
<!-- //site is closed -->', $header);
		}

		echo $header;
}


/*
*footer
* to show footer of any page you want 
* paramenters : none
*/
function Saafooter($outscript=false)
{
		global $tpl, $SQL, $starttm, $config, $usrcp, $lang, $olang;
		global $do_gzip_compress, $script_encoding, $errorpage, $extras, $userinfo;
		
		//show stats ..
		$page_stats = '';
		if ($config['statfooter'] !=0) 
		{
			$gzip			= $do_gzip_compress !=0 ?  "Enabled" : "Disabled";
			$hksys			= !defined('STOP_HOOKS') ? "Enabled" : "Disabled";
			$endtime		= get_microtime();
			$loadtime		= number_format($endtime - $starttm , 4);
			$queries_num	= $SQL->query_num;
			$time_sql		= round($SQL->query_num / $loadtime) ;
			$page_url		= preg_replace('/([\&\?]+)debug/i','', kleeja_get_page());
			$link_dbg		= $usrcp->admin() &&  $config['mod_writer'] != '1' ? '[ <a href="' .  $page_url . (strpos($page_url, '?') === false ? '?' : '&') . 'debug">More Details ... </a> ]' : null;
			$page_stats		= "<strong>[</strong> GZIP : $gzip - Generation Time: $loadtime Sec  - Queries: $queries_num - Hook System:  $hksys <strong>]</strong>  " . $link_dbg ;
		}#end statfooter
		
		$tpl->assign("page_stats", $page_stats);
		
		//if admin, show admin in the bottom of all page
		$tpl->assign("admin_page", ($usrcp->admin() ? '<a href="' . ADMIN_PATH . '" class="admin_cp_link"><span>' . $lang['ADMINCP'] .  '</span></a>' : ''));

		
		// if google analytics .. //new version 
		//http://www.google.com/support/googleanalytics/bin/answer.py?answer=55488&topic=11126
		$googleanalytics = '';
		if (strlen($config['googleanalytics']) > 4)
		{
			$googleanalytics .= '<script type="text/javascript">' . "\n";
			$googleanalytics .= 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");' . "\n";
			$googleanalytics .= 'document.write("\<script src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'>\<\/script>" );' . "\n";
			$googleanalytics .= '</script>' . "\n";
			$googleanalytics .= '<script type="text/javascript">' . "\n";
			$googleanalytics .= 'var pageTracker = _gat._getTracker("' . $config['googleanalytics'] . '");' . "\n";
			$googleanalytics .= 'pageTracker._initData();' . "\n";
			$googleanalytics .= 'pageTracker._trackPageview();' . "\n";
			$googleanalytics .= '</script>' . "\n";
		}

		$tpl->assign("googleanalytics", $googleanalytics);	

		//check for extra header 
		if(empty($extras['footer']))
		{
			$extras['footer'] = false;
		}
		
		($hook = kleeja_run_hook('func_Saafooter')) ? eval($hook) : null; //run hook
		
		//show footer
		if($config['user_system'] != '1' && isset($script_encoding) && function_exists('iconv')  && !preg_match('/utf/i',strtolower($script_encoding)) && !$errorpage && $outscript && !defined('DISABLE_INTR'))
		{
			$footer = iconv("UTF-8", strtoupper($script_encoding) . "//IGNORE", $tpl->display("footer"));
		}
		else 
		{
			$footer = $tpl->display("footer");
		}
		
		
		echo $footer;
		//print $footer;
		
		//page analysis 
		if (isset($_GET['debug']) && $usrcp->admin())
		{
			kleeja_debug();
		}
		
		// THEN .. at finish, close sql connections
		$SQL->close();
}

/*
* print inforamtion message 
* parameters : msg : text that will show as inforamtion
					title : <title>title of page</title>
					exit : stop script after showing msg 
*/
function kleeja_info($msg,$title='', $exit=true, $redirect=false, $rs='2')
{
	global $text, $tpl, $SQL;
	
	($hook = kleeja_run_hook('kleeja_info_func')) ? eval($hook) : null; //run hook
				
	// assign {text} in info template
	$text	= $msg;
	//header
	Saaheader($title);
	//show tpl
	echo $tpl->display('info');
	//footer
	Saafooter();
	
	//redirect
	if($redirect)
	{
        echo '<meta http-equiv="refresh" content="' . $rs . ';url=' . $redirect . '" />'; 
	}
	
	if ($exit)
	{
		$SQL->close();
		exit();
	}
}

/*
* print error message 
* parameters : msg : text that will show as error mressage
					title : <title>title of page</title>
					exit : stop script after showing msg 
*/
function kleeja_err($msg, $title='', $exit=true, $redirect=false, $rs='2')
{
	global $text, $tpl, $SQL;
	
	($hook = kleeja_run_hook('kleeja_err_func')) ? eval($hook) : null; //run hook
				
	// assign {text} in err template
	$text	= $msg;
	//header
	Saaheader($title);
	//show tpl
	echo $tpl->display('err');
	//footer
	Saafooter();

	//redirect
	if($redirect)
	{
        echo '<meta http-equiv="refresh" content="' . $rs . ';url=' . $redirect . '" />'; 
	}

	if ($exit)
	{
		$SQL->close();
		exit();
	}
}

/*
	cp error function handler
*/
function kleeja_admin_err($msg, $navigation=true, $title='', $exit=true)
{
	global $text, $tpl, $SHOW_LIST, $adm_extensions, $adm_extensions_menu, $STYLE_PATH_ADMIN, $lang, $olang, $SQL;
	
	($hook = kleeja_run_hook('kleeja_admin_err_func')) ? eval($hook) : null; //run hook
				
	// assign {text} in err template
	$text	= $msg;
	$SHOW_LIST	= $navigation;

	//header
	echo $tpl->display("admin_header");
	//show tpl
	echo $tpl->display('admin_err');
	//footer
	echo $tpl->display("admin_footer");
		
	if ($exit)
	{
		//close mysql connection
		$SQL->close();
		exit();
	}
}


/*
* print inforamtion message on admin panel
* parameters : msg : text that will show as inforamtion
					title : <title>title of page</title>
					exit : stop script after showing msg 
*/
function kleeja_admin_info($msg, $navigation=true, $title='', $exit=true)
{
	global $text, $tpl, $SHOW_LIST, $adm_extensions, $adm_extensions_menu, $STYLE_PATH_ADMIN, $lang, $SQL;
	
	($hook = kleeja_run_hook('kleeja_admin_info_func')) ? eval($hook) : null; //run hook
				
// assign {text} in err template
	$text	= $msg;
	$SHOW_LIST	= $navigation;
	
	//header
	echo $tpl->display("admin_header");
	//show tpl
	echo $tpl->display('admin_info');
	//footer
	echo $tpl->display("admin_footer");
	
	if ($exit)
	{
		//close mysql connection
		$SQL->close();
		exit();
	}
}

/**
** show debug information 
** parameters: none
**/
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

/*
* show error of critical problem !
* parameter: error_title : title of prblem
*					msg_text: message of problem
*/
function big_error ($error_title,  $msg_text)
{
	global $SQL; 
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">' . "\n";
	echo '<head>' . "\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
	echo '<title>' . htmlspecialchars($error_title) . '</title>' . "\n";
	echo '<style type="text/css">' . "\n\t";
	echo '* { margin: 0; padding: 0; }' . "\n\t";
	echo 'body { background: #fff;color: #444;font-family:tahoma, verdana, arial, sans-serif;font-size: 11px;margin: 0 auto;padding: 50px;width: 767px;}' . "\n\t";
	echo '.error {color: #333;background:#ffebe8;border: 1px solid #dd3c10;}' . "\n\t";
	echo '.error {padding: 10px;font-family:"lucida grande", tahoma, verdana, arial, sans-serif;font-size: 12px;}' . "\n";
	echo '</style>' . "\n";
	echo '</head>' . "\n";
	echo '<body>' . "\n\t";
	echo '<div class="error">' . "\n";
	echo "\n\t\t<h2>Kleeja Error : </h2><br />" . "\n";
	echo "\n\t\t<strong> [ " . $error_title . ' ] </strong><br /><br />' . "\n\t\t" . $msg_text . "\n\t";
	echo "\n\t\t" . '<br /><br /><small>Visit <a href="http://www.kleeja.com/" title="kleeja">Kleeja</a> Website for more details.</small>' . "\n\t";
	echo '</div>' . "\n";
	echo '</body>' . "\n";
	echo '</html>';
	@$SQL->close();
	exit();
}

//redirect [php.net]
function redirect($url, $header=true, $exit=false)
{
	global $SQL;
	
    if (!headers_sent() && $header)
	{
        header('Location: ' . $url); 
    }
	else
	{
        echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . $url . '" /></noscript>'; 
	}
	
	$SQL->close();
	
	if($exit)
	{
		exit;
	}
}

//prevent CSRF, this will generate hidden fields for kleeja forms
function kleeja_add_form_key($form_name)
{
	global $config, $klj_session;
	$now = time();
	return '<input type="hidden" name="k_form_key" value="' . sha1($config['h_key'] . $form_name . $now . $klj_session) . '" /><input type="hidden" name="k_form_time" value="' . $now . '" />' . "\n";
}

//prevent CSRF, this will check hidden fields that came from kleeja forms
function kleeja_check_form_key($form_name, $require_time = 150 /*seconds*/ )
{
	global $config, $klj_session;
	
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


//link generator 
function kleeja_get_link ($pid, $extra = array())
{
	global $config;
	
	$links = array();
	
	//to avoid problems
	$config['id_form'] = empty($config['id_form']) ? 'id' : $config['id_form'];
	
	//for prevent bug with rewrite
	if($config['mod_writer'] && !empty($extra['::NAME::']))
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
							'thumb' => 'download.php?thmb=::ID::',
							'image' => 'download.php?img=::ID::',
							'del'	=> 'go.php?go=del&amp;cd=::CODE::',
							'file'	=> 'download.php?id=::ID::',
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
							'thumb' => 'download.php?thmbf=::NAME::',
							'image' => 'download.php?imgf=::NAME::',
							'del'	=> 'go.php?go=del&amp;cd=::CODE::',
							'file'	=> 'download.php?filename=::NAME::',
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
						'thumb' => '::DIR::/thumbs/::FNAME::',
						'image' => '::DIR::/::FNAME::',
						'file'	=> '::DIR::/::FNAME::',
						);
		break;
		default:
			//add another type of links 
			//if $config['id_form']  == 'another things' : do another things .. 
			($hook = kleeja_run_hook('kleeja_get_link_d_func')) ? eval($hook) : null; //run hook
		break;
	}
	
	($hook = kleeja_run_hook('kleeja_get_link_func')) ? eval($hook) : null; //run hook
	return $config['siteurl'] . str_replace(array_keys($extra), array_values($extra), $links[$pid]);
}

//for uploading boxes 
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
			if(file_exists($STYLE_PATH . 'depend_on.txt'))
			{
				$depend_on = file_get_contents($STYLE_PATH . 'depend_on.txt');
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
	return $return;
}

function group_id_order($a, $b) 
{ 
	return ($a['group_id'] == $b['group_id']) ? 0 : ($a['group_id'] < $b['group_id'] ? -1 : 1); 
}

#<-- EOF
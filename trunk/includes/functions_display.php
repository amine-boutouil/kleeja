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
*  parameter : title : title of page as in <titl></titl>
*/	
function Saaheader($title, $outscript=false)
{
		global $tpl, $usrcp, $lang, $user_is, $config;
		global $extras, $script_encoding, $errorpage;

		$user_is = ($usrcp->name()) ? true: false;
		
		//login - logout-profile... etc ..
		if (!$usrcp->name()) 
		{
			$login_name		= $lang['LOGIN']; 
			$login_url		= ($config['mod_writer']) ? "login.html" : "ucp.php?go=login";
			$usrcp_name		= $lang['REGISTER'];
			$usrcp_url		= ($config['mod_writer']) ? "register.html" : "ucp.php?go=register";
			$usrfile_url = $usrfile_name = null;
		}
		else
		{
			$login_name		= $lang['LOGOUT'] . "[" . $usrcp->name() . "]";
			$login_url		= ($config['mod_writer']) ? "logout.html" : "ucp.php?go=logout";
			$usrcp_name		= $lang['PROFILE'];
			$usrcp_url		= ($config['mod_writer']) ? "profile.html" : "ucp.php?go=profile";
			$usrfile_name	= $lang['YOUR_FILEUSER'];
			$usrfile_url	= ($config['mod_writer']) ? "fileuser.html" : "ucp.php?go=fileuser";
		}

		$vars = array (
							0=>"navigation",
							1=>"index_name",
							2=>"guide_name", 3=>"guide_url",
							4=>"rules_name", 5=>"rules_url",
							6=>"call_name", 7=>"call_url",
							8=>"login_name", 9=>"login_url",
							10=>"usrcp_name", 11=>"usrcp_url",
							12=>"filecp_name", 13=>"filecp_url",
							14=>"stats_name", 15=>"stats_url",
							16=>"usrfile_name", 17=>"usrfile_url"
						);
		
		if($config['mod_writer'])
		{
			$vars2 = array(
							0=>$lang['JUMPTO'],
							1=>$lang['INDEX'],
							2=>$lang['GUIDE'],3=>"guide.html",
							4=>$lang['RULES'],5=>"rules.html",
							6=>$lang['CALL'],7=>"call.html",
							8=>$login_name,9=>$login_url,
							10=>$usrcp_name,11=>$usrcp_url,
							12=>$lang['FILECP'],13=>"filecp.html",
							14=>$lang['STATS'],15=>"stats.html",
							16=>$usrfile_name,17=>$usrfile_url
						);
		}
		else
		{
			$vars2 = array(
							0=>$lang['JUMPTO'],
							1=>$lang['INDEX'],
							2=>$lang['GUIDE'],3=>"go.php?go=guide",
							4=>$lang['RULES'],5=>"go.php?go=rules",
							6=>$lang['CALL'],7=>"go.php?go=call",
							8=>$login_name,9=>$login_url,
							10=>$usrcp_name,11=>$usrcp_url,
							12=>$lang['FILECP'],13=>"ucp.php?go=filecp",
							14=>$lang['STATS'],15=>"go.php?go=stats",
							16=>$usrfile_name,17=>$usrfile_url
						);
		}

		//assign variables
		for($i=0;$i<count($vars);$i++)
		{
			$tpl->assign($vars[$i],$vars2[$i]);
		}
		$tpl->assign("dir", $lang['DIR']);
		$tpl->assign("title", $title);
		$tpl->assign("go_back_browser", $lang['GO_BACK_BROWSER']);
		$extra = '';
		
		//$tpl->assign("ex_header",$extras['header']);

		($hook = kleeja_run_hook('func_Saaheader')) ? eval($hook) : null; //run hook
		
		$tpl->assign("EXTRA_CODE_META", $extra);
		
		if($config['user_system'] != '1' && isset($script_encoding) && function_exists('iconv') && !eregi('utf',strtolower($script_encoding)) && !$errorpage && $outscript && !defined('DISABLE_INTR')) 
		{
			$header = iconv("UTF-8", strtoupper($script_encoding) . "//IGNORE", $tpl->display("header"));
		}
		else 
		{
			$header = $tpl->display("header");
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
		global $do_gzip_compress, $script_encoding, $errorpage;
		
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
			$link_dbg		= $usrcp->admin() ? '[ <a href="' .  str_replace('debug','', kleeja_get_page()) . (strpos(kleeja_get_page(), '?') === false ? '?' : '&') . 'debug">More Details ... </a> ]' : null;
			$page_stats	= "<strong>[</strong> GZIP : $gzip - Generation Time: $loadtime Sec  - Queries: $queries_num - Hook System:  $hksys <strong>]</strong>  " . $link_dbg ;
		}#end statfooter
		
		$tpl->assign("page_stats", $page_stats);
		
		//if admin, show admin in the bottom of all page
			$tpl->assign("admin_page", ($usrcp->admin() ?'<a href="./admin.php"><span>' . $lang['ADMINCP'] .  '</span></a><br />' : ''));

		
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

		($hook = kleeja_run_hook('func_Saafooter')) ? eval($hook) : null; //run hook
		
		//show footer
		if($config['user_system'] != '1' && isset($script_encoding) && function_exists('iconv')  && !eregi('utf',strtolower($script_encoding)) && !$errorpage && $outscript && !defined('DISABLE_INTR'))
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
function kleeja_info($msg,$title='', $exit=true)
{
	global $text, $tpl;
	
	($hook = kleeja_run_hook('kleeja_info_func')) ? eval($hook) : null; //run hook
				
	// assign {text} in info template
	$text	= $msg;
	//header
	Saaheader($title);
	//show tpl
	echo $tpl->display('info');
	//footer
	Saafooter();
				
	if ($exit)
	{
		exit();
	}
}

/*
* print error message 
* parameters : msg : text that will show as error mressage
					title : <title>title of page</title>
					exit : stop script after showing msg 
*/
function kleeja_err($msg, $title='', $exit=true)
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

	if ($exit)
	{
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
	global $text, $tpl, $SHOW_LIST, $adm_extensions, $adm_extensions_menu, $STYLE_PATH_ADMIN, $lang;
	
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
		echo '<br />';
		echo '<fieldset  dir="ltr" style="background:white"><legend style="font-family: Arial; color:red"><em>[Page Analysis]</em></legend>';
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
		
		if(sizeof($all_plg_hooks) > 0)
		{ 
				foreach($all_plg_hooks as $k=>$v)
				{
					foreach($v as $p=>$c) $p=$p; $c=$c; // exactly 
					
					echo '<fieldset name="hook"  dir="ltr" style="background:white"><legend><em>Plugin  # [' . $p . ']</em></legend>';
					echo '<textarea style="font-family:Courier New,monospace;width:99%; background:#F4F4F4" rows="5" cols="10">' . htmlspecialchars($c) . '</textarea><br />';
					echo 'for hook_name :' . $k . '</fieldset><br /><br />';
				}
		}
		else
		{
			echo '<p><strong>NO-HOOKS</strong></p>';
		}
		
		echo '<br /><br /><br /></fieldset>';
}

/*
* show error of critical problem !
* parameter: error_title : title of prblem
*					msg_text: message of problem
*/
function big_error ($error_title,  $msg_text)
{
	global $SQL; 
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html xmlns="http://www.w3.org/1999/xhtml">';
	echo '<head>';
	echo '<meta http-equiv="Content-Language" content="en-us" />';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo '<title>Error In Kleeja</title>';
	echo '</head>';
	echo '<body>';
	echo '<p style="color: #FF0000;"><strong>Error In Kleeja : [<span  style="color: #800000;">&nbsp; ' . $error_title . ' </span>&nbsp;]</strong></p>';
	echo '<div style="border: 1px dashed #808080;background-color: #FFF7F4; width: 70%;font-family:Tahoma">' . $msg_text . '</div>';
	echo '<br /><a href="http://www.kleeja.com/">Kleeja Website</a>';
	echo '</body>';
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

?>
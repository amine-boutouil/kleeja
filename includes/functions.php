<?php
#########################################
#						Kleeja
#
# Filename : functions.php
# purpose :  functions for all script. the important feature of kleeja
# copyright 2007-2009 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : saanina
#########################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}

	  

/*
* header of kleeja
* to show header in any page you want .. 
*  parameter : title : title of page as in <titl></titl>
*/	
function Saaheader($title)
{
		global $tpl,$usrcp,$lang,$user_is,$config,$extras;

		$user_is = ($usrcp->name()) ? true: false;
		
		//login - logout-profile... etc ..
		if (!$usrcp->name()) 
		{
			$login_name		= $lang['LOGIN']; 
			$login_url		= ($config['mod_writer']) ? "login.html" : "ucp.php?go=login";
			$usrcp_name		= $lang['REGISTER'];
			$usrcp_url		= ($config['mod_writer']) ? "register.html" : "ucp.php?go=register";
		}
		else
		{
			$login_name		= $lang['LOGOUT']."[".$usrcp->name()."]";
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
		//$tpl->assign("ex_header",$extras['header']);

		($hook = kleeja_run_hook('func_Saaheader')) ? eval($hook) : null; //run hook
		
		print $tpl->display("header");
	}


/*
*footer
* to show footer of any page you want 
* paramenters : none
*/
function Saafooter()
{
		global $tpl,$SQL,$starttm,$config,$usrcp,$lang,$do_gzip_compress;
		
		//show stats ..
		if ($config['statfooter'] !=0) 
		{
			$gzip			= ($do_gzip_compress !=0 )?  "Enabled" : "Disabled";
			$hksys			= (!defined('STOP_HOOKS'))?  "Enabled" : "Disabled";
			$endtime		= get_microtime();
			$loadtime		= number_format($endtime - $starttm , 4);
			$queries_num	= $SQL->query_num;
			$time_sql		= round($SQL->query_num / $loadtime) ;
			$link_dbg		= (($usrcp->admin()) ? "[ <a href=" .  str_replace('debug','',kleeja_get_page()) . ((strpos(kleeja_get_page(), '?') === false) ? '?' : '&') . "debug>More Details ... </a> ]" : null);
			$page_stats	= "<b>[</b> GZIP : $gzip - Generation Time: $loadtime Sec  - Queries: $queries_num - Hook System:  $hksys <b>]</b>  " . $link_dbg ;
			$tpl->assign("page_stats",$page_stats);
		}#end statfooter

		//if admin, show admin in the bottom of all page
		if ($usrcp->admin())
		{
			$admin_page = '<br /><a href="./admin.php">' . $lang['ADMINCP'] .  '</a><br />';
			$tpl->assign("admin_page",$admin_page);
		}
		
		// if google analytics .. 
		if (strlen($config['googleanalytics']) > 4)
		{
			$googleanalytics = '<script type="text/javascript">' . "\n";
			$googleanalytics = 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");' . "\n";
			$googleanalytics = 'document.write("\<script src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'>\<\/script>" );' . "\n";
			$googleanalytics = '</script>' . "\n";
			$googleanalytics = '<script type="text/javascript">' . "\n";
			$googleanalytics = 'var pageTracker = _gat._getTracker("' . $config['googleanalytics'] . '");' . "\n";
			$googleanalytics = 'pageTracker._initData();' . "\n";
			$googleanalytics = 'pageTracker._trackPageview();' . "\n";
			$googleanalytics = '</script>' . "\n";
			$tpl->assign("googleanalytics",$googleanalytics);
		}
		
		($hook = kleeja_run_hook('func_Saafooter')) ? eval($hook) : null; //run hook
		
		//show footer
		print $tpl->display("footer");
		
		//page analysis 
		if (isset($_GET['debug']) && $usrcp->admin())
		{
			kleeja_debug();
		}
		
		// THEN .. at finish, close sql connections
		$SQL->close();
}

/*
*to return file size in propriate format
* parameters : size: file of size in bites
*/
function Customfile_size($size)
{
	  $sizes = array(' B', ' KB', ' MB', ' GB', ' TB', 'PB', ' EB');
	  $ext = $sizes[0];
	  for ($i=1; (($i < count($sizes)) && ($size >= 1024)); $i++)
	  {
		   $size = $size / 1024;
		   $ext  = $sizes[$i];
	  }
	  $result	=	 round($size, 2).$ext;
	  ($hook = kleeja_run_hook('func_Customfile_size')) ? eval($hook) : null; //run hook
	  return  $result;
}

/*
*for recording who onlines now .. 
*prameters : none
*/
function KleejaOnline ()
{
		global $SQL,$usrcp,$dbprefix;
		
		// get information .. 
		$ip				= (getenv('HTTP_X_FORWARDED_FOR')) ? getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');
		$agent			= $_SERVER['HTTP_USER_AGENT'];
		$timeout		= 600; //seconds
		$time			= time();  
		$timeout2		= $time-$timeout;  
		#$username	= ( $usrcp->name() ) ?  (($usrcp->admin() )?  '<span style="color:blue;"><strong>' .$usrcp->name(). '</strong></span>' : $usrcp->name() ): '-1';
		$username		= ($usrcp->name()) ? $usrcp->name(): '-1';
		
		//
		//for stats 
		//
		if (strstr($agent, 'Google'))
		{
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "last_google='$time', google_num=google_num+1"
									);
				($hook = kleeja_run_hook('qr_update_google_lst_num')) ? eval($hook) : null; //run hook
				if (!$SQL->build($update_query)) die($lang['CANT_UPDATE_SQL']);
		}
		elseif (strstr($agent, 'Yahoo'))
		{
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "last_yahoo='$time', yahoo_num=yahoo_num+1"
									);
				($hook = kleeja_run_hook('qr_update_yahoo_lst_num')) ? eval($hook) : null; //run hook	
				if (!$SQL->build($update_query))	die($lang['CANT_UPDATE_SQL']);
		}
		
		//put another bots as a hook if you want !
		($hook = kleeja_run_hook('anotherbots_onlline_func')) ? eval($hook) : null; //run hook
		
		//---
		$query_on_id = array(
								'SELECT'	=> 'id',
								'FROM'		=> "{$dbprefix}online",
								'WHERE'		=> "ip='". $SQL->escape($ip) . "'"
							);
		($hook = kleeja_run_hook('qr_select_ip_onlline_func')) ? eval($hook) : null; //run hook					
		$result = $SQL->build($query_on_id);
			
		$who_here	= $SQL->num_rows($result);  
		
		if(!$who_here)
		{
			$insert_query = array(
								'INSERT'	=> 'ip, username, agent, time',
								'INTO'		=> "{$dbprefix}online",
								'VALUES'	=> "'$ip','$username','$agent','$time'"
								);
			($hook = kleeja_run_hook('qr_insert_ifnot_onlline_func')) ? eval($hook) : null; //run hook
			$SQL->build($insert_query);
		}
		else
		{
			$update_query = array(
								'UPDATE'=> "{$dbprefix}online",
								'SET'	=> "time='$time'",
								'WHERE'	=> "ip='". $SQL->escape($ip) . "'"
							);
			($hook = kleeja_run_hook('qr_update_ifis_onlline_func')) ? eval($hook) : null; //run hook
			if (!$SQL->build($update_query)) die($lang['CANT_UPDATE_SQL']);			
		}

		// i hate who online feature due to this step .. :( 
		$query_del = array(
						'DELETE'	=> "{$dbprefix}online",
						'WHERE'		=> "time < $timeout2"
							);
		($hook = kleeja_run_hook('qr_del_ifgo_onlline_func')) ? eval($hook) : null; //run hook									
		if (!$SQL->build($query_del))	die($lang['CANT_DELETE_SQL']);
		
		($hook = kleeja_run_hook('KleejaOnline_func')) ? eval($hook) : null; //run hook	

}#End function
	
/*
* visitors calculator
* parameters : none
*/
function visit_stats ()
{
		global $SQL,$usrcp,$dbprefix,$stat_today;
		
		$today = date("j");

		if ($today !=  $stat_today)
		{
			//counter yesterday .. and make today counter as 0 , then get date of today .. 
			$query = array(
						'SELECT'	=> 'counter_today',
						'FROM'		=> "{$dbprefix}stats"
						);
			($hook = kleeja_run_hook('qr_select_counters_ststs_func')) ? eval($hook) : null; //run hook					
			$result = $SQL->build($query);
			while($row=$SQL->fetch_array($result))
			{
				$yesterday_cout = $row['counter_today']; 
			}
			
			$update_query = array(
							'UPDATE'	=> "{$dbprefix}stats",
							'SET'		=> "counter_yesterday='$yesterday_cout', counter_today='0', today='$today' "
						);
			($hook = kleeja_run_hook('qr_update_counters_ststs_func')) ? eval($hook) : null; //run hook
			if ($SQL->build($update_query))
			{
				delete_cache('data_stats');
			}
			else
			{ 
				die($lang['CANT_UPDATE_SQL']);
			}	
			
		}
		
			//not registered as visitor yet ,, becuase visist mean one visit !  
			if (!$_SESSION['visitor'])
			{
					$update_query = array(
												'UPDATE'	=> "{$dbprefix}stats",
												'SET'		=> "counter_today=counter_today+1, counter_all=counter_all+1"
												);
					($hook = kleeja_run_hook('qr_update_countersall_ststs_func')) ? eval($hook) : null; //run hook	
					if ($SQL->build($update_query))
					{
						$_SESSION['visitor'] = true;
					}
					else
					{ 
						die($lang['CANT_UPDATE_SQL']);
					}	
			}
			
			($hook = kleeja_run_hook('visit_stats_func')) ? eval($hook) : null; //run hook	
}#end func
	
/*
*for ban ips .. 
*parameters : none
*/
function get_ban ()
{
		global $banss, $lang, $tpl, $text;
	
		//visitor ip now 
		$ip	=  (getenv('HTTP_X_FORWARDED_FOR')) ? getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');

		//now .. loop for banned ips 
		if (trim($banss) != '' && !empty($ip))
		{
			if (!is_array($banss))	$banss = array();
			
			foreach ($banss as $ip2)
			{
				//first .. replace all * with something good .
				$replaceIt = str_replace("*", '([0-9]{1,3})', $ip2);
				
				if ($ip == $ip2 || @eregi($replaceIt , $ip))
				{
					($hook = kleeja_run_hook('banned_get_ban_func')) ? eval($hook) : null; //run hook	
					kleeja_info($lang['U_R_BANNED'], $lang['U_R_BANNED']);
				}
			}
		}#empty	
		
		($hook = kleeja_run_hook('get_ban_func')) ? eval($hook) : null; //run hook	
}



/*convert xml codes to array  
* parameters : raw_xml : xml codes
*codes from mybb
*/ 
function xml_to_array($raw_xml) 
{
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 0);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		if(xml_parse_into_struct($parser, $raw_xml, $vals, $index) === 0)
		{
			return false;
		}
		$i = -1;
		return xml_get_children($vals, $i);
}

/*
 * xml_build_tag
* parameters : thisvals, vals, i, type
*codes from mybb
*/
function xml_build_tag($thisvals, $vals, &$i, $type)
{
		$tag['tag'] = $thisvals['tag'];
		if(isset($thisvals['attributes']))
		{
			$tag['attributes'] = $thisvals['attributes'];
		}

		if($type == "complete")
		{
			$tag['value'] = $thisvals['value'];
		}
		else
		{
			$tag = array_merge($tag, xml_get_children($vals, $i));
		}
		return $tag;
}
	
/*
*xml_get_children
*parameters: vals, i
*/
function xml_get_children($vals, &$i)
{
		$collapse_dups = 1;
		$index_numeric = 0;
		$children = array();

		if($i > -1 && isset($vals[$i]['value']))
		{
			$children['value'] = $vals[$i]['value'];
		}

		while(++$i < count($vals))
		{
			$type = $vals[$i]['type'];
			if($type == "cdata")
			{
				$children['value'] .= $vals[$i]['value'];
			}
			elseif($type == "complete" || $type == "open")
			{
				$tag = xml_build_tag($vals[$i], $vals, $i, $type);
				if($index_numeric)
				{
					$tag['tag']	= $vals[$i]['tag'];
					$children[]	= $tag;
				}
				else
				{
					$children[$tag['tag']][]	= $tag;
				}
			}
			elseif($type == "close")
			{
				break;
			}
		}
		if($collapse_dups)
		{
			foreach($children as $key => $value)
			{
				if(is_array($value) && (count($value) == 1))
				{
					$children[$key]	= $value[0];
				}
			}
		}
		return $children;
}



/*
* insert a new plugin from xml file
* parameters : contents : xml contents of plugin
*/
function creat_plugin_xml($contents) 
{
	global $dbprefix, $SQL, $lang, $config;

				$gtree = xml_to_array($contents);
				
				$tree				= $gtree['kleeja'];
				$plg_info			= $tree['info'];
				$plg_install		= $tree['install'];
				$plg_uninstall		= $tree['uninstall'];
				$plg_tpl			= $tree['templates'];		
				$plg_hooks			= $tree['hooks'];		
				$plg_langs			= $tree['langs'];		

				//important tags not exists 
				if(!isset($plg_info))
				{
					die($lang['ERR_XML_NO_G_TAGS']);
				}
				else
				{
					$plg_errors	=	array();
					//eval install code
					if (isset($plg_install) && trim($plg_install['value']) != '')
					{
						eval($plg_install['value']);
					}
					
					//some actions with tpls
					if(isset($plg_tpl))
					{
						//edit template
						if(isset($plg_tpl['edit']))
						{
							require "s_strings.php";
							$finder	=	new sa_srch;
							
							if(is_array($plg_tpl['edit']['template']))
							{
								if(array_key_exists("attributes",$plg_tpl['edit']['template']))
								{
									$plg_tpl['edit']['template'] = array($plg_tpl['edit']['template']);
								}
							}		
							
							foreach($plg_tpl['edit']['template'] as $temp)
							{
									$template_name			=	$SQL->real_escape($temp['attributes']['name']);
									$finder->find_word		=	$temp['find']['value'];
									$finder->another_word	=	$temp['action']['value'];
									switch($temp['action']['attributes']['type']):
										case 'add_after': $action_type =3; break;
										case 'add_after_same_line': $action_type =4; break;
										case 'add_before': $action_type =5; break;
										case 'add_before_same_line': $action_type =6; break;
										case 'replace_with': $action_type =1; break;
									endswitch;
									
									$style_id = (substr($template_name, 0, 6) == 'admin_') ? 0 : $config['style'];
									
									//get template content and do wut we have to do , then updated .. 
									$query = array(
													'SELECT'	=> 'template_content',
													'FROM'		=> "{$dbprefix}templates",
													'WHERE'		=>	"style_id='".intval($style_id)."' AND template_name='". $template_name ."'"
												);
									($hook = kleeja_run_hook('qr_select_tplcntedit_crtplgxml_func')) ? eval($hook) : null; //run hook
									
									$resultu = $SQL->build($query);
									if($SQL->num_rows($resultu) == 0)
									{
										$query['WHERE'] = "style_id='1' AND template_name='". $template_name ."'";
										$resultu = $SQL->build($query);
 									}
									
									$result	= $SQL->fetch_array($resultu);
							
									if(!$result['template_content'])	continue;
									
									$finder->text		=	$result['template_content'];
									$finder->do_search($action_type);
									
									if($finder->text != $result['template_content'])
									{
										//update
										$update_query = array(
																'UPDATE'	=> "{$dbprefix}templates",
																'SET'		=> "template_content = '". $SQL->real_escape($finder->text) ."'",
																'WHERE'		=>	"style_id='". intval($style_id) ."' AND template_name='". $template_name . "'"
															);
										($hook = kleeja_run_hook('qr_update_tplcntedit_crtplgxml_func')) ? eval($hook) : null; //run hook
										if ($SQL->build($update_query))
										{
												//delete cache ..
												delete_cache('tpl_' .$template_name);
										}
									}
								}
						}#end edit
							
							//new templates 
							if(isset($plg_tpl['new']))
							{
								
								if(is_array($plg_tpl['new']['template']))
								{
									if(array_key_exists("attributes",$plg_tpl['new']['template']))
									{
										$plg_tpl['new']['template'] = array($plg_tpl['new']['template']);
									}
								}		
							
								foreach($plg_tpl['new']['template'] as $temp)
								{
									$style_id = (substr($template_name, 0, 6) == 'admin_') ? 0 : $config['style'];
									$template_name		=	$SQL->real_escape($temp['attributes']['name']);
									$template_content	=	$SQL->real_escape($temp['value']);

									$insert_query = array(
																'INSERT'	=> 'style_id, template_name, template_content',
																'INTO'		=> "{$dbprefix}templates",
																'VALUES'	=> "'" . $style_id . "','$template_name', '$template_content'"
																);
									($hook = kleeja_run_hook('qr_insert_newtpls_crtplgxml_func')) ? eval($hook) : null; //run hook
									$SQL->build($insert_query);		
								}
							
							} #end new
					}#ens tpl
						
						//hooks
						if(isset($plg_hooks['hook']))
						{
							//insert in plugin table 
							$insert_query = array(
											'INSERT'	=> 'plg_name, plg_ver, plg_author, plg_dsc, plg_uninstall',
											'INTO'		=> "{$dbprefix}plugins",
											'VALUES'	=> "'".$SQL->escape($plg_info['plugin_name']['value'])."','".$SQL->escape($plg_info['plugin_version']['value'])."','".$SQL->escape($plg_info['plugin_author']['value'])."','".$SQL->escape($plg_info['plugin_description']['value'])."','".$SQL->real_escape($plg_uninstall['value'])."'"
											);
							($hook = kleeja_run_hook('qr_insert_plugininfo_crtplgxml_func')) ? eval($hook) : null; //run hook
							$SQL->build($insert_query);
		
							$new_plg_id	=	$SQL->insert_id();
							
							//then
								if(is_array($plg_hooks['hook']))
								{
									if(array_key_exists("attributes",$plg_hooks['hook']))
									{
										$plg_hooks['hook'] = array($plg_hooks['hook']);
									}
								}
								foreach($plg_hooks['hook'] as $hk)
								{

									$hook_for			=	$SQL->real_escape($hk['attributes']['name']);
									$hk_value			=	$SQL->real_escape($hk['value']);

									$insert_query = array(
														'INSERT'	=> 'plg_id, hook_name, hook_content',
														'INTO'		=> "{$dbprefix}hooks",
														'VALUES'	=> "'". $new_plg_id ."','".$hook_for."', '".$hk_value."'"
														);
									($hook = kleeja_run_hook('qr_insert_hooks_crtplgxml_func')) ? eval($hook) : null; //run hook
									$SQL->build($insert_query);		
								}
								//delete cache ..
								delete_cache('data_hooks');
						}
						
					
					if(sizeof($plg_errors)<1) 
					{
						return true;
					}
					else 
					{
						return $plg_errors;
					}
				}
					
				($hook = kleeja_run_hook('creat_plugin_xml_func')) ? eval($hook) : null; //run hook
				return false;
}	  
	    
/*
*run hooks of kleeja
* parameter : hook_name: name of hook or place that will run at.
*/
function kleeja_run_hook ($hook_name)
{
	global $all_plg_hooks;

	if(defined('STOP_HOOKS') || !isset($all_plg_hooks[$hook_name])) return false;

	return implode("\n", $all_plg_hooks[$hook_name]);
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
				print $tpl->display('info');
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
function kleeja_err($msg,$title='', $exit=true)
{
	global $text, $tpl;
	
				($hook = kleeja_run_hook('kleeja_err_func')) ? eval($hook) : null; //run hook
				
				// assign {text} in err template
				$text	= $msg;
				//header
				Saaheader($title);
				//show tpl
				print $tpl->display('err');
				//footer
				Saafooter();
				
				if ($exit)
				{
					exit();
				}
}

/*
* print inforamtion message in admin panel
* parameters : msg : text that will show as inforamtion
					title : <title>title of page</title>
					exit : stop script after showing msg 
*/
function kleeja_adm_info($msg,$title='', $exit=true)
{
	global $text, $tpl;
	
			($hook = kleeja_run_hook('kleeja_adm_info_func')) ? eval($hook) : null; //run hook
				
			// assign {text} in admin_info template	
			$text	= $msg;
				
			//header
			print $tpl->display("admin_header");
				//info tpl
				print $tpl->display('admin_info');
			//footer
			print $tpl->display("admin_footer");
					
			if ($exit)
			{
					exit();
			}			
}

/*
* print error message in admin panel
* parameters : msg : text that will show as error message
					title : <title>title of page</title>
					exit : stop script after showing msg 
*/
function kleeja_adm_err($msg, $title='', $exit=true)
{
	global $text, $tpl;
	
				($hook = kleeja_run_hook('kleeja_adm_err_func')) ? eval($hook) : null; //run hook
				
				// assign {text} in admin_info template	
				$text	= $msg;
				//header
				print $tpl->display("admin_header");
					//err tpl
					print $tpl->display('admin_err');
				//footer
				print $tpl->display("admin_footer");
				
				if ($exit)
				{
					exit();
				}
}

/*
* return current page 
* parameters : none
*/
function kleeja_get_page ()
{

	($hook = kleeja_run_hook('kleeja_get_page_func')) ? eval($hook) : null; //run hook

	if(isset($_SERVER['REQUEST_URI']))
	{
		$location = $_SERVER['REQUEST_URI'];
	}
	elseif(isset($ENV_['REQUEST_URI']))
	{
		$location = $ENV['REQUEST_URI'];
	}
	else
	{
		if(isset($_SERVER['PATH_INFO']))
		{
			$location = $_SERVER['PATH_INFO'];
		}
		elseif(isset($_ENV['PATH_INFO']))
		{
			$location = $_SERVER['PATH_INFO'];
		}
		elseif(isset($_ENV['PHP_SELF']))
		{
			$location = $_ENV['PHP_SELF'];
		}
		else
		{
			$location = $_SERVER['PHP_SELF'];
		}
		if(isset($_SERVER['QUERY_STRING']))
		{
			$location .= "?".$_SERVER['QUERY_STRING'];
		}
		elseif(isset($_ENV['QUERY_STRING']))
		{
			$location = "?".$_ENV['QUERY_STRING'];
		}
	}

	return $location;
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
					$debug_output = 'Memory Usage : <i>' . $memory_usage . '</i>';
				}
		}
		
		//thrn show it
		echo '<br />';
		echo '<fieldset  dir="ltr" style="background:white"><legend style="font-family: Arial; color:red"><em>[Page Analysis]</em></legend>';
		echo '<p>&nbsp;</p>';
		echo '<p><h2><strong>General Information :</strong></h2></p>';
		echo '<p>Gzip : <i>' . (($do_gzip_compress !=0 )?  "Enabled" : "Disabled") . '</i></p>';
		echo '<p>Queries Number :<i> ' .  $SQL->query_num . ' </i></p>';
		echo '<p>Hook System :<i> ' .  ((!defined('STOP_HOOKS'))?  "Enabled" : "Disabled"). ' </i></p>';
		echo '<p>Active Hooks :<i> ' .  sizeof($all_plg_hooks). ' </i></p>';
		echo '<p>' . $debug_output . '</p>';
		echo '<p>&nbsp;</p>';
		echo '<p><h2><strong><em>SQL</em> Information :</strong></h2></p> ';
		
		if(is_array($SQL->debugr))
		{ 
			foreach($SQL->debugr as $key=>$val)
			{
				echo '<fieldset name="sql"  dir="ltr" style="background:white"><legend><em>query # [' . ($key+1) . '</em>]</legend> ';
				echo '<textarea style="font-family:Courier New,monospace;width:99%; background:#F4F4F4" rows="5" cols="10">' . $val[0] . '';
				echo '</textarea>	<br />';
				echo 'Duration :' . $val[1] . ''; 
				echo '</fieldset>';
				echo '<br /><br />';
			}
		}
		else
		{
			echo '<p><b>NO SQLs</b></p>';
		}
		
		echo '<p>&nbsp;</p><p><h2><strong><em>HOOK</em> Information :</strong></h2></p> ';
		
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
			echo '<p><b>NO-HOOKS</b></p>';
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
		echo '</body>';
		echo '</html>';
		exit();
}




/*
* send email
*/
function send_mail($to, $body, $subject, $fromaddress, $fromname,$bcc='')
{
		$eol="\r\n";
		$headers	='';
		$headers .= 'From: ' . $fromname . ' <' . $fromaddress . '>' . $eol;
		$headers .= 'Sender: ' . $fromname . ' <' . $fromaddress . '>' . $eol;
		if (!empty($bcc)) $headers .= 'Bcc: ' . $bcc . $eol;
		$headers .= 'Reply-To: ' . $fromaddress . $eol;
		$headers .= 'Return-Path: <' . $fromaddress . '>' . $eol;
		$headers .= 'MIME-Version: 1.0' . $eol;
		$headers .= 'Message-ID: <' . md5(uniqid(time())) . '@' . $fromname . '>' . $eol;
		$headers .= 'Date: ' . date('r', time()) . $eol;
		$headers .= 'Content-Type: text/plain; charset=UTF-8' . $eol; // format=flowed
		$headers .= 'Content-Transfer-Encoding: 8bit' . $eol; // 7bit
		$headers .= 'X-Priority: 3' . $eol;
		$headers .= 'X-MSMail-Priority: Normal' . $eol;
		$headers .= 'X-Mailer: : PHP v' . phpversion() . $eol;
		$headers .= 'X-MimeOLE: kleeja' . $eol;
		
		($hook = kleeja_run_hook('kleeja_send_mail')) ? eval($hook) : null; //run hook
		
		$mail_sent = @mail($to, $subject, preg_replace("#(?<!\r)\n#s", "\n", $body), $headers);
  
  return $mail_sent;
}


/*
* get remote files
* parameters : url : link of file
*/
function fetch_remote_file($url)
{

	($hook = kleeja_run_hook('kleeja_fetch_remote_file_func')) ? eval($hook) : null; //run hook

	if(function_exists("curl_init"))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	else if(function_exists("fsockopen"))
	{
	    $url_parsed = parse_url($url);
	    $host = $url_parsed["host"];
	    $port = ($url_parsed["port"] == 0) ? 80 : $url_parsed["port"];
		$path = $url_parsed["path"];
	    
		if ($url_parsed["query"] != "")
		{
			$path .= "?" . $url_parsed["query"];
		}
	
	    $out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";

	    $fp = fsockopen($host, $port, $errno, $errstr, 30);

	    fwrite($fp, $out);
	    $body = false;
	    while (!feof($fp))
		{
	        $s = fgets($fp, 1024);
	        if ($body)
			{
	            $in .= $s;
	        }
			
			if ($s == "\r\n")
			{
				$body = true;
			}
	    }
	   
	    fclose($fp);
	    return $in;
	}
	else
	{
		return implode("", file($url));
	}
}

/*
* for delete changes in any template
*paramters : array : this array will include name of template and code
							will delete as name=>code
*/
function delete_change_styles($array)
{
	global $dbprefix, $SQL, $lang, $config;
	
	if(!is_array($array)) return false;
	require "s_strings.php";
	$finder	=	new sa_srch;

	foreach($array as $tplname=>$codes)
	{
		$finder->find_word		=	$codes;
		$finder->another_word	=	'<!-- auto delete kleeja.com -->';
		//get template content and do wut we have to do , then updated .. 
		$query = array(
								'SELECT'	=> 'template_content',
								'FROM'		=> "{$dbprefix}templates",
								'WHERE'	=>	"style_id='".intval($config['style'])."' AND template_name='". $tplname ."'"
							);
		($hook = kleeja_run_hook('qr_select_delete_change_styles_func')) ? eval($hook) : null; //run hook
		$result	= $SQL->fetch_array($SQL->build($query));
		//no conents					
		if(!$result['template_content']) continue;
		
		$finder->text = $result['template_content'];
		$finder->do_search(1);
									
		if($finder->text != $result['template_content'])
		{
					//update
					$update_query = array(
												'UPDATE'	=> "{$dbprefix}templates",
												'SET'		=> "template_content = '". $SQL->real_escape($finder->text) ."'",
												'WHERE'		=>	"style_id='". intval($config['style']) ."' AND template_name='". $tplname . "'"
											);
					($hook = kleeja_run_hook('qr_update_delete_change_styles_func')) ? eval($hook) : null; //run hook
					if ($SQL->build($update_query))
					{
						//delete cache ..
						delete_cache($config['style'] . '_' .$tplname);
					}
		}

	}
}#end fun


function kleeja_mime_groups($return_one = false)
{
	global $lang;
	
		$s = array(
					0 => array('name' => ''),
					1 => array('name' => $lang['N_IMGS']),
					2 => array('name' => $lang['N_ZIPS']),
					3 => array('name' => $lang['N_TXTS']),
					4 => array('name' => $lang['N_DOCS']),
					5 => array('name' => $lang['N_RM']),
					6 => array('name' => $lang['N_WM']),
					7 => array('name' => $lang['N_SWF']),
					8 => array('name' => $lang['N_QT']),
					9 => array('name' => $lang['N_OTHERFILE']),
			);
		
		($hook = kleeja_run_hook('kleeja_mime_groups_func')) ? eval($hook) : null; //run hook
		
		return ($return_one != false ? $s[$return_one] : $s);
}

/*
*admin function for extensions types
*parameters : id : type id, default : default or not
*/
function ch_g ($name_of_select, $g_id, $return_name = false)
{
		global $lang;
		
		
		$s = kleeja_mime_groups(($return_name ?  $g_id : false));
		
		//return name if he want
		if($return_name != false) 
		{
			return $s['name'];
		}
		
		$show = "<select name=\"{$name_of_select}\">";
		
		for($i=1;$i<sizeof($s);$i++)
		{
			$selected = ($default == $i)? "selected=\"selected\"" : "";
			$show .= "<option $selected value=\"$i\">" . $s[$i]['name'] . "</option>";
		}
		
		$show .="</select>";
		
		($hook = kleeja_run_hook('ch_g_func')) ? eval($hook) : null; //run hook
		return $show;
}  

//1rc6+ for check file mime
//this function is under TESTING !
// you can share your experinces with us from our site kleeja.com...
function kleeja_check_mime ($mime, $group_id, $temp)
{
	($hook = kleeja_run_hook('kleeja_check_mime_func')) ? eval($hook) : null; //run hook
	
	//this function work with images only 
	if($group_id != 1) return true;
	
	$s_items = @explode(':', 'image:png:jpg:tif:tga:targa');
	$return = false;
	foreach($s_items as $r)
	{
		if(strpos($mime, $r) !== false)
		{
			$return = true;
			break;
		}
	}
	
	//onther check
	$w = @getimagesize($temp);
	$return =  ($w && (strpos($w['mime'], 'image') !== false)) ? true : false;

	//another check
	if($return == true)
	{
		//check for bad things inside files ... i hate those kids; sorry , i mean i dont like them
		//what if the  size is big ! ! 
		$maybe_bad_codes_are = array('<?', '<%', '<script', 'zend');
		$data = file_get_contents($temp);
		foreach($maybe_bad_codes_are as $i)
		{
			if(strpos(strtolower($data), $i) !== false)
			{
				$return = false;
				break;
			}
		}
	}
	
	return $return;
}

//delete cache
function delete_cache($name, $all=false, $deep = false)
{
	//we have delete it by any method !
	
	$path_to_cache = ($deep ? '.' : '') . './cache';
	
	//unlink
	if(!function_exists('unlink'))
	{
		big_error('No unlink function!', '<b>unlink</b> function Doesnt exists , That mean we can not delete any file and cache. <br/> You have enable this feature .');
	}
	
	if($all)
	{
		$dh = opendir($path_to_cache);
		while (($file = readdir($dh)) !== false)
		{
			if($file != "." && $file != ".." && $file != ".htaccess" && $file != "index.html")
			{
				$del = @unlink ($path_to_cache . "/" . $file);
			}
		}
		closedir($dh);
	}
	else
	{
		$del = true;
		$name = str_replace('.php', '', $name);
		if (file_exists($path_to_cache . "/" . $name . '.php'))
		{
			$del = @unlink ($path_to_cache . "/" . $name . '.php');
		}
		
	}
	
	return $del;
}


//1rc6+ get mime header
function get_mime_for_header($ext)
{
	$mime_types = array(
		"323" => "text/h323",
		"rar"=> "application/x-rar-compressed",
		"acx" => "application/internet-property-stream",
		"ai" => "application/postscript",
		"aif" => "audio/x-aiff",
		"aifc" => "audio/x-aiff",
		"aiff" => "audio/x-aiff",
		"asf" => "video/x-ms-asf",
		"asr" => "video/x-ms-asf",
		"asx" => "video/x-ms-asf",
		"au" => "audio/basic",
		"avi" => "video/x-msvideo",
		"axs" => "application/olescript",
		"bas" => "text/plain",
		"bcpio" => "application/x-bcpio",
		"bin" => "application/octet-stream",
		"bmp" => "image/bmp",
		"c" => "text/plain",
		"cat" => "application/vnd.ms-pkiseccat",
		"cdf" => "application/x-cdf",
		"cer" => "application/x-x509-ca-cert",
		"class" => "application/octet-stream",
		"clp" => "application/x-msclip",
		"cmx" => "image/x-cmx",
		"cod" => "image/cis-cod",
		"cpio" => "application/x-cpio",
		"crd" => "application/x-mscardfile",
		"crl" => "application/pkix-crl",
		"crt" => "application/x-x509-ca-cert",
		"csh" => "application/x-csh",
		"css" => "text/css",
		"dcr" => "application/x-director",
		"der" => "application/x-x509-ca-cert",
		"dir" => "application/x-director",
		"dll" => "application/x-msdownload",
		"dms" => "application/octet-stream",
		"doc" => "application/msword",
		"dot" => "application/msword",
		"dvi" => "application/x-dvi",
		"dxr" => "application/x-director",
		"eps" => "application/postscript",
		"etx" => "text/x-setext",
		"evy" => "application/envoy",
		"exe" => "application/octet-stream",
		"fif" => "application/fractals",
		"flr" => "x-world/x-vrml",
		"gif" => "image/gif",
		"gtar" => "application/x-gtar",
		"gz" => "application/x-gzip",
		"h" => "text/plain",
		"hdf" => "application/x-hdf",
		"hlp" => "application/winhlp",
		"hqx" => "application/mac-binhex40",
		"hta" => "application/hta",
		"htc" => "text/x-component",
		"htm" => "text/html",
		"html" => "text/html",
		"htt" => "text/webviewhtml",
		"ico" => "image/x-icon",
		"ief" => "image/ief",
		"iii" => "application/x-iphone",
		"ins" => "application/x-internet-signup",
		"isp" => "application/x-internet-signup",
		"jfif" => "image/pipeg",
		"jpe" => "image/jpeg",
		"jpeg" => "image/jpeg",
		"jpg" => "image/jpeg",
		"js" => "application/x-javascript",
		"latex" => "application/x-latex",
		"lha" => "application/octet-stream",
		"lsf" => "video/x-la-asf",
		"lsx" => "video/x-la-asf",
		"lzh" => "application/octet-stream",
		"m13" => "application/x-msmediaview",
		"m14" => "application/x-msmediaview",
		"m3u" => "audio/x-mpegurl",
		"man" => "application/x-troff-man",
		"mdb" => "application/x-msaccess",
		"me" => "application/x-troff-me",
		"mht" => "message/rfc822",
		"mhtml" => "message/rfc822",
		"mid" => "audio/mid",
		"mny" => "application/x-msmoney",
		"mov" => "video/quicktime",
		"movie" => "video/x-sgi-movie",
		"mp2" => "video/mpeg",
		"mp3" => "audio/mpeg",
		"mp4" => "video/mp4",
		"m4a" => "audio/mp4",
		"mpa" => "video/mpeg",
		"mpe" => "video/mpeg",
		"mpeg" => "video/mpeg",
		"mpg" => "video/mpeg",
		"amr" => "audio/3gpp",
		"mpp" => "application/vnd.ms-project",
		"mpv2" => "video/mpeg",
		"ms" => "application/x-troff-ms",
		"mvb" => "application/x-msmediaview",
		"nws" => "message/rfc822",
		"oda" => "application/oda",
		"p10" => "application/pkcs10",
		"p12" => "application/x-pkcs12",
		"p7b" => "application/x-pkcs7-certificates",
		"p7c" => "application/x-pkcs7-mime",
		"p7m" => "application/x-pkcs7-mime",
		"p7r" => "application/x-pkcs7-certreqresp",
		"p7s" => "application/x-pkcs7-signature",
		"pbm" => "image/x-portable-bitmap",
		"pdf" => "application/pdf",
		"pfx" => "application/x-pkcs12",
		"pgm" => "image/x-portable-graymap",
		"pko" => "application/ynd.ms-pkipko",
		"pma" => "application/x-perfmon",
		"pmc" => "application/x-perfmon",
		"pml" => "application/x-perfmon",
		"pmr" => "application/x-perfmon",
		"pmw" => "application/x-perfmon",
		"pnm" => "image/x-portable-anymap",
		"pot" => "application/vnd.ms-powerpoint",
		"ppm" => "image/x-portable-pixmap",
		"pps" => "application/vnd.ms-powerpoint",
		"ppt" => "application/vnd.ms-powerpoint",
		"prf" => "application/pics-rules",
		"ps" => "application/postscript",
		"pub" => "application/x-mspublisher",
		"qt" => "video/quicktime",
		"ra" => "audio/x-pn-realaudio",
		"ram" => "audio/x-pn-realaudio",
		"ras" => "image/x-cmu-raster",
		"rgb" => "image/x-rgb",
		"rmi" => "audio/mid",
		"roff" => "application/x-troff",
		"rtf" => "application/rtf",
		"rtx" => "text/richtext",
		"scd" => "application/x-msschedule",
		"sct" => "text/scriptlet",
		"setpay" => "application/set-payment-initiation",
		"setreg" => "application/set-registration-initiation",
		"sh" => "application/x-sh",
		"shar" => "application/x-shar",
		"sit" => "application/x-stuffit",
		"snd" => "audio/basic",
		"spc" => "application/x-pkcs7-certificates",
		"spl" => "application/futuresplash",
		"src" => "application/x-wais-source",
		"sst" => "application/vnd.ms-pkicertstore",
		"stl" => "application/vnd.ms-pkistl",
		"stm" => "text/html",
		"svg" => "image/svg+xml",
		"sv4cpio" => "application/x-sv4cpio",
		"sv4crc" => "application/x-sv4crc",
		"t" => "application/x-troff",
		"tar" => "application/x-tar",
		"tcl" => "application/x-tcl",
		"tex" => "application/x-tex",
		"texi" => "application/x-texinfo",
		"texinfo" => "application/x-texinfo",
		"tgz" => "application/x-compressed",
		"tif" => "image/tiff",
		"tiff" => "image/tiff",
		"tr" => "application/x-troff",
		"trm" => "application/x-msterminal",
		"tsv" => "text/tab-separated-values",
		"txt" => "text/plain",
		"uls" => "text/iuls",
		"ustar" => "application/x-ustar",
		"vcf" => "text/x-vcard",
		"vrml" => "x-world/x-vrml",
		"wav" => "audio/x-wav",
		"wcm" => "application/vnd.ms-works",
		"wdb" => "application/vnd.ms-works",
		"wks" => "application/vnd.ms-works",
		"wmf" => "application/x-msmetafile",
		"wps" => "application/vnd.ms-works",
		"wri" => "application/x-mswrite",
		"wrl" => "x-world/x-vrml",
		"wrz" => "x-world/x-vrml",
		"xaf" => "x-world/x-vrml",
		"xbm" => "image/x-xbitmap",
		"xla" => "application/vnd.ms-excel",
		"xlc" => "application/vnd.ms-excel",
		"xlm" => "application/vnd.ms-excel",
		"xls" => "application/vnd.ms-excel",
		"xlt" => "application/vnd.ms-excel",
		"xlw" => "application/vnd.ms-excel",
		"xof" => "x-world/x-vrml",
		"xpm" => "image/x-xpixmap",
		"xwd" => "image/x-xwindowdump",
		"z" => "application/x-compress",
		"zip" => "application/zip",
		"3gpp"=> "video/3gpp",
		"3gp" => "video/3gpp",
		"3gpp2" => "video/3gpp2",
		"3g2" => "video/3gpp2",
		"midi" => "audio/midi",
		"pmd" => "application/x-pmd",
		"jar" => "application/java-archive",
		"jad" => "text/vnd.sun.j2me.app-descriptor",
		//add more mime here
	);
	
	//return mime
	$ext = strtolower($ext);
    if(in_array($ext, array_keys($mime_types)))
    {
		return  $mime_types[$ext];
	}
	else
	{
    	return 'application/force-download';  
	}	
}


//
//include lang
//
function get_lang($name, $folder = '')
{
	global $config, $root_path, $lang;
	
	
	$name = str_replace('..', '', $name);
	if($folder != '')
	{
		$folder = str_replace('..', '', $folder);
		$name = $folder . '/' . $name;
	}
	
	$path = $root_path . 'lang/' . $config['language'] . '/' . str_replace('.php', '', $name) . '.php';
	
	if(file_exists($path))
	{
		include_once($path);
	}
	else
	{
		big_error('There is no language file in the current path', '' . $path . ' not found');
	}

	return true;

}

?>

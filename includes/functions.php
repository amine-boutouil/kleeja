<?php
#########################################
#						Kleeja
#
# Filename : functions.php
# purpose :  functions for all script. the important feature of kleeja
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author$ , $Rev$,  $Date::                           $
#########################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
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
		global $SQL, $usrcp, $dbprefix, $config, $klj_session;
		
		// get information .. 
		$ip				= get_ip();
		$agent			= $SQL->escape($_SERVER['HTTP_USER_AGENT']);
		$timeout		= 600; //seconds //10 min
		$time			= time();  
		$timeout2		= $time-$timeout;  
		$username		= ($usrcp->name()) ? $usrcp->name(): '-1';
		$session		= $klj_session;
		
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
				$SQL->build($update_query);

		}
		elseif (strstr($agent, 'Yahoo'))
		{
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "last_yahoo='$time', yahoo_num=yahoo_num+1"
									);
				($hook = kleeja_run_hook('qr_update_yahoo_lst_num')) ? eval($hook) : null; //run hook	
				$SQL->build($update_query);
		}
		
		//put another bots as a hook if you want !
		($hook = kleeja_run_hook('anotherbots_onlline_func')) ? eval($hook) : null; //run hook
		
		//---
		if(!empty($ip) && !empty($agent) && !empty($session))
		{
			$rep_query = array(
								'REPLACE'	=> 'ip, username, agent, time, session',
								'INTO'		=> "{$dbprefix}online",
								'VALUES'	=> "'$ip','$username','$agent','$time','$session'",
								'UNIQUE'	=>  "session='$session'"
							);
			($hook = kleeja_run_hook('qr_rep_ifnot_onlline_func')) ? eval($hook) : null; //run hook
			$SQL->build($rep_query);
		}

		//clean online table
		if((time() - $config['last_online_time_update']) >= 3600)
		{
			$query_del = array(
							'DELETE'	=> "{$dbprefix}online",
							'WHERE'		=> "time < '$timeout2'"
						);
			($hook = kleeja_run_hook('qr_del_ifgo_onlline_func')) ? eval($hook) : null; //run hook									
			$SQL->build($query_del);
			
			//update last_online_time_update 
			update_config('last_online_time_update', time());
		}
		
		($hook = kleeja_run_hook('KleejaOnline_func')) ? eval($hook) : null; //run hook	

}#End function
	

/*
*for ban ips .. 
*parameters : none
*/
function get_ban ()
{
		global $banss, $lang, $tpl, $text;
	
		//visitor ip now 
		$ip	= get_ip();

		
		//now .. loop for banned ips 
		if (is_array($banss) && !empty($ip))
		{
			foreach ($banss as $ip2)
			{
				$ip2 = trim($ip2);
				
				if(empty($ip2))
				{
					continue;
				}
				
				//first .. replace all * with something good .
				$replace_it = str_replace("*", '([0-9]{1,3})', $ip2);
				$replace_it = str_replace(".", '\.', $replace_it);
			
				if ($ip == $ip2 || @preg_match('/' . preg_quote($replace_it, '/') . '/i', $ip))
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
	global $dbprefix, $SQL, $lang, $config, $STYLE_PATH_ADMIN , $STYLE_PATH, $root_path, $olang;

				$gtree = xml_to_array($contents);
				
				$tree				= empty($gtree['kleeja']) ? null : $gtree['kleeja'];
				$plg_info			= empty($tree['info']) ? null : $tree['info'];
				$plg_install		= empty($tree['install']) ? null : $tree['install'];
				$plg_uninstall		= empty($tree['uninstall']) ? null : $tree['uninstall'];
				$plg_tpl			= empty($tree['templates']) ? null : $tree['templates'];		
				$plg_hooks			= empty($tree['hooks']) ? null : $tree['hooks'];		
				$plg_langs			= empty($tree['langs']) ? null : $tree['langs'];
				$plg_updates		= empty($tree['updates']) ? null : $tree['updates'];

				//important tags not exists 
				if(empty($plg_info))
				{
					big_error('Error',$lang['ERR_XML_NO_G_TAGS']);
				}

				if(!empty($plg_info['plugin_kleeja_version']['value']) && version_compare(strtolower($plg_info['plugin_kleeja_version']['value']), strtolower(KLEEJA_VERSION), '>=') == false)
				{
					big_error('Error', $lang['PLUGIN_N_CMPT_KLJ']);
				}

					$plg_errors	=	array();
					$plg_new = true;
					
					$plugin_name = preg_replace("/[^a-z0-9-_]/", "-", strtolower($plg_info['plugin_name']['value']));
					
					//is this plugin exists before ! 
					$is_query = array(
										'SELECT'	=> 'plg_id, plg_name, plg_ver',
										'FROM'		=> "{$dbprefix}plugins",
										'WHERE'		=> 'plg_name="' . $plugin_name . '"' 
										);
					($hook = kleeja_run_hook('qr_chk_plginfo_crtplgxml_func')) ? eval($hook) : null; //run hook
					$res = $SQL->build($is_query);
					if($SQL->num_rows($res))
					{
						//omg, it's not new one ! 
						//let's see if it same version
						$plg_new = false;
						$cur_ver = $SQL->fetch_array($res);
						$plg_id = $cur_ver['plg_id'];
						$cur_ver = $cur_ver['plg_ver'];
						$new_ver = $SQL->escape($plg_info['plugin_version']['value']);
						if (version_compare(strtolower($cur_ver), strtolower($new_ver), '>='))
						{
							return 'xyz';
						}
						else if (!empty($plg_updates))
						{
							//delete hooks !
							$query_del = array(
											'DELETE'	=> "{$dbprefix}hooks",
											'WHERE'		=> "plg_id='" . $plg_id . "'"
											);		
											
							$SQL->build($query_del);
							
							if(is_array($plg_updates['update']))
							{
								if(array_key_exists("attributes", $plg_updates['update']))
								{
										$plg_updates['update'] = array($plg_updates['update']);
								}
							}
								
							foreach($plg_updates['update'] as $up)
							{
								if (version_compare(strtolower($cur_ver), strtolower($up['attributes']['to']), '<'))
								{
									eval($up['value']);
								}
							}
						}
					}
					
					
					
					//eval install code
					if (isset($plg_install) && trim($plg_install['value']) != '' && $plg_new)
					{
						eval($plg_install['value']);
					}
					
					
					//
					$cached_instructions = array();
					
					//some actions with tpls
					if(isset($plg_tpl))
					{
						//edit template
						if(isset($plg_tpl['edit']))
						{
							include_once "s_strings.php";
							$finder	= new sa_srch;
							
							if(is_array($plg_tpl['edit']['template']))
							{
								if(array_key_exists("attributes", $plg_tpl['edit']['template']))
								{
									$plg_tpl['edit']['template'] = array($plg_tpl['edit']['template']);
								}
							}		
							
							foreach($plg_tpl['edit']['template'] as $temp)
							{
									$template_name			= $SQL->real_escape($temp['attributes']['name']);
									$finder->find_word		= $temp['find']['value'];
									$finder->another_word	= $temp['action']['value'];
									switch($temp['action']['attributes']['type']):
										case 'add_after': $action_type =3; break;
										case 'add_after_same_line': $action_type =4; break;
										case 'add_before': $action_type =5; break;
										case 'add_before_same_line': $action_type =6; break;
										case 'replace_with': $action_type =1; break;
									endswitch;
									
									$style_path = (substr($template_name, 0, 6) == 'admin_') ? $STYLE_PATH_ADMIN : $STYLE_PATH;
									
									//if template not found and default style is there and not admin tpl
									$template_path = $style_path . $template_name . '.html';
									if(!file_exists($template_path)) 
									{
										if(file_exists($style_path . 'depend_on.txt'))
										{
											$depend_on = file_get_contents($style_path . 'depend_on.txt');
											$template_path_alternative = str_replace('/' . $config['style'] . '/', '/' . trim($depend_on) . '/', $template_path);
											if(file_exists($template_path_alternative))
											{
												$template_path = $template_path_alternative;
											}
										}
										else if($config['style'] != 'default' && !$is_admin_template)
										{
											$template_path_alternative = str_replace('/' . $config['style'] . '/', '/default/', $template_path);
											if(file_exists($template_path_alternative))
											{
												$template_path = $template_path_alternative;
											}
										}
									}
									
									if(file_exists($template_path))
										$d_contents = file_get_contents($template_path);
									else
										$d_contents = '';
									
									$finder->text = trim($d_contents);
									$finder->do_search($action_type);
									
									if($d_contents  != '' && $finder->text != $d_contents && is_writable($style_path))
									{
										//update
										$filename = @fopen($style_path . $template_name . '.html', 'w');
										fwrite($filename, $finder->text);
										fclose($filename);
															
										($hook = kleeja_run_hook('op_update_tplcntedit_crtplgxml_func')) ? eval($hook) : null; //run hook
	
										//delete cache ..
										delete_cache('tpl_' .$template_name);
									}
									else
									{
										$cached_instructions[$template_name] = array(
																		'action'		=> $temp['action']['attributes']['type'], 
																		'find'			=> $temp['find']['value'],
																		'action_text'	=> $temp['action']['value'],
																		);
										
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
									$style_path = (substr($template_name, 0, 6) == 'admin_') ? $STYLE_PATH_ADMIN : $STYLE_PATH;
									$template_name		= $temp['attributes']['name'];
									$template_content	= trim($temp['value']);
									
									if(is_writable($style_path))
									{
										$filename = @fopen($style_path . $template_name . '.html', 'w');
										fwrite($filename, $template_content);
										fclose($filename);
									}
									else
									{
										$cached_instructions[$template_name] = array(
																		'action'		=> 'new', 
																		'find'			=> '',
																		'action_text'	=> $template_content,
																		);
									}
									
									($hook = kleeja_run_hook('op_insert_newtpls_crtplgxml_func')) ? eval($hook) : null; //run hook
									
								}
							
							} #end new
					}#ens tpl
						
						//hooks
						if(isset($plg_hooks['hook']))
						{
							$plugin_author = strip_tags($plg_info['plugin_author']['value'], '<a><span>');
							$plugin_author = $SQL->real_escape($plugin_author);
							if($plg_new)
							{
								//insert in plugin table 
								$insert_query = array(
												'INSERT'	=> 'plg_name, plg_ver, plg_author, plg_dsc, plg_uninstall',
												'INTO'		=> "{$dbprefix}plugins",
												'VALUES'	=> "'" . $SQL->escape($plugin_name) . "','" . $SQL->escape($plg_info['plugin_version']['value']) . "','" . $plugin_author . "','" . $SQL->escape($plg_info['plugin_description']['value']) . "','" . $SQL->real_escape($plg_uninstall['value']) . "'"
												);
								($hook = kleeja_run_hook('qr_insert_plugininfo_crtplgxml_func')) ? eval($hook) : null; //run hook
								$SQL->build($insert_query);
			
								$new_plg_id	=	$SQL->insert_id();
							}
							else 
							{
								$update_query = array(
												'UPDATE'	=> "{$dbprefix}plugins",
												'SET'		=> 'plg_ver="' . $new_ver . '", plg_author="' . $plugin_author . '", plg_dsc="' . $SQL->escape($plg_info['plugin_description']['value']) . '", plg_uninstall="' . $SQL->real_escape($plg_uninstall['value']) . '"',
												'WHERE'		=> "plg_id='" . $plg_id . "'"
											);
								$SQL->build($update_query);
								
								$new_plg_id	=	$plg_id;
							}
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

									$hook_for =	$SQL->real_escape($hk['attributes']['name']);
									$hk_value =	$SQL->real_escape($hk['value']);

									$insert_query = array(
														'INSERT'	=> 'plg_id, hook_name, hook_content',
														'INTO'		=> "{$dbprefix}hooks",
														'VALUES'	=> "'" . $new_plg_id . "','" . $hook_for . "', '" . $hk_value . "'"
														);
									($hook = kleeja_run_hook('qr_insert_hooks_crtplgxml_func')) ? eval($hook) : null; //run hook
									$SQL->build($insert_query);		
								}
								//delete cache ..
								delete_cache('data_hooks');
						}
						
					
					if(sizeof($plg_errors)<1) 
					{
						//add cached instuctions to cache if there
						if(sizeof($cached_instructions) > 0)
						{
							//fix
							if(file_exists($root_path . 'cache/styles_cached.php'))
							{
								$cached_content = file_get_contents($root_path . 'cache/styles_cached.php');
								$cached_content = base64_decode($cached_content);
								$cached_content = unserialize($cached_content);
								$cached_instructions += $cached_content;
							}
							$filename = @fopen($root_path . 'cache/styles_cached.php' , 'w');
							fwrite($filename, base64_encode(serialize($cached_instructions)));
							fclose($filename);
						}
						
						return $plg_new ? true : 'upd';
					}
					else 
					{
						return $plg_errors;
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

	if(defined('STOP_HOOKS') || !isset($all_plg_hooks[$hook_name]))
	{
		return false;
	}
	return implode("\n", $all_plg_hooks[$hook_name]);
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
			$location .= "?" . $_SERVER['QUERY_STRING'];
		}
		elseif(isset($_ENV['QUERY_STRING']))
		{
			$location = "?" . $_ENV['QUERY_STRING'];
		}
	}

	return str_replace(array('&amp;'), array('&'), htmlspecialchars($location));
}

/*
* send email
*/

function _sm_mk_utf8($text)
{
	 return "=?UTF-8?B?" . base64_encode($text) . "?=";
}


function send_mail($to, $body, $subject, $fromaddress, $fromname,$bcc='')
{
	$eol = "\r\n";
	$headers = '';
	$headers .= 'From: ' . _sm_mk_utf8($fromname) . ' <' . $fromaddress . '>' . $eol;
	$headers .= 'Sender: ' . _sm_mk_utf8($fromname) . ' <' . $fromaddress . '>' . $eol;
	if (!empty($bcc)) 
	{
		$headers .= 'Bcc: ' . $bcc . $eol;
	}
	$headers .= 'Reply-To: ' . $fromaddress . $eol;
	$headers .= 'Return-Path: <' . $fromaddress . '>' . $eol;
	$headers .= 'MIME-Version: 1.0' . $eol;
	$headers .= 'Message-ID: <' . md5(uniqid(time())) . '@' . _sm_mk_utf8($fromname) . '>' . $eol;
	$headers .= 'Date: ' . date('r') . $eol;
	$headers .= 'Content-Type: text/plain; charset=UTF-8' . $eol; // format=flowed
	$headers .= 'Content-Transfer-Encoding: 8bit' . $eol; // 7bit
	$headers .= 'X-Priority: 3' . $eol;
	$headers .= 'X-MSMail-Priority: Normal' . $eol;
	$headers .= 'X-Mailer: : PHP v' . phpversion() . $eol;
	$headers .= 'X-MimeOLE: kleeja' . $eol;
		
		
	($hook = kleeja_run_hook('kleeja_send_mail')) ? eval($hook) : null; //run hook
		
	$mail_sent = @mail($to, _sm_mk_utf8($subject), preg_replace("#(?<!\r)\n#s", "\n", $body), $headers);
  
	return $mail_sent;
}


/*
* get remote files
* parameters : url : link of file
*/
function fetch_remote_file($url, $save_in = false, $timeout = 20)
{
	($hook = kleeja_run_hook('kleeja_fetch_remote_file_func')) ? eval($hook) : null; //run hook

	
	if(function_exists("curl_init"))
	{
		$ch = @curl_init();
		@curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_HEADER, 0);
		@curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$data = @curl_exec($ch);
		@curl_close($ch);
		return $data;
	}
	else if(function_exists("fsockopen"))
	{
	    $url_parsed = parse_url($url);
	    $host = $url_parsed['host'];
	    $port = empty($url_parsed['port']) or $url_parsed['port'] == 0 ? 80 : $url_parsed['port'];
		$path = $url_parsed['path'];
	    
		if (isset($url_parsed["query"]) && $url_parsed["query"] != '')
		{
			$path .= '?' . $url_parsed['query'];
		}
	
	    $out = "GET $path HTTP/1.1\r\nHost: $host\r\nConnection: Close\r\n\r\n";

	    if(!$fp = @fsockopen($host, $port, $errno, $errstr, $timeout))
		{
			return false;
		}
		
		@stream_set_timeout($fp, $timeout, 0);	
		@fwrite($fp, $out);
	    $body = false;
		if($save_in)
		{
			$fp2 = @fopen($save_in, "w");
		}
		
		$in = '';
		while (!@feof($fp))
		{
	        $s = @fgets($fp, 1024);
	        echo $s;
	        if ($body)
			{
				if($save_in)
				{
					@fwrite($fp2, $s);
				}
				else
				{
					$in .= $s;
				}
			}
			
			if ($s == "\r\n")
			{
				$body = true;
			}
	    }
	    @fclose($fp);
		
		if($save_in)
		{
			@fclose($fp2);
			return true;
		}
		
		return $in;
	}
	
	//there is known issue here with check update ..
	return implode('', @file($url));
	
}



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
*parameters : $name_of_select, $g_id, $return_name
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
		
		$show = "<select name=\"{$name_of_select}\">\n";
		
		for($i=1; $i< sizeof($s); $i++)
		{
			$selected = ($g_id == $i)? "selected=\"selected\"" : "";
			$show .= "<option $selected value=\"$i\">" . $s[$i]['name'] . "</option>\n";
		}
		
		$show .="</select>";
		
		($hook = kleeja_run_hook('ch_g_func')) ? eval($hook) : null; //run hook
		return $show;
}  

//1rc6+ for check file mime
//this function is under TESTING !
// you can share your experinces with us from our site kleeja.com...
function kleeja_check_mime ($mime, $group_id, $file_path)
{
	($hook = kleeja_run_hook('kleeja_check_mime_func')) ? eval($hook) : null; //run hook

	//This code for images only
	//it's must be improved for all files in future !
	if($group_id != 1)
	{
		return true;
	}
	
	$return = false;
	$s_items = @explode(':', 'image:png:jpg:tif:tga:targa');
	foreach($s_items as $r)
	{
		if(strpos($mime, $r) !== false)
		{
			$return = true;
			break;
		}
	}
		
	//onther check
	//$w = @getimagesize($file_path);
	//$return =  ($w && (strpos($w['mime'], 'image') !== false)) ? true : false;
	
	

	//another check
	if($return == true)
	{
		if(@filesize($file_path) > 4*(1000*1024))
		{
			return true;
		}
		
		//check for bad things inside files ...
		//<.? i cant add it here cuz alot of files contain it 
		$maybe_bad_codes_are = array('<script', 'zend', 'base64_decode');
		
		if(!($data = @file_get_contents($file_path)))
		{
			return true;
		}
		
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
function delete_cache($name, $all=false)
{

	($hook = kleeja_run_hook('delete_cache_func')) ? eval($hook) : null; //run hook
	
	$path_to_cache = PATH . './cache';
	
	if($all)
	{
		$dh = opendir($path_to_cache);
		while (($file = readdir($dh)) !== false)
		{
			if($file != "." && $file != ".." && $file != ".htaccess" && $file != "index.html" && $file != 'styles_cached.php')
			{
				$del = kleeja_unlink($path_to_cache . '/' . $file, true);
			}
		}
		closedir($dh);
	}
	else
	{
		$del = true;
		$name = str_replace('.php', '', $name) . '.php';
		if (file_exists($path_to_cache . '/' . $name))
		{
			$del = kleeja_unlink ($path_to_cache . "/" . $name, true);
		}
	}
	
	return $del;
}

//
//try delete files or at least change its name.
//for those who have dirty hosting 
//
function kleeja_unlink($filepath, $cache_file = false)
{
	//99.9% who use this
	if(function_exists('unlink'))
	{
		return @unlink($filepath);
	}
	//5% only who use this
	//else if (function_exists('exec'))
	//{
	//	$out = array();
	//	$return = null;
	//	exec('del ' . escapeshellarg(realpath($filepath)) . ' /q', $out, $return);
	//	return $return;
	//}
	//5% only who use this
	//else if (function_exists('system'))
	//{
	//	$return = null;
	//	system ('del ' . escapeshellarg(realpath($filepath)) . ' /q', $return);
	//	return $return;
	//}
	//just rename cache file if there is new thing
	else if (function_exists('rename') && $cache_file)
	{
		$new_name = substr($filepath, 0, strrpos($filepath, '/') + 1) . 'old_' . md5($filepath . time()) . '.php'; 
		return rename($filepath, $new_name);
	}

	return false;

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
		"png" => "image/png",
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
		"swf" => "application/x-shockwave-flash",
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
	
	($hook = kleeja_run_hook('get_mime_for_header_func')) ? eval($hook) : null; //run hook
	
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
	
	($hook = kleeja_run_hook('get_lang_func')) ? eval($hook) : null; //run hook
	
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

//
//delete any content from any template , this will used in plugins
//
function delete_ch_tpl($template_name, $delete_txt = array())
{
	global $dbprefix, $lang, $config, $STYLE_PATH_ADMIN , $STYLE_PATH, $root_path;
	
	$style_path = (substr($template_name, 0, 6) == 'admin_') ? $STYLE_PATH_ADMIN : $STYLE_PATH;
	$is_admin_template = (substr($template_name, 0, 6) == 'admin_') ? true : false;
									
	//if template not found and default style is there and not admin tpl
	$template_path = $style_path . $template_name . '.html';
	if(!file_exists($template_path)) 
	{
		if($config['style'] != 'default' && !$is_admin_template)
		{
			$template_path_alternative = str_replace('/' . $config['style'] . '/', '/default/', $template_path);
			if(file_exists($template_path_alternative))
			{
				$template_path = $template_path_alternative;
			}
		}
	}
	
	if(file_exists($template_path))
	{
		$d_contents = file_get_contents($template_path);
	}
	else 
	{
		$d_contents = '';
	}
	
	include_once "s_strings.php";
	$finder	= new sa_srch;
	$finder->find_word		= $delete_txt;
	$finder->another_word	= '<!-- deleted ' . md5(implode(null, $delete_txt)) . ' -->';
	$finder->text = trim($d_contents);
	$finder->do_search(2);
	$cached_instructions = array();
	
	if($d_contents  != '' && md5($finder->text) != md5($d_contents) && is_writable($style_path))
	{
		//update
		$filename = @fopen($style_path . $template_name . '.html', 'w');
		fwrite($filename, $finder->text);
		fclose($filename);
															
		($hook = kleeja_run_hook('op_up_tplcntedit_dlchtpl_fuck')) ? eval($hook) : null; //run hook
		
		//delete cache ..
		delete_cache('tpl_' .$template_name);
	}
	else
	{
		$cached_instructions[$template_name] = array(
													'action'		=> 'replace_with', 
													'find'			=> $finder->find_word[0] . '(.*?)' . $finder->find_word[1],
													'action_text'	=> $finder->another_word,
												);
										
	}
	
	//add cached instuctions to cache if there
	if(sizeof($cached_instructions) > 0)
	{
		//fix
		if(file_exists($root_path . 'cache/styles_cached.php'))
		{
			$cached_content = file_get_contents($root_path . 'cache/styles_cached.php');
			$cached_content = base64_decode($cached_content);
			$cached_content = unserialize($cached_content);
			$cached_instructions += $cached_content;
		}
		
		$filename = @fopen($root_path . 'cache/styles_cached.php' , 'w');
		fwrite($filename, base64_encode(serialize($cached_instructions)));
		fclose($filename);
	}
	
	return true;
}

/*
* Add new config option
*/
function add_config ($name, $value, $order = '0', $html = '', $type = 'other') 
{
	global $dbprefix, $SQL, $config;
	
	if(isset($config[$name]))
	{
		return true;
	}

	$insert_query = array(	'INSERT'	=> '`name` ,`value` ,`option` ,`display_order`, `type`',
							'INTO'		=> "{$dbprefix}config",
							'VALUES'	=> "'" . $SQL->escape($name) . "','" . $SQL->escape($value) . "', '" . addslashes($html) . "','" . intval($order) . "','" . $SQL->escape($type) . "'"
							);
							
	delete_cache('data_config');
	//make it globally ..
	$config[$name] = $value;
	
	return $SQL->build($insert_query);						
}

function add_config_r($configs)
{
	if(!is_array($configs))
	{
		return false;
	}
	
	//array(name=>array(value=>,order=>,html=>),...);
	foreach($configs as $n=>$m)
	{
		add_config($n, $m['value'], $m['order'], $m['html'], $m['type']);
	}
	
	return;
}

function update_config($name, $value, $escape = true)
{
	global $SQL, $dbprefix;
	
	$value = ($escape) ? $SQL->escape($value) : $value;
	
	$update_query = array(
						'UPDATE'	=> "{$dbprefix}config",
						'SET'		=> "value='" . ($escape ? $SQL->escape($value) : $value) . "'",
						'WHERE'		=> 'name = "' . $SQL->escape($name) . '"'
				);
	$SQL->build($update_query);
	if($SQL->affected())
	{
		$config[$name] = $value;
		delete_cache('data_config');
		return true;
	}
	else
	{
		return false;
	}
}

/*
* Delete config
*/
function delete_config ($name) 
{
	global $dbprefix, $SQL;
	
	//if array
	if(is_array($name))
	{
		foreach($name as $n)
		{
			$delete_query	= array('DELETE'	=> "{$dbprefix}config",
									'WHERE'		=>  'name = "' . $SQL->escape($n) . '"'
									);
		 	$SQL->build($delete_query);
		}
	}
	else 
	{
		$delete_query	= array('DELETE'	=> "{$dbprefix}config",
								'WHERE'		=>  'name = "' . $SQL->escape($name) . '"'
								);
		$SQL->build($delete_query);
	}
	
	return;
}


//
//add words to lang
//
function add_olang($words = array(), $lang='en')
{
	global $dbprefix, $SQL;
	
	foreach($words as $w=>$t)
	{
		$insert_query = array(	'INSERT'	=> '`word` ,`trans` ,`lang_id`',
								'INTO'		=> "{$dbprefix}lang",
								'VALUES'	=> "'" . $SQL->escape($w) . "','" . $SQL->escape($t) . "', '" . $SQL->escape($lang) . "'"
								);
		$SQL->build($insert_query);
	}
	
	delete_cache("data_lang");
	return;
}

//
//delete words from lang
//
function delete_olang ($words, $lang='en') 
{
	global $dbprefix, $SQL;
	
	//if array
	if(is_array($words))
	{
		foreach($words as $w)
		{
			$delete_query	= array('DELETE'	=> "{$dbprefix}lang",
									'WHERE'		=>  'word = "' . $SQL->escape($w) . '" AND lang_id="' . $SQL->escape($lang) . '"'
									);
		 	$SQL->build($delete_query);
		}
	}
	else 
	{
		$delete_query	= array('DELETE'	=> "{$dbprefix}lang",
								'WHERE'		=>  'word = "' . $SQL->escape($words) . '" AND lang_id="' . $SQL->escape($lang) . '"'
								);
		$SQL->build($delete_query);
	}
	
	return;
}

//when php less than 5 !
if(!function_exists('htmlspecialchars_decode'))
{
	function htmlspecialchars_decode($string, $style=ENT_COMPAT)
	{
		$translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS, $style));
		if($style === ENT_QUOTES)
		{
			$translation['&#039;'] = '\'';
		}
		return strtr($string, $translation);
	}
}

//
// administarator sometime need some files and delete other .. we
// do that for him .. becuase he has no time .. :)   
//last_down - $config[del_f_day]
//
function klj_clean_old_files($from = 0)
{
	global $config, $SQL, $stat_last_f_del, $dbprefix;


	if((int) $config['del_f_day'] <= 0)
	{
		return;
	}

	if(!$stat_last_f_del || empty($stat_last_f_del))
	{
		$stat_last_f_del = time();
	}
	
	if ((time() - $stat_last_f_del) >= 86400)
	{
		$totaldays	= (time() - ($config['del_f_day']*86400));
		$not_today	= time() - 86400;
		
		
		$query = array(
					'SELECT'	=> 'f.id, f.last_down, f.name, f.type, f.folder, f.time, f.size',
					'FROM'		=> "{$dbprefix}files f",
					'WHERE'		=> "f.last_down < $totaldays AND f.time < $not_today AND f.id > $from",
					'ORDER BY'	=> 'f.id ASC',
					'LIMIT'		=> '20',
					);
		
		($hook = kleeja_run_hook('qr_select_klj_clean_old_files_func')) ? eval($hook) : null; //run hook
		
		$result	= $SQL->build($query);					

		if($SQL->num_rows($result) == 0)
		{
		   	 //update $stat_last_f_del !!
			$update_query = array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> "last_f_del ='" . time() . "'",
							);
						
			($hook = kleeja_run_hook('qr_update_lstf_del_date_kcof')) ? eval($hook) : null; //run hook
		
			$SQL->build($update_query);		
			//delete stats cache
			delete_cache("data_stats");
			update_config('klj_clean_files_from', '0');
			$SQL->freeresult($result);
			return;
		}
		
		$last_id_from = $num = $sizes = 0;
		$ids = array();
		$ex_ids =  array();
		//$ex_types = explode(',', $config['livexts']);
		
		($hook = kleeja_run_hook('beforewhile_klj_clean_old_files_func')) ? eval($hook) : null; //run hook
		
		//delete files 
		while($row=$SQL->fetch_array($result))
		{
			$last_id_from = $row['id'];
			
			/*
			//excpetions
			if(in_array($row['type'], $ex_types) || $config['id_form'] == 'direct')
			{
				$ex_ids[] = $row['id'];
				continue;
			}
			*/
			
			if($config['id_form'] == 'direct')
			{
				$ex_ids[] = $row['id'];
				continue;
			}
			
			//your exepctions
			($hook = kleeja_run_hook('while_klj_clean_old_files_func')) ? eval($hook) : null; //run hook
			
			//delete from folder ..
			if (file_exists($row['folder'] . "/" . $row['name']))
			{
				@kleeja_unlink ($row['folder'] . "/" . $row['name']);
			}
			//delete thumb
			if (file_exists($row['folder'] . "/thumbs/" . $row['name'] ))
			{
				@kleeja_unlink ($row['folder'] . "/thumbs/" . $row['name'] );
			}
					
			$ids[] = $row['id'];
			$num++;		
			$sizes += $row['size'];
			
	    }#END WHILE
		
		$SQL->freeresult($result);
		
		if(sizeof($ex_ids))
		{
				$update_query	= array(
										'UPDATE'	=> "{$dbprefix}files",
										'SET'		=> "last_down = '" . (time() + 2*86400) . "'",
										'WHERE'		=> "id IN (" . implode(',', $ex_ids) . ")"
										);
				($hook = kleeja_run_hook('qr_update_lstdown_old_files')) ? eval($hook) : null; //run hook						
				$SQL->build($update_query);
		}
		
		if(sizeof($ids))
		{
				$query_del	= array(
									'DELETE'	=> "{$dbprefix}files",
									'WHERE'	=> "id IN (" . implode(',', $ids) . ")"
									);
									
				//update number of stats
				$update_query	= array(
										'UPDATE'	=> "{$dbprefix}stats",
										'SET'		=> "sizes=sizes-$sizes,files=files-$num",
										);
										
				($hook = kleeja_run_hook('qr_del_delf_old_files')) ? eval($hook) : null; //run hook
				
				$SQL->build($query_del);
				$SQL->build($update_query);
		}
		
		update_config('klj_clean_files_from', $last_id_from);
    } //stat_del

}

/*
*	 get_ip() for the user
*/
function get_ip()
{
	$ip = '';
	if(!empty($_SERVER['REMOTE_ADDR']))
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	else if (!empty($_SERVER['HTTP_CLIENT_IP']))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	else 
	{
		$ip = getenv('REMOTE_ADDR');
		if(getenv('HTTP_X_FORWARDED_FOR'))
		{
			if(preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip3))
			{
				$ip2 = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10..*/', '/^224..*/', '/^240..*/');
				$ip = preg_replace($ip2, $ip, $ip3[1]);
			}
		}
	}
	
	return preg_replace('/[^0-9a-z.]/i', '', $ip);
}

//check captcha field after submit
function kleeja_check_captcha()
{
	if(!empty($_SESSION['klj_sec_code']) && !empty($_POST['kleeja_code_answer']))
	{
		if($_SESSION['klj_sec_code'] == $_POST['kleeja_code_answer'])
		{
			$_SESSION['klj_sec_code'] = '';
			return true;
		}
	}
	
	return false;
}

//
//http://us2.php.net/manual/en/function.str-split.php#84891
if(!function_exists('str_split'))
{
    function str_split($string, $string_length=1)
	{
        if(strlen($string) > $string_length || !$string_length)
		{
            do
			{
                $c = strlen($string);
                $parts[] = substr($string, 0, $string_length);
                $string	 = substr($string, $string_length);
            }
			while($string !== false);
        }
		else
		{
            $parts = array($string);
        }
        return $parts;
    }
}

//
//for logging
//
function kleeja_log($text, $reset = false)
{
	if(!defined('DEV_STAGE'))
	{
		return;
	}

	$fp = @fopen(PATH . 'cache/kleeja_log.log', 'a');
	@fwrite($fp, $text . " [time : " . date('H:i a, d-m-Y') . "] \r\n");
	@fclose($fp);
	return;
}

#<-- EOF

<?php
##################################################
#						Kleeja
#
# Filename : cache.php
# purpose :  cache for all script. the important feature of kleeja
# copyright 2007-2009 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : saanina
##################################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit;
}

	
//	
//get hooks data from hooks table  ... 
//
if(!defined('STOP_HOOKS'))
{
	if (file_exists($root_path . 'cache/data_hooks.php'))
	{
		include_once ($root_path . 'cache/data_hooks.php');
	}
	
	if (!$all_plg_hooks && !file_exists($root_path.'cache/data_hooks.php'))
	{
		//get all hooks
		$query = array(
		'SELECT'	=> 'h.hook_id,h.hook_name, h.hook_content, h.plg_id, p.plg_name',
		'FROM'		=> "{$dbprefix}hooks AS h",
		'JOINS'		=> array(
			array(
				'INNER JOIN'	=> "{$dbprefix}plugins AS p",
				'ON'			=> 'p.plg_id=h.plg_id'
			)
		),
		'WHERE'		=> 'p.plg_disabled=0',
		'ORDER BY'	=> 'h.hook_id'
	);
	
		($hook = kleeja_run_hook('qr_select_hooks_cache')) ? eval($hook) : null; //run hook
		$result = $SQL->build($query);
			
				$file_datac = '<' . '?php' . "\n\n";
				//$file_datac .= "if (!defined('IN_COMMON')) exit('no directly opening : ' . __file__);";
				$file_datac .= "\n// auto-generated cache files\n//For: Kleeja \n\n";
				$file_datac .= '$all_plg_hooks = array();' ."\n\n";


		while($row=$SQL->fetch_array($result))
		{
				$all_plg_hooks[$row['hook_name']][$row['plg_name']] =	$row['hook_content'];
				$file_datac .= '$all_plg_hooks[\''.$row['hook_name'].'\'][\''.$row['plg_name'].'\'] = \'' . str_replace(array("'","\'"), "\'",  $row['hook_content']) . '\';' . "\n";
		}
				$file_datac .= ''."\n\n";
				$file_datac .= '?' . '>';
				
	 	$SQL->freeresult($result);

		$filenumc = fopen($root_path.'cache/data_hooks.php', 'w');
		flock($filenumc, LOCK_EX); // exlusive look
		fwrite($filenumc, $file_datac);
		fclose($filenumc);
	}
}#plugins is on



//
//get config data from config table  ...
//
	if (file_exists($root_path . 'cache/data_config.php'))
	{
		include_once ($root_path . 'cache/data_config.php');
	}
	
	if (!$config or !file_exists($root_path.'cache/data_config.php'))
	{
		$query = array(
					'SELECT'	=> 'c.*',
					'FROM'		=> "{$dbprefix}config c"
					);
					
		($hook = kleeja_run_hook('qr_select_config_cache')) ? eval($hook) : null; //run hook				
		$result = $SQL->build($query);
			
				$file_datac = '<' . '?php' . "\n\n";
				//$file_datac .= "if (!defined('IN_COMMON')) exit('no directly opening : ' . __file__);";
				$file_datac .= "\n// auto-generated cache files\n//For: Kleeja \n\n";
				$file_datac .= '$config = array( ' . "\n";

		while($row=$SQL->fetch_array($result))
		{
				$config[$row['name']] =$row['value'];
				$file_datac .= '\''.$row['name'].'\' => \'' . str_replace(array("'","\'"), "\'", $row['value']) . '\',' . "\n";
		}
				$file_datac .= ');' . "\n\n";
				$file_datac .= '?' . '>';
				
	 	$SQL->freeresult($result);

		$filenumc = fopen($root_path.'cache/data_config.php', 'w');
		flock($filenumc, LOCK_EX); // exlusive look
		fwrite($filenumc, $file_datac);
		fclose($filenumc);
	}

//
//get data from types table ... 
//
	if (file_exists($root_path . 'cache/data_exts.php'))
	{
		include_once ($root_path . 'cache/data_exts.php');
	}
	
	if (!($g_exts || $u_exts) || !(file_exists($root_path.'cache/data_exts.php')))
	{
		$query = array(
					'SELECT'	=> 'e.*',
					'FROM'		=> "{$dbprefix}exts e"
					);
					
		($hook = kleeja_run_hook('qr_select_exts_cache')) ? eval($hook) : null; //run hook		
		$result = $SQL->build($query);
		
				$file_datat = '<' . '?php' . "\n\n";
				//$file_datat .= "if (!defined('IN_COMMON')) exit('no directly opening : ' . __file__);";
				$file_datat .= "\n// auto-generated cache files\n//For: Kleeja \n\n";
				$file_datat .= 'if (empty($g_exts) || !is_array($g_exts)){$g_exts = array();}'."\n";
				$file_datat .= 'if (empty($u_exts) || !is_array($u_exts)){$u_exts = array();}'."\n\n";
				
		while($row=$SQL->fetch_array($result))
		{
				if ($row['gust_allow'])
				{
					$g_exts[$row['ext']] = array('id' => $row['id'], 'size' => $row['gust_size'], 'group_id' => $row['group_id']);
					$file_datat	.= '$g_exts[\'' . $row['ext'] . '\']  =   array("id"=>\'' . $row['id'] . '\',"size"=>\'' . $row['gust_size'] . '\',"group_id"=>\'' . $row['group_id'] . '\' );' . "\n";
				}
				
				if ($row['user_allow'])
				{
					$u_exts[$row['ext']] = array('id' => $row['id'], 'size' => $row['user_size'], 'group_id' => $row['group_id']);
					$file_datat	.= '$u_exts[\'' . $row['ext'] . '\']  =   array("id"=>\'' . $row['id'] . '\',"size"=>\'' . $row['user_size'] . '\',"group_id"=>\'' . $row['group_id'] . '\' );' . "\n";
				}
		}
				$file_datat .= "\n\n";
				$file_datat .= '?' . '>';
				
	 	$SQL->freeresult($result);

		$filenumt = fopen($root_path.'cache/data_exts.php', 'w');
		flock($filenumt, LOCK_EX); // exlusive look
		fwrite($filenumt, $file_datat);
		fclose($filenumt);
	}

//
//stats .. to cache
//
	if(file_exists("cache/data_stats.php"))
	{
		$tfile = filemtime("cache/data_stats.php");
		if((time()-$tfile) >= 3600)//after 1 hours exactly
		{    
			delete_cache("data_stats");
		}
		else
		{
			include_once ("cache/data_stats.php");
		}
	}
	
	if(!file_exists("cache/data_stats.php"))
	{
		$query = array(
					'SELECT'	=> 's.*',
					'FROM'		=> "{$dbprefix}stats s"
					);
					
		($hook = kleeja_run_hook('qr_select_stats_cache')) ? eval($hook) : null; //run hook				
		$result = $SQL->build($query);
		
		$file_dataw = '<' . '?php' . "\n\n";
		//$file_dataw .= "if (!defined('IN_COMMON')) exit('no directly opening : ' . __file__);";
		$file_dataw .= "\n// auto-generated cache files\n//For: Kleeja \n\n";

		while($row=$SQL->fetch_array($result))
		{
			$stat_files 			=  $row['files'];
			$stat_sizes 			=  $row['sizes'];
			$stat_users 			=  $row['users'];
			$stat_last_file 		=  $row['last_file'];
			$stat_last_f_del		=  $row['last_f_del'];
			$stat_today 			=  $row['today'];
			$stat_counter_today 	=  $row['counter_today'];
			$stat_counter_yesterday	=  $row['counter_yesterday'];
			$stat_counter_all		=  $row['counter_all'];
			$stat_last_google		=  $row['last_google'];
			$stat_last_yahoo		=  $row['last_yahoo'];
			$stat_google_num		=  $row['google_num'];
			$stat_yahoo_num			=  $row['yahoo_num'];
			//$stat_rules				=  $row[rules];
			
			//write
			$file_dataw .= '$stat_files  			=   \'' . $row['files'] . '\';' . "\n";
			$file_dataw .= '$stat_sizes  			=   \'' . $row['sizes'] . '\';' . "\n";
			$file_dataw .= '$stat_users  			=   \'' . $row['users'] . '\';' . "\n";
			$file_dataw .= '$stat_last_file 		=	\'' . $row['last_file'] . '\';' . "\n";
			$file_dataw .= '$stat_last_f_del		=	\'' . $row['last_f_del'] . '\';' . "\n";
			$file_dataw .= '$stat_today 			=	\'' . $row['today'] . '\';' . "\n";
			$file_dataw .= '$stat_counter_today		=	\'' . $row['counter_today'] . '\';' . "\n";
			$file_dataw .= '$stat_counter_yesterday =	\'' . $row['counter_yesterday'] . '\';' . "\n";
			$file_dataw .= '$stat_counter_all 		=	\'' . $row['counter_all'] . '\';' . "\n";
			$file_dataw .= '$stat_last_google 		=	\'' . $row['last_google'] . '\';' . "\n";
			$file_dataw .= '$stat_google_num 		=	\'' . $row['google_num'] . '\';' . "\n";
			$file_dataw .= '$stat_last_yahoo 		=	\'' . $row['last_yahoo'] . '\';' . "\n";
			$file_dataw .= '$stat_yahoo_num 		=	\'' . $row['yahoo_num'] . '\';' . "\n";
			//$file_dataw .= '$stat_rules				=	\'' . $row['rules'] . '\';' . "\n";
		
		}
		$file_dataw .= '?' . '>';
		
		$SQL->freeresult($result);
		
		$filenumw = fopen($root_path.'cache/data_stats.php', 'w');
		flock($filenumw, LOCK_EX); // exlusive look
		fwrite($filenumw, $file_dataw);
		fclose($filenumw);
	}//end else

	
//
// administarator sometime need some files and delete other .. we
// do that for him .. becuase he has no time .. :)   
//last_down - $config[del_f_day]
//
if((int) $config['del_f_day'] >= 0)
{
	if(!$stat_last_f_del || empty($stat_last_f_del)) $stat_last_f_del = time();
	
    if ((time() - $stat_last_f_del) >= 86400)
    {
		$totaldays	= (time() - ($config['del_f_day']*86400));
		$not_today	= time() - 86400;
		
		$query = array(
					'SELECT'	=> 'f.id, f.last_down, f.name, f.folder, f.time',
					'FROM'		=> "{$dbprefix}files f",
					'WHERE'		=> "f.last_down < $totaldays AND f.time < $not_today"
					);
					
		($hook = kleeja_run_hook('qr_select_delfiles_cache')) ? eval($hook) : null; //run hook			
		$result = $SQL->build($query);

		while($row=$SQL->fetch_array($result))
		{
					$query_del = array(
									'DELETE'	=> "{$dbprefix}files",
									'WHERE'		=> "id='" . intval($row['id']) . "' AND last_down < $totaldays"
									);
									
					($hook = kleeja_run_hook('qr_del_delfiles_cache')) ? eval($hook) : null; //run hook	
								
					if (!$SQL->build($query_del))
					{
						die($lang['CANT_DELETE_SQL']);
					}
					
					//delete from folder ..
					if (file_exists($row['folder'] . "/" . $row['name']))
					{
						@unlink ($row['folder'] . "/" . $row['name']);
					}
					//delete thumb
					if (file_exists($row['folder'] . "/thumbs/" . $row['name'] ))
					{
						@unlink ($row['folder'] . "/thumbs/" . $row['name'] );
					}
		
	    }#END WHILE
		
		
	    //update $stat_last_f_del !!
		$update_query = array(
							'UPDATE'	=> "{$dbprefix}stats",
							'SET'		=> "last_f_del ='" . time() . "'",
						);
						
		($hook = kleeja_run_hook('qr_update_delfiles_date_cache')) ? eval($hook) : null; //run hook
		
		if (!$SQL->build($update_query)) die($lang['CANT_UPDATE_SQL']);
		
		//delete stats cache
		delete_cache("data_stats");
		

    } //stat_del
}#no automated delete


//
//get banned ips data from stats table  ...
//
	if (file_exists($root_path . 'cache/data_ban.php'))
	{
		include_once ($root_path . 'cache/data_ban.php');
	}
	
	if (!$banss or !file_exists($root_path.'cache/data_ban.php'))
	{
		$query = array(
					'SELECT'	=> 's.ban',
					'FROM'		=> "{$dbprefix}stats s"
					);
					
		($hook = kleeja_run_hook('qr_select_ban_cache')) ? eval($hook) : null; //run hook				
		$result = $SQL->build($query);
	
		$file_datab = '<' . '?php' . "\n\n";
		//$file_datab .= "if (!defined('IN_COMMON')) exit('no directly opening : ' . __file__);";
		$file_datab .= "\n// auto-generated cache files\n//For: Kleeja \n\n";
		$file_datab .= '$banss = array( ' . "\n";

		while($row=$SQL->fetch_array($result))
		{
			$ban1 = $row['ban'];
		}
		
		$SQL->freeresult($result);
		
		if (!empty($ban1) || $ban1 != ' '|| $ban1 != '  ')
		{
			//seperate ips .. 
			$ban2 = explode("|", $ban1);
			for ( $i=0;$i<count($ban2);$i++)
			{
				$banss[$i] = $ban2[$i];
				$file_datab .= '\'' . trim($ban2[$i]) . '\',' . "\n";
			}#for
		
			$file_datab .= ');'."\n\n";
			$file_datab .= '?' . '>';
	 	}

		$filenumb = fopen($root_path.'cache/data_ban.php', 'w');
		flock($filenumb, LOCK_EX); // exlusive look
		fwrite($filenumb, $file_datab);
		fclose($filenumb);
	}
	
//	
//get rules data from stats table  ...
//
	if (file_exists($root_path.'cache/data_rules.php'))
	{
		include_once ($root_path.'cache/data_rules.php'); 
	}
	
	if (!isset($ruless) or !file_exists($root_path.'cache/data_rules.php'))
	{
		$query = array(
					'SELECT'	=> 's.rules',
					'FROM'		=> "{$dbprefix}stats s"
					);
					
		($hook = kleeja_run_hook('qr_select_rules_cache')) ? eval($hook) : null; //run hook					
		$result = $SQL->build($query);
		
	
		$file_datar = '<' . '?php' . "\n\n";
		//$file_datar .= "if (!defined('IN_COMMON')) exit('no directly opening : ' . __file__);";
		$file_datar .= "\n// auto-generated cache files\n//For: Kleeja \n\n";

		while($row=$SQL->fetch_array($result))
		{
			$rules1 = $row['rules'];
		}
		$SQL->freeresult($result);
		
			$ruless = $rules1;
			$file_datar .= '$ruless = \'' .str_replace(array("'","\'"), "\'", $rules1) .'\';'."\n\n"; // its took 2 hours ..
			
		$file_datar .= '?' . '>';
		$filenumr = fopen($root_path.'cache/data_rules.php', 'w');
		flock($filenumr, LOCK_EX); // exlusive look
		fwrite($filenumr, $file_datar);
		fclose($filenumr);
	}	
	
//	
//get ex-header-footer data from stats table  ... 
//
	if (file_exists($root_path.'cache/data_extra.php'))
	{
		include_once ($root_path.'cache/data_extra.php');
	}
	
	if (!isset($extras) or !file_exists($root_path.'cache/data_extra.php'))
	{
		$query = array(
					'SELECT'	=> 's.ex_header, s.ex_footer',
					'FROM'		=> "{$dbprefix}stats s"
					);
					
		($hook = kleeja_run_hook('qr_select_extra_cache')) ? eval($hook) : null; //run hook		
		$result = $SQL->build($query);

	
		$file_datae = '<' . '?php' . "\n\n";
		//$file_datae .= "if (!defined('IN_COMMON')) exit('no directly opening : ' . __file__);";
		$file_datae .= "\n// auto-generated cache files\n//For: Kleeja \n\n";

		while($row=$SQL->fetch_array($result))
		{
			$headerr = $row['ex_header'];
			$footerr = $row['ex_footer'];
		}
		
		$SQL->freeresult($result);
		

		$extras['header'] = $headerr;
		$file_datae .= '$extras[\'header\'] = \'' .str_replace(array("'","\'"), "\'", $headerr) .'\';'."\n\n";

		$extras['footer'] = $footerr;
		$file_datae .= '$extras[\'footer\'] = \'' .str_replace(array("'","\'"), "\'", $footerr) .'\';'."\n\n";
	 
		
		$file_datae .= '?' . '>';
		$filenume = fopen($root_path.'cache/data_extra.php', 'w');
		flock($filenume, LOCK_EX); // exlusive look
		fwrite($filenume, $file_datae);
		fclose($filenume);
	}
	

 
($hook = kleeja_run_hook('in_cache_page')) ? eval($hook) : null; //run hook
?>

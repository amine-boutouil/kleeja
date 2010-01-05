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
	exit;
}

//we are in cache now ..
define('IN_CACHE', true);


//make sure it's utf8 data
$SQL->set_utf8();


//
//In the future here will be a real cache class 
//this codes, it's just a sample and usefull for
//some time ..
//
class cache
{
	function get($name)
	{
		$name =  preg_replace('![^a-z0-9_]!', '_', $name);
	
		if (file_exists(PATH . 'cache/' . $name . '.php'))
		{
			include_once (PATH . 'cache/' . $name . '.php');
			return  empty($data) ? false : $data;
		}
		else
		{
			return false;
		}
	}
	
	function save($name, $data, $time = 86400)
	{
		$name =  preg_replace('![^a-z0-9_]!i', '_', $name);
		$data_for_save = '<?' . 'php' . "\n";
		$data_for_save .= '//Cache file, generated for Kleeja at ' . gmdate('d-m-Y h:i A') . "\n\n";
		$data_for_save .= '//No direct opening' . "\n";
		$data_for_save .= '(!defined("IN_COMMON") ? exit("hacking attemp!") : null);' . "\n\n";
		$data_for_save .= '//return false after x time' . "\n";	
		$data_for_save .= 'if(time() > ' . (time() + $time) . ') return false;' . "\n\n";	
		$data_for_save .= '$data = ' . var_export($data, true) . ";\n\n//end of cache";

		if($fd = @fopen(PATH . 'cache/' . $name . '.php', 'w'))
		{
			@flock($fd, LOCK_EX); // exlusive look
			@fwrite($fd, $data_for_save);
			@flock($fd, LOCK_UN);
			@fclose($fd);
		}
		return;
	}
}

$cache = new cache;

//	
//get hooks data from hooks table  ... 
//
if(!defined('STOP_HOOKS'))
{
	if (!($all_plg_hooks = $cache->get('data_hooks')))
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

		while($row=$SQL->fetch_array($result))
		{
			$all_plg_hooks[$row['hook_name']][$row['plg_name']] =	$row['hook_content'];
		}
	 	$SQL->freeresult($result);

		$cache->save('data_hooks', $all_plg_hooks);
	}
}#plugins is on


//
//get config data from config table  ...
//

if (!($config = $cache->get('data_config')))
{
	$query = array(
					'SELECT'	=> 'c.*',
					'FROM'		=> "{$dbprefix}config c"
				);

	($hook = kleeja_run_hook('qr_select_config_cache')) ? eval($hook) : null; //run hook				
	$result = $SQL->build($query);


	while($row=$SQL->fetch_array($result))
	{
		$config[$row['name']] =$row['value'];
	}

	$SQL->freeresult($result);

	$cache->save('data_config', $config);
}

//
//get language terms from lang table  ...
//

if (!($olang = $cache->get('data_lang')))
{
	$query = array(
					'SELECT'	=> 'l.*',
					'FROM'		=> "{$dbprefix}lang l",
					'WHERE'		=> "l.lang_id='" . $SQL->escape($config['language']) . "'",
				);

	($hook = kleeja_run_hook('qr_select_lang_cache')) ? eval($hook) : null; //run hook		

	$result = $SQL->build($query);

	while($row=$SQL->fetch_array($result))
	{
		$olang[$row['word']] = $row['trans'];
	}

	$SQL->freeresult($result);

	$cache->save('data_lang', $olang);
}
	
//
//get data from types table ... 
//
if (!($exts = $cache->get('data_exts')))
{
	$query = array(
					'SELECT'	=> 'e.*',
					'FROM'		=> "{$dbprefix}exts e"
				);

	($hook = kleeja_run_hook('qr_select_exts_cache')) ? eval($hook) : null; //run hook		
	$result = $SQL->build($query);
	
	$exts = array();

	while($row=$SQL->fetch_array($result))
	{
		if ($row['gust_allow'])
		{
			$exts['g_exts'][$row['ext']] = array('id' => $row['id'], 'size' => $row['gust_size'], 'group_id' => $row['group_id']);
		}

		if ($row['user_allow'])
		{
			$exts['u_exts'][$row['ext']] = array('id' => $row['id'], 'size' => $row['user_size'], 'group_id' => $row['group_id']);
		}
	}

	$SQL->freeresult($result);

	$cache->save('data_exts', $exts);
}

//make them as seperated vars
extract($exts);
unset($exts);


//
//stats .. to cache
//
if (!($stats = $cache->get('data_stats')))
{
	$query = array(
					'SELECT'	=> 's.*',
					'FROM'		=> "{$dbprefix}stats s"
			);

	($hook = kleeja_run_hook('qr_select_stats_cache')) ? eval($hook) : null; //run hook				
	$result = $SQL->build($query);

	while($row=$SQL->fetch_array($result))
	{
		$stats = array(
			'stat_files'		=> $row['files'],
			'stat_sizes'		=> $row['sizes'],
			'stat_users'		=> $row['users'],
			'stat_last_file'	=> $row['last_file'],
			'stat_last_f_del'	=> $row['last_f_del'],
			'stat_last_google'	=> $row['last_google'],
			'stat_last_yahoo'	=> $row['last_yahoo'],
			'stat_google_num'	=> $row['google_num'],
			'stat_yahoo_num'	=> $row['yahoo_num'],
			'stat_last_user'	=> $row['lastuser']
		);
	
		($hook = kleeja_run_hook('while_fetch_stats_in_cache')) ? eval($hook) : null; //run hook
	}

	$SQL->freeresult($result);

	$cache->save('data_stats', $stats, 3600);
}

//make them as seperated vars
extract($stats);
unset($stats);

//
//get banned ips data from stats table  ...
//
if (!($banss = $cache->get('data_ban')))
{
	$query = array(
					'SELECT'	=> 's.ban',
					'FROM'		=> "{$dbprefix}stats s"
				);

	($hook = kleeja_run_hook('qr_select_ban_cache')) ? eval($hook) : null; //run hook				
	$result = $SQL->build($query);

	$row = $SQL->fetch_array($result);
	$ban1 = $row['ban'];
	$SQL->freeresult($result);

	$banss = array();

	if (!empty($ban1) || $ban1 != ' '|| $ban1 != '  ')
	{
		//seperate ips .. 
		$ban2 = explode('|', $ban1);
		for ($i=0; $i<sizeof($ban2); $i++)
		{
			$banss[$i] = $ban2[$i];
		}
	}

	unset($ban1, $ban1);

	$cache->save('data_ban', $banss);
}

//	
//get rules data from stats table  ...
//
if (!($ruless = $cache->get('data_rules')))
{
	$query = array(
					'SELECT'	=> 's.rules',
					'FROM'		=> "{$dbprefix}stats s"
				);

	($hook = kleeja_run_hook('qr_select_rules_cache')) ? eval($hook) : null; //run hook					
	$result = $SQL->build($query);

	$row = $SQL->fetch_array($result);
	$ruless = $row['rules'];
	$SQL->freeresult($result);

	$cache->save('data_rules', $ruless);
}	


//	
//get ex-header-footer data from stats table  ... 
//
if (!($extras = $cache->get('data_extra')))
{
	$query = array(
					'SELECT'	=> 's.ex_header, s.ex_footer',
					'FROM'		=> "{$dbprefix}stats s"
					);

	($hook = kleeja_run_hook('qr_select_extra_cache')) ? eval($hook) : null; //run hook		
	$result = $SQL->build($query);

	$row = $SQL->fetch_array($result);
	
	$extras = array(
		'header' => $row['ex_header'],
		'footer' => $row['ex_footer']
	);

	$SQL->freeresult($result);

	$cache->save('data_extra', $extras);
}
	

// ummm, does this usefull here
($hook = kleeja_run_hook('in_cache_page')) ? eval($hook) : null; //run hook


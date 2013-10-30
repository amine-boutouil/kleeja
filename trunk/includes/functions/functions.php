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
* function to get variables from _GET, _POST
* kleeja 2.0
*/
function ig($name)
{
	return isset($_GET[$name]) ? true : false;
}

function ip($name)
{
	return isset($_POST[$name]) ? true : false;
}

function g($name, $type = 'str')
{
	if(isset($_GET[$name]))
	{
		return $type == 'str' ? htmlspecialchars($_GET[$name]) : intval($_GET[$name]);
	}
	return false;
}

function p($name, $type = 'str')
{
	if(isset($_POST[$name]))
	{
		return $type == 'str' ? htmlspecialchars($_POST[$name]) : intval($_POST[$name]);
	}
	return false;
}


/**
* For recording who onlines now .. 
*/
function kleeja_detecting_bots()
{
	global $SQL, $usrcp, $dbprefix, $config, $klj_session;

	// get information .. 
	$agent	= $SQL->escape($_SERVER['HTTP_USER_AGENT']);
	$time	= time();

	//for stats 
	if (strpos($agent, 'Google') !== false)
	{
		$update_query = array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> "last_google=$time, google_num=google_num+1"
							);
		($hook = kleeja_run_hook('qr_update_google_lst_num')) ? eval($hook) : null; //run hook
		$SQL->build($update_query);
	}
	elseif (strpos($agent, 'Bing') !== false)
	{
		$update_query = array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> "last_bing=$time, bing_num=bing_num+1"
							);
		($hook = kleeja_run_hook('qr_update_bing_lst_num')) ? eval($hook) : null; //run hook	
		$SQL->build($update_query);
	}

	//put another bots as a hook if you want !
	($hook = kleeja_run_hook('anotherbots_onlline_func')) ? eval($hook) : null; //run hook

	//clean online table
	if((time() - $config['last_online_time_update']) >= 3600)
	{
		#what to add here ?
		//update last_online_time_update 
		update_config('last_online_time_update', time());
	}

	($hook = kleeja_run_hook('KleejaOnline_func')) ? eval($hook) : null; //run hook	
}


/**
* For ban ips .. 
*/
function get_ban()
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
	}

	($hook = kleeja_run_hook('get_ban_func')) ? eval($hook) : null; //run hook	
}


/**
* Run hooks of kleeja
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



/**
* send email
*/
function _sm_mk_utf8($text)
{
	return "=?UTF-8?B?" . kleeja_base64_encode($text) . "?=";
}

function send_mail($to, $body, $subject, $fromaddress, $fromname, $bcc='')
{
	$eol = "\r\n";
	$headers = '';
	$headers .= 'From: ' . _sm_mk_utf8(trim(preg_replace('#[\n\r:]+#s', '', $fromname))) . ' <' . trim(preg_replace('#[\n\r:]+#s', '', $fromaddress)) . '>' . $eol;
	//$headers .= 'Sender: ' . _sm_mk_utf8($fromname) . ' <' . $fromaddress . '>' . $eol;
	$headers .= 'MIME-Version: 1.0' . $eol;
	$headers .= 'Content-transfer-encoding: 8bit' . $eol; // 7bit
	$headers .= 'Content-Type: text/plain; charset=utf-8' . $eol; // format=flowed
	$headers .= 'X-Mailer: Kleeja Mailer' . $eol;
	$headers .= 'Reply-To: ' . _sm_mk_utf8(trim(preg_replace('#[\n\r:]+#s', '', $fromname))) . ' <' . trim(preg_replace('#[\n\r:]+#s', '', $fromaddress)) . '>' . $eol;
	//$headers .= 'Return-Path: <' . $fromaddress . '>' . $eol;
	if (!empty($bcc)) 
	{
		$headers .= 'Bcc: ' . trim(preg_replace('#[\n\r:]+#s', '', $bbc)) . $eol;
	}
	//$headers .= 'Message-ID: <' . md5(uniqid(time())) . '@' . _sm_mk_utf8($fromname) . '>' . $eol;
	//$headers .= 'Date: ' . date('r') . $eol;
	
	//$headers .= 'X-Priority: 3' . $eol;
	//$headers .= 'X-MSMail-Priority: Normal' . $eol;
	
	//$headers .= 'X-MimeOLE: kleeja' . $eol;

	($hook = kleeja_run_hook('kleeja_send_mail')) ? eval($hook) : null; //run hook

	$body = str_replace(array("\n", "\0"), array("\r\n", ''), $body);

	// Change the linebreaks used in the headers according to OS
	if (strtoupper(substr(PHP_OS, 0, 3)) == 'MAC')
	{
		$headers = str_replace("\r\n", "\r", $headers);
	}
	else if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN')
	{
		$headers = str_replace("\r\n", "\n", $headers);
	}

	$mail_sent = @mail(trim(preg_replace('#[\n\r]+#s', '', $to)), _sm_mk_utf8(trim(preg_replace('#[\n\r]+#s', '', $subject))), $body, $headers);

	return $mail_sent;
}




/**
* Delete cache
*/
function delete_cache($name, $all=false)
{
	#Those files are exceptions and not for deletion
	$exceptions = array('.htaccess', 'index.html', 'php.ini', 'styles_cached.php', 'web.config');

	($hook = kleeja_run_hook('delete_cache_func')) ? eval($hook) : null; //run hook

	//handle array of cached files
	if(is_array($name))
	{
		foreach($name as $n)
		{
			delete_cache($n, false);
		}
		return true;
	}

	$path_to_cache = PATH . 'cache';
	
	if($all)
	{
		if($dh = @opendir($path_to_cache))
		{
			while (($file = @readdir($dh)) !== false)
			{
				if($file != '.' && $file != '..' && !in_array($file, $exceptions))
				{
					$del = kleeja_unlink($path_to_cache . '/' . $file, true);
				}
			}
			@closedir($dh);
		}
	}
	else
	{
		if(strpos($name, 'tpl_') !== false && strpos($name, '.html') !== false)
		{
			$name = str_replace('.html', '', $name);
		}

		$del = true;
		$name = str_replace('.php', '', $name) . '.php';
		if (file_exists($path_to_cache . '/' . $name))
		{
			$del = kleeja_unlink ($path_to_cache . "/" . $name, true);
		}
	}

	return $del;
}

/**
* Try delete files or at least change its name.
* for those who have dirty hosting 
*/
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






/**
* Include language file
*/
function get_lang($name, $folder = '')
{
	global $config, $lang;

	($hook = kleeja_run_hook('get_lang_func')) ? eval($hook) : null; //run hook

	$name = str_replace('..', '', $name);
	if($folder != '')
	{
		$folder = str_replace('..', '', $folder);
		$name = $folder . '/' . $name;
	}

	$path = PATH . 'lang/' . $config['language'] . '/' . str_replace('.php', '', $name) . '.php';
	$s = defined('DEBUG') ? include_once($path) : @include_once($path);

	if($s === false)
	{
		//$pathen = PATH . 'lang/en/' . str_replace('.php', '', $name) . '.php';
		//$sen = defined('DEBUG') ? include_once($pathen) :  @include_once($pathen);
		//if($sen === false)
		//{
			big_error('There is no language file in the current path', 'lang/' . $config['language'] . '/' . str_replace('.php', '', $name) . '.php  not found');
		//}
	}

	return true;
}


/*
* Get fresh config value
* some time cache doesnt not work as well, so some important 
* events need fresh version of config values ...
*/
function get_config($name)
{
	global $dbprefix, $SQL, $d_groups, $userinfo;

	$table = "{$dbprefix}config c";

	#what if this config is a group-configs related ?
	$group_id_sql = '';
	if(array_key_exists($name, $d_groups[$userinfo['group_id']]['configs']))
	{
		$table = "{$dbprefix}groups_data c";
		$group_id_sql = " AND c.group_id=" . $userinfo['group_id'];
	}

	$query = array(
					'SELECT'	=> 'c.value',
					'FROM'		=> $table,
					'WHERE'		=> "c.name = '" . $SQL->escape($name) . "'" . $group_id_sql
				);

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);
	$return	= $v['value'];

	($hook = kleeja_run_hook('get_config_func')) ? eval($hook) : null; //run hook
	return $return;
}

/*
* Add new config option
* type: where does your config belone, 0 = system, genetal = has no specifc cat., other = other items.
* html: the input or radio to let the user type or choose from them, see the database:configs to understand.
* dynamic: every refresh of the page, the config data will be brought from db, not from the cache !
* plg_id: if this config belong to plugin .. see devKit. 
*/
function add_config($name, $value, $order = '0', $html = '', $type = '0', $plg_id = '0', $dynamic = false)
{
	global $dbprefix, $SQL, $config, $d_groups;
	
	if(get_config($name))
	{
		return true;
	}

	if($html != '' && $type == '0')
	{
		$type = 'other';
	}

	if($type == 'groups')
	{
		#add this option to all groups
		$group_ids = array_keys($d_groups);
		foreach($group_ids as $g_id)
		{
			$insert_query	= array(
									'INSERT'	=> '`name`, `value`, `group_id`',
									'INTO'		=> "{$dbprefix}groups_data",
									'VALUES'	=> "'" . $SQL->escape($name) . "','" . $SQL->escape($value) . "', group_id" . $g_id,
								);

			($hook = kleeja_run_hook('insert_sql_add_config_func_groups_data')) ? eval($hook) : null; //run hook

			$SQL->build($insert_query);
		}
	}

	$insert_query	= array(
							'INSERT'	=> '`name` ,`value` ,`option` ,`display_order`, `type`, `plg_id`, `dynamic`',
							'INTO'		=> "{$dbprefix}config",
							'VALUES'	=> "'" . $SQL->escape($name) . "','" . $SQL->escape($value) . "', '" . $SQL->real_escape($html) . "','" . intval($order) . "','" . $SQL->escape($type) . "','" . intval($plg_id) . "','"  . ($dynamic ? '1' : '0') . "'",
						);

	($hook = kleeja_run_hook('insert_sql_add_config_func')) ? eval($hook) : null; //run hook

	$SQL->build($insert_query);	

	if($SQL->affected())
	{
		delete_cache('data_config');
		$config[$name] = $value;
		return true;
	}

	return false;
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
		add_config($n, $m['value'], $m['order'], $m['html'], $m['type'], $m['plg_id'], $m['dynamic']);
	}

	return;
}

function update_config($name, $value, $escape = true, $group = false)
{
	global $SQL, $dbprefix, $d_groups, $userinfo;

	$value = ($escape) ? $SQL->escape($value) : $value;
	$table = "{$dbprefix}config";

	#what if this config is a group-configs related ?
	$group_id_sql = '';
	if(array_key_exists($name, $d_groups[$userinfo['group_id']]['configs']))
	{
		$table = "{$dbprefix}groups_data";
		if($group == -1)
		{
			$group_id_sql = ' AND group_id=' . $userinfo['group_id'];
		}
		else if($group)
		{
			$group_id_sql = ' AND group_id=' . intval($group);
		}	
	}

	$update_query	= array(
							'UPDATE'	=> $table,
							'SET'		=> "value='" . ($escape ? $SQL->escape($value) : $value) . "'",
							'WHERE'		=> 'name = "' . $SQL->escape($name) . '"' . $group_id_sql
					);

	($hook = kleeja_run_hook('update_sql_update_config_func')) ? eval($hook) : null; //run hook

	$SQL->build($update_query);
	if($SQL->affected())
	{
		if($table == "{$dbprefix}groups_data")
		{
			$d_groups[$userinfo['group_id']]['configs'][$name] = $value;
			delete_cache('data_groups');
			return true;
		}

		$config[$name] = $value;
		delete_cache('data_config');
		return true;
	}

	return false;
}

/*
* Delete config
*/
function delete_config($name) 
{
	if(is_array($name))
	{
		foreach($name as $n)
		{
			delete_config($n);
		}
		
		return;
	}

	global $dbprefix, $SQL, $d_groups, $userinfo;

	//
	// 'IN' doesnt work here with delete, i dont know why ? 
	//
	$delete_query	= array(
								'DELETE'	=> "{$dbprefix}config",
								'WHERE'		=>  "name  = '" . $SQL->escape($name) . "'"
						);
	($hook = kleeja_run_hook('del_sql_delete_config_func')) ? eval($hook) : null; //run hook
	
	$SQL->build($delete_query);

	if(array_key_exists($name, $d_groups[$userinfo['group_id']]['configs']))
	{
		$delete_query	= array(
									'DELETE'	=> "{$dbprefix}groups_data",
									'WHERE'		=>  "name  = '" . $SQL->escape($name) . "'"
							);
		($hook = kleeja_run_hook('del_sql_delete_config_func2')) ? eval($hook) : null; //run hook

		$SQL->build($delete_query);
	}

	if($SQL->affected())
	{
		return true;
	}

	return false;
}

//
//update words to lang
//
function update_olang($name, $lang = 'en', $value)
{
	global $SQL, $dbprefix;

	$value = ($escape) ? $SQL->escape($value) : $value;

	$update_query	= array(
							'UPDATE'	=> "{$dbprefix}lang",
							'SET'		=> "trans='" . $SQL->escape($value) . "'",
							'WHERE'		=> 'word = "' . $SQL->escape($name) . '", lang_id = "' .  $SQL->escape($lang) . '"'
					);
	($hook = kleeja_run_hook('update_sql_update_olang_func')) ? eval($hook) : null; //run hook

	$SQL->build($update_query);
	if($SQL->affected())
	{
		$olang[$name] = $value;
		return true;
	}

	return false;
}

//
//add words to lang
//
function add_olang($words = array(), $lang = 'en', $plg_id = '0')
{
	global $dbprefix, $SQL;

	foreach($words as $w=>$t)
	{
		$insert_query = array(
								'INSERT'	=> 'word ,trans ,lang_id, plg_id',
								'INTO'		=> "{$dbprefix}lang",
								'VALUES'	=> "'" . $SQL->escape($w) . "','" . $SQL->real_escape($t) . "', '" . $SQL->escape($lang) . "','" . intval($plg_id) . "'",
						);
		($hook = kleeja_run_hook('insert_sql_add_olang_func')) ? eval($hook) : null; //run hook
		$SQL->build($insert_query);
	}

	delete_cache('data_lang');
	return;
}

//
//delete words from lang
//
function delete_olang ($words = '', $lang='en', $plg_id = '') 
{
	global $dbprefix, $SQL;

	if(is_array($words))
	{
		foreach($words as $w)
		{
			delete_olang ($w, $lang);
		}

		return;
	}

	$delete_query	= array(
							'DELETE'	=> "{$dbprefix}lang",
							'WHERE'		=> "word = '" . $SQL->escape($words) . "' AND lang_id = '" . $SQL->escape($lang) . "'"
						);

	if(isset($plg_id) && !empty($plg_id))
	{
		$delete_query['WHERE'] = "plg_id = '" . intval($plg_id) . "'";
	}

	($hook = kleeja_run_hook('del_sql_delete_olang_func')) ? eval($hook) : null; //run hook
		
	$SQL->build($delete_query);

	if($SQL->affected())
	{
		return true;
	}

	return false;
}



//
// administarator sometime need some files and delete other .. we
// do that for him .. becuase he has no time .. :)   
//last_down - $config[del_f_day]
//
function klj_clean_old_files($from = 0)
{
	global $config, $SQL, $stat_last_f_del, $dbprefix;
	
	$return = false;
	($hook = kleeja_run_hook('klj_clean_old_files_func')) ? eval($hook) : null; //run hook

	if((int) $config['del_f_day'] <= 0 || $return)
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
		
		#This feature will work only if id_form is not empty or direct !
		$query = array(
					'SELECT'	=> 'f.id, f.last_down, f.name, f.type, f.folder, f.time, f.size, f.id_form',
					'FROM'		=> "{$dbprefix}files f",
					'WHERE'		=> "f.last_down < $totaldays AND f.time < $not_today AND f.id > $from AND f.id_form <> '' AND f.id_form <> 'direct'",
					'ORDER BY'	=> 'f.id ASC',
					'LIMIT'		=> '20',
					);

		($hook = kleeja_run_hook('qr_select_klj_clean_old_files_func')) ? eval($hook) : null; //run hook

		$result	= $SQL->build($query);					

		$num_of_files_to_delete = $SQL->num_rows($result);
		if($num_of_files_to_delete == 0)
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

		$last_id_from = $files_num = $imgs_num = $real_num = $sizes = 0;
		$ids = array();
		$ex_ids =  array();
		//$ex_types = explode(',', $config['livexts']);
        

		($hook = kleeja_run_hook('beforewhile_klj_clean_old_files_func')) ? eval($hook) : null; //run hook
		
        
        //phpfalcon plugin
        $exlive_types = explode(',', $config['imagefolderexts']);
        
		//delete files 
		while($row=$SQL->fetch_array($result))
		{
			$continue = true;
			$real_num++;
			$last_id_from = $row['id'];
			$is_image = in_array(strtolower(trim($row['type'])), array('gif', 'jpg', 'jpeg', 'bmp', 'png')) ? true : false;

			/*
			//excpetions
			if(in_array($row['type'], $ex_types) || $config['id_form'] == 'direct')
			{
				$ex_ids[] = $row['id'];
				continue;
			}
			*/

			//excpetions
			//if($config['id_form'] == 'direct')
			//{
				//$ex_ids[] = $row['id'];
				//move on
				//continue;
			//}

			//your exepctions
            ($hook = kleeja_run_hook('while_klj_clean_old_files_func')) ? eval($hook) : null; //run hook
            
            
            //phpfalcon plugin
            if(in_array($row['type'], $exlive_types))
            {
                $ex_ids[] = $row['id'];
                if($real_num != $num_of_files_to_delete)
                {
                    $continue = false;
                }
            }
            
			if($continue)
			{
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
				if($is_image)
				{
					$imgs_num++;
				}
				else
				{
					$files_num++;
				}
				$sizes += $row['size'];
			}
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
									'SET'		=> "sizes=sizes-$sizes,files=files-$files_num, imgs=imgs-$imgs_num",
									);

			($hook = kleeja_run_hook('qr_del_delf_old_files')) ? eval($hook) : null; //run hook

			$SQL->build($query_del);
			$SQL->build($update_query);
		}

		update_config('klj_clean_files_from', $last_id_from);
    } //stat_del
}

/**
* klj_clean_old 
*/
function klj_clean_old($table, $for = 'all')
{
	global $SQL, $config, $dbprefix;

	$days = intval(time() - 3600 * 24 * intval($for));

	$query = array(
					'SELECT'	=> 'f.id, f.time',
					'FROM'		=> "`{$dbprefix}" . $table . "` f",
					'ORDER BY'	=> 'f.id ASC',
					'LIMIT'		=> '20',
					);


	if($for != 'all')
	{
		$query['WHERE']	= "f.time < $days";
	}


	
	($hook = kleeja_run_hook('qr_select_klj_clean_old_func')) ? eval($hook) : null; //run hook

	$result	= $SQL->build($query);					
	$num_to_delete = $SQL->num_rows($result);
	if($num_to_delete == 0)
	{
		$t = $table == 'call' ? 'calls' : $table;
		update_config('queue', preg_match('!:del_' . $for . $t . ':!i', '', $config['queue']));
		$SQL->freeresult($result);
		return;
	}

	$ids = array();
	$num = 0;
	while($row=$SQL->fetch_array($result))
	{
		$ids[] = $row['id'];
		$num++;
	}

	$SQL->freeresult($result);

	$query_del	= array(
							'DELETE'	=> "`" . $dbprefix . $table . "`",
							'WHERE'	=> "id IN (" . implode(',', $ids) . ")"
						);

	($hook = kleeja_run_hook('qr_del_delf_old_table')) ? eval($hook) : null; //run hook

	$SQL->build($query_del);



	return;
}

/**
* get_ip() for the user
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

	$return = preg_replace('/[^0-9a-z.]/i', '', $ip);
	($hook = kleeja_run_hook('del_sql_delete_olang_func')) ? eval($hook) : null; //run hook
	return $return;
}

//check captcha field after submit
function kleeja_check_captcha()
{
	global $config;
	if((int) $config['enable_captcha'] == 0)
	{
		return true;
	}

	$return = false;
	if(!empty($_SESSION['klj_sec_code']) && p('kleeja_code_answer') != '')	
	{
		if($_SESSION['klj_sec_code'] == trim(g('kleeja_code_answer')))
		{
			unset($_SESSION['klj_sec_code']);
			$return = true;
		}
	}

	($hook = kleeja_run_hook('kleeja_check_captcha_func')) ? eval($hook) : null; //run hook
	return $return;
}


/**
* for logging and testing
* enables only in DEV. stage !
*/
function kleeja_log($text, $reset = false)
{
	if(!defined('DEV_STAGE'))
	{
		return;
	}

	$log_file = PATH . 'cache/kleeja_log.log';
    $l_c = @file_get_contents($log_file);
	$fp = @fopen($log_file, 'w');
	@fwrite($fp, $text . " [time : " . date('H:i a, d-m-Y') . "] \r\n" . $l_c);
	@fclose($fp);
	return;
}


/**
* user_can, used for checking the acl for the current user
*/
function user_can($acl_name, $group_id = 0)
{
	global $d_groups, $userinfo;

	if($group_id == 0)
	{
		$group_id = $userinfo['group_id'];
	}

	return (bool) $d_groups[$group_id]['acls'][$acl_name];
}

/*
 * this function for the future , made specially for kleeja 
 */
function get_Domain_only($domain)
{
    if(strpos($domain, '.' )  !== false)
    {
        $domain = parse_url($domain,  PHP_URL_HOST);
        $darray = explode('.', $domain);

        if(count($darray) == 2)
        {   
            $domain = $darray[0];
        }
        else
        {   
            $domain = $darray[1];
        }
    }

    return $domain;
}


/**
 *
 * We need to develop this .. add ftp - etc
 * 
 * Simple script to extract files from a .tar archive
 *
 * @param string $file The .tar archive filepath
 * @param string $dest [optional] The extraction destination filepath, defaults to "./"
 * @return boolean Success or failure
 */
function untar($file, $dest = "./") 
{
	if (!is_readable($file))
	{
	 	return false;
	 }

	$filesize = @filesize($file);
	
	// Minimum 4 blocks
	if ($filesize <= 512*4)
	{ 
		return false;
	}
	
	if (!preg_match("/\/$/", $dest)) 
	{
		// Force trailing slash
		$dest .= "/";
	}
	
	//Ensure write to destination
	if (!file_exists($dest)) 
	{
		if (!mkdir($dest, 0777, true)) 
		{
			return false;
		}
	}
	
	$total = 0;

	if($fh = @fopen($file, 'rb'))
	{
		$files = array();
		while (($block = fread($fh, 512)) !== false) 
		{
		
			$total += 512;
			$meta = array();
			
			// Extract meta data
			// http://www.mkssoftware.com/docs/man4/tar.4.asp
			$meta['filename'] = trim(substr($block, 0, 99));
			$meta['mode'] = octdec((int)trim(substr($block, 100, 8)));
			$meta['userid'] = octdec(substr($block, 108, 8));
			$meta['groupid'] = octdec(substr($block, 116, 8));
			$meta['filesize'] = octdec(substr($block, 124, 12));
			$meta['mtime'] = octdec(substr($block, 136, 12));
			$meta['header_checksum'] = octdec(substr($block, 148, 8));
			$meta['link_flag'] = octdec(substr($block, 156, 1));
			$meta['linkname'] = trim(substr($block, 157, 99));
			$meta['databytes'] = ($meta['filesize'] + 511) & ~511;
		
			if ($meta['link_flag'] == 5) 
			{
				// Create folder
				@mkdir($dest . $meta['filename'], 0777, true);
				@chmod($dest . $meta['filename'], $meta['mode']);
			}
		
			if ($meta['databytes'] >= 0 && $meta['header_checksum'] != 0) 
			{
				$block = @fread($fh, $meta['databytes']);
				// Extract data
				$data = substr($block, 0, $meta['filesize']);

				// Write data and set permissions
				if (false !== ($ftmp = @fopen($dest . $meta['filename'], 'wb'))) 
				{
					@flock($ftmp, LOCK_EX); // exlusive look
					@fwrite($ftmp, $data);
					@fclose($ftmp);
					//@touch($dest . $meta['filename'], $meta['mtime'], $meta['mtime']);
				
					if ($meta['mode'] == 0744)
					{
						$meta['mode'] = 0644;
					}
				
					@chmod($dest . $meta['filename'], $meta['mode']);
				}

				$total += $meta['databytes'];
				$files[] = $meta;
				
			}


		
			if ($total >= $filesize-1024) 
			{
				return $files;
			}
		}
	}

	return false;
}


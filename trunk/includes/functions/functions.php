<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


/**
* @ignore
*/
if (!defined('IN_COMMON'))
{
	exit();
}



/**
 * Is given _GET variable exists?
 * 
 * @since 2.0
 * @param string $name The name of _GET variable
 * @return bool
 */
function ig($name)
{
	return isset($_GET[$name]) ? true : false;
}

/**
 * Is given _POST variable exists?
 * 
 * @since 2.0
 * @param string $name The name of _POST variable
 * @return bool
 */
function ip($name)
{
	return isset($_POST[$name]) ? true : false;
}

/**
 *  clean _GET variable if exists and return it
 * 
 * @since 2.0
 * @param string $name The name of _GET variable
 * @param string $type The type of the varaible, str or int
 * @param mixed $default_value [optional] The default value to be return if not existed 
 * @return string|bool
 */
function g($name, $type = 'str', $default_value = false)
{
	return isset($_GET[$name]) ? clean_var($_GET[$name], $type) : $default_value;
}

/**
 * clean _POST variable if exists and return it
 * 
 * @since 2.0
 * @param string $name The name of _POST variable
 * @param string $type The type of the varaible, str or int
 * @param mixed $default_value [optional] The default value to be return if not existed 
 * @return string|bool
 */
function p($name, $type = 'str', $default_value = false)
{
	return isset($_POST[$name]) ? clean_var($_POST[$name], $type) : $default_value;
}

/**
 * Clean variable according to the selected type
 *
 * @since 2.0
 * @param mixed $var the variable to be cleaned
 * @param str $type Validate and clean variable according to this select, string, email ..
 * @return mixed
 */
function clean_var($var, $type = 'str')
{
	$var = trim($var);
	switch($type)
	{
		default:
		case 'str': case 'string':
			return htmlspecialchars($var);
		break;
		case 'int': case 'number':
			return intval($var);
		break;
		case 'mail': case 'email':
			return !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i', $var) ? false : strtolower($var);
		break;
		case 'bool':
			return (bool) $var;
		break;
	}
	return '';
}

/**
* For recording who onlines now .. 
* TODO: move to usr class
*/
function kleeja_detecting_bots()
{
	global $SQL, $user, $dbprefix, $config, $klj_session;

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
function kleeja_run_hook($hook_name)
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
	$s = defined('DEBUG') ? include($path) : @include($path);

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
function add_config($name, $value = '', $order = 0, $field = '', $type = '0', $dynamic = false)
{
	#if bulk adding
	if(is_array($name))
	{
		foreach($name as $n=>$v)
		{
			add_config($n, $v['order'], $v['field'], $v['type'], $v['dynamic']);
		}

		return;
	}

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
									'VALUES'	=> "'" . $SQL->escape($name) . "','" . $SQL->escape($value) . "', " . $g_id,
								);

			($hook = kleeja_run_hook('insert_sql_add_config_func_groups_data')) ? eval($hook) : null; //run hook

			$SQL->build($insert_query);
		}
	}

	$insert_query	= array(
							'INSERT'	=> '`name` ,`value` ,`option` ,`display_order`, `type`, `dynamic`',
							'INTO'		=> "{$dbprefix}config",
							'VALUES'	=> "'" . $SQL->escape($name) . "','" . $SQL->escape($value) . "', '" . $SQL->escape($field) . "', " . intval($order) . ",'" . $SQL->escape($type) . "','"  . ($dynamic ? '1' : '0') . "'",
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


function update_config($name, $value = '', $escape = true, $group = false)
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


/**
* Get the current IP of the user
* TODO: move to usr class 
* @return string
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

/**
 * Check if the given value of CAPTCHA is valid
 *
 * @return bool
 */
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
* Logging and testing, enabled only in DEV. stage !
*
* @param string $text The string you want to save to the log file
* @return bool
*/
function kleeja_log($text, $reset = false)
{
	#if not in development stage, abort
	if(!defined('DEV_STAGE'))
	{
		return false;
	}

	$log_file = PATH . 'cache/kleeja_log.log';
    $l_c = @file_get_contents($log_file);
	$fp = @fopen($log_file, 'w');
	@fwrite($fp, $text . " [time : " . date('H:i a, d-m-Y') . "] \r\n" . $l_c);
	@fclose($fp);
	return true;
}


/**
* Used for checking the acl for the current user
*TODO: move to usr class
* @param string $acl_name The privilege you want check if this group of user has or not
* @param int $group_id [optional] The group you want to check agaist, if not given will 
* use the current user group id.
* @return bool
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

/**
 * Get domain from a url
 * 
 * @param string $url The link you want to get the domain from
 * @return mixed
 */
function get_domain($url)
{
	$pieces = parse_url($url);
	$domain = isset($pieces['host']) ? $pieces['host'] : '';
	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs))
	{
		return $regs['domain'];
 	}
 	return false;
}


/** 
 * Extract files from a .tar archive
 *
 * @param string $file The .tar archive filepath
 * @param string $dest [optional] The extraction destination filepath, defaults to "./"
 * @return bool
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



function garbage_collection()
{
	if(defined('garbage_collection_done'))
	{
		return true;
	}
	
	global $SQL;
	
	$SQL->close();
	
	define('garbage_collection_done', true);
}
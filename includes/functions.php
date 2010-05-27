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
* To return file size in propriate format
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

/**
* For recording who onlines now .. 
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
}
	

/**
* For ban ips .. 
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
* is plugin installed ?
*/
function kleeja_plugin_exists($plugin_name)
{
	global $SQL, $dbprefix;
	
	$plugin_name = str_replace(' ', '-', $plugin_name); 

	$query = array(
					'SELECT'	=> 'p.plg_id',
					'FROM'		=> "{$dbprefix}plugins p",
					'WHERE'		=> "p.plg_name = '" . $SQL->escape($plugin_name) . "'",
				);
		
		($hook = kleeja_run_hook('qr_select_kleeja_plugin_exists_func')) ? eval($hook) : null; //run hook
		
		$result	= $SQL->build($query);					
		$num = $SQL->num_rows($result);
		return ($num == 0) ? false : true;
}

/**
* Return current page url
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

/**
* send email
*/
function _sm_mk_utf8($text)
{
	return "=?UTF-8?B?" . kleeja_base64_encode($text) . "?=";
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


/**
* Get remote files
* (c) punbb
*/
function fetch_remote_file($url, $save_in = false, $timeout = 20, $head_only = false, $max_redirects = 10)
{
	($hook = kleeja_run_hook('kleeja_fetch_remote_file_func')) ? eval($hook) : null; //run hook

	// Quite unlikely that this will be allowed on a shared host, but it can't hurt
	if (function_exists('ini_set'))
		@ini_set('default_socket_timeout', $timeout);
	$allow_url_fopen = function_exists('ini_get') ? strtolower(@ini_get('allow_url_fopen')) : strtolower(@get_cfg_var('allow_url_fopen'));

	if(function_exists('curl_init'))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, $head_only);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0; Kleeja)');
		
		// Grab the page
		$data = @curl_exec($ch);
		$responce_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		// Process 301/302 redirect
		if ($data !== false && ($responce_code == '301' || $responce_code == '302') && $max_redirects > 0)
		{
			$headers = explode("\r\n", trim($data));
			foreach ($headers as $header)
			{
				if (substr($header, 0, 10) == 'Location: ')
				{
					$responce = fetch_remote_file(substr($header, 10), $save_in, $timeout, $head_only, $max_redirects - 1);
					if ($head_only)
					{
						if($responce != false)
						{
							$headers[] = $responce;
						}
						return $headers;
					}
					else
					{
						return false;
					}
				}
			}
		}

		// Ignore everything except a 200 response code
		if ($data !== false && $responce_code == '200')
		{
			if ($head_only)
			{
				return explode("\r\n", str_replace("\r\n\r\n", "\r\n", trim($data)));
			}
			else
			{
				preg_match('#HTTP/1.[01] 200 OK#', $data, $match, PREG_OFFSET_CAPTURE);
				$last_content = substr($data, $match[0][1]);
				$content_start = strpos($last_content, "\r\n\r\n");
				if ($content_start !== false)
				{
					return substr($last_content, $content_start + 4);
				}
			}
		}
		//<--
	}
	// fsockopen() is the second best thing
	else if(function_exists('fsockopen'))
	{
	    $url_parsed = parse_url($url);
	    $host = $url_parsed['host'];
	    $port = empty($url_parsed['port']) or $url_parsed['port'] == 0 ? 80 : $url_parsed['port'];
		$path = $url_parsed['path'];
	    
		if (isset($url_parsed["query"]) && $url_parsed["query"] != '')
		{
			$path .= '?' . $url_parsed['query'];
		}
	
	    if(!$fp = @fsockopen($host, $port, $errno, $errstr, $timeout))
		{
			return false;
		}
		
		// Send a standard HTTP 1.0 request for the page
		fwrite($fp, ($head_only ? 'HEAD' : 'GET') . " $path HTTP/1.0\r\n");
		fwrite($fp, "Host: $host\r\n");
		fwrite($fp, 'User-Agent: Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0; Kleeja)' . "\r\n");
		fwrite($fp, 'Connection: Close'."\r\n\r\n");
			
		stream_set_timeout($fp, $timeout);
		$stream_meta = stream_get_meta_data($fp);
		
		//let's open new file to save it in.
		if($save_in)
		{
			$fp2 = @fopen($save_in, "w");
		}
		
		// Fetch the response 1024 bytes at a time and watch out for a timeout
		$in = false;
		while (!feof($fp) && !$stream_meta['timed_out'])
		{
			$in .= fgets($fp, 1024);
			if($save_in)
			{
				@fwrite($fp2, $in);
			}
			
			$stream_meta = stream_get_meta_data($fp);
		}

		fclose($fp);

		if($save_in)
		{
			unset($in);
			@fclose($fp2);
			return true;
		}
		
		// Process 301/302 redirect
		if ($in !== false && $max_redirects > 0 && preg_match('#^HTTP/1.[01] 30[12]#', $in))
		{
			$headers = explode("\r\n", trim($in));
			foreach ($headers as $header)
			{
				if (substr($header, 0, 10) == 'Location: ')
				{
					$responce = get_remote_file(substr($header, 10), $save_in, $timeout, $head_only, $max_redirects - 1);
					if ($responce != false)
					{
						$headers[] = $responce;
					}
					return $headers;
				}
			}
		}
		
		// Ignore everything except a 200 response code
		if ($in !== false && preg_match('#^HTTP/1.[01] 200 OK#', $in))
		{
			if ($head_only)
			{
				return explode("\r\n", trim($in));
			}	
			else
			{
				$content_start = strpos($in, "\r\n\r\n");
				if ($content_start !== false)
				{
					return substr($in, $content_start + 4);
				}
			}
		}
		return $in;
	}
	// Last case scenario, we use file_get_contents provided allow_url_fopen is enabled (any non 200 response results in a failure)
	else if (in_array($allow_url_fopen, array('on', 'true', '1')))
	{
		// PHP5's version of file_get_contents() supports stream options
		if (version_compare(PHP_VERSION, '5.0.0', '>='))
		{
			// Setup a stream context
			$stream_context = stream_context_create(
				array(
					'http' => array(
						'method'		=> $head_only ? 'HEAD' : 'GET',
						'user_agent'	=> 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0; Kleeja)',
						'max_redirects'	=> $max_redirects + 1,	// PHP >=5.1.0 only
						'timeout'		=> $timeout	// PHP >=5.2.1 only
					)
				)
			);

			$content = @file_get_contents($url, false, $stream_context);
		}
		else
		{
			$content = @file_get_contents($url);
		}

		// Did we get anything?
		if ($content !== false)
		{
			// Gotta love the fact that $http_response_header just appears in the global scope (*cough* hack! *cough*)
			if ($head_only)
			{
				return $http_response_header;
			}
			return $content;
		}
	}
	
	return false;
}


/**
* Get the mime groups or one of them
*/
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

/**
* Admin. function for extensions types
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

/**
* Delete cache
*/
function delete_cache($name, $all=false)
{
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
				if($file != "." && $file != ".." && $file != ".htaccess" && $file != "index.html" && $file != "php.ini" && $file != 'styles_cached.php')
				{
					$del = kleeja_unlink($path_to_cache . '/' . $file, true);
				}
			}
			@closedir($dh);
		}
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
* Get mime header
* 1rc6+ 
*/
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
		"psd" => "image/psd",
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
	
	if(file_exists($path))
	{
		include_once($path);
	}
	else
	{
		if(file_exists(PATH . 'lang/en/' . str_replace('.php', '', $name) . '.php'))
		{
			include_once(PATH . 'lang/en/' . str_replace('.php', '', $name) . '.php');
		}
		else
		{
			big_error('There is no language file in the current path', '' . $path . ' not found');
		}
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
	global $dbprefix, $SQL;

	$query = array(
					'SELECT'	=> 'c.value',
					'FROM'		=> "{$dbprefix}config c",
					'WHERE'		=> "c.name = '" . $SQL->escape($name) . "'"
				);

	$result = $SQL->build($query);
	$v = $SQL->fetch($result);
	return $v['value'];
}

/*
* Add new config option
*/
function add_config ($name, $value, $order = '0', $html = '', $type = 'other', $plg_id = '0')
{
	global $dbprefix, $SQL, $config;
	
	if(get_config($name))
	{
		return true;
	}

	$insert_query	= array(
							'INSERT'	=> '`name` ,`value` ,`option` ,`display_order`, `type`, `plg_id`',
							'INTO'		=> "{$dbprefix}config",
							'VALUES'	=> "'" . $SQL->escape($name) . "','" . $SQL->escape($value) . "', '" . $SQL->real_escape($html) . "','" . intval($order) . "','" . $SQL->escape($type) . "','" . intval($plg_id) . "'",
						);

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
		add_config($n, $m['value'], $m['order'], $m['html'], $m['type']);
	}
	
	return;
}

function update_config($name, $value, $escape = true)
{
	global $SQL, $dbprefix;

	$value = ($escape) ? $SQL->escape($value) : $value;

	$update_query	= array(
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

	return false;
}

/*
* Delete config
*/
function delete_config ($name) 
{
	global $dbprefix, $SQL;

	if(is_array($name))
	{
		foreach($name as $n)
		{
			delete_config($n);
		}
		
		return;
	}

	//
	// 'IN' doesnt work here with delete, i dont know why ? 
	//

	$delete_query	= array(
								'DELETE'	=> "{$dbprefix}config",
								'WHERE'		=>  "name  = '" . $SQL->escape($name) . "'"
						);

	$SQL->build($delete_query);
	
	if($SQL->affected())
	{
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
								'INSERT'	=> '`word` ,`trans` ,`lang_id`, `plg_id`',
								'INTO'		=> "{$dbprefix}lang",
								'VALUES'	=> "'" . $SQL->escape($w) . "','" . $SQL->real_escape($t) . "', '" . $SQL->escape($lang) . "','" . intval($plg_id) . "'",
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
		
		
		$query = array(
					'SELECT'	=> 'f.id, f.last_down, f.name, f.type, f.folder, f.time, f.size',
					'FROM'		=> "{$dbprefix}files f",
					'WHERE'		=> "f.last_down < $totaldays AND f.time < $not_today AND f.id > $from",
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
		
		$last_id_from = $num = $real_num = $sizes = 0;
		$ids = array();
		$ex_ids =  array();
		//$ex_types = explode(',', $config['livexts']);
		
		($hook = kleeja_run_hook('beforewhile_klj_clean_old_files_func')) ? eval($hook) : null; //run hook
		
		//delete files 
		while($row=$SQL->fetch_array($result))
		{
			$real_num++;
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
//for logging
//
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

//
//Browser detection system
//returns whether or not the visiting browser is the one specified [part of kleeja style system]
//
function is_browser($b)
{
	//is there , which mean -OR-
	if(strpos($b, ',') !== false)
	{
		$e = explode(',', $b);
		foreach($e as $n)
		{
			if(is_browser(trim($n)))
			{
				return true;
			}
		}
		
		return false;
	}
	
    //if no agent, let's take the worst case
	$u_agent = (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars((string) strtolower($_SERVER['HTTP_USER_AGENT'])) : (function_exists('getenv') ? getenv('HTTP_USER_AGENT') : '');
	$t = trim(preg_replace('/[0-9.]/', '', $b));
	$r = trim(preg_replace('/[a-z]/', '', $b));
	switch($t)
	{
		case 'ie':
			return strpos($u_agent, trim('msie ' . $r)) !== false ? true : false;
		break;
		case 'firefox':
			return strpos(str_replace('/', ' ', $u_agent), trim('firefox ' . $r)) !== false ? true : false;
		break;
		case 'safari':
			return strpos($u_agent, trim('safari/' . $r)) !== false ? true : false;
		break;
		case 'chrome':
			return strpos($u_agent, trim('chrome ' . $r)) !== false ? true : false;
		break;
		case 'flock':
			return strpos($u_agent, trim('flock ' . $r)) !== false ? true : false;
		break;
		case 'opera':
			return strpos($u_agent, trim('opera ' . $r)) !== false ? true : false;
		break;
		case 'konqueror':
			return strpos($u_agent, trim('konqueror/' . $r)) !== false ? true : false;
		break;
		case 'mozilla':
			return strpos($u_agent, trim('gecko/' . $r)) !== false ? true : false;
		break;
		case 'webkit':
			return strpos($u_agent, trim('applewebkit/' . $r)) !== false ? true : false;
		break;
	}
    return false;
}

/**
* Return the first and last seek of range to be flushed.
*/
function kleeja_set_range($range, $filesize)
{
	$dash	= strpos($range, '-');
	$first	= trim(substr($range, 0, $dash));
	$last	= trim(substr($range, $dash+1));
	if (!$first)
	{
		$suffix	= $last;
		$last	= $filesize-1;
		$first	= $filesize-$suffix;
		if($first < 0)
		{
			$first = 0;
		}
	}
	else
	{
		if(!$last || $last > $filesize-1)
		{
			$last = $filesize-1;
		}
	}

	if($first > $last)
	{
		//unsatisfiable range
		header("Status: 416 Requested range not satisfiable");
		header("Content-Range: */$filesize");
		exit;
	}
	
	return array($first, $last);
}

/**
* Outputs up to $bytes from the file $file to standard output,
* $buffer_size bytes at a time.
*/
function kleeja_buffere_range($file, $bytes, $buffer_size = 1024)
{
	$bytes_left = $bytes;
	while($bytes_left >0 && !feof($file))
	{
		if($bytes_left > $buffer_size)
		{
			$bytes_to_read = $buffer_size;
		}
		else
		{
			$bytes_to_read = $bytes_left;
		}

		$bytes_left	-= $bytes_to_read;
		$contents	= fread($file, $bytes_to_read);
		echo $contents;
		flush();
	}
}

<?php
/**
*
* @package Kleeja
* @version $Id: usr.php 1889 2012-08-21 07:54:23Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
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
 * Get mime header
 * 
 * @param string $filename the filename
 * @return string The mime type
 */
function get_mime_for_header($filename)
{
	$mimetype = '';

	if (function_exists('mime_content_type'))
	{
		$mimetype = mime_content_type($filename);
	}

	#default
	if (!$mimetype || $mimetype == 'application/octet-stream')
	{
		$mimetype = 'application/octetstream';
	}

	($hook = kleeja_run_hook('get_mime_for_header_func')) ? eval($hook) : null; //run hook
	return $mimetype;
}


/**
* Get remote files
*
* @
* @author punbb and kleeja team
*/
function fetch_remote_file($url, $save_in = false, $timeout = 20, $head_only = false, $max_redirects = 10, $binary = false)
{
	($hook = kleeja_run_hook('kleeja_fetch_remote_file_func')) ? eval($hook) : null; //run hook

	#Quite unlikely that this will be allowed on a shared host, but it can't hurt
	if (function_exists('ini_set'))
	{
		@ini_set('default_socket_timeout', $timeout);
	}
	$allow_url_fopen = function_exists('ini_get') ? strtolower(@ini_get('allow_url_fopen')) : strtolower(@get_cfg_var('allow_url_fopen'));

	if(function_exists('curl_init') && !$save_in)
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

	}
	// fsockopen() is the second best thing
	else if(function_exists('fsockopen'))
	{
	    $url_parsed = parse_url($url);
	    $host = $url_parsed['host'];
	    $port = empty($url_parsed['port']) || $url_parsed['port'] == 0 ? 80 : $url_parsed['port'];
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
			$fp2 = @fopen($save_in, 'w' . ($binary ? '' : ''));
		}

		// Fetch the response 1024 bytes at a time and watch out for a timeout
		$in = false;
		$h = false;
		$s = '';
		while (!feof($fp) && !$stream_meta['timed_out'])
		{
			$s = fgets($fp, 1024);
			if($save_in)
			{
					if($s == "\r\n")
					{
						$h = true;
						continue;
					}

					if($h)
					{
						@fwrite($fp2, $s);
					}
			}
			
			$in .= $s;
			$stream_meta = stream_get_meta_data($fp);
		}

		fclose($fp);

		if($save_in)
		{
			unset($in);
			@fclose($fp2);
			return true;
		}

		#Process 301/302 redirect
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

		#Ignore everything except a 200 response code
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
	#Last case scenario, we use file_get_contents provided allow_url_fopen is enabled (any non 200 response results in a failure)
	else if (in_array($allow_url_fopen, array('on', 'true', '1')))
	{
		#PHP5's version of file_get_contents() supports stream options
		if (version_compare(PHP_VERSION, '5.0.0', '>='))
		{
			#Setup a stream context
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

		# Did we get anything?
		if ($content !== false)
		{
			#Gotta love the fact that $http_response_header just appears in the global scope (*cough* hack! *cough*)
			if ($head_only)
			{
				return $http_response_header;
			}
			
			if($save_in)
			{
				$fp2 = fopen($save_in, 'w' . ($binary ? 'b' : ''));
				@fwrite($fp2, $content);
				@fclose($fp2);
				unset($content);
				return true;
			}

			return $content;
		}
	}

	return false;
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


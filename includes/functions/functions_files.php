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
 * @param string $ext the extension of file i.e. gif
 * @return string The mime type
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
		"bmp" => "image/bmp", # this is not a good mime, but it work anyway
		//"bmp"	=> "image/x-ms-bmp", # @see bugs.php.net/47359
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
		'apk' => 'application/vnd.android.package-archive',
		//add more mime here
	);

	//return mime
	$ext = strtolower($ext);
    if(in_array($ext, array_keys($mime_types)))
    {
		$return = $mime_types[$ext];
	}
	else
	{
    	$return = 'application/force-download';  
	}

	($hook = kleeja_run_hook('get_mime_for_header_func')) ? eval($hook) : null; //run hook
	return $return;
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


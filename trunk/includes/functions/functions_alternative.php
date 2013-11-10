<?php
/**
*
* @package Kleeja
* @version $Id: functions_display.php 1281 2009-11-27 08:43:12Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


/**
* After a lot of work, we faced many hosts who use a old PHP version, or 
* they disabled many general functions ... 
* so, this file contains those type of functions.
*/


/**
 * @ignore
 */
if (!defined('IN_COMMON'))
{
	exit();
}



if(!function_exists('json_encode')) 
{
	/**
	 * Alternative Json encoding function, since some servers miss it
	 *
	 * @param mixed $val the value to be encoded in JSON
	 * @return string JSON fotmat string 
	 * @link http://php.net/json_encode
	 */
	function json_encode($val)
	{
	    if (is_string($val))
		{
			return '"'.addslashes($val).'"';
		}
	    elseif (is_numeric($val))
		{
			return $val;
		}
	    elseif ($val === null)
		{
			return 'null';
		}
	    elseif ($val === true)
		{
			return 'true';
		}
	    elseif ($val === false)
		{
			return 'false';
		}

	    $assoc = false;
	    $i = 0;
	    foreach ($val as $k=>$v)
		{
	        if ($k !== $i++)
			{
	            $assoc = true;
	            break;
	        }
	    }

	    $res = array();
	    foreach ($val as $k=>$v)
		{
	        $v = json_encode($v);
	        if ($assoc)
			{
	            $k = '"'.addslashes($k).'"';
	            $v = $k.':'.$v;
	        }
	        $res[] = $v;
	    }

	    $res = implode(',', $res);
	    return $assoc ? '{' . $res . '}' : '[' . $res . ']';
	}
}
 


if(!function_exists('htmlspecialchars_decode'))
{
	/**
	* Alternative function htmlspecialchars_decode
	*
	* @param string $string The string to be decoded
	* @param int $style [optional] type of decoding
	* @return string The decoded string
	* @link http://php.net/htmlspecialchars_decode
	*/
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


if(!function_exists('str_split'))
{
	/**
	* Alternative function str_split
	*
	* @param string $string The input string
	* @param int $string_length Maximum length of the chunk
	* @return array The spilted string into parts
	* @link http://php.net/str_split
	*/
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


if(!function_exists('base64_encode'))
{
	/**
	* Alternative function base64_encode
	*
	* @param string $string The input string to be encoded in base64
	* @return string String encoded in base64
	* @link http://php.net/base64_encode
	*/
	function base64_encode($string = '')
	{
		if(!function_exists('convert_binary_str'))
		{
			function convert_binary_str($string)
			{
				if (strlen($string) <= 0)
					return;

				$tmp = decbin(ord($string[0]));
				$tmp = str_repeat('0', 8-strlen($tmp)) . $tmp;
				return $tmp . convert_binary_str(substr($string,1));
			}
		}

		$binval = convert_binary_str($string);
		$final = '';
		$start = 0;

		while ($start < strlen($binval))
		{
			if (strlen(substr($binval,$start)) < 6)
				$binval .= str_repeat("0", 6-strlen(substr($binval,$start)));
			$tmp = bindec(substr($binval, $start,6));
			if ($tmp < 26)
				$final .= chr($tmp+65);
			elseif ($tmp > 25 && $tmp < 52)
				$final .= chr($tmp+71);
			elseif ($tmp == 62)
				$final .= "+";
			elseif ($tmp == 63)
				$final .= "/";
			elseif (!$tmp)
				$final .= "A";
			else
				$final .= chr($tmp-4);
			$start += 6;
		}
		if (strlen($final)%4>0)
			$final .= str_repeat('=', 4-strlen($final)%4);
		return $final;
	}
}

if(!function_exists('base64_decode'))
{
	/**
	* Alternative function base64_decode
	*
	* @param string $string The input string to be decoded from base64
	* @return string String decoded from base64 to normal string
	* @link http://php.net/base64_decode
	*/
	function base64_decode($str)
	{
		$len = strlen($str);
		$ret = '';
		$b64 = array();
		$base64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
		$len_base64 = strlen($base64);
		
		for ($i = 0; $i < 256; $i++)
			$b64[$i] = 0;
		for ($i = 0; $i < $len_base64 ; $i++)
			$b64[ord($base64[$i])] = $i;

		for($j=0;$j<$len;$j+=4)
		{
			for ($i = 0; $i < 4; $i++)
			{
				$c = ord($str[$j+$i]);
				$a[$i] = $c;
				$b[$i] = $b64[$c];
			}

			$o[0] = ($b[0] << 2) | ($b[1] >> 4);
			$o[1] = ($b[1] << 4) | ($b[2] >> 2);
			$o[2] = ($b[2] << 6) | $b[3];
			if ($a[2] == ord('='))
				$i = 1;
			else if ($a[3] == ord('='))
				$i = 2;
			else
				$i = 3;

			for($k=0;$k<$i;$k++)
				$ret .= chr((int) $o[$k] & 255);

			if ($i < 3)
				break;
		}

		return $ret;
	}
}


if(!function_exists('filesize'))
{
	/**
	* Alternative function filesize
	*
	* @param string $filename The path of the files
	* @return int The file size
	* @link http://php.net/filesize
	*/
	function filesize($filename)
	{
		$a = fopen($filename, 'r'); 
		fseek($a, 0, SEEK_END); 
		$filesize = ftell($a); 
		fclose($a);
		return $filesize;
	}
}



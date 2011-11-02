<?php
/**
*
* @package Kleeja
* @version $Id: functions_display.php 1281 2009-11-27 08:43:12Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


/**
* After a lot of work, we faced many hosts who use a old PHP version, or 
* they disabled many general functions ... 
* so, this file contains those type of functions.
*/


//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}


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

//Custom base64_* functions
function kleeja_base64_encode($str = ''){ return function_exists('base64_encode') ? base64_encode($str) : base64encode($str); }
function kleeja_base64_decode($str = ''){ return function_exists('base64_decode') ? base64_decode($str) : base64decode($str); }

//http://www.php.net/manual/en/function.base64-encode.php#63270
function base64encode($string = '')
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



function base64decode($str)
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

if(!function_exists('filesize'))
{
	function kleeja_filesize($filename)
	{
		$a = fopen($filename, 'r'); 
		fseek($a, 0, SEEK_END); 
		$filesize = ftell($a); 
		fclose($a);
		return $filesize;
	}
}
else
{
	function kleeja_filesize($filename)
	{
		return filesize($filename);
	}
}


#By: alexander at alexauto dot nl
#Edited by: Kleeja Team
function imagecreatefrombmp($p_sFile) 
{
	#Load the image into a string 
	$file = fopen($p_sFile, 'rb');
	$read = fread($file, 10);
	while(!feof($file) && ($read<>"")) 
	{
		$read .= fread($file,1024);
	}

	$temp	= unpack("H*", $read);
	$hex	= $temp[1];
	$header	= substr($hex, 0, 108);

	#Process the header, @see: http://www.fastgraph.com/help/bmp_header_format.html 
	if (substr($header, 0, 4) == "424d")
	{
	    #Cut it in parts of 2 bytes 
		$header_parts	= str_split($header, 2);
		#Get the width	4 bytes 
		$width			= hexdec($header_parts[19] . $header_parts[18]);
	    #Get the height	4 bytes 
		$height			= hexdec($header_parts[23] . $header_parts[22]);
	    #Unset the header params 
	    unset($header_parts);
	}

	#Define starting X and Y 
	$x = 0;
	$y = 1;

	#Create newimage 
	$image = imagecreatetruecolor($width, $height);

	#Grab the body from the image 
	$body = substr($hex, 108);

	#Calculate if padding at the end-line is needed 
	#Divided by two to keep overview. 
	#1 byte = 2 HEX-chars 
	$body_size		= (strlen($body) / 2);
	$header_size	= ($width * $height);

	#Use end-line padding? Only when needed 
	$usePadding	=    ($body_size > ($header_size*3) + 4);
	
	#Using a for-loop with index-calculation instaid of str_split to avoid large memory consumption 
	#Calculate the next DWORD-position in the body 
	for ($i=0;$i<$body_size;$i+=3)
	{
		#Calculate line-ending and padding 
		if ($x>=$width)
		{
			#If padding needed, ignore image-padding 
			#Shift i to the ending of the current 32-bit-block 
			if ($usePadding)
			{
				$i += $width % 4;
			}
			#Reset horizontal position 
			$x = 0;
		
			#Raise the height-position (bottom-up) 
			$y++;

			#Reached the image-height? Break the for-loop 
			if ($y > $height) 
			{
				break;
			}
		}

		#Calculation of the RGB-pixel (defined as BGR in image-data) 
		#Define $i_pos as absolute position in the body 
		$i_pos	= $i*2;
		$r		= hexdec($body[$i_pos + 4] . $body[$i_pos + 5]);
		$g		= hexdec($body[$i_pos + 2] . $body[$i_pos + 3]);
		$b		= hexdec($body[$i_pos] . $body[$i_pos + 1]);

		#Calculate and draw the pixel 
		$color = imagecolorallocate($image, $r, $g, $b);
		imagesetpixel($image, $x, $height-$y, $color);

		#Raise the horizontal position 
		$x++;
	}

	#Unset the body / free the memory 
	unset($body);

	#Return image-object 
	return $image;
}


#BY:shd at earthling dot net
function imagebmp($im, $fn = false)
{
	if (!$im)
	{
		return false;
	}

	if ($fn === false)
	{
		$fn = 'php://output';
	}

	$f = fopen ($fn, 'w');

	if (!$f)
	{
		return false;
	}

	#Image dimensions
	$biWidth	= imagesx ($im);
	$biHeight	= imagesy ($im);
	$biBPLine	= $biWidth * 3;
	$biStride	= ($biBPLine + 3) & ~3;
	$biSizeImage	= $biStride * $biHeight;
	$bfOffBits		= 54;
	$bfSize			= $bfOffBits + $biSizeImage;

    #BITMAPFILEHEADER
	fwrite ($f, 'BM', 2);
	fwrite ($f, pack ('VvvV', $bfSize, 0, 0, $bfOffBits));

	#BITMAPINFO (BITMAPINFOHEADER)
	fwrite ($f, pack ('VVVvvVVVVVV', 40, $biWidth, $biHeight, 1, 24, 0, $biSizeImage, 0, 0, 0, 0));

	$numpad = $biStride - $biBPLine;
	for ($y = $biHeight - 1; $y >= 0; --$y)
	{
		for ($x = 0; $x < $biWidth; ++$x)
		{
			$col = imagecolorat ($im, $x, $y);
			fwrite ($f, pack ('V', $col), 3);
		}

		for ($i = 0; $i < $numpad; ++$i)
		{
			fwrite ($f, pack ('C', 0));
		}
	}
	fclose ($f);
	return true;
}

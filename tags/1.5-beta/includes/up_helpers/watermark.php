<?php
/**
*
* @package Kleeja_up_helpers
* @version $Id: KljUploader.php 2002 2012-09-18 04:47:35Z saanina $
* @copyright (c) 2007-2012 Kleeja.com
* @license ./docs/license.txt
*
*/

//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}

#
# This helper is used to make a watermark on a given image,
# return nothing because if it work then ok , and if not then ok too :)
# 
#

#todo: 
#- text support
#- good support for gif images

function helper_watermark($name, $ext)
{
	($hook = kleeja_run_hook('helper_watermark_func')) ? eval($hook) : null; //run hook	
	
	#is this file really exsits ?
	if(!file_exists($name))
	{
		return;
	}

	#now, lets work and detect our image extension
	if (strpos($ext, 'jp') !== false)
	{
		$src_img = @imagecreatefromjpeg($name);
	}
	elseif (strpos($ext, 'png') !== false)
	{
		$src_img = @imagecreatefrompng($name);
	}
	elseif (strpos($ext, 'gif') !== false)
	{
		$src_img = @imagecreatefromgif($name);
	}
	elseif(strpos($ext, 'bmp') !== false)
	{
		if(!defined('BMP_CLASS_INCLUDED'))
		{
			include dirname(__file__) . '/BMP.php';
			 define('BMP_CLASS_INCLUDED', true);
		}

		$src_img = imagecreatefrombmp($name);
	}
	else
	{
		return;
	}

	$src_logo = false;
	if(file_exists(dirname(__FILE__) . '/../../images/watermark.png'))
	{
		$src_logo = imagecreatefrompng(dirname(__FILE__) . '/../../images/watermark.png');
	}
	elseif(file_exists(dirname(__FILE__) . '/../../images/watermark.gif'))
	{
		$src_logo = imagecreatefromgif(dirname(__FILE__) . '/../../images/watermark.gif');
	}

	#no watermark pic
	if(!$src_logo)
	{
		return;
	}

	#detect width, height for the image
	$bwidth  = @imageSX($src_img);
	$bheight = @imageSY($src_img);
	
	#detect width, height for the watermark image
	$lwidth  = @imageSX($src_logo);
	$lheight = @imageSY($src_logo);


	if ($bwidth > $lwidth+5 &&  $bheight > $lheight+5)
	{
		#where exaxtly do we have to make the watermark ..
		$src_x = $bwidth - ($lwidth + 5);
		$src_y = $bheight - ($lheight + 5);
		
		#make it now, watermark it
		@ImageAlphaBlending($src_img, true);
		@ImageCopy($src_img, $src_logo, $src_x, $src_y, 0, 0, $lwidth, $lheight);

		if (strpos($ext, 'jp') !== false)
		{
			@imagejpeg($src_img, $name);
		}
		elseif (strpos($ext, 'png') !== false)
		{
			@imagepng($src_img, $name);
		}
		elseif (strpos($ext, 'gif') !== false)
		{
			@imagegif($src_img, $name);
		}
		elseif (strpos($ext, 'bmp') !== false)
		{
			@imagebmp($src_img, $name);
		}
	}
	else 
	{
			#image is not big enough to watermark it
			return false;
	}		
}



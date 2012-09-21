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
# This helper is used to make a thumb image, small
#




/**
 * return good ratio for thumb image
 * need imporovment, now only depend on new_h
 */
function get_scale_thumb($old_x, $old_y, $new_w, $new_h, $ext = '')
{
	($hook = kleeja_run_hook('get_scale_thumb_func')) ? eval($hook) : null; //run hook	
	return array(round($old_x * $new_h / $old_y), $new_h);
}
	
/**
 * Creates a resized image
 * @example createthumb('pics/apple.jpg','thumbs/tn_apple.jpg',100,100);
 */
function helper_thumb($source_path, $ext, $dest_image, $dw, $dh)
{
	#no file, quit it
	if(!file_exists($source_path))
	{
		return;
	}

	#check width, height
	if(intval($dw) == 0 || intval($dw) < 10)
	{
		$dw = 100;
	}

	if(intval($dh) == 0 || intval($dh) < 10)
	{
		$dh = $dw;
	}

	#if there is imagick lib, then we should use it
	if(function_exists('phpversion') && phpversion('imagick'))
	{
		helper_generate_thumb_imagick($source_path, $ext, $dest_image, $dw, $dh);
		return;
	}

	#no getimagesize, then go to other helper
	if(!function_exists('getimagesize'))
	{
		helper_generate_thumb2($source_path, $ext, $dest_image, $dw, $dh);
		return;
	}

	//get file info
	list($source_width, $source_height, $source_type) = getimagesize($source_path);

	switch ($source_type)
	{
		case IMAGETYPE_GIF:
			$source_gdim = imagecreatefromgif( $source_path );
			break;
		case IMAGETYPE_JPEG:
			$source_gdim = imagecreatefromjpeg( $source_path );
			break;
		case IMAGETYPE_PNG:
			$source_gdim = imagecreatefrompng( $source_path );
			break;
	}

	$source_aspect_ratio = $source_width / $source_height;
	$desired_aspect_ratio = $dw / $dh;

	if ($source_aspect_ratio > $desired_aspect_ratio)
	{
		// Triggered when source image is wider
		$temp_height = $dh;
		$temp_width = (int) ($dh * $source_aspect_ratio);
	}
	else
	{
		// Triggered otherwise (i.e. source image is similar or taller)
		$temp_width = $dw;
		$temp_height = (int) ($dw / $source_aspect_ratio);
	}

	// Resize the image into a temporary GD image
	$temp_gdim = imagecreatetruecolor( $temp_width, $temp_height );
	imagecopyresampled(
		$temp_gdim,
		$source_gdim,
		0, 0,
		0, 0,
		$temp_width, $temp_height,
		$source_width, $source_height
	);

	// Copy cropped region from temporary image into the desired GD image
	$x0 = ($temp_width - $dw) / 2;
	$y0 = ($temp_height - $dh) / 2;

	$desired_gdim = imagecreatetruecolor($dw, $dh);
	imagecopy(
		$desired_gdim,
		$temp_gdim,
		0, 0,
		$x0, $y0,
		$dw, $dh
	);

	// Create thumbnail
	switch(strtolower(preg_replace('/^.*\./', '', $dest_image)))
	{
		case 'jpg':
		case 'jpeg':
			$return = @imagejpeg($desired_gdim, $dest_image, 90);
			break;
		case 'png':
			$return = @imagepng($desired_gdim, $dest_image);
			break;
		case 'gif':
			$return = @imagegif($desired_gdim, $dest_image);
		break;
		default:
			// Unsupported format
		$return = false;
		break;
	}

	@imagedestroy($desired_gdim);
	@imagedestroy($src_img);
	
	return $return;
}



/**
 * thumb helper if no getimagesize
 */
function helper_thumb2($name, $ext, $filename, $new_w, $new_h)
{
	($hook = kleeja_run_hook('helper_generate_thumb_func')) ? eval($hook) : null; //run hook	

	#no file, quit it
	if(!file_exists($name))
	{
		return;
	}

	#if there is imagick lib, then we should use it
	if(function_exists('phpversion') && phpversion('imagick'))
	{
		helper_generate_thumb_imagick($name, $ext, $filename, $new_w, $new_h);
		return;
	}

	#if the responsible function is not avaliable, then quit it
	$function_create = 'imagecreatefrom' . str_replace('jpg', 'jpeg', $ext);
	if(!function_exists($function_create))
	{
		return;
	}

	#original height, weight
	$src_img = @$function_create($name);
	$old_x = imageSX($src_img);
	$old_y = imageSY($src_img);
	
	#gussing the right thumb height, weight
	list($thumb_w, $thumb_h) = get_scale_thumb($old_x, $old_y, $new_w, $new_h, $ext);

	#create it
	$dst_img = @ImageCreateTrueColor($thumb_w, $thumb_h);
	@imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

	$function = 'image' . str_replace('jpg', 'jpeg', $ext);
	if(function_exists($function))
	{
			@$function($dst_img, $filename);
	}

	@imagedestroy($dst_img);
	@imagedestroy($src_img);
}


/**
 * generating thumb from image using Imagick
 * 
 */
function helper_thumb_imagick($name, $ext, $filename, $new_w, $new_h)
{
	#intiating the Imagick lib	
	$im = new Imagick($name);

	#get the image height, weight
	$old_x = $im->getImageWidth();
	$old_y = $im->getImageHeight();
	
	#guess the right thumb height, weights
	list($thumb_w, $thumb_h) = get_scale_thumb($old_x, $old_y, $new_w, $new_h, $ext);

	#an exception for gif image
	#generating thumb with 10 frames only, big gif is a devil
	if($ext == 'gif')
	{
		$i = 0;
		foreach ($im as $frame)
		{
			$frame->thumbnailImage($thumb_w, $thumb_h);
			$frame->setImagePage($thumb_w, $thumb_h, 0, 0);
			if($i > 10)
			{
				# more than 10 frames, quit it
				break;
			}
			$i++;
		}
	}
	else
	{
		#and other image extenion use one way
		$im->thumbnailImage($thumb_w, $thumb_h);
	}

	#right it
	$im->writeImages($filename, ($ext == 'gif'));
	return;
}



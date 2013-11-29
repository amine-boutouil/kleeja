<?php
/**
*
* @package Kleeja
* @subpackage Uploading 
* @version $Id: KljUploader.php 2002 2012-09-18 04:47:35Z saanina $
* @copyright (c) 2007-2012 Kleeja.com
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
 * create htaccess files for uploading folder
 * @param string $folder The folder path
 * @return void
 */
function generate_safety_htaccess($folder)
{
	#data for the htaccess
	$htaccess_data = "<Files ~ \"^.*\.(php|php*|cgi|pl|phtml|shtml|sql|asp|aspx)\">\nOrder allow,deny\nDeny from all\n</Files>\n<IfModule mod_php4.c>\nphp_flag engine off\n</IfModule>\n<IfModule mod_php5.c>\nphp_flag engine off\n</IfModule>\nRemoveType .php .php* .phtml .pl .cgi .asp .aspx .sql";
	
	#generate the htaccess
	$fi		= @fopen($folder . '/.htaccess', 'w');
	$fi2	= @fopen($folder . '/thumbs/.htaccess','w');
	$fy		= @fwrite($fi, $htaccess_data);
	$fy2	= @fwrite($fi2, $htaccess_data);
}

/**
 * create an uploading folder
 * @param string $folder The folder path
 */
function make_folder($folder)
{
	#try to make a new upload folder 
	$f = @mkdir($folder);
	$t = @mkdir($folder . '/thumbs');

	if($f && $t)
	{
		#then try to chmod it to 777
		$chmod	= @chmod($folder, 0777);
		$chmod2	= @chmod($folder . '/thumbs/', 0777);	

		#make it safe
		generate_safety_htaccess($folder);

		#create empty index so nobody can see the contents
		$fo		= @fopen($folder . "/index.html","w");
		$fo2	= @fopen($folder . "/thumbs/index.html","w");
		$fw		= @fwrite($fo,'<a href="http://kleeja.com"><p>KLEEJA ..</p></a>');
		$fw2	= @fwrite($fo2,'<a href="http://kleeja.com"><p>KLEEJA ..</p></a>');
	}

	return $f && $t ? true : false;	
}

/**
 * Change the file name depend on given decoding type and prefix
 */
function change_filename($filename, $ext)
{
	global $config;

	$return = '';

	#change it, time..
	if($config['decode'] == 1)
	{
		list($usec, $sec) = explode(" ", microtime());
		$extra = str_replace('.', '', (float)$usec + (float)$sec);
		$return = $extra . '.' . $ext;
	}
	# md5
	elseif($config['decode'] == 2)
	{
		list($usec, $sec) = explode(" ", microtime());
		$extra	= md5(((float)$usec + (float)$sec) . $filename);
		$extra	= substr($extra, 0, 12);
		$return	= $extra . "." . $ext;
	}
	# exists before, change it a little
	//elseif($decoding_type == 'exists')
	//{
		//$return = substr($filename, 0, -(strlen($ext)+1)) . '_' . substr(md5($rand . time()), rand(0, 20), 5) . '.' . $ext;
		//}
	#nothing
	else
	{
		$filename = substr($filename, 0, -(strlen($ext)+1));
		$return = preg_replace('/[,.?\/*&^\\\$%#@()_!|"\~\'><=+}{; ]/', '-', $filename) . '.' . $ext;
		$return = preg_replace('/-+/', '-', $return);
	}


	#if filename prefix is enabled
	if(trim($config['prefixname']) != '')
	{
		#random number...
		if (preg_match("/{rand:([0-9]+)}/i", $config['prefixname'], $m))
		{
			$prefix = preg_replace("/{rand:([0-9]+)}/i", substr(md5(time()), 0, $m[1]), $config['prefixname']);
		}
	
		#current date
		if (preg_match("/{date:([a-zA-Z-_]+)}/i", $config['prefixname'], $m))
		{
			$prefix = preg_replace("/{date:([a-zA-Z-_]+)}/i", date($m[1]), $config['prefixname']);
		}

		$filename = $prefix . $filename;
	}


	($hook = kleeja_run_hook('change_filename_func')) ? eval($hook) : null; //run hook

	return $return;
}



function check_file_content($file_path)
{
	$return = true;

	if(@filesize($file_path) > 10*(1000*1024))
	{
		return true;
	}

	#check for bad things inside files
	$maybe_bad_codes_are = array('body', 'head', 'html', 'img', 'plaintext', 'a href', 'pre', 'script', 'table', 'title');
	
	$fp = @fopen($file_path, 'rb');

	if ($fp !== false)
	{
		$f_content = fread($fp, 256);
		fclose($fp);
		foreach ($maybe_bad_codes_are as $forbidden)
		{
			if (stripos($f_content, '<' . $forbidden) !== false)
			{
				$return = false;
				break;
			}
		}
	}


	($hook = kleeja_run_hook('kleeja_check_mime_func')) ? eval($hook) : null; //run hook
	
	return $return;
}


/**
 * To prevent flooding at uploading, waiting between uploads  
 */
function user_is_flooding()
{
	global $SQL, $dbprefix, $config, $user;

	$return = 'empty';

	($hook = kleeja_run_hook('user_is_flooding_func')) ? eval($hook) : null; //run 

	if($return != 'empty')
	{
		return $return;
	}

	#if the value is zero (means that the function is disabled) then return false immediately
	if(intval($config['usersectoupload']) == 0)
	{
		return false;
	}

	//In my point of view I see 30 seconds is not bad rate to stop flooding .. 
	//even though this minimum rate sometime isn't enough to protect Kleeja from flooding attacks 
	$time = time() - intval($config['usersectoupload']); 

	$query = array(
					'SELECT'	=> 'f.time',
					'FROM'		=> "{$dbprefix}files f",
					'WHERE'     => 'f.time >= ' . $time . ' AND f.user_ip = \'' .  $SQL->escape($user->data['ip']) . '\'',
				);

	if ($SQL->num($SQL->build($query)))
	{
		return true;
	}

	return false;
}

/**
 * To re-arrange _FILES array
 * @author timspeelman@live.nl
 */
function rearrange_files_input($arr)
{
    foreach($arr as $key => $all)
	{
        foreach($all as $i=>$val)
		{
            $new[$i][$key] = $val;    
        }    
    }

    return $new;
}

/**
 * Creates a resized image
 * @example create_thumb('pics/apple.jpg','thumbs/tn_apple.jpg',100,100);
 */
function create_thumb($source_path, $ext, $dest_image, $dw, $dh)
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
		create_thumb_imagick($source_path, $ext, $dest_image, $dw, $dh);
		return;
	}

	//get file info
	list($source_width, $source_height, $source_type) = array(false, false, false);
	if(function_exists('getimagesize'))
	{
		list($source_width, $source_height, $source_type) = @getimagesize($source_path);
	}

	switch(strtolower(preg_replace('/^.*\./', '', $source_path)))
	{
		case 'gif':
			$source_gdim = imagecreatefromgif( $source_path );
			break;
		case 'jpg':
		case 'jpeg':
			$source_gdim = imagecreatefromjpeg( $source_path );
			break;
		case 'png':
			$source_gdim = imagecreatefrompng( $source_path );
			break;
	}

	$source_width = !$source_width ? ImageSX($source_gdim) : $source_width;
	$source_height = !$source_height ? ImageSY($source_gdim) : $source_height;

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
 * generating thumb from image using Imagick
 * 
 */
function scale_image_imagick($x, $y, $cx, $cy)
{
	//Set the default NEW values to be the old, in case it doesn't even need scaling
	list($nx,$ny) = array($x, $y);

	//If image is generally smaller, don't even bother
	if ($x >= $cx || $y >= $cx)
	{        
		//Work out ratios
		if ($x > 0)
		{
			$rx = $cx / $x;
		}

		if ($y>0)
		{
			$ry = $cy / $y;
        }

		//Use the lowest ratio, to ensure we don't go over the wanted image size
		if ($rx>$ry)
		{
            $r = $ry;
        }
        else
        {
            $r = $rx;
        }

		//Calculate the new size based on the chosen ratio
		$nx = intval($x * $r);
		$ny = intval($y * $r);
	}

	//Return the results
	return array($nx, $ny);
}

function create_thumb_imagick($name, $ext, $filename, $new_w, $new_h)
{
	#intiating the Imagick lib	
	$im = new Imagick($name);

	#guess the right thumb height, weights
	list($thumb_w, $thumb_h) = scale_image_imagick(
					$im->getImageWidth(),
					$im->getImageHeight(),
					$new_w,
					$new_h);

	#an exception for gif image
	#generating thumb with 10 frames only, big gif is a devil
	if($ext == 'gif')
	{
		$i = 0;
		//$gif_new = new Imagick(); 
		foreach ($im as $frame)
		{
			$frame->thumbnailImage($thumb_w, $thumb_h);
			$frame->setImagePage($thumb_w, $thumb_h, 0, 0);
		//	$gif_new->addImage($frame->getImage()); 
			if($i >= 10)
			{
				# more than 10 frames, quit it
				break;
			}
			$i++;
		}
		$im->writeImages($filename, true);
		return;
	}

	#and other image extenion use one way
	$im->thumbnailImage($thumb_w, $thumb_h);

	#right it
	$im->writeImages($filename, false);
	return;
}


#todo: 
#- text support
#- good support for gif images

function create_watermark($name, $ext)
{
	($hook = kleeja_run_hook('helper_watermark_func')) ? eval($hook) : null; //run hook	
	
	#is this file really exsits ?
	if(!file_exists($name))
	{
		return;
	}

	$src_logo = $logo_path = false;
	if(file_exists(dirname(__FILE__) . '/../../images/watermark.png'))
	{
		$logo_path= dirname(__FILE__) . '/../../images/watermark.png';
		$src_logo = imagecreatefrompng($logo_path);
	}
	elseif(file_exists(dirname(__FILE__) . '/../../images/watermark.gif'))
	{
		$logo_path= dirname(__FILE__) . '/../../images/watermark.gif';
		$src_logo = imagecreatefromgif($logo_path);
	}

	#no watermark pic
	if(!$src_logo)
	{
		return;
	}

	#if there is imagick lib, then we should use it
	if(function_exists('phpversion') && phpversion('imagick'))
	{
		create_watermark_imagick($name, $ext, $logo_path);
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
		return;
		$src_img = @imagecreatefromgif($name);
	}
	else
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
	}
	else 
	{
			#image is not big enough to watermark it
			return false;
	}		
}


#
# generate watermarked images by imagick
#
function create_watermark_imagick($name, $ext, $logo)
{
	#Not just me babe, All the places mises you ..  
	$im = new Imagick($name);

	$watermark = new Imagick($logo);
	//$watermark->readImage($);

	#how big are the images?
	$iWidth	= $im->getImageWidth();
	$iHeight= $im->getImageHeight();
	$wWidth	= $watermark->getImageWidth();
	$wHeight= $watermark->getImageHeight();

	if ($iHeight < $wHeight || $iWidth < $wWidth)
	{
		#resize the watermark
		$watermark->scaleImage($iWidth, $iHeight);

		#get new size
		$wWidth = $watermark->getImageWidth();
		$wHeight = $watermark->getImageHeight();
	}

	#calculate the position
	$x = $iWidth - ($wWidth - 5);
	$y = $iHeight - ($wHeight - 5);

	#an exception for gif image
	#generating thumb with 10 frames only, big gif is a devil
	if($ext == 'gif')
	{
		$i = 0;
		//$gif_new = new Imagick(); 
		foreach ($im as $frame)
		{
			$frame->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);

		//	$gif_new->addImage($frame->getImage()); 
			if($i >= 10)
			{
				# more than 10 frames, quit it
				break;
			}
			$i++;
		}
		$im->writeImages($name, true);
		return;
	}

	$im->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
	
	$im->writeImages($name, false);
}

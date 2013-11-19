<?php
/**
*
* @package Kleeja_up_helpers
* @version $Id: KljUploader.php 2002 2012-09-18 04:47:35Z saanina $
* @copyright (c) 2007-2012 Kleeja.com
* @license http://www.kleeja.com/license
*
*/

//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}

#
# Other helpers that will be used at uploading in Kleeja
# 
#



/**
 * checking the safety and validty of sub-extension of given file 
 * 
 */
function ext_check_safe($filename)
{
	#bad files extensions
	$not_allowed =	array('php', 'php3' ,'php5', 'php4', 'asp' ,'shtml' , 'html' ,'htm' ,'xhtml' ,'phtml', 'pl', 'cgi', 'htaccess', 'ini');
	
	#let split the file name, suppose it filename.gif.php
	$tmp	= explode(".", $filename);

	#if it's less than 3, that its means normal
	if(sizeof($tmp) < 3)
	{
		return true;
	}

	$before_last_ext = $tmp[sizeof($tmp)-2];

	#in the bad extenion, return false to tell him
	if (in_array(strtolower($before_last_ext), $not_allowed)) 
	{
		return false;
	}
	else
	{
		return true;
	}
}


/**
 * create htaccess files for uploading folder
 */
function generate_safety_htaccess($folder)
{
	#data for the htaccess
	$htaccess_data = "<Files ~ \"^.*\.(php|php*|cgi|pl|phtml|shtml|sql|asp|aspx)\">\nOrder allow,deny\nDeny from all\n</Files>\n<IfModule mod_php4.c>\nphp_flag engine off\n</IfModule>\n<IfModule mod_php5.c>\nphp_flag engine off\n</IfModule>\nRemoveType .php .php* .phtml .pl .cgi .asp .aspx .sql";
	
	#generate the htaccess
	$fi		= @fopen($folder . "/.htaccess", "w");
	$fi2	= @fopen($folder . "/thumbs/.htaccess","w");
	$fy		= @fwrite($fi, $htaccess_data);
	$fy2	= @fwrite($fi2, $htaccess_data);
}

/**
 * create an uploading folder
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
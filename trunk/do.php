<?php
/**
*
* @package Kleeja
* @version $Id: do.php 1260 2009-11-23 22:57:20Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


/**
 * We are in do.php file, useful for exceptions
 */
define('IN_DOWNLOAD', true);

/**
 * @ignore
 */
define('IN_KLEEJA', true);
define('PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
include PATH . 'includes/common.php';
include PATH . 'includes/functions/functions_files.php';

($hook = kleeja_run_hook('begin_download_page')) ? eval($hook) : null; //run hook

//
//page of wait downloading files
//
if(ig('id') || ig('filename'))
{
	($hook = kleeja_run_hook('begin_download_id_filename')) ? eval($hook) : null; //run hook

	$query = array(
					'SELECT'	=> 'f.id, f.real_filename, f.name, f.folder, f.size, f.time, f.uploads, f.type',
					'FROM'		=> "{$dbprefix}files f",
				);

	#if user system is default, we use users table
	if((int) $config['user_system'] == 1)
	{
		$query['SELECT'] .= ', u.name AS fusername, u.id AS fuserid';
		$query['JOINS']	=	array(
									array(
										'LEFT JOIN'	=> "{$dbprefix}users u",
										'ON'		=> 'u.id=f.user'
									)
								);
	}

	if (ig('filename'))
	{
		$filename_l  = g('filename');
		$query['WHERE']	= "f.name='" . $SQL->escape($filename_l . (ig('x') ? '.' . g('x') : '')) . "'";
	}
	else
	{
		$id_l = g('id' ,'int');
		$query['WHERE']	= "f.id=" . $id_l;
	}

	($hook = kleeja_run_hook('qr_download_id_filename')) ? eval($hook) : null; //run hook
	$result	= $SQL->build($query);

	if ($SQL->num($result))
	{
		$row = $SQL->fetch($result);
		@extract($row);
		$SQL->free($result);

		#some vars
		$fname	 	= $name;
		$fname2 	= str_replace('.', '-', htmlspecialchars($name));
		$name 		= $real_filename != '' ? str_replace('.' . $type, '', htmlspecialchars($real_filename)) : $name;
		$name		= (strlen($name) > 70) ? substr($name, 0, 70) . '...' : $name;
		$fusername	= $config['user_system'] == 1 && $fuserid > -1 ? $fusername : false;
		$userfolder	= $config['siteurl'] . ($config['mod_writer'] ? 'fileuser-' . $fuserid . '.html' : 'ucp.php?go=fileuser&amp;id=' .  $fuserid);
		$url_file	= ($config['mod_writer']) ? $config['siteurl'] . "down" . (ig('filename') ? 'f' : '') . "-" . $fname2 . ".html" : $config['siteurl'] . "do.php?down" . (ig('filename') ? 'f' : '') . "=" . $fname;

		#live extenstions, those that user doesn't need to wait for
		if(!empty($config['livexts']))
		{
			$livexts = explode(',',$config['livexts']);
			if(in_array($type,$livexts))
			{
				$url_filex	= $config['mod_writer'] ? $config['siteurl'] . "downex" . (ig('filename') ? 'f' : '') . "-" . $fname2 . ".html" : $config['siteurl'] . "do.php?downex" . (ig('filename') ? 'f' : '') . "=" . $fname;		
				redirect($url_filex, false);
			}
		}

		$REPORT		= $config['mod_writer'] ?  $config['siteurl'] . "report-" . $id . ".html" :  $config['siteurl'] . "go.php?go=report&amp;id=" . $id;
		$seconds_w	= $config['sec_down'];
		$time		= kleeja_date($time);
		$size		= Customfile_size($size);

		$file_ext_icon = file_exists('images/filetypes/' . $type . '.png') ? 'images/filetypes/' . $type . '.png' : 'images/filetypes/file.png';
		$sty		= 'download';
		$title 		=  $name . ' ' . $lang['DOWNLAOD'];
	}
	else
	{
		//file not exists
		($hook = kleeja_run_hook('not_exists_qr_downlaod_file')) ? eval($hook) : null; //run hook
		kleeja_err($lang['FILE_NO_FOUNDED']);
	}

	($hook = kleeja_run_hook('b4_showsty_downlaod_id_filename')) ? eval($hook) : null; //run hook

	//add http reffer to session to prevent errors with some browsers ! 
	if (ig('filename'))
	{
		$_SESSION['HTTP_REFERER'] = $config['siteurl'] . ($config['mod_writer'] ? "downloadf" . $fname . ".html" : "do.php?filename=" . $fname);
	}
	else
	{
		$_SESSION['HTTP_REFERER'] = $config['siteurl'] . ($config['mod_writer'] ? "download" . $id . ".html" : "do.php?id=" . $id);
	}


	#show the page
	kleeja_header($title);
	echo $tpl->display($sty);
	kleeja_footer();
}









//
//download file 
//
# guidline for _get variable names
# down: [0-9], default, came from do.php?id=[0-9]
# downf: [a-z0-9].[ext], came from do.php?filename=[a-z0-9].[ext]
#
# img: [0-9], default, direct from do.php?img=[0-9]
# imgf: [a-z0-9].[ext], direct from do.php?imgf=[a-z0-9].[ext]
#
# thmb: [0-9], default, direct from do.php?thmb=[0-9]
# thmbf: [a-z0-9].[ext], direct from do.php?thmbf=[a-z0-9].[ext]
#
# live extensions feature uses downex, downexf as in down & downf  
#
# x : used only for html links, where x = extension, downf is filename without extension

else if (ig('down') || ig('downf') ||
		ig('img') || ig('imgf') ||  
		ig('thmb') || ig('thmbf') || 
		ig('downex') || ig('downexf'))
{
	($hook = kleeja_run_hook('begin_down_go_page')) ? eval($hook) : null; //run hook	



	#must know from where he came ! and stop him if not image
	if(ig('down') || ig('downf'))
	{
		if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER']))
		{
			if(function_exists('getenv'))
			{
				$_SERVER['HTTP_REFERER'] = getenv('HTTP_REFERER') ? getenv('HTTP_REFERER') : null;
			}

			if(!isset($_SERVER['HTTP_REFERER'])|| empty($_SERVER['HTTP_REFERER']))
			{
				if(isset($_SESSION['HTTP_REFERER']))
				{
					$_SERVER['HTTP_REFERER'] = $_SESSION['HTTP_REFERER'];
					unset($_SESSION['HTTP_REFERER']);
				}
			}
		}

		#if not from our site and the waiting page or resuming
		$isset_down_h = ig('downf') && ig('x') ? 'downloadf-' . g('downf') . '-' . g('x') . '.html' : (ig('down') ? 'download' . g('down') . '.html' : '----');
		$not_reffer = true;
		if(strpos($_SERVER['HTTP_REFERER'], $isset_down_h) !== false)
		{
			$not_reffer = false;
		}

		$isset_down = ig('downf') ? 'do.php?filename=' . g('downf') : (ig('down') ? 'do.php?id=' . g('down') : '----');
		if(strpos($_SERVER['HTTP_REFERER'], $isset_down) !== false)
		{
			$not_reffer = false;
		}

		if(empty($_SERVER['HTTP_REFERER']) || strpos($config['siteurl'], str_replace(array('http://', 'www.'), '', htmlspecialchars($_SERVER['HTTP_REFERER']))))
		{
			$not_reffer = false;
		}

		if (isset($_SERVER["HTTP_RANGE"]))
		{
			$not_reffer = false;
		}

		if($not_reffer)
		{
			if(ig('downf'))
			{
				$go_to = $config['siteurl'] . ($config['mod_writer'] && ig('x') ? 'downloadf-' . g('downf') . '-' . g('x') . '.html' : 'do.php?filename=' . g('downf'));
			}
			else
			{
				$go_to = $config['siteurl'] . ($config['mod_writer'] ? 'download' . g('down') . '.html' : 'do.php?id=' . g('down','int'));
			}

			#redirect using header and exit
			redirect($go_to, true, true);
		}
	}

	#download by id or filename
	//is the comming variable is filename(filename123.gif) or id (123) ?
	$is_id_filename = (ig('downf') || ig('imgf') || ig('thmbf') || ig('downexf')) ? true : false;

	if($is_id_filename)
	{
		$var = ig('downf') ? 'downf' : (ig('imgf') ? 'imgf' : (ig('thmbf') ? 'thmbf' : (ig('downexf') ? 'downexf' : false)));

		#x, represent the extension, came from html links
		$filename = $SQL->escape(g($var) . (ig('x') && $var ? '.' . g('x') : ''));
	}
	else
	{
		$id = ig('down') ? g('down', 'int') : (ig('img') ? g('img', 'int') : (ig('thmb') ? g('thmb', 'int') : (ig('downex') ? g('downex', 'int') : null)));
	}


	//is internet explore 8 ?
	$is_ie8 = is_browser('ie8');
	//is internet explore 6 ?
	$is_ie6 = is_browser('ie6');


	$livexts = explode(',', $config['livexts']);

	#get info file
	$query = array('SELECT'	=> 'f.id, f.name, f.real_filename, f.folder, f.type, f.size, f.time',
					'FROM'		=> "{$dbprefix}files f",
					'WHERE'		=> $is_id_filename ? "f.name='" . $filename . "'" .  (ig('downexf') ? " AND f.type IN ('" . implode("', '", $livexts) . "')" : '') :
									'f.id=' . $id  . (ig('downex') ? " AND f.type IN ('" . implode("', '", $livexts) . "')" : ''),
					);

	($hook = kleeja_run_hook('qr_down_go_page_filename')) ? eval($hook) : null; //run hook
	$result	= $SQL->build($query);

	$is_live = false;
	$pre_ext = array_pop(@explode('.', $filename));
	$is_image = in_array(strtolower(trim($pre_ext)), array('gif', 'jpg', 'jpeg', 'bmp', 'png')) ? true : false; 

	if ($SQL->num($result))
	{
		$row = $SQL->fetch($result);
		
		$ii	= $row['id'];
		$n	= $row['name'];
		$rn	= $row['real_filename'];
		$t	= strtolower(trim($row['type']));
		$f	= $row['folder'];
		$ftime	= $row['time'];
		$d_size	= $row['size'];

		#img or not
		$is_image = in_array($t, array('gif', 'jpg', 'jpeg', 'bmp', 'png')) ? true : false; 
		#live url
		$is_live = in_array($t, $livexts) ? true : false; 
		

		$SQL->free($result);

		//check if the vistor is new in this page before updating kleeja counter
		if(!preg_match('/,' . $ii . ',/i', $usrcp->kleeja_get_cookie('oldvistor')))
		{
			//updates number of uploads ..
			$update_query = array(
									'UPDATE'=> "{$dbprefix}files",
									'SET'	=> 'uploads=uploads+1, last_down=' . time(),
									'WHERE'	=> $is_id_filename ? "name='" . $filename . "'" : 'id=' . $id,
								);

			($hook = kleeja_run_hook('qr_update_no_uploads_down')) ? eval($hook) : null; //run hook
			$SQL->build($update_query);

			//
			//Define as old vistor
			//if this vistor has other views then add this view too
			//old vistor just for 1 day
			//
			if($usrcp->kleeja_get_cookie('oldvistor'))
			{
				$usrcp->kleeja_set_cookie('oldvistor', $usrcp->kleeja_get_cookie('oldvistor') . $ii . ',', time()+86400);
			}
			else
			{
				//first time 
				$usrcp->kleeja_set_cookie('oldvistor', ',' . $ii . ',', time()+86400);
			}
		}
	}
	else
	{
		#not exists img or thumb
		if(ig('img') || ig('thmb') || ig('thmbf') || ig('imgf'))
		{
			($hook = kleeja_run_hook('not_exists_qr_down_img')) ? eval($hook) : null; //run hook

			$f = 'images';
			$n = 'not_exists.jpg';

			//set image conditon on
			$is_image = true;
		}
		else
		{
			
			#not exists file
			($hook = kleeja_run_hook('not_exists_qr_down_file')) ? eval($hook) : null; //run hook
			kleeja_err($lang['FILE_NO_FOUNDED']);
		}
	}

	#prevent bug, where you can download file, not image using imagef- url, bug:1134
	if((ig('img') || ig('thmb') || ig('thmbf') || ig('imgf')) && !$is_image)
	{
		$f = 'images';
		$n = 'not_exists.jpg';
		$is_image = true;
	}

	#downalod porcess
	$path_file = (ig('thmb') || ig('thmbf'))  ? "./{$f}/thumbs/{$n}" : "./{$f}/{$n}";
	$chunksize = 1024*120; //1 kelobyte * 120 = 120kb that will send to user every loop
	$resuming_on = true;

	($hook = kleeja_run_hook('down_go_page')) ? eval($hook) : null; //run hook	



	# this is a solution to ignore downloading through the file, redirecct to the actual file
	# where you can add 'define("MAKE_DOPHP_301_HEADER", true);' in config.php to stop the load
	# if there is any.
	if(defined('MAKE_DOPHP_301_HEADER'))
	{
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $path_file);
		garbage_collection();
		exit;
	}

	#unable to read the file?
	if(!is_readable($path_file))
	{
		($hook = kleeja_run_hook('down_file_not_exists')) ? eval($hook) : null; //run hook
		if($is_image)
		{
			$path_file = 'images/not_exists.jpg';
		}
		else
		{
			big_error('* ' . $lang['FILE_NO_FOUNDED'], $lang['NOT_FOUND']);
		}
	}

	#get filesize
	if(!($size = @filesize($path_file)))
	{
		$size = $d_size;
	}

	$name = empty($rn) ? $n : $rn;

	#encode the file name correctly
	if (is_browser('mozilla'))
	{
		$h_name = "filename*=UTF-8''" . rawurlencode(htmlspecialchars_decode($name));
	}
	else if (is_browser('opera, safari, konqueror'))
	{
		$h_name = 'filename="' . str_replace('"', '', htmlspecialchars_decode($name)) . '"';
	}
	else
	{
		$h_name = 'filename="' . rawurlencode(htmlspecialchars_decode($name)) . '"';
	}

	#Figure out the MIME type (if not specified) 
	$ext		= array_pop(explode('.', $path_file));
	$mime_type	= get_mime_for_header($ext);

	#disable execution time limit
	@set_time_limit(0);
	#disable output buffering
	@ob_end_clean();
	
	#close the db connection, and session
	garbage_collection();

	#required for IE, otherwise Content-Disposition may be ignored
	if(@ini_get('zlib.output_compression'))
	{
		@ini_set('zlib.output_compression', 'Off');
	}


	#open the file
	if (!($fp = @fopen($path_file, 'r')))
	{
		#it's failed to open !
		header("HTTP/1.0 404 Not Found");
		@fclose($pfile);
		big_error('** ' . $lang['FILE_NO_FOUNDED'], $lang['NOT_FOUND']);
	}

	#Unsetting all previously set headers.
	header_remove();

	#send file headers
	header('Pragma: public');
	header('Accept-Ranges: bytes');
	header("Content-Description: File Transfer");
	header("Content-Type: $mime_type");
	header('Date: ' . gmdate('D, d M Y H:i:s', $ftime) . ' GMT');
	#header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $ftime) . ' GMT');
	#header('Content-Encoding: none');
	header('Content-Disposition: ' . (($is_image || $is_live) ? 'inline' : 'attachment') . '; '  . $h_name);
	
	#if($is_image)
	#{
	#	header('Content-Transfer-Encoding: binary');
	#}

	#if(!$is_image && !$is_live && $is_ie8)
	#{
	#	header('X-Download-Options: noopen');
	#}

	#header(($is_ie6 ? 'Expires: -1' : 'Expires: Mon, 26 Jul 1997 05:00:00 GMT'));	
	#(($is_ie8) ? '; authoritative=true; X-Content-Type-Options: nosniff;' : '')



	#add multipart download and resume support                        
	if (isset($_SERVER["HTTP_RANGE"]))
	{
		list($a, $range) = explode("=", $_SERVER["HTTP_RANGE"],2);
		list($range) = explode(",", $range, 2);
		list($range, $range_end) = explode("=", $range);
		$range = round(floatval($range),0);
		$range_end = !$range_end ? $size-1 : round(floatval($range_end),0);

		$partial_length = $range_end-$range+1;
		header("HTTP/1.1 206 Partial Content");
		header("Content-Length: $partial_length");
		header("Content-Range: bytes ".($range - $range_end/$size));
	}
	else
	{
		$partial_length = $size;
		header("Content-Length: $partial_length");
	}

	#output file
	$bytes_sent = 0;
	#fast forward within file, if requested
	if (isset($_SERVER['HTTP_RANGE']))
	{
		fseek($fp, $range);
	}
	#read and output the file in chunks
	while( !feof($fp) && (!connection_aborted()) && ($bytes_sent < $partial_length) )
	{
		$buffer = fread($fp, $chunksize);
		print($buffer);
		flush();
		$bytes_sent += strlen($buffer);
	}
	fclose($fp);
}

//
//no one of above are there, you can use this hook to get more actions here
//
else
{
	($hook = kleeja_run_hook('err_navig_download_page')) ? eval($hook) : null; //run hook
	kleeja_err($lang['ERROR_NAVIGATATION']);
}

($hook = kleeja_run_hook('end_download_page')) ? eval($hook) : null; //run hook

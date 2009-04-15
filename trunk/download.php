<?php
##################################################
#						Kleeja
#
# Filename : download.php
# purpose :  when user  request file to  download it.
# copyright 2008-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : PhpFalcon
##################################################

// security ..
define ('IN_INDEX' , true);
define ('IN_DOWNLOAD', true);
//include imprtant file ..
include ('includes/common.php');

($hook = kleeja_run_hook('begin_download_page')) ? eval($hook) : null; //run hook

//
//page of wait downloading files
//
if (isset($_GET['id']) || isset($_GET['filename']))
{
			($hook = kleeja_run_hook('begin_download_id_filename')) ? eval($hook) : null; //run hook
			
			$query = array(
						'SELECT'	=> 'f.id, f.real_filename, f.name, f.folder, f.size, f.time, f.uploads, f.type',
						'FROM'		=> "{$dbprefix}files f",
					);		
					
			if (isset($_GET['filename']))
			{
				$filename_l 	= (string) $SQL->escape($_GET['filename']);
				$query['WHERE']	= "name='" . $filename_l . "'";
			}
			else
			{
				$id_l = intval($_GET['id']);
				$query['WHERE']	= "id=" . $id_l . "";
			}

			($hook = kleeja_run_hook('qr_download_id_filename')) ? eval($hook) : null; //run hook
			$result	=	$SQL->build($query);
			
			if ($SQL->num_rows($result) != 0)
			{
				while($row=$SQL->fetch_array($result))
				{
					@extract ($row);
				}
				$SQL->freeresult($result);

				// some vars
				$fname 		= $name;
				$name 		= $real_filename != '' ? str_replace('.' . $type, '', htmlspecialchars($real_filename)) : $name;
				
				if (isset($_GET['filename']))
				{
					$url_file	= ($config['mod_writer']) ? $config['siteurl'] . "downf-" . $fname . ".html" : $config['siteurl'] . "download.php?downf=" . $fname;
				}
				else
				{
					$url_file	= ($config['mod_writer']) ? $config['siteurl'] . "down-" . $id . ".html" : $config['siteurl'] . "download.php?down=" . $id;
				}
				
				$REPORT		= ($config['mod_writer']) ?  $config['siteurl'] . "report-" . $id . ".html" :  $config['siteurl'] . "go.php?go=report&amp;id=" . $id;
				$seconds_w	= $config['sec_down'];
				$time		= date("d-m-Y H:i a", $time);
				$size		= Customfile_size($size);
				
				$file_ext_icon = file_exists('images/filetypes/' . $type . '.gif') ? 'images/filetypes/' . $type . '.gif' : 'images/filetypes/file.gif';
				$sty		= 'download';
				$title =  $real_filename != '' ? str_replace('.' . $type, '', htmlspecialchars($real_filename)) : $name;
				$title .= ' ' . $lang['DOWNLAOD'];
			}
			else
			{
				//file not exists
				($hook = kleeja_run_hook('not_exists_qr_downlaod_file')) ? eval($hook) : null; //run hook
				kleeja_err($lang['FILE_NO_FOUNDED']);
			}
			
			($hook = kleeja_run_hook('b4_showsty_downlaod_id_filename')) ? eval($hook) : null; //run hook
			
			//add http reffer to session to prevent errors with some browsers ! 
			if (isset($_GET['filename']))
			{
				$_SESSION['HTTP_REFERER'] = $config['siteurl'] . (($config['mod_writer']) ? "downloadf" . $fname . ".html" : "download.php?filename=" . $fname);
			}
			else
			{
				$_SESSION['HTTP_REFERER'] = $config['siteurl'] . (($config['mod_writer']) ? "download" . $id . ".html" : "download.php?id=" . $id);
			}
			
			 // show style ...
			Saaheader($title);
			echo $tpl->display($sty);
			Saafooter();
}

//
//download file 
//
else if (isset($_GET['down']) || isset($_GET['downf']) || isset($_GET['img']) || isset($_GET['thmb']) ||  isset($_GET['imgf']) || isset($_GET['thmbf']))
{
		($hook = kleeja_run_hook('begin_down_go_page')) ? eval($hook) : null; //run hook	
	
		//must know from where he came ! and stop him if not image
		if(isset($_GET['down']) || isset($_GET['downf']))
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
			
			//if not from our site and the waiting page
			$isset_down_h = isset($_GET['downf']) ? 'downloadf-' . $_GET['downf'] . '.html' : 'download' . $_GET['down'] . '.html';
			$not_reffer = true;
			if(strpos($_SERVER['HTTP_REFERER'], $isset_down_h) !== false)
			{
				$not_reffer = false;
			}
			
			$isset_down = isset($_GET['downf']) ? 'download.php?filename=' . $_GET['down'] : 'download.php?id=' . $_GET['down'];
			if(strpos($_SERVER['HTTP_REFERER'], $isset_down) !== false)
			{
				$not_reffer = false;
			}
			
			if($not_reffer)
			{
				if(isset($_GET['downf']))
				{
					$go_to = $config['siteurl'] . (($config['mod_writer']) ? "downloadf-" . $_GET['downf'] . ".html" : "download.php?filename=" . $_GET['downf']);
				}
				else
				{
					$go_to = $config['siteurl'] . (($config['mod_writer']) ? "download" . $_GET['down'] . ".html" : "download.php?id=" . $_GET['down']);
				}
				
				header('Location:' . $go_to);
				exit;
			}
		}
		
		//download by id or filename
		$is_id_filename = (isset($_GET['downf']) || isset($_GET['imgf']) || isset($_GET['thmbf'])) ? true : false;
		
		if($is_id_filename)
		{
			$filename = isset($_GET['downf']) ? $SQL->escape($_GET['downf']) : (isset($_GET['imgf']) ? $SQL->escape($_GET['imgf']) : (isset($_GET['thmbf']) ? $SQL->escape($_GET['thmbf']) : null));
		}
		else
		{
			$id = isset($_GET['down']) ? intval($_GET['down']) : (isset($_GET['img']) ? intval($_GET['img']) : (isset($_GET['thmb']) ? intval($_GET['thmb']) : null));
		}
		
		// worst case default
		$browser = (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars((string) $_SERVER['HTTP_USER_AGENT']) : 'msie 6.0';
		
		//is internet explore 8 ?
		$is_ie8 = strpos($browser, 'msie 8.0') !== false ? true : false;
		//is internet explore 6 ?
		$is_ie6 = strpos($browser, 'msie 6.0') !== false ? true : false;
		

		//updates ups ..
		$update_query = array(
								'UPDATE'=> "{$dbprefix}files",
								'SET'	=> 'uploads=uploads+1, last_down=' . time(),
								'WHERE'	=> ($is_id_filename) ? "name='" . $filename . "'" : "id='" . $id . "'",
							);


		($hook = kleeja_run_hook('qr_update_no_uploads_down')) ? eval($hook) : null; //run hook
				
		if (!$SQL->build($update_query))
		{
			die($lang['CANT_UPDATE_SQL']);
		}
			
		//get info file
		$query = array(
						'SELECT'	=> 'f.id, f.name, f.folder, f.type',
						'FROM'		=> "{$dbprefix}files f",
						'WHERE'		=> ($is_id_filename) ? "name='" . $filename . "'" : "id='" . $id . "'",
					);
						
				
		($hook = kleeja_run_hook('qr_down_go_page_filename')) ? eval($hook) : null; //run hook
		
		$result	= $SQL->build($query);
			
		if ($SQL->num_rows($result) != 0)
		{
			while($row=$SQL->fetch_array($result))
			{
				$ii = $row['id'];
				$n = $row['name'];
				$t = $row['type'];
				$f = $row['folder'];
				//img ot not
				$is_image = in_array(trim($row['type']), array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'tiff', 'tif')) ? true : false; 
			}
				
			$SQL->freeresult($result);
		}
		else
		{
			//not exists img or thumb
			if($is_image)
			{
				($hook = kleeja_run_hook('not_exists_qr_down_img')) ? eval($hook) : null; //run hook
				header("Location: ./images/not_exists.jpg");
				exit;
			}
			else
			{
					//not exists file
					($hook = kleeja_run_hook('not_exists_qr_down_file')) ? eval($hook) : null; //run hook
					kleeja_err($lang['FILE_NO_FOUNDED']);
			}
		}
				
		//downalod porcess

		$path_file = (isset($_GET['thmb']) || isset($_GET['thmbf']))  ? "./{$f}/thumbs/{$n}" : "./{$f}/{$n}";

		$chunksize = 1*(1024*1024); //size that will send to user every second
		$resuming_on = true;
				
		($hook = kleeja_run_hook('down_go_page')) ? eval($hook) : null; //run hook	

		//start download ,,
		if(!is_readable($path_file))
		{
			($hook = kleeja_run_hook('down_file_not_exists')) ? eval($hook) : null; //run hook	
			die('Error, file not exists');
		}
		
		$size = filesize($path_file);
		$name = rawurldecode($n);

		//Figure out the MIME type (if not specified) 
		$ext = explode('.', $path_file);
		$ext = array_pop($ext);
		$mime_type = get_mime_for_header($ext);
		//turn off output buffering to decrease cpu usage
		@ob_end_clean(); 
	 
		// required for IE, otherwise Content-Disposition may be ignored
		if(@ini_get('zlib.output_compression'))
		{
			@ini_set('zlib.output_compression', 'Off');
		}

		header('Content-Type: ' . $mime_type . (($is_ie8) ? '; authoritative=true;' : ''));
		if(!$is_image && $is_ie8)
		{
			header('X-Download-Options: noopen');
		}
		header('Content-Disposition: ' . ($is_image ? 'inline' : 'attachment' ) . ' ; filename="'  . $name . '"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		// The three lines below basically make the  download non-cacheable 
		header('Cache-control: private');
		header('Pragma: private');
		if($is_ie6)
		{
			header('Expires: -1');	
		}
		else
		{
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		}
				
		// multipart-download and download resuming support
		if(isset($_SERVER['HTTP_RANGE']) && !$is_image && $resuming_on)
		{
			list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
			list($range) = explode(",", $range, 2);
			list($range, $range_end) = explode("-", $range);
			$range = intval($range);
			$range_end = (!$range_end) ? $size-1 : intval($range_end);
			$new_length = $range_end-$range+1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");
		}
		else
		{
			$new_length = $size;
			if($size)
			{
				header("Content-Length: " . $size);
			}
		}
			
		/* output the file itself */
		//prevent some limits
		@set_time_limit(0);

		$bytes_send = 0;
		if ($file = fopen($path_file, 'r'))
		{
			if(isset($_SERVER['HTTP_RANGE']) && !$is_image)
			{
				fseek($file, $range);
			}	 
			
			while(!feof($file) && (!connection_aborted()) && ($new_length && ($bytes_send < $new_length)))
			{
				$buffer = fread($file, $chunksize);
				echo $buffer; 
				flush();
				$bytes_send += strlen($buffer);
			}
			fclose($file);
		}
		else
		{
			($hook = kleeja_run_hook('down_go_page_cant_op_file')) ? eval($hook) : null; //run hook
			die('Error - can not open file.');
		}


		exit; // we doesnt need style
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


?>
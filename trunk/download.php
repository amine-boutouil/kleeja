<?php
##################################################
#						Kleeja
#
# Filename : download.php
# purpose :  when user  request file to  download it.
# copyright 2007-2008 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : saanina
##################################################

// security ..
define ( 'IN_INDEX' , true);
//include imprtant file ..
include ('includes/common.php');

($hook = kleeja_run_hook('begin_download_page')) ? eval($hook) : null; //run hook

//
//page of wait downloading files
//
if (isset($_GET['id']) || isset($_GET['filename']))
{
			
			
			$query = array(
						'SELECT'	=> 'f.id, f.name, f.folder, f.size, f.time, f.uploads, f.type',
						'FROM'		=> "{$dbprefix}files f",
					);		
					
			if(isset($_GET['id']))
			{
				$id_l = intval($_GET['id']);
				$query['WHERE']	=	"id=" . $id_l . "";
			}
			elseif (isset($_GET['filename']))
			{
				$filename_l 	= (string) $SQL->escape($_GET['filename']);
				$query['WHERE']	=	"name='" . $filename_l . "'";
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
				$url_file	= ($config['mod_writer']) ? $config['siteurl'] . "down-" . $id . ".html" : $config['siteurl'] . "download.php?down=" . $id;
				$seconds_w	= $config['sec_down'];
				$time		= date("d-m-Y H:a", $time);
				$size		= Customfile_size($size);
				$REPORT		= ($config['mod_writer']) ?  $config['siteurl'] . "report_" . $id . ".html" :  $config['siteurl'] . "go.php?go=report&amp;id=" . $id;
				$file_ext_icon = file_exists('images/filetypes/' . $type . '.gif') ? 'images/filetypes/' . $type . '.gif' : 'images/filetypes/file.gif';
				$sty		= 'download';
			}
			else
			{
					//file not exists
					($hook = kleeja_run_hook('not_exists_qr_downlaod_file')) ? eval($hook) : null; //run hook
					kleeja_err($lang['FILE_NO_FOUNDED']);
			}
			
			($hook = kleeja_run_hook('b4_showsty_downlaod_id_filename')) ? eval($hook) : null; //run hook
			
			 // show style ...
			//header
			Saaheader($lang['DOWNLAOD']);
				//body
				echo $tpl->display($sty);
			//footer
			Saafooter();
}

//
//download file 
//
else if (isset($_GET['down']) || isset($_GET['img']) || isset($_GET['thmb']))
{
		($hook = kleeja_run_hook('begin_down_go_page')) ? eval($hook) : null; //run hook	
		
			//for safe
			$id = isset($_GET['down']) ? intval($_GET['down']) : (isset($_GET['img']) ? intval($_GET['img']) : (isset($_GET['thmb']) ? intval($_GET['thmb']) : null));
		
			$REFERER = !empty($_SERVER['HTTP_REFERER']) ? strtolower($_SERVER['HTTP_REFERER']) : strtolower(getenv('HTTP_REFERER'));
			if ($REFERER != '' && strpos($_SERVER['HTTP_REFERER'], 'download') === false && !(isset($_GET['thmb']) || isset($_GET['img'])))
			{
				$linkoo	= ($config['mod_writer']) ?	'./download' . $id . '.html' : './download.php?id=' . $id;
				header('Location: ' . $linkoo);
				exit;
			}
			else
			{
				//updates ups ..
				$update_query = array(
										'UPDATE'	=> "{$dbprefix}files",
										'SET'		=> 'uploads=uploads+1, last_down=' . time(),
										'WHERE'		=> "id='" . $id . "'",
									);

				($hook = kleeja_run_hook('qr_update_no_uploads_down')) ? eval($hook) : null; //run hook
				
				if (!$SQL->build($update_query)) die($lang['CANT_UPDATE_SQL']);
			
				//get info file
				$query = array(
							'SELECT'	=> 'f.id, f.name, f.folder',
							'FROM'		=> "{$dbprefix}files f",
							'WHERE'		=>	"id=" . $id . ""
						);		
						
				
				($hook = kleeja_run_hook('qr_down_go_page_filename')) ? eval($hook) : null; //run hook
				$result	=	$SQL->build($query);
				
				if ($SQL->num_rows($result) != 0)
				{
					while($row=$SQL->fetch_array($result))
					{
						$n = $row['name'];
						$f = $row['folder'];
					}
					$SQL->freeresult($result);
				}
				else
				{
					//not exists img or thumb
					if(isset($_GET['img']) || isset($_GET['thmb']))
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
				$path_file = isset($_GET['thmb']) ? "./{$f}/thumbs/{$n}" : "./{$f}/{$n}";
				$chunksize = 1*(1024*1024); //size that will send to user every second
				
				($hook = kleeja_run_hook('down_go_page')) ? eval($hook) : null; //run hook	

				//start download ,,
				if(!is_readable($path_file)) die('Error, file not exists');
				$size = filesize($path_file);
				$name = rawurldecode($n);
				//Figure out the MIME type (if not specified) 
				$ext = explode('.', $path_file);
				$ext = array_pop($ext);
				$mime_type = get_mime_for_header($ext);
				@ob_end_clean(); //turn off output buffering to decrease cpu usage
	 
				// required for IE, otherwise Content-Disposition may be ignored
				if(@ini_get('zlib.output_compression'))
				{
					@ini_set('zlib.output_compression', 'Off');
				}
				
				header('Content-Type: ' . $mime_type);
				header('Content-Disposition: attachment; filename="'  . $name . '"');
				header("Content-Transfer-Encoding: binary");
				header('Accept-Ranges: bytes');
				// The three lines below basically make the  download non-cacheable 
				header("Cache-control: private");
				header('Pragma: private');
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				
				// multipart-download and download resuming support
				if(isset($_SERVER['HTTP_RANGE']))
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
					header("Content-Length: " . $size);
				}
		 
				/* output the file itself */
				$bytes_send = 0;
				if ($file = fopen($file, 'r'))
				{
					if(isset($_SERVER['HTTP_RANGE']))
					fseek($file, $range);
				 
					while(!feof($file) && (!connection_aborted()) && ($bytes_send < $new_length))
					{
						$buffer = fread($file, $chunksize);
						print($buffer); 
						flush();
						$bytes_send += strlen($buffer);
					}
					fclose($file);
				}
				else
				{
					die('Error - can not open file.');
				}

			}//elser efer

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

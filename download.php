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
				$id = intval($_GET['id']);
				$query['WHERE']	=	"id=" . $id . "";
			}
			elseif (isset($_GET['filename']))
			{
				$name 	= (string) $SQL->escape($_GET['filename']);
				$query['WHERE']	=	"name='" . $name . "'";
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
				$url_file	= ($config['mod_writer']) ? $config['siteurl'] . "down-" . $name . "-" . $folder . "-" . $id . ".html" : $config['siteurl'] . "download.php?down=" . $id;
				$seconds_w	= $config['sec_down'];
				$time		= date("d-m-Y H:a", $time);
				$size		= Customfile_size($size);
				$REPORT		= ($config['mod_writer']) ?  $config['siteurl'] . "report_" . $id . ".html" :  $config['siteurl'] . "go.php?go=report&amp;id=" . $id;
				$file_ext_icon = file_exists('images/filetypes/' . $type . '.gif') ? 'images/filetypes/' . $type . '.gif' : 'images/filetypes/file.gif';
				$sty		= 'download';
			}
			else
			{
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
//page for update images vists and redirect  to it
//
else if(isset($_GET['img']))
{
			//for safe
			$img = intval($_GET['img']);
			
			$query = array(
									'SELECT'=> 'f.name, f.folder, f.type',
									'FROM'	=> "{$dbprefix}files f",
									'WHERE'	=> 'f.id=' . $img
								);
					
			($hook = kleeja_run_hook('qr_download_img')) ? eval($hook) : null; //run hook
			$result	=	$SQL->build($query);
			
			if ($SQL->num_rows($result) != 0)
			{
				while($row=$SQL->fetch_array($result))
				{
					$n	=  $row['name'];
					$f	=  $row['folder'];
					$t	=  $row['type'];
				}
			}
			else
			{
				($hook = kleeja_run_hook('not_exists_qr_downlaod_img')) ? eval($hook) : null; //run hook
				header("Location: ./images/not_exists.jpg");
			}
			
			$SQL->freeresult($result);

			//update ups
			$update_query = array(
										'UPDATE'=> "{$dbprefix}files",
										'SET'	=> 'uploads=uploads+1,last_down='.time(),
										'WHERE'	=> 'id="' . $img . '"',
									);

			($hook = kleeja_run_hook('qr_update_download_img')) ? eval($hook) : null; //run hook
			if (!$SQL->build($update_query)){ die($lang['CANT_UPDATE_SQL']);}

			//must be img
			if (!in_array(strtolower($t), array('png', 'gif', 'jpg', 'jpeg', 'tif', 'tiff')))
			{
				$text = $lang['NOT_IMG'] . '<br /><a href="' . (($config['mod_writer']) ?  $config['siteurl'] . 'download' . $img . '.html': $config['siteurl'] . "download.php?id=$img") . '">' . $lang['CLICK_DOWN'] . '</a>';
				kleeja_err($text);
			}
			else
			{
				// if there is images ... 				
					if(file_exists("./$f/$n"))
					{
						($hook = kleeja_run_hook('y_exists_downlaod_img')) ? eval($hook) : null; //run hook
						header("Location: ./$f/$n");
					}
					else
					{ 
						($hook = kleeja_run_hook('not_exists_fi_downlaod_img')) ? eval($hook) : null; //run hook
						header("Location: ./images/not_exists.jpg");
					}
			}
}

//
//get thumb of image
//
else if(isset($_GET['thmb']))
{
			//for safe
			$thmb = intval ($_GET['thmb']);
			
			$query = array(
								'SELECT'=> 'f.name, f.folder, f.type',
								'FROM'	=> "{$dbprefix}files f",
								'WHERE'	=> "f.id='" . $thmb . "'"
							);
					
			($hook = kleeja_run_hook('qr_download_thmb')) ? eval($hook) : null; //run hook
			$result	=	$SQL->build($query);
			
			if ($SQL->num_rows($result) != 0 )
			{
				while($row=$SQL->fetch_array($result))
				{
					$n	= $row['name'];
					$f	= $row['folder'];
					$t	= $row['type'];
				}
			}
			else
			{
				($hook = kleeja_run_hook('not_exists_qr_downlaod_thmb')) ? eval($hook) : null; //run hook
				header("Location: ./images/not_exists.jpg");
			}
			
			$SQL->freeresult($result);
			
			//paths
			$image_path = "./{$f}/{$n}";
			$image_thumb_path = "./{$f}/thumbs/{$n}";
			
			//must be img
			if (!in_array(strtolower($t), array('png', 'gif', 'jpg', 'jpeg', 'tif', 'tiff')))
			{
				// if there is images ... 
					if(file_exists($image_path))
					{
						($hook = kleeja_run_hook('y_exists_downlaod_img_inth')) ? eval($hook) : null; //run hook
						header("Location: {$image_path}");
					}
					else
					{
						($hook = kleeja_run_hook('not_exists_fi_downlaod_img_inth')) ? eval($hook) : null; //run hook
						header("Location: ./images/not_exists.jpg");
					}
				
				
			}
			else
			{
				($hook = kleeja_run_hook('y_exists_downlaod_thmb')) ? eval($hook) : null; //run hook
				header("Location: {$image_thumb_path}");
			}

}

//
//download file 
//
else if (isset($_GET['down']))
{
		($hook = kleeja_run_hook('begin_down_go_page')) ? eval($hook) : null; //run hook	
		
		//for safe
			$id	= intval($_GET['down']);
			$REFERER = !empty($_SERVER['HTTP_REFERER']) ? strtolower($_SERVER['HTTP_REFERER']) : strtolower(getenv('HTTP_REFERER'));
			if ($REFERER != '' && strpos($_SERVER['HTTP_REFERER'], 'download') === false)
			{
				$linkoo	= ($config['mod_writer']) ?	'./download' . $id . '.html' : './download.php?id=' . $id;
				header('Location: ' . $linkoo);
			
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
							'WHERE'		=>	"id=" . $id . "";
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
					die('No requested file');
				}
				
				
				//downalod porcess
				$path_file = "./{$f}/{$n}";
				$chunksize = 1*(1024*1024); //size that will send to user every second
				
				($hook = kleeja_run_hook('down_go_page')) ? eval($hook) : null; //run hook	

				//start download ,,
				if(!is_readable($path_file)) die('Error, file not exists');
				$size = filesize($path_file);
				$name = rawurldecode($name);
				/* Figure out the MIME type (if not specified) */
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
//no one of above are there, you can you hooks to get more actions here
//
else
{
		($hook = kleeja_run_hook('err_navig_download_page')) ? eval($hook) : null; //run hook
		kleeja_err($lang['ERROR_NAVIGATATION']);
}

($hook = kleeja_run_hook('end_download_page')) ? eval($hook) : null; //run hook


?>

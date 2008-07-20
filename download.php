<?php
##################################################
#						Kleeja
#
# Filename : download.php
# purpose :  when user  request file to  download it.
# copyright 2007-2008 Kleeja.com ..
# last edit by : saanina
##################################################

// security ..
define ( 'IN_INDEX' , true);
//include imprtant file ..
include ('includes/common.php');

($hook = kleeja_run_hook('begin_download_page')) ? eval($hook) : null; //run hook

	if (isset($_GET['id']) || isset($_GET['filename']))
	{
			
			$query = array(
						'SELECT'	=> 'f.*',
						'FROM'		=> "{$dbprefix}files f",
					);		

					
			if(isset($_GET['id']))
			{
				$id = intval($_GET['id']);
				$query['WHERE']	=	"id='". $id ."'";
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
				$url_file 	= ($config['mod_writer']) ? $config['siteurl']."down-". $name ."-". $folder ."-". $id .".html" : $config['siteurl']."go.php?go=down&amp;n=$name&amp;f=$folder&amp;i=$id";
				$seconds_w 	= $config['sec_down'];
				$time 		= date("d-m-Y H:a", $time);
				$size 		= Customfile_size($size);
				$REPORT 	= ($config['mod_writer']) ?  $config['siteurl']."report_".$id.".html" :  $config['siteurl']."go.php?go=report&amp;id=$id";

				$sty = 'download';

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
			print $tpl->display($sty);
			//footer
			Saafooter();
	 //
	}
	else if(isset($_GET['img']))
	{
			//for safe
			$img = intval($_GET['img']);
			
			$query = array(
						'SELECT'	=> 'f.name, f.folder, f.type',
						'FROM'		=> "{$dbprefix}files f",
						'WHERE'		=> 'f.id='.$img
					);
					
			($hook = kleeja_run_hook('qr_download_img')) ? eval($hook) : null; //run hook
			
			$result	=	$SQL->build($query);
			
			if ($SQL->num_rows($result) != 0  )
			{
				while($row=$SQL->fetch_array($result))
				{
					$n =  $row['name'];
					$f =  $row['folder'];
					$t =  $row['type'];
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
						'UPDATE'	=> "{$dbprefix}files",
						'SET'		=> 'uploads=uploads+1,last_down='.time(),
						'WHERE'		=> 'id="' . $img . '"',
					);

			($hook = kleeja_run_hook('qr_update_download_img')) ? eval($hook) : null; //run hook
			if (!$SQL->build($update_query)){ die($lang['CANT_UPDATE_SQL']);}

			//must be img //
			if (!in_array($t,array('png','gif','jpg','jpeg','tif','tiff')))
			{
				$text = $lang['NOT_IMG'] . '<br /><a href="'.(($config['mod_writer'])?  $config['siteurl'].'download'.$img.'.html': $config['siteurl']."download.php?id=$img"  ).'">' . $lang['CLICK_DOWN'] . '</a>';
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
	else if( isset($_GET['thmb']) ) // thumb
	{
			//for safe
			$thmb = intval ($_GET['thmb']);

			$query = array(
						'SELECT'	=> 'f.name, f.folder, f.type',
						'FROM'		=> "{$dbprefix}files f",
						'WHERE'		=> "f.id='". $thmb ."'"
					);
					
			($hook = kleeja_run_hook('qr_download_thmb')) ? eval($hook) : null; //run hook
			
			$result	=	$SQL->build($query);
			
			if ($SQL->num_rows($result) != 0 )
			{
				while($row=$SQL->fetch_array($result))
				{
					$n =  $row['name'];
					$f =  $row['folder'];
					$t =  $row['type'];
				}
			}
			else
			{
				($hook = kleeja_run_hook('not_exists_qr_downlaod_thmb')) ? eval($hook) : null; //run hook
				header("Location: ./images/not_exists.jpg");
			
			}
			$SQL->freeresult($result);


			//must be img //
			if (!in_array($t, array('png','jpg','jpeg','gif')))
			{
			
				// if there is images ... 
					if(file_exists("./$f/$n"))
					{
						($hook = kleeja_run_hook('y_exists_downlaod_img_inth')) ? eval($hook) : null; //run hook
						header("Location: ./$f/$n");
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
				header("Location: ./$f/thumbs/$n");
			}

	}
	else
	{
		($hook = kleeja_run_hook('err_navig_download_page')) ? eval($hook) : null; //run hook
		kleeja_err($lang['ERROR_NAVIGATATION']);
	}

($hook = kleeja_run_hook('end_download_page')) ? eval($hook) : null; //run hook


?>
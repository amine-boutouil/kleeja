<?
##################################################
#						Kleeja
#
# Filename : download.php
# purpose :  when user  request file to  download it.
# copyright 2007 Kleeja.com ..
# last edit by : saanina
##################################################

	// security ..
	define ( 'IN_INDEX' , true);
	//include imprtant file ..
	require ('includes/common.php');


	if ( isset($_GET['id']) )
	{
			//for safe
			$id = intval ($_GET['id']);

			$sql	=	$SQL->query("SELECT * FROM {$dbprefix}files where id=$id");

			if ($SQL->num_rows($sql) != 0  )
			{
				while($row=$SQL->fetch_array($sql)){
				@extract ($row);
			}
			$SQL->freeresult($sql);

			// SOME WORDS FOR TEMPLATE
			$file_found = $lang['FILE_FOUNDED'];
			$wait  		= $lang['WAIT'];
			$click 		= $lang['CLICK_DOWN'];
			$err_jv 	= $lang['JS_MUST_ON'];
			$url_file 	= ($config[mod_writer]) ? $config[siteurl]."down-".$name."-".$folder."-".$id.".html" : $config[siteurl]."go.php?go=down&amp;n=$name&amp;f=$folder&amp;i=$id";
			$seconds_w 	= $config[sec_down];
			$time 		= date("d-m-Y H:a", $time);
			$size 		= Customfile_size($size);
			$information= $lang['FILE_INFO'];
			$L_FILE 	= $lang['FILENAME'];
			$L_SIZE		= $lang['FILESIZE'];
			$L_TYPE		= $lang['FILETYPE'];
			$L_TIME		= $lang['FILEDATE'];
			$L_UPS		= $lang['FILEUPS'];
			$L_REPORT 	= $lang['FILEREPORT'];
			$REPORT 	= ($config[mod_writer]) ?  $config[siteurl]."report_".$id.".html" :  $config[siteurl]."go.php?go=report&amp;id=$id";

			$sty = 'download.html';

			}
			else
			{
				$text = $lang['FILE_NO_FOUNDED'];
				$sty = 'err.html';
			}
			 // show style ...

			//header
			Saaheader($lang['DOWNLAOD']);
		 	//body
			print $tpl->display($sty);
			//footer
			Saafooter();
	 //
	}
	else if( isset($_GET['img']) )
	{
			//for safe
			$img = intval ($_GET['img']);

			//updates ups ..
			$sql	=	$SQL->query("SELECT name,folder,type FROM {$dbprefix}files where id=$img");

			if ($SQL->num_rows($sql) != 0  )
			{
				while($row=$SQL->fetch_array($sql)){
				$n =  $row[name];
				$f =  $row[folder];
				$t =  $row[type];
				}
			}
			else
			{
				
				header("Location: ./images/not_exists.jpg");
			}
			$SQL->freeresult($sql);

			$update = $SQL->query("UPDATE {$dbprefix}files SET
									uploads=uploads+1,
									last_down='". time() . "'
		                            WHERE id='$img' ");
			if (!$update){ die(	$lang['CANT_UPDATE_SQL']);}

			//must be img //
			$imgs = array('png','gif','jpg','jpeg','tif','tiff');
			if (!in_array($t,$imgs) )
			{
				$text = $lang['NOT_IMG'] . '<br /><a href="'.(($config[mod_writer])?  $config[siteurl].'download'.$img.'.html': $config[siteurl]."download.php?id=$img"  ).'">' . $lang['CLICK_DOWN'] . '</a>';
				$sty = 'err.html';
			}
			else
			{
				// if there is images ... 
					//show img
					if(file_exists("./$f/$n")){
						header("Location: ./$f/$n");
					}else{ // 
						header("Location: ./images/not_exists.jpg");
					}
				
			
			}
	}
		else if( isset($_GET['thmb']) ) // thumb
	{
			//for safe
			$thmb = intval ($_GET['thmb']);

			//updates ups ..
			$sql	=	$SQL->query("SELECT name,folder,type FROM {$dbprefix}files where id=$thmb");

			if ($SQL->num_rows($sql) != 0  )
			{
				while($row=$SQL->fetch_array($sql)){
				$n =  $row[name];
				$f =  $row[folder];
				$t =  $row[type];
				}
			}
			else
			{
				header("Location: ./images/not_exists.jpg");
			
			}
			$SQL->freeresult($sql);


			//must be img //
			$imgs = array('png','jpg','jpeg','gif');
			if (!in_array($t,$imgs) )
			{// no thumbs ..
			
				// if there is images ... 
					//show img
					if(file_exists("./$f/$n")){
						header("Location: ./$f/$n");
					}else{ // 
						header("Location: ./images/not_exists.jpg");
					}
				
				
					
					
			}else{
			//show img
			header("Location: ./$f/thumbs/$n");
			}

	}
	else
	{
		die ('<STRONG style="color:red">' . $lang['ERROR_NAVIGATATION'] . '</STRONG>');
	}



?>
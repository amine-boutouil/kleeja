<?php
	//img
	//part of admin extensions
	//conrtoll imgs
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	
	//number of images in each page 
	if(!isset($images_cp_perpage) || !$images_cp_perpage)
	{
		 // you can add this varibale to config.php
		$images_cp_perpage = 9;
	}
	
	
	//for style ..
	$stylee		= "admin_img";
	$action 	= basename(ADMIN_PATH) . "?cp=img_ctrl&amp;page=" . (isset($_GET['page']) ? intval($_GET['page']) : '1');

	$query	= array('SELECT'	=> 'COUNT(f.id) AS total_files',
					'FROM'		=> "{$dbprefix}files f",
					'JOINS'		=> array(
							array(
								'LEFT JOIN'	=> "{$dbprefix}users u",
								'ON'		=> 'u.id=f.user'
							)
						),
					'ORDER BY'	=> 'f.id DESC'
					);
						
	if(isset($_GET['last_visit']))
	{
		$query['WHERE']	= "time > '" . intval($_GET['last_visit']) . "' AND type IN ('gif','jpg','png','bmp','jpeg','tif','tiff','GIF','JPG','PNG','BMP','JPEG','TIF','TIFF')";
	}
	else
	{
		$query['WHERE']	= "type IN ('gif','jpg','png','bmp','jpeg','tif','tiff','GIF','JPG','PNG','BMP','JPEG','TIF','TIFF')";
	}
		
	$result_p = $SQL->build($query);
	
	
	$nums_rows = 0;
	$n_fetch = $SQL->fetch_array($result_p);
	$nums_rows = $n_fetch['total_files'];
	$SQL->freeresult($result_p);

	//pager
	$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$Pager = new SimplePager($images_cp_perpage, $nums_rows, $currentPage);
	$start = $Pager->getStartRow();

		
	$no_results = false;
		
	if ($nums_rows > 0) 
	{
		$query['SELECT'] = 'f.*, u.name AS username';
		$query['LIMIT']	= "$start, $images_cp_perpage";
		$result = $SQL->build($query);
		
		$tdnum = 0;
		//$all_tdnum = 0;
		$sizes = false;
		$num = 0;
		while($row=$SQL->fetch_array($result))
		{
			//thumb ?
			$is_there_thumb = file_exists(PATH . $row['folder'] . '/thumbs/' . $row['name']) ? true : false;
			
			//make new lovely arrays !!
			$arr[]		= array('id'		=> $row['id'],
								'tdnum'		=> ($tdnum==0) ? '<tr>': '',
								'tdnum2'	=> ($tdnum==2) ? '</tr>' : '',
								'name'		=> ($row['real_filename'] == '' ? $row['name'] : $row['real_filename']),
								'ip' 		=> $lang['IP'] . ':' . htmlspecialchars($row['user_ip']),
								'href'		=> PATH . $row['folder'] . '/' . $row['name'],
								'size'		=> $lang['FILESIZE']. ':' . Customfile_size($row['size']),
								'ups'		=> $lang['FILEUPS'] .' : ' . $row['uploads'],
								'time'		=> $lang['FILEDATE']. ':' . date('d-m-Y H:a', $row['time']),
								'user'		=> $lang['BY'] . ':' . ($row['user'] == '-1' ? $lang['GUST'] :  $row['username']),
								'is_thumb'	=> $is_there_thumb,
								'thumb_link'=>  $is_there_thumb ? PATH . $row['folder'] . '/thumbs/' . $row['name'] :  PATH . $row['folder'] . '/' . $row['name'],
						);
			
			//fix ... 
			$tdnum = ($tdnum == 2) ? 0 : $tdnum+1; 
			//$all_tdnum++;
			//
			$del[$row['id']] = (isset($_POST['del_' . $row['id']])) ? $_POST['del_' . $row['id']] : '';

		
				//when submit !!
				if (isset($_POST['submit']))
				{
					if ($del[$row['id']])
					{
						//delete from folder ..
						@kleeja_unlink ($root_path . $row['folder'] . "/" . $row['name']);
						
						//delete thumb
						if (is_file($row['folder'] . "/thumbs/" . $row['name'] ))
						{
							@kleeja_unlink ($root_path . $row['folder'] . "/thumbs/" . $row['name'] );
						}
						$ids[] = $row['id'];
						$num++;		
						$sizes += $row['size'];
						
					}
			}
		}
			
		if (isset($_POST['submit']))
		{
			//no files to delete
			if(isset($ids) && !empty($ids))
			{
				//$imp =  implode(',', $ids);
				//we have imprvove this and use implode with In statment in future [WE DID :D]
				$query_del = array('DELETE'	=> "{$dbprefix}files",
									'WHERE'	=> "id IN (" . implode(',', $ids) . ")",);
			
				$SQL->build($query_del);

				//update number of stats
				$update_query	= array('UPDATE'	=> "{$dbprefix}stats",
										'SET'		=> "sizes=sizes-$sizes,files=files-$num",
									);
				//echo $sizes;
				$SQL->build($update_query);
			}
		}
		
		$SQL->freeresult($result);
	}
	else #num_rows
	{
		$no_results = true;
	}
		$total_pages 	= $Pager->getTotalPages(); 
		$page_nums 		= $Pager->print_nums(basename(ADMIN_PATH). '?cp=img_ctrl'); 
		
	//after submit 
	if(isset($_POST['submit']))
	{
		$text	= $lang['FILES_UPDATED'] . '<meta HTTP-EQUIV="REFRESH" content="0; url=' . basename(ADMIN_PATH) . '?cp=img_ctrl&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : '1') . '">' ."\n";
		$stylee	= "admin_info";
	}
?>

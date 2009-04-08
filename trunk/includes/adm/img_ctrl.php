<?php
	//img
	//part of admin extensions
	//conrtoll imgs
	//kleeja.com
	
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
	$action 	= "admin.php?cp=img_ctrl&amp;page=" . intval($_GET['page']) ;

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
		$query['WHERE']	= "time > '". intval($_GET['last_visit']) . "' AND type IN ('gif','jpg','png','bmp','jpeg','tif','tiff')";
	}
	else
	{
		$query['WHERE']	= "type IN ('gif','jpg','png','bmp','jpeg','tif','tiff')";
	}
		
	$result = $SQL->build($query);
	
	
	$nums_rows = 0;
	$n_fetch = $SQL->fetch_array($result);
	$nums_rows = $n_fetch['total_files'];

	/////////////pager
	$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$Pager = new SimplePager($images_cp_perpage, $nums_rows, $currentPage);
	$start = $Pager->getStartRow();
	////////////////
		
	$no_results = false;
		
	if ($nums_rows > 0) 
	{
		$query['SELECT'] = 'f.*, u.name AS username';
		$query['LIMIT']	= "$start, $images_cp_perpage";
		$result = $SQL->build($query);
		
		$tdnum = 0;
		//$all_tdnum = 0;
		while($row=$SQL->fetch_array($result))
		{
		
			//make new lovely arrays !!
			$arr[]		= array('id'		=> $row['id'],
								'tdnum'		=> ($tdnum==0) ? '<tr>': '',
								'tdnum2'	=> ($tdnum==2) ? '</tr>' : '',
								'name'		=> $row['name'],
								'ip' 		=> $lang['IP'] . ':' . htmlspecialchars($row['user_ip']),
								'href'		=> $row['folder'] . '/' . $row['name'],
								'size'		=> $lang['FILESIZE']. ':' . Customfile_size($row['size']),
								'ups'		=> $lang['FILEUPS'] .' : ' . $row['uploads'],
								'time'		=> $lang['FILEDATE']. ':' . date('d-m-Y H:a', $row['time']),
								'user'		=> $lang['BY'] . ':' . ($row['user'] == '-1' ? $lang['GUST'] :  $row['username']),
								'thumb_link'=> (is_file($row['folder'] . '/thumbs/' . $row['name'])) ? $row['folder'] . '/thumbs/' . $row['name'] : $row['folder'] . '/' . $row['name'],
						);
			
			//fix ... 
			$tdnum = ($tdnum==2) ? 0 : $tdnum+1; 
			//$all_tdnum++;
			//
			$del[$row['id']] = (isset($_POST['del_' . $row['id']])) ? $_POST['del_' . $row['id']] : '';

				//when submit  !! !!
				if (isset($_POST['submit']))
				{
					if ($del[$row['id']])
					{
						//we have imprvove this and use implode with In statment in future
						$query_del = array(
										'DELETE'	=> "{$dbprefix}files",
										'WHERE'		=> "id='" . intval($row['id']) . "'"
										);
															
						if (!$SQL->build($query_del))
						{
							die($lang['CANT_DELETE_SQL']);
						}	

						//delete from folder ..
						@unlink ($row['folder'] . "/" . $row['name']);
						//delete thumb
						if (is_file($row['folder'] . "/thumbs/" . $row['name']))
						{
							@unlink ($row['folder'] . "/thumbs/" . $row['name']);
						}
					}
			}
		}
		$SQL->freeresult($result);
	}
	else #num_rows
	{
		$no_results = true;
	}
		$total_pages 	= $Pager->getTotalPages(); 
		$page_nums 		= $Pager->print_nums($config['siteurl'].'admin.php?cp=img_ctrl'); 
		
	//after submit 
	if(isset($_POST['submit']))
	{
		$text	= $lang['FILES_UPDATED']. '<meta HTTP-EQUIV="REFRESH" content="0; url=./admin.php?cp=img_ctrl&amp;page=' . intval($_GET['page']). '">' ."\n";
		$stylee	= "admin_info";
	}
?>

<?php
/**
*
* @package adm
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/

// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}

//number of images in each page 
if(!isset($images_cp_perpage) || !$images_cp_perpage)
{
	// you can add this varibale to config.php
	$images_cp_perpage = 20;
}

//for style ..
$stylee	= "admin_img";
$action	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : 1);

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

$img_types = array('gif','jpg','png','bmp','jpeg','tif','tiff','GIF','JPG','PNG','BMP','JPEG','TIF','TIFF');

$query['WHERE']	= "type IN ('" . implode("', '", $img_types) . "')";

if(isset($_GET['last_visit']))
{
	$query['WHERE']	.= " AND time > " . intval($_GET['last_visit']) . "";
}


$result_p = $SQL->build($query);

$nums_rows = 0;
$n_fetch	= $SQL->fetch_array($result_p);
$nums_rows	= $n_fetch['total_files'];
$SQL->freeresult($result_p);

//pager
$currentPage= isset($_GET['page']) ? intval($_GET['page']) : 1;
$Pager		= new SimplePager($images_cp_perpage, $nums_rows, $currentPage);
$start		= $Pager->getStartRow();


$no_results = $affected = $sizes = false;
if ($nums_rows > 0) 
{
	$query['SELECT'] = 'f.*, u.name AS username';
	$query['LIMIT']	= "$start, $images_cp_perpage";
	$result = $SQL->build($query);

	$tdnum = $num = 0;

	while($row=$SQL->fetch_array($result))
	{
		//thumb ?
		$is_there_thumb = file_exists(PATH . $row['folder'] . '/thumbs/' . $row['name']) ? true : false;

		//make new lovely arrays !!
		$arr[]	= array(
						'id'		=> $row['id'],
						'tdnum'		=> $tdnum == 0 ? '<tr>': '',
						'tdnum2'	=> $tdnum == 3 ? '</tr>' : '',
						'name'		=> ($row['real_filename'] == '' ? ((strlen($row['name']) > 15) ? substr($row['name'], 0, 15) . '...' : $row['name']) : ((strlen($row['real_filename']) > 15) ? substr($row['real_filename'], 0, 15) . '...' : $row['real_filename'])),
						'ip' 		=> $lang['IP'] . ':' . htmlspecialchars($row['user_ip']),
						'href'		=> PATH . $row['folder'] . '/' . $row['name'],
						'size'		=> Customfile_size($row['size']),
						'ups'		=> $row['uploads'],
						'time'		=> date('d-m-Y h:i a', $row['time']),
						'user'		=> $row['user'] == '-1' ? $lang['GUST'] :  $row['username'],
						'is_thumb'	=> $is_there_thumb,
						'thumb_link'=> $is_there_thumb ? PATH . $row['folder'] . '/thumbs/' . $row['name'] :  PATH . $row['folder'] . '/' . $row['name'],
					);

		//fix ... 
		$tdnum = $tdnum == 3 ? 0 : $tdnum+1; 

		$del[$row['id']] = isset($_POST['del_' . $row['id']]) ? $_POST['del_' . $row['id']] : '';

		//when submit !!
		if (isset($_POST['submit']))
		{
			if ($del[$row['id']])
			{
				//delete from folder ..
				@kleeja_unlink ($root_path . $row['folder'] . '/' . $row['name']);
				//delete thumb
				if (file_exists($root_path . $row['folder'] . '/thumbs/' . $row['name'] ))
				{
					@kleeja_unlink ($root_path . $row['folder'] . '/thumbs/' . $row['name'] );
				}
				$ids[] = $row['id'];
				$num++;		
				$sizes += $row['size'];	
			}
		}
	}

	$SQL->freeresult($result);

	if (isset($_POST['submit']))
	{
		//no files to delete
		if(isset($ids) && sizeof($ids))
		{
			$query_del = array(
								'DELETE'	=> "{$dbprefix}files",
								'WHERE'	=> "id IN (" . implode(',', $ids) . ")"
							);
			
			$SQL->build($query_del);

			//update number of stats
			$update_query	= array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "sizes=sizes-$sizes, files=files-$num",
								);

			$SQL->build($update_query);
			if($SQL->affected())
			{
				delete_cache('data_stats');
				$affected = true;
			}
		}
	}
}
else
{
	$no_results = true;
}

//pages
$total_pages 	= $Pager->getTotalPages(); 
$page_nums 		= $Pager->print_nums(basename(ADMIN_PATH). '?cp=' . basename(__file__, '.php')); 

//after submit 
if(isset($_POST['submit']))
{
	$text	= ($affected ? $lang['FILES_UPDATED'] : $lang['NO_UP_CHANGE_S']) . '<meta HTTP-EQUIV="REFRESH" content="2; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : '1') . '">' . "\n";
	$stylee	= "admin_info";
}


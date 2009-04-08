<?php
	//files
	//part of admin extensions
	//conrtoll files
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	

	//for style ..
	$stylee		= "admin_files";
	
	$url_or		= (isset($_REQUEST['order_by']) ? '&amp;order_by=' . $_REQUEST['order_by'] : '');
	$url_lst	= (isset($_REQUEST['last_visit']) ? '&amp;last_visit=' . $_REQUEST['last_visit'] : '');
	$url_pg		= (isset($_REQUEST['page']) ? '&amp;page=' . intval($_REQUEST['page']) : '');
	$page_action = "admin.php?cp=files" . $url_or	. $url_lst;
	$ord_action	= "admin.php?cp=files" . $url_pg	. $url_lst;
	$action		= $page_action . $url_or;
	
	
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
						
	//posts search ..
	if (isset($_POST['search_file']))
	{
		$file_namee	= ($_POST['filename']!='') ? 'AND f.real_filename LIKE \'%' . $SQL->escape($_POST['filename']) . '%\' ' : ''; 
		$usernamee	= ($_POST['username']!='') ? 'AND u.name LIKE \'%' . $SQL->escape($_POST['username']) . '%\'' : ''; 
		$size_than	=   ' f.size ' . ($_POST['than']!=1 ? '<=' : '>=') . (intval($_POST['size']) * 1024) . ' ';
		$ups_than	=  ($_POST['ups']!='') ? 'AND f.uploads ' . ($_POST['uthan']!=1 ? '<' : '>') . intval($_POST['ups']) . ' ' : '';
		$rep_than	=  ($_POST['rep']!='') ? 'AND f.report ' . ($_POST['rthan']!=1 ? '<' : '>') . intval($_POST['rep']) . ' ' : '';
		$lstd_than	=  ($_POST['lastdown']!='') ? 'AND f.last_down =' . (time()-(intval($_POST['lastdown']) * (24 * 60 * 60))) . ' ' : '';
		$exte		=  ($_POST['ext']!='') ? 'AND f.type LIKE \'%' . $SQL->escape($_POST['ext']) . '%\' ' : '';
		$ipp		=  ($_POST['user_ip']!='') ? 'AND f.user_ip LIKE \'%' . $SQL->escape($_POST['user_ip']) . '%\' ' : '';
		

		$query['WHERE'] = "$size_than $file_namee $ups_than $exte $rep_than $usernamee $lstd_than $exte $ipp";

	}
	else if(isset($_REQUEST['last_visit']))
	{
		$query['WHERE']	= "f.time > '" . intval($_REQUEST['last_visit']) . "'";
	}
	else if(isset($_REQUEST['order_by']))
	{
		$query['ORDER BY'] = "f." . $SQL->escape($_REQUEST['order_by']) . " DESC";
	}

	$result = $SQL->build($query);
	
	$nums_rows = 0;
	$n_fetch = $SQL->fetch_array($result);
	$nums_rows = $n_fetch['total_files'];

	/////////////pager 
	$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
	$Pager = new SimplePager($perpage, $nums_rows, $currentPage);
	$start = $Pager->getStartRow();

	$no_results = false;
	if ($nums_rows > 0)
	{
		$query['SELECT'] = 'f.*, u.name AS username';
		$query['LIMIT']	= "$start, $perpage";
		$result = $SQL->build($query);

		while($row=$SQL->fetch_array($result))
		{
			//make new lovely arrays !!
			$userfile =  $config['siteurl'] . ($config['mod_writer'] ? 'fileuser_' . $row['user'] . '.html' : 'ucp.php?go=fileuser&amp;id=' . $row['user']);
			
			
			$arr[]	= array('id' =>$row['id'],
							'name' =>"<a href=\"./" . $row['folder'] . "/" . $row['name'] . "\" target=\"blank\">" . ($row['real_filename'] == '' ? $row['name'] : $row['real_filename']) . "</a>",
							'size' =>Customfile_size($row['size']),
							'ups' =>$row['uploads'],
							'time' => date("d-m-Y H:a", $row['time']),
							'type' =>$row['type'],
							'folder' =>$row['folder'],
							'report' =>($row['report'] > 4)? "<span style=\"color:red\"><big>" . $row['report'] . "</big></span>":$row['report'],
							'user' =>($row['user'] == '-1') ? $lang['GUST'] :  '<a href="' . $userfile . '" target="_blank">' . $row['username'] . '</a>',
							'ip' 	=> '<a href="http://www.ripe.net/whois?form_type=simple&amp;full_query_string=&amp;searchtext=' . $row['user_ip'] . '&amp;do_search=Search" target="_new">' . $row['user_ip'] . '</a>',
						);
			//
			$del[$row['id']] = (isset($_POST['del_' . $row['id']]) ) ? $_POST["del_" . $row['id']] : '';


				//when submit !!
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
						if (is_file($row['folder'] . "/thumbs/" . $row['name'] ))
						{
							@unlink ($row['folder'] . "/thumbs/" . $row['name'] );
						}
							
					}
			}
		}
		
		$SQL->freeresult($result);
	}
	else  #num_rows
	{
		$no_results = true;
	}
		
	//some vars
	$total_pages= $Pager->getTotalPages(); 
	$page_nums 	= $Pager->print_nums($page_action); 
	
		
	//after submit 
	if (isset($_POST['submit']))
	{
		$text	= $lang['FILES_UPDATED'] . '<meta HTTP-EQUIV="REFRESH" content="0; url=' . $action . '">' ."\n";
		$stylee	= "admin_info";
	}
?>
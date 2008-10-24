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
		$action		= "admin.php?cp=files&amp;page=" . intval($_GET['page']);

		//posts search ..
		if (isset($_POST['search_file']))
		{
			$file_namee	= ($_POST['filename']!='') ? 'AND f.name LIKE \'%'.$SQL->escape($_POST['filename']).'%\' ' : ''; 
			$usernamee	= ($_POST['username']!='') ? 'AND u.name LIKE \'%'.$SQL->escape($_POST['username']).'%\' AND u.id=f.user' : ''; 
			$size_than	=   ' `size` '.(($_POST['than']==1) ? '>' : '<').intval($_POST['size']).' ';
			$ups_than	=  ($_POST['ups']!='') ? 'AND f.uploads '.(($_POST['uthan']==1) ? '>' : '<').intval($_POST['ups']).' ' : '';
			$rep_than	=  ($_POST['rep']!='') ? 'AND f.report '.(($_POST['rthan']==1) ? '>' : '<').intval($_POST['rep']).' ' : '';
			$lstd_than	=  ($_POST['lastdown']!='') ? 'AND f.last_down ='.(time()-(intval($_POST['lastdown']) * (24 * 60 * 60))).' ' : '';
			$exte		=  ($_POST['ext']!='') ? 'AND f.type LIKE \'%'.$SQL->escape($_POST['ext']).'%\' ' : '';
			$ipp		=  ($_POST['user_ip']!='') ? 'AND f.user_ip LIKE \'%'.$SQL->escape($_POST['user_ip']).'%\' ' : '';
		

			$query = array(
							'SELECT'	=> 'f.*',
							'FROM'		=> "{$dbprefix}files f , {$dbprefix}users u",
							'WHERE'		=> "$size_than $file_namee $ups_than $exte $rep_than $usernamee $lstd_than $exte $ipp",
							'ORDER BY'	=> 'id DESC'
						);				

		}
		elseif(isset($_GET['last_visit']))
		{
			$query = array(
							'SELECT'	=> '*',
							'FROM'		=> "{$dbprefix}files",
							'WHERE'		=> "time > '". intval($_GET['last_visit']) ."'",
							'ORDER BY'	=> 'id DESC'
						);
		}
		else
		{
			$query = array(
							'SELECT'	=> '*',
							'FROM'		=> "{$dbprefix}files",
							'ORDER BY'	=> 'id DESC'
						);
		}
		
		$result = $SQL->build($query);
		
		/////////////pager 
		$nums_rows = $SQL->num_rows($result);
		$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager = new SimplePager($perpage,$nums_rows,$currentPage);
		$start = $Pager->getStartRow();
		////////////////
		
		$no_results = false;
		

		if ($nums_rows > 0) {
		
		$query['LIMIT']	= "$start,$perpage";
		
		$result = $SQL->build($query);
		
		while($row=$SQL->fetch_array($result))
		{
		
			//make new lovely arrays !!
			$userfile =  $config['siteurl'].(($config['mod_writer'])? 'fileuser_'.$row['user'].'.html' : 'ucp.php?go=fileuser&amp;id='.$row['user'] );
			
			//get username
			$query_name = array(
								'SELECT'	=> 'name',
								'FROM'		=> "{$dbprefix}users",
								'WHERE'		=> 'id='.$row['user']
								);

			$user_name = $SQL->fetch_array($SQL->build($query_name));
			
			$arr[] = array('id' =>$row['id'],
						'name' =>"<a href=\"./".$row['folder']."/".$row['name']."\" target=\"blank\">".$row['name']."</a>",
						'size' =>Customfile_size($row['size']),
						'ups' =>$row['uploads'],
						'time' => date("d-m-Y H:a", $row['time']),
						'type' =>$row['type'],
						'folder' =>$row['folder'],
						'report' =>($row['report'] > 4)? "<span style=\"color:red\"><big>".$row['report']."</big></span>":$row['report'],
						'user' =>($row['user'] == '-1') ? $lang['GUST'] :  '<a href="'.$userfile.'" target="_blank">'. $user_name['name'] . '</a>',
						'ip' 	=> '<a href="http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=' . $row['user_ip'] . '&do_search=Search" target="_new">' . $row['user_ip'] . '</a>',
						);
			//
			$del[$row[id]] = ( isset($_POST["del_".$row['id']]) ) ? $_POST["del_".$row['id']] : "";


				//when submit !!
				if (isset($_POST['submit']))
				{
					if ($del[$row['id']])
					{
						$query_del = array(
										'DELETE'	=> "{$dbprefix}files",
										'WHERE'		=> "id='".intval($row['id'])."'"
										);
															
						if (!$SQL->build($query_del)) {die($lang['CANT_DELETE_SQL']);}	

						//delete from folder ..
						@unlink ($row['folder'] . "/" .$row['name']);
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
		$total_pages 	= $Pager->getTotalPages(); 
		$page_nums 		= $Pager->print_nums($config['siteurl'].'admin.php?cp=files'); 
		
	//after submit 
	if (isset($_POST['submit']))
	{
		$text	= $lang['FILES_UPDATED'] . '<meta HTTP-EQUIV="REFRESH" content="0; url=./admin.php?cp=files&amp;page=' . intval($_GET['page']). '">' ."\n";
		$stylee	= "admin_info";
	}
?>

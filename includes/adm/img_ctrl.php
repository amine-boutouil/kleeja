<?php
	//img_ctrl
	//part of admin extensions
	//conrtoll imgs
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit();
	}
	
	
		//for style ..
		$stylee		= "admin_img";
		$action 	= "admin.php?cp=img";

		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}files",
					'ORDER BY'	=> 'id DESC'
				);		
						
		if(isset($_GET['last_visit']))
		{
			$query['WHERE']	=	"time > '". intval($_GET['last_visit']) ."' AND type IN ('gif','jpg','png','bmp','jpeg','tif','tiff')";
		}
		else
		{
			$query['WHERE']	=	"type IN ('gif','jpg','png','bmp','jpeg','tif','tiff')";
		}
		
		$result = $SQL->build($query);
		
		/////////////pager 
		$perpag2e = 9;
		$nums_rows = $SQL->num_rows($result);
		$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager = new SimplePager($perpag2e,$nums_rows,$currentPage);
		$start = $Pager->getStartRow();
		////////////////
		
		$no_results = false;
		
		if ($nums_rows > 0) {
		
		$query['LIMIT']	=	"$start,$perpag2e";
		$result = $SQL->build($query);
		
		$tdnum = 0;
		//$all_tdnum = 0;
		while($row=$SQL->fetch_array($result))
		{
		
			//get username
			$query_name = array(
								'SELECT'	=> 'name',
								'FROM'		=> "`{$dbprefix}users`",
								'WHERE'		=> 'id='.$row['user']
								);

			$user_name = $SQL->fetch_array($SQL->build($query_name));
			
			//make new lovely arrays !!
			$arr[] = array(id =>$row['id'],
						tdnum=>($tdnum==0) ? "<tr>": "",
						tdnum2=>($tdnum==2) ? "</tr>" : "",
					//	tdnum3=>($all_tdnum >= $nums_rows) ? "</tr>" : "",
						name =>$row['name'],
						href =>$row['folder']."/".$row['name'],
						size =>$lang['FILESIZE']. ':' . Customfile_size($row['size']),
						ups => $lang['FILEUPS'] .' : '.$row['uploads'],
						time => $lang['FILEDATE']. ':' .date("d-m-Y H:a", $row['time']),
						user =>$lang['BY'] . ':' .(($row['user'] == '-1') ? $lang['GUST'] :  $user_name['name']),
						thumb_link => (is_file($row['folder'] . "/thumbs/" . $row['name'] )) ? $row['folder'] . "/thumbs/" . $row['name'] : $row['folder'] . "/" . $row['name'],
						);
			
			//fix ... 
			$tdnum = ($tdnum==2) ? 0 : $tdnum+1; 
			//$all_tdnum++;
			//
			$del[$row['id']] = ( isset($_POST["del_".$row['id']]) ) ? $_POST["del_".$row['id']] : "";

				//when submit  !! !!
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
						if (is_file($row['folder'] . "/thumbs/" . $row['name']))
						{
								@unlink ($row['folder'] . "/thumbs/" . $row['name'] );
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
		$page_nums 		= $Pager->print_nums($config['siteurl'].'admin.php?cp=img'); 
		
	//after submit 
	if(isset($_POST['submit']))
	{
		$text	= $lang['FILES_UPDATED'];
		$stylee	= "admin_info";
	}
?>
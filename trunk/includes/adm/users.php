<?php
	//users
	//part of admin extensions
	//conrtoll users
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	


	//for style ..
		$stylee 	= "admin_users";
		$action 	= "admin.php?cp=users&amp;page=". intval($_GET['page']);

		
		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}users",
					'ORDER BY'	=> 'id DESC'
					);
						
		$result = $SQL->build($query);
		
		//posts search ..
		if (isset($_POST['search_user']))
		{
			$usernamee	= ($_POST['username']!='') ? 'AND name  LIKE \'%'.$SQL->escape($_POST['username']).'%\' ' : ''; 
			$usermailee	= ($_POST['usermail']!='') ? 'AND mail  LIKE \'%'.$SQL->escape($_POST['usermail']).'%\' ' : ''; 

			$query['WHERE']	=	"name != '' $usernamee $usermailee";
			
		}
		
		$result = $SQL->build($query);
		
		/////////////pager 
		$nums_rows = $SQL->num_rows($result);
		$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager = new SimplePager($perpage,$nums_rows,$currentPage);
		$start = $Pager->getStartRow();

		$no_results = false;
		
		if ($nums_rows > 0)
		{
		
			$query['LIMIT']	=	"$start,$perpage";
			
			$result = $SQL->build($query);
			
			while($row=$SQL->fetch_array($result))
			{
		
				//make new lovely arrays !!
				$ids[$row['id']]	= $row['id'];
				$name[$row['id']] 	= (isset($_POST["nm_".$row['id']])) ? $_POST["nm_".$row['id']] : $row['name'];
				$mail[$row['id']]	= (isset($_POST["ml_".$row['id']])) ? $_POST["ml_".$row['id']] : $row['mail'];
				$pass[$row['id']]	= (isset($_POST["ps_".$row['id']])) ? $_POST["ps_".$row['id']] : "";
				$admin[$row['id']]	= $row['admin'];
				$del[$row['id']] 	= (isset($_POST["del_".$row['id']])) ? $_POST["del_".$row['id']] : "";

				$arr[] = array( id =>$ids[$row['id']],
								name =>$name[$row['id']],
								mail =>$mail[$row['id']],
								admin =>($admin[$row['id']])? "<input name=\"ad_{$row[id]}\" type=\"checkbox\" checked=\"checked\" />":"<input name=\"ad_{$row[id]}\" type=\"checkbox\"  />"
							);


			
					//when submit !!
				if (isset($_POST['submit']))
				{
					if ($del[$row['id']])
					{
						$query_del = array(
										'DELETE'	=> "{$dbprefix}users",
										'WHERE'		=> "id='" . intval($ids[$row['id']])."'"
											);
																
						if (!$SQL->build($query_del)) {die($lang['CANT_DELETE_SQL']);}	
					}

					//update
					$admin[$row['id']] = isset($_POST["ad_".$row['id']])  ? 1 : 0 ;
					$pass[$row['id']] = ($pass[$row['id']] != '') ? "password = '" . md5($SQL->escape($pass[$row['id']])) . "'," : "";
				
					$update_query = array(
										'UPDATE'	=> "{$dbprefix}users",
										'SET'		=> 	"name = '" . $SQL->escape($name[$row['id']]) . "',
														mail = '" . $SQL->escape($mail[$row['id']]) . "',
														".$pass[$row['id']]."
														admin = '" . intval($admin[$row['id']]) . "'",
										'WHERE'		=>	"id=".$row['id']
									);

					if (!$SQL->build($update_query)){ die($lang['CANT_UPDATE_SQL']);}	
				}
			}
			$SQL->freeresult($result);

	}
	else #num rows
	{ 
		$no_results = true;
	}
	
	$total_pages 	= $Pager->getTotalPages(); 
	$page_nums 		= $Pager->print_nums($config['siteurl'].'admin.php?cp=users'); 
		
	//after submit 
	if (isset($_POST['submit']))
	{
			$text	= $lang['USERS_UPDATED'] . '<meta HTTP-EQUIV="REFRESH" content="0; url=./admin.php?cp=users&amp;page=' . intval($_GET['page']). '">' ."\n";
			$stylee	= "admin_info";
	}
?>
<?php
	//users
	//part of admin extensions
	//conrtoll users
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	


		//for style ..
		$stylee 	= "admin_users";
		$action 	= "admin.php?cp=users&amp;page=" . (isset($GET['page'])  ? intval($GET['page']) : 1);

		
		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}users",
					'ORDER BY'	=> 'id DESC'
					);
						
		if (isset($_POST['newuser']))
		{
			($hook = kleeja_run_hook('register_submit')) ? eval($hook) : null; //run hook
						
						if (trim($_POST['lname'])=='' || trim($_POST['lpass'])=='' || trim($_POST['lmail'])=='')
						{
							$ERRORS[] = $lang['EMPTY_FIELDS'];
						}	
						else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['lmail'])))
						{
							$ERRORS[] = $lang['WRONG_EMAIL'];
						}
						else if (strlen(trim($_POST['lname'])) < 4 || strlen(trim($_POST['lname'])) > 30)
						{
							$ERRORS[] = $lang['WRONG_NAME'];
						}
						else if ($SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE name='" . trim($SQL->escape($_POST["lname"])) . "'")) !=0 )
						{
							$ERRORS[] = $lang['EXIST_NAME'];
						}
						else if ($SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE mail='" . trim($SQL->escape($_POST["lmail"])) . "'")) !=0 )
						{
							$ERRORS[] = $lang['EXIST_EMAIL'];
						}
						
						//no errors, lets do process
						if(empty($ERRORS))	 
						{
							$name			= (string) $SQL->escape(trim($_POST['lname']));
							$pass			= (string) md5($SQL->escape(trim($_POST['lpass'])));
							$mail			= (string) trim($_POST['lmail']);
							
							$insert_query	= array('INSERT'	=> 'name ,password ,mail,admin, session_id',
													'INTO'		=> "{$dbprefix}users",
													'VALUES'	=> "'$name', '$pass', '$mail','0',''"
												);
							if ($SQL->build($insert_query))
							{
								$last_user_id = $SQL->insert_id();

								//update number of stats
								$update_query	= array('UPDATE'	=> "{$dbprefix}stats",
														'SET'		=> "users=users+1,lastuser='$name'",
													);
								$SQL->build($update_query);
							}
						}
						else
						{
							$errs	=	'';
							foreach($ERRORS as $r)
							{
								$errs .= '- ' . $r . '. <br />';
							}
							
							die($errs);
						}
		}
		else {
		//posts search ..
		if (isset($_POST['search_user']))
		{
			$usernamee	= ($_POST['username']!='') ? 'AND name  LIKE \'%'.$SQL->escape($_POST['username']).'%\' ' : ''; 
			$usermailee	= ($_POST['usermail']!='') ? 'AND mail  LIKE \'%'.$SQL->escape($_POST['usermail']).'%\' ' : ''; 

			$query['WHERE']	=	"name != '' $usernamee $usermailee";
		}
		
		$result = $SQL->build($query);
		
		//pager 
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
				$name[$row['id']] 	= (isset($_POST["nm_" . $row['id']])) ? $_POST["nm_" . $row['id']] : $row['name'];
				$mail[$row['id']]	= (isset($_POST["ml_" . $row['id']])) ? $_POST["ml_" . $row['id']] : $row['mail'];
				$pass[$row['id']]	= (isset($_POST["ps_" . $row['id']])) ? $_POST["ps_" . $row['id']] : "";
				$admin[$row['id']]	= $row['admin'];
				$del[$row['id']] 	= (isset($_POST["del_" . $row['id']])) ? $_POST["del_" . $row['id']] : "";

				$arr[] = array( 'id'	=> $ids[$row['id']],
								'name'	=> $name[$row['id']],
								'mail'	=> $mail[$row['id']],
								'admin'	=> !empty($admin[$row['id']]) ? '<input name="ad_' . $row['id'] . '" type="checkbox" checked="checked" />' : '<input name="ad_' . $row['id'] . '" type="checkbox" />'
							);

				//when submit !!
				if (isset($_POST['submit']))
				{
					if ($del[$row['id']])
					{
						//delete  user
						$query_del = array(
										'DELETE'	=> "{$dbprefix}users",
										'WHERE'		=> "id='" . intval($ids[$row['id']])."'"
											);
											
						$SQL->build($query_del);
						
						//update number of stats
						$update_query	= array('UPDATE'	=> "{$dbprefix}stats",
												'SET'		=> 'users=users-1',
										);
							
						$SQL->build($update_query);
																
						
					}

					//update
					$admin[$row['id']] = isset($_POST['ad_' . $row['id']])  ? 1 : 0 ;
					$pass[$row['id']] = ($pass[$row['id']] != '') ? "password = '" . md5($SQL->escape($pass[$row['id']])) . "'," : "";
				
					$update_query = array(
										'UPDATE'	=> "{$dbprefix}users",
										'SET'		=> 	"name = '" . $SQL->escape($name[$row['id']]) . "',
														mail = '" . $SQL->escape($mail[$row['id']]) . "',
														" . $pass[$row['id']] . "
														admin = '" . intval($admin[$row['id']]) . "'",
										'WHERE'		=>	"id=" . $row['id']
									);

					$SQL->build($update_query);
				}
			}
			$SQL->freeresult($result);

	}
	else #num rows
	{ 
		$no_results = true;
	}
	
	$total_pages 	= $Pager->getTotalPages(); 
	$page_nums 		= $Pager->print_nums($config['siteurl'] . 'admin.php?cp=users'); 
	
	//if not noraml user system 
	$user_not_normal =$config['user_system'] != 1 ?  true : false;
	}
	//after submit 
	if (isset($_POST['submit']) || isset($_POST['newuser']))
	{
			$text	= $lang['USERS_UPDATED'] . '<meta HTTP-EQUIV="REFRESH" content="0; url=./admin.php?cp=users&amp;page=' . (isset($GET['page'])  ? intval($GET['page']) : 1) . '">' . "\n";
			$stylee	= "admin_info";
	}
?>

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
		$action 	= "admin.php?cp=users&amp;page=" . (isset($_GET['page'])  ? intval($_GET['page']) : 1) . (isset($_GET['search']) ? '&search=' . $_GET['search'] : '');
		$is_search	= false;
		$isn_search	= true;
		
		$query = array(
					'SELECT'	=> 'COUNT(id) AS total_users',
					'FROM'		=> "{$dbprefix}users",
					'ORDER BY'	=> 'id DESC'
					);
					
		//new feature delete all user files [only one user]			
		if(isset($_GET['deleteuserfile']) && $SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE id='" . intval($_GET['deleteuserfile']) . "'")) != 0)
		{
			$query = array(
				'SELECT'	=> 'size,name,folder',
				'FROM'		=> "{$dbprefix}files",
				'WHERE'		=>	'user=' . intval($_GET['deleteuserfile']),
				);
			
			$result = $SQL->build($query);
			$sizes = false;
			$num = 0;
			while($row=$SQL->fetch_array($result))
			{
				//delete from folder ..
				@kleeja_unlink ($row['folder'] . "/" . $row['name']);
						
				//delete thumb
				if (is_file($row['folder'] . "/thumbs/" . $row['name'] ))
				{
					@kleeja_unlink ($row['folder'] . "/thumbs/" . $row['name'] );
				}
				
				$num++;		
				$sizes += $row['size'];
			}
			
			if($sizes)
			{
				//update number of stats
				$update_query	= array('UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "sizes=sizes-$sizes,files=files-$num",
									);
									
				$SQL->build($update_query);
			
				//delete all files in just one query
				$d_query	= array('DELETE'	=> "{$dbprefix}files",
								'WHERE'		=> "user='".intval($_GET['deleteuserfile'])."'",
									);
									
				$SQL->build($d_query);
				$SQL->freeresult($result);
				
				kleeja_admin_info($lang['ADMIN_DELETE_FILE_OK']);
			}
			else
			{
				$errs = $lang['ADMIN_DELETE_FILE_ERR'];
				kleeja_admin_err($errs);
			}
			
		}				
		else if (isset($_POST['newuser']))
		{						
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
			else if ($SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE clean_name='" . trim($SQL->escape($usrcp->cleanusername($_POST["lname"]))) . "'")) !=0 )
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
				$user_salt		= (string) substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
				$pass			= (string) $usrcp->kleeja_hash_password($SQL->escape(trim($_POST['lpass'])) . $user_salt);
				$mail			= (string) trim($_POST['lmail']);
				$clean_name		= $usrcp->cleanusername($name);
										
				$insert_query	= array('INSERT'	=> 'name ,password, password_salt ,mail,admin, session_id, clean_name',
													'INTO'		=> "{$dbprefix}users",
													'VALUES'	=> "'$name', '$pass', '$user_salt', '$mail','0','','$clean_name'"
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
				
				kleeja_admin_err($errs);
							
			}
		}
		else
		{
		//posts search ..
			if (isset($_POST['search_user']))
			{
				header('Location: admin.php?cp=users&search=' . base64_encode(serialize($_POST)));
				$SQL->close();
				exit;
			}
			else if(isset($_GET['search']))
			{
				$search = base64_decode($_GET['search']);
				$search	= unserialize($search);
				$usernamee	= ($search['username']!='') ? 'AND name  LIKE \'%'.$SQL->escape($search['username']).'%\' ' : ''; 
				$usermailee	= ($search['usermail']!='') ? 'AND mail  LIKE \'%'.$SQL->escape($search['usermail']).'%\' ' : ''; 
				$is_search	= true;
				$isn_search	= false;
				$query['WHERE']	=	"name != '' $usernamee $usermailee";
			}
		
			$result = $SQL->build($query);
	
			$nums_rows = 0;
			$n_fetch = $SQL->fetch_array($result);
			$nums_rows = $n_fetch['total_users'];
		
			//pager 
			$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
			$Pager = new SimplePager($perpage,$nums_rows,$currentPage);
			$start = $Pager->getStartRow();

			$no_results = false;
		
			if ($nums_rows > 0)
			{
				$query['SELECT'] =	'*';
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
						$user_salt		   = substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
						$pass[$row['id']]  = ($pass[$row['id']] != '') ? "password = '" . $usrcp->kleeja_hash_password($SQL->escape($pass[$row['id']]) . $user_salt) . "',password_salt='" . $user_salt . "'," : "";
				
						$update_query = array(
										'UPDATE'	=> "{$dbprefix}users",
										'SET'		=> 	"name = '" . $SQL->escape($name[$row['id']]) . "',
														mail = '" . $SQL->escape($mail[$row['id']]) . "',
														" . $pass[$row['id']] . "
														admin = '" . intval($admin[$row['id']]) . "',
														clean_name = '" . $SQL->escape($usrcp->cleanusername($name[$row['id']])) . "'",
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
	$page_nums 		= $Pager->print_nums($config['siteurl'] . 'admin.php?cp=users' . ((isset($_GET['search'])) ? '&search=' . $_GET['search'] : '')); 
	
	//if not noraml user system 
	$user_not_normal = $config['user_system'] != 1 ?  true : false;
	}
	//after submit 
	if (isset($_POST['submit']) || isset($_POST['newuser']))
	{
			$text	= $lang['USERS_UPDATED'] . '<meta HTTP-EQUIV="REFRESH" content="0; url=./admin.php?cp=users&amp;page=' . (isset($_GET['page'])  ? intval($_GET['page']) : 1) . (isset($_GET['search']) ? '&search=' . $_GET['search'] : '') . '">' . "\n";
			$stylee	= "admin_info";
	}
?>

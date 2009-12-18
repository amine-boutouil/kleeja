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


//for style ..
$stylee 	= "admin_users";
$action 	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page'])  ? intval($_GET['page']) : 1);
$action 	.= (isset($_GET['search']) ? '&search=' . $SQL->escape($_GET['search']) : '') . (isset($_GET['admin']) && $_GET['admin'] == '1' ? '&admin=1' : '');

$is_search	= $affected = $is_asearch = false;
$isn_search	= true;
$H_FORM_KEYS	= kleeja_add_form_key('adm_users');
$H_FORM_KEYS2	= kleeja_add_form_key('adm_users_newuser');

//
// Check form key
//
if (isset($_POST['submit']))
{
	if(!kleeja_check_form_key('adm_users'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}
if (isset($_POST['newuser']))
{
	if(!kleeja_check_form_key('adm_users_newuser'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}
if (isset($_POST['search_user']))
{
	if(!kleeja_check_form_key('adm_users_search'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, basename(ADMIN_PATH) . '?cp=h_search', 1);
	}
}

//
//delete all user files [only one user]			
//
if(isset($_GET['deleteuserfile']) && $SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE id=" . intval($_GET['deleteuserfile']))) != 0)
{
	$query = array(
					'SELECT'	=> 'size, name, folder',
					'FROM'		=> "{$dbprefix}files",
					'WHERE'		=>	'user=' . intval($_GET['deleteuserfile']),
				);

	$result = $SQL->build($query);
	$sizes = false;
	$num = 0;
	while($row=$SQL->fetch_array($result))
	{
		//delete from folder ..
		@kleeja_unlink (PATH . $row['folder'] . "/" . $row['name']);
		//delete thumb
		if (file_exists(PATH . $row['folder'] . "/thumbs/" . $row['name']))
		{
			@kleeja_unlink (PATH . $row['folder'] . "/thumbs/" . $row['name']);
		}

		$num++;		
		$sizes += $row['size'];
	}

	$SQL->freeresult($result);

	if($num == 0)
	{
		kleeja_admin_err($lang['ADMIN_DELETE_NO_FILE']);
	}
	else
	{
		//update number of stats
		$update_query	= array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> "sizes=sizes-$sizes, files=files-$num",
							);

		$SQL->build($update_query);
		if($SQL->affected())
		{
			delete_cache('data_stats');
		}

		//delete all files in just one query
		$d_query	= array(
							'DELETE'	=> "{$dbprefix}files",
							'WHERE'		=> "user=" . intval($_GET['deleteuserfile']),
							);

		$SQL->build($d_query);

		kleeja_admin_info($lang['ADMIN_DELETE_FILE_OK']);
	}
}
//
//add new user
//
else if (isset($_POST['newuser']))
{
	if (trim($_POST['lname']) == '' || trim($_POST['lpass']) == '' || trim($_POST['lmail']) == '')
	{						
		$ERRORS[] = $lang['EMPTY_FIELDS'];
	}
	else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", trim(strtolower($_POST['lmail']))))
	{
		$ERRORS[] = $lang['WRONG_EMAIL'];
	}
	else if (strlen(trim($_POST['lname'])) < 2 || strlen(trim($_POST['lname'])) > 100)
	{
		$ERRORS[] = str_replace('4', '2', $lang['WRONG_NAME']);
	}
	else if ($SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE clean_name='" . trim($SQL->escape($usrcp->cleanusername($_POST["lname"]))) . "'")) != 0)
	{
		$ERRORS[] = $lang['EXIST_NAME'];
	}
	else if ($SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE mail='" . trim($SQL->escape(strtolower($_POST["lmail"]))) . "'")) != 0)
	{
		$ERRORS[] = $lang['EXIST_EMAIL'];
	}

	//no errors, lets do process
	if(empty($ERRORS))	 
	{
		$name			= (string) $SQL->escape(trim($_POST['lname']));
		$user_salt		= (string) substr(kleeja_base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
		$pass			= (string) $usrcp->kleeja_hash_password($SQL->escape(trim($_POST['lpass'])) . $user_salt);
		$mail			= (string) trim(strtolower($_POST['lmail']));
		$clean_name		= (string) $usrcp->cleanusername($name);

		$insert_query	= array(
								'INSERT'	=> 'name ,password, password_salt ,mail,admin, session_id, clean_name',
								'INTO'		=> "{$dbprefix}users",
								'VALUES'	=> "'$name', '$pass', '$user_salt', '$mail','0','','$clean_name'"
						);

		if ($SQL->build($insert_query))
		{
			$last_user_id = $SQL->insert_id();

			//update number of stats
			$update_query	= array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "users=users+1, lastuser='$name'",
							);

			$SQL->build($update_query);
			if($SQL->affected())
			{
				delete_cache('data_stats');
			}
		}
		
		//return to users page
		redirect(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));
	}
	else
	{
		$errs =	'';
		foreach($ERRORS as $r)
		{
			$errs .= '- ' . $r . '. <br />';
		}

		kleeja_admin_err($errs);			
	}
}


//
//begin of default users page 
//

$query	= array(
				'SELECT'	=> 'COUNT(id) AS total_users',
				'FROM'		=> "{$dbprefix}users",
				'ORDER BY'	=> 'id ASC'
		);

//posts search ..
if (isset($_POST['search_user']))
{
	 redirect(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&search=' . kleeja_base64_encode(serialize($_POST)));
	$SQL->close();
	exit;
}
else if(isset($_GET['search']))
{
	$search = kleeja_base64_decode($_GET['search']);
	$search	= unserialize($search);
	$usernamee	= $search['username'] != '' ? 'AND name  LIKE \'%' . $SQL->escape($search['username']) . '%\' ' : ''; 
	$usermailee	= $search['usermail'] != '' ? 'AND mail  LIKE \'%' . $SQL->escape($search['usermail']) . '%\' ' : ''; 
	$is_search	= true;
	$isn_search	= false;
	$query['WHERE']	=	"name != '' $usernamee $usermailee";
}
else if(isset($_GET['admin']))
{
	$admin	= (int) $_GET['admin'] == 1 ? "AND admin = 1 " : ''; 
	$is_search	= true;
	$isn_search	= false;
	$is_asearch = true;
	$query['WHERE']	= "name != '' $admin";
}

$result = $SQL->build($query);

$nums_rows = 0;
$n_fetch = $SQL->fetch_array($result);
$nums_rows = $n_fetch['total_users'];

//pager 
$currentPage	= isset($_GET['page']) ? intval($_GET['page']) : 1;
$Pager			= new SimplePager($perpage, $nums_rows, $currentPage);
$start			= $Pager->getStartRow();

$no_results = false;

if ($nums_rows > 0)
{
	$query['SELECT'] =	'*';
	$query['LIMIT']	=	"$start, $perpage";

	$result = $SQL->build($query);

	while($row=$SQL->fetch_array($result))
	{
		//make new lovely arrays !!
		$ids[$row['id']]	= $row['id'];
		$name[$row['id']] 	= isset($_POST['nm_' . $row['id']]) ? $_POST['nm_' . $row['id']] : $row['name'];
		$mail[$row['id']]	= isset($_POST['ml_' . $row['id']]) ? $_POST['ml_' . $row['id']] : $row['mail'];
		$pass[$row['id']]	= isset($_POST['ps_' . $row['id']]) ? $_POST['ps_' . $row['id']] : '';
		$admin[$row['id']]	= $row['admin'];
		$del[$row['id']] 	= isset($_POST['del_' . $row['id']]) ? $_POST['del_' . $row['id']] : '';

		$userfile =  $config['siteurl'] . ($config['mod_writer'] ? 'fileuser-' . $row['id'] . '.html' : 'ucp.php?go=fileuser&amp;id=' . $row['id']);

		$arr[]	= array(
						'id'	=> $ids[$row['id']],
						'name'	=> $name[$row['id']],
						'mail'	=> $mail[$row['id']],
						'userfile_link' => $userfile,
						'delusrfile_link' => basename(ADMIN_PATH) .'?cp=' . basename(__file__, '.php') . '&deleteuserfile='. $row['id'],
						'admin'	=> !empty($admin[$row['id']]) ? '<input name="ad_' . $row['id'] . '" type="checkbox" checked="checked" />' : '<input name="ad_' . $row['id'] . '" type="checkbox" />'
				);

		//when submit !!
		if (isset($_POST['submit']))
		{
			if ($del[$row['id']])
			{
				//delete user
				$query_del	= array(
									'DELETE'	=> "{$dbprefix}users",
									'WHERE'		=> 'id=' . intval($ids[$row['id']])
								);

				$SQL->build($query_del);

				//update number of stats
				$update_query	= array(
										'UPDATE'	=> "{$dbprefix}stats",
										'SET'		=> 'users=users-1',
									);

				$SQL->build($update_query);
						
				if($SQL->affected())
				{
					$affected = true;
					delete_cache('data_stats');
				}
			}

			//update
			$admin[$row['id']] = isset($_POST['ad_' . $row['id']])  ? 1 : 0 ;
			$user_salt		   = substr(kleeja_base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
			$pass[$row['id']]  = ($pass[$row['id']] != '') ? "password = '" . $usrcp->kleeja_hash_password($SQL->escape($pass[$row['id']]) . $user_salt) . "', password_salt='" . $user_salt . "'," : '';

			$update_query	= array(
									'UPDATE'	=> "{$dbprefix}users",
									'SET'		=> 	"name = '" . $SQL->escape($name[$row['id']]) . "',
													mail = '" . $SQL->escape($mail[$row['id']]) . "',
													" . $pass[$row['id']] . "
													admin = " . intval($admin[$row['id']]) . ",
													clean_name = '" . $SQL->escape($usrcp->cleanusername($name[$row['id']])) . "'",
									'WHERE'		=>	'id=' . $row['id']
							);

			$SQL->build($update_query);

			if($SQL->affected())
			{
				$affected = true;
			}
		}
	}

	$SQL->freeresult($result);
}
else #num rows
{ 
	$no_results = true;
}
		
//pages
$total_pages 	= $Pager->getTotalPages(); 
$page_nums 		= $Pager->print_nums(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . (isset($_GET['search']) ? '&search=' . strip_tags($_GET['search']) : '')); 

//if not noraml user system 
$user_not_normal = (int) $config['user_system'] != 1 ?  true : false;

//after submit 
if (isset($_POST['submit']) || isset($_POST['newuser']))
{
	$text	= ($affected ? $lang['USERS_UPDATED'] : $lang['NO_UP_CHANGE_S']) . '<meta HTTP-EQUIV="REFRESH" content="0; url=' . basename(ADMIN_PATH) . '?cp=';
	$text	.= basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page'])  ? intval($_GET['page']) : 1) . (isset($_GET['search']) ? '&search=' . strip_tags($_GET['search']) : '');
	$text	.= ((isset($_GET['admin']) && $_GET['admin'] == '1') ? '&admin=1' : '') . '">' . "\n";
	$stylee	= "admin_info";
}

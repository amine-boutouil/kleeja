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
$stylee			= "admin_users";
$current_smt	= isset($_GET['smt']) ? (preg_match('![a-z0-9_]!i', trim($_GET['smt'])) ? trim($_GET['smt']) : 'general') : 'general';
$action			= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . (isset($_GET['page'])  ? '&amp;page=' . intval($_GET['page']) : '');
$action			.= (isset($_GET['search']) ? '&amp;search=' . $SQL->escape($_GET['search']) : '');
$action			.= (isset($_GET['qg']) ? '&amp;qg=' . intval($_GET['qg']) : '') . '&amp;smt=' . $current_smt;
$action_all		= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php')  . '&amp;smt=' . $current_smt . (isset($_GET['page']) ? '&amp;page=' . intval($_GET['page']) : '');

$is_search	= $affected = $is_asearch = false;
$isn_search	= true;
$GET_FORM_KEY	= kleeja_add_form_key_get('adm_users');
$H_FORM_KEYS	= kleeja_add_form_key('adm_users');
$H_FORM_KEYS2	= kleeja_add_form_key('adm_users_newuser');
$H_FORM_KEYS3	= kleeja_add_form_key('adm_users_newgroup');
$H_FORM_KEYS4	= kleeja_add_form_key('adm_users_delgroup');
$H_FORM_KEYS5	= kleeja_add_form_key('adm_users_editacl');
$H_FORM_KEYS6	= kleeja_add_form_key('adm_users_editdata');

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
if (isset($_POST['delgroup']))
{
	if(!kleeja_check_form_key('adm_users_delgroup'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}
if (isset($_POST['newgroup']))
{
	if(!kleeja_check_form_key('adm_users_newgroup'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}
if (isset($_POST['editacl']))
{
	if(!kleeja_check_form_key('adm_users_editacl'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}
if (isset($_POST['editdata']))
{
	if(!kleeja_check_form_key('adm_users_editdata'))
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
if(isset($_GET['deleteuserfile'])) 
{
	//check _GET Csrf token
	if(!kleeja_check_form_key_get('adm_users'))
	{
		kleeja_admin_err($lang['INVALID_GET_KEY'], true, $lang['ERROR'], true, $action_all, 2);
	}

	//is exists ?
	if(!$SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}users` WHERE id=" . intval($_GET['deleteuserfile']))))
	{
		redirect($action_all);
	}

	$query = array(
					'SELECT'	=> 'size, name, folder',
					'FROM'		=> "{$dbprefix}files",
					'WHERE'		=> 'user=' . intval($_GET['deleteuserfile']),
				);

	$result = $SQL->build($query);

	$sizes = $num = 0;
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

		kleeja_admin_info($lang['ADMIN_DELETE_FILE_OK'], true, '', true, $action_all, 3);
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
	
	
		//User added ..
		kleeja_admin_info($lang['USERS_UPDATED'], true, '', true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'), 3);
	}
	else
	{
		$errs =	'';
		foreach($ERRORS as $r)
		{
			$errs .= '- ' . $r . '. <br />';
		}

		kleeja_admin_err($errs, true, '', true, $action_all, 3);
	}
}

//
//add new group
//
if(isset($_POST['newgroup']))
{
	if (trim($_POST['gname']) == '' || trim($_POST['gname']) == '' || trim($_POST['gname']) == '')
	{						
		$ERRORS[] = $lang['EMPTY_FIELDS'];
	}
	else if (strlen(trim($_POST['gname'])) < 2 || strlen(trim($_POST['gname'])) > 100)
	{
		$ERRORS[] = str_replace('4', '1', $lang['WRONG_NAME']);
	}
	else if ($SQL->num_rows($SQL->query("SELECT * FROM `{$dbprefix}groups` WHERE group_name='" . trim($SQL->escape($_POST["gname"])) . "'")) != 0)
	{
		$ERRORS[] = $lang['EXIST_NAME'];
	}
	elseif (in_array(trim($_POST['gname']), array($lang['ADMINS'], $lang['GUESTS'], $lang['USERS'])))
	{						
		$ERRORS[] = $lang['TAKEN_NAMES'];
	}
	
	//no errors, lets do process
	if(empty($ERRORS))	 
	{
		//
	}
	else
	{
		$errs =	'';
		foreach($ERRORS as $r)
		{
			$errs .= '- ' . $r . '. <br />';
		}

		kleeja_admin_err($errs, true, '', true, $action_all, 3);
	}
}

//
//delete group
//
if(isset($_POST['delgroup']))
{
	

}

//
//begin of default users page 
//
$query = array();
$show_results = false;
switch($current_smt):

case 'general':

	$query = array(
					'SELECT'	=> 'COUNT(group_id) AS total_groups',
					'FROM'		=> "{$dbprefix}groups",
					'ORDER BY'	=> 'group_id ASC'
			);

	$result = $SQL->build($query);

	$nums_rows = 0;
	$n_fetch = $SQL->fetch_array($result);
	$nums_rows = $n_fetch['total_groups'];
	$no_results = false;
	$e_groups	= $c_groups = array();
	$l_groups	= array();


	if ($nums_rows > 0)
	{
		$query['SELECT'] =	'group_id, group_name, group_is_default, group_is_essential';

		$result = $SQL->build($query);

		while($row=$SQL->fetch_array($result))
		{
			$r = array(
						'id'	=> $row['group_id'],
						'name'	=> preg_replace('!{lang.([A-Z0-9]+)}!e', '$lang[\'\\1\']', $row['group_name']),
						'is_default'	=> (int) $row['group_is_default'] ? true : false
				);
			
			if((int) $row['group_is_essential'] == 1)
			{
				$e_groups[] = $r;
			}
			else
			{
				$c_groups[] = $r;
			}
		}
	}
	
	$SQL->freeresult($result);

break;

case 'group_acl':
	$req_group	= isset($_GET['qg']) ? intval($_GET['qg']) : 0;
	$group_name	= preg_replace('!{lang.([A-Z0-9]+)}!e', '$lang[\'\\1\']', $d_groups[$req_group]['data']['group_name']);

	$query = array(
					'SELECT'	=> 'acl_name, acl_can',
					'FROM'		=> "{$dbprefix}groups_acl",
					'WHERE'		=> 'group_id=' . $req_group,
					'ORDER BY'	=> 'acl_name ASC'
			);

	$result = $SQL->build($query);
	
	$acls = array();
	while($row=$SQL->fetch_array($result))
	{
		$acls[] = array(
						'acl_title'	=> $lang['ACLS_' .  strtoupper($row['acl_name'])],
						'acl_name'	=> $row['acl_name'],
						'acl_can'	=> (int) $row['acl_can']
			);
	}
	$SQL->freeresult($result);

break;

case 'group_data':
	$req_group = isset($_GET['qg']) ? intval($_GET['qg']) : 0;
	if(!$req_group)
	{
		kleeja_admin_err('ERROR-NO-ID', true, '', true,  basename(ADMIN_PATH) . '?cp=g_users');
	}

	$group_name	= preg_replace('!{lang.([A-Z0-9]+)}!e', '$lang[\'\\1\']', $d_groups[$req_group]['data']['group_name']);
	$gdata		= $d_groups[$req_group]['data'];

	$query = array(
					'SELECT'	=> 'c.name, c.option',
					'FROM'		=> "{$dbprefix}config c",
					'WHERE'		=> "c.type='groups'",
					'ORDER BY'	=> 'c.display_order ASC'
			);

	$result = $SQL->build($query);

	$data = array();
	$cdata= $d_groups[$req_group]['configs'];
	$STAMP_IMG_URL = file_exists(PATH . 'images/watermark.gif') ? PATH . 'images/watermark.gif' : PATH . 'images/watermark.png';

	while($row=$SQL->fetch_array($result))
	{	
		#submit, why here ? dont ask me just accept it as it.
		if(isset($_POST['editdata']))
		{
			($hook = kleeja_run_hook('after_submit_adm_users_groupdata')) ? eval($hook) : null; //run hook

			$new[$row['name']] = isset($_POST[$row['name']]) ? $_POST[$row['name']] : $row['value'];

			$update_query = array(
									'UPDATE'	=> "{$dbprefix}groups_data",
									'SET'		=> "`value`='" . $SQL->escape($new[$row['name']]) . "'",
									'WHERE'		=> "`name`='" . $row['name'] . "' AND `group_id`=". $req_group
								);

			$SQL->build($update_query);
			continue;
		}
	
		if($row['name'] == 'language')
		{
			//get languages
			if ($dh = @opendir(PATH . 'lang'))
			{
				while (($file = readdir($dh)) !== false)
				{
					if(strpos($file, '.') === false && $file != '..' && $file != '.')
					{
						$lngfiles .= '<option ' . ($con['language'] == $file ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
					}
				}
				@closedir($dh);
			}
		}
		$data[] = array(
						'option'	=> '<div class="section">' . "\n" .  
										'<h3><label for="' . $row['name'] . '">' . (!empty($lang[strtoupper($row['name'])]) ? $lang[strtoupper($row['name'])] : $olang[strtoupper($row['name'])]) . '</label></h3>' . "\n" .
										'<div class="box">' . (empty($row['option']) ? '' : $tpl->admindisplayoption(preg_replace('!{con.[a-z0-9_]+}!', '{cdata.' . $row['name'] . '}', $row['option']))) . '</div>' . "\n" .
										'</div>' . "\n" . '<div class="clear"></div>',
			);
	}
	$SQL->freeresult($result);

	#submit
	if(isset($_POST['editdata']))
	{
		#delete cache ..
		delete_cache('data_groups');
		kleeja_admin_info($lang['CONFIGS_UPDATED'], true, '', true,  basename(ADMIN_PATH) . '?cp=g_users');
	}

break;

case 'show_su':
	if(isset($_POST['search_user']))
	{
		$search = $_POST;
		$_GET['search'] = kleeja_base64_encode(serialize($_POST));
	}
	else
	{
		$search	= unserialize(kleeja_base64_decode($_GET['search']));
	}

	$usernamee	= $search['username'] != '' ? 'AND name  LIKE \'%' . $SQL->escape($search['username']) . '%\' ' : ''; 
	$usermailee	= $search['usermail'] != '' ? 'AND mail  LIKE \'%' . $SQL->escape($search['usermail']) . '%\' ' : ''; 
	$is_search	= true;
	$isn_search	= false;
	$query['WHERE']	=	"name != '' $usernamee $usermailee";
	
case 'show_group':
	$is_search	= true;
	$isn_search	= false;
	$is_asearch = true;
	$req_group	= isset($_GET['qg']) ? intval($_GET['qg']) : 0;
	$group_name	= preg_replace('!{lang.([A-Z0-9]+)}!e', '$lang[\'\\1\']', $d_groups[$req_group]['data']['group_name']);

	$query['WHERE']	= "name != '' AND group_id =  " . $req_group;


case 'users':

	$query['SELECT']	= 'COUNT(id) AS total_users';
	$query['FROM']		= "{$dbprefix}users";
	$query['ORDER BY']	= 'id ASC';

	$result = $SQL->build($query);

	$nums_rows = 0;
	$n_fetch = $SQL->fetch_array($result);
	$nums_rows = $n_fetch['total_users'];

	//pagination
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
						'delusrfile_link' => basename(ADMIN_PATH) .'?cp=' . basename(__file__, '.php') . '&amp;deleteuserfile='. $row['id'] . (isset($_GET['page']) ? '&amp;page=' . intval($_GET['page']) : ''),
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
	$page_nums 		= $Pager->print_nums(
								basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . (isset($_GET['search']) ? '&search=' . strip_tags($_GET['search']) : '') 
								. (isset($_GET['qg']) ? '&qg=' . intval($_GET['qg']) : '') . (isset($_GET['smt']) ? '&smt=' . $current_smt : ''),
								'onclick="javascript:get_kleeja_link($(this).attr(\'href\'), \'#content\'); return false;"' 
							); 

	
	$show_results = true;
break;
endswitch;

//if not noraml user system 
$user_not_normal = (int) $config['user_system'] != 1 ?  true : false;

//after submit 
if (isset($_POST['submit']) || isset($_POST['newuser']))
{
	$g_link = basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page'])  ? intval($_GET['page']) : 1) . 
				(isset($_GET['search']) ? '&amp;search=' . strip_tags($_GET['search']) : '') . '&amp;smt=' . $current_smt;

	$text	= ($affected ? $lang['USERS_UPDATED'] : $lang['NO_UP_CHANGE_S']) .
				'<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . str_replace('&amp;', '&', $g_link) . '\');", 2000);</script>' . "\n";
	$stylee	= "admin_info";
}



//secondary menu
$go_menu = array(
				'general' => array('name'=>$lang['R_GROUPS'], 'link'=> basename(ADMIN_PATH) . '?cp=g_users&amp;smt=general', 'goto'=>'general', 'current'=> $current_smt == 'general'),
				#'users' => array('name'=>$lang['R_USERS'], 'link'=> basename(ADMIN_PATH) . '?cp=g_users&amp;smt=users', 'goto'=>'users', 'current'=> $current_smt == 'users'),
				'show_su' => array('name'=>$lang['SEARCH_USERS'], 'link'=> basename(ADMIN_PATH) . '?cp=h_search&amp;smt=users', 'goto'=>'show_su', 'current'=> $current_smt == 'show_su'),
				'new_u' => array('name'=>$lang['NEW_USER'], 'link'=> basename(ADMIN_PATH) . '?cp=g_users&amp;smt=new_u', 'goto'=>'new_u', 'current'=> $current_smt == 'new_u'),
	);

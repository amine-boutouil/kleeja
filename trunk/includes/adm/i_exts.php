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
$stylee = "admin_exts";
$action 		= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : 1);
$action_new_ext = basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;add_new_ext=1';
$H_FORM_KEYS	= kleeja_add_form_key('adm_exts');
$H_FORM_KEYS2	= kleeja_add_form_key('adm_exts_new_ext');

//
// Check form key
//
if (isset($_POST['submit']))
{
	if(!kleeja_check_form_key('adm_exts'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}
if (isset($_GET['add_new_ext']))
{
	if(!kleeja_check_form_key('adm_exts_new_ext'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}


//show exts
$query = array(
				'SELECT'	=> '*',
				'FROM'		=> "{$dbprefix}exts",
			);

$result_p = $SQL->build($query);

//pager 
$nums_rows		= $SQL->num_rows($result_p);
$currentPage	= isset($_GET['page']) ? intval($_GET['page']) : 1;
$Pager			= new SimplePager($perpage, $nums_rows, $currentPage);
$start			= $Pager->getStartRow();

$no_results = false;

if ($nums_rows > 0)
{
	$query['LIMIT']	= "$start, $perpage";
	$result = $SQL->build($query);

	while($row=$SQL->fetch_array($result))
	{
		//make new lovely arrays !!
		$g_sz[$row['id']] = isset($_POST['gsz' . $row['id']]) ? $_POST['gsz' . $row['id']] : $row['gust_size'];
		$u_sz[$row['id']] = isset($_POST['usz' . $row['id']]) ? $_POST['usz' . $row['id']] : $row['user_size'];

		$arr[]	= array(
						'id' 		=> $row['id'],
						'name' 		=> $row['ext'],
						'group'		=> ch_g(false, $row['group_id'], true),
						'g_size'	=> round($g_sz[$row['id']] / 1024),
						'g_allow'	=> (int) $row['gust_allow'] ? true : false,
						'u_size'	=> round($u_sz[$row['id']] / 1024),
						'u_allow'	=> (int) $row['user_allow'] ?  true : false,
						'ug_allow'	=> $row['gust_allow'] && $row['user_allow'] ? true : false
					);
	}
	$SQL->freeresult($result_p);
	$SQL->freeresult($result);
}
else #num rows
{
	$no_results = true;
}

//pages
$total_pages 	= $Pager->getTotalPages(); 
$arr_paging 	= $Pager->print_nums(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php')); 
$gr_exts_arr	= ch_g('new_ext_group', 9);

//after submit 
if (isset($_POST['submit']))
{
	if(!is_array($_POST['gsz']))
	{
		$_POST['gsz'] = array();
	}

	$affected = false;
	foreach($_POST['gsz'] as $n=>$v)
	{
		$update_query	= array(
								'UPDATE'	=> "{$dbprefix}exts",
								'SET'		=> 	"gust_size = '" . round(intval($_POST['gsz'][$n])*1024) . "', " . 
												"gust_allow = '" . (isset($_POST['gal'][$n]) ? 1 : 0) . "', " . 
												"user_size = '" . round(intval($_POST['usz'][$n])*1024) . "', " . 
												"user_allow = '" .  (isset($_POST['ual'][$n]) ? 1 : 0) . "'",
								'WHERE'		=>	"id=" . intval($n)
						);
	
		$SQL->build($update_query);
		if($SQL->affected())
		{
			$affected = true;
		}
	}

	//delete cache ..
	delete_cache('data_exts');

	$text	= ($affected ? $lang['UPDATED_EXTS'] : $lang['NO_UP_CHANGE_S']) . '<meta HTTP-EQUIV="REFRESH" content="2; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' .  (isset($_GET['page']) ? intval($_GET['page']) : '1') . '">' . "\n";
	$stylee	= "admin_info";
}
else if(isset($_GET['add_new_ext']))
{
	$new_ext_i = $SQL->escape($_POST['new_ext']);
	$ext_gr_i = intval($_POST['new_ext_group']);
	$ext_gr_i =  $ext_gr_i == 0 ? 9 : $ext_gr_i;

	//default
	$gust_size = '1024000';//1 mega
	$user_size = '1024000';//1 mega
			
	if(empty($new_ext_i))
	{
		$text	= $lang['EMPTY_EXT_FIELD'];
		$stylee	= 'admin_info';
	}
	else
	{
		//remove the first . in ext
		$new_ext_i = trim($new_ext_i);
		if($new_ext_i[0] == '.')
		{
			$new_ext_i = substr($new_ext_i, 1, strlen($new_ext_i));
		}

		//check if it's welcomed one
		//if he trying to be smart, he will add like ext1.ext2.php
		//so we will just look at last one
		$check_ext = strtolower(array_pop(explode('.', $new_ext_i))); 
		$not_welcomed_exts = array('php', 'php3', 'php5', 'php4', 'asp', 'aspx', 'shtml', 'html', 'htm', 'xhtml', 'phtml', 'pl', 'cgi', 'ini', 'htaccess', 'sql', 'txt');
		if(in_array($check_ext, $not_welcomed_exts))
		{
			redirect($action, false, false, 5);
			kleeja_admin_err(sprintf($lang['FORBID_EXT'], $check_ext));
		}

		//check if there is any exists of this ext in db
		$query = array(
						'SELECT'	=> '*',
						'FROM'		=> "{$dbprefix}exts",
						'WHERE'		=> "ext='" . $new_ext_i . "'",
					);

		$result = $SQL->build($query);

		if ($SQL->num_rows($result) > 0)
		{
			$text = sprintf($lang['NEW_EXT_EXISTS_B4'], $new_ext_i);
			$text .= '<meta HTTP-EQUIV="REFRESH" content="2; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";
			$stylee	= "admin_err";
		}
		else
		{
			//add to db
			$insert_query	= array(
									'INSERT'	=> '`group_id` ,`ext` ,`gust_size` ,`gust_allow` ,`user_size` ,`user_allow`',
									'INTO'		=> "`{$dbprefix}exts`",
									'VALUES'	=> "'$ext_gr_i', '$new_ext_i', '$gust_size', '1', '$user_size', '1'"
							);

			$SQL->build($insert_query);

			$text	= $lang['NEW_EXT_ADD']. '<meta HTTP-EQUIV="REFRESH" content="2; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";
			$stylee	= "admin_info";
		}

		$SQL->freeresult($result);
	} # add new ext
}


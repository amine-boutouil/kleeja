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
$current_smt	= isset($_GET['smt']) ? (preg_match('![a-z0-9_]!i', trim($_GET['smt'])) ? trim($_GET['smt']) : 'general') : 'general';
$action 		= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : 1) . '&amp;smt=' . $current_smt;
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
$arr_paging 	= $Pager->print_nums(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'), 'onclick="javascript:get_kleeja_link($(this).attr(\'href\'), \'#content\'); return false;"'); 
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
	kleeja_admin_info( ($affected ? $lang['UPDATED_EXTS'] : $lang['NO_UP_CHANGE_S']), true, '', true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' .  (isset($_GET['page']) ? intval($_GET['page']) : '1'));
}
else if(isset($_POST['submit_new_ext']))
{
	$new_ext_i = $SQL->escape($_POST['new_ext']);
	$ext_gr_i = intval($_POST['new_ext_group']);
	$ext_gr_i =  $ext_gr_i == 0 ? 9 : $ext_gr_i;

	//default
	$gust_size = '1024000';//1 mega
	$user_size = '1024000';//1 mega
			
	if(empty($new_ext_i))
	{
		kleeja_admin_err($lang['EMPTY_EXT_FIELD'] , true, '', true, $action);
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
			kleeja_admin_err(sprintf($lang['FORBID_EXT'], $check_ext), true, '', true, $action);
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
			kleeja_admin_err(sprintf($lang['NEW_EXT_EXISTS_B4'], $new_ext_i), true, '', true, $action);
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
			
			kleeja_admin_info($lang['NEW_EXT_ADD'], true, '', true,  basename(ADMIN_PATH) . '?cp=i_exts&amp;smt=general');

		}

		$SQL->freeresult($result);
	} # add new ext
}

//secondary menu
$go_menu = array(
				'general' => array('name'=>$lang['R_EXTS'], 'link'=> basename(ADMIN_PATH) . '?cp=i_exts&amp;smt=general', 'goto'=>'general', 'current'=> $current_smt == 'general'),
				'new_e' => array('name'=>$lang['ADD_NEW_EXT'], 'link'=> basename(ADMIN_PATH) . '?cp=i_exts&amp;smt=new_e', 'goto'=>'new_e', 'current'=> $current_smt == 'new_e'),
				'calc' => array('name'=>$lang['BCONVERTER'], 'link'=> basename(ADMIN_PATH) . '?cp=i_exts&amp;smt=calc', 'goto'=>'calc', 'current'=> $current_smt == 'calc'),
	);

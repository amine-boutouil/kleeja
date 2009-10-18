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

//english as default
if(!isset($_REQUEST['lang']))
{
	$_REQUEST['lang'] = 'en';
}

//for style ..
$stylee 	= "admin_langs";
$action 	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' .  (isset($_GET['page']) ? intval($_GET['page']) : 1) . '&amp;lang=' . $SQL->escape($_REQUEST['lang']);
$action2 	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php');

//get languages
$lngfiles = '';
if ($dh = @opendir($root_path . 'lang'))
{
	while (($file = readdir($dh)) !== false)
	{
		if(strpos($file, '.') === false && $file != '..' && $file != '.')
		{
			$lngfiles .= '<option ' . ($_REQUEST['lang'] == $file ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
		}
	}
	closedir($dh);
}

$query = array(
				'SELECT'	=> '*',
				'FROM'		=> "{$dbprefix}lang",
				'WHERE'		=> "lang_id='" .  $SQL->escape($_REQUEST['lang']) . "'",
				'ORDER BY'	=> 'word DESC'
		);

$result = $SQL->build($query);

//pagination
$nums_rows		= $SQL->num_rows($result);
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
		$transs[$row['word']]	= isset($_POST['t_' . $row['word']]) ? $_POST['t_' . $row['word']] : $row['trans'];
		$del[$row['word']]		= isset($_POST['del_' . $row['word']]) ? $_POST['del_' . $row['word']] : '';

		//make new lovely arrays !!
		$arr[]	= array(
						'lang_id'	=> $row['lang_id'],
						'word'		=> $row['word'],
						'trans'		=> $transs[$row['word']],
					);

		//when submit
		if (isset($_POST['submit']))
		{
			//del
			if ($del[$row['word']])
			{
				$query_del = array(
										'DELETE'	=> "{$dbprefix}lang",
										'WHERE'		=>	"word='" . $SQL->escape($row['word']) . "' AND lang_id='" .  $SQL->escape($_REQUEST['lang']) . "'"
									);

				$SQL->build($query_del);
			}
			//update
			$update_query = array(
									'UPDATE'	=> "{$dbprefix}lang",
									'SET'		=> 	"trans = '" . $SQL->escape($transs[$row['word']]) . "'",
									'WHERE'		=>	"word='" . $SQL->escape($row['word']) . "' AND lang_id='" .  $SQL->escape($_REQUEST['lang']) . "'"
								);

			$SQL->build($update_query);
		}
	}

	$SQL->freeresult($result);
}
else
{
	//no result ...
	$no_results = true;
}

//pages
$total_pages 	= $Pager->getTotalPages(); 
$page_nums 		= $Pager->print_nums(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php')); 

//after submit 
if (isset($_POST['submit']))
{
	$text = $lang['NO_UP_CHANGE_S'];
	if($SQL->affected())
	{
		delete_cache('data_lang');
		$text = $lang['WORDS_UPDATED'];
	}
	
	$text	.= '<meta HTTP-EQUIV="REFRESH" content="0; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : '1') . '&amp;lang=' . $SQL->escape($_REQUEST['lang']) . '">' . "\n";
	$stylee	= "admin_info";
}

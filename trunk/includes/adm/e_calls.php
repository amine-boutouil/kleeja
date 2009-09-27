<?php
//calls
//part of admin extensions
//conrtoll calls
	
//copyright 2007-2009 Kleeja.com ..
//license http://opensource.org/licenses/gpl-license.php GNU Public License
//$Author: saanina $ , $Rev: 1019 $,  $Date:: 2009-09-06 02:16:53 +0300#$
	
// not for directly open
if (!defined('IN_ADMIN'))
{
	exit('no directly opening : ' . __file__);
}
	

//for style ..
$stylee	= "admin_calls";
$action	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : 1);

$query	= array(
				'SELECT'	=> '*',
				'FROM'		=> "`{$dbprefix}call`",
				'ORDER BY'	=> 'id DESC'
		);

$result = $SQL->build($query);
		
//pager 
$nums_rows		= $SQL->num_rows($result);
$currentPage	= isset($_GET['page']) ? intval($_GET['page']) : 1;
$Pager			= new SimplePager($perpage, $nums_rows, $currentPage);
$start			= $Pager->getStartRow();


$no_results = false;
$del_nums = array();

if ($nums_rows > 0)
{
	$query['LIMIT']	= "$start,$perpage";
	$result = $SQL->build($query);

	while($row=$SQL->fetch_array($result))
	{
		//make new lovely arrays !!
		$arr[]	= array(
						'id' 		=> $row['id'],
						'name' 		=> $row['name'],
						'mail' 		=> $row['mail'],
						'text' 		=> htmlspecialchars($row['text']),
						'time' 		=> gmdate('d-m-Y H:a', $row['time']),
						'ip' 		=> $row['ip'],
						'ip_finder'	=> 'http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=' . $row['ip'] . '&do_search=Search'
					);

		$del[$row['id']] = isset($_POST['del_' . $row['id']]) ? $_POST['del_' . $row['id']] : '';
		$sen[$row['id']] = isset($_POST['v_' . $row['id']]) ? $_POST['v_' . $row['id']] : '';
	
		//when submit !!
		if (isset($_POST['submit']))
		{
			if ($del[$row['id']])
			{
				$del_nums[] = $row['id'];
			}
		}

		if (isset($_POST['reply_submit']))
		{
			if ($sen[$row['id']])
			{
				$to      = $row['mail'];
				$subject = $lang['REPLY_CALL'] . ':' . $config['sitename'];
				$message = "\n " . $lang['REPLY_CALL'] . " " . $row['name'] . "\r\n " . $lang['REPLIED_ON_CAL'] . " : " . $config['sitename'] . 
							"\r\n " . $lang['BY_EMAIL'] . ": " . $row['mail'] . "\r\n" . $lang['ADMIN_REPLIED'] . "\r\n" . $sen[$row['id']] . "\r\n\r\n Kleeja.com ";

				$send =  send_mail($to, $message, $subject, $config['sitemail'], $config['sitename']);

				if ($send)
				{
					$text	= $lang['IS_SEND_MAIL'];
					$stylee	= "admin_info";
				}
			}
		}
	}
	$SQL->freeresult($result);	
}
else
{
	$no_results = true;
}

//if deleted
if(sizeof($del_nums))
{
	$query_del	= array(
						'DELETE'	=> "{$dbprefix}call",
						'WHERE'		=> "id IN('" . implode("', '", $del_nums) . "')"
					);

	$SQL->build($query_del);
}
	
$total_pages	= $Pager->getTotalPages(); 
$page_nums	= $Pager->print_nums(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php')); 

//after submit
if (isset($_POST['submit']))
{
	$text	= ($SQL->affected() ? $lang['CALLS_UPDATED'] : $lang['NO_UP_CHANGE_S']) . '<meta HTTP-EQUIV="REFRESH" content="0; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : 1) . '">' ."\n";
	$stylee	= "admin_info";
}

<?php
/**
*
* @package adm
* @version $Id:  $
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
$stylee	= "admin_reports";
$action	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($GET['page'])  ? intval($GET['page']) : 1);
$msg_sent = isset($_GET['sent']) ? intval($_GET['sent']) : false; 


$query = array(
				'SELECT'	=> '*',
				'FROM'		=> "{$dbprefix}reports",
				'ORDER BY'	=> 'id DESC'
		);

$result = $SQL->build($query);

//pagination
$nums_rows		= $SQL->num_rows($result);
$currentPage	= isset($_GET['page']) ? intval($_GET['page']) : 1;
$Pager			= new SimplePager($perpage, $nums_rows, $currentPage);
$start			= $Pager->getStartRow();


$no_results	= false;
$del_nums	= array();

if ($nums_rows > 0)
{
	$query['LIMIT']	=	"$start, $perpage";
	$result = $SQL->build($query);

	while($row=$SQL->fetch_array($result))
	{
		//make new lovely arrays !!
		$arr[]	= array(
						'id' 		=> $row['id'],
						'name' 		=> $row['name'],
						'mail' 		=> $row['mail'],
						'url'  		=> $row['url'],
						'text' 		=> $row['text'],
						'time' 		=> date('d-m-Y H:i a', $row['time']),
						'ip'	 	=> $row['ip'],
						'sent'		=> $row['id'] == $msg_sent,
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
				$subject = $lang['REPLY_REPORT'] . ':' . $config['sitename'];
				$message = "\n " . $lang['WELCOME'] . " " . $row['name'] . "\r\n " . $lang['U_REPORT_ON'] . " " . $config['sitename']. "\r\n " . 
							$lang['BY_EMAIL'] . " : " . $row['mail']."\r\n" . $lang['ADMIN_REPLIED'] . ": \r\n" . $sen[$row['id']] . "\r\n\r\n kleeja.com";

				$send =  send_mail($to, $message, $subject, $config['sitemail'], $config['sitename']);

				if ($send)
				{
					//
					//We will redirect to pages of results and show info msg there ! 
					//
					redirect(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&page=' . (isset($_GET['page']) ? intval($_GET['page']) : 1) . '&sent=' . $row['id']);
				}
				else
				{
					kleeja_admin_err($lang['ERR_SEND_MAIL']);
				}
			}
		}
	}
	$SQL->freeresult($result);
}
else #num rows
{
	$no_results = true;
}
	
//if deleted
if(sizeof($del_nums))
{
	$query_del	= array(
						'DELETE'	=> "{$dbprefix}reports",
						'WHERE'		=> "id IN('" . implode("', '", $del_nums) . "')"
					);

	$SQL->build($query_del);
}

$total_pages 	= $Pager->getTotalPages(); 
$page_nums 		= $Pager->print_nums(basename(ADMIN_PATH)  . '?cp=' . basename(__file__, '.php')); 

//after submit 
if (isset($_POST['submit']))
{
	$text	= ($SQL->affected() ? $lang['REPORTS_UPDATED'] : $lang['NO_UP_CHANGE_S']) . '<meta HTTP-EQUIV="REFRESH" content="0; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : 1) . '">' ."\n";
	$stylee	= "admin_info";
}

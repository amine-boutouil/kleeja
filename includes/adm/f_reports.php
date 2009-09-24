<?php
	//reports
	//part of admin extensions
	//conrtoll reports
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author: saanina $ , $Rev: 1021 $,  $Date:: 2009-09-06 02:28:19 +0300#$
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	

	
		//for style ..
		$stylee 		= "admin_reports";
		$action 		= basename(ADMIN_PATH) . "?cp=reports&amp;page=" . (isset($GET['page'])  ? intval($GET['page']) : 1);

		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}reports",
					'ORDER BY'	=> 'id DESC'
					);
						
		$result = $SQL->build($query);
		
		//pagination
		$nums_rows		= $SQL->num_rows($result);
		$currentPage	= (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager			= new SimplePager($perpage,$nums_rows,$currentPage);
		$start			= $Pager->getStartRow();


		$no_results = false;
		$del_nums = array();

		if ($nums_rows > 0)
		{
			$query['LIMIT']	=	"$start, $perpage";
			$result = $SQL->build($query);
			
			while($row=$SQL->fetch_array($result))
			{
				//make new lovely arrays !!
				$arr[] = array( 'id' 		=> $row['id'],
								'name' 		=> $row['name'],
								'mail' 		=> $row['mail'],
								'url'  		=> $row['url'],
								'text' 		=> $row['text'],
								'time' 		=> date("d-m-Y H:i a", $row['time']),
								'ip'	 	=> $row['ip'],
								'ip_finder'	=> 'http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=' . $row['ip'] . '&do_search=Search'
						);
			
				$del[$row['id']] = ( isset($_POST["del_".$row['id']]) ) ? $_POST["del_".$row['id']] : "";
				$sen[$row['id']] = ( isset($_POST["v_".$row['id']]) ) ? $_POST["v_".$row['id']] 	: "";
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
							$message = "\n " . $lang['WELCOME'] . " ".$row['name']."\r\n " . $lang['U_REPORT_ON'] . " ".$config['sitename']. "\r\n " . $lang['BY_EMAIL'] . ": ".$row['mail']."\r\n" . $lang['ADMIN_REPLIED'] . ": \r\n".$sen[$row['id']]."\r\n\r\n Kleeja Script";
							
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
	else #num rows
	{
		$no_results = true;
	}
	
	//if deleted
	if(sizeof($del_nums))
	{
		$query_del	= array(
							'DELETE'	=> "{$dbprefix}reports",
							'WHERE'		=> "id='" . implode("', '", $del_nums) . "'"
					);
																
		$SQL->build($query_del);
	}

	$total_pages 	= $Pager->getTotalPages(); 
	$page_nums 		= $Pager->print_nums(basename(ADMIN_PATH)  . '?cp=reports'); 
		
	//after submit 
	if (isset($_POST['submit']))
	{
		$text	= ($SQL->affected() ? $lang['REPORTS_UPDATED'] : $lang['NO_UP_CHANGE_S']) . '<meta HTTP-EQUIV="REFRESH" content="0; url=' . basename(ADMIN_PATH) . '?cp=reports&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : '1') . '">' ."\n";
		$stylee	= "admin_info";
	}

//<-- EOF

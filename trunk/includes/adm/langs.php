<?php
	//lang
	//part of admin extensions
	//conrtoll lang terms
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author: saanina $ , $Rev: 362 $,  $Date:: 2009-05-12 16:12:58 +0300#$
	
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	
		

		if(!isset($_REQUEST['lang']))
		{
			$_REQUEST['lang'] = 'en';
		}
		
		
		//for style ..
		$stylee 	= "admin_langs";
		$action 	= basename(ADMIN_PATH) . "?cp=langs&amp;page=" .  (isset($_GET['page']) ? intval($_GET['page']) : '1') . '&amp;lang=' . $SQL->escape($_REQUEST['lang']);
		$action2 	= basename(ADMIN_PATH) . "?cp=langs";

		
		//get languages
		$lngfiles = '';
		if ($dh = @opendir($root_path . 'lang'))
		{
			while (($file = readdir($dh)) !== false)
			{
				if(strpos($file, '.') === false && $file != '..' && $file != '.')
				{
					$lngfiles .=  '<option ' . ($_REQUEST['lang'] == $file ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
				}
			}
			closedir($dh);
		}
		
		
		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}lang",
					'WHERE'	=> 'lang_id="' .  $SQL->escape($_REQUEST['lang']) . '"',
					'ORDER BY'	=> 'word DESC'
					);
		
		$result = $SQL->build($query);

		
		//pager 
		$nums_rows = $SQL->num_rows($result);
		$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager = new SimplePager($perpage, $nums_rows, $currentPage);
		$start = $Pager->getStartRow();

		$no_results = false;
		
		if ($nums_rows > 0)
		{
			$query['LIMIT']	=	"$start, $perpage";
			
			$result = $SQL->build($query);
			
			while($row=$SQL->fetch_array($result))
			{
		
				//make new lovely arrays !!
				$transs[$row['word']]	= (isset($_POST["t_" . $row['word']])) ? $_POST["t_" . $row['word']] : $row['trans'];
				$del[$row['word']] 	= (isset($_POST["del_" . $row['word']])) ? $_POST["del_" . $row['word']] : "";
				
				
				$arr[] = array( 'lang_id' => $row['lang_id'],
								'word' => $row['word'],
								'trans' => $transs[$row['word']],
							);

			
				//when submit !!
				if (isset($_POST['submit']))
				{
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
	else #num rows
	{ 
		$no_results = true;
	}
	
	$total_pages 	= $Pager->getTotalPages(); 
	$page_nums 		= $Pager->print_nums($config['siteurl'] . ADMIN_PATH . '?cp=langs'); 

	//after submit 
	if (isset($_POST['submit']))
	{
			$text	= $lang['WORDS_UPDATED'] . '<meta HTTP-EQUIV="REFRESH" content="0; url=' . basename(ADMIN_PATH) . '?cp=langs&amp;page=' . (isset($_GET['page']) ? intval($_GET['page']) : '1') . '&amp;lang=' . $SQL->escape($_REQUEST['lang']) . '">' . "\n";
			$stylee	= "admin_info";
	}
?>

<?php
	//lang
	//part of admin extensions
	//conrtoll lang terms
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	


		//for style ..
		$stylee 	= "admin_langs";
		$action 	= "admin.php?cp=langs&amp;page=". intval($_GET['page']) . (isset($_REQUEST['lang']) ? '&amp;lang=' . $_REQUEST['lang'] : '');
		$action2 	= "admin.php?cp=langs";

		//get languages
		$lngfiles = '';
		if ($dh = @opendir($root_path . 'lang'))
		{
			while (($file = readdir($dh)) !== false)
			{
				if(strpos($file, '.') === false && $file != '..' && $file != '.')
				{
					$lngfiles .=  '<option ' . (($_REQUEST['lang']==$file) ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
				}
			}
			closedir($dh);
		}
		
		
		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}lang",
					'ORDER BY'	=> 'word DESC'
					);
		
		if(isset($_REQUEST['lang']))
		{
			$query['WHERE'] = 'lang_id="' . $SQL->escape($_REQUEST['lang']) . '"';
		}
		
		$result = $SQL->build($query);

		
		/////////////pager 
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
				$idd[$row['word']]	= (isset($_POST["l_" . $row['word']])) ? $_POST["l_" . $row['word']] : $row['lang_id'];
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
											'WHERE'		=>	"word='" . $SQL->escape($row['word']) . "' AND lang_id='" . $SQL->escape($idd[$row['word']]) . "'"
										);
																
						if (!$SQL->build($query_del))
						{
							die($lang['CANT_DELETE_SQL']);
						}	
					}

					//update

					$update_query = array(
										'UPDATE'	=> "{$dbprefix}lang",
										'SET'		=> 	"trans = '" . $SQL->escape($transs[$row['word']]) . "'",
										'WHERE'		=>	"word='" . $SQL->escape($row['word']) . "' AND lang_id='" . $SQL->escape($idd[$row['word']]) . "'"
									);

					if (!$SQL->build($update_query))
					{
						die($lang['CANT_UPDATE_SQL']);
					}	
				}
			}
			$SQL->freeresult($result);

	}
	else #num rows
	{ 
		$no_results = true;
	}
	
	$total_pages 	= $Pager->getTotalPages(); 
	$page_nums 		= $Pager->print_nums($config['siteurl'] . 'admin.php?cp=langs'); 

	//after submit 
	if (isset($_POST['submit']))
	{
			$text	= $lang['WORDS_UPDATED'] . '<meta HTTP-EQUIV="REFRESH" content="0; url=./admin.php?cp=langs&amp;page=' . intval($_GET['page']). '">' ."\n";
			$stylee	= "admin_info";
	}
?>
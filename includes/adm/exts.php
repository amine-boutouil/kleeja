<?php
	//exts
	//part of admin extensions
	//conrtoll extensions of files
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	
		//for style ..
		$stylee = "admin_exts";
		//words
		$action 	= "admin.php?cp=exts&amp;page=". intval($_GET['page']);
		$n_submit 	= $lang['UPDATE_EXTS'];
					
		$query = array(
						'SELECT'	=> '*',
						'FROM'		=> "{$dbprefix}exts",
				);
									
		$result = $SQL->build($query);
		
		/////////////pager 
		$nums_rows		= $SQL->num_rows($result);
		$currentPage	= (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager			= new SimplePager($perpage, $nums_rows, $currentPage);
		$start			= $Pager->getStartRow();

		$no_results = false;

		if ($nums_rows > 0)
		{
			$query['LIMIT']	=	"$start, $perpage";
			$result = $SQL->build($query);
			
			while($row=$SQL->fetch_array($result))
			{
				//make new lovely arrays !!
				$gr[$row['id']] 	= (isset($_POST["gr_" . $row['id']])) ? $_POST["gr_" . $row['id']]  : $row['group_id'];
				$g_sz[$row['id']]	= (isset($_POST["gsz_" . $row['id']])) ? $_POST["gsz_" . $row['id']] : $row['gust_size'];
				$u_sz[$row['id']]	= (isset($_POST["usz_" . $row['id']]) ) ? $_POST["usz_" . $row['id']] : $row['user_size'];

				$arr[] = array( 'id' 		=>$row['id'],
								'name' 		=>$row['ext'],
								'group'		=>ch_g(false, $gr[$row['id']], true),
								'g_size'	=>$g_sz[$row['id']],
								'g_allow'	=>'<input name="gal[' . $row['id'] . ']" type="checkbox" ' . (($row['gust_allow'])? 'checked="checked"' : '') . ' />',
								'u_size'	=>$u_sz[$row['id']],
								'u_allow'	=>'<input name="ual[' . $row['id'] . ']" type="checkbox" ' . (($row['user_allow'])? 'checked="checked"' : '') . ' />',
	
							);
			}
			$SQL->freeresult($result);
		
		}
		else #num rows
		{ 
			$no_results = true;
		}
		
		$total_pages 	= $Pager->getTotalPages(); 
		$arr_paging 	= $Pager->print_nums($config['siteurl'] . 'admin.php?cp=exts'); 
		

		//after submit ////////////////
		if (isset($_POST['submit']))
		{
					if(!is_array($_POST['gr'])) $_POST['gr'] = array();

					foreach($_POST['gr'] as $n=>$v)
					{

						$update_query = array(
											'UPDATE'	=> "{$dbprefix}exts",
											'SET'		=> 	"group_id = '" . intval($_POST['gr'][$n]) . "',
															gust_size = '" . intval($_POST['gsz'][$n]) . "',
															gust_allow = '" . (isset($_POST['gal'][$n]) ? 1 : 0) . "',
															user_size = '" . intval($_POST['usz'][$n]) . "',
															user_allow = '" .  (isset($_POST['ual'][$n])? 1 : 0) . "'",
											'WHERE'		=>	"id='" . intval($n) . "'"
											);
	
						$SQL->build($update_query);
					}
				
				
			//delete cache ..
			delete_cache('data_exts');

			$text	= $lang['UPDATED_EXTS']. '<meta HTTP-EQUIV="REFRESH" content="0; url=./admin.php?cp=exts&amp;page=' . intval($_GET['page']). '">' ."\n";
			$stylee	= "admin_info";
		}

?>

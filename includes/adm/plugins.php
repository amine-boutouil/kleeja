<?php
	//plugins
	//part of admin extensions
	//conrtoll plugins 
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	


	//for style ..
		$stylee = "admin_plugins";
		$action = ADMIN_PATH . "?cp=plugins";
		$no_plugins	= false;
		
		//kleeja depend on its users .. and kleeja love them .. so let's tell them about that ..
		$klj_d_s = $lang['KLJ_MORE_PLUGINS'][rand(0, sizeof($lang['KLJ_MORE_PLUGINS'])-1)];
		
		//get styles
		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}plugins"
					);
						
		$result = $SQL->build($query);
		
		if($SQL->num_rows($result)>0)
		{
			while($row=$SQL->fetch_array($result))
			{
					$arr[] = array( 'plg_id'		=> $row['plg_id'],
									'plg_name'		=> $row['plg_name'] . ($row['plg_disabled'] == 1 ? ' [x]': ''),
									'plg_disabled'	=> $row['plg_disabled'] == '1' ? true : false,
									'plg_ver'		=> $row['plg_ver'],
									'plg_author'	=> $row['plg_author'],
									'plg_dsc'		=> $row['plg_dsc'],
								);
			}
		}
		else
		{
			$no_plugins	=	true;
		}
		
		$SQL->freeresult($result);
	

		//after submit 
		if (isset($_GET['do_plg']))
		{
			$plg_id = intval($_GET['do_plg']);

			switch($_GET['m'])
			{
				case '1': // disable the plguin		
				case '2': //enable it
				
					$action	=	($_GET['m'] == 1) ? 1 : 0;

					//update
					$update_query = array(
											'UPDATE'	=> "{$dbprefix}plugins",
											'SET'		=> "plg_disabled=$action",
											'WHERE'		=> "plg_id='" . $plg_id . "'"
										);

					$SQL->build($update_query);
					//delete cache ..
					delete_cache('data_hooks');
							
					//show msg
					$text = $lang['PLGUIN_DISABLED_ENABLED'];
					$text .= '<meta HTTP-EQUIV="REFRESH" content="1; url=' . ADMIN_PATH . '?cp=plugins">' . "\n";
					$stylee	= "admin_info";
		
					
				break;
				
				
				case '3': // del the plguin
				
							//before delete we have look for unistalling 
							$query = array(
										'SELECT'	=> 'plg_uninstall',
										'FROM'		=> "{$dbprefix}plugins",
										'WHERE'		=> "plg_id='" . $plg_id . "'"
										);
											
							$result = $SQL->fetch_array($SQL->build($query));

							if(trim($result['plg_uninstall']) != '')
							{
								eval($result['plg_uninstall']);
							}
							
							$query_del = array(
											'DELETE'	=> "{$dbprefix}plugins",
											'WHERE'		=> "plg_id='" . $plg_id . "'"
											);

							$SQL->build($query_del);
											
							$query_del2 = array(
											'DELETE'	=> "{$dbprefix}hooks",
											'WHERE'		=> "plg_id='" . $plg_id . "'"
											);		
											
							$SQL->build($query_del2);
							
							//delete cache ..
							delete_cache('data_hooks');
							delete_cache('data_config');

							//show msg
							$text	= $lang['PLUGIN_DELETED'];
							$text .= '<meta HTTP-EQUIV="REFRESH" content="1; url=' . ADMIN_PATH . '?cp=plugins">' . "\n";
							$stylee	= "admin_info";
						
				break;
				
			}
		}
		//new style from xml
		if(isset($_POST['submit_new_plg']))
		{
		
			$text	=	'';
			// oh , some errors
			if($_FILES['imp_file']['error'])
			{
				$text = $lang['ERR_IN_UPLOAD_XML_FILE'];
			}
			else if(!is_uploaded_file($_FILES['imp_file']['tmp_name']))
			{
				$text = $lang['ERR_UPLOAD_XML_FILE_NO_TMP'];
			}

			//get content
			$contents = @file_get_contents($_FILES['imp_file']['tmp_name']);
			// Delete the temporary file if possible
			
			// Are there contents?
			if(!trim($contents))
			{
				$text = $lang['ERR_UPLOAD_XML_NO_CONTENT'];
			}
						
						
			if(empty($text))
			{
				$return = creat_plugin_xml($contents);
				
				if($return === true)
				{
					$text = $lang['NEW_PLUGIN_ADDED'];
					$text .= '<meta HTTP-EQUIV="REFRESH" content="3; url=' . ADMIN_PATH . '?cp=plugins">' . "\n";
				}
				else if ($return === 'xyz')//exists before
				{
					$text = $lang['PLUGIN_EXISTS_BEFORE'];
					$text .= '<meta HTTP-EQUIV="REFRESH" content="3; url=' . ADMIN_PATH . '?cp=plugins">' . "\n";					
				}
				else if ($return === 'upd') // updated success
				{
					$text = $lang['PLUGIN_UPDATED_SUCCESS'];
					$text .= '<meta HTTP-EQUIV="REFRESH" content="3; url=' . ADMIN_PATH . '?cp=plugins">' . "\n";			
				}
				else
				{
					$text = $lang['ERR_IN_UPLOAD_XML_FILE'];
				}
			}		
						
			$stylee	= "admin_info";
		}
?>

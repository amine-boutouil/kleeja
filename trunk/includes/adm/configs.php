<?php
	//configs
	//part of admin extensions
	//conrtoll all configuarations of the script 
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
		//for style ..
		$stylee 		= "admin_configs";
		//words
		$action 		= "admin.php?cp=options";
		$n_submit 		= $lang['UPDATE_CONFIG'];


		$n_googleanalytics = '<a href="http://www.google.com/analytics">Google Analytics</a>';
		//general
		$STAMP_IMG_URL = './images/watermark.png';
		$stylfiles = $lngfiles	= $authtypes = '';
					
		$query = array(
						'SELECT'	=> '*',
						'FROM'		=> "{$dbprefix}config",
						'ORDER BY'	=> 'display_order'
					);
									
		$result = $SQL->build($query);

		while($row=$SQL->fetch_array($result))
		{
			//make new lovely array !!
			$con[$row['name']] = $row['value'];
				
			if($row['name'] == 'thmb_dims') 
			{
				list($thmb_dim_w, $thmb_dim_h) = @explode('*', $con['thmb_dims']);
			}
			else if($row['name'] == 'style') 
			{
				//get styles
				if ($dh = @opendir($root_path . 'styles'))
				{
						while (($file = readdir($dh)) !== false)
						{
							if(strpos($file, '.') === false && $file != '..' && $file != '.')
								$stylfiles .=  '<option ' . (($con['style']==$file) ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
						}
						closedir($dh);
				}
			}
			else if($row['name'] == 'language') 
			{
				//get languages
				if ($dh = @opendir($root_path . 'lang'))
				{
						while (($file = readdir($dh)) !== false)
						{
							if(strpos($file, '.') === false && $file != '..' && $file != '.')
							{
								$lngfiles .=  '<option ' . (($con['language']==$file) ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
							}
						}
						closedir($dh);
				}
			}
			else if($row['name'] == 'user_system') 
			{
				//get auth types
				//fix previus choice in old kleeja
				if(in_array($con['user_system'], array('2', '3', '4')))
				{
					$con['user_system'] = str_replace(array('2', '3', '4'), array('phpbb', 'vb', 'mysmartbb'), $con['user_system']);
				}
			
				$authtypes .= '<option value="1"' . (($con['user_system']=='1') ? ' selected="selected"' : '') . '>' . $lang['NORMAL'] . '</option>' . "\n";
				if ($dh = @opendir($root_path . 'includes/auth_integration'))
				{
						while (($file = readdir($dh)) !== false)
						{
							if(strpos($file, '.php') !== false)
							{
								$file = trim(str_replace('.php', '', $file));
								$authtypes .=  '<option value="' . $file . '"' . (($con['user_system']==$file) ? ' selected="selected"' : '') . '>' . $file . '</option>' . "\n";
							}
						}
						closedir($dh);
				}
			}
				//options from database [UNDER TEST]
				if(!empty($row['option'])) 
				{
					$options .= '<tr>
					<td><label for="' . $row['name'] . '">' . (isset($lang[strtoupper($row['name'])]) ? $lang[strtoupper($row['name'])] : $olang[strtoupper($row['name'])]) . '</label></td>
					<td><label>' . $tpl->admindisplayoption($row['option']) . '</label></td></tr>';
				}
				//when submit !!
				if (isset($_POST['submit']))
				{
					//-->
					$new[$row['name']] = (isset($_POST[$row['name']])) ? $_POST[$row['name']] : $con[$row['name']];
					//thmb_dims
					if($row['name'] == 'thmb_dims')
					{
						$new['thmb_dims'] = intval($_POST['thmb_dim_w']) . '*' . intval($_POST['thmb_dim_h']);
					}
					
					($hook = kleeja_run_hook('after_submit_adm_config')) ? eval($hook) : null; //run hook
					
					$update_query = array(
											'UPDATE'	=> "{$dbprefix}config",
											'SET'		=> "value='" . $SQL->escape($new[$row['name']]) . "'",
											'WHERE'		=> "name='" . $row['name'] . "'"
										);

					if (!$SQL->build($update_query))
					{
						die($lang['CANT_UPDATE_SQL']);
					}
				}
		}
		
		$SQL->freeresult($result);
		
		//after submit
		if (isset($_POST['submit']))
		{
			//empty ..
			if (empty($_POST['sitename']) || empty($_POST['siteurl']) || empty($_POST['foldername']) || empty($_POST['filesnum']))
			{
				$text	= $lang['EMPTY_FIELDS'];
				$stylee	= "admin_err";
			}
			elseif (!is_numeric($_POST['filesnum']) || !is_numeric($_POST['sec_down']))
			{
				$text	= $lang['NUMFIELD_S'];
				$stylee	= "admin_err";
			}
			else
			{
				if(($_POST['style'] != $config['style']) || ($_POST['language'] != $config['language']))
				{
					delete_cache('', true); //delete all cache to get new style
				}
				
				$text	= $lang['CONFIGS_UPDATED'];
				$stylee	= "admin_info";
			}
			
			//delete cache ..
			delete_cache('data_config');

		}#submit

?>
<?php
	//configs
	//part of admin extensions
	//conrtoll all configuarations of the script 
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	
	
	//for style ..
	$stylee 		= "admin_configs";
	//words
	$action 		= basename(ADMIN_PATH) . '?cp=options';
	$n_submit 		= $lang['UPDATE_CONFIG'];
	$options		= '';
	$SHOW_CH_STAGE	= isset($_POST['type']) ? false : true;
	$type_is		= isset($_POST['type']) ? htmlspecialchars($_POST['type']) : '';

	switch($SHOW_CH_STAGE):
		
		//
		//box of config types
		//
		case true:
		
			$query = array(
							'SELECT'	=> 'DISTINCT(type)',
							'FROM'		=> "{$dbprefix}config",
							'ORDER BY'	=> 'display_order'
					);
					
			$typesnavi = array();
			$result = $SQL->build($query);

			while($row = $SQL->fetch_array($result))
			{
				$typesnavi[] = array(
								'typename'	=> $row['type'],
								'typetitle'	=> !empty($lang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])]) ? $lang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])] : (!empty($olang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])]) ? $olang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])] : $lang['CONFIG_KLJ_MENUS_OTHER']),
							 );
			}
			
			$SQL->freeresult($result);
			
		break; //end true
		
		//
		//page of edit *type* configs
		//
		case false:
			
			//general varaibles
			$n_googleanalytics = '<a href="http://www.google.com/analytics">Google Analytics</a>';
			//general
			$STAMP_IMG_URL = '../images/watermark.png';
			$stylfiles = $lngfiles	= $authtypes = '';
			$optionss  = array();
			
			$query = array(
							'SELECT'	=> '*',
							'FROM'		=> "{$dbprefix}config",
							'ORDER BY'	=> 'display_order'
						);
			
			if(!$SHOW_CH_STAGE)
			{
				if($_POST['type'] != 'all')
				{
					$query['WHERE'] = "type = '" . $SQL->escape($_POST['type']) . "'";
				}
			}
										
			$result = $SQL->build($query);

			while($row=$SQL->fetch_array($result))
			{
				$i++;
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
								{
									$stylfiles .= '<option ' . ($con['style'] == $file ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
								}
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
									$lngfiles .=  '<option ' . ($con['language'] == $file ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
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
				
					$authtypes .= '<option value="1"' . ($con['user_system']=='1' ? ' selected="selected"' : '') . '>' . $lang['NORMAL'] . '</option>' . "\n";
					if ($dh = @opendir($root_path . 'includes/auth_integration'))
					{
							while (($file = readdir($dh)) !== false)
							{
								if(strpos($file, '.php') !== false)
								{
									$file = trim(str_replace('.php', '', $file));
									$authtypes .=  '<option value="' . $file . '"' . ($con['user_system'] == $file ? ' selected="selected"' : '') . '>' . $file . '</option>' . "\n";
								}
							}
							closedir($dh);
					}
				}
				
				($hook = kleeja_run_hook('while_fetch_adm_config')) ? eval($hook) : null; //run hook
				
					//options from database [UNDER TEST]
					if(!empty($row['option'])) 
					{
						$optionss[$row['name']] = array(
						'option'		 => '<tr>' . "\n" .  
											'<td><label for="' . $row['name'] . '">' . (!empty($lang[strtoupper($row['name'])]) ? $lang[strtoupper($row['name'])] : $olang[strtoupper($row['name'])]) . '</label></td>' . "\n" .
											'<td><label>' . (empty($row['option']) ? '' : $tpl->admindisplayoption($row['option'])) . '</label></td>' . "\n" .
											'</tr>' . "\n",
						'type'			=> $row['type'],
						'display_order' => $row['display_order'],
						);
						
					}
					//when submit !!
					if (isset($_POST['submit']))
					{
						//-->
						$new[$row['name']] = (isset($_POST[$row['name']])) ? $_POST[$row['name']] : $con[$row['name']];
						
						//save them as you want ..
						if($row['name'] == 'thmb_dims')
						{
							$new['thmb_dims'] = intval($_POST['thmb_dim_w']) . '*' . intval($_POST['thmb_dim_h']);
						}
						else if($row['name'] == 'livexts')
						{
							$new['livexts'] = implode(',', array_map('trim', explode(',', $_POST['livexts'])));
						}
						
						($hook = kleeja_run_hook('after_submit_adm_config')) ? eval($hook) : null; //run hook
						
						$update_query = array(
												'UPDATE'	=> "{$dbprefix}config",
												'SET'		=> "value='" . $SQL->escape($new[$row['name']]) . "'",
												'WHERE'		=> "name='" . $row['name'] . "'"
											);

						$SQL->build($update_query);
					}
			}
			$types = array();
			
			foreach($optionss as $key => $option)
			{
				if(empty($types[$option['type']]))
				{
					$types[$option['type']] = '<tr style="background:white;"><td colspan="2"><strong><em><h3>' . (!empty($lang['CONFIG_KLJ_MENUS_' . strtoupper($option['type'])]) ? $lang['CONFIG_KLJ_MENUS_' . strtoupper($option['type'])] : ((!empty($olang['CONFIG_KLJ_MENUS_' . strtoupper($option['type'])])) ? $olang['CONFIG_KLJ_MENUS_' . strtoupper($option['type'])] : $lang['CONFIG_KLJ_MENUS_OTHER'])) . '</h3></em></strong></td></tr>';
				}
			}
			
			foreach($types as $typekey => $type)
			{
				$options .= $type;
				foreach($optionss as $key => $option)
				{
					if($option['type'] == $typekey)
					{
						
						$options .= $option['option'];
					}
				}
			}

			
			$SQL->freeresult($result);
			
			//after submit
			if (isset($_POST['submit']))
			{
				//empty ..
				/*
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
				*/
					if(($_POST['style'] != $config['style']) || ($_POST['language'] != $config['language']))
					{
						delete_cache('', true); //delete all cache to get new style
					}

					$text	= $lang['CONFIGS_UPDATED'] . '<meta HTTP-EQUIV="REFRESH" content="2; url=' . basename(ADMIN_PATH) . '?cp=options">' . "\n";
					$stylee	= "admin_info";
				//}
				
				//delete cache ..
				delete_cache('data_config');

			}#submit
		
		break; //end false
		
	endswitch;


#End of File
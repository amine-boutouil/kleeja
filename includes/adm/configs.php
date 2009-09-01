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
	$SHOW_CH_STAGE	= isset($_GET['type']) ? false : true;
	$CONFIGEXTEND	= false;

	switch($SHOW_CH_STAGE):
		
		//
		//box of config types
		//
		case true:
		
			$query = array(
							'SELECT'	=> 'DISTINCT(type)',
							'FROM'		=> "{$dbprefix}config",
							'WHERE'		=> '`option` != \'\'',
							'ORDER BY'	=> 'display_order'
					);
					
			
			$result = $SQL->build($query);
			
			$icons_path = $STYLE_PATH_ADMIN . 'images/config_icons/';
			$default_icon = $icons_path . 'default.png';
			$typesnavi = array();

			while($row = $SQL->fetch_array($result))
			{
				$typesnavi[] = array(
								'typename'	=> $row['type'],
								'typelink'	=> $action  . '&amp;type=' . $row['type'],
								'typeicon'	=> file_exists($icons_path . $row['type'] . '.png') ?  $icons_path . $row['type'] . '.png' : $default_icon,
								'typetitle'	=> !empty($lang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])]) ? $lang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])] : (!empty($olang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])]) ? $olang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])] : $lang['CONFIG_KLJ_MENUS_OTHER']),
					 );
			}
			
			
			//default
			$typesnavi[] = array(
									'typename'	=> 'all',
									'typelink'	=> $action  . '&amp;type=all',
									'typeicon'	=> $icons_path . 'all.png',
									'typetitle'	=> $lang['CONFIG_KLJ_MENUS_ALL']
									);
									
			$SQL->freeresult($result);
			
		break; //end true
		
		//
		//page of edit *type* configs
		//
		case false:
			
			//general varaibles
			$action		= basename(ADMIN_PATH) . '?cp=options&amp;type=' . htmlspecialchars($_GET['type']);
			$STAMP_IMG_URL = PATH . 'images/watermark.gif';
			$stylfiles	= $lngfiles	= $authtypes = '';
			$optionss	= array();
			$n_googleanalytics = '<a href="http://www.google.com/analytics">Google Analytics</a>';
			
			$query = array(
							'SELECT'	=> '*',
							'FROM'		=> "{$dbprefix}config",
							'ORDER BY'	=> 'display_order'
						);
			
			if(!$SHOW_CH_STAGE)
			{
				$CONFIGEXTEND	  = $SQL->escape($_GET['type']);
				$CONFIGEXTENDLANG = (!empty($lang['CONFIG_KLJ_MENUS_' . strtoupper($SQL->escape($_GET['type']))]) ? $lang['CONFIG_KLJ_MENUS_' . strtoupper($SQL->escape($_GET['type']))] : ((!empty($olang['CONFIG_KLJ_MENUS_' . strtoupper($SQL->escape($_GET['type']))])) ? $olang['CONFIG_KLJ_MENUS_' . strtoupper($SQL->escape($_GET['type']))] : $lang['CONFIG_KLJ_MENUS_OTHER']));
				if($_GET['type'] != 'all')
				{
					$query['WHERE'] = "type = '" . $SQL->escape($_GET['type']) . "'";
				}
			}
										
			$result = $SQL->build($query);
			$thmb_dim_w =  $thmb_dim_h = 0;
			while($row=$SQL->fetch_array($result))
			{
				//$i++;
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
						'option'		 => '<table><tr>' . "\n" .  
											'<td style="width:40%;border-style:ridge dotted;border-color:#aaa;border-width:1px;"><label for="' . $row['name'] . '">' . (!empty($lang[strtoupper($row['name'])]) ? $lang[strtoupper($row['name'])] : $olang[strtoupper($row['name'])]) . '</label></td>' . "\n" .
											'<td style="width:60%;border-style:ridge dotted;border-color:#aaa;border-width:1px;"><label>' . (empty($row['option']) ? '' : $tpl->admindisplayoption($row['option'])) . '</label></td>' . "\n" .
											'</tr></table>' . "\n",
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
											
						if(isset($_GET['type']) && $_GET['type'] != 'all')
						{
							$query['WHERE'] .= " AND type = '" . $SQL->escape($_GET['type']) . "'";
						}

						$SQL->build($update_query);
					}
			}
			
			$SQL->freeresult($result);
			$types = array();
			
			foreach($optionss as $key => $option)
			{
				if(empty($types[$option['type']]))
				{ 
					if($option['type'] == 'general')
					{
						$types['general'] = '<div class="title_general_conf1gs"><em><h3>' . (!empty($lang['CONFIG_KLJ_MENUS_GENERAL']) ? $lang['CONFIG_KLJ_MENUS_GENERAL'] : ((!empty($olang['CONFIG_KLJ_MENUS_GENERAL'])) ? $olang['CONFIG_KLJ_MENUS_GENERAL'] : $lang['CONFIG_KLJ_MENUS_OTHER'])) . '</h3></em></div>';
					}
					else if($option['type'] != 'general' && $option['type'] != 'other')
					{
						$types[$option['type']] = '<br /><div class="title_general_conf1gs"><em><h3>' . (!empty($lang['CONFIG_KLJ_MENUS_' . strtoupper($option['type'])]) ? $lang['CONFIG_KLJ_MENUS_' . strtoupper($option['type'])] : ((!empty($olang['CONFIG_KLJ_MENUS_' . strtoupper($option['type'])])) ? $olang['CONFIG_KLJ_MENUS_' . strtoupper($option['type'])] : $lang['CONFIG_KLJ_MENUS_OTHER'])) . '</h3></em></div>';
					}
					else if($option['type'] == 'other')
					{
						$types['other'] = '<br /><div class="title_other_conf1gs"><em><h3>' . $lang['CONFIG_KLJ_MENUS_OTHER'] . '</h3></em></div>';
					}
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

			
			//$SQL->freeresult($result);
			
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
					if(isset($_POST['style']) && ($_POST['style'] != $config['style']) || isset($_POST['language']) && ($_POST['language'] != $config['language']))

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
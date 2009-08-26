<?php
	//styles
	//part of admin extensions
	//conrtoll styles and templates 
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}



if(!isset($_GET['sty_t']))
{
	$_GET['sty_t'] = null;
}

switch ($_GET['sty_t']) 
{
		default:
		case "st" :
		
		//for style ..
		$stylee 	= "admin_styles";
		$action 	= basename(ADMIN_PATH) . "?cp=styles&amp;sty_t=st";
		$edit_tpl_action = basename(ADMIN_PATH) . '?cp=styles&amp;sty_t=style_orders&amp;style_id=' . $config['style'] . '&amp;method=1&amp;tpl_choose=';
		$show_all_tpls_action = basename(ADMIN_PATH) . '?cp=styles&amp;style_choose=' . $config['style'] . '&amp;method=1';
		
		//kleeja depend on its users .. and kleeja love them .. so let's tell them about that ..
		$klj_d_s = $lang['KLJ_MORE_STYLES'][rand(0, sizeof($lang['KLJ_MORE_STYLES'])-1)];
		
		//get styles
		$arr = array();
		if ($dh = @opendir($root_path . 'styles'))
		{
				while (($file = readdir($dh)) !== false)
				{
					if(strpos($file, '.') === false && $file != '..' && $file != '.')
					{
						$arr[] = array(	
										'style_name'	=> $file,
										'is_default'	=> $config['style'] == $file ? true : false,
										'link_show_tpls'	=> basename(ADMIN_PATH) . "?cp=styles&amp;sty_t=st&amp;style_choose=" . $file . "&amp;method=1",
										'link_mk_default'	=> basename(ADMIN_PATH) . "?cp=styles&amp;sty_t=st&amp;style_choose=" . $file . "&amp;method=2",
									);
					}
				}
				closedir($dh);
		}
		
		//look is there any template changes for plugins
		//bla bla bla
		
		
		
		//after submit
		if(isset($_REQUEST['style_choose']))
		{
			$style_id = str_replace('..', '', $_REQUEST['style_choose']);
			
			//is there any possiplity to write on files
			$not_style_writeable = true;
			$d_style_path = $root_path . 'styles/' . $style_id; 
			if (!is_writable($d_style_path))
			{
				@chmod($d_style_path, 0777);
				if (is_writable($d_style_path))
				{
					$not_style_writeable = false;
				}
			}
			else
			{
				$not_style_writeable = false;
			}
			
			$lang['STYLE_DIR_NOT_WR'] = sprintf($lang['STYLE_DIR_NOT_WR'], $d_style_path);
			
			
			switch($_REQUEST['method'])
			{
				default:
				case '1': //show templates
				
					//for style ..
					$stylee = "admin_show_tpls";
					$action = basename(ADMIN_PATH) . "?cp=styles&amp;sty_t=style_orders";

					
					//get_tpls
					$tpls_basic = array();
					$tpls_msg = array();
					$tpls_user = array();
					$tpls_other = array();
					if ($dh = @opendir($d_style_path))
					{
							while (($file = readdir($dh)) !== false)
							{
								if($file != '..' && $file != '.' && $file != '.svn' && !is_dir($d_style_path . '/' . $file))
								{
									if(in_array($file, array('header.html', 'footer.html', 'index_body.html')))
									{
										$tpls_basic[] = array( 'template_name'=>  $file );
									}
									else if(in_array($file, array('info.html', 'err.html')))
									{
										$tpls_msg[]	= array( 'template_name'=>  $file );
									}
									else if(in_array($file, array('login.html', 'register.html', 'profile.html', 'get_pass.html', 'fileuser.html', 'filecp.html')))
									{
										$tpls_user[]	= array( 'template_name'=>  $file );
									}
									else
									{
										$tpls_other[] = array( 'template_name'=>  $file );
									}
								}
							}
							closedir($dh);
					}
					
					
				break;
			
				case '2': // make as default
				
					$query_df = array(
										'UPDATE'=> "{$dbprefix}config",
										'SET'	=> "value='" . $SQL->escape($style_id) . "'",
										'WHERE'	=> "name='style'"
										);
											
					$SQL->build($query_df);
									
					
					delete_cache('', true); //delete all cache to get new style
					
					//show msg
					$text = sprintf($lang['STYLE_NOW_IS_DEFAULT'], htmlspecialchars($style_id)) . '<meta HTTP-EQUIV="REFRESH" content="2; url=' . basename(ADMIN_PATH) . '?cp=styles">' ."\n";
					$stylee	= "admin_info";
						
				break;
				
				
			}
		}

		break; 
		
		case "style_orders" :
			
			//style id ..fix for zooz
			$style_id = str_replace('..', '', $_REQUEST['style_id']);
			
			if(empty($_REQUEST['tpl_choose']))
			{
				redirect(basename(ADMIN_PATH) . '?cp=styles&style_choose=' . $style_id . '&method=1');
			}
			
			//edit or del tpl 
			if(isset($_REQUEST['tpl_choose']) && !empty($_REQUEST['tpl_choose']) && isset($_REQUEST['style_id']))
			{
				//tpl name 
				$tpl_name =	$SQL->escape($_REQUEST['tpl_choose']);
				$tpl_path = $root_path . 'styles/' . $style_id . '/' . $tpl_name;
				$d_style_path = $root_path . 'styles/' . $style_id; 
				
				if(!file_exists($tpl_path))
				{
					$text = sprintf($lang['TPL_PATH_NOT_FOUND'], $tpl_path);
					$_REQUEST['method'] = '0';
				}
				else if (!is_writable($d_style_path))
				{
					$text = sprintf($lang['STYLE_DIR_NOT_WR'], $d_style_path);
					$_REQUEST['method'] = '0';
				}
				
				if(!isset($_REQUEST['method']))
				{
					$_REQUEST['method'] = '0';
				}
				
				switch($_REQUEST['method'])
				{	
					case '0':
						$stylee = "admin_info";
					break;
					case '1': //edit tpl
						//for style ..
						$stylee = "admin_edit_tpl";
						$action = basename(ADMIN_PATH) . "?cp=styles&amp;sty_t=style_orders";
						$action_return = basename(ADMIN_PATH) . '?cp=styles&amp;style_choose=' . $style_id . '&amp;method=1';
						
						//is there any possiplity to write on files
						$not_style_writeable = true;
						$d_style_path = $root_path . 'styles/' . $style_id; 
						$lang['STYLE_DIR_NOT_WR'] = sprintf($lang['STYLE_DIR_NOT_WR'], $d_style_path);
						if (!is_writable($d_style_path))
						{
							@chmod($d_style_path, 0777);
							if (is_writable($d_style_path))
							{
								$not_style_writeable = false;
							}
						}
						else
						{
							$not_style_writeable = false;
						}

						$template_content	= file_get_contents($tpl_path);
						$template_content	= htmlspecialchars(stripslashes($template_content));
						
						
					break;
					
					case '2' : //delete tpl
					
							@kleeja_unlink($tpl_path);
								
							//show msg
							$link	= basename(ADMIN_PATH) . '?cp=styles&amp;style_choose=' . $style_id . '&amp;method=1';
							$text	= $lang['TPL_DELETED']  . '<br /> <a href="' . $link . '">' . $lang['GO_BACK_BROWSER'] . '</a><meta HTTP-EQUIV="REFRESH" content="1; url=' . $link . '">' ."\n";
							$stylee	= "admin_info";
							
					break;
				}
			}
			
			// submit edit of tpl
			if(isset($_POST['template_content']))
			{
				$style_id = str_replace('..', '', $_POST['style_id']);
				//tpl name 
				$tpl_name =	htmlspecialchars_decode($_POST['tpl_choose']);
				$tpl_path = $root_path . 'styles/' . $style_id . '/' . $tpl_name;
				$tpl_content = stripslashes($_POST['template_content']);
				
				//try to make template writable
				if (!is_writable($tpl_path)) 
				{
					@chmod($tpl_path, 0777);
				}
				
				//edit template
				if (is_writable($tpl_path)) 
				{
					$filename = @fopen($tpl_path, 'w');
					fwrite($filename, $tpl_content);
					fclose($filename);
				}
				else
				{
					kleeja_admin_err(sprintf($lang['T_ISNT_WRITEABLE'], $tpl_name));
				}


				//delete cache ..
				delete_cache('tpl_' . str_replace('html','php',$tpl_name));
				//show msg
				$link	= basename(ADMIN_PATH) . '?cp=styles&amp;sty_t=style_orders&amp;style_id=' . $style_id . '';
				$text	= $lang['TPL_UPDATED'] . '<br /><meta HTTP-EQUIV="REFRESH" content="3; url=' . $link . '">' ."\n";
				$stylee	= "admin_info";
			}
			
			//new template file
			if(isset($_POST['submit_new_tpl']))
			{
				//style id 
				$style_id = str_replace('..', '', $_POST['style_id']);
				//tpl name 
				$tpl_name =	htmlspecialchars_decode($_POST['new_tpl']);
				$tpl_path = $root_path . 'styles/' . $style_id . '/' . $tpl_name;
			
				$tpl_content = $_POST['template_content'];
				if($filename = @fopen($tpl_path, 'w'))
				{
					@fwrite($filename, $tpl_content);
					@fclose($filename);
				}
				
				$link	= basename(ADMIN_PATH) . '?cp=styles&amp;style_choose=' . $style_id . '&amp;method=1';
				$text	= $lang['TPL_CREATED']  . '<br /> <a href="' . $link . '">' . $lang['GO_BACK_BROWSER'] . '</a><meta HTTP-EQUIV="REFRESH" content="1; url=' . $link . '">' ."\n";
				$stylee	= "admin_info";
			}
		
		break;
		
		
		case 'cached':
		
			$cached_file = $root_path . 'cache/styles_cached.php';
			
			//delete cached styles
			if(isset($_GET['del']))
			{
				delete_cache('styles_cached');
				$text = $lang['CACHED_STYLES_DELETED'];
				$stylee = 'admin_info';
			}
			elseif(!file_exists($cached_file))
			{
				$text = $lang['NO_CACHED_STYLES'];
				$stylee = 'admin_info';
			}
			else
			{
				
				$content = file_get_contents($cached_file);
				$content = base64_decode($content);
				$content = unserialize($content);
				
				ob_start();
				foreach($content as $template_name=>$do)
				{
					
					echo '<strong>' . $lang['OPEN'] . '</strong> : <br /> ' . (substr($template_name, 0, 6) == 'admin_' ? $STYLE_PATH_ADMIN : $STYLE_PATH) . $template_name . '<br />';
					switch($do['action']):
						case 'replace_with':
						

						
							echo '<strong> ' . $lang['SEARCH_FOR'] . '<strong> : <br />';
							//if it's to code
							if(strpos($do['find'], '(.*?)') !== false)
							{
								$do['find'] = explode('(.*?)', $do['find']);
								echo '<textarea style="direction:ltr;width:90%">' . trim($do['find'][0]) . '</textarea> <br />';
									echo '<strong> ' . $lang['REPLACE_TO_REACH'] . '<strong> : <br />';
								echo '<textarea style="direction:ltr;width:90%">' . trim($do['find'][1]) . '</textarea> <br />';
							}
							else
							{
								echo '<textarea style="direction:ltr;width:90%">' . trim($do['find']) . '</textarea> <br />';
							}
							echo '<strong> ' . $lang['REPLACE_WITH'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['action_text']) . '</textarea> <br />'; 
						break;
						case 'add_after':
							echo '<strong> ' . $lang['SEARCH_FOR'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['find']) . '</textarea> <br />';
							echo '<strong> ' . $lang['ADD_AFTER'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['action_text']) . '</textarea> <br />'; 
						break;	
						case 'add_after_same_line':
							echo '<strong> ' . $lang['SEARCH_FOR'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['find']) . '</textarea> <br />';
							echo '<strong> ' . $lang['ADD_AFTER_SAME_LINE'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['action_text']) . '</textarea> <br />'; 
						break;
						case 'add_before':
							echo '<strong> ' . $lang['SEARCH_FOR'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['find']) . '</textarea> <br />';
							echo '<strong> ' . $lang['ADD_BEFORE'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['action_text']) . '</textarea> <br />'; 
						break;	
						case 'add_before_same_line':
							echo '<strong> ' . $lang['SEARCH_FOR'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['find']) . '</textarea> <br />';
							echo '<strong> ' . $lang['ADD_BEFORE_SAME_LINE'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['action_text']) . '</textarea> <br />'; 
						break;
						case 'new':
							echo '<strong> ' . $lang['ADD_IN'] . '<strong> : <br />';
							echo '<textarea style="direction:ltr;width:90%">' . trim($do['action_text']) . '</textarea> <br />'; 
						break;
					endswitch;	
				
					
					echo '<br /><hr /><br />';
				}
								
				$text = ob_get_contents();
				ob_end_clean();

				$text .= '<br /><br /><a href="' . basename(ADMIN_PATH) . '?cp=styles&amp;sty_t=cached&amp;del=1">' . $lang['DELETE_CACHED_STYLES'] . '</a>';  
						
				$stylee = 'admin_info';
			}
		break;
		
}

//<--- EOF
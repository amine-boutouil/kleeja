<?php
/**
*
* @package adm
* @version $Id$
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
$stylee		= "admin_plugins";
$action		= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php');
$no_plugins	= false;

//kleeja depend on its users .. and kleeja love them .. so let's tell them about that ..
$klj_d_s = $lang['KLJ_MORE_PLUGINS'][rand(0, sizeof($lang['KLJ_MORE_PLUGINS'])-1)];

$H_FORM_KEYS	= kleeja_add_form_key('adm_plugins');

//
// Check form key
//
if (isset($_POST['submit_new_plg']))
{
	if(!kleeja_check_form_key('adm_plugins'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}

//get plugins
$query = array(
				'SELECT'	=> '*',
				'FROM'		=> "{$dbprefix}plugins"
			);

$result = $SQL->build($query);
		
if($SQL->num_rows($result)>0)
{
	$arr = array();

	while($row=$SQL->fetch_array($result))
	{
		$arr[]	= array(
						'plg_id'		=> $row['plg_id'],
						'plg_name'		=> $row['plg_name'] . ($row['plg_disabled'] == 1 ? ' [x]': ''),
						'plg_disabled'	=> (int) $row['plg_disabled'] == 1 ? true : false,
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

			$action	= (int) $_GET['m'] == 1 ? 1 : 0;
			
			//check if there is style require this plugin
			if($action == 1)
			{
				if(($style_info = kleeja_style_info($config['style'])) != false)
				{
					$plugins_required = array_map('trim', explode(',', $style_info['plugins_required']));
					if(in_array($_GET['pn'], $plugins_required))
					{
						kleeja_admin_err($lang['PLUGIN_REQ_BY_STYLE_ERR']);
					}
				}
			}
			
			//update
			$update_query = array(
									'UPDATE'	=> "{$dbprefix}plugins",
									'SET'		=> "plg_disabled = $action",
									'WHERE'		=> "plg_id=" . $plg_id
							);

			$SQL->build($update_query);
			if($SQL->affected())
			{
				delete_cache('data_hooks');
			}		

			//show msg
			$text = $lang['PLGUIN_DISABLED_ENABLED'];
			$text .= '<meta HTTP-EQUIV="REFRESH" content="1; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";
			$stylee	= "admin_info";

		break;
		
		//Delete plguin
		case '3': 
		
			//check if there is style require this plugin
			if(($style_info = kleeja_style_info($config['style'])) != false)
			{
				$plugins_required = array_map('trim', explode(',', $style_info['plugins_required']));
				if(in_array($_GET['pn'], $plugins_required))
				{
					kleeja_admin_err($lang['PLUGIN_REQ_BY_STYLE_ERR']);
				}
			}

			//before delete we have look for unistalling 
			$query	= array(
							'SELECT'	=> 'plg_uninstall',
							'FROM'		=> "{$dbprefix}plugins",
							'WHERE'		=> "plg_id=" . $plg_id
						);

			$result = $SQL->fetch_array($SQL->build($query));

			if(trim($result['plg_uninstall']) != '')
			{
				eval($result['plg_uninstall']);
			}
							
			$query_del	= array(
								'DELETE'	=> "{$dbprefix}plugins",
								'WHERE'		=> "plg_id=" . $plg_id 
							);

			$SQL->build($query_del);

			$query_del2 = array(
								'DELETE'	=> "{$dbprefix}hooks",
								'WHERE'		=> "plg_id=" . $plg_id
							);		

			$SQL->build($query_del2);
			
			$query_del3 = array(
								'DELETE'	=> "{$dbprefix}lang",
								'WHERE'		=> "plg_id=" . $plg_id
							);		

			$SQL->build($query_del3);
			
			$query_del4 = array(
								'DELETE'	=> "{$dbprefix}config",
								'WHERE'		=> "plg_id=" . $plg_id
							);		

			$SQL->build($query_del4);



			//delete caches ..
			delete_cache('data_hooks');
			delete_cache('data_config');

			//show msg
			$text = $lang['PLUGIN_DELETED'] . '<meta HTTP-EQUIV="REFRESH" content="1; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";
			$stylee	= "admin_info";

		break;
		case '4': //plugin instructions
			$query	= array(
							'SELECT'	=> 'plg_instructions',
							'FROM'		=> "{$dbprefix}plugins",
							'WHERE'		=> "plg_id=" . $plg_id
						);

			$result = $SQL->fetch_array($SQL->build($query));
			$result  = $result['plg_instructions'];

			if(empty($result)) //no instructions
			{
				redirect(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));
			}
	
			$info = unserialize(kleeja_base64_decode($result));
			kleeja_admin_info($info[$config['language']]);
		break;
		case '5': //plugins exporting
			if(!isset($plg_id))
			{
				kleeja_admin_err($lang['ERROR']);
			}
			
			
			//get plugin information
			$query = array(
				'SELECT'	=> '*',
				'FROM'		=> "{$dbprefix}plugins",
				'WHERE'		=> "plg_id=" . $plg_id
			);

			$result = $SQL->build($query);
		
			if($SQL->num_rows($result)>0)
			{
				$arr = array();

				$row=$SQL->fetch_array($result);
				
				
				//start xml
				//header('Content-Type: text/xml');
				$name = $row['plg_name'] . '-' . str_replace('.', '-', $row['plg_ver']) . '.xml';

				if (is_browser('mozilla'))
				{
					$h_name = "filename*=UTF-8''" . rawurlencode(htmlspecialchars_decode($name));
				}
				else if (is_browser('opera, safari, konqueror'))
				{
					$h_name = 'filename="' . str_replace('"', '', htmlspecialchars_decode($name)) . '"';
				}
				else
				{
					$h_name = 'filename="' . rawurlencode(htmlspecialchars_decode($name)) . '"';
				}

				if (@ob_get_length())
				{
					@ob_end_clean();
				}

				// required for IE, otherwise Content-Disposition may be ignored
				if(@ini_get('zlib.output_compression'))
				{
					@ini_set('zlib.output_compression', 'Off');
				}
				
				header('Pragma: public');
				header('Content-Type: text/xml');
				header('X-Download-Options: noopen');
				header('Content-Disposition: attachment; '  . $h_name);

				echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
				echo '<kleeja>' . "\n";
				echo '<info>' . "\n";
				echo '	<plugin_name>' . $row['plg_name'] . '</plugin_name>' . "\n";
				echo '	<plugin_version>' . $row['plg_ver'] . '</plugin_version>' . "\n";
				echo '	<plugin_description>' . $row['plg_dsc'] . '</plugin_description>' . "\n";
				echo '	<plugin_author>' . $row['plg_author'] . '</plugin_author>' . "\n";
				echo '	<plugin_kleeja_version>' . KLEEJA_VERSION . '</plugin_kleeja_version>' . "\n";
				echo '</info>' . "\n\n";
				
				if(!empty($row['plg_instructions']))
				{
					echo '<instructions>' . "\n";
					$inst = unserialize(kleeja_base64_decode($row['plg_instructions']));
					foreach($inst as $lang => $instruction)
					{
						echo '	<instruction lang="' . $lang . '"><![CDATA[' . "\n	" . $instruction  . "\n" . '	]]></instruction>' . "\n";
					}
					echo '</instructions>' . "\n\n";
				}
				
				echo '<uninstall><![CDATA[' . "\n";
				echo $row['plg_uninstall'] . "\n";
				echo ']]></uninstall>' . "\n\n";
				
				echo $row['plg_store'] . "\n\n";
				
				
				$querylang = $SQL->build(array('SELECT'	=> 'DISTINCT(lang_id)', 
												'FROM'	=> "{$dbprefix}lang",
												'WHERE' => "plg_id=" . $plg_id));
				
				if($SQL->num_rows($querylang)>0)
				{
					echo '<phrases>' . "\n";
					while($phrases=$SQL->fetch_array($querylang))
					{
						echo ' <lang name="' . $phrases['lang_id'] . '">' . "\n";
						$queryp = $SQL->build(array(
													'SELECT'	=> '*',
						 							'FROM'		=> "{$dbprefix}lang",
													'WHERE'		=> "plg_id='" . $plg_id . "' AND lang_id='" . $phrases['lang_id'] . "'"
													));
						while($phrase=$SQL->fetch_array($queryp))
						{
							echo '	 <phrase name="' . $phrase['word'] . '">' . $phrase['trans'] . '</phrase>' . "\n";
						}
						echo ' </lang>' . "\n";
					}
					echo '</phrases>' . "\n\n";
				}
				
				
				$queryconfig = $SQL->build(array('SELECT'	=> '*', 
												'FROM'	=> "{$dbprefix}config",
												'WHERE' => "plg_id=" . $plg_id));
				
				if($SQL->num_rows($querylang)>0)
				{
					echo '<options>' . "\n";
					while($config=$SQL->fetch_array($queryconfig))
					{
						echo '	<option name="' . $config['name'] . '" value="' . $config['value'] . '" order="' . $config['display_order'] . '" menu="' . $config['type'] . '"><![CDATA[' . "\n	" . $config['option'] . "\n" . '	]]></option>' . "\n";
					}
					echo '</options>' . "\n\n";
				}
				
				$queryhooks = $SQL->build(array('SELECT'	=> '*', 
												'FROM'	=> "{$dbprefix}hooks",
												'WHERE' => "plg_id=" . $plg_id));
				
				if($SQL->num_rows($queryhooks)>0)
				{
					echo '<hooks>' . "\n";
					while($hook=$SQL->fetch_array($queryhooks))
					{
						echo '	<hook name="' . $hook['hook_name'] . '"><![CDATA[' . "\n	" . $hook['hook_content'] . "\n" . '	]]></hook>' . "\n";
					}
					echo '</hooks>' . "\n\n";
				}
				
				echo '</kleeja>';
			}
			else
			{
				kleeja_admin_err($lang['ERROR']);
			}
			exit;
		break;
	}
}

//new style from xml
if(isset($_POST['submit_new_plg']))
{
	$text	= '';
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
	kleeja_unlink($_FILES['imp_file']['tmp_name']);

	// Are there contents?
	if(!trim($contents))
	{
		kleeja_admin_err($lang['ERR_UPLOAD_XML_NO_CONTENT'],true,'',true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));
	}

	if(empty($text))
	{
		$return = creat_plugin_xml($contents);

		if($return === true)
		{
			$text = $lang['NEW_PLUGIN_ADDED'] . '<meta HTTP-EQUIV="REFRESH" content="3; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";
		}
		else if ($return === 'xyz')//exists before
		{
			kleeja_admin_err($lang['PLUGIN_EXISTS_BEFORE'],true,'',true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));			
		}
		else if ($return === 'upd') // updated success
		{
			$text = $lang['PLUGIN_UPDATED_SUCCESS'] . '<meta HTTP-EQUIV="REFRESH" content="3; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";			
		}
		else
		{
			kleeja_admin_err($lang['ERR_IN_UPLOAD_XML_FILE'],true,'',true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));	
		}
	}		

	$stylee	= "admin_info";
}

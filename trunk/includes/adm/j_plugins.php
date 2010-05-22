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

//
//todo : 
// imporve FTP, it's enabled for developers now, so it want alot of testing


include PATH . 'includes/plugins.php';
$plg = new kplugins;

//check methods of files handler, if there is nothing of them, so 
//we have disable uploading.
$there_is_files_method = false;
if($plg->f_method != '')
{
	$there_is_files_method = $plg->f_method;

	//return values of ftp from config, if not get suggested one 
	$ftp_info = array('host', 'user', 'pass', 'path', 'port');

	if(!empty($config['ftp_info']))
	{
		$ftp_info = @unserialize($config['ftp_info']);
	}
	else
	{
		//todo : make sure to figure this from OS, and some other things
		$ftp_info['path'] = '/public_html' . str_replace('/admin', '', dirname($_SERVER['PHP_SELF'])) . '/';
		$ftp_info['port'] = 21;
	}
}

$is_ftp_supported = $plg->is_ftp_supported;


//clean changes files
if(isset($_GET['cc'])):

if ($dh = @opendir(PATH . 'cache'))
{
	while (($file = @readdir($dh)) !== false)
	{

		if(preg_match('!changes_of_[a-z0-9]+.zip!', $file))
		{
			kleeja_unlink(PATH . 'cache/' . $file);
		}
	}
	@closedir($dh);
}

$cache->clean('__changes_files__');

redirect(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));


//show first page of plugins
elseif (!isset($_GET['do_plg'])):

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
				'SELECT'	=> 'p.plg_id, p.plg_name, p.plg_disabled, p.plg_ver, p.plg_ver, p.plg_author, p.plg_dsc, p.plg_instructions',
				'FROM'		=> "{$dbprefix}plugins p"
			);

$result = $SQL->build($query);
		
if($SQL->num_rows($result)>0)
{
	$arr = array();

	while($row=$SQL->fetch_array($result))
	{
		$desc = unserialize(kleeja_base64_decode($row['plg_dsc']));

		$arr[]	= array(
						'plg_id'			=> $row['plg_id'],
						'plg_name'			=> str_replace('-', ' ', $row['plg_name']) . ($row['plg_disabled'] == 1 ? ' [ x ]': ''),
						'plg_disabled'		=> (int) $row['plg_disabled'] == 1 ? true : false,
						'plg_ver'			=> $row['plg_ver'],
						'plg_author'		=> $row['plg_author'],
						'plg_dsc'			=> isset($desc[$config['language']]) ? $desc[$config['language']] : $desc['en'],
						'plg_instructions'	=> trim($row['plg_instructions']) == '' ? false : true,
				);
	}
}
else
{
	$no_plugins	=	true;
}


$SQL->freeresult($result);



//is there any changes from recent installing plugins
if (!($changes_files = $cache->get('__changes_files__')))
{
	if ($dh = @opendir(PATH . 'cache'))
	{
		while (($file = @readdir($dh)) !== false)
		{

			if(preg_match('!changes_of_[a-z0-9]+.zip!', $file))
			{
				$changes_files[] = array(	
								'file'	=> $file,
								'path'	=> basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;do_plg=1&amp;m=6&amp;fn=' . str_replace(array('changes_of_', '.zip'),'', $file),
							);
			}
		}
		@closedir($dh);
	}
	$cache->save('__changes_files__', $changes_files);
}

$is_there_changes_files = empty($changes_files) ? false : true;


//after submit 
else:

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
				delete_cache(array('data_plugins', 'data_config'));
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

			$stylee		= "admin_plugin_mfile";
			$action		= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;m=3&amp;un=1&amp;pn=' . htmlspecialchars($_GET['pn']) . '&amp;do_plg=' . $plg_id;
			$for_unistalling = true;

			//after submit
			if(isset($_GET['un']) || $plg->f_method == 'kfile')
			{
			
				if(isset($_POST['_fmethod']) && $_POST['_fmethod'] == 'kftp')
				{
					if(empty($_POST['ftp_host']) || empty($_POST['ftp_port']) || empty($_POST['ftp_user']) ||empty($_POST['ftp_pass']))
					{
						kleeja_admin_err($lang['EMPTY_FIELDS'], true,'', true, str_replace('un=1', '', $action));
					}
					else
					{
						
						$plg->info = $ftpinfo = array('host'=>$_POST['ftp_host'], 'port'=>$_POST['ftp_port'], 'user'=>$_POST['ftp_user'], 'pass'=>$_POST['ftp_pass'], 'path'=>$_POST['ftp_path']);

						$ftpinfo['pass'] = '';
						update_config('ftp_info', serialize($ftpinfo), false);
						
						if(!$plg->check_connect())
						{
							kleeja_admin_err($lang['LOGIN_ERROR'], true,'', true, str_replace('un=1', '', $action));
						}
					}
				}
				else if(isset($_POST['_fmethod']) && $_POST['_fmethod'] == 'zfile')
				{
					$plg->f_method = 'zfile';
					$plg->check_connect();
				}

			
				//before delete we have look for unistalling 
				$query	= array(
								'SELECT'	=> 'plg_uninstall, plg_files',
								'FROM'		=> "{$dbprefix}plugins",
								'WHERE'		=> "plg_id=" . $plg_id
							);

				$result = $SQL->fetch_array($SQL->build($query));
	
				//do uninstalling codes
				if(trim($result['plg_uninstall']) != '')
				{
					eval($result['plg_uninstall']);
				}

				//delete files of plugin
				if(trim($result['plg_files']) != '')
				{
					$plg->delete_files(@unserialize(kleeja_base64_decode($result['plg_files'])));
				}

				//delete some data in Kleeja tables
				$delete_from_tables = array('plugins', 'hooks', 'lang', 'config');
				foreach($delete_from_tables as $table)
				{
					$query_del	= array(
										'DELETE'	=> "{$dbprefix}{$table}",
										'WHERE'		=> "plg_id=" . $plg_id 
									);

					$SQL->build($query_del);
				}

				//delete caches ..
				delete_cache(array('data_plugins', 'data_config'));

				$plg->atend();
				
				if(empty($plg->zipped_files))
				{
					$text = $lang['PLUGIN_DELETED'] . '<meta HTTP-EQUIV="REFRESH" content="1; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";
				}
				else
				{
					$text = sprintf($lang['PLUGIN_DELETED_ZIPPED'], '<a href="' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;do_plg=' . $plg->plg_id . '&amp;m=6&amp;fn=' . $plg->zipped_files . '">', '</a>');
					$text .= '<br /><br /><a href="' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . $lang['GO_BACK_BROWSER'] . '</a>';
				}

				$stylee	= "admin_info";
			}

		break;
		case '4': //plugin instructions
			$query	= array(
							'SELECT'	=> 'p.plg_name, p.plg_ver, p.plg_instructions',
							'FROM'		=> "{$dbprefix}plugins p",
							'WHERE'		=> "p.plg_id=" . $plg_id
						);

			$result = $SQL->fetch_array($SQL->build($query));


			if(empty($result)) //no instructions
			{
				redirect(basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));
			}
	
			$info = unserialize(kleeja_base64_decode($result['plg_instructions']));
			$info = isset($info[$config['language']]) ? $info[$config['language']] : $info['en'];
			kleeja_admin_info(
							'<h3>' . $result['plg_name'] . ' &nbsp;' . $result['plg_ver']  . ' : </h3>' . 
							$info . '<br /><a href="' . basename(ADMIN_PATH) . '?cp=' .
							basename(__file__, '.php') . '">' . $lang['GO_BACK_BROWSER'] . '</a>'
							);

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
				$name = $row['plg_name'] . '-' . str_replace('.', '-', $row['plg_ver']) . '.klj';

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
				
				function clean_xml($str)
				{
					 return str_replace(array('"', "'", '<', '>', '&'), array('&quot;', '&apos;', '&lt;', '&gt;', '&amp;'), $str);
				}
				
				function clean_xml_cdata($str)
				{
					return str_replace(array('<![CDATA', ']]>'), array('&lt;![CDATA', ']]&gt;'), $str);
				}
				
				echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
				echo '<kleeja>' . "\n";
				echo "\t" . '<info>' . "\n";
				echo "\t\t" . '<plugin_name>' . clean_xml($row['plg_name']) . '</plugin_name>' . "\n";
				echo "\t\t" . '<plugin_version>' . clean_xml($row['plg_ver']) . '</plugin_version>' . "\n";
				echo "\t\t" . '<plugin_description>' . "\n";
				$plgdsc = unserialize(kleeja_base64_decode($row['plg_dsc']));
				foreach($plgdsc as $ln => $cn)
				{
					echo "\t\t\t" . '<description lang="' . $ln . '">' . clean_xml($cn) . '</description>' . "\n";
				}
				echo "\t\t" . '</plugin_description>' . "\n";
				echo "\t\t" . '<plugin_author>' . clean_xml($row['plg_author']) . '</plugin_author>' . "\n";
				echo "\t\t" . '<plugin_kleeja_version>' . clean_xml(KLEEJA_VERSION) . '</plugin_kleeja_version>' . "\n";
				echo "\t" . '</info>' . "\n";
				
				if(!empty($row['plg_instructions']))
				{
					echo  "\t" . '<instructions>' . "\n";
					$inst = unserialize(kleeja_base64_decode($row['plg_instructions']));
					foreach($inst as $lang => $instruction)
					{
						echo  "\t\t" . '<instruction lang="' . $lang . '"><![CDATA[' .  clean_xml_cdata($instruction)  . ']]></instruction>' . "\n";
					}
					echo  "\t" . '</instructions>' . "\n";
				}
				
				echo "\t" . '<uninstall><![CDATA[' . "\n";
				echo clean_xml_cdata($row['plg_uninstall']);
				echo "\t" . ']]></uninstall>' . "\n";

				echo "\t" . $row['plg_store'] . "\n";

				$querylang = $SQL->build(array(
												'SELECT'=> 'DISTINCT(lang_id)', 
												'FROM'	=> "{$dbprefix}lang",
												'WHERE' => "plg_id=" . $plg_id
											));

				if($SQL->num_rows($querylang)>0)
				{
					echo "\t" . '<phrases>' . "\n";
					while($phrases=$SQL->fetch_array($querylang))
					{
						echo "\t\t" . '<lang name="' . $phrases['lang_id'] . '">' . "\n";

						$queryp = $SQL->build(array(
													'SELECT'	=> '*',
						 							'FROM'		=> "{$dbprefix}lang",
													'WHERE'		=> "plg_id='" . $plg_id . "' AND lang_id='" . $phrases['lang_id'] . "'"
												));

						while($phrase=$SQL->fetch_array($queryp))
						{
							echo "\t\t\t" . '<phrase name="' . $phrase['word'] . '"><![CDATA[' . clean_xml_cdata($phrase['trans']) . ']]></phrase>' . "\n";
						}
						echo "\t\t" . '</lang>' . "\n";
					}
					echo "\t" . '</phrases>' . "\n";
				}

				$queryconfig = $SQL->build(array(
												'SELECT'	=> '*', 
												'FROM'	=> "{$dbprefix}config",
												'WHERE' => "plg_id=" . $plg_id)
												);

				if($SQL->num_rows($queryconfig)>0)
				{
					echo "\t" . '<options>' . "\n";
					while($config=$SQL->fetch_array($queryconfig))
					{
						echo "\t\t" . '<option name="' . $config['name'] . '" value="' . $config['value'] . '" order="' . $config['display_order'] . '" menu="' . $config['type'] . '"><![CDATA[' . clean_xml_cdata($config['option']) . ']]></option>' . "\n";
					}
					echo "\t" . '</options>' . "\n";
				}

				$queryhooks = $SQL->build(array(
												'SELECT'	=> '*', 
												'FROM'		=> "{$dbprefix}hooks",
												'WHERE'		=> "plg_id=" . $plg_id
											));

				if($SQL->num_rows($queryhooks)>0)
				{
					echo "\t" . '<hooks>' . "\n";
					while($hook=$SQL->fetch_array($queryhooks))
					{
						echo "\t\t" . '<hook name="' . $hook['hook_name'] . '"><![CDATA[' . clean_xml_cdata($hook['hook_content']) . ']]></hook>' . "\n";
					}
					echo "\t" . '</hooks>' . "\n";
				}
				
				if(!empty($row['plg_files']))
				{
					$nfiles = unserialize(kleeja_base64_decode($row['plg_files']));
					echo "\t" . '<files>' . "\n";
					foreach($nfiles as $f)
					{
						if($f[0] == '/')
						{
							$f = substr($f, 1);
						}
						echo "\t\t" . '<file path="' . $f . '"><![CDATA[' . kleeja_base64_encode(@file_get_contents(PATH . $f)) . ']]></file>' . "\n";
					}
					echo "\t" . '</files>' . "\n";
				}

				echo '</kleeja>';
				exit;
			}
			else
			{
				kleeja_admin_err($lang['ERROR']);
			}

		break;
		
		//downaloding zipped changes ..
		case 6:

			if(!isset($_GET['fn']))
			{
				kleeja_admin_err($lang['ERROR']);
			}

			$_f		= preg_replace('![^a-z0-9]!', '', $_GET['fn']);
			$name	= 'changes_of_' . $_f . '.zip';

			if(!file_exists(PATH . 'cache/' . $name))
			{
				kleeja_admin_err($lang['ERROR']);
			}

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
			header('Content-Type: application/zip');
			header('X-Download-Options: noopen');
			header('Content-Disposition: attachment; '  . $h_name);
			
			echo file_get_contents(PATH . 'cache/' . $name);
			
		break;
	}

endif;//else submit


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
		if(isset($_POST['_fmethod']) && $_POST['_fmethod'] == 'kftp')
		{
			if(empty($_POST['ftp_host']) || empty($_POST['ftp_port']) || empty($_POST['ftp_user']) ||empty($_POST['ftp_pass']))
			{
				kleeja_admin_err($lang['EMPTY_FIELDS'], true,'', true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));
			}
			else
			{
				
				$plg->info = $ftpinfo = array('host'=>$_POST['ftp_host'], 'port'=>$_POST['ftp_port'], 'user'=>$_POST['ftp_user'], 'pass'=>$_POST['ftp_pass'], 'path'=>$_POST['ftp_path']);

				$ftpinfo['pass'] = '';
				update_config('ftp_info', serialize($ftpinfo), false);
				
				if(!$plg->check_connect())
				{
					kleeja_admin_err($lang['LOGIN_ERROR'], true,'', true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));
				}
			}
		}
		else if(isset($_POST['_fmethod']) && $_POST['_fmethod'] == 'zfile')
		{
			$plg->f_method = 'zfile';
			$plg->check_connect();
			
		}

		$return = $plg->add_plugin($contents);

		$plg->atend();
		
		switch($return)
		{
			//plugin added
			case 'done':
				$text = $lang['NEW_PLUGIN_ADDED'] . '<meta HTTP-EQUIV="REFRESH" content="3; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";
			break;
			case 'xyz': //exists before
				kleeja_admin_err($lang['PLUGIN_EXISTS_BEFORE'],true,'',true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));			
			break;
			case 'upd': // updated success
				$text = $lang['PLUGIN_UPDATED_SUCCESS'] . '<meta HTTP-EQUIV="REFRESH" content="3; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . "\n";			
			break;
			case 'inst':
				$text = $lang['NEW_PLUGIN_ADDED'] . '<meta HTTP-EQUIV="REFRESH" content="1; url=' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;do_plg=' . $plg->plg_id . '&amp;m=4">' . "\n";
			break;
			case 'zipped':
				$text = sprintf($lang['PLUGIN_ADDED_ZIPPED'], '<a href="' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;do_plg=' . $plg->plg_id . '&amp;m=6&amp;fn=' . $plg->zipped_files . '">', '</a>');
				$text .= '<br /><br /><a href="' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . $lang['GO_BACK_BROWSER'] . '</a>';
			break;
			case 'zipped/inst':
				$text = sprintf($lang['PLUGIN_ADDED_ZIPPED_INST'], 
								'<a href="' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;do_plg=' . $plg->plg_id . '&amp;m=6&amp;fn=' . $plg->zipped_files . '">',
								'</a>',
								'<a href="' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;do_plg=' . $plg->plg_id . '&amp;m=4">',
								'</a>'
								);
				$text .= '<br /><br /><a href="' . basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '">' . $lang['GO_BACK_BROWSER'] . '</a>';
			break;
			default:
				kleeja_admin_err($lang['ERR_IN_UPLOAD_XML_FILE'],true,'',true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));	
		}
	}
	
	$cache->clean('__changes_files__');
	
	$stylee	= "admin_info";
}

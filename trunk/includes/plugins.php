<?php
/**
*
* @package Kleeja
* @version $Id: pager.php 1251 2009-11-13 22:48:20Z altar3q $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/

class kplugins
{

	//everytime we install plugin
	//we ask user for this..
	var $info		= array();
	var $f_method	= '';
	var $f			= null;

	function kplugins()
	{
		//check for the best method of files handling
		$f_method = '';
		$disabled_functions = explode(',', @ini_get('disable_functions'));

		if(is_writable(PATH))
		{
			$this->f_method = 'kfile';
		}
		else if (@extension_loaded('ftp'))
		{
			$this->f_method = 'kftp';
		}
		else if (!in_array('fsockopen', $disabled_functions))
		{
			//not supported yet !
			//$this->f_method = 'kfsock';
		}
	}

	function check_connect()
	{
		$this->f = new $this->f_method;
		if($this->f->_open($this->info))
		{
			return false;
		}
	}
	
	function atend()
	{
		if(!empty($this->f))
		{
			$this->f->_close();
		}
	}

	function add_plugin($contents)
	{
		global $dbprefix, $SQL, $lang, $config, $STYLE_PATH_ADMIN , $STYLE_PATH, $THIS_STYLE_PATH, $olang;


		//parse xml content
		$XML = new kxml;

		$gtree = $XML->xml_to_array($contents);
		
		$tree				= empty($gtree['kleeja']) ? null : $gtree['kleeja'];
		$plg_info			= empty($tree['info']) ? null : $tree['info'];
		$plg_install		= empty($tree['install']) ? null : $tree['install'];
		$plg_uninstall		= empty($tree['uninstall']) ? null : $tree['uninstall'];
		$plg_tpl			= empty($tree['templates']) ? null : $tree['templates'];		
		$plg_hooks			= empty($tree['hooks']) ? null : $tree['hooks'];		
		$plg_langs			= empty($tree['langs']) ? null : $tree['langs'];
		$plg_updates		= empty($tree['updates']) ? null : $tree['updates'];
		$plg_instructions 	= empty($tree['instructions']) ? null : $tree['instructions'];
		$plg_phrases		= empty($tree['phrases']) ? null : $tree['phrases'];
		$plg_options		= empty($tree['options']) ? null : $tree['options'];

		//important tags not exists 
		if(empty($plg_info))
		{
			big_error('Error',$lang['ERR_XML_NO_G_TAGS']);
		}

		if(!empty($plg_info['plugin_kleeja_version']['value']) && version_compare(strtolower($plg_info['plugin_kleeja_version']['value']), strtolower(KLEEJA_VERSION), '>=') == false)
		{
			big_error('Error', $lang['PLUGIN_N_CMPT_KLJ']);
		}

		$plg_errors	=	array();
		$plg_new = true;

		$plugin_name = preg_replace("/[^a-z0-9-_]/", "-", strtolower($plg_info['plugin_name']['value']));

		//is this plugin exists before ! 
		$is_query = array(
							'SELECT'	=> 'plg_id, plg_name, plg_ver',
							'FROM'		=> "{$dbprefix}plugins",
							'WHERE'		=> 'plg_name="' . $plugin_name . '"' 
						);
		($hook = kleeja_run_hook('qr_chk_plginfo_crtplgxml_func')) ? eval($hook) : null; //run hook
		$res = $SQL->build($is_query);
	
		if($SQL->num_rows($res))
		{
			//it's not new one ! , let's see if it same version
			$plg_new = false;
			$cur_ver = $SQL->fetch_array($res);
			$plg_id = $cur_ver['plg_id'];
			$cur_ver = $cur_ver['plg_ver'];
			$new_ver = $SQL->escape($plg_info['plugin_version']['value']);
			if (version_compare(strtolower($cur_ver), strtolower($new_ver), '>='))
			{
				return 'xyz';
			}
			else if (!empty($plg_updates))
			{	
				if(is_array($plg_updates['update']))
				{
					if(array_key_exists("attributes", $plg_updates['update']))
					{
						$plg_updates['update'] = array($plg_updates['update']);
					}
				}

				foreach($plg_updates['update'] as $up)
				{
					if (version_compare(strtolower($cur_ver), strtolower($up['attributes']['to']), '<'))
					{
						eval($up['value']);
					}
				}
			}
		}

		if(isset($plg_instructions))
		{
			if(is_array($plg_instructions['instruction']))
			{
				if(array_key_exists("attributes", $plg_instructions['instruction']))
				{
					$plg_instructions['instruction'] = array($plg_instructions['instruction']);
				}
			}
	
			$instarr = array();		
			foreach($plg_instructions['instruction'] as $in)
			{
				if(empty($in['attributes']['lang']) || !isset($in['attributes']['lang']))
				{
					big_error('Error',$lang['ERR_XML_NO_G_TAGS']);
				}

				$instarr[$in['attributes']['lang']] = $in['value'];
			}
		}

		//if there is instructions
		$there_is_intruct = false;
		if(isset($instarr) && !empty($instarr))
		{
			$there_is_intruct = true;
		}
	
		//store important tags (for now only "install" and "templates" tags)
		$store = '';

		//storing unreached elements
		if (isset($plg_install) && trim($plg_install['value']) != '')
		{
			$store .= '<install><![CDATA[' . $plg_install['value'] . ']]></install>' . "\n\n";
		}

		if (isset($plg_updates))
		{
			$updates 	 =  explode("<updates>", $contents);
			$updates 	 =  explode("</updates>", $updates[1]);
			$store 		.= '<updates>' . $updates[0] . '</updates>' . "\n\n";
		}

		if (isset($plg_tpl))
		{
			$templates 	 =  explode("<templates>", $contents);
			$templates 	 =  explode("</templates>", $templates[1]);
			$store 		.= '<templates>' . $templates[0] . '</templates>' . "\n\n";
		}

		//if the plugin was new 
		if($plg_new)
		{
			//insert in plugin table 
			$insert_query = array(
								'INSERT'	=> 'plg_name, plg_ver, plg_author, plg_dsc, plg_uninstall, plg_instructions, plg_store',
								'INTO'		=> "{$dbprefix}plugins",
								'VALUES'	=> "'" . $SQL->escape($plugin_name) . "','" . $SQL->escape($plg_info['plugin_version']['value']) . 
												"','" . $SQL->escape($plg_info['plugin_author']['value']) . "','" . 
												$SQL->escape($plg_info['plugin_description']['value']) . "','" . $SQL->real_escape($plg_uninstall['value']) . "','" . 
												((isset($instarr) && !empty($instarr)) ? $SQL->escape(kleeja_base64_encode(serialize($instarr))) : '') . "','" .  
												$SQL->real_escape($store) . "'"
							);

			($hook = kleeja_run_hook('qr_insert_plugininfo_crtplgxml_func')) ? eval($hook) : null; //run hook
			
			$SQL->build($insert_query);
		
			$new_plg_id	=	$SQL->insert_id();
		}
		else //if it was just update proccess
		{
			$update_query = array(
				
								'UPDATE'	=> "{$dbprefix}plugins",
								'SET'		=> 'plg_ver="' . $new_ver . '", plg_author="' . $SQL->escape($plg_info['plugin_author']['value']) . 
												'", plg_dsc="' . $SQL->escape($plg_info['plugin_description']['value']) . '", plg_uninstall="' . 
												$SQL->real_escape($plg_uninstall['value']) . '", plg_instructions="' .
												($there_is_intruct ? $SQL->escape(kleeja_base64_encode(serialize($instarr))) : '') . 
												'", plg_store="' . $SQL->escape($store) . '"',
								'WHERE'		=> "plg_id=" . $plg_id
						);

			($hook = kleeja_run_hook('qr_update_plugininfo_crtplgxml_func')) ? eval($hook) : null; //run hook
			
			$SQL->build($update_query);
			$new_plg_id	= $plg_id;
		}
	
		//eval install code
		if (isset($plg_install) && trim($plg_install['value']) != '' && $plg_new)
		{
			eval($plg_install['value']);
		}

		if(isset($plg_phrases))
		{
			if(is_array($plg_phrases['lang']))
			{
				if(array_key_exists("attributes", $plg_phrases['lang']))
				{
					$plg_phrases['lang'] = array($plg_phrases['lang']);
				}
			}

			$phrases = array();		
			foreach($plg_phrases['lang'] as $in)
			{
				if(empty($in['attributes']['name']) || !isset($in['attributes']['name']))
				{
					big_error('Error', $lang['ERR_XML_NO_G_TAGS']);
				}

				//first we create a new array that can carry language phrases
				$phrases[$in['attributes']['name']] = array();

				if(is_array($in['phrase']))
				{
					if(array_key_exists("attributes", $in['phrase']))
					{
						$in['phrase'] = array($in['phrase']);
					}
				}
				
				//get phrases value
				foreach($in['phrase'] as $phrase)
				{
					$phrases[$in['attributes']['name']][$phrase['attributes']['name']] = $phrase['value'];
				}

				//finally we add it to the database
				add_olang($phrases[$in['attributes']['name']], $in['attributes']['name'], $new_plg_id);
			}
		}

		if(isset($plg_options))
		{
			if(is_array($plg_options['option']))
			{
				if(array_key_exists("attributes", $plg_options['option']))
				{
					$plg_options['option'] = array($plg_options['option']);
				}
			}

			foreach($plg_options['option'] as $in)
			{
				add_config($in['attributes']['name'], $in['attributes']['value'], $in['attributes']['order'], $in['value'], $in['attributes']['menu'], $new_plg_id);
			}

			delete_cache('data_config');
		}

		//cache important instruction
		$cached_instructions = array();

		//some actions with tpls
		if(isset($plg_tpl))
		{
			//edit template
			if(isset($plg_tpl['edit']))
			{
				include_once "s_strings.php";
				$finder	= new sa_srch;
							
				if(is_array($plg_tpl['edit']['template']))
				{
					if(array_key_exists("attributes", $plg_tpl['edit']['template']))
					{
						$plg_tpl['edit']['template'] = array($plg_tpl['edit']['template']);
					}
				}		

				foreach($plg_tpl['edit']['template'] as $temp)
				{
					$template_name			= $SQL->real_escape($temp['attributes']['name']);
					$finder->find_word		= $temp['find']['value'];
					$finder->another_word	= $temp['action']['value'];
					switch($temp['action']['attributes']['type']):
						case 'add_after': $action_type =3; break;
						case 'add_after_same_line': $action_type =4; break;
						case 'add_before': $action_type =5; break;
						case 'add_before_same_line': $action_type =6; break;
						case 'replace_with': $action_type =1; break;
					endswitch;

					$style_path = (substr($template_name, 0, 6) == 'admin_') ? $STYLE_PATH_ADMIN : $THIS_STYLE_PATH;

					//if template not found and default style is there and not admin tpl
					$template_path = $style_path . $template_name . '.html';
					if(!file_exists($template_path)) 
					{
						if(trim($config['style_depend_on']) != '')
						{
							$depend_on = $config['style_depend_on'];
							$template_path_alternative = str_replace('/' . $config['style'] . '/', '/' . trim($depend_on) . '/', $template_path);
							if(file_exists($template_path_alternative))
							{
								$template_path = $template_path_alternative;
							}
						}
						else if($config['style'] != 'default' && !$is_admin_template)
						{
							$template_path_alternative = str_replace('/' . $config['style'] . '/', '/default/', $template_path);
							if(file_exists($template_path_alternative))
							{
								$template_path = $template_path_alternative;
							}
						}
					}
	
				
					$d_contents = file_exists($template_path) ? file_get_contents($template_path) : '';
					$finder->text = trim($d_contents);
					$finder->do_search($action_type);
									
					if($d_contents  != '' && $finder->text != $d_contents)
					{
						//update
						$this->f->_write($style_path . $template_name . '.html', $finder->text);

						($hook = kleeja_run_hook('op_update_tplcntedit_crtplgxml_func')) ? eval($hook) : null; //run hook
	
						//delete cache ..
						delete_cache('tpl_' . $template_name);
					}
					else
					{
						$cached_instructions[$template_name] = array(
																		'action'		=> $temp['action']['attributes']['type'], 
																		'find'			=> $temp['find']['value'],
																		'action_text'	=> $temp['action']['value'],
																		);
					}
				}
			}#end edit
							
			//new templates 
			if(isset($plg_tpl['new']))
			{

				if(is_array($plg_tpl['new']['template']))
				{
					if(array_key_exists("attributes",$plg_tpl['new']['template']))
					{
						$plg_tpl['new']['template'] = array($plg_tpl['new']['template']);
					}
				}		

				foreach($plg_tpl['new']['template'] as $temp)
				{
					$style_path = (substr($template_name, 0, 6) == 'admin_') ? $STYLE_PATH_ADMIN : $THIS_STYLE_PATH;
					$template_name		= $temp['attributes']['name'];
					$template_content	= trim($temp['value']);

					$this->f->_write($style_path . $template_name . '.html', $template_content);
			
					
					/**
						$cached_instructions[$template_name] = array(
																		'action'		=> 'new', 
																		'find'			=> '',
																		'action_text'	=> $template_content,
																	);
					**/

					($hook = kleeja_run_hook('op_insert_newtpls_crtplgxml_func')) ? eval($hook) : null; //run hook
				}

			} #end new
		}#ens tpl

		//hooks
		if(isset($plg_hooks['hook']))
		{
			$plugin_author = strip_tags($plg_info['plugin_author']['value'], '<a><span>');
			$plugin_author = $SQL->real_escape($plugin_author);

			//if the plugin is not new then replace the old hooks with the new hooks
			if(!$plg_new)
			{
				//delete old hooks !
				$query_del = array(
									'DELETE'	=> "{$dbprefix}hooks",
									'WHERE'		=> "plg_id=" . $plg_id
								);		

				$SQL->build($query_del);
			}

			//then
			if(is_array($plg_hooks['hook']))
			{
				if(array_key_exists("attributes",$plg_hooks['hook']))
				{
					$plg_hooks['hook'] = array($plg_hooks['hook']);
				}
			}

			foreach($plg_hooks['hook'] as $hk)
			{
				$hook_for =	$SQL->real_escape($hk['attributes']['name']);
				$hk_value =	$SQL->real_escape($hk['value']);

				$insert_query = array(
										'INSERT'	=> 'plg_id, hook_name, hook_content',
										'INTO'		=> "{$dbprefix}hooks",
										'VALUES'	=> "'" . $new_plg_id . "','" . $hook_for . "', '" . $hk_value . "'"
									);
				($hook = kleeja_run_hook('qr_insert_hooks_crtplgxml_func')) ? eval($hook) : null; //run hook
				$SQL->build($insert_query);		
			}
			//delete cache ..
			delete_cache('data_hooks');
		}
		
		
		//todo : files tag ?
		
		if(sizeof($plg_errors) < 1) 
		{
			//add cached instuctions to cache if there
			if(sizeof($cached_instructions) > 0)
			{
				//fix
				if(file_exists(PATH . 'cache/styles_cached.php'))
				{
					$cached_content = file_get_contents(PATH . 'cache/styles_cached.php');
					$cached_content = kleeja_base64_decode($cached_content);
					$cached_content = unserialize($cached_content);
					$cached_instructions += $cached_content;
				}

				$this->f->_write('cache/styles_cached.php', kleeja_base64_encode(serialize($cached_instructions)));
			}

			return $plg_new ? ($there_is_intruct ? 'inst:' . $new_plg_id : 'done') : 'upd';
		}
		else 
		{
			return $plg_errors;
		}

		($hook = kleeja_run_hook('creat_plugin_xml_func')) ? eval($hook) : null; //run hook
		return false;
	}
}

/**
* Make changes with files using normal functions
* @package Kleeja
*/
class kfile
{
	var $handler = null;

	function _open($info = array())
	{
		return true;
	}
	
	function _close()
	{
		return true;
	}

	function _write($filepath, $content)
	{
		$filename = @fopen($filepath, 'w');
		fwrite($filename, $content);
		@fclose($filename);
	}

	function _delete($filepath)
	{
		return kleeja_unlink($filepath);
	}
	
	function _rename($oldfile, $newfile)
	{
		return @rename($oldfile, $newfile);
	}
	
	function _chmod($filepath, $perm = 0644)
	{
		return @chmod($filepath, $perm);
	}
	
	function _mkdir($dir, $perm = 0777)
	{
		return @mkdir($dir, $perm);
	}
	
	function _rmdir($dir)
	{
		return @rmdir($dir);
	}
}

/**
* Make changes with files using ftp
* @package Kleeja
*/
class kftp
{
	var $handler = null;
	var $timeout = 10;
	var $roo	 = './';

	function _open($info = array())
	{
		// connect to the server
		$this->handler = @ftp_connect($info['host'], $info['port'], $this->timeout);

		if (!$this->handler)
		{
			return false;
		}

		// pasv mode
		@ftp_pasv($this->handler, true);

		// login to the server
		if (!@ftp_login($this->handler, $info['user'], $info['pass']))
		{
			return false;
		}
		
		$this->root = PATH;
		
		if (!$this->_chdir($this->root))
		{
			return false;
		}
		
		return true;
	}
	
	function _close()
	{
		if (!$this->handler)
		{
			return false;
		}

		return @ftp_quit($this->handler);
	}

	
	function _chdir($dir = '')
	{
		if ($dir && $dir !== '/')
		{
			if (substr($dir, -1, 1) == '/')
			{
				$dir = substr($dir, 0, -1);
			}
		}

		return @ftp_chdir($this->handler, $dir);
	}
	
	function _chmod($file, $perm = 0644)
	{
		if (function_exists('ftp_chmod'))
		{
			$action = @ftp_chmod($this->handler, $perm, $file);
		}
		else
		{
			$chmod_cmd = 'CHMOD ' . base_convert($perm, 10, 8) . ' ' . $file;
			$action = $this->_site($chmod_cmd);
		}
		return $action;
	}
	
	function _site($cmd)
	{
		return @ftp_site($this->handler, $cmd);
	}
	
	function _delete($file)
	{
		return @ftp_delete($this->handler, $file);
	}

	function _write($filepath, $content)
	{
		
		$fnames = explode('/', $filepath);
		$filename = array_pop($fnames);
		$extension = strtolower(array_pop(explode('.', $filename)));
		$path = dirname($fnames);
	
		//make it as a cached one
		$h = @fopen(PATH . 'cache/plg_system_' . $filename, 'w');
		fwrite($h, $content);
		@fclose($h);
	

		$mode = FTP_BINARY;
	
		$this->_chdir($path);

		$r = @ftp_put($this->handler, $filename, PATH . 'cache/plg_system_' . $filename, $mode);
		$this->_chdir($this->root);
		
		kleeja_unlink(PATH . 'cache/plg_system_' . $filename);
		
		return $r;
	}
	
	function _rename($old_file, $new_file)
	{
		return @ftp_rename($this->handler, $old_file, $new_file);
	}
	
	
	function _mkdir($dir, $perm = 0777)
	{
		return @ftp_mkdir($this->handler, $dir);
	}
	
	function _rmdir($dir)
	{
		return @ftp_rmdir($this->handler, $dir);
	}
}
/**
* Make changes with files using fsock
* @package Kleeja
*/
class ksock 
{

}


/**
* XML to Array
* @package Kleeja
* @author based on many codes & thoughts at php.net/xml-parse
*/
class kxml 
{
	var $parser;
	var $result = array();

	function xml_to_array($xml_content) 
	{
		$this->parser = xml_parser_create();
		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 0);
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		if(xml_parse_into_struct($this->parser, $xml_content, $values, $index) === 0)
		{
			return false;
		}
		$i = -1;
		return $this->_get_children($values, $i);
	}


	function _build_tag($those_values, $values, &$i, $type)
	{
		$tag['tag'] = $those_values['tag'];
		if(isset($those_values['attributes']))
		{
			$tag['attributes'] = $those_values['attributes'];
		}

		if($type == 'complete' && isset($those_values['value']))
		{
			$tag['value'] = $those_values['value'];
		}
		else
		{
			$tag = array_merge($tag, $this->_get_children($values, $i));
		}
		return $tag;
	}
	
	function _get_children($values, &$i)
	{
		$collapse_dups = 1;
		$index_numeric = 0;
		$children = array();

		if($i > -1 && isset($values[$i]['value']))
		{
			$children['value'] = $values[$i]['value'];
		}

		while(++$i < sizeof($values))
		{
			$type = $values[$i]['type'];
			if($type == 'cdata' && isset($values[$i]['value']))
			{
				if(!isset($children['value']))
				{
					$children['value'] = '';
				}
				$children['value'] .= $values[$i]['value'];
			}
			elseif($type == "complete" || $type == "open")
			{
				$tag = $this->_build_tag($values[$i], $values, $i, $type);
				if($index_numeric)
				{
					$tag['tag']	= $values[$i]['tag'];
					$children[]	= $tag;
				}
				else
				{
					$children[$tag['tag']][]	= $tag;
				}
			}
			elseif($type == "close")
			{
				break;
			}
		}
	
		if($collapse_dups)
		{
			foreach($children as $key => $value)
			{
				if(is_array($value) && (count($value) == 1))
				{
					$children[$key]	= $value[0];
				}
			}
		}
		return $children;
	}
}


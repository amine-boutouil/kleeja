<?php
/**
*
* @package Kleeja
* @version $Id: pager.php 1251 2009-11-13 22:48:20Z altar3q $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/
//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}


@set_time_limit(0); 

/**
* Kleeja Plugins System
* @package Kleeja
*/
class kplugins
{

	//everytime we install plugin
	//we ask user for this..
	var $info		= array();
	var $f_method	= 'kfile';
	var $f			= null;
	var $plg_id		= 0;
	var $zipped_files	= '';

	function kplugins()
	{
		//check for the best method of files handling
		$f_method = '';
		$disabled_functions = explode(',', @ini_get('disable_functions'));

		if(!is_writable(PATH))
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
		if(empty($this->f) && $this->f_method != '')
		{
			$this->f = new $this->f_method;
		}
		
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

		//initiate file handler
		if(empty($this->f) && $this->f_method != '')
		{
			$this->f = new $this->f_method;
		}

		//parse xml content
		$XML = new kxml;

		$gtree = $XML->xml_to_array($contents);
		
		//sekelton of Kleeja plugin file
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
		$plg_files			= empty($tree['files']) ? null : $tree['files'];

		
		//important tags not exists 
		if(empty($plg_info))
		{
			big_error('Error',$lang['ERR_XML_NO_G_TAGS'] . (defined('DEV_STAGE') ? __file__ . ':'. __line__ : ''));
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

		$res = $SQL->build($is_query);
	
		if($SQL->num_rows($res))
		{
			//it's not new one ! , let's see if it same version
			$plg_new = false;
			$cur_ver = $SQL->fetch_array($res);
			$this->plg_id = $cur_ver['plg_id'];
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
		
		$there_is_intruct = false;
		if(isset($plg_instructions))
		{
			if(is_array($plg_instructions['instruction']) && array_key_exists("attributes", $plg_instructions['instruction']))
			{
				$plg_instructions['instruction'] = array($plg_instructions['instruction']);
			}
	
			$instarr = array();		
			foreach($plg_instructions['instruction'] as $in)
			{
				if(empty($in['attributes']['lang']) || !isset($in['attributes']['lang']))
				{
					big_error('Error',$lang['ERR_XML_NO_G_TAGS'] . (defined('DEV_STAGE') ? __file__ . ':'. __line__ : ''));
				}

				$instarr[$in['attributes']['lang']] = $in['value'];
			}
			
			$there_is_intruct = isset($instarr) && !empty($instarr) ? true : false;
		}
		
		$there_is_files = false;
		if(isset($plg_files))
		{
			if(is_array($plg_files['file']) && array_key_exists("attributes", $plg_files['file']))
			{
				$plg_files['file'] = array($plg_files['file']);
			}
	
			$newfiles = array();		
			foreach($plg_files['file'] as $in)
			{
				if(empty($in['attributes']['path']) || !isset($in['attributes']['path']))
				{
					big_error('Error', $lang['ERR_XML_NO_G_TAGS'] . (defined('DEV_STAGE') ? __file__ . ':'. __line__ : ''));
				}

				$newfiles[$in['attributes']['path']] = $in['value'];
			}

			$there_is_files = isset($newfiles) && !empty($newfiles) ? true : false;
		}
	

		if(isset($plg_info['plugin_description']))
		{
			if(is_array($plg_info['plugin_description']['description']) && array_key_exists("attributes", $plg_info['plugin_description']['description']))
			{
				$plg_info['plugin_description']['description'] = array($plg_info['plugin_description']['description']);
			}
	
			$p_desc = array();		
			foreach($plg_info['plugin_description']['description'] as $in)
			{
				if(empty($in['attributes']['lang']) || !isset($in['attributes']['lang']))
				{
					big_error('Error', $lang['ERR_XML_NO_G_TAGS'] . (defined('DEV_STAGE') ? __file__ . ':'. __line__ : ''));
				}
				$p_desc[$in['attributes']['lang']] = $in['value'];
			}
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
								'INSERT'	=> 'plg_name, plg_ver, plg_author, plg_dsc, plg_uninstall, plg_instructions, plg_store, plg_files',
								'INTO'		=> "{$dbprefix}plugins",
								'VALUES'	=> "'" . $SQL->escape($plugin_name) . "','" . $SQL->escape($plg_info['plugin_version']['value']) . 
												"','" . $SQL->escape($plg_info['plugin_author']['value']) . "','" . 
												$SQL->escape(kleeja_base64_encode(serialize($p_desc))) . "','" . $SQL->real_escape($plg_uninstall['value']) . "','" . 
												($there_is_intruct ? $SQL->escape(kleeja_base64_encode(serialize($instarr))) : '') . "','" .  
												$SQL->real_escape($store) . "','" . 
												($there_is_files ? $SQL->escape(kleeja_base64_encode(serialize(array_keys($newfiles)))) : '') . "'"

							);
			
			$SQL->build($insert_query);
		
			$this->plg_id = $SQL->insert_id();
		}
		else //if it was just update proccess
		{
			$update_query = array(
				
								'UPDATE'	=> "{$dbprefix}plugins",
								'SET'		=> "plg_ver='" . $new_ver . "', plg_author='" . $SQL->escape($plg_info['plugin_author']['value']) . 
												"', plg_dsc='" . $SQL->escape($plg_info['plugin_description']['value']) . "', plg_uninstall='" . 
												$SQL->real_escape($plg_uninstall['value']) . "', plg_instructions='" .
												($there_is_intruct ? $SQL->escape(kleeja_base64_encode(serialize($instarr))) : '') . "', plg_files='" .
												($there_is_files ? $SQL->escape(kleeja_base64_encode(serialize(array_keys($newfiles)))) : '') . 
												"', plg_store='" . $SQL->escape($store) . "'",
								'WHERE'		=> "plg_id=" . $this->plg_id
						);

			$SQL->build($update_query);
		}
	
		//eval install code
		if (isset($plg_install) && trim($plg_install['value']) != '' && $plg_new)
		{
			eval($plg_install['value']);
		}

		if(isset($plg_phrases))
		{
			if(is_array($plg_phrases['lang']) && array_key_exists("attributes", $plg_phrases['lang']))
			{
				$plg_phrases['lang'] = array($plg_phrases['lang']);
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

				if(is_array($in['phrase']) && array_key_exists("attributes", $in['phrase']))
				{
					$in['phrase'] = array($in['phrase']);
				}
				
				//get phrases value
				foreach($in['phrase'] as $phrase)
				{
					$phrases[$in['attributes']['name']][$phrase['attributes']['name']] = $phrase['value'];
				}

				//finally we add it to the database
				add_olang($phrases[$in['attributes']['name']], $in['attributes']['name'], $this->plg_id);
			}
		}

		if(isset($plg_options))
		{
			if(is_array($plg_options['option']) && array_key_exists("attributes", $plg_options['option']))
			{
				$plg_options['option'] = array($plg_options['option']);
			}

			foreach($plg_options['option'] as $in)
			{
				add_config($in['attributes']['name'], $in['attributes']['value'], $in['attributes']['order'], $in['value'], $in['attributes']['menu'], $this->plg_id);
			}

			delete_cache('data_config');
		}
		
		
		//add new files 
		if($there_is_files)
		{
			foreach($newfiles as $path => $content)
			{
				$this->f->_write($this->_fixpath_newfile($path), kleeja_base64_decode($content));
			}
			
			unset($newfiles);
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
							
				if(is_array($plg_tpl['edit']['template']) && array_key_exists("attributes", $plg_tpl['edit']['template']))
				{
					$plg_tpl['edit']['template'] = array($plg_tpl['edit']['template']);
				}		

				foreach($plg_tpl['edit']['template'] as $temp)
				{
					$template_name			= $SQL->real_escape($temp['attributes']['name']);
					if(isset($temp['find']['value']) && isset($temp['findend']['value']))
					{
						$finder->find_word		= array(1 => $temp['find']['value'],
														2 => $temp['findend']['value']);
					}
					else
					{
						$finder->find_word		= $temp['find']['value'];
					}
					
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
						//$this->f->_write($style_path . $template_name . '.html', $finder->text);
						$filename = @fopen($style_path . $template_name . '.html' , 'w');
						fwrite($filename, $finder->text);
						fclose($filename);
						
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

					//$this->f->_write($style_path . $template_name . '.html', $template_content);
					$filename = @fopen($style_path . $template_name . '.html' , 'w');
					fwrite($filename, $finder->text);
					fclose($filename);
			
					
					/**
						$cached_instructions[$template_name] = array(
																		'action'		=> 'new', 
																		'find'			=> '',
																		'action_text'	=> $template_content,
																	);
					**/

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
										'VALUES'	=> "'" . $this->plg_id . "','" . $hook_for . "', '" . $hk_value . "'"
									);

				$SQL->build($insert_query);		
			}
			//delete cache ..
			delete_cache('data_hooks');
		}

		
		//done !
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

				$filename = @fopen(PATH . 'cache/styles_cached.php' , 'w');
				fwrite($filename, kleeja_base64_encode(serialize($cached_instructions)));
				fclose($filename);
			}

			if($this->f_method === 'zfile')
			{
				if($this->f->check())
				{
					$this->zipped_files = $this->f->push($plugin_name);

					return $there_is_intruct ? 'zipped/inst' : 'zipped';
				}
			}
			
			
			return $plg_new ? ($there_is_intruct ? 'inst' : 'done') : 'upd';
		}
		else
		{
			return $plg_errors;
		}


		return false;
	}

	/**
	* delete any content from any template , this will used in plugins
	* used in unistall tag at plugin xml file
	*
	* todo : use file handler, require ftp info at uninstalling
	*/
	function delete_ch_tpl($template_name, $delete_txt = array())
	{
		global $dbprefix, $lang, $config, $STYLE_PATH_ADMIN , $STYLE_PATH, $THIS_STYLE_PATH;
		
		$style_path = (substr($template_name, 0, 6) == 'admin_') ? $STYLE_PATH_ADMIN : $THIS_STYLE_PATH;
		$is_admin_template = (substr($template_name, 0, 6) == 'admin_') ? true : false;

		//if template not found and default style is there and not admin tpl
		$template_path = $style_path . $template_name . '.html';
		if(!file_exists($template_path)) 
		{
			if($config['style'] != 'default' && !$is_admin_template)
			{
				$template_path_alternative = str_replace('/' . $config['style'] . '/', '/default/', $template_path);
				if(file_exists($template_path_alternative))
				{
					$template_path = $template_path_alternative;
				}
			}
		}
		
		if(file_exists($template_path))
		{
			$d_contents = file_get_contents($template_path);
		}
		else 
		{
			$d_contents = '';
		}
		
		include_once "s_strings.php";
		$finder	= new sa_srch;
		$finder->find_word		= $delete_txt;
		$finder->another_word	= '<!-- deleted ' . md5(implode(null, $delete_txt)) . ' -->';
		$finder->text = trim($d_contents);
		$finder->do_search(2);
		$cached_instructions = array();
		
		if($d_contents  != '' && md5($finder->text) != md5($d_contents) && is_writable($style_path))
		{
			//update
			//$this->f->_write($style_path . $template_name . '.html', $finder->text);
			$filename = @fopen($style_path . $template_name . '.html' , 'w');
			fwrite($filename, $finder->text);
			fclose($filename);
			
			//delete cache ..
			delete_cache('tpl_' . $template_name);
		}
		else
		{
			$cached_instructions[$template_name] = array(
														'action'		=> 'replace_with', 
														'find'			=> $finder->find_word[0] . '(.*?)' . $finder->find_word[1],
														'action_text'	=> $finder->another_word,
													);
		}
		
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
			
			$filename = @fopen(PATH . 'cache/styles_cached.php' , 'w');
			fwrite($filename, kleeja_base64_encode(serialize($cached_instructions)));
			fclose($filename);
		}
		
		return true;
	}
	
	/**
	* to delete file at uninstalling
	*
	*/
	function delete($files = array())
	{
		return;
	}
	
	
	
	function _fixpath_newfile($path)
	{
		if($path[0] == '/')
		{
			$path = substr($path, 1);
		}

		return $path;
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
* It's not a real method, it's just for save files changes
* @package Kleeja
*/
class zfile
{
	var $handler = null;
	var $files = array();

	function _open($info = array()){ return true; }
	function _close() { return true; }

	function _write($filepath, $content)
	{
		$this->files[$filepath] = $content;
	}

	function _delete($filepath)
	{
		//
		// best way is tell his that directly .. i have alot of ideas
		// just we have wait ..  
		//
		return true;
	}
	
	function _rename($oldfile, $newfile)
	{
		//see _delete
		//or, just we can give him a new file in zip ? good idea
		return true;
	}
	
	function _chmod($filepath, $perm = 0644)
	{
		//i hv no idea ...
		return true;
	}
	
	function _mkdir($dir, $perm = 0777)
	{
		//we can make dir in zip file, so wut ?
		return true;
	}
	function _rmdir($dir){ return true; }
	
	function check()
	{
		return @sizeof($this->files) ? true : false;
	}
	
	function push($plg_name)
	{
		$z = new zipfile;
		
		/* 
		//testing ...
		echo '<pre>';
		print_r($this->files);
		echo '</pre>';
		exit;
		*/

		foreach($this->files as $filepath => $content)
		{
			$z->create_file($content, $filepath);
		}

		$ff = md5($plg_name);

		//save file to cache and return the cached file name
		$c = $z->zipped_file();
		$fn = @fopen(PATH . 'cache/changes_of_' . $ff . '.zip', 'w');
		fwrite($fn, $c);
		fclose($fn);

		return $ff;
	}
}

/**
* Make changes with files using ftp
* @package Kleeja
*/
class kftp
{
	var $handler = null;
	var $timeout = 15;
	var $root	 = '';

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

		$this->root = ($info['path'][0] != '/' ? '/' : '') . $info['path'] . ($info['path'][strlen($info['path'])-1] != '/' ? '/' : '');

		if (!$this->_chdir(PATH))
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

		return @ftp_chdir($this->handler, $this->_fixpath($dir));
	}
	
	function _chmod($file, $perm = 0644)
	{
		if (function_exists('ftp_chmod'))
		{
			$action = @ftp_chmod($this->handler, $perm, $this->_fixpath($file));
		}
		else
		{
			$chmod_cmd = 'CHMOD ' . base_convert($perm, 10, 8) . ' ' . $this->_fixpath($file);
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
		return @ftp_delete($this->handler, $this->_fixpath($file));
	}

	function _write($filepath, $content)
	{
		
		$fnames = explode('/', $filepath);
		$filename = array_pop($fnames);
		$extension = strtolower(array_pop(explode('.', $filename)));
		$path = dirname($fnames);
		$cached_file = PATH . 'cache/plg_system_' . $filename;

		//make it as a cached one
		$h = @fopen($cached_file, 'wb');
		fwrite($h, $content);
		@fclose($h);
	
		if(in_array($extension, array('gif', 'jpg', 'png')))
		{
			$mode = FTP_BINARY;
		}
		else
		{
			$mode = FTP_ASCII;
		}

		$this->_chdir($this->_fixpath($path));

		$r = @ftp_put($this->handler, $filename, $this->_fixpath($cached_file), $mode);
		$this->_chdir($this->root);
		
		kleeja_unlink($cached_file);
		
		return $r;
	}
	
	function _rename($old_file, $new_file)
	{
		return @ftp_rename($this->handler, $this->_fixpath($old_file), $this->_fixpath($new_file));
	}
	
	
	function _mkdir($dir, $perm = 0777)
	{
		return @ftp_mkdir($this->handler, $this->_fixpath($dir));
	}
	
	function _rmdir($dir)
	{
		return @ftp_rmdir($this->handler, $this->_fixpath($dir));
	}
	
	function _fixpath($path)
	{
		return $this->root . str_replace(PATH, '', $path);
	}
}
/**
* Make changes with files using fsock
* @package Kleeja
*/
class kfsock {}


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


/**
*	zipfile class for writing .zip files
*	Copyright (C) Joshua Townsend (http://www.gamingg.net)
*	Based on tutorial given by John Coggeshall
*	@edited on 2010 By Kleeja team
*/
class zipfile
{
	//container variables
	var $datasec= array(), $dirs = array(), $ctrl_dir = array();
	//end of Central directory record
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00"; 
	var $old_offset = 0;
	var $basedir = '.';

	function create_dir($name, $echo = false)
	{
		$name = str_replace("\\", "/", $name);
		$fr = "\x50\x4b\x03\x04" . "\x0a\x00" . "\x00\x00" . "\x00\x00" . "\x00\x00\x00\x00" . pack("V",0). pack("V",0) . pack("V",0) . pack("v", strlen($name)) . pack("v", 0) . $name . pack("V",0) . pack("V",0) .pack("V",0);
		$this->datasec[] = $fr;
		//output now !
		if($echo)
			echo $fr;
		$new_offset = strlen(implode('', $this->datasec));
		// now add to central record
		$cdrec = "\x50\x4b\x01\x02" . "\x00\x00" . "\x0a\x00" . "\x00\x00". "\x00\x00" . "\x00\x00\x00\x00" . pack("V",0) . pack("V",0) . pack("V",0) . pack("v", strlen($name)) . pack("v", 0) .  pack("v", 0) . pack("v", 0) . pack("v", 0) . pack("V", 16) . pack("V", $this->old_offset) . $name;
		$this->old_offset = $new_offset;
		$this->ctrl_dir[] = $cdrec;
		$this->dirs[] = $name;
	}

	function check_file_path($filepath)
	{
		// todo : check dir and creat them
		// path/path2/path3/filename.ext
		// here there is 3 folder, so you have to make them
		// before creating file
		return true;
	}

	function create_file($data, $name, $echo = false)
	{
		$name = str_replace("\\", "/", $name);
		$fr = "\x50\x4b\x03\x04". "\x14\x00" . "\x00\x00" . "\x08\x00" . "\x00\x00\x00\x00"; 
		$unc_len = strlen($data);
		$crc = crc32($data);
		$zdata =  substr(gzcompress($data), 2, -4);
		$c_len = strlen($zdata);
		$fr .= pack("V",$crc) . pack("V",$c_len) . pack("V",$unc_len) . pack("v", strlen($name)) .  pack("v", 0). $name . $zdata .  pack("V",$crc) . pack("V",$c_len) . pack("V",$unc_len);
		$this->datasec[] = $fr;
		$new_offset = strlen(implode("", $this->datasec));
		//output now !
		if($echo)
			echo $fr;
		// now add to central directory record
		$cdrec = "\x50\x4b\x01\x02" . "\x00\x00" . "\x14\x00" . "\x00\x00" . "\x08\x00" . "\x00\x00\x00\x00" . pack("V",$crc) . pack("V",$c_len) . pack("V",$unc_len) . pack("v", strlen($name) ). pack("v", 0 ) . pack("v", 0 ) . pack("v", 0 ) . pack("v", 0 ) . pack("V", 32 ) . pack("V", $this->old_offset) . $name;
		$this->old_offset = $new_offset;
		$this->ctrl_dir[] = $cdrec;
	}

	function zipped_file($d = true)
	{
		$data = implode('', $this->datasec);
		$ctrldir = implode('', $this->ctrl_dir);
		return 	($d ? $data : null) . $ctrldir . $this->eof_ctrl_dir . pack("v", sizeof($this->ctrl_dir)). pack("v", sizeof($this->ctrl_dir)). pack("V", strlen($ctrldir)) . pack("V", strlen($data)) . "\x00\x00";
	}
}

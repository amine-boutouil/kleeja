<?php
	//start
	//part of admin extensions
	//begin of admin

	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $


	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}

		//style of
		$stylee			= "admin_start";
		//last visit
		$last_visit		= (defined('LAST_VISIT')) ?  date("[d-m-Y], [h:i a] ", LAST_VISIT) : false;
		$h_lst_files	= basename(ADMIN_PATH) . '?cp=files&amp;last_visit=' . (defined('LAST_VISIT') ? LAST_VISIT : time() - 3600*12);
		$h_lst_imgs		= basename(ADMIN_PATH) . '?cp=img_ctrl&amp;last_visit=' . (defined('LAST_VISIT') ? LAST_VISIT : time() - 3600*12);

		//data
		$lst_reg			= (empty($stat_last_user)) ? $lang['UNKNOWN'] : $stat_last_user;
		$files_number 		= $stat_files;
		$files_sizes 		= Customfile_size($stat_sizes);
		$users_number 		= $stat_users;
		$last_file_up_url	= PATH . $stat_last_file;
		$last_file_up		= (strlen($stat_last_file) > 25) ? substr($stat_last_file, 0, 25) . '...' : $stat_last_file;
		$last_del_fles 		= date("d-m-Y h:i a", $stat_last_f_del);
		$php_version 		= isset($NO_PHPINFO) || !function_exists('phpinfo') ? phpversion() : '<a href="' . basename(ADMIN_PATH) . '?cp=php_info" title="php_info">php ' . phpversion() . '</a>';
		$mysql_version 		= 'MYSQL ' . $SQL->mysql_version;
		$max_execution_time = function_exists('ini_get') ?  @ini_get('max_execution_time') : @get_cfg_var('max_execution_time');
		$upload_max_filesize= function_exists('ini_get') ?  @ini_get('upload_max_filesize') : @get_cfg_var('upload_max_filesize');
		$post_max_size 		= function_exists('ini_get') ?  @ini_get('post_max_size') : @get_cfg_var('post_max_size');
		$memory_limit 		= function_exists('ini_get') ?  @ini_get('memory_limit') : @get_cfg_var('memory_limit');
		$s_last_google		= ($stat_last_google == 0) ? '[ ? ]' : date("d-m-Y h:i a", $stat_last_google);
		$s_google_num		= $stat_google_num;
		$s_last_yahoo		= ($stat_last_yahoo == 0) ? '[ ? ]' : date("d-m-Y h:i a", $stat_last_yahoo);
		$s_yahoo_num		= $stat_yahoo_num;
		$usernamelang		= sprintf($lang['KLEEJA_CP_W'], $username);

		//size board by percent
		$per	= $stat_sizes / ($config['total_size'] * 1048576);
		$per1	= round($per*100, 2);
		$per1	= $per1 >= 100 ? 100 : $per1;

		//ppl must know about kleeja version!
		$kleeja_version	 = '<a href="' . basename(ADMIN_PATH) . '?cp=check_update" title="' . $lang['R_CHECK_UPDATE'] . '">' . KLEEJA_VERSION . '</a>';

		//admin messages system
		$ADM_NOTIFICATIONS = array();
		
		//useing IE6 ! and he is admin ?  omg !
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !==false)
		{
			$ADM_NOTIFICATIONS[]  = array('id' => 'IE6', 'msg_type'=> 'error', 'title'=> $lang['NOTE'], 'msg'=> $lang['ADMIN_USING_IE6']);
		}
		
		//updating
		$v = @unserialize($config['new_version']);
		if(version_compare(strtolower(KLEEJA_VERSION), strtolower($v['version_number']), '<'))
		{
			$ADM_NOTIFICATIONS[]  = array(
									'id' => 'up_ver_klj',//this not so important row 
									'msg_type'=> 'error', 'title'=> $lang['R_CHECK_UPDATE'], 
									'msg'=> sprintf($lang['UPDATE_NOW_S'] , KLEEJA_VERSION, $v['version_number']) . '<br />' . '<a href="http://www.kleeja.com/">www.kleeja.com</a>'
									);
									
			($hook = kleeja_run_hook('admin_update_now')) ? eval($hook) : null; //run hook 
		}


		//check upload_max_filesize
		$u_e_s = isset($u_exts) && is_array($u_exts) ? array_values($u_exts) : array(0);
		$g_e_s = isset($g_exts) && is_array($g_exts) ? array_values($g_exts) : array(0);
		asort($u_e_s);
		asort($g_e_s);
		if(strpos($upload_max_filesize, 'M') !== false)
		{
			$upload_max_filesize_s = ((int) trim(str_replace('M', '', $upload_max_filesize))) * 1048576;
		}
		else if(strpos($upload_max_filesize, 'G') !== false)
		{
			$upload_max_filesize_s = ((int) trim(str_replace('G', '', $upload_max_filesize))) * 1073741824;
		}
		
		$big_size_is = $u_e_s[0]['size'] > $g_e_s[0]['size'] ? $u_e_s[0]['size'] : $g_e_s[0]['size'];
		if(!empty($upload_max_filesize) && $upload_max_filesize_s < $big_size_is)
		{
			$ADM_NOTIFICATIONS[]  = array(
									'id' => 'file_size_ini_low',
									'msg_type'=> 'info', 'title'=> $lang['NOTE'], 
									'msg'=> sprintf($lang['PHPINI_FILESIZE_SMALL'] , Customfile_size($big_size_is), Customfile_size($upload_max_filesize_s))
									);
		}

		//check post_max_size
		if(strpos($post_max_size, 'M') !== false)
		{
			$post_max_size_s = ((int) trim(str_replace('M', '', $post_max_size))) * 1048576;
		}
		else if(strpos($post_max_size, 'G') !== false)
		{
			$post_max_size_s = ((int) trim(str_replace('G', '', $post_max_size))) * 1073741824;
		}

		$post_max_size_s_must_be = ($config['filesnum'] * $big_size_is) + 5242880;//+ 5 mega to make sure it's ok

		if(!empty($post_max_size) && $post_max_size_s < $post_max_size_s_must_be)
		{
			$ADM_NOTIFICATIONS[]  = array(
									'id' => 'post_m_size_ini_low',
									'msg_type'=> 'info', 'title'=> $lang['NOTE'], 
									'msg'=> sprintf($lang['PHPINI_MPOSTSIZE_SMALL'] , $config['filesnum'], Customfile_size($post_max_size_s_must_be))
									);		
		}

		//if 24 hours, lets chcek agian !
		if((time() - $v['last_check']) > 86400 && !$v['msg_appeared'] && $_SERVER['SERVER_NAME'] != 'localhost')
		{
			redirect(basename(ADMIN_PATH) . '?cp=check_update&show_msg');
			$SQL->close();
			exit;
		}	


		//cached templates
		$there_is_cached = false;
		$cached_file = $root_path . 'cache/styles_cached.php';
		if(file_exists($cached_file))
		{
			$ADM_NOTIFICATIONS[]  = array(
								'id' => 'cached_tpl',
								'msg_type'=> 'info', 'title'=> $lang['CACHED_STYLES'],
								'msg'=> sprintf($lang['CACHED_STYLES_DISC'] , '<a href="' . basename(ADMIN_PATH) . '?cp=styles&amp;sty_t=cached">' . $lang['CLICKHERE'] .'</a>')
							);
		}

		//if config not safe
		if((bool) (@fileperms($root_path . 'config.php') & 0x0002))
		{
			$ADM_NOTIFICATIONS[]  = array('id' => 'config_perm', 'msg_type'=> 'info', 'title'=> $lang['NOTE'], 'msg'=> $lang['CONFIG_WRITEABLE']);
		}

		//no htaccess
		if(!file_exists($root_path . $config['foldername'] . '/.htaccess'))
		{
			$ADM_NOTIFICATIONS[]  = array('id' => 'htaccess_u', 'msg_type'=> 'error', 'title'=> $lang['WARN'], 'msg'=> sprintf($lang['NO_HTACCESS_DIR_UP'], $config['foldername']));
		}

		if(!file_exists($root_path . $config['foldername'] . '/thumbs/.htaccess'))
		{
			$ADM_NOTIFICATIONS[]  = array('id' => 'htaccess_t', 'msg_type'=> 'error', 'title'=> $lang['WARN'], 'msg'=> sprintf($lang['NO_HTACCESS_DIR_UP_THUMB'], $config['foldername'] . '/thumbs'));
		}


		//check is there any copyright on footer.html if not show pretty msg with peace
		if(file_exists($STYLE_PATH . 'footer.html'))
		{
			$t_data = file_get_contents($STYLE_PATH . 'footer.html');
			$t_data .= file_get_contents($STYLE_PATH . 'header.html');

			if(strpos($t_data, 'kleeja.com') === false)
			{
				//not guilty or not guilty! we love who use kleeja even witout copyrights 
				//but we are human being, so we need some money to live as a normal people 
				if($v['copyrights'] == false)
				{
					$copy_msg = sprintf($lang['NO_KLEEJA_COPYRIGHTS'], '<a href="http://www.kleeja.com/buy/">' . $lang['CLICKHERE'] .'</a>');
					$ADM_NOTIFICATIONS[]  = array('id' => 'copyrights_t', 'msg_type'=> 'error', 'title'=> $lang['NOTE'], 'msg'=> $copy_msg);
				}
			}
		}

		//there is cleaning files process now
		if((int)$config['klj_clean_files_from'] > 0)
		{
			$ADM_NOTIFICATIONS[]  = array('id' => 'klj_clean_files', 'msg_type'=> 'info', 'title'=> '', 'msg'=> $lang['T_CLEANING_FILES_NOW']);
		}

		($hook = kleeja_run_hook('default_admin_page')) ? eval($hook) : null; //run hook 

#<--- EOF

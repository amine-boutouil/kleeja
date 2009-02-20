<?php
	//start
	//part of admin extensions
	//begin of admin
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	
		//style of
		$stylee 				= "admin_start";
		//last visit
		$last_visit				= ($_SESSION['LAST_VISIT']) ?  gmdate("[d-m-Y], [h:i] a ", $_SESSION['LAST_VISIT']) : false;
		$h_lst_files			= './admin.php?cp=files&last_visit=' . $_SESSION['LAST_VISIT'];
		$h_lst_imgs				= './admin.php?cp=img_ctrl&last_visit=' . $_SESSION['LAST_VISIT'];
		
		//data
		$files_number 		= $stat_files ;
		$files_sizes 		= Customfile_size($stat_sizes);
		$users_number 		= $stat_users;
		$last_file_up		= $stat_last_file;
		$last_del_fles 		= gmdate("d-m-Y h:i a", $stat_last_f_del);
		$s_c_t				= $stat_counter_today;
		$s_c_y				= $stat_counter_yesterday;
		$s_c_a				= $stat_counter_all;
		$php_version 		= 'php ' . phpversion();
		$mysql_version 		= 'MYSQL ' . $SQL->mysql_version;
		$max_execution_time = @ini_get('max_execution_time');
		$upload_max_filesize= @ini_get('upload_max_filesize');
		$post_max_size 		= @ini_get('post_max_size');
		$s_last_google		= ($stat_last_google == 0) ? '[ ? ]' : gmdate("d-m-Y h:i a", $stat_last_google);
		$s_google_num		= $stat_google_num;
		$s_last_yahoo		= ($stat_last_yahoo == 0) ? '[ ? ]' : gmdate("d-m-Y h:i a", $stat_last_yahoo);
		$s_yahoo_num		= $stat_yahoo_num;
		//size board by percent
		$per1 = @round($stat_sizes / ($config['total_size'] *1048576) ,2) *100;
		//ppl must know about kleeja version!
		//ok i forgive ...
		$kleeja_version		= KLEEJA_VERSION;
		
		//updating
		$v = unserialize($config['new_version']);
		$update_now		= (version_compare(strtolower(KLEEJA_VERSION), strtolower($v['version_number']), '<')) ? true : false;
		$update_now_disc = sprintf($lang['UPDATE_NOW_S'] , KLEEJA_VERSION, $v['version_number']) . '<br />' . '<a href="http://www.kleeja.com/">www.kleeja.com</a>';
		
		//if 24 hours, lets chcek agian !
		if((time() - $v['last_check']) > 86400 && !$v['msg_appeared'])
		{
			header('location: ./admin.php?cp=check_update&show_msg');
		}	
		
		
		//cached
		$there_is_cached = false;
		$cached_file = $root_path . 'cache/styles_cached.php';
		if(file_exists($cached_file))
		{
			$there_is_cached = sprintf($lang['CACHED_STYLES_DISC'] , '<a href="./admin.php?cp=styles&amp;sty_t=cached">' . $lang['CLICKHERE'] .'</a>');
		}
		
		($hook = kleeja_run_hook('default_admin_page')) ? eval($hook) : null; //run hook 
		

?>

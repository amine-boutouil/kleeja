<?php
	//start
	//part of admin extensions
	//begin of admin
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit();
	}
	
		//style of
		$stylee 				= "admin_start";
		//last visit
		$last_visit				= ($_SESSION['LAST_VISIT']) ?  date("[d-m-Y], [h:i] a ", $_SESSION['LAST_VISIT']) : false;
		$h_lst_files			= './admin.php?cp=files&last_visit='.$_SESSION['LAST_VISIT'];
		$h_lst_imgs				= './admin.php?cp=img_ctrl&last_visit='.$_SESSION['LAST_VISIT'];
		
		//data
		$files_number 		= $stat_files ;
		$files_sizes 		= Customfile_size($stat_sizes);
		$users_number 		= $stat_users;
		$last_file_up		= $stat_last_file;
		$last_del_fles 		= date("d-m-Y h:i a", $stat_last_f_del);
		$s_c_t				= $stat_counter_today;
		$s_c_y				= $stat_counter_yesterday;
		$s_c_a				= $stat_counter_all;
		$php_version 		= 'php '.phpversion();
		$mysql_version 		= 'MYSQL '.$SQL->mysql_version;
		$max_execution_time = ini_get('max_execution_time');
		$upload_max_filesize= ini_get('upload_max_filesize');
		$post_max_size 		= ini_get('post_max_size');
		$s_last_google		= ($stat_last_google == 0) ? '[ ? ]' : date("d-m-Y h:i a", $stat_last_google);
		$s_google_num		= $stat_google_num;
		$s_last_yahoo		= ($stat_last_yahoo == 0) ? '[ ? ]' : date("d-m-Y h:i a", $stat_last_yahoo);
		$s_yahoo_num		= $stat_yahoo_num;
		//size board by percent
		$per1 = @round($stat_sizes / ($config['total_size'] *1048576) ,2) *100;

		$kleeja_version		= KLEEJA_VERSION;
		
		($hook = kleeja_run_hook('default_admin_page')) ? eval($hook) : null; //run hook 
		

?>
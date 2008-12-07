<?php
	//configs
	//part of admin extensions
	//conrtoll all configuarations of the script 
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}

		//for style ..
		$stylee 		= "admin_configs";
		//words
		$action 		= "admin.php?cp=configs";
		$n_submit 		= $lang['UPDATE_CONFIG'];


		$n_googleanalytics = '<a href="http://www.google.com/analytics">Google Analytics</a>';

					
		$query = array(
						'SELECT'	=> '*',
						'FROM'		=> "{$dbprefix}config"
					);
									
		$result = $SQL->build($query);

		while($row=$SQL->fetch_array($result))
		{
			//make new lovely array !!
			$con[$row['name']]=$row['value'];
			//-->
			$new[$row['name']] = (isset($_POST[$row['name']])) ? $_POST[$row['name']] : $con[$row['name']];

				//when submit !!
				if (isset($_POST['submit']))
				{
					$update_query = array(
												'UPDATE'	=> "{$dbprefix}config",
												'SET'		=> "value='" . $SQL->escape($new[$row['name']]) . "'",
												'WHERE'		=> "name='" . $row['name'] . "'"
										);

					if ($SQL->build($update_query))
					{
						//delete cache ..
						delete_cache('data_config');
					}
					else
					{
						die($lang['CANT_UPDATE_SQL']);
					}
				}
		}
		
		$SQL->freeresult($result);

		//for  choose
		if ($con['siteclose'] == "1" ) {$yclose = true; }else {$nclose = true;}
		if ($con['decode'] == "2" ) {$md5_decode = true; }elseif ($con['decode'] == "1" ) {$time_decode = true;}
		else {$none_decode = true; }
		if ($con['user_system'] == "1" ) {$user_system_normal = true; }elseif ($con['user_system'] == "2" ) {$user_system_phpbb = true;}
		elseif($con['user_system'] == "3" ) {$user_system_vb = true; }elseif($con['user_system'] == "4" ) {$user_system_mysbb = true; }
		if ($con['statfooter'] == "1" ) {$ystatfooter = true; }else {$nstatfooter = true;}
		if ($con['gzip'] == "1" ) {$ygzip = true; }else {$ngzip = true;}
		if ($con['register'] == "1" ) {$yregister = true; }else {$nregister = true;}
		if ($con['thumbs_imgs'] == "1" ) {$ythumbs_imgs = true; }else {$nthumbs_imgs = true;}
		if ($con['write_imgs'] == "1" ) {$ywrite_imgs = true; }else {$nwrite_imgs = true;}
		if ($con['del_url_file'] == "1" ) {$ydel_url_file = true; }else {$ndel_url_file = true;}
		if ($con['www_url'] == "1" ) {$ywww_url = true; }else {$nwww_url = true;}
		if ($con['allow_stat_pg'] == "1" ) {$yallow_stat_pg = true; }else {$nallow_stat_pg = true;}
		if ($con['allow_online'] == "1" ) {$yallow_online = true; }else {$nallow_online = true;}
		if ($con['mod_writer'] == "1" ) {$ymod_writer = true; }else {$nmod_writer = true;}
		if ($con['enable_userfile'] == "1" ) {$yenable_userfile = true; }else {$nenable_userfile = true;}
		if ($con['enable_userfile'] == "1" ) {$yenable_userfile = true; }else {$nenable_userfile = true;}
		if ($con['safe_code'] == "1" ) {$ysafe_code = true; }else {$nsafe_code = true;}


		//get languag and get styles
		$stylfiles = $lngfiles	='';
		$query_styles = array(
							'SELECT'	=> '*',
							'FROM'		=> "{$dbprefix}lists"
							);
						
		$result_styles = $SQL->build($query_styles);

		while($row=$SQL->fetch_array($result_styles))
		{		
			if($row['list_type']==1)
				$stylfiles .=  '<option '.(($con['style']==$row['list_id']) ? 'selected="selected"' : ''). ' value="' . $row['list_id'] . '">' . $row['list_name'] . '</option>';
			if($row['list_type']==2)
				$lngfiles .=  '<option '.(($con['language']==$row['list_id']) ? 'selected="selected"' : ''). ' value="' . $row['list_id'] . '">' . $row['list_name'] . '</option>';

		}
		$SQL->freeresult($result_styles);
		
		//after submit ////////////////
		if (isset($_POST['submit']))
		{
			//empty ..
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
				$text	= $lang['CONFIGS_UPDATED'];
				$stylee	= "admin_info";
			}

		}#submit

?>
<?
##################################################
#						Kleeja
#
# Filename : admin.php
# purpose :  control panel for administarator
# copyright 2007 Kleeja.com ..
# rev id : $id$
##################################################


	// security ..
	define ( 'IN_INDEX' , true);
	//include imprtant file ..
	require ('includes/common.php');
	include ('includes/version.php');


	//change style just for admin
	$tpl->Temp = "includes/style_admin/";
	$tpl->Cache = "cache";
	$stylepath = $tpl->Temp;


	//for security
	if ( !$usrcp->admin() ) {
			$text = '<span style="color:red;">' . $lang['U_NOT_ADMIN'] . '</span><br/><a href="usrcp.php?go=login">' . $lang['LOGIN'] . '</a>';
			//header
			print $tpl->display("header.html");
			//index
			print $tpl->display('info.html');
			//footer
			print $tpl->display("footer.html");
	exit();
	}

	//fix bug
	$SHOW_LIST = true;

	// now we will navigate
	switch ($_GET['cp']) {
		case "configs" ://===================================== [ CONFIGS]
		//for style ..
		$stylee 	= "configs.html";
		//words
		$action 		= "admin.php?cp=configs";
		$n_submit 		= $lang['UPDATE_CONFIG'];
		$n_yes 			= $lang['YES'];
		$n_no 			= $lang['NO'];
		$n_none 		= $lang['NO_CHANGE'];
		$n_md5 			= $lang['CHANGE_MD5'];
		$n_time 		= $lang['CHANGE_TIME'];
		$n_sitename 	= $lang['SITENAME'];
		$n_sitemail	 	= $lang['SITEMAIL'];
		$n_siteurl		= $lang['SITEURL'];
		$n_foldername 	= $lang['FOLDERNAME'];
		$n_prefixname 	= $lang['FILES_PREFIX'];
		$n_filesnum 	= $lang['FILES_NUMB'];
		$n_siteclose 	= $lang['SITECLOSE'];
		$n_closemsg 	= $lang['CLOSE_MSG'];
		$n_decode 		= $lang['FILENAME_CHNG'];
		$n_style 		= $lang['STYLENAME'];
		$n_sec_down 	= $lang['SC_BEFOR_DOWM'];
		$n_statfooter 	= $lang['SHOW_PHSTAT'];
		$n_gzip 		= $lang['EN_GZIP'];
		$n_welcome_msg 	= $lang['WELC_MSG'];
		$n_user_system 	= $lang['USER_SYSTEM'];
		$us_normal 		= $lang['NORMAL'];
		$us_phpbb 		= $lang['W_PHPBB'];
		$us_mysbb 		= $lang['W_MYSBB'];
		$us_vb			= $lang['W_VBB'];
		$n_register 	= $lang['ENAB_REG'];
		$n_total_size 	= $lang['MAX_SIZE_SITE'];
		$n_thumbs_imgs 	= $lang['ENAB_THMB'];
		$n_write_imgs 	= $lang['ENAB_STAMP'];
		$n_del_url_file = $lang['ENAB_DELURL'];
		$n_language		= $lang['LANGUAGE'];
		$n_www_url		= $lang['WWW_URL'];
		$n_del_f_day	= $lang['DEL_FDAY'];
		$n_allow_stat_pg= $lang['ALLOW_STAT_PG'];
		$n_allow_online = $lang['ALLOW_ONLINE'];
		$n_googleanalytics = '<a href="http://www.google.com/analytics">Google Analytics</a>';


		$sql	=	$SQL->query("SELECT * FROM {$dbprefix}config");
		while($row=$SQL->fetch_array($sql)){
		//make new lovely array !!
			$con[$row['name']]=$row['value'];
			//-->
			$new[$row[name]] = ( isset($_POST[$row[name]]) ) ? $_POST[$row[name]] : $con[$row[name]];

				//when submit !!
				if ( isset($_POST['submit']) ) {
				$update = $SQL->query("UPDATE `{$dbprefix}config` SET
				value = '" . $SQL->escape($new[$row[name]]) . "'
				WHERE name = '$row[name]'");
				if (!$update) { die($lang['CANT_UPDATE_SQL']);}
				else
				{
				//delete cache ..
					if (file_exists('cache/data_config.php')){
					@unlink('cache/data_config.php');
					}
				}
				}

		}
		$SQL->freeresult($sql);

		//for  choose
		if ($con[siteclose] == "1" ) {$yclose = true; }else {$nclose = true;}
		//..
		if ($con[decode] == "2" ) {$md5_decode = true; }elseif ($con[decode] == "1" ) {$time_decode = true;}
		else {$none_decode = true; }
		//..
		if ($con[user_system] == "1" ) {$user_system_normal = true; }elseif ($con[user_system] == "2" ) {$user_system_phpbb = true;}
		elseif($con[user_system] == "3" ) {$user_system_vb = true; }elseif($con[user_system] == "4" ) {$user_system_mysbb = true; }
		//..
		if ($con[statfooter] == "1" ) {$ystatfooter = true; }else {$nstatfooter = true;}
		//..
		if ($con[gzip] == "1" ) {$ygzip = true; }else {$ngzip = true;}
		//..
		if ($con[register] == "1" ) {$yregister = true; }else {$nregister = true;}
		//..
		if ($con[thumbs_imgs] == "1" ) {$ythumbs_imgs = true; }else {$nthumbs_imgs = true;}
		//..
		if ($con[write_imgs] == "1" ) {$ywrite_imgs = true; }else {$nwrite_imgs = true;}
		//..
		if ($con[del_url_file] == "1" ) {$ydel_url_file = true; }else {$ndel_url_file = true;}
        //..
		if ($con[www_url] == "1" ) {$ywww_url = true; }else {$nwww_url = true;}
        //..
		if ($con[allow_stat_pg] == "1" ) {$yallow_stat_pg = true; }else {$nallow_stat_pg = true;}
        //..
		if ($con[allow_online] == "1" ) {$yallow_online = true; }else {$nallow_online = true;}

		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		//empty ..
		if (empty($_POST['sitename']) || empty($_POST['siteurl']) || empty($_POST['foldername']) || empty($_POST['filesnum'])
				|| empty($_POST['style']) )
		{
		$text = $lang['EMPTY_FIELDS'];
		$stylee	= "err.html";
		}
		elseif (!is_numeric($_POST['filesnum']) || !is_numeric($_POST['sec_down']))
		{
		$text = $lang['NUMFIELD_S'];
		$stylee	= "err.html";
		}
		else
		{
		$text = $lang['CONFIGS_UPDATED'];
		$stylee	= "info.html";
		}

		}#submit
		break; //=================================================
		case "exts" ://===================================== [ exts]
		//for style ..
		$stylee = "exts.html";
		//words
		$action 	= "admin.php?cp=exts";
		$n_submit 	= $lang['UPDATE_EXTS'];
		$n_ext 		= $lang['EXT'];
		$n_group 	= $lang['GROUP'];
		$n_gsize 	= $lang['SIZE_G'];
		$n_gallow 	= $lang['ALLOW_G'];
		$n_usize 	= $lang['SIZE_U'];
		$n_uallow 	= $lang['ALLOW_U'];
		$n_note 	= $lang['E_EXTS'];


		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}exts`");
		while($row=$SQL->fetch_array($sql)){

			//make new lovely arrays !!
			$ids[$row['id']]=	$row['id'];
			$ex[$row['id']]	=	$row['ext'];
			$gr[$row[id]] 	=	( isset($_POST["gr_".$row[id]])  ) ? $_POST["gr_".$row[id]]  : $row['group_id'];
			$g_sz[$row[id]]	=	( isset($_POST["gsz_".$row[id]]) ) ? $_POST["gsz_".$row[id]] : $row['gust_size'];
			$g_al[$row[id]]	=	$row['gust_allow'];
			$u_sz[$row[id]]	=	( isset($_POST["usz_".$row[id]]) ) ? $_POST["usz_".$row[id]] : $row['user_size'];
			$u_al[$row[id]]	=	$row['user_allow'];



				//when submit !!
				if ( isset($_POST['submit']) ) {
				$g_al[$row[id]] = isset($_POST["gal_".$row[id]])  ? 1 : $row['gust_allow'] ;
				$u_al[$row[id]] = isset($_POST["ual_".$row[id]])  ? 1 : $row['user_allow'] ;

				$update = $SQL->query("UPDATE `{$dbprefix}exts` SET
				group_id = '" . intval($gr[$row[id]]) . "',
				gust_size = '" . intval($g_sz[$row[id]]) . "',
				gust_allow = '" . intval($g_al[$row[id]]) . "',
				user_size = '" . intval($u_sz[$row[id]]) . "',
				user_allow = '" . intval($u_al[$row[id]]) . "'
				WHERE id = '$row[id]'");
				if (!$update){ die($lang['CANT_UPDATE_SQL']);}
				else
				{
				//delete cache ..
					if (file_exists('cache/data_exts.php')){
					@unlink('cache/data_exts.php');
					}
					if (file_exists('cache/data_sizes.php')){
					@unlink('cache/data_sizes.php');
					}
				}
				}

		}
		$SQL->freeresult($sql);
		if (!is_array($ids)){$ids = array();}//fix bug
		foreach($ids as $i)
		{
		$arr[] = array( id =>$i,
						name =>$ex[$i],
						group=>ch_g($i,$gr[$i]),
						g_size =>$g_sz[$i],
						g_allow=>($g_al[$i])? "<input name=\"gal_{$i}\" type=\"checkbox\" checked=\"checked\" />":"<input name=\"gal_{$i}\" type=\"checkbox\" />",
						u_size =>$u_sz[$i],
						u_allow=>($u_al[$i])? "<input name=\"ual_{$i}\" type=\"checkbox\" checked=\"checked\" />":"<input name=\"ual_{$i}\" type=\"checkbox\"  />"
						);
		}
		if (!is_array($arr)){$arr = array();}

		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		$text = $lang['UPDATED_EXTS'];
		$stylee	= "info.html";
		}

		break; //=================================================
		case "files" ://===================================== [ files]
		//for style ..
		$stylee = "files.html";
		//words
		$action 	= "admin.php?cp=files";
		$n_submit 	= $lang['UPDATE_FILES'];
		$n_name 	= $lang['FILENAME'];
		$n_user 	= $lang['BY'];
		$n_size 	= $lang['FILESIZE'];
		$n_time 	= $lang['FILEDATE'];
		$n_uploads 	= $lang['FILEUPS'];
		$n_type	 	= $lang['FILETYPE'];
		$n_folder 	= $lang['FILDER'];
		$n_report 	= $lang['REPORT'];
		$n_del 		= $lang['DELETE'];


		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}files` ORDER BY `id` DESC");
		while($row=$SQL->fetch_array($sql)){
		//make new lovely arrays !!
			$ids[$row['id']] =  $row['id'];
			$name[$row['id']]=$row['name'];
			$size[$row['id']]=$row['size'];
			$uploads[$row['id']]=$row['uploads'];
			$time[$row['id']]=$row['time'];
			$type[$row['id']]=$row['type'];
			$folder[$row['id']]=$row['folder'];
			$report[$row['id']]=$row['report'];
			$user[$row['id']]=$row['user'];

			//
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";


				//when submit !!
				if ( isset($_POST['submit']) ) {
					if ($del[$row[id]])
					{
						$update = $SQL->query("DELETE FROM `{$dbprefix}files` WHERE id='" . intval($ids[$row[id]]) . "' ");
						if (!$update) { die($lang['CANT_UPDATE_SQL']);}

						//delete from folder ..
						@unlink ( $folder[$row['id']] . "/" . $name[$row['id']] );
							//delete thumb
							if (is_file($folder[$row['id']] . "/thumbs/" . $name[$row['id']] ))
							{@unlink ( $folder[$row['id']] . "/thumbs/" . $name[$row['id']] );}
							//delete thumb
					}
			}
		}
		$SQL->freeresult($sql);

		if (!is_array($ids)){$ids = array();}//fix bug
		foreach($ids as $i)
		{
		$s = $SQL->fetch_array($SQL->query("select name from `{$dbprefix}users` where id='".$user[$i]."' "));
		$arr[] = array( id =>$i,
						name =>"<a href=\"./$folder[$i]/$name[$i]\" target=\"blank\">".$name[$i]."</a>",
						size =>Customfile_size($size[$i]),
						ups =>$uploads[$i],
						time => date("d-m-Y H:a", $time[$i]),
						type =>$type[$i],
						folder =>$folder[$i],
						report =>($report[$i] > 4)? "<span style=\"color:red\"><big>".$report[$i]."</big></span>":$report[$i],
						user =>($user[$i] == '-1') ? $lang['GUST']:  $s[0],
						);
		}
		if (!is_array($arr)){$arr = array();}

		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		$text = $lang['FILES_UPDATED'];
		$stylee	= "info.html";
		}
		break; //=================================================
		case "reports" ://===================================== [ reports]
		//for style ..
		$stylee 		= "reports.html";
		//words
		$action 		= "admin.php?cp=reports";
		$n_submit 		= $lang['UPDATE_REPORTS'];
		$n_name 		= $lang['NAME'];
		$n_mail 		= $lang['EMAIL'];
		$n_url 			= $lang['URL'];
		$n_click 		= $lang['CLICKHERE'];
		$n_text 		= $lang['TEXT'];
		$n_time 		= $lang['TIME'];
		$n_mouse 		= $lang['E_CLICK'];
		$n_ip 			= $lang['IP'];
		$n_reply 		= $lang['REPLY'];
		$n_del 			= $lang['DELETE'];

		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}reports`  ORDER BY `id` DESC");
		while($row=$SQL->fetch_array($sql)){
		//make new lovely arrays !!
			$ids[$row['id']]	=$row['id'];
			$name[$row['id']]	=$row['name'];
			$mail[$row['id']]	=$row['mail'];
			$url[$row['id']]	=$row['url'];
			$text[$row['id']]	=$row['text'];
			$time[$row['id']]	=$row['time'];
			$ip_{$row['id']}	=$row['ip'];

			//
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";
			$sen[$row[id]] = ( isset($_POST["v_".$row[id]]) ) ? $_POST["v_".$row[id]] : "";
			//when submit !!
			if ( isset($_POST['submit']) ) {
					if ($del[$row[id]])
					{
					$update = $SQL->query("DELETE FROM `{$dbprefix}reports` WHERE id='" . intval($ids[$row[id]]) . "' ");
					if (!$update) { die($lang['CANT_UPDATE_SQL']);}
					}
				}
			if ( isset($_POST['reply_submit']) ) {
				if ($sen[$row[id]])
					{
						$to      = $mail[$row['id']];
						$subject = $lang['REPLY_REPORT'] . ':'.$config[sitename];
						$message = "\n " . $lang['WELCOME'] . " ".$name[$row['id']]."\r\n " . $lang['U_REPORT_ON'] . " ".$config[sitename]. "\r\n " . $lang['BY_EMAIL'] . ": ".$mail[$row['id']]."\r\n" . $lang['ADMIN_REPLIED'] . ": \r\n".$sen[$row[id]]."\r\n\r\n Kleeja Script";
						$headers = 'From: '. $config[sitename]. '<'. $config[sitemail]. '>' . "\r\n" .
						    'MIME-Version: 1.0' . "\r\n" .
						    'X-Mailer: PHP/' . phpversion();
						$send =  @mail($to, $subject, $message, $headers);
						if (!$send) {die($lang['CANT_SEND_MAIL']);}
						else {
						$text = $lang['IS_SEND_MAIL'];
						$stylee	= "info.html";
						}

					}
				//may send
			}
		}
		$SQL->freeresult($sql);

		if (!is_array($ids)){$ids = array();}//fix bug
		foreach($ids as $i)
		{
		$arr[] = array( id =>$i,
						name 		=> $name[$i],
						mail 		=> $mail[$i],
						url  		=> $url[$i],
						text 		=> $text[$i],
						time 		=> date("d-m-Y H:a", $time[$i]),
						ip	 		=> $ip_{$i},
						ip_finder	=> 'http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=' . $ip_{$i} . '&do_search=Search'
						);

		}
		if (!is_array($arr)){$arr = array();}

		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		$text = $lang['REPORTS_UPDATED'];
		$stylee	= "info.html";
		}

		break; //=================================================
		case "calls" ://===================================== [ calls]
		//for style ..
		$stylee = "calls.html";
		//words
		$action 		= "admin.php?cp=calls";
		$n_submit		= $lang['UPDATE_CALSS'];
		$n_name 		= $lang['NAME'];
		$n_mail 		= $lang['EMAIL'];
		$n_text			= $lang['TEXT'];
		$n_time 		= $lang['TIME'];
		$n_mouse		= $lang['E_CLICK'];
		$n_ip 			= $lang['IP'];
		$n_reply 		= $lang['REPLY'];
		$n_del 			= $lang['DELETE'];

		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}call` ORDER BY `id` DESC");
		while($row=$SQL->fetch_array($sql)){
		//make new lovely arrays !!
			$ids[$row['id']] 	=$row['id'];
			$name[$row['id']]	=$row['name'];
			$mail[$row['id']]	=$row['mail'];
			$text[$row['id']]	=$row['text'];
			$time[$row['id']]	=$row['time'];
			$ip_{$row['id']}		=$row['ip'];

			//
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";
			$sen[$row[id]] = ( isset($_POST["v_".$row[id]]) ) ? $_POST["v_".$row[id]] : "";
			//when submit !!
			if ( isset($_POST['submit']) ) {
				if ($del[$row[id]])
				{
				$update = $SQL->query("DELETE FROM `{$dbprefix}call` WHERE id='" . intval($ids[$row[id]]) . "' ");
				if (!$update) { die($lang['CANT_UPDATE_SQL']);}
				}
			}
			if ( isset($_POST['reply_submit']) ) {
				if ($sen[$row[id]])
				{
				$to      = $mail[$row['id']];
				$subject = $lang['REPLY_CALL'] . ':'.$config[sitename];
				$message = "\n " . $lang['REPLY_CALL'] . " ".$name[$row['id']]."\r\n " . $lang['REPLIED_ON_CAL'] . " : ".$config[sitename]. "\r\n " . $lang['BY_EMAIL'] . ": ".$mail[$row['id']]."\r\n" . $lang['ADMIN_REPLIED'] . "\r\n".$sen[$row[id]]."\r\n\r\n Kleeja Script";
				$headers = 'From: '. $config[sitename]. '<'. $config[sitemail]. '>' . "\r\n" .
				    'MIME-Version: 1.0' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
				$send =  @mail($to, $subject, $message, $headers);
				if (!$send) { die($lang['CANT_SEND_MAIL']);}
				else {
					$text = $lang['IS_SEND_MAIL'];
					$stylee	= "info.html";
					}
				}
				//may send
			}
		}
		$SQL->freeresult($sql);

		if (!is_array($ids)){$ids = array();}//fix bug
		foreach($ids as $i)
		{
		$arr[] = array( id =>$i,
						name 		=> $name[$i],
						mail 		=> $mail[$i],
						text 		=> $text[$i],
						time 		=> date("d-m-Y H:a", $time[$i]),
						ip 			=> $ip_{$i},
						ip_finder	=> 'http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=' . $ip_{$i} . '&do_search=Search'
						);


		}
		if (!is_array($arr)){$arr = array();}

		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		$text = $lang['CALLS_UPDATED'];
		$stylee	= "info.html";
		}

		break; //=================================================
		case "users" ://===================================== [ users]
		//for style ..
		$stylee = "users.html";
		//words
		$action 	= "admin.php?cp=users";
		$n_name 	= $lang['USERNAME'];
		$n_mail 	= $lang['EMAIL'];
		$n_admin 	= $lang['IS_ADMIN'];
		$n_pass 	= $lang['PASSWORD'];
		$n_submit 	= $lang['UPDATE_USERS'];
		//$n_files = "HIS FILES";
		$n_del		= $lang['DELETE'];


		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}users`  ORDER BY `id` DESC");
		while($row=$SQL->fetch_array($sql)){

			//make new lovely arrays !!
			$ids[$row['id']]=	$row['id'];
			$name[$row[id]] 	=( isset($_POST["nm_".$row[id]])  ) ? $_POST["nm_".$row[id]]  : $row['name'];
			$mail[$row[id]]	=	( isset($_POST["ml_".$row[id]]) ) ? $_POST["ml_".$row[id]] : $row['mail'];
			$pass[$row[id]]	=	( isset($_POST["ps_".$row[id]]) ) ? $_POST["ps_".$row[id]] :"";
			$admin[$row[id]]	=	$row['admin'];
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";


				//when submit !!
			if ( isset($_POST['submit']) ) {
				if ($del[$row[id]])
				{
					//when submit !!
					$update = $SQL->query("DELETE FROM `{$dbprefix}users` WHERE id='" . intval($ids[$row[id]]) . "' ");
					if (!$update) { die($lang['CANT_UPDATE_SQL']);}
				}

				//update
				$admin[$row[id]] = isset($_POST["ad_".$row[id]])  ? 1 : 0 ;
				$pass[$row[id]] = ($pass[$row[id]] != '') ? "password = '" . md5($SQL->escape($pass[$row[id]])) . "'," : "";

				$update2 = $SQL->query("UPDATE `{$dbprefix}users` SET
				name = '" . $SQL->escape($name[$row[id]]) . "',
				mail = '" . $SQL->escape($mail[$row[id]]) . "',
				".$pass[$row[id]]."
				admin = '" . intval($admin[$row[id]]) . "'
				WHERE id = '$row[id]'");
				if (!$update2) { die($lang['CANT_UPDATE_SQL']);}

			}
		}
		$SQL->freeresult($sql);

		if (!is_array($ids)){$ids = array();}//fix bug
		foreach($ids as $i)
		{
		$arr[] = array( id =>$i,
						name =>$name[$i],
						mail =>$mail[$i],
						admin =>($admin[$i])? "<input name=\"ad_{$i}\" type=\"checkbox\" checked=\"checked\" />":"<input name=\"ad_{$i}\" type=\"checkbox\"  />"
						);
		}
		if (!is_array($arr)){$arr = array();}

		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		$text = $lang['USERS_UPDATED'];
		$stylee	= "info.html";
		}
		break; //=================================================
		case "ban" ://===================================== [ ban]
		//for style ..
		$stylee = "ban.html";
		//words
		$action 		= "admin.php?cp=ban";
		$n_explain_top 	= $lang['BAN_EXP1'];
		$n_explain_btm 	= $lang['BAN_EXP2'];
		$n_submit 		= $lang['UPDATE_BAN'];


		$sql	=	$SQL->query("SELECT ban FROM `{$dbprefix}stats`");
		while($row=$SQL->fetch_array($sql)){

		
			$ban = ( isset($_POST["ban_text"]) ) ? $_POST["ban_text"] : $row[ban];
				//when submit !!
			if ( isset($_POST['submit']) ) {

				//update
				$update2 = $SQL->query("UPDATE `{$dbprefix}stats` SET
				ban = '" . $SQL->escape($ban) . "' ");
				if (!$update2) { die($lang['CANT_UPDATE_SQL']);}
				else
				{
				//delete cache ..
					if (file_exists('cache/data_ban.php')){
					@unlink('cache/data_ban.php');
					}
				}
			}
		}
		$SQL->freeresult($sql);


		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		$text = $lang['BAN_UPDATED'];
		$stylee	= "info.html";
		}
		
		break; //=================================================
		case "backup" ://===================================== [ backup]
		//thanks for [coder] from montadaphp.net  for his simle lession
		//@set_time_limit(1000);
		//for style ..
		$stylee = "backup.html";
		//words
		$action 		= "admin.php?cp=backup";
		$n_explain 		= $lang['E_BACKUP'];
		$n_name 		= $lang['NAME'];
		$n_size 		= $lang['SIZE'];
		$n_submit 		= $lang['TAKE_BK'];


		$sql	=	$SQL->query("SHOW TABLE STATUS");
		$i = 0;
		while($row=$SQL->fetch_array($sql)){

			//make new lovely arrays !!
			$id		= $i++;
			$size[$id]	= round($row['Data_length']/1024, 2);
			$name[$id]   = $row[Name];

		}
		$SQL->freeresult($sql);


		for($i=0;$i<$id;$i++)
		{
		$arr[] = array( name =>$name[$i],
						size =>$size[$i]
						);
		}
		if (!is_array($arr)){$arr = array();}

		//after submit ////////////////
		if ( isset($_POST['submit']) ) {
		//variables
		$tables = $_POST['check'];
		$outta = "";
		//then
		foreach($tables as $table)
		{
		    $sql = $SQL->query("SHOW CREATE TABLE `".$table."`"); //get code of tables ceation
		    $que = $SQL->fetch_array($sql);
		    $outta .= $que['Create Table'] . "\r\n";//preivous code iside file
		    $sql2 = $SQL->query("SELECT * FROM `$que[Table]`");// gets rows of table
		    while($result = $SQL->fetch_array($sql2))
		    {
		        while($res = current($result))
		        {
		            $fields[] .= "`".key($result)."`";
		            $values[] .= "'$res'";
		            next($result);
		        }

		        $fields = join(", ", $fields);
		        $values = join(", ", $values);
		        $q = "INSERT INTO `$que[Table]` ($fields) VALUES ($values);";
		        $outta .= $q . "\r\n";
		        unset($fields);
		        unset($values);
		    }

			$SQL->freeresult($sql);
			$SQL->freeresult($sql2);
		}
		header("Content-length: " . strlen($outta));
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=$dbname.sql");
		echo $outta;
		exit;
		}
		break; //=================================================
		case "repair" ://===================================== [ repair]

		//prevent err
		$text = '';

		//fix tables ..
		$sql	=	$SQL->query("SHOW TABLE STATUS");

		while($row=$SQL->fetch_array($sql)){

			//fix
			$sqlf = $SQL->query("REPAIR TABLE `".$row[Name]."`");
			if ($sqlf) { $text .= $lang['REPAIRE_TABLE'] . $row[Name] . "<br />";}

		}
		$SQL->freeresult($sql);


		//fix stats ..
		$sqlr	=	$SQL->query("SELECT size FROM `{$dbprefix}files`");
		$files_number = 0;
		$files_sizes = 0;
		while($row=$SQL->fetch_array($sqlr)){

			//stats files
			$files_number++;
			$files_sizes = $files_sizes+$row[size];

		}
		$SQL->freeresult($sqlr);

		$sqlw	=	$SQL->query("SELECT name FROM `{$dbprefix}users`");
		$user_number = 0;
		while($row=$SQL->fetch_array($sqlw)){

			//stats files
			$user_number++;
		}
		$SQL->freeresult($sqlw);

		$update1 = $SQL->query("UPDATE `{$dbprefix}stats` SET
		`files`=" . $files_number . ",
		`sizes`=" . $files_sizes . ",
		`users`=" . $user_number . "
		");
		if ( $update1 ){
		$text .=  $lang['REPAIRE_F_STAT'] . "<br />";
		$text .= $lang['REPAIRE_S_STAT'] . "<br />";
		}

		//clear cache
		$path = "cache";
		$dh = opendir($path);
		$i=1;
		while (($file = readdir($dh)) !== false) {
		    if($file != "." && $file != ".." && $file != ".htaccess" && $file != "index.html") {
		       $del =  @unlink ( $path . "/" . $file );
			  $text .= $lang['REPAIRE_CACHE']  . $file . "<br />";
		        $i++;
		    }
		}
		closedir($dh);


		$stylee = "info.html";

		break; //=================================================
		case "lgutcp" ://===================================== [ lgutcp]

		if ( $usrcp->logout_cp() )
		{
		$text = $lang['LOGOUT_CP_OK'];
		$stylee	= "info.html";
		}


		break; //=================================================
		default:
		$Kleja_cp 				= $lang['KLEEJA_CP'];
		$stylee 				= "start.html";
		$n_general_stats 		= $lang['GENERAL_STAT'];
		$n_sizes_stats 			= $lang['SIZE_STAT'];
		$n_other_stats 			= $lang['OTHER_INFO'];
		$n_files_number 		= $lang['AFILES_NUM'];
		$n_stat_sizes 			= $lang['AFILES_SIZE'];
		$n_users_number 		= $lang['AUSERS_NUM'];
		$n_last_del_fles		= $lang['LSTDELST'];
		$n_last_file_up			= $lang['LSTFLE_ST'];
		$n_welcome_msg 			= $lang['KLEEJA_CP_W'];
		$N_SIZE_STATUS 			= $lang['USING_SIZE'];
		$n_php_version 			= $lang['PHP_VER'];
		$n_mysql_version 		= $lang['MYSQL_VER'];
		$n_max_execution_time 	= "max_execution_time";
		$n_upload_max_filesize 	= "upload_max_filesize";
		$n_post_max_size 		= "post_max_size";
		$n_kleeja_version		= $lang['KLEEJA_VERSION'];
		$n_s_c_t				= $lang['S_C_T'];
		$n_s_c_y				= $lang['S_C_Y'];
		$n_s_c_a				= $lang['S_C_A'];
		
		//data
		$files_number 		= $stat_files ;
		$files_sizes 		= Customfile_size($stat_sizes);
		$users_number 		= $stat_users;
		$last_file_up		= $stat_last_file;
		$last_del_fles 		= date("d-m-Y H:a", $stat_last_f_del);
		$s_c_t				= $stat_counter_today;
		$s_c_y				= $stat_counter_yesterday;
		$s_c_a				= $stat_counter_all;
		$php_version 		= 'php '.phpversion();
		$mysql_version 		= 'MYSQL '.$SQL->mysql_version;
		$max_execution_time =  ini_get('max_execution_time');
		$upload_max_filesize= ini_get('upload_max_filesize');
		$post_max_size 		= ini_get('post_max_size');
		//size board by percent
		$per1 = round($stat_sizes / ($config[total_size] *1048576) ,2) *100;

		$kleeja_version		= KLEEJA_VERSION;
	}#end switch


	//admin functions
	function ch_g ($id,$def)
	{global $lang;
	$s =  array(0=>'',1=>$lang['N_IMGS'],2=>$lang['N_ZIPS'],3=>$lang['N_TXTS'],4=>$lang['N_DOCS'],5=>$lang['N_RM'],6=>$lang['N_WM'],
				7=>$lang['N_SWF'],8=>$lang['N_QT'],9=>$lang['N_OTHERFILE']);
	$show = "<select name=\"gr_{$id}\">";
	for($i=1;$i<count($s);$i++)
	{
	$selected = ($def==$i)? "selected=\"selected\"" : "";
	$show .= "<option $selected value=\"$i\">$s[$i]</option>";
	}
	$show .="</select>";
	return $show;
	}



	//show style ..
	$cp_admin 		= $lang['KLEEJA_CP'];

	$index_name 	= $lang['RETURN_HOME'];
	$cp_name 		= $lang['R_CPINDEX'];
	$cp_url 		= "admin.php";
	$configs_name 	= $lang['R_CONFIGS'];
	$configs_url 	= "admin.php?cp=configs";
	$exts_name 		= $lang['R_EXTS'];
	$exts_url 		= "admin.php?cp=exts";
	$files_name 	= $lang['R_FILES'];
	$files_url 		= "admin.php?cp=files";
	$reports_name 	= $lang['R_REPORTS'];
	$reports_url 	= "admin.php?cp=reports";
	$calls_name 	= $lang['R_CALLS'];
	$calls_url 		= "admin.php?cp=calls";
	$users_name 	= $lang['R_USERS'];
	$users_url 		= "admin.php?cp=users";
	$backup_name 	= $lang['R_BCKUP'];
	$backup_url 	= "admin.php?cp=backup";
	$repair_name 	= $lang['R_REPAIR'];
	$repair_url 	= "admin.php?cp=repair";
	$lgutcp_name 	= $lang['R_LGOUTCP'];
	$lgutcp_url 	= "admin.php?cp=lgutcp";
	$ban_name 		= $lang['R_BAN'];
	$ban_url 		= "admin.php?cp=ban";

	//header
	print $tpl->display("header.html");
 	//body
	print $tpl->display($stylee);
	//footer
	print $tpl->display("footer.html");

	$SQL->close();
?>
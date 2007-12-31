<?
##################################################
#						Kleeja
#
# Filename : admin.php
# purpose :  control panel for administarator
# copyright 2007 Kleeja.com ..
# last edit by : saanina
##################################################


	// security ..
	define ( 'IN_INDEX' , true);
	define ( 'IN_ADMIN' , true);
	
	//include imprtant file ..
	require ('includes/common.php');
	include ('includes/version.php');


	//change style just for admin
	$tpl->Temp = "includes/style_admin";
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
		$n_mod_writer 	= $lang['MOD_WRITER'];
		$n_mod_writer_ex= $lang['MOD_WRITER_EX'];
		$n_safe_code	= $lang['SAFE_CODE_UPLOAD'];
		$n_enable_fileuser = $lang['ENABLE_USER_FILE'];
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
		if ($con[decode] == "2" ) {$md5_decode = true; }elseif ($con[decode] == "1" ) {$time_decode = true;}
		else {$none_decode = true; }
		if ($con[user_system] == "1" ) {$user_system_normal = true; }elseif ($con[user_system] == "2" ) {$user_system_phpbb = true;}
		elseif($con[user_system] == "3" ) {$user_system_vb = true; }elseif($con[user_system] == "4" ) {$user_system_mysbb = true; }
		if ($con[statfooter] == "1" ) {$ystatfooter = true; }else {$nstatfooter = true;}
		if ($con[gzip] == "1" ) {$ygzip = true; }else {$ngzip = true;}
		if ($con[register] == "1" ) {$yregister = true; }else {$nregister = true;}
		if ($con[thumbs_imgs] == "1" ) {$ythumbs_imgs = true; }else {$nthumbs_imgs = true;}
		if ($con[write_imgs] == "1" ) {$ywrite_imgs = true; }else {$nwrite_imgs = true;}
		if ($con[del_url_file] == "1" ) {$ydel_url_file = true; }else {$ndel_url_file = true;}
		if ($con[www_url] == "1" ) {$ywww_url = true; }else {$nwww_url = true;}
		if ($con[allow_stat_pg] == "1" ) {$yallow_stat_pg = true; }else {$nallow_stat_pg = true;}
		if ($con[allow_online] == "1" ) {$yallow_online = true; }else {$nallow_online = true;}
		if ($con[mod_writer] == "1" ) {$ymod_writer = true; }else {$nmod_writer = true;}
		if ($con[enable_userfile] == "1" ) {$yenable_userfile = true; }else {$nenable_userfile = true;}
		if ($con[enable_userfile] == "1" ) {$yenable_userfile = true; }else {$nenable_userfile = true;}
		if ($con[safe_code] == "1" ) {$ysafe_code = true; }else {$nsafe_code = true;}


		//get language from LANGUAGE folder
		$path = "language";
		$dh = opendir($path);
		$lngfiles = '';
		$i=1;
		while (($file = readdir($dh)) !== false) {
		    if($file != "." && $file != ".."  && $file != "index.html") {
			$file = str_replace('.php','', $file);
			  $lngfiles .= ($con[language]==$file) ? '<option selected="selected" value="' . $file . '">' . $file . '</option>': '<option value="' . $file . '">' . $file . '</option>';
		    }
		}
		closedir($dh);
		//get styles from styles folder
		$dhs = opendir("styles");
		$stylfiles = '';
		$i=1;
		while (($file = readdir($dhs)) !== false) {
		    if($file != "." && $file != ".."  && $file != "index.html") {
			  $stylfiles .= ($con[style]==$file) ? '<option selected="selected" value="' . $file . '">' . $file . '</option>': '<option value="' . $file . '">' . $file . '</option>';
		    }
		}
		closedir($dhs);
		
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
		case "search" ://===================================== [ search]
		//for style ..
		$stylee = "search.html";
		//words
		$action1 			= "admin.php?cp=files";
		$n_search_submit 	= $lang['SEARCH_SUBMIT'];
		$search_files		= $lang['SEARCH_FILES'];
		$n_name 			= $lang['FILENAME'];
		$n_user 			= $lang['USERNAME'];
		$n_size 			= $lang['FILESIZE'];
		$n_time 			= $lang['FILEDATE'];
		$n_uploads 			= $lang['FILEUPS'];
		$n_type	 			= $lang['FILETYPE'];
		$n_folder 			= $lang['FILDER'];
		$n_report 			= $lang['REPORT'];
		$n_last_down 		= $lang['LAST_DOWN'];
		$n_today 			= $lang['TODAY'];
		$n_days				= $lang['DAYS'];
		$n_was_b4			= $lang['WAS_B4'];
		$n_bite				= $lang['BITE'];
		//
		$action2 			= "admin.php?cp=users";	
		$search_users		= $lang['SEARCH_USERS'];
		$n_username			= $lang['USERNAME'];
		$n_usermail			= $lang['EMAIL'];
		
		// ITS DEFAULT SYSTEM USER !
		$default_user_system= (true) ? true : false;
		
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
	
		//posts search ..
		if (isset($_POST['search_file'])){
		
			$file_namee	= ($_POST['filename']!='') ? 'AND f.name LIKE \'%'.$SQL->escape($_POST['filename']).'%\' ' : ''; 
			$usernamee	= ($_POST['username']!='') ? 'AND u.name LIKE \'%'.$SQL->escape($_POST['username']).'%\' AND u.id=f.user' : ''; 
			$size_than	=   ' `size` '.(($_POST['than']==1) ? '>' : '<').intval($_POST['size']).' ';
			$ups_than	=  ($_POST['ups']!='') ? 'AND f.uploads '.(($_POST['uthan']==1) ? '>' : '<').intval($_POST['ups']).' ' : '';
			$rep_than	=  ($_POST['rep']!='') ? 'AND f.report '.(($_POST['rthan']==1) ? '>' : '<').intval($_POST['rep']).' ' : '';
			$lstd_than	=  ($_POST['lastdown']!='') ? 'AND f.last_down ='.(time()-(intval($_POST['lastdown']) * (24 * 60 * 60))).' ' : '';
			$exte		=  ($_POST['ext']!='') ? 'AND f.type LIKE \'%'.$SQL->escape($_POST['ext']).'%\' ' : '';
			
			$sql_text = "SELECT f.* 
			FROM {$dbprefix}files f , {$dbprefix}users u
			WHERE $size_than $file_namee $ups_than $exte $rep_than $usernamee $lstd_than $exte
			ORDER BY `id` DESC";
			
		}elseif(isset($_GET['last_visit'])){
			$sql_text = "SELECT * FROM `{$dbprefix}files` 
			WHERE time > '". intval($_GET['last_visit']) ."'
			ORDER BY `id` DESC";
		}else{
			$sql_text = "SELECT * FROM `{$dbprefix}files` ORDER BY `id` DESC";
		}
		
		$sql = $SQL->query($sql_text);
		
		/////////////pager 
		$nums_rows = $SQL->num_rows($sql);
		$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager = new SimplePager($perpage,$nums_rows,$currentPage);
		$start = $Pager->getStartRow();
		////////////////
		
		$no_results = false;
		

		if ( $nums_rows > 0  ) {
		
		$sql = $SQL->query($sql_text . " LIMIT $start,$perpage");
		
		while($row=$SQL->fetch_array($sql)){
		
		//make new lovely arrays !!
			$userfile =  $config[siteurl].(($config[mod_writer])? 'fileuser_'.$row['user'].'.html' : 'usrcp.php?go=fileuser&amp;id='.$row['user'] );
			$user_name = $SQL->fetch_array($SQL->query("SELECT name FROM `{$dbprefix}users` WHERE id='".$row['user']."' "));
			$arr[] = array(id =>$row['id'],
						name =>"<a href=\"./".$row['folder']."/".$row['name']."\" target=\"blank\">".$row['name']."</a>",
						size =>Customfile_size($row['size']),
						ups =>$row['uploads'],
						time => date("d-m-Y H:a", $row['time']),
						type =>$row['type'],
						folder =>$row['folder'],
						report =>($row['report'] > 4)? "<span style=\"color:red\"><big>".$row['report']."</big></span>":$row['report'],
						user =>($row['user'] == '-1') ? $lang['GUST'] :  '<a href="'.$userfile.'" target="_blank">'. $user_name['name'] . '</a>',
						);
			//
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";


				//when submit !!
				if ( isset($_POST['submit']) ) {
					if ($del[$row[id]])
					{
						$update = $SQL->query("DELETE FROM `{$dbprefix}files` WHERE id='" . intval($row[id]) . "' ");
						if (!$update) { die($lang['CANT_UPDATE_SQL']);}

						//delete from folder ..
						@unlink ($row['folder'] . "/" .$row['name'] );
							//delete thumb
							if (is_file($row['folder'] . "/thumbs/" . $row['name'] ))
							{@unlink ($row['folder'] . "/thumbs/" . $row['name'] );}
							//delete thumb
					}
			}
		}
		$SQL->freeresult($sql);
	}else{#num_rows
	$no_results = true;
	}
		$total_pages 	= $Pager->getTotalPages(); 
		$page_nums 		= $Pager->print_nums($config[siteurl].'admin.php?cp=files'); 
		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		$text = $lang['FILES_UPDATED'];
		$stylee	= "info.html";
		}
		
		break; //=================================================
		case "img" ://===================================== [ files]
		//for style ..
		$stylee = "img.html";
		//words
		$action 	= "admin.php?cp=img";
		$n_submit 	= $lang['DEL_SELECTED'];
		$n_del 		= $lang['DELETE'];
	
		if(isset($_GET['last_visit'])){
			$sql = $SQL->query("SELECT * FROM `{$dbprefix}files` 
			WHERE 
			time > '". intval($_GET['last_visit']) ."'
			AND type IN ('gif','jpg','png','bmp','jpeg','tif','tiff')
			ORDER BY `id` DESC");
		}else{
		$sql = $SQL->query("SELECT * FROM `{$dbprefix}files` WHERE type IN ('gif','jpg','png','bmp','jpeg','tif','tiff') ORDER BY `id` DESC");
	}
		/////////////pager 
		$perpag2e = 9;
		$nums_rows = $SQL->num_rows($sql);
		$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager = new SimplePager($perpag2e,$nums_rows,$currentPage);
		$start = $Pager->getStartRow();
		////////////////
		
		$no_results = false;
		
		if ( $nums_rows > 0  ) {
		
		$sql = $SQL->query("SELECT * FROM `{$dbprefix}files` WHERE type IN ('gif','jpg','png','bmp','jpeg','tif','tiff') ORDER BY `id` DESC LIMIT $start,$perpag2e");
		
		$tdnum = 0;
		//$all_tdnum = 0;
		while($row=$SQL->fetch_array($sql)){
		//make new lovely arrays !!
			$user_name = $SQL->fetch_array($SQL->query("SELECT name FROM `{$dbprefix}users` WHERE id='".$row['user']."' "));
			$arr[] = array(id =>$row['id'],
						tdnum=>($tdnum==0) ? "<tr>": "",
						tdnum2=>($tdnum==2) ? "</tr>" : "",
					//	tdnum3=>($all_tdnum >= $nums_rows) ? "</tr>" : "",
						name =>$row['name'],
						href =>$row['folder']."/".$row['name'],
						size =>$lang['FILESIZE']. ':' . Customfile_size($row['size']),
						ups => $lang['FILEUPS'] .' : '.$row['uploads'],
						time => $lang['FILEDATE']. ':' .date("d-m-Y H:a", $row['time']),
						user =>$lang['BY'] . ':' .(($row['user'] == '-1') ? $lang['GUST'] :  $user_name['name']),
						thumb_link => (is_file($row['folder'] . "/thumbs/" . $row['name'] )) ? $row['folder'] . "/thumbs/" . $row['name'] : $row['folder'] . "/" . $row['name'],
						);
			
			
			//fix ... 
			
			$tdnum = ($tdnum==2) ? 0 : $tdnum+1; 
			//$all_tdnum++;

			//
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";

		//when submit by get !! !!
				if ( isset($_POST['submit']) ) {
					if ($del[$row[id]])
					{
						$update = $SQL->query("DELETE FROM `{$dbprefix}files` WHERE id='" . intval($row[id]) . "' ");
						if (!$update) { die($lang['CANT_UPDATE_SQL']);}

						//delete from folder ..
						@unlink ($row['folder'] . "/" .$row['name'] );
							//delete thumb
							if (is_file($row['folder'] . "/thumbs/" . $row['name'] ))
							{@unlink ($row['folder'] . "/thumbs/" . $row['name'] );}
							//delete thumb
					}
			}
		}
		$SQL->freeresult($sql);
	}else{#num_rows
	$no_results = true;
	}
		$total_pages 	= $Pager->getTotalPages(); 
		$page_nums 		= $Pager->print_nums($config[siteurl].'admin.php?cp=img'); 
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

		/////////////pager 
		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}reports`  ORDER BY `id` DESC");
		$nums_rows = $SQL->num_rows($sql);
		$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager = new SimplePager($perpage,$nums_rows,$currentPage);
		$start = $Pager->getStartRow();
		////////////////
		
		$no_results = false;
		
		if ( $nums_rows > 0  ) {
		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}reports`  ORDER BY `id` DESC LIMIT $start,$perpage");
		while($row=$SQL->fetch_array($sql)){
		//make new lovely arrays !!
		$arr[] = array( id =>$row['id'],
						name 		=> $row['name'],
						mail 		=> $row['mail'],
						url  		=> $row['url'],
						text 		=> $row['text'],
						time 		=> date("d-m-Y H:a", $row['time']),
						ip	 		=> $row['ip'],
						ip_finder	=> 'http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=' . $row['ip'] . '&do_search=Search'
						);
			//
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";
			$sen[$row[id]] = ( isset($_POST["v_".$row[id]]) ) ? $_POST["v_".$row[id]] : "";
			//when submit !!
			if ( isset($_POST['submit']) ) {
					if ($del[$row[id]])
					{
					$update = $SQL->query("DELETE FROM `{$dbprefix}reports` WHERE id='" . intval($row['id']) . "' ");
					if (!$update) { die($lang['CANT_UPDATE_SQL']);}
					}
				}
			if ( isset($_POST['reply_submit']) ) {
				if ($sen[$row[id]])
					{
						$to      = $row['mail'];
						$subject = $lang['REPLY_REPORT'] . ':'.$config[sitename];
						$message = "\n " . $lang['WELCOME'] . " ".$row['name']."\r\n " . $lang['U_REPORT_ON'] . " ".$config[sitename]. "\r\n " . $lang['BY_EMAIL'] . ": ".$row['mail']."\r\n" . $lang['ADMIN_REPLIED'] . ": \r\n".$sen[$row[id]]."\r\n\r\n Kleeja Script";
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
	}else{ #num rows
		$no_results = true;
	}
	
		$total_pages 	= $Pager->getTotalPages(); 
		$page_nums 		= $Pager->print_nums($config[siteurl].'admin.php?cp=report'); 
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
		
		/////////////pager 
		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}call` ORDER BY `id` DESC");
		$nums_rows = $SQL->num_rows($sql);
		$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager = new SimplePager($perpage,$nums_rows,$currentPage);
		$start = $Pager->getStartRow();
		////////////////
		
		$no_results = false;
		
		if ( $nums_rows > 0  ) {
		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}call` ORDER BY `id` DESC LIMIT $start,$perpage");
		
		while($row=$SQL->fetch_array($sql)){
		//make new lovely arrays !!
		$arr[] = array( id =>$row['id'],
						name 		=> $row['name'],
						mail 		=> $row['mail'],
						text 		=> $row['text'],
						time 		=> date("d-m-Y H:a", $row['time']),
						ip 			=> $row['ip'],
						ip_finder	=> 'http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=' . $row['ip'] . '&do_search=Search'
						);

			//
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";
			$sen[$row[id]] = ( isset($_POST["v_".$row[id]]) ) ? $_POST["v_".$row[id]] : "";
			//when submit !!
			if ( isset($_POST['submit']) ) {
				if ($del[$row[id]])
				{
				$update = $SQL->query("DELETE FROM `{$dbprefix}call` WHERE id='" . intval($row['id']) . "' ");
				if (!$update) { die($lang['CANT_UPDATE_SQL']);}
				}
			}
			if ( isset($_POST['reply_submit']) ) {
				if ($sen[$row[id]])
				{
				$to      = $row['mail'];
				$subject = $lang['REPLY_CALL'] . ':'.$config[sitename];
				$message = "\n " . $lang['REPLY_CALL'] . " ".$row['name']."\r\n " . $lang['REPLIED_ON_CAL'] . " : ".$config[sitename]. "\r\n " . $lang['BY_EMAIL'] . ": ".$row['mail']."\r\n" . $lang['ADMIN_REPLIED'] . "\r\n".$sen[$row[id]]."\r\n\r\n Kleeja Script";
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

	}else{ #num rows
		$no_results = true;
	}
	
		$total_pages 	= $Pager->getTotalPages(); 
		$page_nums 		= $Pager->print_nums($config[siteurl].'admin.php?cp=calls'); 
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
		
		//posts search ..
		if (isset($_POST['search_user'])){
			$usernamee	= ($_POST['username']!='') ? 'AND name  LIKE \'%'.$SQL->escape($_POST['username']).'%\' ' : ''; 
			$usermailee	= ($_POST['usermail']!='') ? 'AND mail  LIKE \'%'.$SQL->escape($_POST['usermail']).'%\' ' : ''; 

			$sql_text	=	"SELECT * 
			FROM {$dbprefix}users
			WHERE name != '' $usernamee $usermailee
			ORDER BY `id` DESC";
			
		}else{
		$sql_text	=	"SELECT * FROM `{$dbprefix}users`  ORDER BY `id` DESC";
		}
		
		$sql	= $SQL->query($sql_text);
		/////////////pager 
		$nums_rows = $SQL->num_rows($sql);
		$currentPage = (isset($_GET['page']))? intval($_GET['page']) : 1;
		$Pager = new SimplePager($perpage,$nums_rows,$currentPage);
		$start = $Pager->getStartRow();
		////////////////
		
		$no_results = false;
		
		if ( $nums_rows > 0  ) {
		
		$sql	= $SQL->query($sql_text . " LIMIT $start,$perpage");
		
		while($row=$SQL->fetch_array($sql)){
	
			//make new lovely arrays !!
			$ids[$row['id']]	= $row['id'];
			$name[$row[id]] 	= (isset($_POST["nm_".$row[id]])) ? $_POST["nm_".$row[id]]  : $row['name'];
			$mail[$row[id]]		= (isset($_POST["ml_".$row[id]])) ? $_POST["ml_".$row[id]] : $row['mail'];
			$pass[$row[id]]		= (isset($_POST["ps_".$row[id]])) ? $_POST["ps_".$row[id]] :"";
			$admin[$row[id]]	= $row['admin'];
			$del[$row[id]] 		= (isset($_POST["del_".$row[id]])) ? $_POST["del_".$row[id]] : "";

			$arr[] = array( id =>$ids[$row['id']],
						name =>$name[$row[id]],
						mail =>$mail[$row[id]],
						admin =>($admin[$row[id]])? "<input name=\"ad_{$row[id]}\" type=\"checkbox\" checked=\"checked\" />":"<input name=\"ad_{$row[id]}\" type=\"checkbox\"  />"
						);


		
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

	}else{ #num rows
		$no_results = true;
	}
	
		$total_pages 	= $Pager->getTotalPages(); 
		$page_nums 		= $Pager->print_nums($config[siteurl].'admin.php?cp=users'); 
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
		case "rules" ://===================================== [ rules]
		//for style ..
		$stylee = "rules.html";
		//words
		$action 		= "admin.php?cp=rules";
		$n_explain_top 	= $lang['RULES_EXP'];
		$n_submit 		= $lang['UPDATE_RULES'];


		$sql	=	$SQL->query("SELECT rules FROM `{$dbprefix}stats`");
		while($row=$SQL->fetch_array($sql)){

		
			$rulesw = ( isset($_POST["rules_text"]) ) ? $_POST["rules_text"] : $row['rules'];
			$rules = stripslashes($rulesw);
			//addcslashes
				//when submit !!
			if ( isset($_POST['submit']) ) {

				//update
				$update2 = $SQL->query("UPDATE `{$dbprefix}stats` SET
				rules = '". $rulesw ."' ");
				if (!$update2) { die($lang['CANT_UPDATE_SQL']);}
				else
				{
				//delete cache ..
					if (file_exists('cache/data_rules.php')){
					@unlink('cache/data_rules.php');
					}
				}
			}
		}
		$SQL->freeresult($sql);


		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		$text = $lang['RULES_UPDATED'];
		$stylee	= "info.html";
		}
		
		break; //=================================================	
		case "extra" ://===================================== [ extra]
		//for style ..
		$stylee = "extra.html";
		//words
		$action 		= "admin.php?cp=extra";
		$n_explain_top 	= $lang['RULES_EXP'];
		$n_submit 		= $lang['UPDATE_EXTRA'];
		$n_ex_header	= $lang['EX_HEADER_N'];
		$n_ex_footer	= $lang['EX_FOOTER_N'];

		$sql	=	$SQL->query("SELECT ex_header,ex_footer FROM `{$dbprefix}stats`");
		while($row=$SQL->fetch_array($sql)){

		
			$ex_headere = ( isset($_POST["ex_header"]) ) ? $_POST["ex_header"] : $row['ex_header'];
			$ex_footere = ( isset($_POST["ex_footer"]) ) ? $_POST["ex_footer"] : $row['ex_footer'];
			
			$ex_header = stripslashes($ex_headere);
			$ex_footer = stripslashes($ex_footere);
			//addcslashes
				//when submit !!
			if ( isset($_POST['submit']) ) {

				//update
				$update2 = $SQL->query("UPDATE `{$dbprefix}stats` SET
				ex_header = '". $ex_headere ."',
				ex_footer = '". $ex_footere ."'");
				if (!$update2) { die($lang['CANT_UPDATE_SQL']);}
				else
				{
				//delete cache ..
					if (file_exists('cache/data_extra.php')){
					@unlink('cache/data_extra.php');
					}
				}
			}
		}
		$SQL->freeresult($sql);


		//after submit ////////////////
		if ( isset($_POST['submit']) )
		{
		$text = $lang['EXTRA_UPDATED'];
		$stylee	= "info.html";
		}
		
		break; //=================================================
		case "backup" ://===================================== [ backup]
		//thanks for [coder] from montadaphp.net  for his simle lession
		@set_time_limit(0);
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
			$size[$row["Name"]]	= round($row['Data_length']/1024, 2);
		}
		$SQL->freeresult($sql);


		// to output our tables on;ly !!
		$config_t  = array( name =>"{$dbprefix}config",size =>$size["{$dbprefix}config"]);
		$files_t  = array( name =>"{$dbprefix}files",size =>$size["{$dbprefix}files"]);
		$stats_t  = array( name =>"{$dbprefix}stats",size =>$size["{$dbprefix}stats"]);
		$users_t  = array( name =>"{$dbprefix}users",size =>$size["{$dbprefix}users"]);
		$call_t  = array( name =>"{$dbprefix}call",size =>$size["{$dbprefix}call"]);
		$exts_t  = array( name =>"{$dbprefix}exts",size =>$size["{$dbprefix}exts"]);
		$online_t  = array( name =>"{$dbprefix}online",size =>$size["{$dbprefix}online"]);
		$reports_t  = array( name =>"{$dbprefix}reports",size =>$size["{$dbprefix}reports"]);


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
		$n_last_google			= $lang['LAST_GOOGLE'];
		$n_google_num			= $lang['GOOGLE_NUM'];
		$n_last_yahoo			= $lang['LAST_YAHOO'];
		$n_yahoo_num			= $lang['YAHOO_NUM'];
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
		$n_last_visit			= $lang['LAST_VISIT'];
		$n_files_last_visit		= $lang['FLS_LST_VST_SEARCH'];
		$n_imgs_last_visit		= $lang['IMG_LST_VST_SEARCH'];
		
		//last visit
		$last_visit				= ($_SESSION['LAST_VISIT']) ?  date("d-m-Y h:i a", $_SESSION['LAST_VISIT']) : false;
		$h_lst_files			= './admin.php?cp=files&last_visit='.$_SESSION['LAST_VISIT'];
		$h_lst_imgs				= './admin.php?cp=img&last_visit='.$_SESSION['LAST_VISIT'];
		
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
		$per1 = @round($stat_sizes / ($config[total_size] *1048576) ,2) *100;

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
	$rules_name 	= $lang['R_RULES'];
	$rules_url 		= "admin.php?cp=rules";	
	$search_name 	= $lang['R_SEARCH'];
	$search_url 	= "admin.php?cp=search";		
	$extra_name 	= $lang['R_EXTRA'];
	$extra_url 		= "admin.php?cp=extra";	
	$img_ctrl_name 	= $lang['R_IMG_CTRL'];
	$img_ctrl_url 		= "admin.php?cp=img";

	//header
	print $tpl->display("header.html");
 	//body
	print $tpl->display($stylee);
	//footer
	print $tpl->display("footer.html");

	$SQL->close();
?>
<?
##################################################
#						Kleeja 
#
# Filename : admin.php 
# purpose :  control panel for administarator
# copyright 2007 Kleeja.com ..
#
##################################################


	// security .. 
	define ( 'IN_INDEX' , true);
	//include imprtant file .. 
	require ('includes/common.php');
	
	//change style just for admin
	$tpl->Temp = "includes/style_admin/";
	$tpl->Cache = "cache";
	$stylepath = $tpl->Temp;
	
	
	//for security
	if ( !$usrcp->admin() ) { 
			$text = '<span style="color:red;">لست مديراً!!</span><br/><a href="usrcp.php?go=login">دخول</a>';
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
		$stylee = "configs.html";
		//words
		$action = "admin.php?cp=configs";
		$n_submit = "حفظ البيانات";
		$n_yes = "نعم";
		$n_no = "لا";
		$n_none = "لا تغير اسم الملف";
		$n_md5 = "تغيير مع دالة md5";
		$n_time = "تغيير مع دالة time";
		$n_sitename = "إسم المركز";
		$n_sitemail = "بريد المركز";		
		$n_siteurl	= "رابط المركز(مع /)";
		$n_foldername = "إسم مجلد التحميل";
		$n_prefixname = "بداية لاسم الملفات";
		$n_filesnum = "عدد ملفات التحميل";
		$n_siteclose = "إغلاق المركز";
		$n_closemsg = "رسالة الإغلاق";
		$n_decode = " تغيير إسم الملف";
		$n_style = "ستايل المركز";
		$n_sec_down = "الثواني قبل بدء التحميل";		
		$n_statfooter = "إحصائيات الصفحه بالفوتر";
		$n_gzip = "gzip مسرع";
		$n_welcome_msg = "كلمة الترحيب";
		$n_user_system = "نظام العضويات";
		$us_normal = "عادي";
		$us_phpbb = "مربوط phpbb";
		$us_vb = "مربوط vb";
		$n_register = "فتح التسجيل ";
		$n_total_size = "أقصى حجم كلي ميقا";
		
		
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
				if (!$update) {die("لايمكن تحديث البيانات !!");}
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
		elseif($con[user_system] == "3" ) {$user_system_vb = true; }
		//..
		if ($con[statfooter] == "1" ) {$ystatfooter = true; }else {$nstatfooter = true;}
		//..
		if ($con[gzip] == "1" ) {$ygzip = true; }else {$ngzip = true;}
		//..
		if ($con[register] == "1" ) {$yregister = true; }else {$nregister = true;}		
		
		//after submit ////////////////
		if ( isset($_POST['submit']) ) 
		{
		//empty .. 
		if (empty($_POST['sitename']) || empty($_POST['siteurl']) || empty($_POST['foldername']) || empty($_POST['filesnum'])
				|| empty($_POST['style']) )
		{
		$text = "هناك حقول مهمه فارغه !!";
		$stylee	= "err.html";
		}
		elseif (!is_numeric($_POST['filesnum']) || !is_numeric($_POST['sec_down']))
		{
		$text = "رجاءاً .. الحقول الرقميه .. يجب ان تكون رقميه !!";
		$stylee	= "err.html";
		}
		else
		{
		$text = "تم تحديت الإعدادات بنجاح";
		$stylee	= "info.html";
		}
				
		}#submit
		break; //=================================================
		case "exts" ://===================================== [ exts]
		//for style .. 
		$stylee = "exts.html";
		//words
		$action = "admin.php?cp=exts";
		$n_submit = "تعديل البيانات";
		$n_ext = "الإمتداد";
		$n_group = "المجموعه";
		$n_gsize = "الحجم ز";
		$n_gallow ="سماح ز";
		$n_usize = "الحجم م";
		$n_uallow ="سماح م";
		$n_note = "ز :تعني الزوار<br />م : تعني الأعضاء <br />الأحجام تظبط بالبايت.";
		
		
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
				$g_al[$row[id]] = isset($_POST["gal_".$row[id]])  ? 1 : 0 ;
				$u_al[$row[id]] = isset($_POST["ual_".$row[id]])  ? 1 : 0 ;
				
				$update = $SQL->query("UPDATE `{$dbprefix}exts` SET 
				group_id = '" . intval($gr[$row[id]]) . "',
				gust_size = '" . intval($g_sz[$row[id]]) . "',
				gust_allow = '" . intval($g_al[$row[id]]) . "',
				user_size = '" . intval($u_sz[$row[id]]) . "',
				user_allow = '" . intval($u_al[$row[id]]) . "'
				WHERE id = '$row[id]'");
				if (!$update) {die("لايمكن تحديث البيانات !!");}
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
		$text = "تم تحديت الإمتدادات بنجاح";
		$stylee	= "info.html";
		}

		break; //=================================================
		case "files" ://===================================== [ files]
		//for style .. 
		$stylee = "files.html";
		//words
		$action = "admin.php?cp=files";
		$n_submit = "تحديث الملفات";
		$n_name = "الإسم";
		$n_user = "بـ";
		$n_size = "الحجم";
		$n_time ="الوقت";
		$n_uploads ="حُمل";
		$n_type ="النوع";
		$n_folder = "في مجلد";
		$n_report = "تبليغ";
		$n_del ="حذف";

		
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

			if ($del[$row[id]])
			{
				//when submit !!
				if ( isset($_POST['submit']) ) {
				$update = $SQL->query("DELETE FROM `{$dbprefix}files` WHERE id='" . intval($ids[$row[id]]) . "' ");
				if (!$update) {die("لايمكن تحديث البيانات !!");}
				
				//delete from folder .. 
				@unlink ( $folder[$row['id']] . "/" . $name[$row['id']] );
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
						user =>($user[$i] == '-1') ? "زائر":  $s[0],
						);
		}
		if (!is_array($arr)){$arr = array();}
		
		//after submit ////////////////
		if ( isset($_POST['submit']) ) 
		{
		$text = "تم تحديث الملفات بنجاح";
		$stylee	= "info.html";
		}
		break; //=================================================
		case "reports" ://===================================== [ reports]
		//for style .. 
		$stylee = "reports.html";
		//words
		$action = "admin.php?cp=reports";
		$n_submit = "تحديث التبليغات";
		$n_name = "الإسم";
		$n_mail = "البريد";
		$n_url ="الرابط";
		$n_click ="إظغط هنا";
		$n_text ="النص";
		$n_time ="الوقت";
		$n_mouse = "إظغط على أحد التبليغات لتظهر هنا!";
		$n_ip = "ip";
		$n_reply = "[ رد ]";
		$n_del ="حذف";
		
		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}reports`");
		while($row=$SQL->fetch_array($sql)){
		//make new lovely arrays !!
			$ids[$row['id']] =  $row['id'];
			$name[$row['id']]=$row['name'];
			$mail[$row['id']]=$row['mail'];
			$url[$row['id']]=$row['url'];
			$text[$row['id']]=$row['text'];
			$time[$row['id']]=$row['time'];
			$ip[$row['id']]=$row['ip'];

			//
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";
			$sen[$row[id]] = ( isset($_POST["v_".$row[id]]) ) ? $_POST["v_".$row[id]] : "";
			//when submit !!
			if ( isset($_POST['submit']) ) {
				if ($del[$row[id]])
				{		
				$update = $SQL->query("DELETE FROM `{$dbprefix}reports` WHERE id='" . intval($ids[$row[id]]) . "' ");
				if (!$update) {die("لايمكن تحديث البيانات !!");}
				}
				
			if ($sen[$row[id]])
				{		
				$to      = $mail[$row['id']];
				$subject = 'رد على تبليغ:'.$config[sitename];
				$message = "\n اهلاً ".$name[$row['id']]."\r\n بخصوص تبليغك في مركز التحميل  ".$config[sitename]. "\r\n ببريدك الالكتروني: ".$mail[$row['id']]."\r\nفقد قام المدير بالرد التالي: \r\n".$sen[$row[id]]."\r\n\r\n SaaUp Script";
				$headers = 'From: '. $config[sitename]. '<'. $config[sitemail]. '>' . "\r\n" .
				    'MIME-Version: 1.0' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
				$send =  @mail($to, $subject, $message, $headers);
				if (!$send) {die("لا يمكن إرسال الرد ... !!");}
				}
				//may send
			}
		}
		$SQL->freeresult($sql);
		
		if (!is_array($ids)){$ids = array();}//fix bug
		foreach($ids as $i)
		{
		$arr[] = array( id =>$i,
						name =>$name[$i],
						mail =>$mail[$i],
						url =>$url[$i],
						text => $text[$i],
						time =>date("d-m-Y H:a", $time[$i]),
						ip =>"<a href=\"http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=$ip[$i]&do_search=Search\" target=\"blank\">".$ip[$i]."</a>"
						);
		}
		if (!is_array($arr)){$arr = array();}
		
		//after submit ////////////////
		if ( isset($_POST['submit']) ) 
		{
		$text = "تم تحديث التبليغات بنجاح";
		$stylee	= "info.html";
		}
		
		break; //=================================================
		case "calls" ://===================================== [ calls]
		//for style .. 
		$stylee = "calls.html";
		//words
		$action = "admin.php?cp=calls";
		$n_submit = "تحديث المراسلات";
		$n_name = "الإسم";
		$n_mail = "البريد";
		$n_text ="النص";
		$n_time ="الوقت";
		$n_mouse = "إظغط على أحد المراسلات لتظهر هنا!";
		$n_ip = "ip";
		$n_reply = "[ رد ]";
		$n_del ="حذف";
		
		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}call`");
		while($row=$SQL->fetch_array($sql)){
		//make new lovely arrays !!
			$ids[$row['id']] =$row['id'];
			$name[$row['id']]=$row['name'];
			$mail[$row['id']]=$row['mail'];
			$text[$row['id']]=$row['text'];
			$time[$row['id']]=$row['time'];
			$ip[$row['id']]=$row['ip'];

			//
			$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";
			$sen[$row[id]] = ( isset($_POST["v_".$row[id]]) ) ? $_POST["v_".$row[id]] : "";
			//when submit !!
			if ( isset($_POST['submit']) ) {
				if ($del[$row[id]])
				{		
				$update = $SQL->query("DELETE FROM `{$dbprefix}call` WHERE id='" . intval($ids[$row[id]]) . "' ");
				if (!$update) {die("لايمكن تحديث البيانات !!");}
				}
				
			if ($sen[$row[id]])
				{		
				$to      = $mail[$row['id']];
				$subject = 'رد على مراسلتك:'.$config[sitename];
				$message = "\n اهلاً ".$name[$row['id']]."\r\n بخصوص مراسلتك لـ مركز التحميل  ".$config[sitename]. "\r\n ببريدك الالكتروني: ".$mail[$row['id']]."\r\nفقد قام المدير بالرد التالي: \r\n".$sen[$row[id]]."\r\n\r\n SaaUp Script";
				$headers = 'From: '. $config[sitename]. '<'. $config[sitemail]. '>' . "\r\n" .
				    'MIME-Version: 1.0' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
				$send =  @mail($to, $subject, $message, $headers);
				if (!$send) {die("لا يمكن إرسال الرد ... !!");}
				}
				//may send
			}
		}
		$SQL->freeresult($sql);
		
		if (!is_array($ids)){$ids = array();}//fix bug
		foreach($ids as $i)
		{
		$arr[] = array( id =>$i,
						name =>$name[$i],
						mail =>$mail[$i],
						text => $text[$i],
						time =>date("d-m-Y H:a", $time[$i]),
						ip =>"<a href=\"http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=$ip[$i]&do_search=Search\" target=\"blank\">".$ip[$i]."</a>"
						);
		}
		if (!is_array($arr)){$arr = array();}
		
		//after submit ////////////////
		if ( isset($_POST['submit']) ) 
		{
		$text = "تم تحديث المراسلات بنجاح";
		$stylee	= "info.html";
		}
		
		break; //=================================================
		case "users" ://===================================== [ users]
		//for style .. 
		$stylee = "users.html";
		//words
		$action = "admin.php?cp=users";
		$n_name = "الإسم";
		$n_mail = "البريد";
		$n_admin ="مدير";
		$n_pass ="كلمة المرور";
		$n_submit = "تحديث البيانات";
		//$n_files = "ملفاته";
		$n_del ="حذف";

		
		$sql	=	$SQL->query("SELECT * FROM `{$dbprefix}users`");
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
					if (!$update) {die("لايمكن تحديث البيانات !!");}
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
				if (!$update2) {die("لايمكن تحديث البيانات !!");}

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
		$text = "تم تحديث الملفات بنجاح";
		$stylee	= "info.html";
		}
		break; //=================================================
		case "backup" ://===================================== [ backup]
		//thanks for [coder] from montadaphp.net  for his simle lession
		//@set_time_limit(1000);
		//for style .. 
		$stylee = "backup.html";
		//words
		$action = "admin.php?cp=backup";
		$n_explain = "اختر الجداول التي تريد تضمينها في النسخة الاحتياطية ومن ثم اضغط على تحميل";
		$n_name = "الإسم";
		$n_size = "الحجم";
		$n_submit = "تحميل..";

		
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
			if ($sqlf) { $text .= "[جداول] تم إصلاح  " . $row[Name] . "<br />";}

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
		$text .= "[إحصائيات] تم إعادة إحتساب عدد الملفات<br />";
		$text .= "[إحصائيات] تم إعادة إحتساب حجم الملفات <br />";
		}

		//clear cache
		$path = "cache";
		$dh = opendir($path);
		$i=1;
		while (($file = readdir($dh)) !== false) {
		    if($file != "." && $file != ".." && $file != ".htaccess" && $file != "index.html") {
		       $del =  @unlink ( $path . "/" . $file );
			  $text .= "[كاش] تم حذف  ..".$file."<br />";
		        $i++;
		    }
		}
		closedir($dh);

		
		$stylee = "info.html";

		break; //=================================================
		default:
		$Kleja_cp = "لوحة تحكم [ كليجا ]";
		$stylee = "start.html";
		$n_general_stats = "إحصائيات عامه";
		$n_sizes_stats = "إحصائيات الحجم";
		$n_other_stats = "معلومات أخرى";
		$n_files_number = "عدد الملفات كلها";
		$n_stat_sizes = "أحجام الملفات كلها";
		$n_users_number = "عدد الإعضاء";
		$n_welcome_msg = "أهلا وسهلاً بك في لوحة التحكم لمركز التحميل <b>كليجا</b>";
		$N_SIZE_STATUS = "الحجم المستخدم";
		$n_php_version = "إصدار php";
		$n_mysql_version = "إصدار mysql";
		$n_max_execution_time = "max_execution_time";
		$n_upload_max_filesize = "upload_max_filesize";
		
		//data 
		$files_number = $stat_files ;
		$files_sizes = Customfile_size($stat_sizes);	
		$users_number = $stat_users;
		$php_version = 'php '.phpversion();
		$mysql_version = 'MYSQL '.$SQL->mysql_version;
		$max_execution_time =  ini_get('max_execution_time');
		$upload_max_filesize = ini_get('upload_max_filesize');
		
		//size board by percent
		$per1 = round($stat_sizes / ($config[total_size] *1048576) ,2) *100;
		
		
	}#end switch

	
	//admin functions
	function ch_g ($id,$def)
	{
	$s =  array(0=>'',1=>"الصور",2=>"ملفات الظغط",3=>"نصوص",4=>"مستندات",5=>"RealMedia",6=>"WindowsMedia",
				7=>"ملفات الفلاش",8=>"QuickTime",9=>"ملفات أخرى");
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
	$cp_admin = "لوحة التحكم";
	
	$index_name = "<<  رجوع للمركز";
	$configs_name = "إعدادات المركز";
	$cp_name = "بداية لوحة التحكم";
	$cp_url = "admin.php";
	$configs_name = "إعدادات المركز";
	$configs_url = "admin.php?cp=configs";
	$exts_name = "إعدادات الإمتدادات";
	$exts_url = "admin.php?cp=exts";
	$files_name = "التحكم بالملفات";
	$files_url = "admin.php?cp=files";
	$reports_name = "التحكم بالتبليغات";
	$reports_url = "admin.php?cp=reports";
	$calls_name = "التحكم بالمراسلات";
	$calls_url = "admin.php?cp=calls";
	$users_name = "التحكم بالأعضاء";
	$users_url = "admin.php?cp=users";
	$backup_name = "نسخه إحتياطيه";
	$backup_url = "admin.php?cp=backup";
	$repair_name = "صيانه شامله";
	$repair_url = "admin.php?cp=repair";	
	
	//header
	print $tpl->display("header.html");
 	//body	
	print $tpl->display($stylee);
	//footer
	print $tpl->display("footer.html");
	
	$SQL->close();
?>
<?
##################################################
#						Kleeja 
#
# Filename : usrcp.php 
# purpose :  every things for users.
# copyright 2007 Kleeja.com ..
#
##################################################

	// security .. 
	define ( 'IN_INDEX' , true);
	//include imprtant file .. 
	require ('includes/common.php');
	
	//now we will navigate ;)
	switch ($_GET['go']) { 	
	case "login" : //=============================[login]
			$stylee = "login.html";
			$titlee = "دخول";
			$action = "usrcp.php?go=login";
			$L_NAME = "اسم المستخدم";
			$L_PASS = "كلمة المرور";
			$n_submit = "دخول";
			$forget_pass = '<a href="usrcp.php?go=get_pass">نسيت كلمة المرور؟</a>';
			
			
			if ($usrcp->name())
			{
			$text = "انت داخل بالفعل ..<br / > <a href=\"usrcp.php?go=logout\">خروج</a>";
			$stylee = "info.html";
			
			}
			elseif ( isset($_POST['submit']) )
			{
			
			if( $usrcp->data($_POST['lname'],$_POST['lpass']) )
			{
			$text = "لقد تم الدخول بنجاح <br /> <a href=\"index.php\">البدايه</a>";
			$stylee = "info.html";

			}
			else
			{
			$text = "خطأ .. لا يمكنك الدخول!";
			$stylee = "err.html";
			}
		}
		
		break; //=================================================
		case "register" : //=============================[register]
			//config register
			if ( !$config[register] || $config[user_system] !=1 )
			{
			$text = "نأسف ..التسجيل غير متاح ";
			$stylee = "info.html";
			//header
			Saaheader("منطقه محظوره");
			//index
			print $tpl->display($stylee);
			//footer
			Saafooter();
			exit();
			}
			//inlude class
			require ('includes/ocheck_class.php');
			//start ocheck class
			$ch = new ocheck;
			$ch->method = 'post';
			$ch->PathImg = 'images/code';
			
			
			if ($usrcp->name())
			{
			$text = "انت مسجل ..بالفعل ..<br / >";
			$stylee = "info.html";
			}

			
			if ( !isset($_POST['submit']) ) {

	
			$stylee = "register.html";
			$titlee = "تسجيل عضويه";
			$action = "usrcp.php?go=register";
			$L_NAME = "اسم المستخدم";
			$L_PASS = "كلمة المرور";
			$L_MAIL = "البريد الإلكتروني";
			$L_CODE = "كود الأمان";
			$n_submit = "تسجيل";
			$code = $ch->rand();
			$code_input = $ch->show();
			
			}else { // submit
				
						if (empty($_POST['lname']) || empty($_POST['lpass']) || empty($_POST['lmail']) )
						{
							$text = 'هناك حقول ناقصه ..!!';
							$stylee = 'err.html';	
						}	
						else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['lmail'])))
						{
							$text = 'بريد خاطئ ..!!';
							$stylee = 'err.html';	
						}
						else if (strlen($_POST['lname']) < 4 || strlen($_POST['lname']) > 30)
						{
							$text = 'الإسم يجب أن يكون أكبر من 4 حروف واقل من 30 حرف.!!';
							$stylee = 'err.html';	
						}
						else if ($SQL->num_rows($SQL->query("select * from `{$dbprefix}users` where name='$_POST[lname]' ")) !=0 )
						{
							$text = 'الاسم مسجل مسبقاُ ....!!';
							$stylee = 'err.html';	
						}
						else if ($SQL->num_rows($SQL->query("select * from `{$dbprefix}users` where mail='$_POST[lmail]' ")) !=0 )
						{
							$text = 'البريد مسجل مسبقاُ ....!!';
							$stylee = 'err.html';	
						}
						else if ( !$ch->result($_SESSION['ocheck']) )
						{
							$text = 'كود الأمان خاطئ ..!!';
							$stylee = 'err.html';	
						}
						else 
						{
							$name = (string) $SQL->escape($_POST['lname']);
							$pass = (string) md5($SQL->escape($_POST['lpass']));
							$mail = (string) $_POST['lmail'];
							$session_id = (string)  session_id();
							
							
							$sql = "INSERT INTO `{$dbprefix}users` 	(
							`name` ,`password` ,`mail`,`admin`,`session_id`
							) 
							 VALUES (
							 '$name', '$pass', '$mail','0','$session_id'
							 )";
							 
							$insert = $SQL->query($sql);

							if (!$insert) {
							$text =  'خطأ .. لايمكن إدخال المعلومات لقاعدة البيانات!';
							$stylee = 'err.html';	
							}	
							else
							{
							$text = 'شكراً لتسجيلك في مركزنا ..<a href="usrcp.php?go=login">دخول..</a>';
							$stylee = 'info.html';	
							}
						}
			}
		
		break; //=================================================
		case "logout" : //=============================[logout]
			if ( $usrcp->logout() )
			{
			$text = "لقد تم الخروج بنجاح <br /> <a href=\"index.php\">البدايه</a>";
			$stylee = "info.html";
			}
			else
			{
			$text = "خطأ .. لا يمكنك الخروج!";
			$stylee = "err.html";
			}
		
		break; //=================================================
		case "profile" : //=============================[profile]
			//config register
			
			if ($config[user_system] !=1 || !$usrcp->name())
			{
			$text = "نأسف . لايمكنك الدخول لهذه المنطقه";
			$stylee = "info.html";
			//header
			Saaheader("منطقه محظوره");
			//index
			print $tpl->display($stylee);
			//footer
			Saafooter();
			exit();
			}
			
			if ( !isset($_POST['submit']) )
			{
			
			$stylee = "profile.html";
			$titlee = "الملف الشخضي";
			$action = "usrcp.php?go=profile";
			$L_NAME = "اسم المستخدم";
			$L_PASS = "كلمة المرور ..عند التغيير فقط";
			$L_PASS_OLD = "القديمه";
			$L_PASS_NEW = "الجديده";
			$L_PASS_NEW2 = "تكرار الجديده";
			$L_MAIL = "البريد";
			$n_submit = "تغيير";
			$name = $usrcp->name(); //<<
			$mail = $usrcp->mail(); // <<
			
			
			}else{//submit

			if( empty($_POST['pname']) || empty($_POST['pmail']) )
			{
					$text = 'هناك حقول ناقصه ..!!';
					$stylee = 'err.html';	
			}	
			elseif( !empty($_POST['ppass_new'])  && ( ( $_POST['ppass_new'] !=  $_POST['ppass_new2']) 
					||  empty($_POST['ppass_old']) || ( !$usrcp->data($usrcp->name(),$_POST['ppass_old']) ) ) )
			{
					$text = 'كلمة المرور القديمه مهمه واكتب كلمتا المرور الجديدتان بدقه ';
					$stylee = 'err.html';	
				
			}
			else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['pmail'])))
			{
							$text = 'ضيغة بريد خاطئه ..!!';
							$stylee = 'err.html';	
			}
			else
			{
				if ($_POST['pmail'] != $usrcp->mail() ) { $cahnemail = true; }
				if (!empty($_POST['ppass_new'])  ) { $cahnepass = true; }
				
				if ($cahnemail || $cahnepass) 
				{
				$mail	= ($cahnemail)? "mail = '" . $SQL->escape($_POST['pmail']) . "'" : "";
				$pass	= ($cahnepass)? "password = '" . md5($SQL->escape($_POST['ppass_new'])) . "'" : "";
				$comma	= ($cahnemail && $cahnepass)? ",":"";
				$id		= (int)		$usrcp->id();
				
				$update = $SQL->query("UPDATE `{$dbprefix}users` SET 
				".$mail.$comma.$pass."
				WHERE id = '$id'");
				if (!$update) {die("لايمكن تحديث البيانات !!");}
				
					$text = " تم تحديث البيانات..وسوف يتم إستخدمها بدخولك القادم";
					$stylee = "info.html";
				
				}else{
					$text = "لم تقم بإحداث تغييرات ..لن يتم تغيير شيء";
					$stylee = "info.html";
				}
				
			}

		}#else submit
		
		
		break; //=================================================
		case "get_pass" : //=============================[get_pass]
			//config register
			if ( $config[user_system] !=1 )
			{
			$text = "نأسف .إذهب للمنتدى وإسترجع كلمة المرور";
			$stylee = "info.html";
			//header
			Saaheader("منطقه محظوره");
			//index
			print $tpl->display($stylee);
			//footer
			Saafooter();
			exit();
			}
			//inlude class
			
			
			if ($usrcp->name())
			{
			$text = "انت مسجل ..بالفعل ..<br / >";
			$stylee = "info.html";
			}

			
			if ( !isset($_POST['submit']) ) {

	
			$stylee = "get_pass.html";
			$titlee = "إستعادة كلمة المرور";
			$action = "usrcp.php?go=get_pass";
			$L_NAME = "بريدك المسجل لدينا";
			$n_explain = "قم بكتابة بريدك .. لنرسل كلمة المرور عليه";
			$n_submit = "إستعادة كلمة المرور";


			}else { // submit
				
						if (empty($_POST['rmail']))
						{
							$text = 'حقل فارغ ..!!';
							$stylee = 'err.html';	
						}	
						else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['rmail'])))
						{
							$text = 'بريد خاطئ ..!!';
							$stylee = 'err.html';	
						}
						else if ($SQL->num_rows($SQL->query("select * from `{$dbprefix}users` where mail='$_POST[rmail]' ")) ==0 )
						{
							$text = 'لا يوجد بريد كهذا في قاعدة البيانات لدينا ....!!';
							$stylee = 'err.html';	
						}
						else 
						{
						
						$sql = $SQL->query("select * from `{$dbprefix}users` where mail='$_POST[rmail]' ");
						$newpass=substr(md5(time()),0,5);
						
							while($row=$SQL->fetch_array($sql)){
							$to      = $row['mail'];
							$subject = 'إستعادة كلمة المرور:'.$config[sitename];
							$message = "\n اهلاً ".$row[name]."\r\n لقد قمت بطلب كلمة مرور جديده  لعضويتك في".$config[sitename]. "\r\n كلمة المرور : ".$newpass."\r\n\r\n SaaUp Script";
							$headers = 'From: '. $config[sitename]. '<'. $config[sitemail]. '>' . "\r\n" .
							    'MIME-Version: 1.0' . "\r\n" .
							    'X-Mailer: PHP/' . phpversion();
								
							$newpass	= (string)	$newpass;
							$id			= (int)		$row[id];
							
							$update = $SQL->query("UPDATE `{$dbprefix}users` SET 
							password = '" . md5($SQL->escape($newpass)) . "'
							WHERE id = '$id'");	
							if (!$update) {die("لايمكن تحديث كلمة المرور !!");}	
							}
							
							$send =  @mail($to, $subject, $message, $headers);
							
							if (!$send) {
							$text =  'خطأ ..لم يتم ارسال كلمة المرور الجديده!';
							$stylee = 'err.html';	
							}	
							else
							{
							$text = 'تم إرسال كلمة المرور الجديده..<a href="usrcp.php?go=login">دخول..</a>';
							$stylee = 'info.html';	
							}
						unset($newpass);
						}
			}
		
		break; //=================================================
		default:
		$text = "مكان خاطئ";
		$stylee = "err.html";
	}#end switch
	
	
	//show style ...
	if (!$titlee) {$titlee = "نظام العضويات"; }
	//header
	Saaheader($titlee);
	//index
	print $tpl->display($stylee);
	//footer
	Saafooter();
?>
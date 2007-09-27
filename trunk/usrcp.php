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
			$titlee = $lang['LOGIN'];
			$action = "usrcp.php?go=login";
			$L_NAME = $lang['USERNAME'];
			$L_PASS = $lang['PASSWORD'];
			$n_submit = $lang['LOGIN'];
			$err_empty_name = $lang['EMPTY_USERNAME'];
			$err_empty_pass = $lang['EMPTY_PASSWORD'];
			
			$forget_pass = '<a href="usrcp.php?go=get_pass">' .$lang['LOSS_PASSWORD'] . '</a>';
			
			
			if ($usrcp->name())
			{
			$text = $lang['LOGINED_BEFORE'].' ..<br / > <a href="usrcp.php?go=logout">' . $lang['LOGOUT'] . '</a>';
			$stylee = "info.html";
			}
			
			elseif ( isset($_POST['submit']) )
			{
		
			//if ($config[user_system] == 3){  // vb 
			
			
			//	if ( empty($_POST['lname']) || empty($_POST['challenge']) ) //challenge just for vb [i hate vb]
			//	{
			//	$text = $lang['EMPTY_FIELDS'];
			//	$stylee = "err.html";
			//	}
			//	elseif( $usrcp->data($_POST['lname'],$_POST['challenge']) )
			//	{
			//	$text = $lang['LOGIN_SUCCESFUL'].' <br /> <a href="./index.php">'. $lang['HOME'] . '</a>';
			//	$stylee = "info.html";
			//	}
			//	else
			//	{
			//	$text = $lang['LOGIN_ERROR'];
			//	$stylee = "err.html";
			//	}
			
			//}
			//else
			//{
				if ( empty($_POST['lname']) || empty($_POST['lpass']) )
				{
				$text = $lang['EMPTY_FIELDS'];
				$stylee = "err.html";
				}
				elseif( $usrcp->data($_POST['lname'],$_POST['lpass']) )
				{
				$text = $lang['LOGIN_SUCCESFUL'].' <br /> <a href="./index.php">'. $lang['HOME'] . '</a>';
				$stylee = "info.html";
				}
				else
				{
				$text = $lang['LOGIN_ERROR'];
				$stylee = "err.html";
				}
			//}

		}
	
		break; //=================================================
		case "register" : //=============================[register]
			//config register
			if ( !$config[register] || $config[user_system] !=1 )
			{
			$text = $lang['REGISTER_CLOSED'];
			$stylee = "info.html";
			//header
			Saaheader($lang['PLACE_NO_YOU']);
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
			$text = $lang['REGISTERED_BEFORE'];
			$stylee = "info.html";
			}

			
			if ( !isset($_POST['submit']) ) {

	
			$stylee = "register.html";
			$titlee = $lang['REGISTER'];
			$action = "usrcp.php?go=register";
			$L_NAME = $lang['USERNAME'];
			$L_PASS = $lang['PASSWORD'];
			$L_MAIL = $lang['EMAIL'];
			$L_CODE = $lang['VERTY_CODE'];
			$n_submit =  $lang['REGISTER'];
			$code = $ch->rand();
			$code_input = $ch->show();
			
			}else { // submit
				
						if (empty($_POST['lname']) || empty($_POST['lpass']) || empty($_POST['lmail']) )
						{
							$text = $lang['EMPTY_FIELDS'];
							$stylee = 'err.html';	
						}	
						else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['lmail'])))
						{
							$text = $lang['WRONG_EMAIL'];
							$stylee = 'err.html';	
						}
						else if (strlen($_POST['lname']) < 4 || strlen($_POST['lname']) > 30)
						{
							$text 	=  $lang['WRONG_NAME'];
							$stylee = 'err.html';	
						}
						else if ($SQL->num_rows($SQL->query("select * from `{$dbprefix}users` where name='$_POST[lname]' ")) !=0 )
						{
							$text = $lang['EXIST_NAME'];
							$stylee = 'err.html';	
						}
						else if ($SQL->num_rows($SQL->query("select * from `{$dbprefix}users` where mail='$_POST[lmail]' ")) !=0 )
						{
							$text = $lang['EXIST_EMAIL'];
							$stylee = 'err.html';	
						}
						else if ( !$ch->result($_SESSION['ocheck']) )
						{
							$text = $lang['WRONG_VERTY_CODE'];
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
							
							//calculate stats ..s
							$update1 = $SQL->query("UPDATE `{$dbprefix}stats` SET `users`=users+1 ");
							if ( !$update1 ){ die($lang['CANT_UPDATE_SQL']);}
							//calculate stats ..e
							
							if (!$insert) {
							$text =  $lang['CANT_INSERT_SQL'];
							$stylee = 'err.html';	
							}	
							else
							{
							$text = $lang['REGISTER_SUCCESFUL'] . '<a href="usrcp.php?go=login">' . $lang['LOGIN'] . '</a>';
							$stylee = 'info.html';	
							}

						}
			}
		
		break; //=================================================
		case "logout" : //=============================[logout]
			if ( $usrcp->logout() )
			{
			$text =$lang['LOGOUT_SUCCESFUL'] . '<br /> <a href="index.php">' . $lang['HOME'] . '</a>';
			$stylee = "info.html";
			}
			else
			{
			$text = $lang['LOGOUT_ERROR'];
			$stylee = "err.html";
			}
		
		break; //=================================================
		case "filecp" : //=============================[filecp]
			$stylee 		= "filecp.html";
			$titlee 		= $lang['FILECP'];
			$N_EDIT_FILES 	= $lang['EDIT_U_FILES'];
			$action 		= "usrcp.php?go=filecp";
			$n_submit_files = $lang['DEL_SELECTED'];
			
			//te get files and update them !!
			$sql	=	$SQL->query("SELECT id,name,folder FROM `{$dbprefix}files` WHERE user='".$usrcp->id()."' ORDER BY `id` DESC");
			while($row=$SQL->fetch_array($sql)){
			//make new lovely arrays !!
				$ids[$row['id']]	=$row['id'];
				$name[$row['id']]	=$row['name'];
				$folder[$row['id']]	=$row['folder'];
				
				//
				$del[$row[id]] = ( isset($_POST["del_".$row[id]]) ) ? $_POST["del_".$row[id]] : "";


					//when submit !!
					if ( isset($_POST['submit_files']) ) {
						if ($del[$row[id]])
						{
							$update = $SQL->query("DELETE FROM `{$dbprefix}files` WHERE id='" . intval($ids[$row[id]]) . "' ");
							if (!$update) {die($lang['CANT_UPDATE_SQL']);}
							
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
			$arr[] = array( id =>$i,
							name =>"<a href=\"./download.php?id={$i}\" target=\"blank\">".$name[$i]."</a>"
							);
			}
			if (!is_array($arr)){$arr = array();}
			
			//after submit ////////////////
			if ( isset($_POST['submit_files']) ) 
			{
			$text = $lang['FILES_UPDATED'];
			$stylee	= "info.html";
			}
				
		break; //=================================================
		case "profile" : //=============================[profile]
			if (!$usrcp->name())
			{
			$text = $lang['USER_PLACE'];
			$stylee = "info.html";
			//header
			Saaheader($lang['PLACE_NO_YOU']);
			//index
			print $tpl->display($stylee);
			//footer
			Saafooter();
			exit();
			}

			$stylee 		= "profile.html";
			$titlee 		= $lang['PROFILE'];
			$N_EDIT_DATA 	= $lang['EDIT_U_DATA'];
			$action 		= "usrcp.php?go=profile";
			$n_submit_data 	= $lang['EDIT_U_DATA'];
			$L_NAME 		= $lang['USERNAME'];
			$L_PASS 		= $lang['PASS_ON_CHANGE'];
			$L_PASS_OLD 	= $lang['OLD'];
			$L_PASS_NEW 	= $lang['NEW'];
			$L_PASS_NEW2 	= $lang['NEW_AGAIN'];
			$L_MAIL 		= $lang['EMAIL'];
			$name 			= $usrcp->name(); //<<
			$mail 			= $usrcp->mail(); // <<
			$data_forum 	= ($config[user_system]==1 ) ? TRUE : FALSE ;
			$goto_forum 	= '<a href="' . $forum_path . '">' . $lang['PFILE_4_FORUM'] . '</a>';
			
			
		
		
			if ( isset($_POST['submit_data']) )
			{	

			if( empty($_POST['pname']) || empty($_POST['pmail']) )
			{
					$text = $lang['EMPTY_FIELDS'];
					$stylee = 'err.html';	
			}	
			elseif( !empty($_POST['ppass_new'])  && ( ( $_POST['ppass_new'] !=  $_POST['ppass_new2']) 
					||  empty($_POST['ppass_old']) || ( !$usrcp->data($usrcp->name(),$_POST['ppass_old']) ) ) )
			{
					$text = $lang['PASS_O_PASS2'];
					$stylee = 'err.html';	
				
			}
			else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['pmail'])))
			{
							$text = $lang['WRONG_EMAIL'];
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
				if (!$update) {die($lang['CANT_UPDATE_SQL']);}
				
					$text =  $lang['DATA_CHANGED_O_LO'];
					$stylee = "info.html";
				
				}else{
					$text = $lang['DATA_CHANGED_NO'];
					$stylee = "info.html";
				}
				
			}

		}#else submit
		
		
		break; //=================================================
		case "get_pass" : //=============================[get_pass]
			//config register
			if ( $config[user_system] !=1 )
			{
			$text = '<a href="' . $forum_path . '">' . $lang['LOST_PASS_FORUM'] . '</a>';
			$stylee = "info.html";
			//header
			Saaheader($lang['PLACE_NO_YOU']);
			//index
			print $tpl->display($stylee);
			//footer
			Saafooter();
			exit();
			}
			//inlude class
			
			
			if ($usrcp->name())
			{
			$text = $lang['LOGINED_BEFORE'];
			$stylee = "info.html";
			}

			
			if ( !isset($_POST['submit']) ) {

	
			$stylee = "get_pass.html";
			$titlee = $lang['GET_LOSTPASS'];
			$action = "usrcp.php?go=get_pass";
			$L_NAME = $lang['EMAIL'];
			$n_explain = $lang['E_GET_LOSTPASS'];
			$n_submit = $lang['GET_LOSTPASS'];


			}else { // submit
				
						if (empty($_POST['rmail']))
						{
							$text = $lang['EMPTY_FIELDS'];
							$stylee = 'err.html';	
						}	
						else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['rmail'])))
						{
							$text = $lang['WRONG_EMAIL'];
							$stylee = 'err.html';	
						}
						else if ($SQL->num_rows($SQL->query("select * from `{$dbprefix}users` where mail='$_POST[rmail]' ")) ==0 )
						{
							$text = $lang['WRONG_DB_EMAIL'];'';
							$stylee = 'err.html';	
						}
						else 
						{
						
						$sql = $SQL->query("select * from `{$dbprefix}users` where mail='$_POST[rmail]' ");
						$newpass=substr(md5(time()),0,5);
						
							while($row=$SQL->fetch_array($sql)){
							$to      = $row['mail'];
							$subject = $lang['GET_LOSTPASS'] . ':' . $config[sitename];
							$message = "\n " . $lang['WELCOME'] . " ".$row[name]."\r\n " . $lang['GET_LOSTPASS_MSG'] . "\r\n " . $lang['PASSWORD'] . " : ".$newpass."\r\n\r\n SaaUp Script";
							$headers = 'From: '. $config[sitename]. '<'. $config[sitemail]. '>' . "\r\n" .
							    'MIME-Version: 1.0' . "\r\n" .
							    'X-Mailer: PHP/' . phpversion();
								
							$newpass	= (string)	$newpass;
							$id			= (int)		$row[id];
							
							$update = $SQL->query("UPDATE `{$dbprefix}users` SET 
							password = '" . md5($SQL->escape($newpass)) . "'
							WHERE id = '$id'");	
							if (!$update) {die($lang['CANT_UPDATE_SQL']);}	
							}
							
							$send =  @mail($to, $subject, $message, $headers);
							
							if (!$send) {
							$text =  $lang['CANT_SEND_NEWPASS'];
							$stylee = 'err.html';	
							}	
							else
							{
							$text = $lang['OK_SEND_NEWPASS'] . '<a href="usrcp.php?go=login">' . $lang['LOGIN'] . '</a>';
							$stylee = 'info.html';	
							}
						unset($newpass);
						}
			}
		
		break; //=================================================
		default:
		$text = $lang['ERROR_NAVIGATATION'];
		$stylee = "err.html";
	}#end switch
	
	
	//show style ...
	if (!$titlee) {$titlee = $lang['USERS_SYSTEM']; }
	//header
	Saaheader($titlee);
	//index
	print $tpl->display($stylee);
	//footer
	Saafooter();
?>
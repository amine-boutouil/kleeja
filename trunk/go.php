<?
##################################################
#						Kleeja 
#
# Filename : go.php 
# purpose :  File for Navigataion .
# copyright 2007 Kleeja.com ..
#
##################################################

	// security .. 
	define ( 'IN_INDEX' , true);
	//include imprtant file .. 
	require ('includes/common.php');
	
	switch ($_GET['go']) { 	
	case "guide" : //=============================[guide]
	$stylee = "guide.html";
	$titlee = "دليل الملفات";
	$text_msg_g = 'الإمتدادات المسموحه للزوار وامتداداتها:';
	$text_msg_u = 'الإمتدادات المسموحه للأعضاء وامتداداتها:';
	$L_EXT	= 'الإمتداد';
	$L_SIZE = 'الحجم';
	//make it loop
	foreach($g_exts as $s )
	{
	$gggg[] = array( 'ext' => $s,
					'num' => Customfile_size($g_sizes[$s])
					
	);
	}
	if (!is_array($gggg)){$gggg = array();}
	
	//make it loop
	foreach($u_exts as $s )
	{
	$uuuu[] = array( 'ext' => $s,
					'num' => Customfile_size($u_sizes[$s])
					
	);
	}
	if (!is_array($uuuu)){$uuuu = array();}
	

	break; //=================================================
	case "report" : //=============================[report]
	

	//inlude class
	require ('includes/ocheck_class.php');
	//start ocheck class
	$ch = new ocheck;
	$ch->method = 'post';
	$ch->PathImg = 'images/code';
	
	if ( !isset($_POST['submit']) ) 
	{
	$stylee = "report.html";
	$titlee = "تبليغ ..";
	$url_id = $config[siteurl]."download.php?id=".intval($_GET['id']);
	$action = "./go.php?go=report";
	$submit = "إرسال..";
	$L_NAME = "الإسم";
	$L_MAIL = "البريد";
	$L_URL = "الرابط";
	$L_TEXT = "السبب";
	$L_CODE = "كود الأمان";
	$code = $ch->rand();
	$code_input = $ch->show();
	$id_d = intval($_GET['id']);
	

		// first 
	if (!$_GET['id']) {
			$text = 'لم تحدد مقال ..!!';
			$stylee = 'err.html';	
	}
		
	}
	else
	{
	
		if (empty($_POST['rname']) || empty($_POST['rmail']) || empty($_POST['rurl']) )
		{
			
			$text = 'هناك حقول ناقصه ..!!';
			$stylee = 'err.html';	

		}
		else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['rmail'])))
		{
			$text = 'بريد خاطئ ..!!';
			$stylee = 'err.html';	
		}
		else if (strlen($_POST['rtext']) > 300 )
		{
			$text = 'رجاءاً .. حقل السبب لا يمكن ملأه بأكثر من 300 حرف ..!!';
			$stylee = 'err.html';	
		}
		else if ( !$ch->result($_SESSION['ocheck']) )
		{
			$text = 'كود الأمان خاطئ ..!!';
			$stylee = 'err.html';	
		}
		else 
		{
				$name	= (string)	$SQL->escape($_POST['rname']);
				$text	= (string)	$SQL->escape($_POST['rtext']);
				$mail	= (string)	$_POST['rmail'];
				$url	= (string)	$_POST['rurl'];
				$time 	= (int)		time();
				$rid	= (int)		$_POST['rid'];
				
				if (getenv('HTTP_X_FORWARDED_FOR')){
				$ip= (string) getenv('HTTP_X_FORWARDED_FOR');
				} else {
				$ip= (string) getenv('REMOTE_ADDR');}

			
				$insert = $SQL->query("INSERT INTO `{$dbprefix}reports` (
				`name` ,`mail` ,`url` ,`text` ,`time` ,`ip`
				)
				VALUES (
				'$name','$mail','$url','$text','$time','$ip'
				)");

				$update = $SQL->query("UPDATE {$dbprefix}files SET 						
								report=report+1
	                            WHERE id='$rid' ");
				
				
				if (!$insert) {
				$text =  'خطأ .. لايمكن إدخال المعلومات لقاعدة البيانات!';
				$stylee = 'err.html';	
				}	
				else
				{
				$text = 'تم التبليغ . شكراً لإهتمامك  ';
				$stylee = 'info.html';	
				}
				
				if (!$update){ die("لم يتم تحديث عدد التقارير!!!!");}
		}
	}
	break; //=================================================
	case "rules" : //=============================[rules]
	$stylee = "rules.html";
	$titlee = "شروط المركز";
	
	//get rules from txt 
	$filename = "rules.txt";
	//prevent error !!
	if (filesize($filename) > 10 )
	{
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	}
	else
	{
	$contents = "لا يوجد قوانين حالياً ..";
	}
	$text_msg = 'هذه هي شروط مركز التحميل ';
	
	
	break; //=================================================
	case "call" : //=============================[call]

	//inlude class
	require ('includes/ocheck_class.php');
	//start ocheck class
	$ch = new ocheck;
	$ch->method = 'post';
	$ch->PathImg = 'images/code';
	
	
	if ( !isset($_POST['submit']) ) 
	{
	$stylee = "call.html";
	$titlee = "إتصل بنا";
	$action = "./go.php?go=call";
	$submit = "إرسال..";
	$L_NAME = "الإسم";
	$L_MAIL = "البريد";
	$L_TEXT = "النص";
	$L_CODE = "كود الأمان";
	$code = $ch->rand();
	$code_input = $ch->show();
	
	}
	else
	{
	
		if (empty($_POST['cname']) || empty($_POST['cmail']) || empty($_POST['ctext']) )
		{
			
			$text = 'هناك حقول ناقصه ..!!';
			$stylee = 'err.html';	

		}	
		else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['cmail'])))
		{
			$text = 'بريد خاطئ ..!!';
			$stylee = 'err.html';	
		}
		else if (strlen($_POST['ctext']) > 300 )
		{
			$text = 'رجاءاً .. حقل النص لا يمكن ملأه بأكثر من 300 حرف ..!!';
			$stylee = 'err.html';	
		}
		else if ( !$ch->result($_SESSION['ocheck']) )
		{
			$text = 'كود الأمان خاطئ ..!!';
			$stylee = 'err.html';	
		}
		else 
		{
			$name = (string) $SQL->escape($_POST['cname']);
			$text = (string) $SQL->escape($_POST['ctext']);
			$mail = (string) $_POST['cmail'];
			$timee = (int) time();
			if (getenv('HTTP_X_FORWARDED_FOR')){$ip= (string) getenv('HTTP_X_FORWARDED_FOR');
			} else {$ip= (string) getenv('REMOTE_ADDR');}
	
			$sql = "INSERT INTO `{$dbprefix}call` 	(
			`name` ,`text` ,`mail` ,`time` ,`ip` 
			) 
			 VALUES (
			 '$name', '$text', '$mail', '$timee', '$ip'
			 )";
			 
			$insert = $SQL->query($sql);

			if (!$insert) {
			$text =  'خطأ .. لايمكن إدخال المعلومات لقاعدة البيانات!';
			$stylee = 'err.html';	
			}	
			else
			{
			$text = 'تم الإرسال. . سوف يتم الرد قريباً  ';
			$stylee = 'info.html';	
			}
		}
	}
	
	break; //=================================================
	case "down" : //=============================[down]
	//maybe .. 
	function saff ($var) {
   	$var = str_replace(array('http', ':','//','/','>', '<', '.com', '.net', '.org'), '', $var);
	return $var;
	}
	
	
	
	if ( isset($_GET['i']) )
	{
	//for safe
	$id = intval ($_GET['i']);
	
	//updates ups ..
	$update = $SQL->query("UPDATE {$dbprefix}files SET 						
							uploads=uploads+1
                            WHERE id='$id' ");
	if (!$update){ die("لم يتم تحديث عدد التحميلات !!!!");}

	//for safe !!!
	$n = saff($_GET[n]);
	$f = saff($_GET[f]);
	
	
	//start download ,, 
	header("Location: ./$f/$n");
	
	}
	
	break; //=================================================
	/*case "example" : //=============================[example]
	$stylee = "example.html"; //>> style 
	$titlee = "دليل الملفات"; // >> title
	
	
	break; //=================================================*/
	default:
	$text = "مكان خاطئ";
	$stylee = "err.html";
	}#end switch
	
	
	//show style ...
	//header
	Saaheader($titlee);
	//index
	print $tpl->display($stylee);
	//footer
	Saafooter();
?>
<?
##################################################
#						Kleeja 
#
# Filename : download.php 
# purpose :  when user  request file to  download it.
# copyright 2007 Kleeja.com ..
#
##################################################

	// security .. 
	define ( 'IN_INDEX' , true);
	//include imprtant file .. 
	require ('includes/common.php');

	
	if ( isset($_GET['id']) )
	{
	//for safe
	$id = intval ($_GET['id']);
	  	
	$sql	=	$SQL->query("SELECT * FROM {$dbprefix}files where id=$id");
	
	if ($SQL->num_rows($sql) != 0  ) 
	{
		while($row=$SQL->fetch_array($sql)){
		@extract ($row);
	}
	$SQL->freeresult($sql);   
	
	// SOME WORDS FOR TEMPLATE
	$file_found = "تم إيجاد الملف .. ";
	$wait  = "إنتظر رجاءاً ..";
	$click = "اضغط هنا لتنزيل الملف";
	$err_jv = "لا بد من تفعيل الجافا سكربت في  متصفحك !!";
	$url_file = "./go.php?go=down&amp;n=$name&amp;f=$folder&amp;i=$id";
	$seconds_w = $config[sec_down];
	$time = date("d-m-Y H:a", $time);
	$size = Customfile_size($size);
	$information = "معلومات عن الملف ";
	$L_FILE = "إسم الملف";
	$L_SIZE= "حجم الملف";
	$L_TYPE= "نوع الملف";
	$L_TIME= "تم رفعه في";
	$L_UPS= "عدد التحميلات";
	$L_REPORT = "تبليغ : ملف مخالف للقوانين";
	$REPORT = "./go.php?go=report&amp;id=$id";
	
	$sty = 'download.html';	

	}
	else
	{
		$text = 'لم نتمكن من إيجاد الملف ..!!';
		$sty = 'err.html';	
	}
	 // show style ...
	 
	//header
	Saaheader("تحميل !");
 	//body	
	print $tpl->display($sty);
	//footer
	Saafooter();
	 //
	}
	else if( isset($_GET['img']) )
	{
	//for safe
	$img = intval ($_GET['img']);
	
	//updates ups ..
	$sql	=	$SQL->query("SELECT name,folder,type FROM {$dbprefix}files where id=$img");
	
	if ($SQL->num_rows($sql) != 0  ) 
	{
		while($row=$SQL->fetch_array($sql)){
		$n =  $row[name];
		$f =  $row[folder];
		$t =  $row[type];
		}
	}
	else
	{
		$text = 'لم نتمكن من إيجاد الصوره ..!!';
		$sty = 'err.html';	
	}
	$SQL->freeresult($sql);   
	
	$update = $SQL->query("UPDATE {$dbprefix}files SET 						
							uploads=uploads+1
                            WHERE id='$img' ");
	if (!$update){ die("لم يتم تحديث عدد التحميلات !!!!");}

	//must be img //	
	$imgs = array('png','gif','jpg','jpeg','tif','tiff');
	if (!in_array($t,$imgs) )
	{
		$text = 'ليست صوره  ..هذا ملف!!<br> توجه إلى <a href="./download.php?id=$img"></a>';
		$sty = 'err.html';	
	}
	else
	{
	//show img
	header("Location: ./$f/$n");
	}
	}
	else 
	{
	die ('<STRONG style="color:red">مكان خاطئ</STRONG>');
	}
	
	
	
?>
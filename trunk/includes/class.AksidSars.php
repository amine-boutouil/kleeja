<?php
##################################################
#						Kleeja 
#
# Filename : class.AksidSars.php 
# purpose :  cache for all script.
# copyright 2007 Kleeja.com ..
#class by : Nadorino [@msn.com]
# changed specially for this script , i love this  sentence .. (  start from same way was people finished)   
##################################################

	  if (!defined('IN_COMMON'))
	  {
	  echo '<strong><br /><span style="color:red">[NOTE]: This Is Dangrous Place !! [2007 saanina@gmail.com]</span></strong>';
	  exit();
	  }
	  
class AksidSars
{
    var $asarsi;  // متغير اسم المجلد
    var $amchan; //رابط الصفحة
    var $thwara; //عدد الحقول
    var $ansaq;  // الأنساق
    var $ansaqimages;   // انساق الصور
    var $isam;     // اسم الصورة
	var $sizes;
	var $typet;
	var $id_for_url;
    var $baddarisam;  // بعد تغيير الاسم
    var $linksite;    // رابط الموقع
    var $tashfir;     // اختيار نوع اسم الصورة md5 او time
	var $id_user;
	var $errs = array();
	
	

/**
// source : php.net
 */
 function watermark($name, $logo){
	$system=explode(".",$name);
	if (preg_match("/jpg|jpeg/",$system[1])){$src_img=imagecreatefromjpeg($name);}
	if (preg_match("/png/",$system[1])){$src_img=imagecreatefrompng($name);}
	if (preg_match("/gif/",$system[1])){$src_img=imagecreatefromgif($name);}
	
	$src_logo = imagecreatefrompng($logo);
	
    $bwidth  = imageSX($src_img);
    $bheight = imageSY($src_img);
    $lwidth  = imageSX($src_logo);
    $lheight = imageSY($src_logo);
    $src_x = $bwidth - ($lwidth + 5);
    $src_y = $bheight - ($lheight + 5);
    ImageAlphaBlending($src_img, true);
    ImageCopy($src_img,$src_logo,$src_x,$src_y,0,0,$lwidth,$lheight);
	
	if (preg_match("/jpg|jpeg/",$system[1])){imagejpeg($src_img, $name);}
	if (preg_match("/png/",$system[1])){imagepng($src_img, $name);}
	if (preg_match("/gif/",$system[1])){imagegif($src_img, $name);}
}


/*
	Function createthumb($name,$filename,$new_w,$new_h)
	example : createthumb('pics/apple.jpg','thumbs/tn_apple.jpg',100,100);
	creates a resized image
	source :http://icant.co.uk/articles/phpthumbnails/
*/	
function createthumb($name,$filename,$new_w,$new_h)
{
	$system=explode(".",$name);
	if (preg_match("/jpg|jpeg/",$system[1])){$src_img=imagecreatefromjpeg($name);}
	if (preg_match("/png/",$system[1])){$src_img=imagecreatefrompng($name);}
	if (preg_match("/gif/",$system[1])){$src_img=imagecreatefromgif($name);}
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	if ($old_x > $old_y) 
	{
		$thumb_w=$new_w;
		$thumb_h=$old_y*($new_h/$old_x);
	}
	if ($old_x < $old_y) 
	{
		$thumb_w=$old_x*($new_w/$old_y);
		$thumb_h=$new_h;
	}
	if ($old_x == $old_y) 
	{
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	if (preg_match("/png/",$system[1]))
	{
		imagepng($dst_img,$filename); 
	}
	elseif(preg_match("/jpg|jpeg/",$system[1])) {
		imagejpeg($dst_img,$filename); 
	} 
	elseif(preg_match("/gif/",$system[1]))
	{
		imagegif($dst_img,$filename); 
	}
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
}


	// for show
    function thwara(){  //thwara بداية
	$sss ='<script type="text/javascript">//<![CDATA[ 
	totalupload_num='.$this->thwara.'-1;
	function makeupload(){
	upload_show_num=\'\';
	uploaded=2;
	upload_num=document.uploader.upload_num.value-1;
	if(upload_num>totalupload_num){	upload_num=totalupload_num;	}
	for(i=0;i<upload_num;i++){
	thisuid = uploaded+i;
	upload_show_num=upload_show_num+\'<input type="file" name="file[]"><br>\';
	
		}
		document.getElementById(\'upload_forum\').innerHTML  = upload_show_num;
	}
	function plus ()
	{
	var num = '.$this->thwara.';
	if (document.uploader.upload_num.value < num )
	{
	document.uploader.upload_num.value++;
	}
	else
	{
	alert(\'هذا آخر حد يمكنك تحميله !!\');
	}
	}
	function minus ()
	{
	var num = '.$this->thwara.';
	if (document.uploader.upload_num.value != 1 )
	{
	document.uploader.upload_num.value--;
	}

	}
	function form_submit() {
		var load = document.getElementById(\'loadbox\');
		document.uploader.submit();
		load.style.display = \'block\';
		load.src = \'images/loading.gif\';
	}

//]]>>
</script>
	';
    $sss .= '<form name="uploader" action="'.$this->amchan.'" method="post"  encType="multipart/form-data"> ';
    $sss .= '<input type="file" name="file[]"><br><span id="upload_forum"></span>';
    $sss .= '<input name="mraupload" onclick="javascript:plus();makeupload();" type="button" value="+" /><input name="mreupload" onclick="javascript:minus();makeupload();" type="button" value="-" /><br>';	
    $sss .= '<input type="submit" value="[ تحميل الملفات ]"  onClick="form_submit();"><input type="text" name="upload_num" value="1" size="1" /> </form>';
	$sss .= '<div id="loadbox"><img src="images/loading.gif" id="loading"></div>';
	return $sss;
} //thwara نهاية
function aksid(){  //Aksid بداية
		global $SQL,$dbprefix,$config;
	//by saanina
	if(file_exists($this->asarsi)){   //بداية التحقق من المجلد هل هو موجود ام لا
	 //يبقى فارغا اذا كان المجلد موجود
	for($i=0;$i<$this->thwara;$i++){   // for بداية


	$this->baddarisam=@explode(".",$_FILES['file']['name'][$i]);
	$this->baddarisam=$this->baddarisam[count($this->baddarisam)-1];
	$this->typet = $this->baddarisam;
	if($this->tashfir == "time"){  //if($this->tashfir == "time"){
	$zaid=time();
	$this->baddarisam=$this->isam.$zaid.$i.".".$this->baddarisam;
	}
	elseif($this->tashfir == "md5")
	{
	$zaid=md5(time());
	$zaid=substr($zaid,0,10);
	$this->baddarisam=$this->isam.$zaid.$i.".".$this->baddarisam;
	}  //if($this->tashfir == "time"){
	else
	{
	// اسم الصورة الحقيقي
	$this->baddarisam=$_FILES['file']['name'][$i];
	}
	if(empty($_FILES['file']['tmp_name'][$i])){ // التحقق من الملف هل هو فارغ
	// فارغ
	}
else
{


if(file_exists($this->asarsi.'/'.$_FILES['file']['name'][$i])){  // if بداية total
	$this->errs[]=  'هذا الملف موجود مسبقا';
    }  
	elseif( preg_match ("#[\\\/\:\*\?\<\>\|\"]#", $this->baddarisam ) ){
    $this->errs[]= 'اسم الملف غير يحوي أحرف غير مسموحه ['.$this->baddarisam.']';
    }
    elseif(!in_array(strtolower($this->typet),$this->ansaq)){
    $this->errs[]= 'هذه الصيغة غير مدعومه ['.$this->typet.']';
    }
	elseif($this->sizes[strtolower($this->typet)] > 0 && $_FILES['file']['size'][$i] >= $this->sizes[strtolower($this->typet)])
	{
	$this->errs[]=  'الحجم للملف المختار يجب أن يكون أقل من '.$this->sizes[$this->typet].'';
	}
    else
    {
#------------------------------------------------------------------------------------------------------------------------------------------------
//ob_end_flush();  
//flush();
$file=move_uploaded_file($_FILES['file']['tmp_name'][$i], $this->asarsi."/".$this->baddarisam); 
//flush();
#------------------------------------------------------------------------------------------------------------------------------------------------


if($file){ //if بداية
//---------->
				$name 	= (string)	$SQL->escape($this->baddarisam);
				$size	= (int) 	$_FILES['file']['size'][$i];
				$type 	= (string)	$SQL->escape($this->typet);
				$folder	= (string)	$SQL->escape($this->asarsi);
				$timeww	= (int)		time();
				$user	= (int)		$this->id_user;
				
				$insert	= $SQL->query("INSERT INTO `{$dbprefix}files`(
				`name` ,`size` ,`time` ,`folder` ,`type`,`user`
				) 
				VALUES (
				'$name','$size','$timeww','$folder','$type','$user'
				)");

				if (!$insert) { $this->errs[]=  'خطأ .. لايمكن إدخال المعلومات لقاعدة البيانات!';}	
				
				$this->id_for_url =  $SQL->insert_id();
				
				//calculate stats ..s
				$update1 = $SQL->query("UPDATE `{$dbprefix}stats` SET 
				`files`=files+1,
				`sizes`=sizes+" . $size . ",
				`last_file`='" . $folder ."/". $name . "'
				");
				if ( !$update1 ){ die("لم يتم تحديث الإحصائيات !!!!");}
				//calculate stats ..e
//<---------------
	//must be img //	
$this->ansaqimages= array('png','gif','jpg','jpeg','tif','tiff');
$this->ansaqthumbs= array('png','jpg','jpeg','gif');

//show imgs
if (in_array(strtolower($this->typet),$this->ansaqimages)){

	//make thumbs
	if($config[thumbs_imgs] && in_array(strtolower($this->typet),$this->ansaqthumbs))
	{
	@$this->createthumb($this->asarsi."/".$this->baddarisam,$this->asarsi.'/thumbs/'.$this->baddarisam,100,100);
	$extra_thmb = 'مصغره للمنتديات :<br /><textarea rows=2 cols=49 rows=1>[url='.$this->linksite."download.php?img=".$this->id_for_url.'][img]'.$this->linksite."download.php?thmb=".$this->id_for_url.'[/img][/url]</textarea><br />';
	}
	//write on image
	if($config[write_imgs] && in_array(strtolower($this->typet),$this->ansaqthumbs))
	{
		$this->watermark($this->asarsi . "/" . $this->baddarisam, 'images/watermark.png');
	}
	
	//then show
	$this->errs[] = '
			.. لقد تم تحميل الملف بنجاح<br />
			رابط مباشر :<br /><textarea rows=2 cols=49 rows=1>'.$this->linksite."download.php?img=".$this->id_for_url.'</textarea>
			شفرة المنتديات :<br /><textarea rows=2 cols=49 rows=1>[url='.$config[siteurl].'][img]'.$this->linksite."download.php?img=".$this->id_for_url.'[/img][/url]</textarea><br />
			'.$extra_thmb;
}else {
	//then show other files
	$this->errs[] = '
			.. لقد تم تحميل الملف بنجاح<br />
			رابط مباشر :<br /><textarea cols=49 rows=1>'.$this->linksite."download.php?id=".$this->id_for_url.'</textarea>';
}

}
else
{
	$this->errs[]=  'خطأ... لم يتم تحميل الملف  لاسباب غير معروفة';
}   // if نهاية


} // if نهاية total
}  // التحقق من الملف هل هو فارغ   نهاية
} // نهاية for

}    // نهاية التحقق من الملف
else  
{      
	$jadid=@mkdir($this->asarsi);
	$jadid2=@mkdir($this->asarsi.'/thumbs');
	if($jadid){  

	$this->errs[]=  "لقد تم انشاء مجلد جديد<br />";

	$fo=@fopen($this->asarsi."/index.html","w"); 
	$fo2=@fopen($this->asarsi."/thumbs/index.html","w"); 
	$fw=@fwrite($fo,'<p>مرحبا بك</p>'); 
	$fw2=@fwrite($fo2,'<p>مرحبا بك</p>'); 
	$fi=@fopen($this->asarsi."/.htaccess","w");
	$fi2=@fopen($this->asarsi."/thumbs/.htaccess","w");
	$fy=@fwrite($fi,'<Files *>
		Order Allow,Deny
		Deny from All
	</Files>');
	$fy2=@fwrite($fi2,'<Files ~ "\.(php*|s?p?x?i?html|cgi|asp|php3|php4|pl|htm)$">   
	  deny from all   
	</Files>   
	php_flag engine off');
	$chmod=@chmod($this->asarsi,0777);
	$chmod2=@chmod($this->asarsi.'/thumbs/',0777);

	if(!$chmod){
	$this->errs[]=   " لم يتم اعطاء التصريح للمجلد ";
	} //if !chmod
}
else
{
	$this->errs[]=  " <font color=red><b>ضروري انشاء مجلد<b></font>".$this->asarsi."  لم يتم انشاء مجلد جديد لاسباب لا اعرفها<br />";
}      // نهاية التحقق من انشاء ممجلد جديد


}  // نهاية التحقق من الملف




} //Aksid نهاية
	

}#end class

?>
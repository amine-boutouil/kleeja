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
				$name 	= (string) $SQL->escape($this->baddarisam);
				$size	= (int) $_FILES['file']['size'][$i];
				$type 	= (string) $SQL->escape($this->typet);
				$folder	= (string) $SQL->escape($this->asarsi);
				$timeww	= (int) time();
				$user	= (int) $this->id_user;
				
				$insert	= $SQL->query("INSERT INTO `{$dbprefix}files`(
				`name` ,`size` ,`time` ,`folder` ,`type`,`user`
				) 
				VALUES (
				'$name','$size','$timeww','$folder','$type','$user'
				)");

				if (!$insert) { $this->errs[]=  'خطأ .. لايمكن إدخال المعلومات لقاعدة البيانات!';}	
				
				$this->id_for_url =  $SQL->insert_id();
//<---------------
	//must be img //	
$this->ansaqimages= array('png','gif','jpg','jpeg','tif','tiff');
//show imgs
if (in_array(strtolower($this->typet),$this->ansaqimages)){
$this->errs[] = '
		.. لقد تم تحميل الملف بنجاح<br />
		رابط مباشر :<br /><textarea rows=2 cols=49 rows=1>'.$this->linksite."download.php?img=".$this->id_for_url.'</textarea>
		شفرة المنتديات :<br /><textarea rows=2 cols=49 rows=1>[url='.$config[siteurl].'][img]'.$this->linksite."download.php?img=".$this->id_for_url.'[/img][/url]</textarea><br />
';
}else {
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
else    // نهاية التحقق من الملف
{        // نهاية التحقق من الملف
$jadid=@mkdir($this->asarsi);
if($jadid){   //بداية التحقق من انشاء مجلد جديد
    echo"لقد تم انشاء مجلد جديد<br />";
$fo=@fopen($this->asarsi."/index.html","w"); // انشاء صفحة index.html   كل ما في الأمر هو حماية الملفات التي حملتها الى هذا المجلد
$fw=@fwrite($fo,'<p>مرحبا بك</p>'); // كتابة نص ترحيبي في صفحة الأندكس
$fi=@fopen($this->asarsi."/.htaccess","w");
$fy=@fwrite($fi,'<Files *>
	Order Allow,Deny
	Deny from All
</Files>');
$chmod=@chmod($this->asarsi,0777);
if(!$chmod){ //if(!$chmod){
$this->errs[]=   " لم يتم اعطاء التصريح للمجلد ";
} //if(!$chmod){
}
else
{
$this->errs[]=  " <font color=red><b>ضروري انشاء مجلد<b></font>".$this->asarsi."  لم يتم انشاء مجلد جديد لاسباب لا اعرفها<br />";
}      // نهاية التحقق من انشاء ممجلد جديد


}  // نهاية التحقق من الملف
} //Aksid نهاية
	

}#end class

##################
function ByteSize($bytes)  
    { 
    $size = $bytes / 1024; 
    if($size < 1024) 
        { 
        $size = number_format($size, 2); 
        $size .= ' KB'; 
        }  
    else  
        { 
        if($size / 1024 < 1024)  
            { 
            $size = number_format($size / 1024, 2); 
            $size .= ' MB'; 
            }  
        else if ($size / 1024 / 1024 < 1024)   
            { 
            $size = number_format($size / 1024 / 1024, 2); 
            $size .= ' GB'; 
            }  
        } 
    return $size; 
    } 



?>
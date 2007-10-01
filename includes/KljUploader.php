<?php
##################################################
#						Kleeja
#
# Filename : class.AksidSars.php
# purpose :  cache for all script.
# copyright 2007 Kleeja.com ..
#class by : saanina based on class.AksidSars.php  of Nadorino [@msn.com]
##################################################

	  if (!defined('IN_COMMON'))
	  {
	  echo '<strong><br /><span style="color:red">[NOTE]: This Is Dangrous Place !! [2007 saanina@gmail.com]</span></strong>';
	  exit();
	  }

class KljUploader
{
    var $folder;
    var $action; //رابط الصفحة
    var $filesnum; //عدد الحقول
    var $types;  // الأنساق
    var $ansaqimages;   // انساق الصور
    var $filename;     // اسم الصورة
	var $sizes;
	var $typet;
	var $sizet;
	var $id_for_url;
    var $filename2;  // بعد تغيير الاسم
    var $linksite;    // رابط الموقع
    var $decode;     // اختيار نوع اسم الصورة md5 او time
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

function tpl(){  //thwara بداية
	global $lang,$config;

	$sss ='<script type="text/javascript">//<![CDATA[
	totalupload_num=' . $this->filesnum . '-1;
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
	var num = ' . $this->filesnum . ';
	if (document.uploader.upload_num.value < num )
	{
	document.uploader.upload_num.value++;
	}
	else
	{
	alert("' . $lang['MORE_F_FILES'] . '");
	}
	}
	function minus ()
	{
	var num = ' . $this->filesnum . ';
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
		var txt = document.getElementById("texttype");
		var fle = document.getElementById("filetype");
		txt.style.display = \'none\';
		fle.style.display = \'none\'
	}
	function wdwdwd (sub,ch){
	var submit = document.getElementById(sub);
	var checker = document.getElementById(ch);
	if ( checker.checked ){submit.disabled = ""; }else{submit.disabled = "disabled"; }
	}

	function showhide() {
	var txt = document.getElementById("texttype");
	var fle = document.getElementById("filetype");

	if (txt.style.display == \'none\'){
	txt.style.display = \'block\';
	fle.style.display = \'none\'
	}else{
	fle.style.display = \'block\';
	txt.style.display = \'none\'
	}


	}
//]]>>
</script>';
	//www url icon
	if ($config['www_url'] != '0') {
    $sss .= '<a href="#"  onclick="showhide();" title="' . $lang['CHANG_TO_URL_FILE'] . '"><img src="images/urlORfile.gif" alt="' . $lang['CHANG_TO_URL_FILE'] . '"  /></a>';
    }

    $sss .= '<form name="uploader" action="' . $this->action . '" method="post"  encType="multipart/form-data" onsubmit="form_submit();"> ';
	$sss .= '<div id="loadbox"><img src="images/loading.gif" id="loading"></div>';


	//file input
    $sss .= '<div id="filetype"><input type="file" name="file[]"><br><span id="upload_forum"></span>';
    $sss .= '<input name="mraupload" onclick="javascript:plus();makeupload();" type="button" value="+" />';
	$sss .= '<input name="mreupload" onclick="javascript:minus();makeupload();" type="button" value="-" />';
	$sss .= '<br /><input id="checkr" type="checkbox" onclick="wdwdwd(\'submitr\',\'checkr\');" />' . $lang['AGREE_RULES'];
	$sss .= '<br /><input type="submit" id="submitr" name="submitr" value="' . $lang['DOWNLOAD_F'] . '"  disabled="disabled" />';
	$sss .= '<input type="text" name="upload_num" value="1" size="1" readonly="readonly"/></div>';

	//www input
	if ($config['www_url'] != '0') {
    $sss .= '<div id="texttype"><input type="text" name="file" size="50" value="' . $lang['PAST_URL_HERE'] . '" onclick="this.value=\'\'" style="color:silver;" dir="ltr">';
	$sss .= '<br /><input id="checkr2" type="checkbox" onclick="wdwdwd(\'submittxt\',\'checkr2\');" />' . $lang['AGREE_RULES'];
	$sss .= '<br /><input type="submit" id="submittxt" name="submittxt" value="' . $lang['DOWNLOAD_T'] . '"   disabled="disabled" /></div>';
	}

	$sss .= '</form>';

	return $sss;

} //thwara نهاية

################################


################################

function process () {
		global $SQL,$dbprefix,$config,$lang;
		global $use_ftp,$ftp_server,$ftp_user,$ftp_pass;

//for folder
if(!file_exists($this->folder))   // نهاية التحقق من المجلد
{
	$jadid=@mkdir($this->folder);
	$jadid2=@mkdir($this->folder.'/thumbs');
	if($jadid){

	$this->errs[]= $lang['NEW_DIR_CRT'];

	$fo=@fopen($this->folder."/index.html","w");
	$fo2=@fopen($this->folder."/thumbs/index.html","w");
	$fw=@fwrite($fo,'<p>KLEEJA ..</p>');
	$fw2=@fwrite($fo2,'<p>KLEEJA ..</p>');
	$fi=@fopen($this->folder."/.htaccess","w");
	$fi2=@fopen($this->folder."/thumbs/.htaccess","w");
	$fy=@fwrite($fi,'RemoveType .php .php3 .phtml .pl .cgi .asp .htm .html
	php_flag engine off');
	$fy2=@fwrite($fi2,'RemoveType .php .php3 .phtml .pl .cgi .asp .htm .html
	php_flag engine off');
	$chmod=@chmod($this->folder,0777);
	$chmod2=@chmod($this->folder.'/thumbs/',0777);

	if(!$chmod){$this->errs[]=   $lang['PR_DIR_CRT'];} //if !chmod
	}
	else
	{
		$this->errs[]= '"<font color=red><b>' . $lang['CANT_DIR_CRT'] . '<b></font>';
	}
}

	//then wut did u click
	if ( isset($_POST['submitr']) ) { $wut=1; }
	elseif( isset($_POST['submittxt']) ){$wut=2;}

	// no url
	if ($wut == 1) {
	 #-----------------------------------------------------------------------------------------------------------------------------------------------------------#
	for($i=0;$i<$this->filesnum;$i++){
	$this->filename2=@explode(".",$_FILES['file']['name'][$i]);
	$this->filename2=$this->filename2[count($this->filename2)-1];
	$this->typet = $this->filename2;
	$this->sizet = $_FILES['file']['size'][$i];
		//tashfer [decode]
		if($this->decode == "time"){
		$zaid=time();
		$this->filename2=$this->filename.$zaid.$i.".".$this->filename2;
		}
		elseif($this->tashfir == "md5")
		{
		$zaid=md5(time());
		$zaid=substr($zaid,0,10);
		$this->filename2=$this->filename.$zaid.$i.".".$this->filename2;
		}  //if($this->tashfir == "time"){
		else
		{
		// اسم الصورة الحقيقي
		$this->filename2=$_FILES['file']['name'][$i];
		}
		//end tashfer

	if(empty($_FILES['file']['tmp_name'][$i])){ }

	elseif(file_exists($this->folder.'/'.$_FILES['file']['name'][$i]))
	{
	$this->errs[]=  $lang['SAME_FILE_EXIST'];
    }
	elseif( preg_match ("#[\\\/\:\*\?\<\>\|\"]#", $this->filename2 ) )
	{
    $this->errs[]= $lang['WRONG_F_NAME'] . '['.$this->filename2.']';
    }
    elseif(!in_array(strtolower($this->typet),$this->types))
	{
    $this->errs[]= $lang['FORBID_EXT'] . '['.$this->typet.']';
    }
	elseif($this->sizes[strtolower($this->typet)] > 0 && $this->sizet >= $this->sizes[strtolower($this->typet)])
	{
	$this->errs[]=  $lang['SIZE_F_BIG'] . ' ' . Customfile_size($this->sizes[$this->typet]);
	}
    else
    {
#----------------------------------------------------------uplaod----------------------------------------------------------------------
//ob_end_flush();
//flush();
	if (!$use_ftp)
	{
				$file = move_uploaded_file($_FILES['file']['tmp_name'][$i], $this->folder."/".$this->filename2);
	}
	else // use ftp account
	{
				// set up a connection or die
				$conn_id = @ftp_connect($ftp_server);
	            // Login with username and password
	            $login_result = @ftp_login($conn_id, $ftp_user, $ftp_pass);

	            // Check the connection
	            if ((!$conn_id) || (!$login_result)) {
	                  $this->errs[]= $lang['CANT_CON_FTP'] . $ftp_server;
	                }
	            // Upload the file
	            $file = @ftp_put($conn_id, $this->folder."/".$this->filename2,$_FILES['file']['tmp_name'][$i], FTP_BINARY);
				@ftp_close($conn_id);
	}
	//flush();

	if ($file) {
	$this->saveit ($this->filename2,$this->folder,$this->sizet,$this->typet);
	} else {
	$this->errs[]	= $lang['CANT_UPLAOD'];
	}

	}
}
	 #-----------------------------------------------------------------------------------------------------------------------------------------------------------#

	}#wut=1
	elseif ( $wut == 2 && $config['www_url'] == '1' )
	{


		$filename =  basename($_POST['file']);
		$this->filename2=@explode(".",$filename);
		$this->filename2=$this->filename2[count($this->filename2)-1];
		$this->typet = $this->filename2;

		//tashfer [decode]
		if($this->decode == "time"){
		$zaid=time();
		$this->filename2=$this->filename.$zaid.$i.".".$this->filename2;
		}
		elseif($this->tashfir == "md5")
		{
		$zaid=md5(time());
		$zaid=substr($zaid,0,10);
		$this->filename2=$this->filename.$zaid.$i.".".$this->filename2;
		}
		else
		{
		// اسم الملف الحقيقي
		$this->filename2=$filename;
		}
		//end tashfer


	if(empty($_POST['file'])){
	$this->errs[]	= $lang['NO_FILE_SELECTED'];
	}
	elseif(!preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $_POST['file']))
	{
	$this->errs[]=  $lang['WRONG_LINK'];
    }
	elseif(file_exists($this->folder.'/'.$filename))
	{
	$this->errs[]=  $lang['SAME_FILE_EXIST'];
    }
	elseif( preg_match ("#[\\\/\:\*\?\<\>\|\"]#", $this->filename2 ) )
	{
    $this->errs[]= $lang['WRONG_F_NAME'] . '['.$this->filename2.']';
    }
    elseif(!in_array(strtolower($this->typet),$this->types))
	{
    $this->errs[]= $lang['FORBID_EXT'] . '['.$this->typet.']';
    }
	else //end err .. start upload
	{

	//sooo
	if ( function_exists('curl_init') )
	{

	// attempt retrieveing the url
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,$_POST['file']);
	curl_setopt($curl_handle,CURLOPT_TIMEOUT,30);
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,15);
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl_handle,CURLOPT_FAILONERROR,1);
	$data = curl_exec($curl_handle);
	curl_close($curl_handle);

	$this->sizet = strlen($data);

		if($this->sizes[strtolower($this->typet)] > 0 && $this->sizet >= $this->sizes[strtolower($this->typet)])
		{
		$this->errs[]=  $lang['SIZE_F_BIG'] . ' ' . Customfile_size($this->sizes[$this->typet]);
		}
		else
		{
		//then ..write new file
	    $fp2 = @fopen($this->folder."/".$this->filename2,"w");
	    fwrite($fp2,$data);
	    fclose($fp2);
		}

		$this->saveit ($this->filename2,$this->folder,$this->sizet,$this->typet);
	}
	else
	{
	$this->errs[]	= $lang['CANT_UPLAOD'];
	}

}#else

	}#end wut2

}#END process





function saveit ($filname,$folderee,$sizeee,$typeee) { //
		global $SQL,$dbprefix,$config,$lang;

				// sometime cant see file after uploading.. but ..
				@chmod($filname."/".$folderee, 0666);//0755
//---------->
				$name 	= (string)	$SQL->escape($filname);
				$size	= (int) 	$sizeee;
				$type 	= (string)	$SQL->escape($typeee);
				$folder	= (string)	$SQL->escape($folderee);
				$timeww	= (int)		time();
				$user	= (int)		$this->id_user;
				$code_del=(string)	md5(time());

				$insert	= $SQL->query("INSERT INTO `{$dbprefix}files`(
				`name` ,`size` ,`time` ,`folder` ,`type`,`user`,`code_del`
				)
				VALUES (
				'$name','$size','$timeww','$folder','$type','$user','$code_del'
				)");

				if (!$insert) { $this->errs[]=  $lang['CANT_INSERT_SQL'];}

				$this->id_for_url =  $SQL->insert_id();

				//calculate stats ..s
				$update1 = $SQL->query("UPDATE `{$dbprefix}stats` SET
				`files`=files+1,
				`sizes`=sizes+" . $size . ",
				`last_file`='" . $folder ."/". $name . "'
				");
				if ( !$update1 ){ die($lang['CANT_UPDATE_SQL']);}
				//calculate stats ..e
//<---------------
	//must be img //
$this->imgstypes	= array('png','gif','jpg','jpeg','tif','tiff');
$this->thmbstypes	= array('png','jpg','jpeg','gif');
if ($config[del_url_file]){$extra_del = $lang['URL_F_DEL'] . ':<br /><textarea rows=2 cols=49 rows=1>'.$this->linksite.'go.php?go=del&amp;id='.$this->id_for_url.'&amp;cd='.$code_del.'</textarea><br/>';}


//show imgs
if (in_array(strtolower($this->typet),$this->imgstypes)){

	//make thumbs
	if( ($config[thumbs_imgs]!=0) && in_array(strtolower($this->typet),$this->thmbstypes))
	{
	@$this->createthumb($folderee."/".$filname,$folderee.'/thumbs/'.$filname,100,100);
	$extra_thmb = $lang['URL_F_THMB'] . ':<br /><textarea rows=2 cols=49 rows=1>[url='.$this->linksite."download.php?img=".$this->id_for_url.'][img]'.$this->linksite."download.php?thmb=".$this->id_for_url.'[/img][/url]</textarea><br />';
	}
	//write on image
	if( ($config[write_imgs]!=0) && in_array(strtolower($this->typet),$this->thmbstypes))
	{
		$this->watermark($folderee . "/" . $filname, 'images/watermark.png');
	}

	//then show
	$this->errs[] = $lang['IMG_DOWNLAODED'] . '<br />
			' . $lang['URL_F_IMG'] . ':<br /><textarea rows=2 cols=49 rows=1>'.$this->linksite."download.php?img=".$this->id_for_url.'</textarea>
			' . $lang['URL_F_BBC'] . ':<br /><textarea rows=2 cols=49 rows=1>[url='.$config[siteurl].'][img]'.$this->linksite."download.php?img=".$this->id_for_url.'[/img][/url]</textarea><br />
			'.$extra_thmb.$extra_del;

}else {
	//then show other files
	$this->errs[] = $lang['FILE_DOWNLAODED'] . '<br />
			' . $lang['URL_F_FILE'] . ':<br /><textarea cols=49 rows=1>'.$this->linksite."download.php?id=".$this->id_for_url.'</textarea>
			' . $lang['URL_F_BBC'] . ':<br /><textarea rows=2 cols=49 rows=1>[url]'.$this->linksite."download.php?id=".$this->id_for_url.'[/url]</textarea><br />
			'.$extra_del;
}

unset ($filename,$folderee,$sizeee,$typeee);

}#save it




}#end class

?>
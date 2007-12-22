<?php
##################################################
#						Kleeja 
#
# Filename : js.php
# purpose : javascript file
# copyright 2007 Kleeja.com ..
#class by : Saanina [@gmail.com]
##################################################

	  if (!defined('IN_COMMON'))
	  {
	  echo '<strong><br /><span style="color:red">[NOTE]: This Is Dangrous Place !! [2007 saanina@gmail.com]</span></strong>';
	  exit();
	  }
	  

	// js for iuplder

	function js_uploader($num){
	global $lang;
	
	return '<script type="text/javascript">//<![CDATA[
	function $(id) { return  document.getElementById(id);}
		var totalupload_num=' . $num. '-1;
	function makeupload(type){
		var value = (type==1) ?  $("upload_num").value : $("upload_f_num").value;
		var upload_forum3 = (type==1) ? $("upload_forum") :  $("upload_f_forum");
		var upload_show_num="";
		uploaded=2;
		upload_num=(value-1);
		if(upload_num>totalupload_num){	upload_num=totalupload_num;	}
		for(i=0;i<upload_num;i++){
			thisuid = uploaded+i;
			upload_show_num =(type==1) ? upload_show_num+\'<input type="file" name="file[]"><br>\' : upload_show_num+\'<input type="text" size="50" name="file[\'+thisuid+\']" value="' . $lang['PAST_URL_HERE'] . '" style="color:silver;" dir="ltr"><br>\';
		}
		upload_forum3.innerHTML  = upload_show_num;
	
		
		
	}
	function plus (type){
	var value = (type==1) ?  $("upload_num") : $("upload_f_num");
	var num = ' . $num . ';
		if (value.value < num ){
		value.value++;
		}else{
		alert("' . $lang['MORE_F_FILES'] . '");
		}
	}
	function minus (type){
	var value = (type==1) ?  $("upload_num") : $("upload_f_num");
	var num = ' . $num . ';
	if (value.value != 1 )value.value--;
	}
	function form_submit() {
		var load = document.getElementById(\'loadbox\');
		var uploader = document.getElementById("uploader");
		load.style.display = "block";
		load.innerHTML = "<img src=\"images/loading.gif\" id=\"loading\">";	
		
		uploader.style.display = "none";
		document.uploader.submit();
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
	}
	//end 
?>
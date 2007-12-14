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
	totalupload_num=' . $num. '-1;
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
	var num = ' . $num . ';
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
	var num = ' . $num . ';
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
	}
	//end 
?>
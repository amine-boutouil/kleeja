<?php
# KLEEJA INSTALLER ...
# updated  [2/2009]
# this file hav many updates .. dont use previous ones
# last edit by : saanina

/*
include important files
*/

	
define ( 'IN_COMMON' , true);
$_path = "../";
(file_exists($_path . 'config.php')) ? include ($_path . 'config.php') : null;
include ($_path . 'includes/functions.php');
include ('func_inst.php');





/*
//print header
*/
if (!isset($_POST['lang']))
{
	echo $header_inst;
}

if(!isset($_GET['step']))
{
	$_GET['step'] = 'language';
}

/*
//nvigate ..
*/
switch ($_GET['step']) 
{
default:
case 'language':
		if (isset($_POST['lang'])) 
		{
			if (!empty($_POST['lang']))
			{
				//go to .. 2step
				echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'].'?step=choose&lang='.$_POST['lang'].'">';
			//	@header("Location:".$_SERVER[PHP_SELF]."?step=check"); /* Redirect browser */
			}
		}
		else //no language
		{ 

	//get language from LANGUAGE folder
		$path = "langs";
		$dh = opendir($path);
		$lngfiles = '';
		$i=1;
		while (($file = readdir($dh)) !== false)
		{
		    if($file != "." && $file != ".."  && $file != "index.html")
			{
			$file = str_replace('.php','', $file);
			  $lngfiles .= '<option value="' . $file . '">' . $file . '</option>';
		        $i++;
		    }
		}
		closedir($dh);

	// show  language list ..
	echo '<br />		
	<div class="centery">
	<fieldset>
		<img src="img/map.gif" style="border:0" alt="al-Idrisi Map">
	<br /><form  action="' . $_SERVER['PHP_SELF'] . '?step=language&'.get_lang(1).'" method="post">
	<select name="lang" style="width: 352px">
	' . $lngfiles . '
	</select>
	<br /><input name="submitlang" type="submit" value=" [  Next  ] " /><br /><br /><br /></form></fieldset></div>';

		}//no language else



break; // end case language
case 'choose' :
		echo '<fieldset><span style="color:green;">' . $lang['INST_CHOOSE_INSTALLER'] . '</span><br /><br /><br />';
		echo '<a href="./install.php?'.get_lang(1).'"><img src="img/installer.gif" alt="installer" /><br />  ' . $lang['INST_INSTALL_CLEAN_VER'] . ' </a><br /><br />';
		echo '<a href="./update.php?'.get_lang(1).'"><img src="img/updater.gif" alt="updater" /> <br /> ' . $lang['INST_UPDATE_P_VER'] . ' </a><br /><br /><br />';

		echo '<a href="http://www.kleeja.com"><span style="color:black;">www.kleeja.com</span></a></fieldset><br /><br />';

break;
}#endOFswitch



/*
//print footer
*/
echo $footer_inst;


?>
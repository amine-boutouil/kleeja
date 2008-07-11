<?php
# KLEEJA INSTALLER ...
# updated 22/4/1249 [4/2008]
# this file hav many updates .. dont use previous ones
# last edit by : saanina

/*
include important files
*/

	
	define ( 'IN_COMMON' , true);
	$path = "../includes/";
	include ($path.'config.php');
	include ($path.'functions.php');
	include ('func_inst.php');





/*
//print header
*/
if (!isset($_POST['lang']))
{
	print $header_inst;
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
				setcookie("lang", htmlspecialchars($_POST['lang']), time()+60*60*24*365);
				echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'].'?step=choose">';
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
	print '<br />		
	<div class="centery">
	<a href="http://en.wikipedia.org/wiki/Muhammad_al-Idrisi" target="_blank" title="al-Idrisi Map">
		<img src="img/aledrisiMap.gif" style="border:0" alt="al-Idrisi Map">
	</a>
	<br /><form  action="' . $_SERVER['PHP_SELF'] . '?step=language" method="post">
	<select name="lang" style="width: 352px">
	' . $lngfiles . '
	</select>
	<br /><input name="submitlang" type="submit" value="[  >>>  ] " /><br /><br /><br /></form></div>';

		}//no language else



break; // end case language
case 'choose' :
		print '<span style="color:green;">' . $lang['INST_CHOOSE_INSTALLER'] . '</span><br/><br/><br/>';
		print '<a href="./install.php"><img src="img/installer.gif" alt="installer" /><br/>  ' . $lang['INST_INSTALL_CLEAN_VER'] . ' </a><br/><br/>';
		print '<a href="./update.php"><img src="img/updater.gif" alt="updater" /> <br/> ' . $lang['INST_UPDATE_P_VER'] . ' </a><br/><br/><br/>';

		print '<a href="http://www.kleeja.com"><span style="color:black;">www.kleeja.com</span></a><br/><br>';

break;
}#endOFswitch



/*
//print footer
*/
print $footer_inst;


?>
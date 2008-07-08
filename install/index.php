<?php
# KLEEJA INSTALLER ...
# updated 22/4/1249 [4/2008]
# this file hav many updates .. dont use previous ones
# last edit by : saanina

/*
include important files
*/
	// ...header ..  i like it ;)
	header('Content-type: text/html; charset=UTF-8');
	header('Cache-Control: private, no-cache="set-cookie"');
	header('Expires: 0');
	header('Pragma: no-cache');
	
	define ( 'IN_COMMON' , true);
	$path = "../includes/";
	include ($path.'config.php');
	include ($path.'functions.php');



    // support for php older than 4.1.0
    if ( phpversion() < '4.1.0' )
	{
        $_GET 			= $HTTP_GET_VARS;
        $_POST 			= $HTTP_POST_VARS;
        $_COOKIE 		= $HTTP_COOKIE_VARS;
        $_SERVER 		= $HTTP_SERVER_VARS;
     }



//for language //	
//fix for 1rc1
if ( !isset($_POST['lang']) )
{ 
		if ( isset($_COOKIE['lang']) )
		{
				if(file_exists('langs/' . $_COOKIE['lang'] . '.php'))
				{
					include ('langs/' . $_COOKIE['lang'] . '.php');
				}
				else
				{
					include ('langs/en.php');
				}	
		}
		else
		{
			include ('langs/en.php');
		}
}

/*
style of installer
*/
$header = '<!-- Header Start -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 strick //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="' . $lang['DIR'] . '">
<head>
<title>...Kleeja...</title><style type="text/css">
* { padding: 0;margin: 0;}
body {background: #FF9933;font: 0.74em "Tahoma" Verdana, Arial, sans-serif;line-height: 1.5em;text-align:center; }
a {color: #3B6EBF;text-decoration: none;}a:hover {text-decoration: underline;}
.roundedcornr_box_283542 {background: #fff0d2;margin-left: 10%; margin-right: 10%;width: 724px;}
.roundedcornr_top_283542 div {background: url(../images/inst/roundedcornr_283542_tl.png) no-repeat top left;}
.roundedcornr_top_283542 {background: url(../images/inst/roundedcornr_283542_tr.png) no-repeat top right;}
.roundedcornr_bottom_283542 div {background: url(../images/inst/roundedcornr_283542_bl.png) no-repeat bottom left;}
.roundedcornr_bottom_283542 {background: url(../images/inst/roundedcornr_283542_br.png) no-repeat bottom right;}
.roundedcornr_top_283542 div, .roundedcornr_top_283542, .roundedcornr_bottom_283542 div, .roundedcornr_bottom_283542 {
width: 100%;height: 30px;font-size: 1px;}.roundedcornr_content_283542 { margin: 0 30px; }</style></head><body><br/>
<div class="roundedcornr_box_283542"><div class="roundedcornr_top_283542"><div></div></div><div class="roundedcornr_content_283542">
<img src="../images/inst/logo.gif" style="border:0;">
<br/>
<!-- Header End -->
<br/>
';


$footer = '<br/>
<!-- Foterr Start -->
</div><div class="roundedcornr_bottom_283542"><div></div></div></div></body></html>
<!-- Foterr End -->';


/*
//print header
*/
if (!isset($_POST['lang']))
{
	print $header;
}


/*
//nvigate ..
*/
switch ($_GET['step']) {
default:
case 'language':
	if (isset($_POST['lang'])) {
			if (!empty($_POST['lang'])){
				//go to .. 2step
				setcookie("lang", $_POST['lang'], time()+60*60*24*365);
				echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER[PHP_SELF].'?step=choose">';
			//	@header("Location:".$_SERVER[PHP_SELF]."?step=check"); /* Redirect browser */
			}

		}else { //no language

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
	print '<br /><img src="../images/inst/aledrisiMap.gif" style="border:0" alt="Aledrisi Map">
	<br /><form  action="' . $_SERVER[PHP_SELF] . '?step=language" method="post">
	<select name="lang" style="width: 352px">
	' . $lngfiles . '
	</select>
	<br /><input name="submitlang" type="submit" value="[  >>>  ] " /><br /><br /><br /></form>';

		}//no language else



break; // end case language
case 'choose' :
		print '<span style="color:green;">' . $lang['INST_CHOOSE_INSTALLER'] . '</span><br/><br/><br/>';
		print '<a href="./install.php">[ ' . $lang['INST_INSTALL_CLEAN_VER'] . ' ]</a><br/><br/>';
		print '<a href="./update.php">[ ' . $lang['INST_UPDATE_P_VER'] . ' ]</a><br/><br><br>';

		print '<a href="http://www.kleeja.com"><span style="color:black;">www.kleeja.com</span></a><br/><br>';

break;
}#endOFswitch



/*
//print footer
*/
print $footer;


















?>
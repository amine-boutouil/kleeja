<?php
//
/// supporting install process
//

//for lang 
if(isset($_GET['change_lang']))
{
			if (!empty($_POST['lang']))
			{
				//go to .. 2step
				setcookie("lang", htmlspecialchars($_POST['lang']), time()+60*60*24*365);
			//	echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'].'?step=">';
				@header("Location:".$_SERVER['PHP_SELF']."?step=" . $_POST['step_is']); /* Redirect browser */
			}
			
}



//for language //	fix for 1rc1
if (!isset($_POST['lang']))
{ 
				if(!$_COOKIE['lang'])  $_COOKIE['lang'] = 'en';
				
				if(file_exists('langs/' . htmlspecialchars($_COOKIE['lang']) . '.php'))
				{
					include ('langs/' . htmlspecialchars($_COOKIE['lang']) . '.php');
				}
				else
				{
					include ('langs/en.php');
				}	


/*
style of installer
*/
$header_inst = '<!-- Header Start -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $_COOKIE['lang'] . '" lang="' . $_COOKIE['lang'] . '" dir="' . $lang['DIR'] . '">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>...Kleeja...</title><style type="text/css">
* { padding: 0;margin: 0;}
body {background: #FF9933;font: 0.74em "Tahoma" Verdana, Arial, sans-serif;line-height: 1.5em;text-align:center;}
aligny {
float : '.(($lang['DIR']=='ltr') ? 'left' : 'right') .'
}
input { font-family:Tahoma; } 
img { border:0px } 
a {color: #3B6EBF;text-decoration: none;}a:hover {text-decoration: underline;}
.roundedcornr_box_283542 {background: #fff0d2;margin-left: 10%; margin-right: 10%;width: 724px;}
.roundedcornr_top_283542 div {background: url(img/roundedcornr_283542_tl.png) no-repeat top left;}
.roundedcornr_top_283542 {background: url(img/roundedcornr_283542_tr.png) no-repeat top right;}
.roundedcornr_bottom_283542 div {background: url(img/roundedcornr_283542_bl.png) no-repeat bottom left;}
.roundedcornr_bottom_283542 {background: url(img/roundedcornr_283542_br.png) no-repeat bottom right;}
.roundedcornr_top_283542 div, .roundedcornr_top_283542, .roundedcornr_bottom_283542 div, .roundedcornr_bottom_283542 {
width: 100%;height: 30px;font-size: 1px;}.roundedcornr_content_283542 { margin: 0 30px; }
</style>
	<script type="text/javascript">
	function agree ()
	{
		var agrec = document.getElementById(\'agrec\');
		var agres = document.getElementById(\'agres\');

		if (agrec.checked) { agres.disabled= \'\';}else{agres.disabled= \'disabled\';}
	}

//	http://www.marketingtechblog.com/2007/08/27/javascript-password-strength/
function passwordChanged() 
{
	var strength = document.getElementById("strength");
	var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
	var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	var pwd = document.getElementById("password");
	
	if (pwd.value.length==0) 
	{
		strength.innerHTML =  \'<img src="img/p1.gif" alt="! .." />\';
	} 
	else if (false == enoughRegex.test(pwd.value)) 
	{
		strength.innerHTML = \'<img src="img/p2.gif" alt="write more .." />\';
	}
	else if (strongRegex.test(pwd.value)) 
	{
		strength.innerHTML = \'<img src="img/p5.gif" alt="strong .." />\';
	} 
	else if (mediumRegex.test(pwd.value))
	{
		strength.innerHTML = \'<img src="img/p4.gif" alt="Medium .." />\';
	} 
	else 
	{
		strength.innerHTML = \'<img src="img/p3.gif" alt="Weak! .." />\';
	}
}

function w_email(l)
{
		var m = document.getElementById(l);
		if (m.value.indexOf("@") == -1 ||m.value.indexOf(".") == -1 || m.value.length < 7 ) 
		{
			alert("'.$lang['WRONG_EMAIL'].'");
			m.focus();
		}
}
	</script>
</head>
<body>
<br/>
<div class="roundedcornr_box_283542"><div class="roundedcornr_top_283542"><div></div></div><div class="roundedcornr_content_283542">
';

if (($_GET['step'] != 'language') && (strpos('index.php',$_SERVER['PHP_SELF'])=== false && isset($_GET['step'])))
{
$header_inst .= '<form action="?change_lang" method="post">
<img src="img/world.gif" alt="language" style="float:left" /> 
<select name="lang" style="float:left" onchange="submit()">';

$path = "langs";
		$dh = opendir($path);
		$lngfiles = '';
		$i=1;
		while (($file = readdir($dh)) !== false)
		{
		    if($file != "." && $file != ".."  && $file != "index.html")
			{
			$file = str_replace('.php','', $file);
			  $header_inst .= '<option value="' . $file . '">' . $file . '</option>';
		        $i++;
		    }
		}
		closedir($dh);
$header_inst .= '</select>
<input type="hidden" name="step_is" value="' . $_GET['step'] . '" />
</form>';
}
$header_inst .= '
<img src="img/logo.gif" style="border:0;">
<br/>
<!-- Header End -->
<br/>
';


$footer_inst = '<br/>
<!-- Foterr Start -->
</div><div class="roundedcornr_bottom_283542"><div></div></div></div></body></html>
<!-- Foterr End -->';


//functions
function make_style()
{
	$contents	=	file_get_contents('res/style.xml');
	creat_style_xml($contents, true);
}

function make_language($def)
{
	if(!$def || $def=='en') 
	{
		$ar = false; 
		$en = true; 
	}
	else
	{
		$ar = true; 
		$en = false;
	}
	
	$contents	=	file_get_contents('res/lang_ar.xml');
	$contents1	=	file_get_contents('res/lang_en.xml');
	creat_lang_xml($contents, $ar);
	creat_lang_xml($contents1, $en);

}



}

	function get_microtime(){	list($usec, $sec) = explode(' ', microtime());	return ((float)$usec + (float)$sec);	}

	
    if (phpversion() < '4.1.0') exit('Your php version is too old !');


?>
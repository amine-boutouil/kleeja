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
			//	echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'].'?step=">';
				@header("Location:".$_SERVER['PHP_SELF']."?step=" . $_POST['step_is'] . "&lang=".$_POST['lang'] ); /* Redirect browser */
			}
			
}

function get_lang ($link=false)
{
	if (isset($_GET['lang']))
	{ 
					if(empty($_GET['lang']))  $_GET['lang'] = 'en';
					
					if(file_exists('langs/' . htmlspecialchars($_GET['lang']) . '.php'))
					{
						$ln	=  htmlspecialchars($_GET['lang']);
					}
					else
					{
						$ln = 'en';
					}	

	}
	else
	{
		$ln	=	'en';
	}

	return ($link != false) ? 'lang='.$ln : $ln;
}

//for language //	fix for 1rc3
include ('langs/' .get_lang() . '.php');

/*
style of installer
*/
$header_inst = '<!-- Header Start -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . get_lang() . '" lang="' . get_lang() . '" dir="' . $lang['DIR'] . '">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>...Kleeja...</title><style type="text/css">
* { padding: 0;margin: 0;}
body {
background: #FF9933;
font: 0.74em "Tahoma" Verdana, Arial, sans-serif;line-height: 1.5em;
margin:0px auto; text-align: center
}
aligny {
float : '.(($lang['DIR']=='ltr') ? 'left' : 'right') .'
}
input { font-family:Tahoma; } 
img { border:0px } 
a {color: #3B6EBF;text-decoration: none;}a:hover {text-decoration: underline;}
.roundedcornr_box_283542 {background: #fff0d2;margin-left: 10%; margin:0 auto;width: 724px;}
.roundedcornr_top_283542 div {background: url(img/roundedcornr_283542_tl.png) no-repeat top left;}
.roundedcornr_top_283542 {background: url(img/roundedcornr_283542_tr.png) no-repeat top right;}
.roundedcornr_bottom_283542 div {background: url(img/roundedcornr_283542_bl.png) no-repeat bottom left;}
.roundedcornr_bottom_283542 {background: url(img/roundedcornr_283542_br.png) no-repeat bottom right;}
.roundedcornr_top_283542 div, .roundedcornr_top_283542, .roundedcornr_bottom_283542 div, .roundedcornr_bottom_283542 {
width: 100%;height: 30px;font-size: 1px;}.roundedcornr_content_283542 { margin: 0 30px; }
</style>
	<script type="text/javascript">
	//<![CDATA[
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

//By JavaScript Kit (http://javascriptkit.com)
function checkrequired(which)
{
	var pass	=	true;
	if (document.images)
	{
		for (i=0;i<which.length;i++)
		{
			var tempobj=which.elements[i]
			if (tempobj.name.substring(0,8)=="required")
			{
				if (((tempobj.type=="text"||tempobj.type=="textarea")&&tempobj.value==\'\')||(tempobj.type.toString().charAt(0)=="s"&&tempobj.selectedIndex==-1))
				{
					pass	=	false;
					break
				}
			}
		}
	}
	if (!pass)
	{
		alert("' . $lang['VALIDATING_FORM_WRONG'] . '");
		return false;
	}
	else
	{
		return true;
	}
}

// http://www.dynamicdrive.com/ 
function formCheck(formobj, fieldRequired){


	// dialog message
	var alertMsg = "' . $lang['VALIDATING_FORM_WRONG'] . ':\n";
	
	var l_Msg = alertMsg.length;
	
	for (var i = 0; i < fieldRequired.length; i++){
		var obj = formobj.elements[fieldRequired[i]];
		if (obj){
			switch(obj.type){
			case "text":
			case "textarea":
				if (obj.value == "" || obj.value == null){
					alertMsg += " - " + fieldRequired[i] + "\n";
				}
				break;
			default:
			}
			if (obj.type == undefined){
				var blnchecked = false;
				for (var j = 0; j < obj.length; j++){
					if (obj[j].checked){
						blnchecked = true;
					}
				}
				if (!blnchecked){
					alertMsg += " - " + fieldRequired[i] + "\n";
				}
			}
		}
	}

	if (alertMsg.length == l_Msg){
		return true;
	}else{
		alert(alertMsg);
		return false;
	}
}
// -->
//]]>

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
			  $header_inst .= '<option value="' . $file . '" ' . ($file==$_GET['lang'] ? 'selected="selected"' : '') . '>' . $file . '</option>';
		        $i++;
		    }
		}
		closedir($dh);
$header_inst .= '</select>
<input type="hidden" name="step_is" value="' . $_GET['step'] . '" />
</form>';
}
$header_inst .= '
<img src="img/logo.gif" style="border:0;" alt="kleeja" />
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



	//export config 
	function do_config_export($srv, $usr, $pass, $nm, $prf)
	{
	
		$data	= '<?php'."\n\n" . '' . "\n\n\n";
		$data	.= '$dbserver		= \''.addslashes($srv)."';//database server \n";
		$data	.= '$dbuser			= \''.addslashes($usr)."';// database user \n";
		$data	.= '$dbpass			= \''.addslashes($pass)."';// database password \n";
		$data	.= '$dbname			= \''.addslashes($nm)."';// database name \n";
		$data	.= '$dbprefix		= \''.addslashes($prf)."';// if you use perfix for tables , fill it \n";
		$data	.= '$perpage		= 10;'."// number of results in each page  \n";
		$data	.= "\n\n\n";
		$data	.= "//for integration with forums [ must change user systen from admin cp ] \n";
		$data	.= '$forum_srv		= "localhost";'."// forum database server  \n";
		$data	.= '$forum_db		= "";'."// forum database name   \n";
		$data	.= '$forum_user		= "";'."// forum database user  \n";
		$data	.= '$forum_pass		= "";'."// forum database password  \n";
		$data	.= '$forum_prefix	= "";'."//the perfix before name of forum tables  \n";
		$data	.= '$forum_path		= "/forum";'."// path of forums  \n";
		$data	.= "\n\n\n";
		$data	.= "//for use ftp account to uplaod [ Under Develpment ] \n";
		$data	.= '$use_ftp		= 0;'."// 1 : yes  - 0 : no   \n";
		$data	.= '$ftp_server		= "ftp.example.com";'."// ...   \n";
		$data	.= '$ftp_user		= "";'."//    \n";
		$data	.= '$ftp_pass		= "";'."//    \n";
		$data	.= "\n\n\n";
		$data	.= "// stop hook system if you need, to stop remove the \\ from the next line  \n";
		$data	.= "//define('STOP_HOOKS', true); \n";
		$data	.= "// \n";
		$data	.= "\n\n";
		$data	.= '?'.'>';
	
			
		header('Content-Type: text/x-delimtext; name="config.php"');
		header('Content-disposition: attachment; filename=config.php');
		echo $data;
		exit;
}	

	
	
	function get_microtime(){	list($usec, $sec) = explode(' ', microtime());	return ((float)$usec + (float)$sec);	}

	
    if (phpversion() < '4.1.0') exit('Your php version is too old !');


	

?>

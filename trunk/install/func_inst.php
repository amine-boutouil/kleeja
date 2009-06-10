<?php
//
/// supporting install process
//


if (phpversion() < '4.3')
{
	exit('Your php version is too old !');
}

//for lang 
if(isset($_GET['change_lang']))
{
	if (!empty($_POST['lang']))
	{
		//Redirect browser
		@header("Location:".$_SERVER['PHP_SELF'] . "?step=" . $_POST['step_is'] . "&lang=" . $_POST['lang']); 
	}
}

function getlang ($link=false)
{
	if (isset($_GET['lang']))
	{ 
		if(empty($_GET['lang']))
		{
			$_GET['lang'] = 'en';
		}			
		if(file_exists('../lang/' . htmlspecialchars($_GET['lang']) . '/install.php'))
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
		$ln	= 'en';
	}
	
	return ($link != false) ? 'lang=' . $ln : $ln;
}

//for language //	
include ('../lang/' .getlang() . '/install.php');

/*
style of installer
*/
$header_inst = '<!-- Header Start -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . getlang() . '" lang="' . getlang() . '" dir="' . $lang['DIR'] . '">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>...Kleeja...</title><style type="text/css">
* { padding: 0;margin: 0;}
body {
background: #ffda8c url(\'img/bg.gif\');
font: 0.74em "Tahoma" Verdana, Arial, sans-serif;line-height: 1.5em;
margin:0px auto; text-align: center
}
aligny {
float : ' . (($lang['DIR']=='ltr') ? 'left' : 'right') .'
}
input {color: #333333;font-family: "Tahoma", Verdana, Helvetica, sans-serif;font-size: 1.1em;font-weight: normal;padding: 1px;
border: 1px solid #A9B8C2;background-color: #FAFAFA;/* top right */-moz-border-radius-topright:10px;/* bottom left */-moz-border-radius-bottomleft:10px;}
select {-moz-border-radius: 8px; border-radius:1px;color: #333333; background-color: #FAFAFA;font-family: "Tahoma", Verdana, Helvetica, sans-serif;font-size: 1.1em;font-weight: normal;border: 1px solid #A9B8C2;padding: 1px;}
option { padding: 0 1em 0 0;}
img { border:0px } 

.home { margin: 15px 0;padding: 10px;border-top: 1px solid #D7D7D7; border-right: 1px solid #CCCCCC;border-bottom: 1px solid #CCCCCC;border-left: 1px solid #D7D7D7; background-color: #FFFFFF; position: relative;-moz-border-radius: 8px; border-radius:1px;}

* html fieldset { padding: 0 10px 5px 10px;}
a {color: #3B6EBF;text-decoration: none;}a:hover {text-decoration: underline;}
legend
{
font-size: 2.5em;
color: #000;
background-color: #fff0d2;
padding: 13px 15px;
-moz-border-radius: 8px; border-radius:1px;
} 
.wz{-moz-border-radius: 8px; border-radius:1px;color: #333333; background-color: #FAFAFA;font-family: "Tahoma", Verdana, Helvetica, sans-serif;font-size: 1.1em;font-weight: normal;border: 1px solid #A9B8C2;padding: 1px;}
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
			alert("' . $lang['WRONG_EMAIL'] . '");
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
function formCheck(formobj, fieldRequired)
{
	// dialog message
	var alertMsg = "' . $lang['VALIDATING_FORM_WRONG'] . ':\n";
	var l_Msg = alertMsg.length;
	//lang
	var lang = new Array(9);
	lang["db_server"] = "' . $lang['DB_SERVER'] . '";
	lang["db_user"] = "' . $lang['DB_USER'] . '";
	lang["db_name"] = "' . $lang['DB_NAME'] . '";
	lang["sitename"] = "' . $lang['SITENAME'] . '";
	lang["siteurl"] = "' . $lang['SITEURL'] . '";
	lang["sitemail"] = "' . $lang['SITEMAIL'] . '";
	lang["username"] = "' . $lang['USERNAME'] . '";
	lang["password"] = "' . $lang['PASSWORD'] . '";
	lang["password2"] = "' . $lang['PASSWORD2'] . '";
	lang["email"] = "' . $lang['EMAIL'] . '";
	
	for (var i = 0; i < fieldRequired.length; i++)
	{
		var obj = formobj.elements[fieldRequired[i]];
		if (obj)
		{
			switch(obj.type)
			{
				case "text":
				case "textarea":
					if (obj.value == "" || obj.value == null)
						alertMsg += " - " + lang[fieldRequired[i]] + "\n";
					break;
				default:
			}
			
			if (obj.type == undefined)
			{
				var blnchecked = false;
				for (var j = 0; j < obj.length; j++)
				{
					if (obj[j].checked)
						blnchecked = true;
				}
				
				if (!blnchecked)
					alertMsg += " - " + lang[fieldRequired[i]] + "\n";
			}
		}
	}

	if (alertMsg.length == l_Msg)
		return true;
	else
	{
		alert(alertMsg);
		return false;
	}
}
// -->
//]]>

</script>
</head>
<body>
<br />


';

if ((isset($_GET['step']) && $_GET['step'] != 'language') && (strpos('index.php',$_SERVER['PHP_SELF'])=== false && isset($_GET['step'])))
{
	$header_inst .= '<form action="?change_lang" method="post">
	<img src="img/world.gif" alt="language" style="float:left" /> 
	<select name="lang" style="float:left" onchange="submit()">';

	$path = "../lang";

	if ($dh = @opendir($path))
	{
		while (($file = readdir($dh)) !== false)
		{
			if(strpos($file, '.') === false && $file != '..' && $file != '.')
			{
				$header_inst .= '<option value="' . $file . '" ' . ($file == $_GET['lang'] ? 'selected="selected"' : '') . '>' . $file . '</option>';
			}
		}
		
		closedir($dh);
	}


	$header_inst .= '</select>
	<input type="hidden" name="step_is" value="' . $_GET['step'] . '" />
	</form>';
}

$header_inst .= '
<img src="img/logo.png" style="border:0;" alt="kleeja" />
<br />
<!-- Header End -->
<br />
<fieldset style="margin-left: 10%;margin: 0 auto;width: 724px;border: 1px solid #FCB012;background-color: #fff0d2;position: relative;-moz-border-radius: 8px;border-radius: 1px;">
';


$footer_inst = '<br />
<!-- Foterr Start -->
</fieldset>
</body></html>
<!-- Foterr End -->';



//export config 
function do_config_export($srv, $usr, $pass, $nm, $prf, $fpath)
{
		global $_path;
		
		$data	= '<?php'."\n\n" . '//fill those varaibles with your data' . "\n";
		$data	.= '$dbserver		= \'' . str_replace("'","\'", $srv) . "';//database server \n";
		$data	.= '$dbuser			= \''. str_replace("'","\'", $usr)."';// database user \n";
		$data	.= '$dbpass			= \''. str_replace("'","\'", $pass)."';// database password \n";
		$data	.= '$dbname			= \''. str_replace("'","\'", $nm)."';// database name \n";
		$data	.= '$dbprefix		= \''. str_replace("'","\'", $prf)."';// if you use perfix for tables , fill it \n";
		$data	.= "\n\n\n";
		$data	.= "//for integration with script [ must change user systen from admin cp ] \n";
		$data	.= '$script_path		= \''. str_replace("'","\'", $fpath)."';// path of script (./forums)  \n";
		$data	.= "\n\n";
		$data	.= '?'.'>';
	
		$written = false;
		if (is_writable($_path))
		{
			$fh = @fopen($_path . 'config.php', 'wb');
			if ($fh)
			{
				fwrite($fh, $data);
				fclose($fh);

				$written = true;
			}
		}
		
		if(!$written)
		{
			header('Content-Type: text/x-delimtext; name="config.php"');
			header('Content-disposition: attachment; filename=config.php');
			echo $data;
			exit;
		}
		
		return true;
}	



function get_microtime()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float) $usec + (float) $sec);
}


?>

<?php
# KLEEJA INSTALLER ...
# updated 22/4/1249 [4/2008]
# this file have many updates .. dont use previous ones
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

	function get_microtime(){	list($usec, $sec) = explode(' ', microtime());	return ((float)$usec + (float)$sec);	}

    if (phpversion() < '4.1.0') exit('Your php version is too old !');


//for language //	fix for 1rc1
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
<br/>
<!-- Header End -->
';


$footer = '<!-- Foterr Start -->
<br/>
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

/*
//print header
*/
print $header;


/*
//nvigate ..
*/
switch ($_GET['step']) 
{
default:
case 'check':

	$submit_wh = '';


	//config,php
	if (!$dbname || !$dbuser)
	{
		print '<span style="color:red;">' . $lang['INST_CHANG_CONFIG'] . '</span><br/>';
		$submit_wh = 'disabled="disabled"';
	}

	//connect .. for check
	$texterr = '';
	$connect = @mysql_connect($dbserver,$dbuser,$dbpass);
	if (!$connect) 
		$texterr .= '<span style="color:red;">' . $lang['INST_CONNCET_ERR'] . '</span><br/>';
		
	$select = @mysql_select_db($dbname);
	if (!$select) 
		$texterr .= '<span style="color:red;">' . $lang['INST_SELECT_ERR'] . '</span><br/>';
		
	if ( !is_writable('../cache') ) 
			$texterr .= '<span style="color:red;">[cache]: ' . $lang['INST_NO_WRTABLE'] . '</span><br/>';
	
	
	if ( !is_writable('../uploads') )
			$texterr .= '<span style="color:red;">[uploads]: ' . $lang['INST_NO_WRTABLE'] . '</span><br/>';
	
	
	if ( !is_writable('../uploads/thumbs') )
			$texterr .= '<span style="color:red;">[uploads/thumbs]: ' . $lang['INST_NO_WRTABLE'] . '</span><br/>';
	
	
	if ($texterr !='')
	{
		print $texterr;
		$submit_wh = 'disabled="disabled"';
	}

	if($submit_wh == '')
	{
		print '<br/><span style="color:green;"><b>[ ' . $lang['INST_GOOD_GO'] . ' ]</b></span><br/><br/>';
	}

	print '<form method="post" action="' . $_SERVER[PHP_SELF] . '?step=gpl2">
	<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" ' . $submit_wh . '/>
	</form>';

break;
case 'gpl2':

	$contentofgpl2 = @file_get_contents('../docs/GPL2.txt');
	
	if (strlen($contentofgpl2) < 3 ) 
				$contentofgpl2 = "CANT FIND 'GPL2.TXT. FILE .. SEARCH ON NET ABOUT GPL2";

	print '
	<script type="text/javascript">
	function agree ()
	{
		var agrec = document.getElementById(\'agrec\');
		var agres = document.getElementById(\'agres\');

		if (agrec.checked) { agres.disabled= \'\';}else{agres.disabled= \'disabled\';}
	}

	</script>

	<form method="post" action="' . $_SERVER[PHP_SELF] . '?step=data">
	<textarea name="gpl2" style="width: 456px; height: 365px">
	' . $contentofgpl2 . '
	</textarea>

	<br />
	' . $lang['INST_AGR_GPL2'] . ' <input name="agrec" id="agrec" type="checkbox" onclick="javascript:agree();"  /><br />
	<input name="agres" id="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" disabled="disabled"/>

	</form>';


break;
case 'data' :

	if (isset($_POST['datasubmit']))
	{

		//check data ...
		if (empty($_POST['sitename']) || empty($_POST['siteurl']) || empty($_POST['sitemail'])
			 || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) )
		{
			print $lang['EMPTY_FIELDS'];
			print $footer;
			exit();
		}

		 if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['email'])))
		 {
			print $lang['WRONG_EMAIL'];
			print $footer;
			exit();
		}

		$connect = @mysql_connect($dbserver,$dbuser,$dbpass);
		$select = @mysql_select_db($dbname);
		if ($select) {if (mysql_version>='4.1.0') mysql_query("SET NAMES 'utf8'"); }


		$user_pass 			= md5($_POST['password']);
		$user_name 			=	$_POST['username'];
		$user_mail 			=	$_POST['email'];
		$config_sitename	=	$_POST['sitename'];
		$config_siteurl		=	$_POST['siteurl'];
		$config_sitemail	=	$_POST['sitemail'];
		
		
		 /// ok .. will get sqls now ..
		include ('res/install_sqls.php');
		 
		$err = 0;

		foreach($install_sqls as $name=>$sql_content)
		{

			$do_it	= @mysql_query($sql_content, $connect);
			
			if($do_it)
			{
				if ($name == 'call')		print '<span style="color:green;">' . $lang['INST_CRT_CALL'] . '</span><br/>';
				elseif ($name == 'reports')	print '<span style="color:green;">' . $lang['INST_CRT_REPRS'] . '</span><br/>';
				elseif ($name == 'stats')	print '<span style="color:green;">' . $lang['INST_CRT_STS'] . '</span><br/>';
				elseif ($name == 'users')	print '<span style="color:green;">' . $lang['INST_CRT_USRS'] . '</span><br/>';
				elseif ($name == 'users')	print '<span style="color:green;">' . $lang['INST_CRT_ADM'] . '</span><br/>';
				elseif ($name == 'files')	print '<span style="color:green;">' . $lang['INST_CRT_FLS'] . '</span><br/>';
				elseif ($name == 'config')	print '<span style="color:green;">' . $lang['INST_CRT_CNF'] . '</span><br/>';
				elseif ($name == 'exts')	print '<span style="color:green;">' . $lang['INST_CRT_EXT'] . '</span><br/>';
				elseif ($name == 'online')	print '<span style="color:green;">' . $lang['INST_CRT_ONL'] . '</span><br/>';
				else
					print '<span style="color:green;"> [' .$name .'] : ' . $lang['INST_SQL_OK'] . '</span><br/>';
			}
			else
			{
				print '<span style="color:red;"> [' .$name .'] : ' . $lang['INST_SQL_ERR'] . '</span><br/>';
				$err++;
			}

		}#for

		if (!$err)
		{
			make_style();
			make_language($_COOKIE['lang']);
			
			print '<form method="post" action="' . $_SERVER[PHP_SELF] . '?step=end">
			<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '"/>
			</form>';
		}
		else
		{
			print '<span style="color:red;">' . $lang['INST_FINISH_ERRSQL'] . '</span>';
		}

	}else{

	//$sitepath = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']);
	$urlsite =  "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/';

 print '<form method="post" action="' . $_SERVER[PHP_SELF] . '?step=data">
	<fieldset name="Group1" dir="' . $lang['DIR'] . '">
	<legend style="width: 73px">' . $lang['INST_SITE_INFO'] . '</legend>
	<table style="width: 100%">
		<tr>
			<td>' . $lang['SITENAME'] . '</td>
			<td><input name="sitename" type="text" style="width: 256px" /></td>
		</tr>
		<tr>
			<td>' . $lang['SITEURL'] . '</td>
			<td><input name="siteurl" type="text" value="' . $urlsite . '" style="width: 256px" /></td>
		</tr>
		<tr>
			<td>' . $lang['SITEMAIL'] . '</td>
			<td><input name="sitemail" type="text" style="width: 256px" /></td>
		</tr>
	</table>
	</fieldset>

	<br />

	<fieldset name="Group2" dir="' . $lang['DIR'] . '">
	<legend style="width: 73px">' . $lang['INST_ADMIN_INFO'] . '</legend>
	<table style="width: 100%">
		<tr>
			<td>' . $lang['USERNAME'] . '</td>
			<td><input name="username" type="text" style="width: 256px" /></td>
		</tr>
		<tr>
			<td>' . $lang['PASSWORD'] . '</td>
			<td><input name="password" type="text" style="width: 256px" /></td>
		</tr>
		<tr>
			<td>' . $lang['EMAIL'] . '</td>
			<td><input name="email" type="text" style="width: 256px" /></td>
		</tr>
	</table>
	</fieldset>

	<input name="datasubmit" type="submit" value="' . $lang['INST_SUBMIT'] . '" />';
	}#else


break;
case 'end' :
		print '<span style="color:blue;">' . $lang['INST_FINISH_SQL'] . '</span><br/><br/>';
		print '<a href="../index.php">' . $lang['INDEX'] . '</a><br/><br/>';
		print '<a href="../admin.php">' . $lang['ADMINCP'] . '</a><br/><br>';
		print '' . $lang['INST_KLEEJADEVELOPERS'] . '<br/><br>';
		print '<a href="http://www.kleeja.com">www.kleeja.com</a><br/><br>';
		//for safe ..
		@rename("install.php", "install.lock");
break;
}#endOFswitch



/*
//print footer
*/
print $footer;



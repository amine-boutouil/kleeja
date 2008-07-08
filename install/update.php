<?php
# KLEEJA UPDATOR ...
# last edit by : saanina

/*
include important files
*/



	
	define ( 'IN_COMMON' , true);
	$path = "../includes/";
	include ($path.'config.php');
	include ($path.'functions.php');
	include ($path.'mysql.php');

	function get_microtime(){	list($usec, $sec) = explode(' ', microtime());	return ((float)$usec + (float)$sec);	}

	if (phpversion() < '4.1.0') exit('Your php version is too old !');
 


//for language //	
if (!isset($_POST['lang']))
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
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $_COOKIE['lang'] . '" lang="' . $_COOKIE['lang'] . '" dir="' . $lang['DIR'] . '">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
if (!isset($_POST['action_file_do']))
{
	print $header;
}

/*
//nvigate ..
*/
switch ($_GET['step']) {
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
		
	if ( !is_writable('../cache') ) {$texterr .= '<span style="color:red;">[cache]: ' . $lang['INST_NO_WRTABLE'] . '</span><br/>';};
	if ( !is_writable('../uploads') ) {$texterr .= '<span style="color:red;">[uploads]: ' . $lang['INST_NO_WRTABLE'] . '</span><br/>';};
	if ( !is_writable('../uploads/thumbs') ) {$texterr .= '<span style="color:red;">[uploads/thumbs]: ' . $lang['INST_NO_WRTABLE'] . '</span><br/>';};
	if ($texterr !='')
	{
		print $texterr;
		$submit_wh = 'disabled="disabled"';
	}

	if($submit_wh == '')
	{
		print '<br/><span style="color:green;"><b>[ ' . $lang['INST_GOOD_GO'] . ' ]</b></span><br/><br/>';
	}

	print '<form method="post" action="' . $_SERVER[PHP_SELF] . '?step=action_file">
	<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" ' . $submit_wh . '/>
	</form>';

break;

case 'action_file':

	if (isset($_POST['action_file_do']))
	{
			if (!empty($_POST['action_file_do']))
			{
				//go to .. 2step
				setcookie("action_file_do", $_POST['action_file_do'], time()+60*60*24*365);
				echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER[PHP_SELF].'?step=update_now">';
			//	@header("Location:".$_SERVER[PHP_SELF]."?step=check"); /* Redirect browser */
			}

	}
	else
	{ //no 

		//get fles
			$path = "update_files";
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

		// show   list ..
		print '
		<br />
		<br /><form  action="' . $_SERVER[PHP_SELF] . '?step=action_file" method="post">
		'.$lang['INST_CHOOSE_UPDATE_FILE'].' 
		<br/>
		<select name="action_file_do" style="width: 352px">
		' . $lngfiles . '
		</select>
		<br />
		<br />
		<input name="submitlfile" type="submit" value="'.$lang['INST_SUBMIT'].'" /><br /><br /><br /></form>';

	}//no  else



break;

case 'update_now':
	
		$file_for_up	=	'update_files/'.$_COOKIE['action_file_do'].'.php';
		if(!file_exists($file_for_up))
		{
			print '<span style="color:red;">' . $lang['INST_ERR_NO_SELECTED_UPFILE_GOOD'] . ' [ '.$file_for_up.' ]</span><br/>';
		}
		else
		{	
			//get it
			require $file_for_up;

			$SQL	= new SSQL($dbserver,$dbuser,$dbpass,$dbname);
			
			//
			//is there any sqls 
			//
			if(sizeof($update_sqls) > 0)
			{
				foreach($update_sqls as $name=>$sql_content)
				{
					$do_it	= $SQL->query($sql_content);
					
					if(!$do_it)
						print '<span style="color:red;"> [' .$name .'] : ' . $lang['INST_SQL_ERR'] . '</span><br/>';
				}
			}
			
			//
			//is there any functions 
			//
			if(sizeof($update_functions) > 0)
			{
				foreach($update_functions as $n)
				{
					eval('' . $n .'; ');
				}
			}
			
			//
			//is there any notes 
			//
			if(sizeof($update_notes) > 0)
			{
				print '<br/><span style="color:blue;"><b>' . $lang['INST_NOTES_UPDATE'] . ' :</b> </span><br/>';
				
				$i=1;
				foreach($update_notes as $n)
				{
					print '  [<b>' . $i .'</b>] <br/><span style="color:black;">' . $n. ' : </span><br/>';
					++$i;
				}

			}
			
			print '<br/><br/><span style="color:green;">' . $lang['INST_UPDATE_IS_FINISH']. '</span><br/>';
		
		}

break;


}//end switch
/*
//print footer
*/
print $footer;

?>
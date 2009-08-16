<?php
//
// kleeja installer wizard ...
// $Author$ , $Rev$,  $Date::                           $
//

/*
include important files
*/

	
define ( 'IN_COMMON' , true);
$_path = "../";
(file_exists($_path . 'config.php')) ? include_once ($_path . 'config.php') : null;
include_once ($_path . 'includes/functions.php');
include_once ($_path . 'includes/mysql.php');
include_once ('func_inst.php');



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
				echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER['PHP_SELF'] . '?step=choose&lang=' . $_POST['lang'] . '">';
			}
		}
		else //no language
		{ 

	//get language from LANGUAGE folder
	$path = "../lang";
	$lngfiles = '';
	if ($dh = @opendir($path))
	{
		while (($file = readdir($dh)) !== false)
		{
			if(strpos($file, '.') === false && $file != '..' && $file != '.')
			{
				$lngfiles .= '<option value="' . $file . '"' . ($file == 'en' ? ' selected="selected"' : '') . '>' . $file . '</option>';
			}
		}
		
		@closedir($dh);
	}


	// show  language list ..
	echo '<br />		
	<div class="centery">
		<fieldset class="home">
			<img src="img/map.png" style="border:0" alt="al-Idrisi Map">
			<br />
			<form  action="' . $_SERVER['PHP_SELF'] . '?step=language&' . getlang(1) . '" method="post">
				<select name="lang" style="width: 352px">' . $lngfiles . '</select>
				<br /><br /><br /><input name="submitlang" type="submit" value=" [  Next >>  ] " /><br /><br /><br />
			</form>
		</fieldset>
	</div>';

		}//no language else



break; // end case language
case 'choose' :

		echo '<fieldset class="home"><span style="color:green;">' . $lang['INST_CHOOSE_INSTALLER'] . '</span><br /><br /><br />';
		
		$install_or_no = true;
		if(file_exists($_path . 'config.php'))
		{
			include_once $_path . 'config.php';
			if(!empty($dbuser) && !empty($dbname))
			{
				$d = inst_get_config('language');
				if(!empty($d))
				{
					$install_or_no = false;
				}
			}
		}
		
		
		if($install_or_no)
		{
			echo '<a href="./install.php?' . getlang(1) . '"><img src="img/Installer.png" alt="installer" /><br />  ' . $lang['INST_INSTALL_CLEAN_VER'] . ' </a><br /><br />';
		}
		
		echo '<a href="./update.php?' . getlang(1) . '"><img src="img/updater.png" alt="updater" /> <br /> ' . $lang['INST_UPDATE_P_VER'] . ' </a><br /><br /><br />';

		echo '<a href="http://www.kleeja.com"><span style="color:black;">www.kleeja.com</span></a></fieldset>';

break;

}#endOFswitch



/*
//print footer
*/
echo $footer_inst;


?>
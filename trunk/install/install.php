<?php
//
// kleeja installer ...
// $Author$ , $Rev$,  $Date::                           $
//


// Report all errors, except notices
@error_reporting(E_ALL ^ E_NOTICE);


/*
include important files
*/

define ( 'IN_COMMON' , true);
$_path = "../";
define('PATH', '../');
(file_exists($_path . 'config.php')) ? include_once ($_path . 'config.php') : null;
include_once ($_path . 'includes/functions.php');
include_once ($_path . 'includes/mysql.php');
include_once ('func_inst.php');

//
//version of latest changes at db
//
define ('DB_VERSION' , '7');


//
//kleeja must be safe ..
//
if(!empty($dbuser) && !empty($dbname) && !(isset($_GET['step']) && $_GET['step'] == 'end'))
{
	$d = inst_get_config('language');
	if(!empty($d))
	{
		header('Location: ../');
		exit;
	}
}


/*
//echo header
*/
if(isset($_POST['dbsubmit']) && !is_writable($_path))
{
	// ...
}
else
{
	echo $header_inst;	
}


if(!isset($_GET['step']))
{
	$_GET['step'] = 'gpl2';
}

/*
//nvigate ..
*/
switch ($_GET['step']) 
{
default:
case 'gpl2':

	$contentofgpl2 = @file_get_contents('../docs/GPL2.txt');
	
	if (strlen($contentofgpl2) < 3)
	{
		$contentofgpl2 = "Can't find 'gpl2.txt' file .. search on the web about GPL2";
	}
	
	echo '
	<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=f&' . getlang(1) . '">
	<br />
	<div class="home" name="gpl2" style="width: 456px; height: 320px;direction:ltr;overflow: auto;margin:0 auto">
	' . nl2br($contentofgpl2) . '
	</div>

	<br />
	' . $lang['INST_AGR_GPL2'] . ' <input name="agrec" id="agrec" type="checkbox" onclick="javascript:agree();"  /><br />
	<input name="agres" id="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" disabled="disabled"/>

	</form>';


break;
case 'f':
	
	$check_ok = true;
	
	echo '<fieldset class="home" dir="' . $lang['DIR'] . '" style="text-align:' . ($lang['DIR'] == 'rtl' ? 'right' : 'left') . ';margin: 8px;">';
	echo '<strong>' . $lang['FUNCTIONS_CHECK'] . '</strong> : <br />';
	echo '<ul class="ul_check">';
	if(function_exists('unlink'))
		echo '<li style="color:green">' . sprintf($lang['FUNCTION_IS_EXISTS'], 'unlink') . '</li>';
	else
	{
		$check_ok = false;
		echo '<li style="color:red">' . sprintf($lang['FUNCTION_IS_NOT_EXISTS'], 'unlink') . '</li>';
	}
	echo '[ ' . $lang['FUNCTION_DISC_UNLINK'] . ']<br /> ';
	if(function_exists('imagecreatetruecolor'))

		echo '<li style="color:green">' . sprintf($lang['FUNCTION_IS_EXISTS'], 'imagecreatetruecolor') . '</li>';
	else
	{
		$check_ok = false;
		echo '<li style="color:red">' . sprintf($lang['FUNCTION_IS_NOT_EXISTS'], 'imagecreatetruecolor') . '</li>';
	}
	echo ' [ ' . $lang['FUNCTION_DISC_GD'] . ']<br /> ';
	if(function_exists('fopen'))
		echo '<li style="color:green">' . sprintf($lang['FUNCTION_IS_EXISTS'], 'fopen') . '</li>';
	else
	{
		$check_ok = false;
		echo '<li style="color:red">' . sprintf($lang['FUNCTION_IS_NOT_EXISTS'], 'fopen') . '</li>';
	}
	echo ' [ ' . $lang['FUNCTION_DISC_FOPEN'] . ']<br /> ';
	if(function_exists('move_uploaded_file'))
		echo '<li style="color:green">' . sprintf($lang['FUNCTION_IS_EXISTS'], 'move_uploaded_file') . '</li>';
	else
	{
		$check_ok = false;
		echo '<li style="color:red">' . sprintf($lang['FUNCTION_IS_NOT_EXISTS'], 'move_uploaded_file') . '</li>';
	}
	echo ' [ ' . $lang['FUNCTION_DISC_MUF'] . ']<br /> ';
	
	//advies
	$advices = ''; 
	if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
	{
		$advices .= '<li>' . $lang['ADVICES_REGISTER_GLOBALS'] . '</li>'; 
	}
	if( (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || 
	(@ini_get('magic_quotes_sybase') && (strtolower(@ini_get('magic_quotes_sybase')) != "off")) )
	{
		$advices .= '<li>' . $lang['ADVICES_MAGIC_QUOTES'] . '</li>'; 
	}
	if (!function_exists('iconv'))
	{
		$advices .= '<li>' . $lang['ADVICES_ICONV'] . '</li>'; 
	}

	
	if($advices != '')
	{
		echo '</ul><br /> <strong>' . $lang['ADVICES_CHECK'] . '</strong> : <br /> <ul  class="ul_check2">' . $advices;
	}
	
	echo '</ul></fieldset><br />';
	
	if($check_ok)
	{
		echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=c&' . getlang(1) . '">
		<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '"  />
		</form>';
	}
	else
	{
		echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=f&' . getlang(1) . '">
		<input name="agres" type="submit" value="' . $lang['RE_CHECK'] . '"  />
		</form>';
	}
	
break;
case 'c':
	
	// SUBMIT
	if(isset($_POST['dbsubmit']))
	{
		//lets do it
		do_config_export(
						$_POST['db_server'],
						$_POST['db_user'],
						$_POST['db_pass'],
						$_POST['db_name'],
						$_POST['db_prefix'],
						$_POST['fpath']
						);
	
	}
	
	$xs	=!file_exists('../config.php') ? false : true;
	$writeable_path = is_writable($_path) ? true : false;
	
	if(!$xs)
	{
		 echo '<br /><form method="post"  action="' . $_SERVER['PHP_SELF'] . '?step=c&' . getlang(1) . '"  onsubmit="javascript:return formCheck(this, Array(\'db_server\',\'db_user\' ,\'db_name\'));">

			<fieldset class="home" id="Group1" dir="' . $lang['DIR'] . '">
			<b>' . ($writeable_path ? $lang['DB_INFO'] : $lang['DB_INFO_NW']) . '</b>
			<br />
			<br />
			<table style="width: 100%">
				<tr>
					<td>' . $lang['DB_SERVER'] . '</td>
					<td><input name="db_server" type="text" value="localhost" style="width: 256px" />
					</td>
				</tr>
				<tr>
					<td>' . $lang['DB_NAME'] . '</td>
					<td><input name="db_name" type="text" style="width: 256px" />
					</td>
				</tr> 
				<tr>
					<td>' . $lang['DB_USER'] . '</td>
					<td><input name="db_user" type="text" style="width: 256px" />
					</td>
				</tr>
				<tr>
					<td>' . $lang['DB_PASSWORD'] . '</td>
					<td><input name="db_pass" type="text" style="width: 256px" />
					</td>
				</tr>  
				<tr>
					<td>' . $lang['DB_PREFIX'] . '</td>
					<td><input name="db_prefix" type="text" value="klj_" style="width: 256px" />
					</td>
				</tr>       
			</table>
			<br />
			</fieldset>
			
			<fieldset class="home" id="Group1" dir="' . $lang['DIR'] . '">
			<b>' . $lang['IN_INFO'] . '</b>
			<br />
			<br />
			<table style="width: 100%">
				<tr>
					<td>' . $lang['IN_PATH'] . '</td>
					<td><input name="fpath" type="text" value="./forum" style="width: 256px;direction:ltr" />
					</td>
				</tr>
			</table>
			<br />
			</fieldset>

			<input name="dbsubmit" type="submit" value="' . ($writeable_path ? $lang['INST_SUBMIT'] : $lang['INST_EXPORT']) . '" />
			</form>
			<br />
			';
			
			if(!$writeable_path) 
			{
				echo '<br />
				<hr/>
				<br />
				<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=c&' . getlang(1) . '">
				<input  type="submit" value="' . $lang['INST_SUBMIT_CONFIGOK'] . '" />
				</form>';
			}
	}
	else
	{
		echo  ' <fieldset class="home"><br /><span style="color:green;"><strong>' .  $lang['CONFIG_EXISTS'] . '</strong><br /><br />';
		echo  '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=check&' . getlang(1) . '">
		<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" />
		</form></fieldset>';
	}



break;
case 'check':

	$submit_wh = $texterr = '';

	//config,php
	if (!isset($dbname) || !isset($dbuser))
	{
		$texterr = '<span style="color:red;">' . $lang['INST_CHANG_CONFIG'] . '</span><br />';
		$submit_wh = 'disabled="disabled"';
	}
	else
	{
		//connect .. for check
		$connect = @mysql_connect($dbserver, $dbuser, $dbpass);
		if (!$connect) 
			$texterr .= '<span style="color:red;">' . $lang['INST_CONNCET_ERR'] . '</span><br />';
			
		$select = @mysql_select_db($dbname);
		if (!$select)
		{
			//lets try to make the db 
			$sql = 'CREATE DATABASE ' . $dbname ;
			if (@mysql_query($sql, $connect))
			{
				$select = @mysql_select_db($dbname);
			}
			
			if (!$select)
			$texterr .= '<span style="color:red;">' . $lang['INST_SELECT_ERR'] . '</span><br />';
		}
	}
		
	//try to chmod them
	if(function_exists('chmod'))
	{	
		@chmod('../cache', 0777);
		@chmod('../uploads', 0777);
		@chmod('../uploads/thumbs', 0777);
	}
		
	if (!is_writable('../cache')) 
			$texterr .= '<span style="color:red;">[cache]: ' . $lang['INST_NO_WRTABLE'] . '</span><br />';
	
	
	if (!is_writable('../uploads'))
			$texterr .= '<span style="color:red;">[uploads]: ' . $lang['INST_NO_WRTABLE'] . '</span><br />';
	
	
	if (!is_writable('../uploads/thumbs'))
			$texterr .= '<span style="color:red;">[uploads/thumbs]: ' . $lang['INST_NO_WRTABLE'] . '</span><br />';
	
	
	echo '<fieldset class="home">';
	if ($texterr !='')
	{
		echo '<br /><img src="img/bad.png" alt="bad." /> <br />' . $texterr;
		$submit_wh = 'disabled="disabled"';
	}

	if($submit_wh == '')
	{
		echo '<br /><img src="img/good.png" alt="good" /> <br /><span style="color:green;"><b>[ ' . $lang['INST_GOOD_GO'] . ' ]</b></span><br /><br />';
	}

	echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=data&'.getlang(1) . '">
	<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" ' . $submit_wh . ' />
	</form></fieldset>';

break;

case 'data' :

	if (isset($_POST['datasubmit']))
	{

		//check data ...
		if (empty($_POST['sitename']) || empty($_POST['siteurl']) || empty($_POST['sitemail'])
			 || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) )
		{
			echo $lang['EMPTY_FIELDS'];
			echo $footer_inst;
			exit();
		}

		 if (strpos($_POST['email'],'@') === false)
		 {
			echo $lang['WRONG_EMAIL'];
			echo $footer_inst;
			exit();
		}

		$connect = @mysql_connect($dbserver,$dbuser,$dbpass);
		$select = @mysql_select_db($dbname);
		mysql_query("SET NAMES 'utf8'");
		 
		include_once  '../includes/usr.php';
		$usrcp = new usrcp;

		$user_salt			= substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
		$user_pass 			= $usrcp->kleeja_hash_password($_POST['password'] . $user_salt);
		$user_name 			= $_POST['username'];
		$user_mail 			= $_POST['email'];
		$config_sitename	= $_POST['sitename'];
		$config_siteurl		= $_POST['siteurl'];
		$config_sitemail	= $_POST['sitemail'];
		$clean_name			= $usrcp->cleanusername(mysql_real_escape_string($user_name));
		
		
		 /// ok .. will get sqls now ..
		include ('res/install_sqls.php');
		 
		$err = 0;
		$dots = 0;
		//do important before
		//mysql_query($install_sqls['DROP_TABLES'], $connect);
		mysql_query($install_sqls['ALTER_DATABASE_UTF'], $connect);
		
		foreach($install_sqls as $name=>$sql_content)
		{
			
			if($name == 'DROP_TABLES' || $name == 'ALTER_DATABASE_UTF') continue;
			
			$do_it	= @mysql_query($sql_content, $connect);
		
			if($do_it)
			{
				if ($name == 'call')			echo '<span style="color:green;">' . $lang['INST_CRT_CALL'] . '</span><br />';
				elseif ($name == 'reports')		echo '<span style="color:green;">' . $lang['INST_CRT_REPRS'] . '</span><br />';
				elseif ($name == 'stats')		echo '<span style="color:green;">' . $lang['INST_CRT_STS'] . '</span><br />';
				elseif ($name == 'users')		echo '<span style="color:green;">' . $lang['INST_CRT_USRS'] . '</span><br />';
				elseif ($name == 'users')		echo '<span style="color:green;">' . $lang['INST_CRT_ADM'] . '</span><br />';
				elseif ($name == 'files')		echo '<span style="color:green;">' . $lang['INST_CRT_FLS'] . '</span><br />';
				elseif ($name == 'config')		echo '<span style="color:green;">' . $lang['INST_CRT_CNF'] . '</span><br />';
				elseif ($name == 'exts')		echo '<span style="color:green;">' . $lang['INST_CRT_EXT'] . '</span><br />';
				elseif ($name == 'online')		echo '<span style="color:green;">' . $lang['INST_CRT_ONL'] . '</span><br />';
				elseif ($name == 'hooks')		echo '<span style="color:green;">' . $lang['INST_CRT_HKS'] . '</span><br />';
				elseif ($name == 'plugins')		echo '<span style="color:green;">' . $lang['INST_CRT_PLG'] . '</span><br />';
				elseif ($name == 'lang')		echo '<span style="color:green;">' . $lang['INST_CRT_LNG'] . '</span><br />';
				else
				{
					
					echo ' . ';
					if($dots == 7)
					{
						$dots = 0;
						echo '<br />';
					}
					else
						$dots++;
						
					//echo '<span style="color:green;"> [' .$name  . '] : ' . $lang['INST_SQL_OK'] . '</span><br />';
				}
			}
			else
			{
				echo '<span style="color:red;"> [' .$name . '] : ' . $lang['INST_SQL_ERR'] . '</span><br />';
				$err++;
			}

		}#for

		if (!$err)
		{
			// start classe..
			$SQL	= new SSQL($dbserver,$dbuser,$dbpass,$dbname);

			echo '<fieldset class="home"><form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=end&' . getlang(1) . '">
			<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '"/>
			</form></fieldset>';
			
			//clean cache
			delete_cache(null, true);
		}
		else
		{
			echo '<fieldset class="home"><span style="color:red;">' . $lang['INST_FINISH_ERRSQL'] . '</span></fieldset>';
		}

	}
	else
	{

	//$sitepath = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']);
	$urlsite =  "http://".$_SERVER['HTTP_HOST'] . str_replace('install','',dirname($_SERVER['PHP_SELF']));

 echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=data&' . getlang(1) . '"  onsubmit="javascript:return formCheck(this, Array(\'sitename\',\'siteurl\',\'sitemail\' ,\'username\', \'password\',\'email\' ));">
	<fieldset class="home" id="Group1" dir="' . $lang['DIR'] . '">
	<legend style="width: 73px"> [ <strong>' . $lang['INST_SITE_INFO'] . '</strong> ]</legend>
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
			<td><input name="sitemail" id="sitemail" type="text" style="width: 256px" onchange="return w_email(this.name);" /></td>
		</tr>       
	</table>
	</fieldset>

	<br />

	<fieldset class="home" id="Group2" dir="' . $lang['DIR'] . '">
	<legend style="width: 73px"> [ <strong>' . $lang['INST_ADMIN_INFO'] . '</strong> ]</legend>
	<table style="width: 100%">
		<tr>
			<td>' . $lang['USERNAME'] . '</td>
			<td><input name="username" type="text" style="width: 256px" /></td>
		</tr>
		<tr>
			<td>' . $lang['PASSWORD'] . '</td>
			<td><input name="password" id="password" type="text" style="width: 173px"  onkeyup="return passwordChanged();"/> <span id="strength"><img src="img/p1.gif" alt="! .." /></span></td>
		</tr>
		<tr>
			<td>' . $lang['PASSWORD2'] . '</td>
			<td><input name="password2" id="password2" type="text" style="width: 253px" /></td>
		</tr>
		<tr>
			<td><strong>' . $lang['EMAIL'] . '</strong></td>
			<td><input name="email" id="email" type="text" style="width: 256px"  onchange="return w_email(this.name);" /></td>
		</tr>
	</table>
	</fieldset>

	<input name="datasubmit" type="submit" value="' . $lang['INST_SUBMIT'] . '" />
	</form>';
	}#else


break;
case 'end' :
		echo '<fieldset class="home"><img src="img/wink.png" alt="congratulation" /><br /><br />';
		echo '<span style="color:blue;">' . $lang['INST_FINISH_SQL'] . '</span><br /><br />';
		echo '<img src="img/wz.gif" alt="home" />&nbsp;<a href="./wizard.php">' . $lang['WZ_TITLE'] . '</a><br /><br />';
		echo '<img src="img/home.gif" alt="home" />&nbsp;<a href="../index.php">' . $lang['INDEX'] . '</a><br /><br />';
		echo '<img src="img/adm.gif" alt="admin" />&nbsp;<a href="../admin/">' . $lang['ADMINCP'] . '</a><br /><br />';
		echo '' . $lang['INST_KLEEJADEVELOPERS'] . '<br /><br />';
		echo '<a href="http://www.kleeja.com">www.kleeja.com</a><br /><br /></fieldset>';
		//for safe ..
		//@rename("install.php", "install.lock");
break;
}#endOFswitch



/*
//echo footer
*/
echo $footer_inst;



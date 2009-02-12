<?php
# KLEEJA INSTALLER ...
# updated  [14/10/2008]
# this file have many updates .. dont use previous ones
# last edit by : saanina

/*
include important files
*/


define ( 'IN_COMMON' , true);
$_path = "../";
(file_exists($_path . 'config.php')) ? include ($_path . 'config.php') : null;
include ($_path . 'includes/functions.php');
include ($_path.'includes/mysql.php');
include ('func_inst.php');
	

/*
//print header
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
	<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=c&'.get_lang(1).'">
	<textarea name="gpl2" rows=""   readonly="readonly" cols="" style="width: 456px; height: 365px;direction:ltr;">
	' . $contentofgpl2 . '
	</textarea>

	<br />
	' . $lang['INST_AGR_GPL2'] . ' <input name="agrec" id="agrec" type="checkbox" onclick="javascript:agree();"  /><br />
	<input name="agres" id="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" disabled="disabled"/>

	</form>';


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
						$_POST['db_prefix']
						);
	
	}
	
	$xs	=(!file_exists('../config.php')) ? false : true;
	$writeable_path = is_writable($_path) ? true : false;
	
	if($xs== false)
	{
		 print '<br /><form method="post"  action="' . $_SERVER['PHP_SELF'] . '?step=c&' . get_lang(1) . '"  onsubmit="javascript:return formCheck(this, Array(\'db_server\',\'db_user\' ,\'db_name\',\'db_prefix\' ));">

			<fieldset id="Group1" dir="' . $lang['DIR'] . '">
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

			<input name="dbsubmit" type="submit" value="' . ($writeable_path ? $lang['INST_SUBMIT'] : $lang['INST_EXPORT']) . '" />
			</form>
			<br />
			';
			
			if(!$writeable_path) 
			{
				echo '<br />
				<hr/>
				<br />
				<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=c&'.get_lang(1).'">
				<input  type="submit" value="' . $lang['INST_SUBMIT_CONFIGOK'] . '" />
				</form>';
			}
	}
	else
	{
		echo  ' <fieldset><br /><span style="color:green;"><strong>'. $lang['CONFIG_EXISTS'] . '</strong><br /><br />';
		echo  '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=check&'.get_lang(1).'">
		<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" />
		</form></fieldset>';
	}



break;
case 'check':

	$submit_wh = '';

		
	//config,php
	if (!$dbname || !$dbuser)
	{
		echo '<span style="color:red;">' . $lang['INST_CHANG_CONFIG'] . '</span><br />';
		$submit_wh = 'disabled="disabled"';
	}

	//connect .. for check
	$texterr = '';
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
		
	//try to chmod them
	if(function_exists('chmod'))
	{	
		@chmod('../cache', 0777);
		@chmod('../uploads', 0777);
		@chmod('../uploads/thumbs', 0777);
	}
		
	if ( !is_writable('../cache') ) 
			$texterr .= '<span style="color:red;">[cache]: ' . $lang['INST_NO_WRTABLE'] . '</span><br />';
	
	
	if ( !is_writable('../uploads') )
			$texterr .= '<span style="color:red;">[uploads]: ' . $lang['INST_NO_WRTABLE'] . '</span><br />';
	
	
	if ( !is_writable('../uploads/thumbs') )
			$texterr .= '<span style="color:red;">[uploads/thumbs]: ' . $lang['INST_NO_WRTABLE'] . '</span><br />';
	
	
	print '<fieldset>';
	if ($texterr !='')
	{
		print '<br /><img src="img/bad.gif" alt="bad." /> <br />'.$texterr;
		$submit_wh = 'disabled="disabled"';
	}

	if($submit_wh == '')
	{
		print '<br /><img src="img/good.gif" alt="good" /> <br /><span style="color:green;"><b>[ ' . $lang['INST_GOOD_GO'] . ' ]</b></span><br /><br />';
	}

	print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=data&'.get_lang(1).'">
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
			print $lang['EMPTY_FIELDS'];
			print $footer_inst;
			exit();
		}

		 if (strpos($_POST['email'],'@') === false)
		 {
			print $lang['WRONG_EMAIL'];
			print $footer_inst;
			exit();
		}

		$connect = @mysql_connect($dbserver,$dbuser,$dbpass);
		$select = @mysql_select_db($dbname);
		mysql_query("SET NAMES 'utf8'"); 


		$user_pass 			= md5($_POST['password']);
		$user_name 			= $_POST['username'];
		$user_mail 			= $_POST['email'];
		$config_sitename	= $_POST['sitename'];
		$config_siteurl		= $_POST['siteurl'];
		$config_sitemail	= $_POST['sitemail'];
		
		
		 /// ok .. will get sqls now ..
		include ('res/install_sqls.php');
		 
		$err = 0;
		$dots = 0;
		//do important before
		mysql_query($install_sqls['DROP_TABLES'], $connect);
		mysql_query($install_sqls['ALTER_DATABASE_UTF'], $connect);
		
		foreach($install_sqls as $name=>$sql_content)
		{
			
			if($name == 'DROP_TABLES' || $name == 'ALTER_DATABASE_UTF') continue;
			
			$do_it	= @mysql_query($sql_content, $connect);
		
			if($do_it)
			{
				if ($name == 'call')			print '<span style="color:green;">' . $lang['INST_CRT_CALL'] . '</span><br />';
				elseif ($name == 'reports')		print '<span style="color:green;">' . $lang['INST_CRT_REPRS'] . '</span><br />';
				elseif ($name == 'stats')		print '<span style="color:green;">' . $lang['INST_CRT_STS'] . '</span><br />';
				elseif ($name == 'users')		print '<span style="color:green;">' . $lang['INST_CRT_USRS'] . '</span><br />';
				elseif ($name == 'users')		print '<span style="color:green;">' . $lang['INST_CRT_ADM'] . '</span><br />';
				elseif ($name == 'files')		print '<span style="color:green;">' . $lang['INST_CRT_FLS'] . '</span><br />';
				elseif ($name == 'config')		print '<span style="color:green;">' . $lang['INST_CRT_CNF'] . '</span><br />';
				elseif ($name == 'exts')		print '<span style="color:green;">' . $lang['INST_CRT_EXT'] . '</span><br />';
				elseif ($name == 'online')		print '<span style="color:green;">' . $lang['INST_CRT_ONL'] . '</span><br />';
				elseif ($name == 'hooks')		print '<span style="color:green;">' . $lang['INST_CRT_HKS'] . '</span><br />';
				elseif ($name == 'lang')		print '<span style="color:green;">' . $lang['INST_CRT_LNG'] . '</span><br />';
				elseif ($name == 'lists')		print '<span style="color:green;">' . $lang['INST_CRT_LSTS'] . '</span><br />';
				elseif ($name == 'plugins')		print '<span style="color:green;">' . $lang['INST_CRT_PLG'] . '</span><br />';
				elseif ($name == 'templates')	print '<span style="color:green;">' . $lang['INST_CRT_TPL'] . '</span><br />';
				else
				{
					
					echo '.';
					if($dots == 7)
					{
						$dots = 0;
						echo '<br />';
					}
					else
						$dots++;
						
					//print '<span style="color:green;"> [' .$name .'] : ' . $lang['INST_SQL_OK'] . '</span><br />';
				}
			}
			else
			{
				print '<span style="color:red;"> [' .$name . '] : ' . $lang['INST_SQL_ERR'] . '</span><br />';
				$err++;
			}

		}#for

		if (!$err)
		{
			// start classe..
			$SQL	= new SSQL($dbserver,$dbuser,$dbpass,$dbname);
			make_style();
			make_language($_GET['lang']);
			
			print '<fieldset><form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=end&'.get_lang(1).'">
			<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '"/>
			</form></fieldset>';
			
			//clean cache
			empty_cache_of_kleeja();
		}
		else
		{
			print '<fieldset><span style="color:red;">' . $lang['INST_FINISH_ERRSQL'] . '</span></fieldset>';
		}

	}else{

	//$sitepath = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']);
	$urlsite =  "http://".$_SERVER['HTTP_HOST'] . str_replace('install','',dirname($_SERVER['PHP_SELF']));

 print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=data&'.get_lang(1).'"  onsubmit="javascript:return formCheck(this, Array(\'sitename\',\'siteurl\',\'sitemail\' ,\'username\',\'password\',\'email\' ));">
	<fieldset id="Group1" dir="' . $lang['DIR'] . '">
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

	<fieldset id="Group2" dir="' . $lang['DIR'] . '">
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
		print '<fieldset><img src="img/wink.gif" alt="congratulation" /><br /><br />';
		print '<span style="color:blue;">' . $lang['INST_FINISH_SQL'] . '</span><br /><br />';
		print '<img src="img/home.gif" alt="home" />&nbsp;<a href="../index.php">' . $lang['INDEX'] . '</a><br /><br />';
		print '<img src="img/adm.gif" alt="admin" />&nbsp;<a href="../admin.php">' . $lang['ADMINCP'] . '</a><br /><br />';
		print '' . $lang['INST_KLEEJADEVELOPERS'] . '<br /><br />';
		print '<a href="http://www.kleeja.com">www.kleeja.com</a><br /><br /></fieldset>';
		//for safe ..
		//@rename("install.php", "install.lock");
break;
}#endOFswitch



/*
//print footer
*/
print $footer_inst;



<?php
# KLEEJA INSTALLER ...
# updated  [7/7/2008]
# this file have many updates .. dont use previous ones
# last edit by : saanina

/*
include important files
*/


	define ( 'IN_COMMON' , true);
	$path = "../includes/";
	(file_exists('../config.php')) ? include ('../config.php') : null;
	include ($path.'functions.php');
	include ($path.'mysql.php');
	include ('func_inst.php');
	

/*
//print header
*/
if(!isset($_POST['dbsubmit']))
	print $header_inst;	


/*
//nvigate ..
*/
switch ($_GET['step']) 
{
default:
case 'gpl2':

	$contentofgpl2 = @file_get_contents('../docs/GPL2.txt');
	
	if (strlen($contentofgpl2) < 3 ) 
				$contentofgpl2 = "CANT FIND 'GPL2.TXT. FILE .. SEARCH ON NET ABOUT GPL2";

	print '
	<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=config&'.get_lang(1).'">
	<textarea name="gpl2" rows="" cols="" style="width: 456px; height: 365px;direction:ltr;">
	' . $contentofgpl2 . '
	</textarea>

	<br />
	' . $lang['INST_AGR_GPL2'] . ' <input name="agrec" id="agrec" type="checkbox" onclick="javascript:agree();"  /><br />
	<input name="agres" id="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" disabled="disabled"/>

	</form>';


break;
case 'config':
	
	
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
	
	
	if($xs== false)
	{
		 print '<br/><form method="post"  action="' . $_SERVER['PHP_SELF'] . '?step=config&'.get_lang(1).'"  onsubmit="javascript:return formCheck(this, Array(\'db_server\',\'db_user\',\'db_pass\' ,\'db_name\',\'db_prefix\' ));">

			<fieldset id="Group1" dir="' . $lang['DIR'] . '">
			<b>' . $lang['DB_INFO'] . '</b>
			<br/>
			<br/>
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
			<br/>
			</fieldset>

			<input name="dbsubmit" type="submit" value="' . $lang['INST_EXPORT'] . '" />
			</form>
			<br/>
			<br/>
			<hr/>
			<br/>
			<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=config&'.get_lang(1).'">
			<input  type="submit" value="' . $lang['INST_SUBMIT_CONFIGOK'] . '" />
			</form>
		';
	}
	else
	{
		print ' <br/><span style="color:green;"><b>'. $lang['CONFIG_EXISTS'] . '</b><br/>';
		print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=check&'.get_lang(1).'">
		<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" />
		</form>';
	}



break;
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
		print '<br/><img src="img/bad.gif" alt="bad." /> <br/>'.$texterr;
		$submit_wh = 'disabled="disabled"';
	}

	if($submit_wh == '')
	{
		print '<br/><img src="img/good.gif" alt="good" /> <br/><span style="color:green;"><b>[ ' . $lang['INST_GOOD_GO'] . ' ]</b></span><br/><br/>';
	}

	print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=data&'.get_lang(1).'">
	<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" ' . $submit_wh . '/>
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
				if ($name == 'call')			print '<span style="color:green;">' . $lang['INST_CRT_CALL'] . '</span><br/>';
				elseif ($name == 'reports')		print '<span style="color:green;">' . $lang['INST_CRT_REPRS'] . '</span><br/>';
				elseif ($name == 'stats')		print '<span style="color:green;">' . $lang['INST_CRT_STS'] . '</span><br/>';
				elseif ($name == 'users')		print '<span style="color:green;">' . $lang['INST_CRT_USRS'] . '</span><br/>';
				elseif ($name == 'users')		print '<span style="color:green;">' . $lang['INST_CRT_ADM'] . '</span><br/>';
				elseif ($name == 'files')		print '<span style="color:green;">' . $lang['INST_CRT_FLS'] . '</span><br/>';
				elseif ($name == 'config')		print '<span style="color:green;">' . $lang['INST_CRT_CNF'] . '</span><br/>';
				elseif ($name == 'exts')		print '<span style="color:green;">' . $lang['INST_CRT_EXT'] . '</span><br/>';
				elseif ($name == 'online')		print '<span style="color:green;">' . $lang['INST_CRT_ONL'] . '</span><br/>';
				elseif ($name == 'hooks')		print '<span style="color:green;">' . $lang['INST_CRT_HKS'] . '</span><br/>';
				elseif ($name == 'lang')		print '<span style="color:green;">' . $lang['INST_CRT_LNG'] . '</span><br/>';
				elseif ($name == 'lists')		print '<span style="color:green;">' . $lang['INST_CRT_LSTS'] . '</span><br/>';
				elseif ($name == 'plugins')		print '<span style="color:green;">' . $lang['INST_CRT_PLG'] . '</span><br/>';
				elseif ($name == 'templates')	print '<span style="color:green;">' . $lang['INST_CRT_TPL'] . '</span><br/>';
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
			// start classe..
			$SQL	= new SSQL($dbserver,$dbuser,$dbpass,$dbname);
			make_style();
			make_language($_COOKIE['lang']);
			
			print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=end&'.get_lang(1).'">
			<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '"/>
			</form>';
		}
		else
		{
			print '<span style="color:red;">' . $lang['INST_FINISH_ERRSQL'] . '</span>';
		}

	}else{

	//$sitepath = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']);
	$urlsite =  "http://".$_SERVER['HTTP_HOST'] . str_replace('install','',dirname($_SERVER['PHP_SELF']));

 print '<form method="post" action="' . $_SERVER['PHP_SELF'] . '?step=data&'.get_lang(1).'"  onsubmit="javascript:return formCheck(this, Array(\'sitename\',\'siteurl\',\'sitemail\' ,\'username\',\'password\',\'email\' ));">
	<fieldset id="Group1" dir="' . $lang['DIR'] . '">
	<legend style="width: 73px"><b>' . $lang['INST_SITE_INFO'] . '</b></legend>
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
	<legend style="width: 73px"><b>' . $lang['INST_ADMIN_INFO'] . '</b></legend>
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
			<td>' . $lang['EMAIL'] . '</td>
			<td><input name="email" id="email" type="text" style="width: 256px"  onchange="return w_email(this.name);" /></td>
		</tr>
	</table>
	</fieldset>

	<input name="datasubmit" type="submit" value="' . $lang['INST_SUBMIT'] . '" />
	</form>';
	}#else


break;
case 'end' :
		print '<img src="img/wink.gif" alt="congratulation" /><br/><br/>';
		print '<span style="color:blue;">' . $lang['INST_FINISH_SQL'] . '</span><br/><br/>';
		print '<img src="img/home.gif" alt="home" />&nbsp;<a href="../index.php">' . $lang['INDEX'] . '</a><br/><br/>';
		print '<img src="img/adm.gif" alt="admin" />&nbsp;<a href="../admin.php">' . $lang['ADMINCP'] . '</a><br/><br />';
		print '' . $lang['INST_KLEEJADEVELOPERS'] . '<br/><br/>';
		print '<a href="http://www.kleeja.com">www.kleeja.com</a><br/><br/>';
		//for safe ..
		//@rename("install.php", "install.lock");
break;
}#endOFswitch



/*
//print footer
*/
print $footer_inst;



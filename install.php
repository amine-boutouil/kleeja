<?
# KLEEJA INSTALLER ... BY SAANINA
# 12:11 AM 14/9/1428

/*
include important files
*/

	define ( 'IN_COMMON' , true);
	$path = "includes/";
	include ($path.'config.php');


    // support for php older than 4.1.0
    if ( phpversion() < '4.1.0' ){
        $_GET 			= $HTTP_GET_VARS;
        $_POST 			= $HTTP_POST_VARS;
        $_COOKIE 		= $HTTP_COOKIE_VARS;
        $_SERVER 		= $HTTP_SERVER_VARS;
     }


	// ...header ..  i like it ;)
	header('Content-type: text/html; charset=UTF-8');
	header('Cache-Control: private, no-cache="set-cookie"');
	header('Expires: 0');
	header('Pragma: no-cache');


	//for language //
	if ( isset($_COOKIE['lang']) ) {
	include ('language/' . $_COOKIE['lang'] . '.php');
	}else
	{
	include ('language/ar.php');
	}


/*
style of installer
*/
$header = '<!-- Header Start -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" dir="' . $lang['DIR'] . '"/>
<title>...Kleeja...</title><style type="text/css">
* { padding: 0;margin: 0;}
body {background: FF9933;font: .74em "Tahoma" Verdana, Arial, sans-serif;line-height: 1.5em;text-align:center; }
a {color: #3B6EBF;text-decoration: none;}a:hover {text-decoration: underline;}
.roundedcornr_box_283542 {background: #fff0d2;margin-left: 10%; margin-right: 10%;width: 724px;}
.roundedcornr_top_283542 div {background: url(./images/inst/roundedcornr_283542_tl.png) no-repeat top left;}
.roundedcornr_top_283542 {background: url(./images/inst/roundedcornr_283542_tr.png) no-repeat top right;}
.roundedcornr_bottom_283542 div {background: url(./images/inst/roundedcornr_283542_bl.png) no-repeat bottom left;}
.roundedcornr_bottom_283542 {background: url(./images/inst/roundedcornr_283542_br.png) no-repeat bottom right;}
.roundedcornr_top_283542 div, .roundedcornr_top_283542, .roundedcornr_bottom_283542 div, .roundedcornr_bottom_283542 {
width: 100%;height: 30px;font-size: 1px;}.roundedcornr_content_283542 { margin: 0 30px; }</style></head><body><br/>
<div class="roundedcornr_box_283542"><div class="roundedcornr_top_283542"><div></div></div><div class="roundedcornr_content_283542">
<img src="./images/inst/logo_install.png" style="border:0;">
<br/>
<!-- Header End -->
<br/>
';
$footer = '<br/>
<!-- Foterr Start -->
</div><div class="roundedcornr_bottom_283542"><div></div></div></div></body></html>
<!-- Foterr End -->';
//some variavbles //
$sql_call = "
CREATE TABLE `{$dbprefix}call` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(200) collate utf8_bin NOT NULL,
  `text` varchar(300) collate utf8_bin NOT NULL,
  `mail` varchar(200) collate utf8_bin NOT NULL,
  `time` int(10) NOT NULL,
  `ip` varchar(40) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql_reports = "
CREATE TABLE `{$dbprefix}reports` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(200) collate utf8_bin NOT NULL,
  `mail` varchar(200) collate utf8_bin NOT NULL,
  `url` varchar(250) collate utf8_bin NOT NULL,
  `text` varchar(300) collate utf8_bin NOT NULL,
  `time` int(10) NOT NULL,
  `ip` varchar(40) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql_stat = "
CREATE TABLE `{$dbprefix}stats` (
  `files` int(10) NOT NULL default '0',
  `users` int(10) NOT NULL default '0',
  `sizes` int(10) NOT NULL default '0',
  `last_file` varchar(200) collate utf8_bin NOT NULL,
  `last_f_del` int(10) NOT NULL,
  `today` int(4) NOT NULL,
  `counter_today` int(12) NOT NULL,
  `counter_all` int(12) NOT NULL,
  `counter_yesterday` int(12) NOT NULL,
  `ban` text collate utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql_stat2 = "
INSERT INTO `{$dbprefix}stats`  VALUES (0,1,0,0,0,0,0,0,0,'');
";

$sql_users = "
CREATE TABLE `{$dbprefix}users` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(200) collate utf8_bin NOT NULL,
  `password` varchar(200) collate utf8_bin NOT NULL,
  `mail` varchar(250) collate utf8_bin NOT NULL,
  `admin` tinyint(1) NOT NULL default '0',
  `session_id` char(32) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql_files = "
CREATE TABLE `{$dbprefix}files` (
  `id` int(10) NOT NULL auto_increment,
  `last_down` int(10) NOT NULL,
  `name` varchar(255) collate utf8_bin NOT NULL,
  `size` int(10) NOT NULL,
  `uploads` int(10) NOT NULL,
  `time` int(10) NOT NULL,
  `type` varchar(100) collate utf8_bin NOT NULL,
  `folder` varchar(100) collate utf8_bin NOT NULL,
  `report` int(10) NOT NULL,
  `user` int(10) NOT NULL default '-1',
  `code_del` varchar(150) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql_config = "
CREATE TABLE `{$dbprefix}config` (
  `name` varchar(255) collate utf8_bin NOT NULL,
  `value` varchar(255) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";
$sql_online = "
CREATE TABLE `{$dbprefix}online` (
  `id` int(12) NOT NULL auto_increment,
  `ip` varchar(30) collate utf8_bin NOT NULL,
  `username` varchar(100) collate utf8_bin NOT NULL,
  `agent` varchar(100) collate utf8_bin NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$sql_config1 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('foldername', 'uploads')";
$sql_config2 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('prefixname', '')";
$sql_config3 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('filesnum', '5')";
$sql_config4 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('siteclose', '0')";
$sql_config5 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('decode', '1')";
$sql_config6 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('style', 'bluefreedom')";
$sql_config7 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('closemsg', 'sits is closed now')";
$sql_config8 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('sec_down', '10')";
$sql_config9 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('statfooter', '0')";
$sql_config10 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('gzip', '0')";
$sql_config11 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('welcome_msg', '$lang[INST_MSGINS]')";
$sql_config12 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('user_system', '1')";
$sql_config13 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('register', '1')";
$sql_config14 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('total_size', '10000')";
$sql_config15 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('thumbs_imgs', '0')";
$sql_config16 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('write_imgs', '0')";
$sql_config17 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('del_url_file', '1')";
$sql_config18 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('language', '$_COOKIE[lang]')";
$sql_config19 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('www_url', '0')";
$sql_config20 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('del_f_day', '10')";
$sql_config21 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('allow_stat_pg', '1')";
$sql_config22 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('allow_online', '0')";
$sql_config23 = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('googleanalytics', '')";

$sql_exts = "
CREATE TABLE `{$dbprefix}exts` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `group_id` mediumint(8) unsigned NOT NULL default '0',
  `ext` varchar(100) collate utf8_bin NOT NULL default '',
  `gust_size` int(10) NOT NULL,
  `gust_allow` tinyint(1) NOT NULL default '0',
  `user_size` int(10) NOT NULL,
  `user_allow` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=67 ;
";

$sql_exts2 = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(1, 1, 0x676966, 10000, 1, 10000, 1),(2, 1, 0x706e67, 10000, 1, 10000, 1),(3, 1, 0x6a706567, 10000, 1, 10000, 1),
(4, 1, 0x6a7067, 10000, 1, 10000, 1),(5, 1, 0x746966, 0, 0, 0, 0),(6, 1, 0x74696666, 0, 1, 0, 0),
(7, 1, 0x746761, 0, 0, 0, 0),(8, 2, 0x67746172, 0, 0, 0, 0),(9, 2, 0x677a, 0, 0, 0, 0);";
$sql_exts3 = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(10, 2, 0x746172, 0, 0, 0, 0),(11, 2, 0x7a6970, 10000, 1, 10000, 1),(12, 2, 0x726172, 0, 0, 0, 0),
(13, 2, 0x616365, 0, 0, 0, 0),(14, 2, 0x746f7272656e74, 0, 0, 0, 0),(15, 2, 0x74677a, 0, 0, 0, 0),
(16, 2, 0x627a32, 0, 0, 0, 0),(17, 2, 0x377a, 0, 0, 0, 0),(18, 3, 0x747874, 0, 0, 0, 0);";
$sql_exts4 = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(19, 3, 0x63, 0, 0, 0, 0),(20, 3, 0x68, 0, 0, 0, 0),(21, 3, 0x637070, 0, 0, 0, 0),(22, 3, 0x687070, 0, 0, 0, 0),
(23, 3, 0x64697a, 0, 0, 0, 0),(24, 3, 0x637376, 0, 0, 0, 0),(25, 3, 0x696e69, 0, 0, 0, 0),
(26, 3, 0x6c6f67, 0, 0, 0, 0),(27, 3, 0x6a73, 0, 0, 0, 0),(28, 3, 0x786d6c, 0, 0, 0, 0),(29, 4, 0x786c73, 0, 0, 0, 0);";
$sql_exts5 = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(30, 4, 0x786c7378, 0, 0, 0, 0),(31, 4, 0x786c736d, 0, 0, 0, 0),(32, 4, 0x786c7362, 0, 0, 0, 0),(33, 4, 0x646f63, 0, 0, 0, 0),
(34, 4, 0x646f6378, 0, 0, 0, 0),(35, 4, 0x646f636d, 0, 0, 0, 0),(36, 4, 0x646f74, 0, 0, 0, 0),(37, 4, 0x646f7478, 0, 0, 0, 0),
(38, 4, 0x646f746d, 0, 0, 0, 0),(39, 4, 0x706466, 0, 0, 0, 0),(40, 4, 0x6169, 0, 0, 0, 0),(41, 4, 0x7073, 0, 0, 0, 0);";
$sql_exts6 = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(42, 4, 0x707074, 0, 0, 0, 0),(43, 4, 0x70707478, 0, 0, 0, 0),(44, 4, 0x7070746d, 0, 0, 0, 0),(45, 4, 0x6f6467, 0, 0, 0, 0),
(46, 4, 0x6f6470, 0, 0, 0, 0),(47, 4, 0x6f6473, 0, 0, 0, 0),(48, 4, 0x6f6474, 0, 0, 0, 0),(49, 4, 0x727466, 0, 0, 0, 0),
(50, 5, 0x726d, 0, 0, 0, 0),(51, 5, 0x72616d, 0, 0, 0, 0),(52, 6, 0x776d61, 0, 0, 0, 0),(53, 6, 0x776d76, 0, 0, 0, 0);";
$sql_exts7 = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(54, 7, 0x737766, 0, 0, 0, 0),(55, 8, 0x6d6f76, 0, 0, 0, 0),(56, 8, 0x6d3476, 0, 0, 0, 0),(57, 8, 0x6d3461, 0, 0, 0, 0),
(58, 8, 0x6d7034, 0, 0, 0, 0),(59, 8, 0x336770, 0, 0, 0, 0),(60, 8, 0x336732, 0, 0, 0, 0),(61, 8, 0x7174, 0, 0, 0, 0),
(62, 9, 0x6d706567, 0, 0, 0, 0),(63, 9, 0x6d7067, 0, 0, 0, 0),(64, 9, 0x6d7033, 0, 0, 0, 0),
(65, 9, 0x6f6767, 0, 0, 0, 0),(66, 9, 0x6f676d, 0, 0, 0, 0);";




/*
//print header
*/
if ( !isset($_POST['submitlang']) ) {
print $header;
}



/*
//nvigate ..
*/
switch ($_GET['step']) {
default:
case 'language':
	if ( isset($_POST['submitlang']) ) {
		if ($_POST['lang'] == ''){return false;}else{
		//go to .. 2step
		setcookie("lang", $_POST['lang'], time()+3600);
		echo '<meta http-equiv="refresh" content="0;url=' . $_SERVER[PHP_SELF].'?step=check">';
			}

		}else { //no language

	//get language from LANGUAGE folder
		$path = "language";
		$dh = opendir($path);
		$lngfiles = '';
		$i=1;
		while (($file = readdir($dh)) !== false) {
		    if($file != "." && $file != ".."  && $file != "index.html") {
			$file = str_replace('.php','', $file);
			  $lngfiles .= '<option value="' . $file . '">' . $file . '</option>';
		        $i++;
		    }
		}
		closedir($dh);

	// show  language list ..
	print '<br /><img src="./images/inst/aledrisiMap.gif" style="border:0" alt="Aledrisi Map">
	<br /><form  action="' . $_SERVER[PHP_SELF] . '?step=language" method="post">
	<select name="lang" style="width: 352px">
	' . $lngfiles . '
	</select>
	<br /><input name="submitlang" type="submit" value="[  >>>  ] " /><br /><br /><br /></form>';

		}//no language else



break; // end case language
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
	if (!$connect) {$texterr .= '<span style="color:red;">' . $lang['INST_CONNCET_ERR'] . '</span><br/>';}
	$select = @mysql_select_db($dbname);
	if (!$select) {$texterr .= '<span style="color:red;">' . $lang['INST_SELECT_ERR'] . '</span><br/>';}
	if ( !is_writable('cache') ) {$texterr .= '<span style="color:red;">[cache]: ' . $lang['INST_NO_WRTABLE'] . '</span><br/>';};
	if ( !is_writable('uploads') ) {$texterr .= '<span style="color:red;">[uploads]: ' . $lang['INST_NO_WRTABLE'] . '</span><br/>';};
	if ( !is_writable('uploads/thumbs') ) {$texterr .= '<span style="color:red;">[uploads/thumbs]: ' . $lang['INST_NO_WRTABLE'] . '</span><br/>';};
	if ($texterr !='')
	{
	print $texterr;
	$submit_wh = 'disabled="disabled"';
	}

	if($submit_wh == '')
	{
	print '<span style="color:green;"><b>' . $lang['INST_GOOD_GO'] . '</b></span><br/>';
	}

	print '<form method="post" action="' . $_SERVER[PHP_SELF] . '?step=gpl2">
	<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" ' . $submit_wh . '/>
	</form>';

break;
case 'gpl2':

	$contentofgpl2 = @file_get_contents('./GPL2.txt');
	if (strlen($contentofgpl2) < 3 ) {$contentofgpl2 = "CANT FIND 'GPL2.TXT. FILE .. SEARCH ON NET ABOUT GPL2";}

	print '<script>
	function agree () {
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
	' . $lang['INST_AGR_GPL2'] . ' <input name="agrec" type="checkbox" onclick="agree()"  /><br />
	<input name="agres" type="submit" value="' . $lang['INST_SUBMIT'] . '" disabled="disabled"/>

	</form>';


break;
case 'data' :

	if ( isset($_POST['datasubmit']) ) {

		//check data ...
		if (empty($_POST['sitename']) || empty($_POST['siteurl']) || empty($_POST['sitemail'])
			 || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) ) {

			print $lang['EMPTY_FIELDS'];
			print $footer;
			exit();
			}

		 if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['email']))) {
			print $lang['WRONG_EMAIL'];
			print $footer;
			exit();
			}

		$connect = @mysql_connect($dbserver,$dbuser,$dbpass);
		$select = @mysql_select_db($dbname);
		if ($select) {if (mysql_version>='4.1.0') mysql_query("SET NAMES 'utf8'"); }


		$pass = md5($_POST['password']);

		 /// ok .. will install now ..
		 $sql[0] =  @mysql_query("ALTER DATABASE `{$dbname}` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin", $connect);
		 $sql[1] =  @mysql_query($sql_call, $connect);
		 $sql[2] =  @mysql_query($sql_reports, $connect);
		 $sql[3] =  @mysql_query($sql_stat, $connect);
		 $sql[4] =  @mysql_query($sql_stat2, $connect);
		 $sql[5] =  @mysql_query($sql_users, $connect);
		 $sql[6] =  @mysql_query("INSERT INTO `{$dbprefix}users` (`id`,`name` ,`password` ,`mail`,`admin`) VALUES ('1','$_POST[username]', '$pass', '$_POST[email]','1')", $connect);
		 $sql[7] =  @mysql_query($sql_files, $connect);
		 $sql[8] =  @mysql_query($sql_config, $connect);
		 $sql[9] =  @mysql_query("INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('sitename', '$_POST[sitename]')", $connect);
		 $sql[10] =  @mysql_query("INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('siteurl', '$_POST[siteurl]')", $connect);
		 $sql[11] =  @mysql_query("INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('sitemail', '$_POST[sitemail]')", $connect);
		 $sql[12] =  @mysql_query($sql_config1, $connect);
		 $sql[13] =  @mysql_query($sql_config2, $connect);
		 $sql[14] =  @mysql_query($sql_config3, $connect);
		 $sql[15] =  @mysql_query($sql_config4, $connect);
		 $sql[16] =  @mysql_query($sql_config5, $connect);
		 $sql[17] =  @mysql_query($sql_config6, $connect);
		 $sql[18] =  @mysql_query($sql_config7, $connect);
		 $sql[19] =  @mysql_query($sql_config8, $connect);
		 $sql[20] =  @mysql_query($sql_config9, $connect);
		 $sql[21] =  @mysql_query($sql_config10, $connect);
		 $sql[22] =  @mysql_query($sql_config11, $connect);
		 $sql[23] =  @mysql_query($sql_config12, $connect);
		 $sql[24] =  @mysql_query($sql_config13, $connect);
		 $sql[25] =  @mysql_query($sql_config14, $connect);
		 $sql[26] =  @mysql_query($sql_config15, $connect);
		 $sql[27] =  @mysql_query($sql_config16, $connect);
		 $sql[28] =  @mysql_query($sql_config17, $connect);
		 $sql[29] =  @mysql_query($sql_config18, $connect);
		 $sql[30] =  @mysql_query($sql_config19, $connect);
		 $sql[31] =  @mysql_query($sql_config20, $connect);
		 $sql[32] =  @mysql_query($sql_config21, $connect);
		 $sql[33] =  @mysql_query($sql_config22, $connect);
		 $sql[34] =  @mysql_query($sql_config23, $connect);
		 $sql[35] =  @mysql_query($sql_exts, $connect);
		 $sql[36] =  @mysql_query($sql_exts2, $connect);
		 $sql[37] =  @mysql_query($sql_exts3, $connect);
		 $sql[38] =  @mysql_query($sql_exts4, $connect);
		 $sql[39] =  @mysql_query($sql_exts5, $connect);
		 $sql[40] =  @mysql_query($sql_exts6, $connect);
		 $sql[41] =  @mysql_query($sql_exts7, $connect);
		 $sql[42] =  @mysql_query($sql_online, $connect);
		 $err = 0;
		for ($i=0; $i<count($sql); $i++)
		{

			if ($sql[$i]) {

				if ($i == 1) {print '<span style="color:green;">' . $lang['INST_CRT_CALL'] . '</span><br/>';}
				elseif ($i == 2) {print '<span style="color:green;">' . $lang['INST_CRT_REPRS'] . '</span><br/>';}
				elseif ($i == 3) {print '<span style="color:green;">' . $lang['INST_CRT_STS'] . '</span><br/>';}
				elseif ($i == 5) {print '<span style="color:green;">' . $lang['INST_CRT_USRS'] . '</span><br/>';}
				elseif ($i == 6) {print '<span style="color:green;">' . $lang['INST_CRT_ADM'] . '</span><br/>';}
				elseif ($i == 7) {print '<span style="color:green;">' . $lang['INST_CRT_FLS'] . '</span><br/>';}
				elseif ($i == 8) {print '<span style="color:green;">' . $lang['INST_CRT_CNF'] . '</span><br/>';}
				elseif ($i == 35) {print '<span style="color:green;">' . $lang['INST_CRT_EXT'] . '</span><br/>';}
				elseif ($i == 42) {print '<span style="color:green;">' . $lang['INST_CRT_ONL'] . '</span><br/>';}
				else {print '<span style="color:green;">' . $lang['INST_SQL_OK'] . '</span><br/>';}

			}else{
				print '<span style="color:red;">' . $lang['INST_SQL_ERR'] . '[' . $i . ']</span><br/>';
				$err++;
			}

		if ($i == '42') { $ok = true;}
		}#for

		if ($ok && !$err)
		{
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
	<legend style="width: 73px">' . $lang['INST_SITE_INFO'] . '</legend>
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
		print '<a href="./index.php">' . $lang['INDEX'] . '</a><br/><br/>';
		print '<a href="./admin.php">' . $lang['ADMINCP'] . '</a><br/><br>';
		print '' . $lang['INST_KLEEJADEVELOPERS'] . '';

		//for safe ..
		@rename("install.php", "install.lock");
break;
}#endOFswitch



/*
//print footer
*/
print $footer;



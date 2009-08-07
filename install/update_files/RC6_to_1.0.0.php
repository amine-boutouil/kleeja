<?php
// not for directly open
if (!defined('IN_COMMON'))
{
	exit();
}

//
//db version when this update was released
//
define ('DB_VERSION' , '7');

///////////////////////////////////////////////////////////////////////////////////////////////////////
// sqls /////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

//randome cookie name
$cookie_name = 'klj_' . substr(md5(time()), 0, 6);
// rey to extract cookie domain
$cookie_domain = (!empty($_SERVER['HTTP_HOST'])) ? strtolower($_SERVER['HTTP_HOST']) : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME'));
if (strtolower(substr($cookie_domain, 0, 4) ) == 'www.')
	$cookie_domain = substr($cookie_domain, 4);
if (substr($cookie_domain, 0, 1) != '.' && $cookie_domain != 'localhost')
	$cookie_domain = '.' . $cookie_domain;
$port = strpos($cookie_domain, ':');
if ($port !== false)
	$cookie_domain = substr($cookie_domain, 0, $port);


$update_sqls['up_dbv_config'] = "UPDATE `{$dbprefix}config` SET `value` = '" . DB_VERSION . "' WHERE `name` = 'db_version'";
$update_sqls['online_i'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('last_online_time_update', '" .  time() . "', '', 0)";
$update_sqls['files_del_c'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('klj_clean_files_from', '0', '', 0)";
$update_sqls['online_t'] = "TRUNCATE TABLE `{$dbprefix}online`";
$update_sqls['online_c'] = "ALTER TABLE `{$dbprefix}online` ADD `session` VARCHAR( 100 ) NOT NULL";
$update_sqls['runique_sesion'] = "ALTER TABLE {$dbprefix}online DROP INDEX session";//to prevent dublicate
$update_sqls['unique_sesion'] = "ALTER TABLE `{$dbprefix}online` ADD UNIQUE (`session`)";
$update_sqls['online_moue1'] = "ALTER TABLE `{$dbprefix}stats` ADD `most_user_online_ever` INT( 11 ) NOT NULL";
$update_sqls['online_moue2'] = "ALTER TABLE `{$dbprefix}stats` ADD `lastuser` VARCHAR( 300 ) NOT NULL ";
$update_sqls['online_moue3'] = "ALTER TABLE `{$dbprefix}stats` ADD `last_muoe` INT( 10 ) NOT NULL";

$update_sqls['livexts_feature'] = "INSERT INTO `{$dbprefix}config` (`name` ,`value` ,`option` ,`display_order`)VALUES ('livexts', 'swf', '<input type=\"text\" id=\"livexts\" name=\"livexts\" value=\"{con.livexts}\" size=\"20\">', '70')";
$update_sqls['configs_id_form'] = "UPDATE `{$dbprefix}config` SET `option` = '<select id=\"id_form\" name=\"id_form\">\r\n  <option <IF NAME=\"con.id_form==id\">selected=\"selected\"</IF> value=\"id\">{lang.IDF}</option>\r\n   <option <IF NAME=\"con.id_form==filename\">selected=\"selected\"</IF> value=\"filename\">{lang.IDFF}</option>\r\n <option <IF NAME=\"con.id_form==direct\">selected=\"selected\"</IF> value=\"direct\">{lang.IDFD}</option>\r\n</select>',`display_order` = 29 WHERE  `name` = 'id_form'";
$update_sqls['clean_name'] = "ALTER TABLE `{$dbprefix}users` ADD `clean_name` VARCHAR( 200 ) NOT NULL AFTER `name`";
$update_sqls['new_password'] = "ALTER TABLE `{$dbprefix}users` ADD `new_password` VARCHAR( 200 ) NOT NULL DEFAULT ''";
$update_sqls['hash_key'] = "ALTER TABLE `{$dbprefix}users` ADD `hash_key` VARCHAR( 200 ) NOT NULL DEFAULT ''";
$update_sqls['sitemail2'] = "INSERT INTO `{$dbprefix}config` (`name` ,`value` ,`option` ,`display_order`)
VALUES ('sitemail2', '" . inst_get_config('sitemail') . "', '<input type=\"text\" id=\"sitemail2\" name=\"sitemail2\" value=\"{con.sitemail2}\" size=\"40\">', '3');";
$update_sqls['password_salt'] = "ALTER TABLE `{$dbprefix}users` ADD `password_salt` VARCHAR( 250 ) NOT NULL AFTER `password`";

$update_sqls['type_config'] = "ALTER TABLE `{$dbprefix}config` ADD `type` VARCHAR( 20 ) NOT NULL DEFAULT 'other'";
$update_sqls['type_config_general'] = "UPDATE `{$dbprefix}config` SET `type` = 'general' WHERE `name` IN ('siteclose','closemsg', 'style', 'welcome_msg', 'language', 'siteurl', 'sitemail', 'sitemail2');";
$update_sqls['cookie_1'] = "INSERT INTO `{$dbprefix}config` (
`name` ,
`value` ,
`option` ,
`display_order`
)
VALUES (
'cookie_name', '" . $cookie_name  . "', '<input type=\"text\" id=\"cookie_name\" name=\"cookie_name\" value=\"{con.cookie_name}\" size=\"30\">', '70'
);";
$update_sqls['cookie_2'] = "INSERT INTO `{$dbprefix}config` (
`name` ,
`value` ,
`option` ,
`display_order`
)
VALUES (
'cookie_path', '/', '<input type=\"text\" id=\"cookie_path\" name=\"cookie_path\" value=\"{con.cookie_path}\" size=\"30\">', '70'
);";
$update_sqls['cookie_3'] = "INSERT INTO `{$dbprefix}config` (
`name` ,
`value` ,
`option` ,
`display_order`
)
VALUES (
'cookie_domain', '" . $cookie_domain . "', '<input type=\"text\" id=\"cookie_domain\" name=\"cookie_domain\" value=\"{con.cookie_domain}\" size=\"30\">', '70'
);";

$update_sqls['cookie_4'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('cookie_secure', '0', '<label>{lang.YES}<input type=\"radio\" id=\"cookie_secure\" name=\"cookie_secure\" value=\"1\"  <IF NAME=\"con.cookie_secure==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"cookie_secure\" name=\"cookie_secure\" value=\"0\"  <IF NAME=\"con.cookie_secure==0\"> checked=\"checked\"</IF>></label>', '70')";
 

///////////////////////////////////////////////////////////////////////////////////////////////////////
//notes ////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

$update_notes[]	= $lang['INST_NOTE_RC6_TO_1.0.0'];



///////////////////////////////////////////////////////////////////////////////////////////////////////
//functions ////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

function update_clean_name()
{
	global $SQL, $dbprefix, $path, $lang;
	
	include_once $path . 'usr.php';
	$usrcp = new usrcp;
	$last_id_was = 0;
	$user_per_refresh = 100;
	$is = isset($_GET['is_us']) ? intval($_GET['is_us']) : 0;
	$num_users = isset($_GET['num_users']) ? intval($_GET['num_users']) : 0;
	$loop = isset($_GET['loop']) ? intval($_GET['loop'])+1 : 1;

	$query = array(
					'SELECT'	=> 'COUNT(id) AS total_users',
					'FROM'		=> "{$dbprefix}users",
				);
	
	$result = $SQL->build($query);			
	
	if($is == 0)
	{
		$result = $SQL->build($query);	
		$num_users = 0;
		$n_fetch = $SQL->fetch_array($result);
		$num_users = $n_fetch['total_users'];
	}

	$query = array(
				'SELECT'	=> 'id, clean_name, name',
				'FROM'		=> "{$dbprefix}users",
				'WHERE'		=> 'id > ' . $is,
				'ORDER BY'	=> 'id ASC',
				'LIMIT'		=> $user_per_refresh,
				);
				
	$result = $SQL->build($query);	
	
	while($row=$SQL->fetch_array($result))
	{
		$last_id_was = $row['id'];
		
		if($row['clean_name'] == '')
		{
			$update_query = array(
				'UPDATE'	=> "{$dbprefix}users",
				'SET'		=> "clean_name = '" . $SQL->escape($usrcp->cleanusername($row['name'])) . "'",
				'WHERE'		=> "id=" . $row['id']
				);
			$SQL->build($update_query);
		}
	}
		
	$SQL->freeresult($result);
	
	echo '<br /><span style="color:green;">' . $lang['RC6_1_CNV_CLEAN_NAMES'] . ' [ <strong>'  . $loop . ' -> ' . ceil($num_users/$user_per_refresh) . '</strong> ] </span>';
	if($num_users > $last_id_was)
	{	
		$url = 'update.php?step=update_now&amp;complet_up_func=1&amp;action_file_do=' . htmlspecialchars($_GET['action_file_do']) .'&amp;is_us=' . $last_id_was . '&amp;num_users=' . $num_users . '&amp;loop=' . $loop . '&amp;lang=' . htmlspecialchars($_GET['lang']);
		echo '<meta http-equiv="refresh" content="4; url=' . $url . '" />';
	}
}

$update_functions[]	=	'update_clean_name()';

?>

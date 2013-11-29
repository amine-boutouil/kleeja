<?php
/**
*
* @package install
* @version $Id: func_inst.php 1187 2009-10-18 23:10:13Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/



/**
* @ignore
*/
if (!defined('IN_COMMON'))
{
	exit();
}


//db version when this update was released
define ('DB_VERSION' , '10');

//////////////////////////////////////////////
// sqls ////////////////////////////////////////
//////////////////////////////////////////////

$update_sqls['delete_del_f_day'] = "DELETE FROM `{$dbprefix}config` WHERE `name` IN('del_f_day', 'gzip');";
$update_sqls['configs_no_plg_id'] = "ALTER TABLE `{$dbprefix}config` DROP `plg_id`;";
$update_sqls['configs_to_text'] = "UPDATE `{$dbprefix}config` SET `option`='text' WHERE `name` IN ('sitename', 'siteurl', 'sitemail', 'sitemail2', 'closemsg', 'cookie_name', 'cookie_path', 'cookie_domain', 'foldername', 'prefixname', 'livexts', 'imagefolder','imagefolderexts', 'welcome_msg', 'googleanalytics');";
$update_sqls['configs_to_number'] = "UPDATE `{$dbprefix}config` SET `option`='number' WHERE `name` IN ('total_size', 'filesnum', 'sec_down', 'usersectoupload');";
$update_sqls['configs_to_select'] = "UPDATE  `{$dbprefix}config` SET  `option` =  'select' WHERE  `name` IN ('language','time_zone','user_system','decode','id_form');";
$update_sqls['configs_to_select'] = "UPDATE  `{$dbprefix}config` SET  `option` =  'yes_no' WHERE  `name` IN ('siteclose', 'register', 'enable_userfile', 'mod_writer', 'cookie_secure', 'del_url_file', 'safe_code', 'www_url', 'thumbs_imgs', 'write_imgs', 'filesnum_show', 'imagefoldere', 'allow_stat_pg', 'allow_online', 'statfooter', 'gzip', 'enable_captcha');";
$update_sqls['drop_tables_plugins_hooks_lang'] = "DROP TABLE `{$dbprefix}hooks`, `{$dbprefix}lang`, `{$dbprefix}plugins`;";

$update_sqls['ftp_servers'] = "
CREATE TABLE `{$dbprefix}ftp_servers` (
  `ftp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ftp_name` varchar(200) NOT NULL,
  `ftp_host` varchar(200) NOT NULL,
  `ftp_user` varchar(200) NOT NULL,
  `ftp_password` varchar(200) NOT NULL,
  `ftp_port` int(4) unsigned NOT NULL DEFAULT '21',
  `ftp_folder` varchar(200) NOT NULL,
  `ftp_url` VARCHAR( 200 ) NOT NULL,
  `ftp_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ftp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
$update_sqls['files_ftp'] = "ALTER TABLE  `{$dbprefix}files` ADD  `ftp_server` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0', ADD INDEX (  `ftp_server` );";


//////////////////////////////////////////////////
//notes ///////////////////////////////////////////
//////////////////////////////////////////////////

//$update_notes[]	= $lang['INST_NOTE_RC6_TO_1.5'];


////////////////////////////////////////////////
//functions /////////////////////////////////////
////////////////////////////////////////////////

//$update_functions[]	= '';

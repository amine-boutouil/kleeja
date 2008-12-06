<?php

//
// sql of installing a clean version of Kleeja ..
// kleeja.com
//

// not for directly open
if (!defined('IN_COMMON'))	exit();

$install_sqls	=	array();

$install_sqls['DROP_TABLES'] = "
DROP TABLE IF EXISTS `{$dbprefix}call`, `{$dbprefix}config`, `{$dbprefix}exts`, `{$dbprefix}files`, 
	`{$dbprefix}hooks`, `{$dbprefix}lang`, `{$dbprefix}online`, `{$dbprefix}plugins`, `{$dbprefix}reports`, `{$dbprefix}stats`,
	`{$dbprefix}templates`, `{$dbprefix}users`, `{$dbprefix}lists`;
";


$install_sqls['ALTER_DATABASE_UTF'] = "
ALTER DATABASE `{$dbname}` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin
";



$install_sqls['call'] = "
CREATE TABLE `{$dbprefix}call` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(200) collate utf8_bin NOT NULL,
  `text` varchar(350) collate utf8_bin NOT NULL,
  `mail` varchar(350) collate utf8_bin NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(40) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$install_sqls['reports'] = "
CREATE TABLE `{$dbprefix}reports` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(350) collate utf8_bin NOT NULL,
  `mail` varchar(350) collate utf8_bin NOT NULL,
  `url` varchar(250) collate utf8_bin NOT NULL,
  `text` varchar(400) collate utf8_bin NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(40) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";


$install_sqls['stats'] = "
CREATE TABLE `{$dbprefix}stats` (
  `files` int(11) NOT NULL default '0',
  `users` int(11) NOT NULL default '0',
  `sizes` int(11) NOT NULL default '0',
  `last_file` varchar(350) collate utf8_bin NOT NULL,
  `last_f_del` int(10) NOT NULL,
  `today` int(4) NOT NULL,
  `counter_today` int(12) NOT NULL,
  `counter_all` int(12) NOT NULL,
  `counter_yesterday` int(12) NOT NULL,
  `ban` text collate utf8_bin NOT NULL,
  `last_google` INT(11) UNSIGNED NOT NULL,
  `google_num` INT(11) UNSIGNED NOT NULL,
  `last_yahoo` INT(11) UNSIGNED NOT NULL,
  `yahoo_num` INT(11) UNSIGNED NOT NULL,
   `rules` text collate utf8_bin NOT NULL,
   `ex_header` text collate utf8_bin NOT NULL,
   `ex_footer` text collate utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";



$install_sqls['users'] = "
CREATE TABLE `{$dbprefix}users` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(300) collate utf8_bin NOT NULL,
  `password` varchar(200) collate utf8_bin NOT NULL,
  `mail` varchar(350) collate utf8_bin NOT NULL,
  `admin` tinyint(1) NOT NULL default '0',
  `session_id` char(32) collate utf8_bin NOT NULL,
  `last_visit` INT(11) NOT NULL,
  `show_my_filecp` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$install_sqls['files'] = "
CREATE TABLE `{$dbprefix}files` (
  `id` int(10) NOT NULL auto_increment,
  `last_down` int(11) NOT NULL,
  `name` varchar(350) collate utf8_bin NOT NULL,
  `size` int(10) NOT NULL,
  `uploads` int(10) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(100) collate utf8_bin NOT NULL,
  `folder` varchar(100) collate utf8_bin NOT NULL,
  `report` int(10) NOT NULL,
  `user` int(10) NOT NULL default '-1',
  `code_del` varchar(150) collate utf8_bin NOT NULL,
  `user_ip` VARCHAR( 250 ) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$install_sqls['config'] = "
CREATE TABLE `{$dbprefix}config` (
  `name` varchar(255) collate utf8_bin NOT NULL,
  `value` varchar(255) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";


$install_sqls['exts'] = "
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

$install_sqls['online'] = "
CREATE TABLE `{$dbprefix}online` (
  `id` int(12) NOT NULL auto_increment,
  `ip` varchar(30) collate utf8_bin NOT NULL,
  `username` varchar(100) collate utf8_bin NOT NULL,
  `agent` varchar(100) collate utf8_bin NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$install_sqls['hooks'] = "
CREATE TABLE `{$dbprefix}hooks` (
  `hook_id` int(11) unsigned NOT NULL  auto_increment,
  `plg_id` int(11) unsigned NOT NULL,
  `hook_name` varchar(255) collate utf8_bin NOT NULL,
  `hook_content` mediumtext collate utf8_bin NOT NULL,
  PRIMARY KEY  (`hook_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$install_sqls['lang'] = "
CREATE TABLE `{$dbprefix}lang` (
  `word` varchar(255) collate utf8_bin NOT NULL,
  `trans` varchar(255) collate utf8_bin NOT NULL,
  `lang_id` int(11) unsigned NOT NULL,
  KEY `lang` (`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$install_sqls['lists'] = "
CREATE TABLE `{$dbprefix}lists` (
  `list_id` int(11) unsigned NOT NULL auto_increment,
  `list_name` varchar(255) collate utf8_bin NOT NULL,
  `list_author` varchar(255) collate utf8_bin NOT NULL,
  `list_type` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$install_sqls['plugins'] = "
CREATE TABLE `{$dbprefix}plugins` (
  `plg_id` int(11) unsigned NOT NULL auto_increment,
  `plg_name` varchar(255) collate utf8_bin NOT NULL,
  `plg_ver` varchar(255) collate utf8_bin NOT NULL,
  `plg_author` varchar(255) collate utf8_bin NOT NULL,
  `plg_dsc` varchar(255) collate utf8_bin NOT NULL,
  `plg_uninstall` mediumtext collate utf8_bin NOT NULL,
  `plg_disabled` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`plg_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;
";

$install_sqls['templates'] = "
CREATE TABLE `{$dbprefix}templates` (
  `style_id` int(11) unsigned NOT NULL,
  `template_name` varchar(255) collate utf8_bin NOT NULL,
  `template_content` mediumtext collate utf8_bin NOT NULL,
  KEY `style_id` (`style_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

";

//
// inserts sql
//

$install_sqls['config_insert1'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('foldername', 'uploads')";
$install_sqls['config_insert2'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('prefixname', '')";
$install_sqls['config_insert3'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('filesnum', '5')";
$install_sqls['config_insert4'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('siteclose', '0')";
$install_sqls['config_insert5'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('decode', '1')";
$install_sqls['config_insert6'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('style', '1')";
$install_sqls['config_insert7'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('closemsg', 'sits is closed now')";
$install_sqls['config_insert8'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('sec_down', '10')";
$install_sqls['config_insert9'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('statfooter', '0')";
$install_sqls['config_insert10'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('gzip', '0')";
$install_sqls['config_insert11'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('welcome_msg', '".$lang['INST_MSGINS']."')";
$install_sqls['config_insert12'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('user_system', '1')";
$install_sqls['config_insert13'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('register', '1')";
$install_sqls['config_insert14'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('total_size', '1000')";
$install_sqls['config_insert15'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('thumbs_imgs', '0')";
$install_sqls['config_insert16'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('write_imgs', '0')";
$install_sqls['config_insert17'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('del_url_file', '1')";
$install_sqls['config_insert18'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('language', '" . ($_COOKIE['lang']=='ar' ? '2' : '3') . "')";
$install_sqls['config_insert19'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('www_url', '0')";
$install_sqls['config_insert20'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('del_f_day', '10')";
$install_sqls['config_insert21'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('allow_stat_pg', '1')";
$install_sqls['config_insert22'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('allow_online', '0')";
$install_sqls['config_insert23'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('googleanalytics', '')";
$install_sqls['config_insert24'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('mod_writer', '0')";
$install_sqls['config_insert25'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('enable_userfile', '1')";
$install_sqls['config_insert26'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('safe_code', '0')";
$install_sqls['config_insert27'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('sitename', '$config_sitename')";
$install_sqls['config_insert28'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('siteurl', '$config_siteurl')";
$install_sqls['config_insert29'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`) VALUES ('sitemail', '$config_sitemail')";
		
		
$install_sqls['exts_insert1'] = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(1, 1, 'gif', 100000, 1, 10000, 1),
(2, 1, 'png', 10000, 1, 10000, 1),
(3, 1, 'jpeg', 10000, 1, 10000, 1),
(4, 1, 'jpg', 10000, 1, 10000, 1),
(5, 1, 'tif', 0, 0, 0, 0),
(6, 1, 'tiff', 0, 1, 0, 0),
(7, 1, 'tga', 0, 0, 0, 0),
(9, 2, 'gtar', 0, 0, 0, 0),
(10, 2, 'gz', 0, 0, 0, 0),
(11, 2, 'tar', 0, 0, 0, 0),
(12, 2, 'zip', 10000, 1, 10000, 1),
(13, 2, 'rar', 0, 0, 0, 0);";

$install_sqls['exts_insert2'] = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(14, 2, 'ace', 0, 0, 0, 0),
(15, 2, 'torrent', 0, 0, 0, 0),
(16, 2, 'tgz', 0, 0, 0, 0),
(17, 2, 'bz2', 0, 0, 0, 0),
(18, 2, '7z', 0, 0, 0, 0),
(19, 3, 'txt', 0, 0, 0, 0),
(20, 3, 'c', 0, 0, 0, 0),
(21, 3, 'h', 0, 0, 0, 0),
(22, 3, 'cpp', 0, 0, 0, 0),
(23, 3, 'hpp', 0, 0, 0, 0),
(24, 3, 'diz', 0, 0, 0, 0);";

$install_sqls['exts_insert3'] = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(25, 3, 'csv', 0, 0, 0, 0),
(26, 3, 'ini', 0, 0, 0, 0),
(27, 3, 'log', 0, 0, 0, 0),
(28, 3, 'js', 0, 0, 0, 0),
(29, 3, 'xml', 0, 0, 0, 0),
(30, 4, 'xls', 0, 0, 0, 0),
(31, 4, 'xlsx', 0, 0, 0, 0),
(32, 4, 'xlsm', 0, 0, 0, 0),
(33, 4, 'xlsb', 0, 0, 0, 0),
(34, 4, 'doc', 0, 0, 0, 0),
(35, 4, 'docx', 0, 0, 0, 0),
(36, 4, 'docm', 0, 0, 0, 0);";

$install_sqls['exts_insert4'] = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(37, 4, 'dot', 0, 0, 0, 0),
(38, 4, 'dotx', 0, 0, 0, 0),
(39, 4, 'dotm', 0, 0, 0, 0),
(40, 4, 'pdf', 0, 0, 0, 0),
(41, 4, 'ai', 0, 0, 0, 0),
(42, 4, 'ps', 0, 0, 0, 0),
(43, 4, 'ppt', 0, 0, 0, 0),
(44, 4, 'pptx', 0, 0, 0, 0),
(45, 4, 'pptm', 0, 0, 0, 0),
(46, 4, 'odg', 0, 0, 0, 0),
(47, 4, 'odp', 0, 0, 0, 0),
(48, 4, 'ods', 0, 0, 0, 0);";

$install_sqls['exts_insert5'] = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(49, 4, 'odt', 0, 0, 0, 0),
(50, 4, 'rtf', 0, 0, 0, 0),
(51, 5, 'rm', 0, 0, 0, 0),
(52, 5, 'ram', 0, 0, 0, 0),
(53, 6, 'wma', 0, 0, 0, 0),
(54, 6, 'wmv', 0, 0, 0, 0),
(55, 7, 'swf', 0, 0, 0, 0),
(56, 8, 'mov', 0, 0, 0, 0),
(57, 8, 'm4v', 0, 0, 0, 0),
(58, 8, 'm4a', 0, 0, 0, 0),
(59, 8, 'mp4', 0, 0, 0, 0),
(60, 8, '3gp', 0, 0, 0, 0);";

$install_sqls['exts_insert7'] = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(61, 8, '3g2', 0, 0, 0, 0),
(62, 8, 'qt', 0, 0, 0, 0),
(63, 8, 'avi', 0, 0, 0, 0),
(64, 9, 'mpeg', 0, 0, 0, 0),
(65, 9, 'mpg', 0, 0, 0, 0),
(66, 9, 'mp3', 0, 0, 0, 0),
(67, 9, 'ogg', 0, 0, 0, 0),
(68, 9, 'ogm', 0, 0, 0, 0),
(8, 1, 'bmp', 1, 127, 1, 127);";


$install_sqls['stats_insert'] = "INSERT INTO `{$dbprefix}stats`  VALUES (0,1,0,0," . time() . ",0,0,0,0,'',0,0,0,0,'','','')";

$install_sqls['users_insert'] = "INSERT INTO `{$dbprefix}users` (`id`,`name` ,`password` ,`mail`,`admin`) VALUES ('1','". mysql_real_escape_string($user_name) ."', '" . mysql_real_escape_string($user_pass) ."', '" . mysql_real_escape_string($user_mail) ."','1')";



/*
$install_sqls['lists_insert'] = "
INSERT INTO `{$dbprefix}lists` (`list_id`, `list_name`, `list_author`, `list_type`) VALUES
(1, 'default', '', 1),
(2, 'arabic(sa)', 'official language', 2),
(3, 'english(NK)', 'By:NK, Email: n.k@cityofangelz.com', 2);
";
*/

?>

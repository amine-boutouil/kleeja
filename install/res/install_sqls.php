<?php

//
// sql of installing a clean version of Kleeja ..
// kleeja.com
//

// not for directly open
if (!defined('IN_COMMON'))
{
	exit();
}


$install_sqls = array();

$install_sqls['DROP_TABLES'] = "
DROP TABLE IF EXISTS `{$dbprefix}call`, `{$dbprefix}config`, `{$dbprefix}exts`, `{$dbprefix}files`, `{$dbprefix}hooks`, 
				`{$dbprefix}online`, `{$dbprefix}plugins`, `{$dbprefix}reports`, `{$dbprefix}stats`,`{$dbprefix}users`, `{$dbprefix}lang`;
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
  `sizes` bigint(20) NOT NULL default '0',
  `last_file` varchar(350) collate utf8_bin NOT NULL,
  `last_f_del` int(10) NOT NULL,
  `today` int(4) NOT NULL,
  `counter_today` int(12) NOT NULL,
  `counter_all` int(12) NOT NULL,
  `counter_yesterday` int(12) NOT NULL,
  `ban` text collate utf8_bin NOT NULL,
  `last_google` int(11) unsigned NOT NULL,
  `google_num` int(11) unsigned NOT NULL,
  `last_yahoo` int(11) unsigned NOT NULL,
  `yahoo_num` int(11) unsigned NOT NULL,
  `rules` text collate utf8_bin NOT NULL,
  `ex_header` text collate utf8_bin NOT NULL,
  `ex_footer` text collate utf8_bin NOT NULL,
  `most_user_online_ever` int(11) NOT NULL,
  `lastuser` varchar(300) collate utf8_bin NOT NULL,
  `last_muoe` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";



$install_sqls['users'] = "
CREATE TABLE `{$dbprefix}users` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(300) collate utf8_bin NOT NULL,
  `password` varchar(200) collate utf8_bin NOT NULL,
  `password_salt` varchar(250) collate utf8_bin NOT NULL,
  `mail` varchar(350) collate utf8_bin NOT NULL,
  `admin` tinyint(1) NOT NULL default '0',
  `session_id` char(32) collate utf8_bin NOT NULL,
  `clean_name` varchar(300) collate utf8_bin NOT NULL,
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
  `real_filename` VARCHAR( 350 ) collate utf8_bin NOT NULL,
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
  `option` mediumtext collate utf8_bin NOT NULL,
  `display_order` int(10) NOT NULL,
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
  `session` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `session` (`session`)
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


$install_sqls['lang'] = "
CREATE TABLE `{$dbprefix}lang` (
  `word` varchar(255) collate utf8_bin NOT NULL,
  `trans` varchar(255) collate utf8_bin NOT NULL,
  `lang_id` varchar(100) COLLATE utf8_bin NOT NULL,
  KEY `lang` (`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";


//
// inserts sql
//

$install_sqls['config_insert1'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('foldername', 'uploads', '<input type=\"text\" id=\"foldername\" name=\"foldername\" value=\"{con.foldername}\" size=\"20\">', 4)";

$install_sqls['config_insert2'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('prefixname', '', '<input type=\"text\" id=\"prefixname\" name=\"prefixname\" value=\"{con.prefixname}\" size=\"10\">', 5)";

$install_sqls['config_insert3'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('filesnum', '5', '<input type=\"text\" id=\"filesnum\" name=\"filesnum\" value=\"{con.filesnum}\" size=\"10\">', 6)";

$install_sqls['config_insert4'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('siteclose', '0', '<label>{lang.YES}<input type=\"radio\" id=\"siteclose\" name=\"siteclose\" value=\"1\"  <IF NAME=\"con.siteclose==1\"> checked=\"checked\"</IF>></label><label>{lang.NO}<input type=\"radio\" id=\"siteclose\" name=\"siteclose\" value=\"0\"  <IF NAME=\"con.siteclose==0\"> checked=\"checked\"</IF>></label>', 10)";

$install_sqls['config_insert5'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('decode', '1', '<select id=\"decode\" name=\"decode\">\r\n <option <IF NAME=\"con.decode==0\">selected=\"selected\"</IF> value=\"0\">{lang.NO_CHANGE}</option>\r\n <option <IF NAME=\"con.decode==2\">selected=\"selected\"</IF> value=\"2\">{lang.CHANGE_MD5}</option>\r\n <option <IF NAME=\"con.decode==1\">selected=\"selected\"</IF> value=\"1\">{lang.CHANGE_TIME}</option>\r\n				<!-- another config decode options -->\r\n </select>', 11)";

$install_sqls['config_insert6'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('style', 'default', '<select name=\"style\" id=\"style\">\r\n {stylfiles}\r\n </select>', 18)";

$install_sqls['config_insert7'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('closemsg', 'sits is closed now', '<input type=\"text\" id=\"closemsg\" name=\"closemsg\" value=\"{con.closemsg}\" size=\"40\">', 12)";

$install_sqls['config_insert8'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('sec_down', '10', '<input type=\"text\" id=\"sec_down\" name=\"sec_down\" value=\"{con.sec_down}\" size=\"40\">', 20)";

$install_sqls['config_insert9'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('statfooter', '0', '<label>{lang.YES}<input type=\"radio\" id=\"statfooter\" name=\"statfooter\" value=\"1\"  <IF NAME=\"con.statfooter==1\"> checked=\"checked\"</IF>></label><label>{lang.NO}<input type=\"radio\" id=\"statfooter\" name=\"statfooter\" value=\"0\"  <IF NAME=\"con.statfooter==0\"> checked=\"checked\"</IF>></label>', 23)";

$install_sqls['config_insert10'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('gzip', '0', '<label>{lang.YES}<input type=\"radio\" id=\"gzip\" name=\"gzip\" value=\"1\"  <IF NAME=\"con.gzip==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"gzip\" name=\"gzip\" value=\"0\"  <IF NAME=\"con.gzip==0\"> checked=\"checked\"</IF>></label>', 24)";

$install_sqls['config_insert11'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('welcome_msg', '" . $lang['INST_MSGINS'] . "', '<input type=\"text\" id=\"welcome_msg\" name=\"welcome_msg\" value=\"{con.welcome_msg}\" size=\"40\">', 26)";

$install_sqls['config_insert12'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('user_system', '1', '<select id=\"user_system\" name=\"user_system\">{authtypes}</select>', 15)";

$install_sqls['config_insert13'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('register', '1', '<label>{lang.YES}<input type=\"radio\" id=\"register\" name=\"register\" value=\"1\"  <IF NAME=\"con.register==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"register\" name=\"register\" value=\"0\"  <IF NAME=\"con.register==0\"> checked=\"checked\"</IF>></label>', 16)";

$install_sqls['config_insert14'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('total_size', '10000000000', '<input type=\"text\" id=\"total_size\" name=\"total_size\" value=\"{con.total_size}\" size=\"10\">', 4)";

$install_sqls['config_insert15'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('thumbs_imgs', '0', '<label>{lang.YES}<input type=\"radio\" id=\"thumbs_imgs\" name=\"thumbs_imgs\" value=\"1\"  <IF NAME=\"con.thumbs_imgs==1\"> checked=\"checked\"</IF>></label><label>{lang.NO}<input type=\"radio\" id=\"thumbs_imgs\" name=\"thumbs_imgs\" value=\"0\" <IF NAME=\"con.thumbs_imgs==0\"> checked=\"checked\"</IF>></label></td></tr><tr><td><label for=\"thumbs_imgs\">{lang.DIMENSIONS_THMB}</label></td>\r\n <td><input type=\"text\" id=\"thmb_dim_w\" name=\"thmb_dim_w\" value=\"{thmb_dim_w}\" size=\"2\"> * <input type=\"text\" id=\"thmb_dim_h\" name=\"thmb_dim_h\" value=\"{thmb_dim_h}\" size=\"2\"> ', 9)";

$install_sqls['config_insert16'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('write_imgs', '0', '<div style=\"border:1px outset\"><img src=\"{STAMP_IMG_URL}\" /> <br />\r\n <label>{lang.YES}<input type=\"radio\" id=\"write_imgs\" name=\"write_imgs\" value=\"1\"  <IF NAME=\"con.write_imgs==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"write_imgs\" name=\"write_imgs\" value=\"0\"  <IF NAME=\"con.write_imgs==0\"> checked=\"checked\"</IF>></label>\r\n <br /></div>', 27)";

$install_sqls['config_insert17'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('del_url_file', '1', '<label>{lang.YES}<input type=\"radio\" id=\"del_url_file\" name=\"del_url_file\" value=\"1\"  <IF NAME=\"con.del_url_file==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"del_url_file\" name=\"del_url_file\" value=\"0\"  <IF NAME=\"con.del_url_file==0\"> checked=\"checked\"</IF>></label>', 13)";

$install_sqls['config_insert18'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('language', '" . getlang() . "', '<select name=\"language\" id=\"language\">\r\n {lngfiles}\r\n </select>', 19)";

$install_sqls['config_insert19'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('www_url', '0', '<label>{lang.YES}<input type=\"radio\" id=\"www_url\" name=\"www_url\" value=\"1\"  <IF NAME=\"con.www_url==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"www_url\" name=\"www_url\" value=\"0\"  <IF NAME=\"con.www_url==0\"> checked=\"checked\"</IF>></label>', 8)";

$install_sqls['config_insert20'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('del_f_day', '0', '<input type=\"text\" id=\"del_f_day\" name=\"del_f_day\" value=\"{con.del_f_day}\" size=\"10\">', 7)";

$install_sqls['config_insert21'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('allow_stat_pg', '1', '<label>{lang.YES}<input type=\"radio\" id=\"allow_stat_pg\" name=\"allow_stat_pg\" value=\"1\"  <IF NAME=\"con.allow_stat_pg==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"allow_stat_pg\" name=\"allow_stat_pg\" value=\"0\"  <IF NAME=\"con.allow_stat_pg==0\"> checked=\"checked\"</IF>></label>', 22)";

$install_sqls['config_insert22'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('allow_online', '0', '<label>{lang.YES}<input type=\"radio\" id=\"allow_online\" name=\"allow_online\" value=\"1\"  <IF NAME=\"con.allow_online==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"allow_online\" name=\"allow_online\" value=\"0\"  <IF NAME=\"con.allow_online==0\"> checked=\"checked\"</IF>></label>', 21)";

$install_sqls['config_insert23'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('googleanalytics', '', '<input type=\"text\" id=\"googleanalytics\" name=\"googleanalytics\" value=\"{con.googleanalytics}\" size=\"10\">', 28)";

$install_sqls['config_insert24'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('mod_writer', '0', '<label>{lang.YES}<input type=\"radio\" id=\"mod_writer\" name=\"mod_writer\" value=\"1\"  <IF NAME=\"con.mod_writer==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"mod_writer\" name=\"mod_writer\" value=\"0\"  <IF NAME=\"con.mod_writer==0\"> checked=\"checked\"</IF>></label>\r\n   [ {lang.MOD_WRITER_EX} ]', 25)";

$install_sqls['config_insert25'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('enable_userfile', '1', '<label>{lang.YES}<input type=\"radio\" id=\"enable_userfile\" name=\"enable_userfile\" value=\"1\"  <IF NAME=\"con.enable_userfile==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"enable_userfile\" name=\"enable_userfile\" value=\"0\"  <IF NAME=\"con.enable_userfile==0\"> checked=\"checked\"</IF>></label>', 14)";

$install_sqls['config_insert26'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('safe_code', '0', '<label>{lang.YES}<input type=\"radio\" id=\"safe_code\" name=\"safe_code\" value=\"1\"  <IF NAME=\"con.safe_code==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"safe_code\" name=\"safe_code\" value=\"0\"  <IF NAME=\"con.safe_code==0\"> checked=\"checked\"</IF>></label>', 23)";

$install_sqls['config_insert27'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('sitename', '$config_sitename', '<input type=\"text\" id=\"sitename\" name=\"sitename\" value=\"{con.sitename}\" size=\"40\">', 1)";

$install_sqls['config_insert28'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('siteurl', '$config_siteurl', '<input type=\"text\" id=\"siteurl\" name=\"siteurl\" value=\"{con.siteurl}\" size=\"40\">', 2)";

$install_sqls['config_insert29'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('sitemail', '$config_sitemail', '<input type=\"text\" id=\"sitemail\" name=\"sitemail\" value=\"{con.sitemail}\" size=\"40\">', 3)";


$install_sqls['config_insert30'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('thmb_dims', '100*100', '', 0)";

$install_sqls['config_insert31'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('id_form', 'id', '<select id=\"id_form\" name=\"id_form\">\r\n <option <IF NAME=\"con.id_form==id\">selected=\"selected\"</IF> value=\"id\">{lang.IDF}</option>\r\n <option <IF NAME=\"con.id_form==filename\">selected=\"selected\"</IF> value=\"filename\">{lang.IDFF}</option>\r\n<option <IF NAME=\"con.id_form==direct\">selected=\"selected\"</IF> value=\"direct\">{lang.IDFD}</option>\r\n </select>', 29)";
		
//system config
$install_sqls['config_insert32'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('new_version', '', '', 0)";
$install_sqls['config_insert33'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('db_version', '" .  DB_VERSION . "', '', 0)";
$install_sqls['config_insert34'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('last_online_time_update', '" .  time() . "', '', 0)";
$install_sqls['config_insert35'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('klj_clean_files_from', '0', '', 0)";
$install_sqls['config_insert36'] = "INSERT INTO `{$dbprefix}config` (`name` ,`value` ,`option` ,`display_order`)VALUES ('livexts', 'swf', '<input type=\"text\" id=\"livexts\" name=\"livexts\" value=\"{con.livexts}\" size=\"20\">', '70')";
$install_sqls['config_insert37'] = "INSERT INTO `{$dbprefix}config` (`name` ,`value` ,`option` ,`display_order`)
VALUES ('sitemail2', '" . $config_sitemail . "', '<input type=\"text\" id=\"sitemail2\" name=\"sitemail2\" value=\"{con.sitemail2}\" size=\"40\">', '3');";

$install_sqls['config_insert38'] = "INSERT INTO `{$dbprefix}config` (
`name` ,
`value` ,
`option` ,
`display_order`
)
VALUES (
'cookie_name', 'klj', '<input type=\"text\" id=\"cookie_name\" name=\"cookie_name\" value=\"{con.cookie_name}\" size=\"30\">', '70'
);";
$install_sqls['config_insert39'] = "INSERT INTO `{$dbprefix}config` (
`name` ,
`value` ,
`option` ,
`display_order`
)
VALUES (
'cookie_path', '/', '<input type=\"text\" id=\"cookie_path\" name=\"cookie_path\" value=\"{con.cookie_path}\" size=\"30\">', '70'
);";
$install_sqls['config_insert40'] = "INSERT INTO `{$dbprefix}config` (
`name` ,
`value` ,
`option` ,
`display_order`
)
VALUES (
'cookie_domain', '', '<input type=\"text\" id=\"cookie_domain\" name=\"cookie_domain\" value=\"{con.cookie_domain}\" size=\"30\">', '70'
);";

$install_sqls['config_insert41'] = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`) VALUES ('cookie_secure', '0', '<label>{lang.YES}<input type=\"radio\" id=\"cookie_secure\" name=\"cookie_secure\" value=\"1\"  <IF NAME=\"con.cookie_secure==1\"> checked=\"checked\"</IF>></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"cookie_secure\" name=\"cookie_secure\" value=\"0\"  <IF NAME=\"con.cookie_secure==0\"> checked=\"checked\"</IF>></label>', '70')";


$install_sqls['exts_insert1'] = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(1, 1, 'gif', 100000, 1, 10000, 1),
(2, 1, 'png', 10000, 1, 10000, 1),
(3, 1, 'jpeg', 10000, 1, 10000, 1),
(4, 1, 'jpg', 10000, 1, 10000, 1),
(5, 1, 'bmp', 1, 10000, 1, 10000),
(6, 1, 'psd', 0, 0, 0, 0),
(7, 1, 'tif', 0, 0, 0, 0),
(8, 1, 'tiff', 0, 1, 0, 0),
(9, 1, 'tga', 0, 0, 0, 0),
(10, 2, 'gtar', 0, 0, 0, 0),
(11, 2, 'gz', 0, 0, 0, 0),
(12, 2, 'tar', 0, 0, 0, 0),
(13, 2, 'zip', 10000, 1, 10000, 1),
(14, 2, 'rar', 0, 0, 0, 0);";

$install_sqls['exts_insert2'] = "
INSERT INTO `{$dbprefix}exts` (`id`, `group_id`, `ext`, `gust_size`, `gust_allow`, `user_size`, `user_allow`) VALUES
(15, 2, 'ace', 0, 0, 0, 0),
(16, 2, 'torrent', 0, 0, 0, 0),
(17, 2, 'tgz', 0, 0, 0, 0),
(18, 2, 'bz2', 0, 0, 0, 0),
(19, 2, '7z', 0, 0, 0, 0),
(20, 3, 'txt', 0, 0, 0, 0),
(21, 3, 'c', 0, 0, 0, 0),
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
(68, 9, 'ogm', 0, 0, 0, 0);";


$install_sqls['stats_insert'] = "INSERT INTO `{$dbprefix}stats`  VALUES (0,1,0,0," . time() . ",0,0,0,0,'',0,0,0,0,'','','','','','')";


$install_sqls['users_insert'] = "INSERT INTO `{$dbprefix}users` (`id`,`name`,`password`,`password_salt`,`mail`,`admin`,`clean_name`) VALUES ('1','" . mysql_real_escape_string($user_name) . "', '" . mysql_real_escape_string($user_pass) . "','" . mysql_real_escape_string($user_salt) . "', '" . mysql_real_escape_string($user_mail) . "','1','" . $clean_name . "')";




?>

<?php
// not for directly open
if (!defined('IN_COMMON'))
{
	exit();
}

//
//db version when this update was released
//
define ('DB_VERSION' , '8');

$update_sqls['groups'] = "
CREATE TABLE `{$dbprefix}groups` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `group_is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_is_essential` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$update_sqls['groups_data'] = "
CREATE TABLE `{$dbprefix}groups_data` (
  `group_id` int(11) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `value` varchar(255) COLLATE utf8_bin NOT NULL,
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$update_sqls['groups_acl'] = "
CREATE TABLE `{$dbprefix}groups_acl` (
  `acl_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `acl_can` tinyint(1) unsigned NOT NULL DEFAULT '0',
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$update_sqls['groups_insert'] = "INSERT INTO `{$dbprefix}groups` (`group_id`, `group_name`, `group_is_default`, `group_is_essential`) VALUES
(1, '{lang.ADMINS}', 0, 1),
(2, '{lang.GUESTS}', 0, 1),
(3, '{lang.USERS}', 1, 1),
(4, 'برونزي', 0, 0);";

$update_sqls['admin2founder'] = "ALTER TABLE  `{$dbprefix}users` CHANGE  `admin`  `founder` TINYINT( 1 ) NOT NULL DEFAULT  '0'";
$update_sqls['group_id4users'] = "ALTER TABLE  `{$dbprefix}users` ADD  `group_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT  '3' AFTER  `name` , ADD INDEX (  `group_id` )";

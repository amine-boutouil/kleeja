<?php
// not for directly open
if (!defined('IN_COMMON'))	exit();

///////////////////////////////////////////////////////////////////////////////////////////////////////
// sqls /////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////


$update_sqls['hooks'] = "
CREATE TABLE `{$dbprefix}hooks` (
  `hook_id` int(11) unsigned NOT NULL,
  `plg_id` int(11) unsigned NOT NULL,
  `hook_name` varchar(255) collate utf8_bin NOT NULL,
  `hook_content` mediumtext collate utf8_bin NOT NULL,
  PRIMARY KEY  (`hook_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$update_sqls['lang'] = "
CREATE TABLE `{$dbprefix}lang` (
  `word` varchar(255) collate utf8_bin NOT NULL,
  `trans` varchar(255) collate utf8_bin NOT NULL,
  `lang_id` int(11) unsigned NOT NULL,
  KEY `lang` (`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$update_sqls['lists'] = "
CREATE TABLE `{$dbprefix}lists` (
  `list_id` int(11) unsigned NOT NULL auto_increment,
  `list_name` varchar(255) collate utf8_bin NOT NULL,
  `list_author` varchar(255) collate utf8_bin NOT NULL,
  `list_type` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
";

$update_sqls['plugins'] = "
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

$update_sqls['templates'] = "
CREATE TABLE `{$dbprefix}templates` (
  `style_id` int(11) unsigned NOT NULL,
  `template_name` varchar(255) collate utf8_bin NOT NULL,
  `template_content` mediumtext collate utf8_bin NOT NULL,
  KEY `style_id` (`style_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

";

/*
$update_sqls['lists_insert'] = "
INSERT INTO `{$dbprefix}lists` (`list_id`, `list_name`, `list_author`, `list_type`) VALUES
(1, 'default', '', 1),
(2, 'arabic(sa)', 'official language', 2),
(3, 'english(NK)', 'By:NK, Email: n.k@cityofangelz.com', 2);
";

$update_sqls['config_update1'] = "
UPDATE `{$dbprefix}config` SET `value` = '1' WHERE `name`='style'
";

$update_sqls['config_update2'] = "
UPDATE `{$dbprefix}config` SET `value` = '3' WHERE `name`='language'
";
*/

///////////////////////////////////////////////////////////////////////////////////////////////////////
//notes ////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

$update_notes[]	=	$lang['INST_NOTE_RC2_TO_RC3'];



///////////////////////////////////////////////////////////////////////////////////////////////////////
//functions ////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

function make_style()
{
	$contents	=	file_get_contents('res/style.xml');
	creat_style_xml($contents, true);
}

function make_language()
{
	$contents	=	file_get_contents('res/lang_ar.xml');
	$contents1	=	file_get_contents('res/lang_en.xml');
	creat_lang_xml($contents);
	creat_lang_xml($contents1, true);

}

$update_functions[]	=	'make_style()';
$update_functions[]	=	'make_language()';


?>
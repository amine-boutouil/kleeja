<?php
// not for directly open
if (!defined('IN_COMMON'))	exit();


//
//db version when this update was released
//
define ('DB_VERSION' , '6');

///////////////////////////////////////////////////////////////////////////////////////////////////////
// sqls /////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

$update_sqls['real_filename'] = "ALTER TABLE `{$dbprefix}files` ADD `real_filename` VARCHAR( 350 ) NOT NULL;";
$update_sqls['new_version_config'] = "INSERT INTO `{$dbprefix}config` (`name` ,`value`)VALUES ('new_version', '');";
$update_sqls['thmb_dims_config'] = "INSERT INTO `{$dbprefix}config` (`name` ,`value`)VALUES ('thmb_dims', '100*100');";
$update_sqls['form_id_config'] = "INSERT INTO `{$dbprefix}config` (`name` ,`value`)VALUES ('id_form', 'id');";
$update_sqls['up_dbv_config'] = "UPDATE `{$dbprefix}config` SET `value` = '" . DB_VERSION . "' WHERE `name` = 'db_version'";
$update_sqls['up_lang_config'] = "UPDATE `{$dbprefix}config` SET `value` = '" . getlang() . "' WHERE `name` = 'language'";
$update_sqls['up_style_config'] = "UPDATE `{$dbprefix}config` SET `value` = 'default' WHERE `name` = 'style'";
$update_sqls['drop_lang'] = "DROP TABLE `{$dbprefix}lang`";
$update_sqls['option'] = "ALTER TABLE `{$dbprefix}config` ADD `option` mediumtext NOT NULL;";
$update_sqls['size_in_stats'] = "ALTER TABLE `{$dbprefix}_stats` CHANGE `sizes` `sizes` INT( 25 ) NOT NULL DEFAULT '0';";


//
//is this 
//

$update_sqls['display_order'] = "ALTER TABLE `{$dbprefix}config` ADD `display_order` int(10) NOT NULL;";

$update_sqls['configs1'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"foldername\" name=\"foldername\" value=\"{con.foldername}\" size=\"20\">',`display_order` = 4 WHERE  `name` = 'foldername';";

$update_sqls['configs2'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"prefixname\" name=\"prefixname\" value=\"{con.prefixname}\" size=\"10\">',`display_order` = 5 WHERE  `name` = 'prefixname';";

$update_sqls['configs3'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"filesnum\" name=\"filesnum\" value=\"{con.filesnum}\" size=\"10\">',`display_order` = 6 WHERE  `name` = 'filesnum';";

$update_sqls['configs4'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"siteclose\" name=\"siteclose\" value=\"1\"  <IF NAME=\"con.siteclose==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"siteclose\" name=\"siteclose\" value=\"0\"  <IF NAME=\"con.siteclose==0\"> checked=\"checked\"</IF>></label>',`display_order` = 10 WHERE  `name` = 'siteclose';";

$update_sqls['configs5'] = "UPDATE `{$dbprefix}config` SET `option` = '<select id=\"decode\" name=\"decode\">\r\n                <option <IF NAME=\"con.decode==0\">selected=\"selected\"</IF> value=\"0\">{lang.NO_CHANGE}</option>\r\n                <option <IF NAME=\"con.decode==2\">selected=\"selected\"</IF> value=\"2\">{lang.CHANGE_MD5}</option>\r\n                <option <IF NAME=\"con.decode==1\">selected=\"selected\"</IF> value=\"1\">{lang.CHANGE_TIME}</option>\r\n				<!-- another config decode options -->\r\n                </select>',`display_order` = 11 WHERE  `name` = 'decode';";

$update_sqls['configs6'] = "UPDATE `{$dbprefix}config` SET `option` = '<select name=\"style\" id=\"style\">\r\n                {stylfiles}\r\n                </select>',`display_order` = 18 WHERE  `name` = 'style';";

$update_sqls['configs7'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"closemsg\" name=\"closemsg\" value=\"{con.closemsg}\" size=\"40\">',`display_order` = 12 WHERE  `name` = 'closemsg';";

$update_sqls['configs8'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"sec_down\" name=\"sec_down\" value=\"{con.sec_down}\" size=\"40\">',`display_order` = 20 WHERE  `name` = 'sec_down';";

$update_sqls['configs9'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"statfooter\" name=\"statfooter\" value=\"1\"  <IF NAME=\"con.statfooter==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"statfooter\" name=\"statfooter\" value=\"0\"  <IF NAME=\"con.statfooter==0\"> checked=\"checked\"</IF>></label>',`display_order` = 23 WHERE  `name` = 'statfooter';";

$update_sqls['configs9'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"gzip\" name=\"gzip\" value=\"1\"  <IF NAME=\"con.gzip==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"gzip\" name=\"gzip\" value=\"0\"  <IF NAME=\"con.gzip==0\"> checked=\"checked\"</IF>></label>',`display_order` = 24 WHERE  `name` = 'gzip';";

$update_sqls['configs10'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"welcome_msg\" name=\"welcome_msg\" value=\"{con.welcome_msg}\" size=\"40\">',`display_order` = 26 WHERE  `name` = 'welcome_msg';";

$update_sqls['configs11'] = "UPDATE `{$dbprefix}config` SET `option` = '<select id=\"user_system\" name=\"user_system\">\r\n						{authtypes}           \r\n					</select>',`display_order` = 15 WHERE  `name` = 'user_system';";

$update_sqls['configs11'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"register\" name=\"register\" value=\"1\"  <IF NAME=\"con.register==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"register\" name=\"register\" value=\"0\"  <IF NAME=\"con.register==0\"> checked=\"checked\"</IF>></label>',`display_order` = 16 WHERE  `name` = 'register';";

$update_sqls['configs12'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"total_size\" name=\"total_size\" value=\"{con.total_size}\" size=\"10\">',`display_order` = 4 WHERE  `name` = 'total_size';";

$update_sqls['configs13'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"thumbs_imgs\" name=\"thumbs_imgs\" value=\"1\"  <IF NAME=\"con.thumbs_imgs==1\"> checked=\"checked\"</IF>></label><label>{lang.NO}<input type=\"radio\" id=\"thumbs_imgs\" name=\"thumbs_imgs\" value=\"0\" <IF NAME=\"con.thumbs_imgs==0\"> checked=\"checked\"</IF>></label></td></tr><tr><td><label for=\"thumbs_imgs\">{lang.DIMENSIONS_THMB}</label></td>\r\n <td><input type=\"text\" id=\"thmb_dim_w\" name=\"thmb_dim_w\" value=\"{thmb_dim_w}\" size=\"2\"> * <input type=\"text\" id=\"thmb_dim_h\" name=\"thmb_dim_h\" value=\"{thmb_dim_h}\" size=\"2\"> ',`display_order` = 9 WHERE  `name` = 'thumbs_imgs';";

$update_sqls['configs14'] = "UPDATE `{$dbprefix}config` SET `option` = '<div style=\"border:1px outset\"><img src=\"{STAMP_IMG_URL}\" /> <br />\r\n                <label>{lang.YES}<input type=\"radio\" id=\"write_imgs\" name=\"write_imgs\" value=\"1\"  <IF NAME=\"con.write_imgs==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"write_imgs\" name=\"write_imgs\" value=\"0\"  <IF NAME=\"con.write_imgs==0\"> checked=\"checked\"</IF>></label>\r\n                <br /></div>',`display_order` = 27 WHERE  `name` = 'write_imgs';";

$update_sqls['configs15'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"del_url_file\" name=\"del_url_file\" value=\"1\"  <IF NAME=\"con.del_url_file==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"del_url_file\" name=\"del_url_file\" value=\"0\"  <IF NAME=\"con.del_url_file==0\"> checked=\"checked\"</IF>></label>',`display_order` = 13 WHERE  `name` = 'del_url_file';";

$update_sqls['configs16'] = "UPDATE `{$dbprefix}config` SET `option` = '<select name=\"language\" id=\"language\">\r\n                {lngfiles}\r\n                </select>',`display_order` = 19 WHERE  `name` = 'language';";

$update_sqls['configs17'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"www_url\" name=\"www_url\" value=\"1\"  <IF NAME=\"con.www_url==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"www_url\" name=\"www_url\" value=\"0\"  <IF NAME=\"con.www_url==0\"> checked=\"checked\"</IF>></label>',`display_order` = 8 WHERE  `name` = 'www_url';";

$update_sqls['configs18'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"del_f_day\" name=\"del_f_day\" value=\"{con.del_f_day}\" size=\"10\">',`display_order` = 7 WHERE  `name` = 'del_f_day';";

$update_sqls['configs19'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"allow_stat_pg\" name=\"allow_stat_pg\" value=\"1\"  <IF NAME=\"con.allow_stat_pg==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"allow_stat_pg\" name=\"allow_stat_pg\" value=\"0\"  <IF NAME=\"con.allow_stat_pg==0\"> checked=\"checked\"</IF>></label>',`display_order` = 22 WHERE  `name` = 'allow_stat_pg';";

$update_sqls['configs20'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"allow_online\" name=\"allow_online\" value=\"1\"  <IF NAME=\"con.allow_online==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"allow_online\" name=\"allow_online\" value=\"0\"  <IF NAME=\"con.allow_online==0\"> checked=\"checked\"</IF>></label>',`display_order` = 21 WHERE  `name` = 'allow_online';";

$update_sqls['configs21'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"googleanalytics\" name=\"googleanalytics\" value=\"{con.googleanalytics}\" size=\"10\">',`display_order` = 28 WHERE  `name` = 'googleanalytics';";

$update_sqls['configs22'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"mod_writer\" name=\"mod_writer\" value=\"1\"  <IF NAME=\"con.mod_writer==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"mod_writer\" name=\"mod_writer\" value=\"0\"  <IF NAME=\"con.mod_writer==0\"> checked=\"checked\"</IF>></label>\r\n                  [ {lang.MOD_WRITER_EX} ]',`display_order` = 25 WHERE  `name` = 'mod_writer';";

$update_sqls['configs23'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"enable_userfile\" name=\"enable_userfile\" value=\"1\"  <IF NAME=\"con.enable_userfile==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"enable_userfile\" name=\"enable_userfile\" value=\"0\"  <IF NAME=\"con.enable_userfile==0\"> checked=\"checked\"</IF>></label>',`display_order` = 14 WHERE  `name` = 'enable_userfile';";

$update_sqls['configs24'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"safe_code\" name=\"safe_code\" value=\"1\"  <IF NAME=\"con.safe_code==1\"> checked=\"checked\"</IF>></label>\r\n                <label>{lang.NO}<input type=\"radio\" id=\"safe_code\" name=\"safe_code\" value=\"0\"  <IF NAME=\"con.safe_code==0\"> checked=\"checked\"</IF>></label>',`display_order` = 23 WHERE  `name` = 'safe_code';";

$update_sqls['configs25'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"sitename\" name=\"sitename\" value=\"{con.sitename}\" size=\"40\">',`display_order` = 1 WHERE  `name` = 'sitename';";

$update_sqls['configs26'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"siteurl\" name=\"siteurl\" value=\"{con.siteurl}\" size=\"40\">',`display_order` = 2 WHERE  `name` = 'siteurl';";

$update_sqls['configs27'] = "UPDATE `{$dbprefix}config` SET `option` = '<input type=\"text\" id=\"sitemail\" name=\"sitemail\" value=\"{con.sitemail}\" size=\"40\">',`display_order` = 3 WHERE  `name` = 'sitemail';";

$update_sqls['configs28'] = "UPDATE `{$dbprefix}config` SET `option` = '<select id=\"id_form\" name=\"id_form\">\r\n                <option <IF NAME=\"con.id_form==id\">selected=\"selected\"</IF> value=\"id\">{lang.IDF}</option>\r\n                <option <IF NAME=\"con.id_form==filename\">selected=\"selected\"</IF> value=\"filename\">{lang.IDFF}</option>\r\n                </select>',`display_order` = 29 WHERE  `name` = 'id_form';";

$update_sqls['configs29'] = "UPDATE `{$dbprefix}config` SET `option` = '<select id=\"user_system\" name=\"user_system\">{authtypes}</select>',`display_order` = '15' WHERE  `name` = 'user_system';";

$update_sqls['configs30'] = "UPDATE `{$dbprefix}config` SET `option` = '<label>{lang.YES}<input type=\"radio\" id=\"statfooter\" name=\"statfooter\" value=\"1\"  <IF NAME=\"con.statfooter==1\"> checked=\"checked\"</IF>></label><label>{lang.NO}<input type=\"radio\" id=\"statfooter\" name=\"statfooter\" value=\"0\"  <IF NAME=\"con.statfooter==0\"> checked=\"checked\"</IF>></label>',`display_order` = '23' WHERE  `name` = 'statfooter';";
///////////////////////////////////////////////////////////////////////////////////////////////////////
//notes ////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

$update_notes[]	= $lang['INST_NOTE_RC5_TO_RC6'];



///////////////////////////////////////////////////////////////////////////////////////////////////////
//functions ////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////


//$update_functions[]	=	'name()';

?>

<?php
/**
*
* @package install
* @version $Id: install_sqls.php 1187 2009-10-18 23:10:13Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/

// not for directly open
if (!defined('IN_COMMON'))
{
	exit();
}


//
// Configuration values
//

$config_values = array();

// do it like this : 
//$config_values = array('name', 'value', 'option', 'display_order', 'type');

// General settings
$config_values[] = array('sitename', $config_sitename, '<input type=\"text\" id=\"sitename\" name=\"sitename\" value=\"{con.sitename}\" size=\"50\" />', 1, 'general');
$config_values[] = array('siteurl', $config_siteurl, '<input type=\"text\" id=\"siteurl\" name=\"siteurl\" value=\"{con.siteurl}\" size=\"50\" style=\"direction:ltr\" />', 2, 'general');
$config_values[] = array('sitemail', $config_sitemail, '<input type=\"text\" id=\"sitemail\" name=\"sitemail\" value=\"{con.sitemail}\" size=\"25\" style=\"direction:ltr\" />', 3, 'general');
$config_values[] = array('sitemail2', $config_sitemail, '<input type=\"text\" id=\"sitemail2\" name=\"sitemail2\" value=\"{con.sitemail2}\" size=\"25\" style=\"direction:ltr\" />', '4', 'general');
$config_values[] = array('del_f_day', '0', '<input type=\"text\" id=\"del_f_day\" name=\"del_f_day\" value=\"{con.del_f_day}\" size=\"6\" style=\"text-align:center\" />{lang.DELF_CAUTION}', 5, 'advanced');
$config_values[] = array('language', getlang(), '<select name=\"language\" id=\"language\">\r\n {lngfiles}\r\n </select>', 6, 'general');
$config_values[] = array('siteclose', '0', '<label>{lang.YES}<input type=\"radio\" id=\"siteclose\" name=\"siteclose\" value=\"1\"  <IF NAME=\"con.siteclose==1\"> checked=\"checked\"</IF> /></label><label>{lang.NO}<input type=\"radio\" id=\"siteclose\" name=\"siteclose\" value=\"0\"  <IF NAME=\"con.siteclose==0\"> checked=\"checked\"</IF> /></label>', 7, 'general');
$config_values[] = array('closemsg', 'sits is closed now', '<input type=\"text\" id=\"closemsg\" name=\"closemsg\" value=\"{con.closemsg}\" size=\"68\" />', 8, 'general');
$config_values[] = array('user_system', '1', '<select id=\"user_system\" name=\"user_system\">{authtypes}</select>', 9, 'advanced');
$config_values[] = array('register', '1', '<label>{lang.YES}<input type=\"radio\" id=\"register\" name=\"register\" value=\"1\"  <IF NAME=\"con.register==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"register\" name=\"register\" value=\"0\"  <IF NAME=\"con.register==0\"> checked=\"checked\"</IF> /></label>', 10, 'general');
$config_values[] = array('enable_userfile', '1', '<label>{lang.YES}<input type=\"radio\" id=\"enable_userfile\" name=\"enable_userfile\" value=\"1\"  <IF NAME=\"con.enable_userfile==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"enable_userfile\" name=\"enable_userfile\" value=\"0\"  <IF NAME=\"con.enable_userfile==0\"> checked=\"checked\"</IF> /></label>', 11, 'general');
$config_values[] = array('mod_writer', '0', '<label>{lang.YES}<input type=\"radio\" id=\"mod_writer\" name=\"mod_writer\" value=\"1\"  <IF NAME=\"con.mod_writer==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"mod_writer\" name=\"mod_writer\" value=\"0\"  <IF NAME=\"con.mod_writer==0\"> checked=\"checked\"</IF> /></label>\r\n   [ {lang.MOD_WRITER_EX} ]', 12, 'advanced');

// Cookies settings
$cookie_data = get_cookies_settings();
$config_values[] = array('cookie_name', $cookie_data['cookie_name'], '<input type=\"text\" id=\"cookie_name\" name=\"cookie_name\" value=\"{con.cookie_name}\" size=\"20\" style=\"direction:ltr\" />', '13', 'advanced');
$config_values[] = array('cookie_path', $cookie_data['cookie_path'], '<input type=\"text\" id=\"cookie_path\" name=\"cookie_path\" value=\"{con.cookie_path}\" size=\"20\" style=\"direction:ltr\" />', '14', 'advanced');
$config_values[] = array('cookie_domain', $cookie_data['cookie_domain'], '<input type=\"text\" id=\"cookie_domain\" name=\"cookie_domain\" value=\"{con.cookie_domain}\" size=\"20\" style=\"direction:ltr\" />', '15', 'advanced');
$config_values[] = array('cookie_secure', ($cookie_data['cookie_secure'] ? '1' : '0'), '<label>{lang.YES}<input type=\"radio\" id=\"cookie_secure\" name=\"cookie_secure\" value=\"1\"  <IF NAME=\"con.cookie_secure==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"cookie_secure\" name=\"cookie_secure\" value=\"0\"  <IF NAME=\"con.cookie_secure==0\"> checked=\"checked\"</IF> /></label>', '16', 'advanced');

// Upload settings 
$config_values[] = array('total_size', '10000000000', '<input type=\"text\" id=\"total_size\" name=\"total_size\" value=\"{con.total_size}\" size=\"20\" style=\"direction:ltr\" />', 17, 'upload');
$config_values[] = array('foldername', 'uploads', '<input type=\"text\" id=\"foldername\" name=\"foldername\" value=\"{con.foldername}\" size=\"20\" style=\"direction:ltr\" />', 18, 'upload');
$config_values[] = array('prefixname', '', '<input type=\"text\" id=\"prefixname\" name=\"prefixname\" value=\"{con.prefixname}\" size=\"20\" style=\"direction:ltr\" />', 19, 'upload');
$config_values[] = array('decode', '1', '<select id=\"decode\" name=\"decode\">\r\n <option <IF NAME=\"con.decode==0\">selected=\"selected\"</IF> value=\"0\">{lang.NO_CHANGE}</option>\r\n <option <IF NAME=\"con.decode==2\">selected=\"selected\"</IF> value=\"2\">{lang.CHANGE_MD5}</option>\r\n <option <IF NAME=\"con.decode==1\">selected=\"selected\"</IF> value=\"1\">{lang.CHANGE_TIME}</option>\r\n				<!-- another config decode options -->\r\n </select>', 20, 'upload');
$config_values[] = array('id_form', $config_urls_type, '<select id=\"id_form\" name=\"id_form\">\r\n <option <IF NAME=\"con.id_form==id\">selected=\"selected\"</IF> value=\"id\">{lang.IDF}</option>\r\n <option <IF NAME=\"con.id_form==filename\">selected=\"selected\"</IF> value=\"filename\">{lang.IDFF}</option>\r\n<option <IF NAME=\"con.id_form==direct\">selected=\"selected\"</IF> value=\"direct\">{lang.IDFD}</option>\r\n </select>', 21, 'upload');
$config_values[] = array('filesnum', '5', '<input type=\"text\" id=\"filesnum\" name=\"filesnum\" value=\"{con.filesnum}\" size=\"6\" style=\"text-align:center\" />', 22, 'upload');
$config_values[] = array('sec_down', '10', '<input type=\"text\" id=\"sec_down\" name=\"sec_down\" value=\"{con.sec_down}\" size=\"6\" style=\"text-align:center\" />', 23, 'upload');
$config_values[] = array('del_url_file', '1', '<label>{lang.YES}<input type=\"radio\" id=\"del_url_file\" name=\"del_url_file\" value=\"1\"  <IF NAME=\"con.del_url_file==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"del_url_file\" name=\"del_url_file\" value=\"0\"  <IF NAME=\"con.del_url_file==0\"> checked=\"checked\"</IF> /></label>', 24, 'upload');
$config_values[] = array('safe_code', '0', '<label>{lang.YES}<input type=\"radio\" id=\"safe_code\" name=\"safe_code\" value=\"1\"  <IF NAME=\"con.safe_code==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"safe_code\" name=\"safe_code\" value=\"0\"  <IF NAME=\"con.safe_code==0\"> checked=\"checked\"</IF> /></label>', 25, 'upload');
$config_values[] = array('www_url', '0', '<label>{lang.YES}<input type=\"radio\" id=\"www_url\" name=\"www_url\" value=\"1\"  <IF NAME=\"con.www_url==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"www_url\" name=\"www_url\" value=\"0\"  <IF NAME=\"con.www_url==0\"> checked=\"checked\"</IF> /></label>', 26, 'upload');
$config_values[] = array('thumbs_imgs', '0', '<label>{lang.YES}<input type=\"radio\" id=\"thumbs_imgs\" name=\"thumbs_imgs\" value=\"1\"  <IF NAME=\"con.thumbs_imgs==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"thumbs_imgs\" name=\"thumbs_imgs\" value=\"0\" <IF NAME=\"con.thumbs_imgs==0\"> checked=\"checked\"</IF> /></label></td></tr><tr><td><label for=\"thumbs_imgs\">{lang.DIMENSIONS_THMB}</label></td>\r\n <td><input type=\"text\" id=\"thmb_dim_w\" name=\"thmb_dim_w\" value=\"{thmb_dim_w}\" size=\"2\" style=\"text-align:center\" /> * <input type=\"text\" id=\"thmb_dim_h\" name=\"thmb_dim_h\" value=\"{thmb_dim_h}\" size=\"2\" style=\"text-align:center\" /> ', 27, 'upload');
$config_values[] = array('write_imgs', '0' , '<label>{lang.YES}<input type=\"radio\" id=\"write_imgs\" name=\"write_imgs\" value=\"1\"  <IF NAME=\"con.write_imgs==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"write_imgs\" name=\"write_imgs\" value=\"0\"  <IF NAME=\"con.write_imgs==0\"> checked=\"checked\"</IF> /></label>\r\n <br /><img src=\"{STAMP_IMG_URL}\" alt=\"Seal photo\" style=\"margin-top:4px;border:1px groove #FF865E;\" />\r\n ', 28, 'upload');
$config_values[] = array('livexts', 'swf', '<input type=\"text\" id=\"livexts\" name=\"livexts\" value=\"{con.livexts}\" size=\"62\" style=\"direction:ltr\" />{lang.COMMA_X}', '29', 'upload');
$config_values[] = array('usersectoupload', '10', '<input type=\"text\" id=\"usersectoupload\" name=\"usersectoupload\" value=\"{con.usersectoupload}\" size=\"10\" />', 44, 'upload');
$config_values[] = array('filesnum_show', '0', '<label>{lang.YES}<input type=\"radio\" id=\"filesnum_show\" name=\"filesnum_show\" value=\"1\"  <IF NAME=\"con.filesnum_show==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"filesnum_show\" name=\"filesnum_show\" value=\"0\"  <IF NAME=\"con.filesnum_show==0\"> checked=\"checked\"</IF> /></label>', 22, 'upload');
$config_values[] = array('guestsectoupload', '30', '<input type=\"text\" id=\"guestsectoupload\" name=\"guestsectoupload\" value=\"{con.guestsectoupload}\" size=\"10\" />', 44, 'upload');

// Interface settings 
$config_values[] = array('welcome_msg', $lang['INST_MSGINS'], '<input type=\"text\" id=\"welcome_msg\" name=\"welcome_msg\" value=\"{con.welcome_msg}\" size=\"68\" />', 30, 'interface');
$config_values[] = array('allow_stat_pg', '1', '<label>{lang.YES}<input type=\"radio\" id=\"allow_stat_pg\" name=\"allow_stat_pg\" value=\"1\"  <IF NAME=\"con.allow_stat_pg==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"allow_stat_pg\" name=\"allow_stat_pg\" value=\"0\"  <IF NAME=\"con.allow_stat_pg==0\"> checked=\"checked\"</IF> /></label>', 31, 'interface');
$config_values[] = array('allow_online', '0', '<label>{lang.YES}<input type=\"radio\" id=\"allow_online\" name=\"allow_online\" value=\"1\"  <IF NAME=\"con.allow_online==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"allow_online\" name=\"allow_online\" value=\"0\"  <IF NAME=\"con.allow_online==0\"> checked=\"checked\"</IF> /></label>', 32, 'interface');
$config_values[] = array('statfooter', '0' , '<label>{lang.YES}<input type=\"radio\" id=\"statfooter\" name=\"statfooter\" value=\"1\"  <IF NAME=\"con.statfooter==1\"> checked=\"checked=\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"statfooter\" name=\"statfooter\" value=\"0\"  <IF NAME=\"con.statfooter==0\"> checked=\"checked\"</IF> /></label>', 33, 'interface');
$config_values[] = array('gzip', '0', '<label>{lang.YES}<input type=\"radio\" id=\"gzip\" name=\"gzip\" value=\"1\"  <IF NAME=\"con.gzip==1\"> checked=\"checked\"</IF> /></label>\r\n <label>{lang.NO}<input type=\"radio\" id=\"gzip\" name=\"gzip\" value=\"0\"  <IF NAME=\"con.gzip==0\"> checked=\"checked\"</IF> /></label>', 34, 'interface');
$config_values[] = array('googleanalytics', '', '<input type=\"text\" id=\"googleanalytics\" name=\"googleanalytics\" value=\"{con.googleanalytics}\" size=\"10\" />', 35, 'interface');

// System settings [ invisible configs ]
$config_values[] = array('thmb_dims', '100*100', '', 0);
$config_values[] = array('style', 'default', '', 0, '');
$config_values[] = array('new_version', '', '', 0);
$config_values[] = array('db_version', LAST_DB_VERSION, '', 0);
$config_values[] = array('last_online_time_update', time(), '', 0);
$config_values[] = array('klj_clean_files_from', '0', '', 0);
$config_values[] = array('style_depend_on', '', '', 0);
$config_values[] = array('most_user_online_ever', '', '', 0);
$config_values[] = array('expand_menu', '0', '', 0);
$config_values[] = array('ftp_info', '', '', 0);



//
// Extensions
//

$ext_values = array();

// do it like this : 
//$ext_values[] = array('group_id', 'ext', 'gust_size', 'gust_allow', 'user_size', 'user_allow');


//images
$ext_values[] = array(1, 'gif', 2097152, 1, 2097152, 1);
$ext_values[] = array(1, 'png', 2097152, 1, 2097152, 1);
$ext_values[] = array(1, 'jpeg', 2097152, 1, 2097152, 1);
$ext_values[] = array(1, 'jpg', 2097152, 1, 2097152, 1);
$ext_values[] = array(1, 'bmp', 2097152, 1, 2097152, 1);
$ext_values[] = array(1, 'tif', 0, 0, 0, 0);
$ext_values[] = array(1, 'tiff', 0, 0, 0, 0);
$ext_values[] = array(1, 'tga', 0, 0, 0, 0);
//archives
$ext_values[] = array(2, 'gtar', 0, 0, 0, 0);
$ext_values[] = array(2, 'gz', 0, 0, 0, 0);
$ext_values[] = array(2, 'tar', 0, 0, 0, 0);
$ext_values[] = array(2, 'zip', 2097152, 1, 2097152, 1);
$ext_values[] = array(2, 'rar', 0, 0, 0, 0);
$ext_values[] = array(2, 'ace', 0, 0, 0, 0);
$ext_values[] = array(2, 'torrent', 0, 0, 0, 0);
$ext_values[] = array(2, 'tgz', 0, 0, 0, 0);
$ext_values[] = array(2, 'bz2', 0, 0, 0, 0);
$ext_values[] = array(2, '7z', 0, 0, 0, 0);
//txts
$ext_values[] = array(3, 'c', 0, 0, 0, 0);
$ext_values[] = array(3, 'cpp', 0, 0, 0, 0);
$ext_values[] = array(3, 'hpp', 0, 0, 0, 0);
$ext_values[] = array(3, 'diz', 0, 0, 0, 0);
$ext_values[] = array(3, 'csv', 0, 0, 0, 0);
$ext_values[] = array(3, 'log', 0, 0, 0, 0);
$ext_values[] = array(3, 'js', 0, 0, 0, 0);
$ext_values[] = array(3, 'xml', 0, 0, 0, 0);
//documents
$ext_values[] = array(4, 'xls', 0, 0, 0, 0);
$ext_values[] = array(4, 'xlsx', 0, 0, 0, 0);
$ext_values[] = array(4, 'xlsm', 0, 0, 0, 0);
$ext_values[] = array(4, 'xlsb', 0, 0, 0, 0);
$ext_values[] = array(4, 'doc', 0, 0, 0, 0);
$ext_values[] = array(4, 'docx', 0, 0, 0, 0);
$ext_values[] = array(4, 'docm', 0, 0, 0, 0);
$ext_values[] = array(4, 'dot', 0, 0, 0, 0);
$ext_values[] = array(4, 'dotx', 0, 0, 0, 0);
$ext_values[] = array(4, 'dotm', 0, 0, 0, 0);
$ext_values[] = array(4, 'pdf', 0, 0, 0, 0);
$ext_values[] = array(4, 'ai', 0, 0, 0, 0);
$ext_values[] = array(4, 'ps', 0, 0, 0, 0);
$ext_values[] = array(4, 'ppt', 0, 0, 0, 0);
$ext_values[] = array(4, 'pptx', 0, 0, 0, 0);
$ext_values[] = array(4, 'pptm', 0, 0, 0, 0);
$ext_values[] = array(4, 'odg', 0, 0, 0, 0);
$ext_values[] = array(4, 'odp', 0, 0, 0, 0);
$ext_values[] = array(4, 'ods', 0, 0, 0, 0);
$ext_values[] = array(4, 'odt', 0, 0, 0, 0);
$ext_values[] = array(4, 'rtf', 0, 0, 0, 0);
//real media
$ext_values[] = array(5, 'rm', 0, 0, 0, 0);
$ext_values[] = array(5, 'ram', 0, 0, 0, 0);
//windows media
$ext_values[] = array(6, 'wma', 0, 0, 0, 0);
$ext_values[] = array(6, 'wmv', 0, 0, 0, 0);
//flash
$ext_values[] = array(7, 'swf', 0, 0, 0, 0);
$ext_values[] = array(7, 'flv', 0, 0, 0, 0);
$ext_values[] = array(7, 'fla', 0, 0, 0, 0);
//quicktime media
$ext_values[] = array(8, 'mov', 0, 0, 0, 0);
$ext_values[] = array(8, 'm4v', 0, 0, 0, 0);
$ext_values[] = array(8, 'm4a', 0, 0, 0, 0);
$ext_values[] = array(8, 'mp4', 0, 0, 0, 0);
$ext_values[] = array(8, '3gp', 0, 0, 0, 0);
$ext_values[] = array(8, '3g2', 0, 0, 0, 0);
$ext_values[] = array(8, 'qt', 0, 0, 0, 0);
$ext_values[] = array(8, 'avi', 0, 0, 0, 0);
//other extensions
$ext_values[] = array(9, 'mpeg', 0, 0, 0, 0);
$ext_values[] = array(9, 'mpg', 0, 0, 0, 0);
$ext_values[] = array(9, 'mp3', 0, 0, 0, 0);
$ext_values[] = array(9, 'ogg', 0, 0, 0, 0);
$ext_values[] = array(9, 'ogm', 0, 0, 0, 0);
$ext_values[] = array(9, 'psd', 0, 0, 0, 0);



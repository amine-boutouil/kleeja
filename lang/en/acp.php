<?php
//
// kleeja language, admin
// English
//

if (!defined('IN_COMMON'))
	exit;

if (empty($lang) || !is_array($lang))
	$lang = array();


$lang = array_merge($lang, array(
	'U_NOT_ADMIN' 			=> 'You do not have the administration permissions',
	'UPDATE_CONFIG' 		=> 'Update Settings',
	'NO_CHANGE' 			=> 'Do NOT change',
	'CHANGE_MD5' 			=> 'Change using MD5',
	'CHANGE_TIME' 			=> 'Change using TIME',
	'SITENAME' 				=> 'Service name',
	'SITEMAIL' 				=> 'Email address',
	'SITEMAIL2' 			=> 'Email address of reports',
	'SITEURL' 				=> 'Service URL with / at the end',
	'FOLDERNAME' 			=> 'Folder name for uploaded files',
	'PREFIXNAME' 			=> 'Files prefix <small>( you can also use {rand:4} , {date:d_Y})</small>',
	'FILESNUM' 				=> 'Number of upload input fields',
	'FILESNUM_SHOW' 		=> 'Show all upload inputs',
	'SITECLOSE' 			=> 'Shutdown service',
	'CLOSEMSG' 				=> 'Shutdown message',
	'DECODE' 				=> 'Change file name',
	'SEC_DOWN' 				=> 'Seconds before download',
	'STATFOOTER' 			=> 'Page statistics in footer',
	'GZIP' 					=> 'use gzip',
	'GOOGLEANALYTICS' 		=> '<a href="http://www.google.com/analytics" target="_kleeja"><span style="color:orange">Google</span> Analytics</a>',
	'WELCOME_MSG' 			=> 'Welcome message',
	'USER_SYSTEM' 			=> 'Users system',
	'ENAB_REG' 				=> 'Allow registraion',
	'TOTAL_SIZE' 			=> 'Max service size[Mg]',
	'THUMBS_IMGS' 			=> 'Enable image thumbnails',
	'WRITE_IMGS' 			=> 'Enable image watermark',
	'ID_FORM' 				=> 'Id form',
	'IDF' 					=> 'File id in database',
	'IDFF' 					=> 'File name',
	'IDFD' 					=> 'Directly',
	'DEL_URL_FILE' 			=> 'Enable file deletion URL feature',
	'WWW_URL' 				=> 'Enable uploading from URL',
	'ALLOW_STAT_PG' 		=> 'Enable statistics page',
	'ALLOW_ONLINE' 			=> 'Enable Who is Online',
	'MOD_WRITER' 			=> 'Mod Rewrite',
	'MOD_WRITER_EX' 		=> 'HTML links..',
	'DEL_F_DAY' 			=> 'Delete undownloaded files in',
	'NUMFIELD_S' 			=> 'You can only use numbers with some fields !!',
	'CONFIGS_UPDATED' 		=> 'Settings updated successfully.',
	'UPDATE_EXTS' 			=> 'Update Extensions',
	'SIZE_G' 				=> 'Size [ <font style="color:red">Visitor</font> ]',
	'SIZE_U' 				=> 'Size [ <font style="color:green">User</font> ]',
	'ALLOW_G' 				=> 'Allow <br />[Visitor]',
	'ALLOW_U' 				=> 'Allow <br />[User]',
	'E_EXTS' 				=> 'Note : Sizes are measured in kilobytes .</i>',
	'UPDATED_EXTS' 			=> 'Extensions updated successfully.',
	'UPDATE_REPORTS' 		=> 'Update Reports',
	'E_CLICK' 				=> 'Select one to be viewed here',
	'REPLY' 				=> '[ Reply ]',
	'REPLY_REPORT' 			=> 'Reply on the report',
	'U_REPORT_ON' 			=> 'For your report about ',
	'BY_EMAIL' 				=> 'By email ',
	'ADMIN_REPLIED' 		=> 'Admin Reply',
	'CANT_SEND_MAIL' 		=> 'cannot send reply via email',
	'IS_SEND_MAIL' 			=> 'Reply has been sent.',
	'REPORTS_UPDATED' 		=> 'Reports have been updated.',
	'UPDATE_CALSS' 			=> 'Update Comments',
	'REPLY_CALL' 			=> 'Reply on the comment',
	'REPLIED_ON_CAL' 		=> 'About your comment ',
	'CALLS_UPDATED' 		=> 'Comments updated successfully.',
	'IS_ADMIN' 				=> 'Admin',
	'UPDATE_USERS' 			=> 'Update Users',
	'USERS_UPDATED' 		=> 'Users updated successfully.',
	'E_BACKUP' 				=> 'Select the tables you want to make a backup for:',
	'TAKE_BK' 				=> 'Back-Up',
	'REPAIRE_TABLE' 		=> '[Tables] Repaired. ',
	'REPAIRE_F_STAT' 		=> '[stats] total number of files has been recounted.',
	'REPAIRE_S_STAT' 		=> '[stats] total size of files has been recounted.',
	'REPAIRE_CACHE' 		=> '[Cache] deleted for  ..',
	'KLEEJA_CP' 			=> '[ Kleeja ] Administration',
	'GENERAL_STAT' 			=> 'General Stats',
	'OTHER_INFO' 			=> 'Other Info',
	'AFILES_NUM' 			=> 'Total number of files',
	'AFILES_SIZE' 			=> 'Total size of files',
	'AFILES_SIZE_SPACE' 	=> 'Space that has been consumed so far',
	'AUSERS_NUM' 			=> 'Total users',
	'LAST_GOOGLE' 			=> 'Last visit to Google',
	'GOOGLE_NUM' 			=> 'Google visits',
	'LAST_YAHOO' 			=> 'Last visit to Yahoo!',
	'YAHOO_NUM' 			=> 'Yahoo! visits',
	'KLEEJA_CP_W' 			=> 'Hello ! [ %s ] , Welcome to <b>Kleeja</b> administration panel',
	'PHP_VER' 				=> 'php version',
	'MYSQL_VER' 			=> 'mysql version',
	'LOGOUT_CP_OK' 			=> 'Your administration session has been cleared ..',
	'R_CONFIGS' 			=> 'General Settings',
	'R_CPINDEX' 			=> 'Main Admin Page',
	'R_EXTS' 				=> 'Extensions Settings',
	'R_FILES' 				=> 'Files Control',
	'R_REPORTS' 			=> 'Reports',
	'R_CALLS' 				=> 'Messages',
	'R_USERS' 				=> 'Users Control',
	'R_BCKUP' 				=> 'Back-Up',
	'R_REPAIR' 				=> 'Total Repair',
	'R_LGOUTCP' 			=> 'Clear Session',
	'R_BAN' 				=> 'Ban Control',
	'BAN_EXP1' 				=> 'Edit the banned IPs and add new ones here ..',
	'BAN_EXP2' 				=> 'Use the star (*) symbol to replace numbers if you want a total ban.... and use the (|) to separate the IPs',
	'UPDATE_BAN' 			=> 'Save Changes',
	'BAN_UPDATED' 			=> 'Changes saved successfully.',
	'R_RULES' 				=> 'Terms',
	'RULES_EXP' 			=> 'You can edit the terms and conditions of your service from here',
	'UPDATE_RULES' 			=> 'Update',
	'RULES_UPDATED' 		=> 'Terms and conditions updated successfully ..',
	'R_SEARCH' 				=> 'Advanced search',
	'SEARCH_FILES' 			=> 'Search for files',
	'SEARCH_SUBMIT' 		=> 'Search now',
	'LAST_DOWN' 			=> 'Last download',
	'WAS_B4' 				=> 'Was before',
	'SEARCH_USERS' 			=> 'Search for users',
	'R_IMG_CTRL' 			=> 'Image control only',
	'ENABLE_USERFILE' 		=> 'Enable users files',
	'R_EXTRA' 				=> 'Extra Templates',
	'EX_HEADER_N' 			=> 'Extra header ... which shows at the bottom of the original header',
	'EX_FOOTER_N' 			=> 'Extra footer ... which shows at the top of the original footer',
	'UPDATE_EXTRA' 			=> 'Update template additions',
	'EXTRA_UPDATED' 		=> 'Template additions updated successfully',
	'R_STYLES' 				=> 'Styles',
	'STYLES_EXP' 			=> 'Select a style from below to delete or update it',
	'SHOW_TPLS' 			=> 'Show Templates',
	'TPL_UPDATED' 			=> 'Template updated...',
	'TPL_DELETED' 			=> 'Template deleted ...',
	'NO_TPL_SHOOSED' 		=> 'You did not select a template!',
	'NO_TPL_NAME_WROTE' 	=> 'Please enter the name of the template!',
	'ADD_NEW_STYLE' 		=> 'Create a new style',
	'EXPORT_AS_XML' 		=> 'Export As xml',
	'NEW_STYLES_EXP' 		=> 'Upload style from a XML file',
	'NEW_STYLE_ADDED' 		=> 'Style added successfully',
	
	'ERR_IN_UPLOAD_XML_FILE' 		=> '(ERR:XML) Error uploading...',
	'ERR_UPLOAD_XML_FILE_NO_TMP' 	=> '(ERR:NOTMP) Error uploading...',
	'ERR_UPLOAD_XML_NO_CONTENT' 	=> 'The file selected is blank!',
	'ERR_XML_NO_G_TAGS' 			=> 'Some required tags are missing from the file!',
	'STYLE_DELETED' 				=> 'Style successfully removed',
	'STYLE_1_NOT_FOR_DEL' 			=> 'You cannot delete the default style!',
	'ADD_NEW_TPL' 					=> 'Add a new template',
	'ADD_NEW_TPL_EXP' 				=> 'Enter a name for the new template',
	'TPL_CREATED' 					=> 'New template successfully created...',
	'R_LANGS' 						=> 'words and phrases',
	'WORDS_UPDATED' 				=> 'Words successfully updated...',
	'R_PLUGINS' 					=> 'Plugins',
	'PLUGINS_EX' 				=> 'Delete or updated plugins here...',
	'ADD_NEW_PLUGIN' 			=> 'Add plugin',
	'ADD_NEW_PLUGIN_EXP' 		=> 'Upload plugin from a XML file',
	'PLUGIN_DELETED' 			=> 'Plugin deleted...',
	'PLGUIN_DISABLED_ENABLED' 	=> 'Plugin Enabled / Disabled',
	'NO_PLUGINS' 				=> 'No plugins found',
	'NEW_PLUGIN_ADDED' 			=> 'Plugin added ... <br /> Note: some plugins come with extra files , you need to transfer them to root folder of Kleeja.',
	'PLUGIN_EXISTS_BEFORE' 		=> 'This plugin exists before with same version or above, so no need to update it!.',
	'PLUGIN_UPDATED_SUCCESS' 	=> 'This plugin is updated successfully...',
	'R_CHECK_UPDATE' 			=> 'Check for updates',
	'ERROR_CHECK_VER' 			=> 'Error: cannot get any update information at this momoent , try again later !',
	'UPDATE_KLJ_NOW' 			=> 'You Have to update your version now!. visit Kleeja.com for more inforamtion',
	'U_LAST_VER_KLJ' 			=> 'You are using the lastest version of Kleeja...',
	'U_USE_PRE_RE' 				=> 'You are using a Pre-release version, Click <a href="http://www.kleeja.com/bugs/">here</a> to report any bugs or exploits.',
	'STYLE_IS_DEFAULT'			=> 'Default style',
	'MAKE_AS_DEFAULT'			=> 'Set as default',
	'TPLS_RE_BASIC'				=>	'Basic templates', 
	'TPLS_RE_MSG'				=>	'Notification templates', 
	'TPLS_RE_USER'				=>	'User templates', 
	'TPLS_RE_OTHER'				=>	'Other templates',
	'STYLE_NOW_IS_DEFAULT' 		=> 'The style "%s" was set as default',
	'STYLE_DIR_NOT_WR'			=>	'The style directory %s is not writeable therefore, you cannot edit the templates until you CHMOD it to 777.',
	'TPL_PATH_NOT_FOUND' 		=> 'Template %s cannot be found !',
	'NO_CACHED_STYLES'			=> 'No currently cached styles !',
	'SEARCH_FOR'				=> 'Look for',
	'REPLACE_WITH'				=> 'Replace with',
	'REPLACE_TO_REACH'			=> 'Until you reach the next code',
	'ADD_AFTER'					=> 'Add after',
	'ADD_AFTER_SAME_LINE'		=> 'Add after it in the same line',
	'ADD_BEFORE'				=> 'Add before',
	'ADD_BEFORE_SAME_LINE'		=> 'Add before it in the same line',
	'ADD_IN'					=> 'Add in it after created',
	'CACHED_STYLES_DELETED'		=>'Cached styles deleted.',
	'CACHED_STYLES'				=>' Cached Styles',
	'DELETE_CACHED_STYLES'		=>'Delete cached styles',
	'CACHED_STYLES_DISC'		=>	'The stored templates are the remaining modifications from additions that were not applied either because of the permissions or the lack of a suitable search keyword therefore, it needs to be set manually %s .',
	'UPDATE_NOW_S'				=>	'You are using an old version of Kleeja. Update Now. Your currect version is %1$s and the latest one is %2$s',
	'ADD_NEW_EXT'				=> 'Add a new extension',
	'ADD_NEW_EXT_EXP'			=> 'Enter extension and choose category',
	'EMPTY_EXT_FIELD'			=>	'The extension field is blank!', 
	'NEW_EXT_ADD'				=>	'New extension added. ',
	'NEW_EXT_EXISTS_B4'			=>	'The extension %s already exists!.',
	'NOT_SAFE_FILE'				=> 'The file "%s" does not look safe !',
	'CONFIG_WRITEABLE'			=> 'The file config.php is currently writeable, We strongly recommend that it be changed to 640 or at least 644.',
	'NO_KLEEJA_COPYRIGHTS'		=> 'you seem to have accidentally removed the copyrights from the footer, please put it back on so we can continue to develop Kleeja free of charge, you can buy a copyright removal license %s .',
	'USERS_NOT_NORMAL_SYS'		=> 'The current users system is not the normal one, which means that the current users cannot be edited from here but from the script that was integrated with Kleeja, those users use the normal membership system.',
	'DIMENSIONS_THMB'			=> 'Thumbs dimensions',
	'ADMIN_DELETE_FILE_ERR'		=> 'There is error occurred while trying to delete user files . ',
	'ADMIN_DELETE_FILE_OK'		=> 'Done ! ',
	'ADMIN_DELETE_FILES'		=> 'Delete all user files',
	
	'KLJ_MORE_PLUGINS'			=> array('Get more plugins from Kleeja\'s plugin center ,<a target="_blank" href="http://www.kleeja.com/plugins/">click here</a> .',
								'Are you a developer? have you developed plugins for Kleeja & you want to showcase  in Kleeja\'s plugins center? <a target="_blank" href="http://www.kleeja.com/plugins/">click here</a>. ',
								),
	'KLJ_MORE_STYLES'			=> array('Get more styles from Kleeja\'s style gallery ,<a target="_blank" href="http://www.kleeja.com/styles/">click here</a> .',
								'Are you a designer? looking to showcase your styles in Kleeja\'s gallery for everyone? <a target="_blank" href="http://www.kleeja.com/styles/">click here</a> .',
								),
	'BCONVERTER' 				=> 'Byte Converter',
	'NO_HTACCESS_DIR_UP'		=> 'No .htaccess file was found in "%s" folder, Which means if malicious codes were injected a hacker can do damage to your website!',
	'NO_HTACCESS_DIR_UP_THUMB'	=> 'No .htaccess file was found in Thumbs folder "%s", Which means if malicious codes were injected a hacker can do damage to your website!',
	'COOKIE_DOMAIN' 			=> 'Cookie domain',
	'COOKIE_NAME' 				=> 'Cookie prefix',
	'COOKIE_PATH' 				=> 'Cookie path',
	'COOKIE_SECURE'				=> 'Cookie secure',
	'ADMINISTRATORS'			=> 'Administrators',
	'DELETEALLRES'				=> 'Delete all results',
	'ADMIN_DELETE_FILES_OK'     => 'File %s successfully deleted',
	'ADMIN_DELETE_FILES_NOF'	=> 'No files to delete',
	'NOT_EXSIT_USER'			=> 'Sorry, the user you are looking for does not exist in our database... perhaps you are trying to reach a deleted membership !!!!',
	'ADMIN_DELETE_NO_FILE'		=> 'This user has no files to delete ! .',
	'CONFIG_KLJ_MENUS_OTHER'	=> 'Other settings',
	'CONFIG_KLJ_MENUS_GENERAL'	=> 'General settings',
	'CONFIG_KLJ_MENUS_ALL'		=> 'Display all the settings',
	'CONFIG_KLJ_MENUS_UPLOAD'	=> 'Upload settings',
	'CONFIG_KLJ_MENUS_INTERFACE'=> 'Interface and design settings',
	'CONFIG_KLJ_MENUS_ADVANCED' => 'Advanced settings',
	'DELF_CAUTION'				=> '<span class="delf_caution">Caution: this function might be dangerous when using small numbers .</span>',
	'PLUGIN_N_CMPT_KLJ'			=> 'This plugin is not compatible with your current version of Kleeja.',
	'PHPINI_FILESIZE_SMALL'		=> 'Maximum file size allowed for your service is "%1$s" while upload_max_filesize in your hosts PHP settings is set to "%2$s" upload it so that your chosen size can be applied.',
	'PHPINI_MPOSTSIZE_SMALL'	=> 'You have allowed the upload of "%1$s" files at once, You need to use a bigger value for post_max_size in your servers PHP settings, something like "%2$s" for a better performance.',
	'NUMPER_REPORT' 			=> 'Number of reports',
	'NO_UP_CHANGE_S'			=> 'No changes ...',
	'ADD_HEADER_EXTRA' 			=> 'Extra Header',
	'ADD_FOOTER_EXTRA' 			=> 'Extra footer',
	'ADMIN_USING_IE6'			=> 'You are using IE6, Please update your browser or use FireFox now!!',
	'FOOTER_TXTS'				=> array('PLUGINS'=> 'Plugins', 'STYLES'=>'Styles', 'BUGS'=>'Bug report'),
	'T_ISNT_WRITEABLE'			=> 'Cannot edit <strong>%s</strong> template. (Unwriteable)',
	'T_CLEANING_FILES_NOW'		=> 'Deleting temp files, The process could take a while depending on the size of the files.',
	'HOW_UPDATE_KLEEJA'			=> 'How to update Kleeja?',
	'HOW_UPDATE_KLEEJA_STEP1'	=> 'Visit the official website <a target="_blank" href="http://www.kleeja.com/">Kleeja.com</a> then go to the Download page and download the latest version of the script, or download an upgrade copy if available.',
	'HOW_UPDATE_KLEEJA_STEP2'	=> 'Unzip the file and upload it to your website to replace the old files with the new ones <b>Except config.php</b>.',
	'HOW_UPDATE_KLEEJA_STEP3'	=> 'When done, go to the following URL to update the database.',
	'RETURN_TEMPLATE_BK'		=> 'Restore any backup template',
	'RETURN_TEMPLATE_BK_EXP'	=> 'Choose any backup template to restore to original, these templates belong to the default style.',
	'TPL_BK_RETURNED'			=> 'Backup copy restored for template %s.',
	'REPLACE_WHOLW_TPL'			=> 'Replace the whole template',
	'DEPEND_ON_NO_STYLE_ERR'	=> 'This style is based on the "%s" style which you dont seem to have', 
	'PLUGINS_REQ_NO_STYLE_ERR'	=> 'This style requires the [ s% ] plugin(s), install it/them and try again.', 
	'PLUGIN_REQ_BY_STYLE_ERR'	=> 'The current default style requires this plugin, to remove or disable it you need to change the default style first.', 
	'KLJ_VER_NO_STYLE_ERR'		=> 'This style requires Kleeja version %s or above',
	'KLJ_STYLE_INFO'			=> 'Style info',
	'STYLE_NAME'				=> 'Style name',
	'STYLE_COPYRIGHT'			=> 'Copyrights',
	'STYLE_VERSION'				=> 'Style version',
	'STYLE_DEPEND_ON'			=> 'Based on',
	'MESSAGE_NONE'				=> 'No messages yet ...',
	'KLEEJA_TEAM'				=> 'Kleeja development team',
	'ERR_SEND_MAIL'				=> 'Mail sending error, try again later !',
	'FIND_IP_FILES' 			=> 'Found',
	'ALPHABETICAL_ORDER_FILES'	=> 'Sort files by alphabetical order', 
	'ORDER_SIZE'				=> 'Sort files by size from largest to smallest',
	'ORDER_TOTAL_DOWNLOADS'		=> 'Sort files by number of downloads', 
	'COMMA_X'					=> '<p class="live_xts">separate by comma (<font style="font-size:large"> , </font>)</p>',
	'NO_SEARCH_WORD'			=> 'You didn\'t type anything in the search form !',
	'GUESTSECTOUPLOAD'			=> 'The period time (number of seconds) between each upload process for the visitor',
	'USERSECTOUPLOAD'			=> 'The period time (number of seconds) between each upload process for the registered user',
	'ADM_UNWANTED_FILES'		=> 'You seem to have upgraded from a previous version, and because some file names are different now, you\'ll notice duplicated buttons in control panel. </ br> to solve this, remove all the files in "includes/adm" directory and re-upload them.',
	'ADVANCED_SETTINGS_CATUION' => 'Caution : you must know what these settings are in order to edit them!',
	'HTML_URLS_ENABLED_NO_HTCC'	=> 'you have enabled the htaccess URLs, but you seem to have forgot to move the config file from docs/.htaccess.txt to Kleeja\'s root directory. you also need to rename it to ".htaccess" however, if you don\'t know what i\'m talking about, go to Kleeja\'s support forums or simply disable the htaccess function.',	
	'PLUGIN_WT_FILE_METHOD'		=> 'Some plugins require file modification or adding new files, choose the way you want to handle the files:',
	'PLUGIN_ZIP_FILE_METHOD'	=> 'Give me the new and the modified files so i can upload and replace them manually.',
	'PLUGIN_FTP_FILE_METHOD'	=> 'Using the FTP method.',	
	'PLUGIN_FTP_EXP'			=> 'You can\'t modify the files without FTP access, write your FTP access info below to install the plugin.',
	'PLUGIN_FTP_HOST'			=> 'FTP Host Address',
	'PLUGIN_FTP_USER'			=> 'FTP Username',
	'PLUGIN_FTP_PASS'			=> 'FTP Password',
	'PLUGIN_FTP_PATH'			=> 'Script directory on FTP',
	'PLUGIN_FTP_PORT'			=> 'FTP Port <small>(usually 21, If you are unsure leave it as it is.)</small>',
	'PLUGIN_CONFIRM_ADD'		=> 'Caution: the plugins make programmatical changes to the script, and they could be harmful at times. so be sure to check the plugin source and make sure that it\'s an official Kleeja plugin, Do you want to continue?',
	'PLUGIN_ADDED_ZIPPED'		=> 'Plugin added. to complete the setup process %1$sdownload%2$s the modified files and replace them manually. forgetting or ignoring to replace the files can lead to plugin malfunction.',
	'PLUGIN_ADDED_ZIPPED_INST'	=> 'Plugin added. to complete the setup process %1$sdownload%2$s the modified files and replace them manually. forgetting or ignoring to replace the files can lead to plugin malfunction. </ br> you should also %3$sreads%4$s the plugin instructions for more info. Or you can read them later on the plugins page.',
	'PLUGIN_DELETED_ZIPPED'		=> 'The plugin has been deleted, to complete the deletion %1$sdownload%2$s the modified files and replace them with the current files in Kleeja manually.', 
	'PLUGINS_CHANGES_FILES'		=> 'Modified files as a result of plugin installation', 
	'PLUGINS_CHANGES_FILES_EXP'	=> 'These are compressed files that contain the modified files that were changed for some plugins, you need to download them from here and replace them. you can remove the zip files when done.',

));

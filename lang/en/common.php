<?php
//
// kleeja language
// English
// By : Fenix4Web
//

if (!defined('IN_COMMON'))
	exit;

if (empty($lang) || !is_array($lang))
	$lang = array();



$lang = array_merge($lang, array(
	'DIR' => 'ltr',
	'HOME' => 'Home',
	'INDEX' => 'Index',
	'SITE_CLOSED' => 'The website is closed.',
	'STOP_FOR_SIZE' => 'The service is currently stopped.',
	'SIZES_EXCCEDED' => 'We have ran out of space ... we will be back soon.',
	'ENTER_CODE_IMG' => 'Enter verification code.',
	'SAFE_CODE_UPLOAD' => 'Enable safety code for downloads',
	'LAST_VISIT' => 'Your last visit',
	'FLS_LST_VST_SEARCH' => 'Show files from last visit ?',
	'IMG_LST_VST_SEARCH' => 'Show images from last visit ?',
	'NEXT' => 'Next',
	'PREV' => 'Prev.',
	'INFORMATION' => 'Instructions',
	'WELCOME' => 'Welcome',
	'KLEEJA_VERSION' => 'Kleeja version',
	'NUMBER_ONLINE' => 'online users',
	'NUMBER_UONLINE' => 'users',
	'NUMBER_VONLINE' => 'guests',
	'USERS_SYSTEM' => 'Users System',
	'ERROR_NAVIGATATION' => 'Redirection Error ..',
	'LOGIN' => 'Login',
	'USERNAME' => 'User name',
	'PASSWORD' => 'Password',
	'EMPTY_USERNAME' => 'Please enter your username',
	'EMPTY_PASSWORD' => 'Please enter your password',
	'LOSS_PASSWORD' => 'Forgot Password?',
	'LOGINED_BEFORE' => 'You are already logged in.',
	'LOGOUT' => 'Logout ',
	'EMPTY_FIELDS' => 'Error ... Missing Fields!',
	'LOGIN_SUCCESFUL' => 'You have logged in successfully.',
	'LOGIN_ERROR' => 'Error ... cannot login!',
	'REGISTER_CLOSED' => 'Sorry, the registration is currently closed.',
	'PLACE_NO_YOU' => 'Restricted Area',
	'REGISTERED_BEFORE' => 'already',
	'REGISTER' => 'Register',
	'EMAIL' => 'Email address',
	'VERTY_CODE' => 'Security code',
	'WRONG_EMAIL' => 'Incorrect email address!',
	'WRONG_NAME' => 'The username must be longer than 4 characters!',
	'WRONG_LINK' => 'Incorrect link ..',
	'EXIST_NAME' => 'Someone has already registered with this username!',
	'EXIST_EMAIL' => 'Someone with this email address has already registered!',
	'WRONG_VERTY_CODE' => 'Incorrect security code!',
	'CANT_UPDATE_SQL' => 'cant update database!',
	'CANT_INSERT_SQL' => 'cant insert data to the database!',
	'REGISTER_SUCCESFUL' => 'Thank you for registering.ً',
	'LOGOUT_SUCCESFUL' => 'Logged out successfully.',
	'LOGOUT_ERROR' => 'Logout Error!',
	'FILECP' => 'File Manager',
	'DEL_SELECTED' => 'Delete selected',
	'EDIT_U_FILES' => 'Update files',
	'FILES_UPDATED' => 'File updated successfully.',
	'PUBLIC_USER_FILES' => 'User files&#039; folder',
	'FILEUSER' => 'User files&#039; folder',
	'GO_FILECP' => 'Click here to manage these files',
	'YOUR_FILEUSER' => 'Your folder',
	'COPY_AND_GET_DUD' => 'Copy the URL and give it to your friends to be able to see your folder ',
	'CLOSED_FEATURE' => 'Closed feature',
	'USERFILE_CLOSED' => 'Users folders feature is closed !',
	'PFILE_4_FORUM' => 'Go to the forum to change your details',
	'USER_PLACE' => 'Users Area',
	'PROFILE' => 'Profile',
	'EDIT_U_DATA' => 'Update your details',
	'PASS_ON_CHANGE' => 'Password (Only if you want to change it).',
	'OLD' => 'Old',
	'NEW' => 'New',
	'NEW_AGAIN' => 'Confirm',
	'UPDATE' => 'Update',
	'PASS_O_PASS2' => 'The old password is required, and enter the new password carefully.',
	'DATA_CHANGED_O_LO' => 'Your details have been updated.',
	'DATA_CHANGED_NO' => 'No new details entered.',
	'LOST_PASS_FORUM' => 'Go to the forum to change your details ?',
	'GET_LOSTPASS' => 'Get your password',
	'E_GET_LOSTPASS' => 'Enter your email to receive your password.',
	'WRONG_DB_EMAIL' => 'The specified email address cannot be found in our database!',
	'GET_LOSTPASS_MSG' => 'We have sent you the new password.',
	'CANT_SEND_NEWPASS' => 'Error... the new password could not be sent!',
	'OK_SEND_NEWPASS' => 'We have sent you the new password',
	'GUIDE' => 'Allowed Extensions',
	'GUIDE_VISITORS' => 'Allowed extensions for guests',
	'GUIDE_USERS' => 'Allowed extensions for users:',
	'EXT' => 'Extension',
	'SIZE' => 'Size',
	'REPORT' => 'Report',
	'YOURNAME' => 'Your name',
	'URL' => 'Link',
	'REASON' => 'Reason',
	'NO_ID' => 'No file selected ..!!',
	'NO_ME300RES' => 'The Reason field cannot be more than 300 characters!!',
	'THNX_REPORTED' => 'We have received your report, Thank you.',
	'RULES' => 'Terms',
	'NO_RULES_NOW' => 'No terms have been specified currently.',
	'E_RULES' => 'Below are the terms of our service',
	'CALL' => 'Contact Us',
	'SEND' => 'Send',
	'TEXT' => 'Comments',
	'NO_ME300TEXT' => 'The Comments field cannot be more than 300 characters!!',
	'THNX_CALLED' => 'Sent ... you will get a reply from us as soon as possible.',
	'NO_DEL_F' => 'Sorry, file deletion URL feature is disabled by admin',
	'E_DEL_F' => 'File deletion URL',
	'WRONG_URL' => 'There is something wrong with the URL ..',
	'CANT_DEL_F' => 'Error: cannot delete the file .. It might be already deleted!',
	'CANT_DELETE_SQL' => 'Cannot be deleted from the database!',
	'DELETE_SUCCESFUL' => 'Deleted successfully.',
	'STATS' => 'Statistics',
	'STATS_CLOSED' => 'The statistics page is closed by the administrator.',
	'FILES_ST' => 'Uploaded',
	'FILE' => 'File',
	'USERS_ST' => 'Total Users',
	'USER' => 'user',
	'SIZES_ST' => 'Total size of uploaded files',
	'LSTFLE_ST' => 'Latest upload',
	'LSTDELST' => 'Last check for undownloaded files',
	'S_C_T' => 'Todays guests',
	'S_C_Y' => 'Yesterdays guests',
	'S_C_A' => 'Total number of guests',
	'LAST_1_H' => 'Statistics for the past hour',
	'DOWNLAOD' => 'Download',
	'FILE_FOUNDED' => 'File has been found .. ',
	'WAIT' => 'Please wait ..',
	'CLICK_DOWN' => 'Click here to download',
	'JS_MUST_ON' => 'Enable JavaScript in your browser!',
	'FILE_INFO' => 'File Info',
	'FILENAME' => 'File name',
	'FILESIZE' => 'File size',
	'FILETYPE' => 'File type',
	'FILEDATE' => 'File date',
	'FILEUPS' => 'Number of downloads',
	'FILEREPORT' => 'Report violation of terms',
	'FILE_NO_FOUNDED' => 'File cannot be found ..!!',
	'IMG_NO_FOUNDED' => 'Image cannot be found ..!!',
	'NOT_IMG' => 'This is not an image!!',
	'MORE_F_FILES' => 'This is the final limit for input fields',
	'DOWNLOAD_F' => '[ Upload Files ]',
	'DOWNLOAD_T' => '[ Download From Link ]',
	'PAST_URL_HERE' => '[ Paste Link Here ]',
	'SAME_FILE_EXIST' => 'File already exist',
	'NO_FILE_SELECTED' => 'Select a file first !!',
	'WRONG_F_NAME' => 'File name contains restricted characters.',
	'FORBID_EXT' => 'Extension not supported.',
	'SIZE_F_BIG' => 'File size must be smaller than',
	'CANT_CON_FTP' => 'Cannot connect to ',
	'URL_F_DEL' => 'Link for deleting the file',
	'URL_F_THMB' => 'Link for Thumbnail',
	'URL_F_FILE' => 'Link for file',
	'URL_F_IMG' => 'Link for Image',
	'URL_F_BBC' => 'Link for forums',
	'IMG_DOWNLAODED' => 'Image uploaded successfully.',
	'FILE_DOWNLAODED' => 'File uploaded successfully.',
	'CANT_UPLAOD' => 'Error: cannot upload file for UNKNOWN reason!',
	'NEW_DIR_CRT' => 'New folder created',
	'PR_DIR_CRT' => 'The folder has not been CHMODed',
	'CANT_DIR_CRT' => 'The folder has not been created automatically, you must create it manually.',
	'AGREE_RULES' => 'I Agree to the terms',
	'CHANG_TO_URL_FILE' => 'Change uploading method',
	'URL_CANT_GET' => 'error during get file from url..',
	'ADMINCP' => 'Administration Panel',
	'JUMPTO' => 'Navigate to',
	'GO_BACK_BROWSER' => 'Go back',
	'U_R_BANNED' => 'Your IP has been banned.',
	'U_R_FLOODER' => 'it&#039;s antiflood system ...',
	'U_NOT_ADMIN' => 'You do not have the administration permissions',
	'UPDATE_CONFIG' => 'Update Settings',
	'YES' => 'Yes',
	'NO' => 'No',
	'NO_CHANGE' => 'Do NOT change',
	'CHANGE_MD5' => 'Change using MD5',
	'CHANGE_TIME' => 'Change using TIME',
	'SITENAME' => 'Service name',
	'SITEMAIL' => 'Email address',
	'SITEURL' => 'Service URL with / at the end',
	'FOLDERNAME' => 'Folder name for uploaded files',
	'FILES_PREFIX' => 'Files prefix',
	'FILES_NUMB' => 'Number of upload input fields',
	'SITECLOSE' => 'Shutdown service',
	'CLOSE_MSG' => 'Shutdown message',
	'LANGUAGE' => 'Language',
	'FILENAME_CHNG' => 'Change file name',
	'STYLENAME' => 'Service style',
	'SC_BEFOR_DOWM' => 'Seconds before download',
	'SHOW_PHSTAT' => 'Page statistics in footer',
	'EN_GZIP' => 'use gzip',
	'WELC_MSG' => 'Welcome message',
	'USER_SYSTEM' => 'Users system',
	'NORMAL' => 'Normal',
	'W_PHPBB' => 'Attached to phpbb',
	'W_MYSBB' => 'Attached to MySmartBB',
	'W_VBB' => 'Attached to vb',
	'ENAB_REG' => 'Allow registraion',
	'MAX_SIZE_SITE' => 'Max service size[Mg]',
	'ENAB_THMB' => 'Enable image thumbnails',
	'ENAB_STAMP' => 'Enable image watermark',
	'ENAB_DELURL' => 'Enable file deletion URL feature',
	'WWW_URL' => 'Enable uploading from URL',
	'ALLOW_STAT_PG' => 'Enable statistics page',
	'ALLOW_ONLINE' => 'Enable Who is Online',
	'MOD_WRITER' => 'Mod Rewrite',
	'MOD_WRITER_EX' => 'HTML links..',
	'DEL_FDAY' => 'Delete undownloaded files in',
	'NUMFIELD_S' => 'You can only use numbers with some fields !!',
	'CONFIGS_UPDATED' => 'Settings updated successfully.',
	'UPDATE_EXTS' => 'Update Extensions',
	'GROUP' => 'Category',
	'SIZE_G' => 'Size G',
	'SIZE_U' => 'Size U',
	'ALLOW_G' => 'Allow G',
	'ALLOW_U' => 'Allow U',
	'E_EXTS' => '<b>G</b> for guests. <br /> <b>U</b> for users<br /> Sizes are measured in bytes.',
	'UPDATED_EXTS' => 'Extensions updated successfully.',
	'UPDATE_FILES' => 'Update Files',
	'BY' => 'By',
	'FILDER' => 'Folder',
	'DELETE' => 'Delete',
	'GUST' => 'Guest',
	'UPDATE_REPORTS' => 'Update Reports',
	'NAME' => 'Name',
	'CLICKHERE' => 'Click Here',
	'TIME' => 'Time',
	'E_CLICK' => 'Select one to be viewed here',
	'IP' => 'IP',
	'REPLY' => '[ Reply ]',
	'REPLY_REPORT' => 'Reply on the report',
	'U_REPORT_ON' => 'For your report about ',
	'BY_EMAIL' => 'By email ',
	'ADMIN_REPLIED' => 'Admin Reply',
	'CANT_SEND_MAIL' => 'cannot send reply via email',
	'IS_SEND_MAIL' => 'Reply has been sent.',
	'REPORTS_UPDATED' => 'Reports have been updated.',
	'UPDATE_CALSS' => 'Update Comments',
	'REPLY_CALL' => 'Reply on the comment',
	'REPLIED_ON_CAL' => 'About your comment ',
	'CALLS_UPDATED' => 'Comments updated successfully.',
	'IS_ADMIN' => 'Admin',
	'UPDATE_USERS' => 'Update Users',
	'USERS_UPDATED' => 'Users updated successfully.',
	'E_BACKUP' => 'Select the tables you want to make a backup for:',
	'TAKE_BK' => 'Back-Up',
	'REPAIRE_TABLE' => '[Tables] Repaired. ',
	'REPAIRE_F_STAT' => '[stats] total number of files has been recounted.',
	'REPAIRE_S_STAT' => '[stats] total size of files has been recounted.',
	'REPAIRE_CACHE' => '[Cache] deleted for  ..',
	'KLEEJA_CP' => '[ Kleeja ] Administration',
	'GENERAL_STAT' => 'General Stats',
	'SIZE_STAT' => 'Size Stats',
	'OTHER_INFO' => 'Other Info',
	'AFILES_NUM' => 'Total number of files',
	'AFILES_SIZE' => 'Total size of files',
	'AUSERS_NUM' => 'Total users',
	'LAST_GOOGLE' => 'Last visit to Google',
	'GOOGLE_NUM' => 'Google visits',
	'LAST_YAHOO' => 'Last visit to Yahoo!',
	'YAHOO_NUM' => 'Yahoo! visits',
	'KLEEJA_CP_W' => 'Welcome to <b>Kleeja</b> administration panel',
	'USING_SIZE' => 'Disk Usage',
	'PHP_VER' => 'php version',
	'MYSQL_VER' => 'mysql version',
	'N_IMGS' => 'Images',
	'N_ZIPS' => 'ZIP Files',
	'N_TXTS' => 'TXT Files',
	'N_DOCS' => 'DOCS',
	'N_RM' => 'RealMedia',
	'N_WM' => 'WindowsMedia',
	'N_SWF' => 'Flash Files',
	'N_QT' => 'QuickTime',
	'N_OTHERFILE' => 'Other Files',
	'LOGOUT_CP_OK' => 'Your administration session has been cleared ..',
	'RETURN_HOME' => '&lt;&lt;  Back to Home',
	'R_CONFIGS' => 'General Settings',
	'R_CPINDEX' => 'Main Admin Page',
	'R_EXTS' => 'Extensions Settings',
	'R_FILES' => 'Files Control',
	'R_REPORTS' => 'Reports Control',
	'R_CALLS' => 'Messages Control',
	'R_USERS' => 'Users Control',
	'R_BCKUP' => 'Back-Up',
	'R_REPAIR' => 'Total Repair',
	'R_LGOUTCP' => 'Clear Session',
	'R_BAN' => 'Ban Control',
	'BAN_EXP1' => 'Edit the banned IPs and add new ones here ..',
	'BAN_EXP2' => 'Use the star (*) symbol to replace numbers if you want a total ban.... and use the (|) to separate the IPs',
	'UPDATE_BAN' => 'Save Changes',
	'BAN_UPDATED' => 'Changes saved successfully.',
	'R_RULES' => 'Terms and conditions control',
	'RULES_EXP' => 'You can edit the terms and conditions of your service from here',
	'UPDATE_RULES' => 'Update',
	'RULES_UPDATED' => 'Terms and conditions updated successfully ..',
	'R_SEARCH' => 'Advanced search',
	'SEARCH_FILES' => 'Search for files',
	'SEARCH_SUBMIT' => 'Search now',
	'LAST_DOWN' => 'Last download',
	'TODAY' => 'Today',
	'DAYS' => 'Days',
	'WAS_B4' => 'Was before',
	'BITE' => 'byte',
	'SEARCH_USERS' => 'Search for users',
	'R_IMG_CTRL' => 'Image control only',
	'ENABLE_USER_FILE' => 'Enable users files',
	'R_EXTRA' => 'Extra header and footer',
	'EX_HEADER_N' => 'Extra header ... which shows at the bottom of the original header',
	'EX_FOOTER_N' => 'Extra footer ... which shows at the top of the original footer',
	'UPDATE_EXTRA' => 'Update template additions',
	'EXTRA_UPDATED' => 'Template additions updated successfully',
	'R_STYLES' => 'Styles',
	'STYLES_EXP' => 'Select a style from below to delete or update it',
	'SHOW_TPLS' => 'Show Templates',
	'SUBMIT' => 'Submit',
	'EDIT' => 'Edit',
	'TPL_UPDATED' => 'Template updated...',
	'TPL_DELETED' => 'Template deleted ...',
	'NO_TPL_SHOOSED' => 'You did not select a template!',
	'NO_TPL_NAME_WROTE' => 'Please enter the name of the template!',
	'ADD_NEW_STYLE' => 'Create a new style',
	'EXPORT_AS_XML' => 'Export As xml',
	'NEW_STYLES_EXP' => 'Upload style from a XML file',
	'NEW_STYLE_ADDED' => 'Style added successfully',
	'ERR_IN_UPLOAD_XML_FILE' => 'Error uploading...',
	'ERR_UPLOAD_XML_FILE_NO_TMP' => 'Error uploading...',
	'ERR_UPLOAD_XML_NO_CONTENT' => 'The file selected is blank!',
	'ERR_XML_NO_G_TAGS' => 'Some required tags are missing from the file!',
	'STYLE_DELETED' => 'Style successfully removed',
	'STYLE_1_NOT_FOR_DEL' => 'You cannot delete the default style!',
	'ADD_NEW_TPL' => 'Add a new template',
	'ADD_NEW_TPL_EXP' => 'Enter a name for the new template',
	'TPL_CREATED' => 'New template successfully created...',
	'R_LANGS' => 'words and phrases',
	'LANGS_EXP' => 'To delete or update a language, select it from below',
	'SHOW_WORDS' => 'Show words and phrases',
	'ADD_NEW_LANG' => 'Add new language',
	'NEW_LANG_EXP' => 'Upload a new or an altered language',
	'SHOW_WORDS_EXP' => 'Show the language variables and it\'s translations or update or delete them....',
	'ADD_NEW_WORD' => 'Add a new language var.',
	'ADD_NEW_WORD_EXP' => 'Add a new language var. and it\'s translation',
	'LANG_DELETED' => 'Language deleted...',
	'LANG_1_NOT_FOR_DEL' => 'You cannot delete the default language',
	'NEW_LANG_ADDED' => 'New language added...',
	'NO_WORD_SHOOSED' => 'You did not select a word !',
	'WORD_DELETED' => 'Word successfully deleted...',
	'WORD_UPDATED' => 'Word successfully updated...',
	'WORD_CREATED' => 'Word successfully added...',
	'PLUGINS' => 'Plugins',
	'PLUGINS_EX' => 'Delete or updated plugins here...',
	'DISABLE' => 'Disable',
	'ENABLE' => 'Enable',
	'ADD_NEW_PLUGIN' => 'Add plugin',
	'ADD_NEW_PLUGIN_EXP' => 'Upload plugin from a XML file',
	'PLUGIN_DELETED' => 'Plugin deleted...',
	'PLGUIN_DISABLED_ENABLED' => 'Plugin Enabled / Disabled',
	'NO_PLUGINS' => 'No plugins found',
	'NEW_PLUGIN_ADDED' => 'Plugin added ...',
	'R_CHECK_UPDATE' => 'Check for updates',
	'ERROR_CHECK_VER' => 'Error: cannot get any inforamtiom about updates at this momoent , try again later !',
	'UPDATE_KLJ_NOW' => 'You Have to update your version now!. Go to kleeja website for more inforamtion',
	'U_LAST_VER_KLJ' => 'You are using last version of Kleeja...',
	'S_TRANSLATED_BY' => 'Translated By <a href="http://www.fenix4web.com/">Fenix4Web</a>',
));

?>
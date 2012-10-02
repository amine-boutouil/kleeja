<?php
//
// kleeja language file
// English
// By: Kleeja team & (NK, n.k@cityofangelz.com)
//

if (!defined('IN_COMMON'))
	exit;

if (empty($lang) || !is_array($lang))
	$lang = array();



$lang = array_merge($lang, array(
	//language inforamtion
	'DIR' 					=> 'ltr',
	'LANG_SMALL_NAME'		=> 'en-us',

	'HOME' 					=> 'Home',
	'INDEX' 				=> 'Index',
	'SITE_CLOSED' 			=> 'The website is closed.',
	'STOP_FOR_SIZE' 		=> 'The service is suspended.',
	'SIZES_EXCCEDED' 		=> 'We have ran out of space ... we will be back soon.',
	'ENTER_CODE_IMG' 		=> 'Enter verification code.',
	'SAFE_CODE' 			=> 'Enable safety code for downloads',
	'LAST_VISIT' 			=> 'Last visit',
	'FLS_LST_VST_SEARCH' 	=> 'Show files since',
	'IMG_LST_VST_SEARCH' 	=> 'Show images since',
	'NEXT' 					=> 'Next &raquo;',
	'PREV' 					=> '&laquo; Previous',
	'INFORMATION' 			=> 'Instructions',
	'WELCOME' 				=> 'Welcome',
	'KLEEJA_VERSION' 		=> 'Kleeja version',
	'NUMBER_ONLINE' 		=> 'registred online users',
	'NUMBER_UONLINE' 		=> 'users',
	'NUMBER_VONLINE' 		=> 'guests',
	'USERS_SYSTEM' 			=> 'Users System',
	'ERROR_NAVIGATATION' 	=> 'Redirection Error ..',
	'LOGIN' 				=> 'Login',
	'USERNAME' 				=> 'User name',
	'PASSWORD' 				=> 'Password',
	'EMPTY_USERNAME'		=> 'Please enter your username',
	'EMPTY_PASSWORD' 		=> 'Please enter your password',
	'LOSS_PASSWORD' 		=> 'Forgot Password?',
	'LOGINED_BEFORE' 		=> 'You are already logged in.',
	'LOGOUT' 				=> 'Logout ',
	'EMPTY_FIELDS' 			=> 'Error ... Missing Fields!',
	'LOGIN_SUCCESFUL' 		=> 'You have logged in successfully.',
	'LOGIN_ERROR' 			=> 'Error ... cannot login!',
	'REGISTER_CLOSED' 		=> 'Sorry, the registration is currently closed.',
	'PLACE_NO_YOU' 			=> 'Restricted Area',
	'REGISTERED_BEFORE' 	=> 'already',
	'REGISTER' 				=> 'Register',
	'EMAIL' 				=> 'Email address',
	'VERTY_CODE' 			=> 'Security code',
	'WRONG_EMAIL' 			=> 'Incorrect email address!',
	'WRONG_NAME' 			=> 'The username must be longer than 4 characters!',
	'WRONG_LINK' 			=> 'Incorrect link ..',
	'EXIST_NAME' 			=> 'Someone has already registered with this username!',
	'EXIST_EMAIL' 			=> 'Someone with this email address has already registered!',
	'WRONG_VERTY_CODE' 		=> 'Incorrect security code!',
	'CANT_UPDATE_SQL' 		=> 'cant update database!',
	'CANT_INSERT_SQL' 		=> 'cant insert data to the database!',
	'REGISTER_SUCCESFUL' 	=> 'Thank you for registering.ً',
	'LOGOUT_SUCCESFUL' 		=> 'Logged out successfully.',
	'LOGOUT_ERROR' 			=> 'Logout Error!',
	'FILECP' 				=> 'File Manager',
	'DEL_SELECTED' 			=> 'Delete selected',
	'EDIT_U_FILES' 			=> 'Update files',
	'FILES_UPDATED' 		=> 'File updated successfully.',
	'PUBLIC_USER_FILES' 	=> 'User files&#039; folder',
	'FILEUSER' 				=> 'User files&#039; folder',
	'GO_FILECP' 			=> 'Click here to manage these files',
	'YOUR_FILEUSER' 		=> 'Your folder',
	'COPY_AND_GET_DUD' 		=> 'Copy URL and give it to your friends To see your files ',
	'CLOSED_FEATURE' 		=> 'Closed feature',
	'USERFILE_CLOSED' 		=> 'Users folders feature is closed !',
	'PFILE_4_FORUM' 		=> 'Go to the users cp to change your details',
	'USER_PLACE' 			=> 'Users Area',
	'PROFILE' 				=> 'Profile',
	'EDIT_U_DATA' 			=> 'Update your details',
	'PASS_ON_CHANGE' 		=> 'Edit Password',
	'OLD' 					=> 'Old',
	'NEW' 					=> 'New',
	'NEW_AGAIN' 			=> 'Confirm',
	'UPDATE' 				=> 'Update',
	'PASS_O_PASS2' 			=> 'The old password is required, and enter the new password carefully.',
	'DATA_CHANGED_O_LO' 	=> 'Your details have been updated.',
	'DATA_CHANGED_NO' 		=> 'No new details entered.',
	'LOST_PASS_FORUM' 		=> 'Go to the forum to change your details ?',
	'GET_LOSTPASS' 			=> 'Get your password',
	'E_GET_LOSTPASS' 		=> 'Enter your email to receive your password.',
	'WRONG_DB_EMAIL' 		=> 'The specified email address cannot be found in our database!',
	'GET_LOSTPASS_MSG' 		=> "You have asked for your password to be reset but, to avoid spam click on the link below for confirmation : \r\n %1\$s \r\n New Password : %2\$s",
	'CANT_SEND_NEWPASS' 	=> 'Error... the new password could not be sent!',
	'OK_SEND_NEWPASS' 		=> 'We have sent you the new password',
	'OK_APPLY_NEWPASS' 		=> 'New password set. you can now login to your account.',
	'GUIDE' 				=> 'Allowed Extensions',
	'GUIDE_EXP' 			=> 'Allowed extensions & Sizes',
	'EXT' 					=> 'Extension',
	'SIZE' 					=> 'Size',
	'REPORT' 				=> 'Report',
	'YOURNAME' 				=> 'Your name',
	'URL' 					=> 'Link',
	'REASON' 				=> 'Reason',
	'NO_ID' 				=> 'No file selected ..!!',
	'NO_ME300RES' 			=> 'The Reason field cannot be more than 300 characters!!',
	'THNX_REPORTED' 		=> 'We have received your report, Thank you.',
	'RULES' 				=> 'Terms',
	'NO_RULES_NOW' 			=> 'No terms have been specified currently.',
	'E_RULES' 				=> 'Below are the terms of our service',
	'CALL' 					=> 'Contact Us',
	'SEND' 					=> 'Send',
	'TEXT' 					=> 'Comments',
	'NO_ME300TEXT' 			=> 'The Comments field cannot be more than 300 characters!!',
	'THNX_CALLED' 			=> 'Sent ... you will get a reply from us as soon as possible.',
	'NO_DEL_F' 				=> 'Sorry, file deletion URL feature is disabled by admin',
	'E_DEL_F' 				=> 'File deletion URL',
	'WRONG_URL' 			=> 'There is something wrong with the URL ..',
	'CANT_DEL_F' 			=> 'Error: cannot delete the file .. It might be already deleted!',
	'CANT_DELETE_SQL' 		=> 'Cannot be deleted from the database!',
	'DELETE_SUCCESFUL' 		=> 'Deleted successfully.',
	'STATS' 				=> 'Statistics',
	'STATS_CLOSED' 			=> 'The statistics page is closed by the administrator.',
	'FILES_ST' 				=> 'Uploaded',
	'FILE' 					=> 'File',
	'IMAGE' 				=> 'Image',
	'USERS_ST' 				=> 'Total Users',
	'USER' 					=> 'user',
	'SIZES_ST' 				=> 'Total size of uploaded files',
	'LSTFLE_ST' 			=> 'Latest upload',
	'LSTDELST' 				=> 'Last check for undownloaded files',
	'S_C_T' 				=> 'Todays guests',
	'S_C_Y' 				=> 'Yesterdays guests',
	'S_C_A' 				=> 'Total number of guests',
	'LAST_1_H' 				=> 'Statistics for the past hour',
	'DOWNLAOD' 				=> 'Download',
	'FILE_FOUNDED' 			=> 'File has been found .. ',
	'WAIT' 					=> 'Please wait ..',
	'CLICK_DOWN' 			=> 'Click here to download',
	'JS_MUST_ON' 			=> 'Enable JavaScript in your browser!',
	'FILE_INFO' 			=> 'File Info',
	'FILENAME' 				=> 'File name',
	'FILESIZE' 				=> 'File size',
	'FILETYPE' 				=> 'File type',
	'FILEDATE' 				=> 'File date',
	'LAST_DOWN' 			=> 'Last download',
	'FILEUPS' 				=> 'Number of downloads',
	'FILEREPORT' 			=> 'Report violation of terms',
	'FILE_NO_FOUNDED' 		=> 'File cannot be found ..!!',
	'IMG_NO_FOUNDED' 		=> 'Image cannot be found ..!!',
	'NOT_IMG' 				=> 'This is not an image!!',
	'MORE_F_FILES' 			=> 'This is the final limit for input fields',
	'DOWNLOAD_F' 			=> '[ Upload Files ]',
	'DOWNLOAD_T' 			=> '[ Download From Link ]',
	'PAST_URL_HERE' 		=> '[ Paste Link Here ]',
	'SAME_FILE_EXIST' 		=> 'File "%s" already exist, Rename it and try again.',
	'NO_FILE_SELECTED' 		=> 'Select a file first !!',
	'WRONG_F_NAME' 			=> 'File name "%s" contains restricted characters.',
	'FORBID_EXT' 			=> 'Extension "%s" not supported.',
	'SIZE_F_BIG' 			=> 'File size of "%1$s" must be smaller than %2$s .',
	'CANT_CON_FTP' 			=> 'Cannot connect to ',
	'URL_F_DEL' 			=> 'Link Deleting The File',
	'URL_F_THMB' 			=> 'Thumbnail Link',
	'URL_F_FILE' 			=> 'File Link',
	'URL_F_IMG' 			=> 'Image Link',
	'URL_F_BBC' 			=> 'Forums Link',
	'IMG_DOWNLAODED' 		=> 'Image uploaded successfully.',
	'FILE_DOWNLAODED' 		=> 'File uploaded successfully.',
	'CANT_UPLAOD' 			=> 'Error: cannot upload file "%s" for UNKNOWN reason!',
	'NEW_DIR_CRT' 			=> 'New folder created',
	'PR_DIR_CRT' 			=> 'The folder has not been CHMODed',
	'CANT_DIR_CRT' 			=> 'The folder has not been created automatically, you must create it manually.',
	'AGREE_RULES' 			=> 'By clicking on the button below, you agree to %1$sthe terms%2$s.',
	'CHANG_TO_URL_FILE' 	=> 'Change uploading method',
	'URL_CANT_GET' 			=> 'error during get file from url..',
	'ADMINCP' 				=> 'Control Panel',
	'JUMPTO' 				=> 'Navigate to',
	'GO_BACK_BROWSER' 		=> 'Go back',
	'U_R_BANNED' 			=> 'Your IP has been banned.',
	'U_R_FLOODER' 			=> 'it&#039;s antiflood system ...',
	'YES' 					=> 'Yes',
	'NO' 					=> 'No',
	'LANGUAGE' 				=> 'Language',
	'STYLE' 				=> 'Style',
	'NORMAL' 				=> 'Normal',
	'W_PHPBB' 				=> 'Attached to phpbb',
	'W_MYSBB' 				=> 'Attached to MySmartBB',
	'W_VBB' 				=> 'Attached to vb',
	'GROUP' 				=> 'Category',
	'UPDATE_FILES' 			=> 'Update Files',
	'BY' 					=> 'By',
	'FILDER' 				=> 'Folder',
	'DELETE' 				=> 'Delete',
	'GUST' 					=> 'Guest',
	'NAME' 					=> 'Name',
	'CLICKHERE' 			=> 'Click Here',
	'TIME' 					=> 'Time',
	'IP' 					=> 'IP',
	'N_IMGS' 				=> 'Images',
	'N_ZIPS' 				=> 'ZIP Files',
	'N_TXTS' 				=> 'TXT Files',
	'N_DOCS' 				=> 'DOCS',
	'N_RM' 					=> 'RealMedia',
	'N_WM' 					=> 'WindowsMedia',
	'N_SWF' 				=> 'Flash Files',
	'N_QT' 					=> 'QuickTime',
	'N_OTHERFILE' 			=> 'Other Files',
	'RETURN_HOME' 			=> 'Return to home',
	'TODAY' 				=> 'Today',
	'DAYS' 					=> 'Days',
	'BITE' 					=> 'byte',
	'SUBMIT' 				=> 'Submit',
	'EDIT' 					=> 'Edit',
	'DISABLE' 					=> 'Disable',
	'ENABLE' 					=> 'Enable',	
	'OPEN'						=> 'Open',
	'KILOBYTE'					=>	'Kilobyte',
	'NOTE'						=>	'Note',
	'WARN'						=>	'Warning',
	'ARE_YOU_SURE_DO_THIS'		=> 'Are you sure you want to do this?',
	'SITE_FOR_MEMBER_ONLY'		=> 'This center is only for members, register or login to upload your files.',
	'AUTH_INTEGRATION_N_UTF8_T'	=> '%s is not utf8',
	'AUTH_INTEGRATION_N_UTF8' 	=> '%s database must be utf8 to be integrated with Kleeja !.',
	'SCRIPT_AUTH_PATH_WRONG'	=> 'Path of %s is not valid, change it now.',
	'SHOW_MY_FILECP'			=> 'Show my files',
	'PASS_CHANGE'				=> 'Change password',
	'MOST_EVER_ONLINE'			=> 'Most registered users ever online was',
	'ON'						=> 'on',
	'LAST_REG'					=> 'newest member',
	'NEW_USER'					=> 'New user',
	'LIVEXTS'					=> 'Live extensions',
	'ADD_UPLAD_A'				=> 'Add more fields',
	'ADD_UPLAD_B'				=> 'Remove fields',
	'COPYRIGHTS_X'				=> 'All rights reserved',
	'CHECK_ALL'	 				=> 'Check all',
	'BROSWERF'					=> 'User files',
	'REMME'						=> 'Remmeber me',
	'REMME_EXP'					=> 'Check this if your device isn\'t shared with others',
	'HOUR'						=> 'an hour',
	'5HOURS'					=> '5 hours',
	'DAY'						=> 'a day',
	'WEEK'						=> 'a week',
	'MONTH'						=> 'a month',
	'YEAR'						=> 'a year',
	'INVALID_FORM_KEY'			=> 'Invalid form, or your session was expired',
	'INVALID_GET_KEY'			=> 'Sorry, The requested link is expired, and is blocked for secuirty reason, go back and try again.',
	'REFRESH_CAPTCHA'			=> 'Click to get a new CAPTCHA image',
	'CHOSE_F'					=> 'Please select at least one file',
	'NO_REPEATING_UPLOADING'	=> 'The page should NOT be refreshed after upload!.',
	'NOTE_CODE' 				=> 'Enter the letters shown in the image accurately',
	'USER_LOGIN'				=> ' Login + Members Only ',
	'FILES_DELETED' 			=> 'Files successfully deleted.',
	'GUIDE_GROUPS' 			    => 'Group',
	'ALL_FILES' 			    => 'Number of all files',
	'ALL_IMAGES' 			    => 'Number of all images',
	'NO_FILE_USER'				=> 'No files were found in the account!',
	'SHOWFILESBYIP'				=> 'Show files by IP', 
	'WAIT_LOADING'				=> 'Please wait, the files are being uploaded to the server...',
	'NOTICECLOSED'				=> 'Notice: website closed',
	'UNKNOWN'					=> 'Unknown',
	'WE_UPDATING_KLEEJA_NOW'	=> 'Closed for maintenance, Check back soon...',
	'ERROR_TRY_AGAIN'			=> 'Error, try again.',
	'VIEW'						=> 'View',
	'NONE'						=> 'None',
	'USER_STAT'					=> 'User Stats',
	'SEARCH_STAT' 				=> 'Search Bots Stats',
	'NOTHING'					=> 'There are no new files or photos .. !!',
	'YOU_HAVE_TO_WAIT'			=> 'Wait %s seconds .. then try to re-upload your files',
	'REPEAT_PASS'				=> 'Repeat Password',
	'PASS_NEQ_PASS2'			=> 'Passwords are not equal !',
	'LOAD_IS_HIGH_NOW'			=> 'Our website facing very high load right now !, please wait and try refresh this page again.',
	#1.5
	'GROUP'						=> 'Group',
	'ADMINS'					=> 'Admins',
	'GUESTS'					=> 'Guests',
	'USERS'						=> 'Users',
	'DELETE_INSTALL_FOLDER'		=> 'To start using Kleeja, delete "install" folder, Kleeja will never work while this folder exists.',
	'HV_NOT_PRVLG_ACCESS'		=> 'You don\'t have privilege to access this page.',
	'W_PERIODS' 		=> array("a second", "a minute", "an hour", "a day", "a week", "a month", "a year", "a decade"),
	'W_PERIODS2'		=> array("two seconds", "two minutes", "two hours", "two days", "two weeks", "two months", "two years", "two decades"),
	'W_PERIODS_P'		=> array("seconds", "minutes", "hours", "days", "weeks", "months", "years", "decades"),
	'W_FROM' 			=> 'from',
	'W_AGO' 			=> 'ago',
	'W_FROM NOW'		=> 'from now',
	'TIME_PM'			=> 'pm',
	'TIME_AM'			=> 'am',
	'NOT_YET'			=> 'Not yet!',
	'NOT_FOUND'			=> 'This file is not exist. either deleted by the user or the administrator or there is an error opening the file!.',
	'TIME_ZONE'			=> 'Time zone',
	'OR'				=> 'or',
	'AND'				=> 'and',
	'CHANGE'			=> 'Change',
	'FOR'				=> 'for',
	'ALL'				=> 'All',
	'NOW'				=> 'Now',

	//last line of this file ...					 
	'S_TRANSLATED_BY' 			=> 'Translated By <a href="http://www.fenix4web.com/" target="_blank">Fenix4Web</a> &amp; Kleeja Team',
	
));

#<-- EOF

<?php
//English language
//  By:NK , Email: n.k@cityofangelz.com 


	if(!isset($lang) || !is_array($lang)) $lang = array();
	
	$lang['INST_INSTALL_CLEAN_VER']					= 'New Installation';
	$lang['INST_UPDATE_P_VER']						= 'Update ';
	$lang['INST_CHOOSE_INSTALLER']					= 'Choose what suits you best from the setup wizard';
	$lang['INST_AGR_GPL2']							= 'I Agree';
	$lang['INST_SUBMIT']							= '[  Continue  ]';
	$lang['INST_SITE_INFO']							= 'Site Info';
	$lang['INST_ADMIN_INFO']						= 'Admin Info';
	$lang['INST_CHANG_CONFIG']						= 'Missing requirements ... make sure you have edited the config.php file.';
	$lang['INST_CONNCET_ERR']						= 'Cannot connect ..';
	$lang['INST_SELECT_ERR']						= 'Cannot select database';
	$lang['INST_NO_WRTABLE']						= 'The directory is not writeable';
	$lang['INST_GOOD_GO']							= 'Everything seems to be OK .... continue';
	$lang['INST_MSGINS']							= 'Welcome to our uploading service, here you can upload anything as long as it does not violate our terms.';
	$lang['INST_CRT_CALL']							= 'Comments table created.';
	$lang['INST_CRT_ONL']							= 'Online users table created.';
	$lang['INST_CRT_REPRS']							= 'Reports table created.';
	$lang['INST_CRT_STS']							= 'Statistics table created.';
	$lang['INST_CRT_USRS']							= 'Users table created.';
	$lang['INST_CRT_ADM']							= 'Admin details created.';
	$lang['INST_CRT_FLS']							= 'Files table created.';
	$lang['INST_CRT_CNF']							= 'Settings table created.';
	$lang['INST_CRT_EXT']							= 'Extensions table created.';
	$lang['INST_CRT_HKS']							= 'Hacks table created';
	$lang['INST_CRT_LNG']							= 'Language table created';
	$lang['INST_CRT_LSTS']							= 'Lists table created';
	$lang['INST_CRT_PLG']							= 'Plugins table created';
	$lang['INST_CRT_TPL']							= 'Templates table created';
	$lang['INST_SQL_OK']							= 'SQL Executed Successfully ..';
	$lang['INST_SQL_ERR']							= 'Error Executing SQL .. ';
	$lang['INST_FINISH_SQL']						= 'Kleeja was installed successfully, Please remove the INSTALL directory';
	$lang['INST_FINISH_ERRSQL']						= 'Oops! there seems to be a problem, try again.';
	$lang['INST_KLEEJADEVELOPERS']					= 'Thank you for using Kleeja and good luck from our development team.';
	$lang['SITENAME']								= 'Website title';
	$lang['SITEURL']								= 'Website URL';
	$lang['SITEMAIL']								= 'Website Email';
	$lang['USERNAME']								= 'Username';
	$lang['PASSWORD']								= 'Password';
	$lang['EMAIL']									= 'Email';
	$lang['INDEX']									= 'Home';
	$lang['ADMINCP']								= 'Control Panel';
	$lang['DIR']									= 'rtl';
	$lang['EMPTY_FIELDS']							= 'Some important fields were left blank!';
	$lang['WRONG_EMAIL']							= 'Incorrect Email Address!';
	//
	
	$lang['DB_INFO_NW']								= 'Config.php file is missing, fill in the fields below and kleeja will create the file for you, place it in kleeja\'s directory.';
	$lang['DB_INFO']								= 'Config.php file is missing, fill in the fields below and kleeja will create the file for you.';
	$lang['DB_SERVER']								= 'Host';
	$lang['DB_USER']								= 'Database Username';
	$lang['DB_PASSWORD']							= 'Database Password';
	$lang['DB_NAME']								= 'Database Name';
	$lang['DB_PREFIX']								= 'Database prefix <br />[a word you provide that is added to the beginning of all tables for this branch installation...]';
	$lang['VALIDATING_FORM_WRONG']					= 'A required field was left blank!';
	$lang['CONFIG_EXISTS']							= 'Config.php was found, Continue...';
	$lang['INST_SUBMIT_CONFIGOK']					= 'Upload the file in the main directory';
	$lang['INST_EXPORT']							= 'Export File';
	//
	$lang['FUNCTIONS_CHECK']						= 'Functions Check';
	$lang['RE_CHECK']								= 'ReCheck';
	$lang['FUNCTION_IS_NOT_EXISTS']					= 'The function %s is disabled.';
	$lang['FUNCTION_IS_EXISTS']						= 'The function %s is enabled.';
	$lang['FUNCTION_DISC_UNLINK']					= 'The function Unlink is used to remove and update cache files.';
	$lang['FUNCTION_DISC_GD']						= 'The function imagecreatetruecolor is a GD library that is used to create thumbnails & control photos.';
	$lang['FUNCTION_DISC_FOPEN']					= 'The function fopen is used to control styles & files in kleeja.';
	$lang['FUNCTION_DISC_MUF']						= 'The function move_uploaded_file is used to upload files and it\'s the most important function in the script.';
	
	
	//UPDATOR
	$lang['INST_CHOOSE_UPDATE_FILE']				= 'Choose the appropriate update file';
	$lang['INST_ERR_NO_SELECTED_UPFILE_GOOD']		= 'Inappropriate update file, or it is missing!';
	
	$lang['INST_NOTES_UPDATE']						= 'Update Notes';
	$lang['INST_NOTE_RC2_TO_RC3']					= 'You need to replace all the script files and not just run this wizard  ...';
	$lang['INST_NOTE_RC4_TO_RC5']					= 'You need to replace all new the script files with the old ones !.';
	$lang['INST_NOTE_RC5_TO_RC6']					= 'You need to replace all new the script files with the old ones !.';
	
	$lang['INST_UPDATE_IS_FINISH']					= 'Installation completed! you can now delete the <br /><b>INSTALL</b><br /> directory...';
	$lang['IN_INFO']								= 'Fill in the fields below if you want to integrate kleeja with your script . Ignore this step if you do not wish to do it<br /><span style="color:red;">you should change user system from admin cp after installing kleeja</span>';
	$lang['IN_PATH']								= 'Path of script';
	$lang['IN_CHARSET']								= 'Charset of script';
	
	
?>
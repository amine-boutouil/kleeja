<?php
//
// kleeja language, admin
// Arabic
//

if (!defined('IN_COMMON'))
	exit;

if (empty($lang) || !is_array($lang))
	$lang = array();


$lang = array_merge($lang, array(

	# ?
	'LOADING'					=> 'Loading',
	'ERROR_AJAX'				=> 'There is an error, try again!.',
	'MORE'						=> 'More',
	'MENU'						=> 'Menu',
	'WELCOME'					=> 'Welcome',
	'ENABLE_CAPTCHA'			=> 'Enable Captcha in Kleeja',
	'NO_THUMB_FOLDER'			=> 'It seems you enabled Thumbs but in same time the folder %s does not exist! create it.',
	'DELETE_EARLIER_30DAYS'		=> 'Delete files older than 30 days',
	'DELETE_ALL'				=> 'Delete all',
	'DELETE_PROCESS_QUEUED'		=> 'The delete process has been added to the waiting list to execute it gradually to reduce the load.',
	'DELETE_PROCESS_IN_WORK'	=> 'Currently, the delete process is executing ...',
	'SHOW_FROM_24H'				=> 'Show past 24 hours',
	'FIRST_TIME_CP'				=> 'First time in Kleeja CP ?!', 
	'FIRST_TIME_CP_EX'			=> 'Since 2007, Kleeja development team working on study of how Kleeja users are using Kleeja control panel' . 
									' and after many developement versions we figured out a new ideas that you will figure them out by using' . 
									' Kleeja later, and the main ideas you will notice are the AJAX and the new powerful menus. ' . 
									' <br /><br /> Below is the explanation of Kleeja CP  and how to use it /',  
	'FIRST_TIME_HOW1'			=> 'Main Kleeja menu, from here you can reach images control or files control page etc ..',
	'FIRST_TIME_HOW2'			=> 'CP home button, move you to main cp page.',
	'FIRST_TIME_HOW3'			=> 'Website button, move you to the main upload center page.',
	'FIRST_TIME_HOW4'			=> 'CP logout button, destroy your CP session and let you become regular user.',
	'FIRST_TIME_HOW5'			=> 'Settings button, from here you can change Kleeja settings, all of them.',
	'FIRST_TIME_HOW6'			=> 'GO menu, it\'s submenu from the main menu, shows links from other sections.',
	'FIRST_TIME_HOW7'			=> 'Here where the contents appears, clean to understand.',
	'FIRST_TIME_DONE'			=> 'I got it, thank you',
	'THUMB_DIS_LONGTIME'		=> 'Thumbs are disabled, this will force Kleeja to resize every images to be small here, and cost you time and bandwidth!. Enable thumbs now.', 
));

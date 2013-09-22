<?php
	//auto update wizard
	//part of admin extensions [beta !]
	//helpls kleeja to be up to date!
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author: $ , $Rev: $,  $Date:: $
	
	// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}



//update in 5 steps so we can reduce the load and knows errors when they occurs 


	$v = @unserialize($config['new_version']);

	if(!version_compare(strtolower(KLEEJA_VERSION), strtolower($v['version_number']), '<'))
	{
		//kleeja_admin_err($lang['U_LAST_VER_KLJ']);
	}
	


	#security vars
	$H_FORM_KEYS	= kleeja_add_form_key('adm_aupdate');
	$GET_FORM_KEY	= kleeja_add_form_key_get('adm_aupdate');


	$current_step = isset($_GET['astep']) ? (preg_match('![a-z0-9_]!i', trim($_GET['astep'])) ? trim($_GET['astep']) : 'general') : 'general';

	$action		  = basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;astep=' . $current_step;

	if($current_step != 'general')
	{
		//check _GET Csrf token
		//remember to add token at every m=? request !

		if(!kleeja_check_form_key_get('adm_aupdate'))
		{
			kleeja_admin_err($lang['INVALID_GET_KEY'], true, $lang['ERROR'], true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'), 2);
		}
		
	}

	include(PATH . 'includes/update.php');

	//for style ..
	$stylee = 'admin_aupdate';
	//$action = basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') .'&amp;sty_t=style_orders';

	//class
	$ups = new kupdate();
	$is_ftp_supported = $ups->is_ftp_supported;

	$ftp_info = array('host', 'user', 'pass', 'path', 'port');

	if(!empty($config['ftp_info']))
	{
		$ftp_info = @unserialize($config['ftp_info']);
	}
	else
	{
		//todo : make sure to figure this from OS, and some other things
		$ftp_info['path'] = str_replace('/includes/adm', '', dirname(__file__)) . '/';
		#mose 
		if(strpos($ftp_info['path'], 'public_html') !== false)
		{
			$ftppath_parts = explode('public_html', $ftp_info['path']);
			$ftp_info['path'] = '/public_html'. $ftppath_parts[1];
		}
		else
		{
			$ftp_info['path'] = '/public_html/';
		}

		$ftp_info['port'] = 21;
		$ftp_info['host'] = str_replace(array('http://', 'https://'), array('', ''), $config['siteurl']);

		#ie. up.anmmy.com, www.anmmy.com
		if(sizeof(explode('.', $ftp_info['host'])) > 1 || (sizeof(explode('.', $ftp_info['host'])) == 2 && strpos($ftp_info['host'], 'www.') === false))
		{
			$siteurl_parts = explode('.', $ftp_info['host']);
			$ftp_info['host'] = 'ftp.' . $siteurl_parts[sizeof($siteurl_parts)-2] . '.' . $siteurl_parts[sizeof($siteurl_parts)-1];
		}

		$ftp_info['host'] = str_replace('www.', 'ftp.', $ftp_info['host']);

		if(strpos($ftp_info['host'], '/') !== false)
		{
			$siteurl_parts = explode('/', $ftp_info['host']);
			$ftp_info['host'] = $siteurl_parts[0];
		}
	}


	switch($current_step)
	{
						
		default :  //general
		
			$not_writable = false;

			//check if not writable then we need ftp
			if(!is_writable(PATH))
			{
				$not_writable = true;
				//kleeja_admin_info($lang['KLJ_DIR_NOT_WR']);
			}


			//save ftp info in database
			if(isset($_POST['_fmethod']) && $_POST['_fmethod'] == 'kftp')
			{
				if(!kleeja_check_form_key('adm_aupdate', 3600))
				{
					kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
				}

				$ups->save_f_method('kftp');
				$ups->f_method = 'kftp';
				if(empty($_POST['ftp_host']) || empty($_POST['ftp_port']) || empty($_POST['ftp_user']) ||empty($_POST['ftp_pass']))
				{
					kleeja_admin_err($lang['EMPTY_FIELDS'], true,'', true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php'));
				}
				else
				{
					$ups->info = $ftpinfo = array('host'=>$_POST['ftp_host'], 'port'=>$_POST['ftp_port'], 'user'=>$_POST['ftp_user'], 'pass'=>$_POST['ftp_pass'], 'path'=>$_POST['ftp_path']);

					$ftpinfo['pass'] = '';
					update_config('ftp_info', serialize($ftpinfo), false);
							
					if(!$ups->check_connect())
					{
						kleeja_admin_err($lang['LOGIN_ERROR'], true,'', true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '?#!cp=' . basename(__file__, '.php'));
					}
					else
					{

						//. '&amp;' . $GET_FORM_KEY
						$ups->atend();
						kleeja_admin_info($lang['WAIT'] . ' ' . $lang['UPDATE_GOING_TODOWN'], true,'', true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;astep=1&amp;' . $GET_FORM_KEY, 10); //auto direct to download 
					}
				}

			}
			else if(isset($_POST['_fmethod']) && $_POST['_fmethod'] == 'zfile')
			{
				$ups->save_f_method('zfile');
				$ups->f_method = 'zfile';
				$ups->check_connect();
				kleeja_admin_info($lang['WAIT'] . ' ' . $lang['UPDATE_GOING_TODOWN'], true,'', true, basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php') . '&amp;astep=1&amp;' . $GET_FORM_KEY, 10); //auto direct to download 

			}


			break;	
		case '1' : //download
			
				//echo ('<h1>heeeeey</h1>');
				//exit();

				$re = $ups->update_core('1', $v);

					
			break;

		case '3': //database update
			# code...
			break;

		case '4': //delete temp files
			# code..
			break;

			
	}



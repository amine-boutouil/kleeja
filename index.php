<?php
##################################################
#						Kleeja 
#
# Filename : index.php 
# purpose :  home page  .
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author$ , $Rev$,  $Date::                           $
##################################################

	// security .. 
	define ( 'IN_INDEX' , true);
	//include imprtant file .. 
	include ('includes/common.php');
	
	($hook = kleeja_run_hook('begin_index_page')) ? eval($hook) : null; //run hook
	
	//is kleeja only for memebers ! 
	if(sizeof($g_exts) == 0 && !$usrcp->name())
	{
		// Send a 503 HTTP response code to prevent search bots from indexing this message
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		kleeja_info($lang['SITE_FOR_MEMBER_ONLY'], $lang['HOME']);
	}

	
	//type of how will decoding name ..
	switch($config['decode']):
		case 1:	$decode = "time";	break;
		case 2:	$decode = "md5";	break;
		default:
			//add you own decode
			($hook = kleeja_run_hook('decode_config_default')) ? eval($hook) : null; //run hook
			$decode = "";
		break;
	endswitch;

	//safe code
	if ($config['safe_code'])
	{
		$ch = new ocr_captcha;
	}
	
	//start class .. 
	$kljup->decode		= $decode;              
	$kljup->linksite	= $config['siteurl']; 
	$kljup->folder		= $config['foldername'];
	$kljup->filename	= $config['prefixname'];
	$kljup->action		= $action = "index.php";
	$kljup->filesnum	= $config['filesnum'];
	//--------------------- start user system part
	$kljup->types		= ($usrcp->name()) ? $u_exts : $g_exts;
	//$kljup->sizes		= ($usrcp->name()) ? $u_sizes : $g_sizes ;	//deprecated from 1rc6
	$kljup->id_user		= ($usrcp->name()) ? $usrcp->id() : '-1';
	$kljup->safe_code	= $config['safe_code'];
	//--------------------- end user system part
	$kljup->process();

	//add from 1rc6
	$FILES_NUM_LOOP = array();
	foreach(range(1, $config['filesnum']) as $i)
	{
		$FILES_NUM_LOOP[] = array('i' => $i, 'show'=>($i == 1 ? '' : 'none'));
	}

	//show errors and info
	$info = array();
	foreach($kljup->errs as $s )	
	{
		$info[] = array( 'i' => $s);
	}
	
	//some words for template
	$welcome_msg	= $config['welcome_msg'];
	$SAFE_CODE		= ($config['safe_code']) ? $ch->display_captcha(true) : false;
	$SAFE_CODE2		= ($config['safe_code']) ? str_replace('public_key', 'public_key2', $ch->display_captcha(true)) : false;

	//
	//for who online now..  
	// i dont like this feature and i prefer disable it
	//
	$show_online = ($config['allow_online']==1) ? true : false ;
	if ($config['allow_online'] == 1)
	{
	
		$visitornum		= $usersnum	=	0;
		//$show_online	= true;
		$OnlineNames	= array();
		$timeout		= 100; //second  
		$timeout2		= time()-$timeout;  
		
		$search_engines = array(
								'Google' => '<span style="color:orange;">GoogleBot</span>',
								'Yahoo' => '<span style="color:red;">Yahoo!Slurp</span>',
								//add more ..
								);
		
		//put another bot name
		($hook = kleeja_run_hook('anotherbots_online_index_page')) ? eval($hook) : null; //run hook
			
		$query = array(
						'SELECT'	=> 'DISTINCT(n.ip), n.username, n.agent',
						'FROM'		=> "{$dbprefix}online n",
						'WHERE'		=> "n.time > '$timeout2'"
				);
				
		($hook = kleeja_run_hook('qr_select_online_index_page')) ? eval($hook) : null; //run hook
		
		$result	= $SQL->build($query); 
		
		while($row=$SQL->fetch_array($result))
		{
			($hook = kleeja_run_hook('while_qr_select_online_index_page')) ? eval($hook) : null; //run hook	
			
			//bots
			if(!empty($row['agent']))
			{
				foreach($search_engines as $c=>$s)
				{
					if (strstr($row['agent'], $c) && empty($OnlineNames[$c]))
					{
						$usersnum++; 
						$OnlineNames[$c] = $s;
						break;
					}
				}
			}
			
			if($row['username'] != "-1") 
			{
				$usersnum++; 
				$OnlineNames[$row['username']] = $row['username'];
			}
			else
			{
				$visitornum++;
			}
		
		} #while
		
	 	$SQL->freeresult($result);
		
		$shownames = array();
		foreach ($OnlineNames as $k)
		{
			$shownames[] = array('name' => $k );
		}
		
		/*
		wanna increase your onlines counter ..you can from next line 
		 but you must know this is illegial method ... 
		*/
		$allnumbers = $usersnum + $visitornum;
		
		//check & update most ever users and vistors was online 
		if($stat_most_user_online_ever < $allnumbers)
		{
			
			$stat_most_user_online_ever = $allnumbers;
			$stat_last_muoe				= time();
			
			if (!$SQL->build(array('UPDATE'	=> "{$dbprefix}stats",'SET'	=> "most_user_online_ever='" . intval($allnumbers) . "',last_muoe='" . time() . "'")))
			{
				die($lang['CANT_UPDATE_SQL']);
			}
			
			delete_cache('data_stats');
		}
		
		$most_online = $stat_most_user_online_ever; 
		$on_muoe	 = gmdate("d-m-Y H:a", $stat_last_muoe);
		($hook = kleeja_run_hook('if_online_index_page')) ? eval($hook) : null; //run hook	
	
	}#allow_online
	
	($hook = kleeja_run_hook('end_index_page')) ? eval($hook) : null; //run hook	

		//for show .. 
		//header
		Saaheader($lang['HOME']);
			//index
			echo $tpl->display("index_body");
		//footer
		
		Saafooter();
	

?>

<?php
##################################################
#						Kleeja 
#
# Filename : index.php 
# purpose :  home page  .
# copyright 2007 Kleeja.com ..
#
##################################################

	// security .. 
	define ( 'IN_INDEX' , true);
	//include imprtant file .. 
	require ('includes/common.php');
	

	
	//decode s
	if ($config[decode] == 1 ) {$decode = "time";} 
	elseif ($config[decode] == 2) {$decode = "md5";}
	else{$decode = "";}
	//decode e
	
	
	//start class .. 
	$kljup->decode		=	$decode;              
	$kljup->linksite	=	$config[siteurl]; 
	$kljup->folder		=	$config[foldername];
	$kljup->filename	=	$config[prefixname];
	$kljup->action		=	"index.php";
	$kljup->filesnum	=	$config[filesnum];
	//--------------------- s user system part
	$kljup->types		= ( $usrcp->name() ) ? $u_exts : $g_exts;
	$kljup->sizes		= ( $usrcp->name() ) ? $u_sizes : $g_sizes ;	
	$kljup->id_user 	= ( $usrcp->name() ) ? $usrcp->id() : '-1';
	//--------------------- e user system part
	$inputs 			= $kljup->tpl(); //<<--- template
	$kljup->process();

	
	

	//show errors and info
	foreach($kljup->errs as $s )
	{
	$info[] 	= array( 'i' => $s );
	}
	if (!is_array($info)){$info = array();}
	
	
	//some words for template
	$info_lang 		= $lang['INFORMATION'];
	$welcome 		= $lang['WELCOME'];
	$welcome_msg 	= $config[welcome_msg];
	$NUMBER_ONLINE	= $lang['NUMBER_ONLINE'];
	$NUMBER_UONLINE	= $lang['NUMBER_UONLINE'];
	$NUMBER_VONLINE	= $lang['NUMBER_VONLINE'];
	
	
	//for online .. 
	if ($config[allow_online] == 1 ){
	$visitornum	=	0;
	$usersnum	=	0;
	$show_online= true;
	$OnlineNames = array();
	$result 	= $SQL->query("SELECT DISTINCT(ip),username,agent FROM {$dbprefix}online");  
	while($row=$SQL->fetch_array($result)){
		//googlebot
		if (strstr($row[agent], 'Googlebot')) {
			$usersnum++; 
			$OnlineNames[] = '<span style="color:orange;">[Googlebot]</span>';
		}
		elseif (strstr($row[agent], 'Yahoo! Slurp')) {
			$usersnum++; 
			$OnlineNames[] = '<span style="color:red;">[Yahoo!Slurp]</span>';
		}
		elseif($row[username] != "-1") {
			$usersnum++; 
			$OnlineNames[] =  $row[username];
		}
		else
		{
		$visitornum++; 
		}
	
	} #while
 	$SQL->freeresult($result);
	
	foreach ($OnlineNames as $k) {
	$shownames[] = array( name => $k );
	}
	if (!is_array($shownames)){$shownames = array();}
	
	//wanna increas your onlines counter ..you can from next line 
	// but you must no this is illegial ... 
	$allnumbers = $usersnum +$visitornum;
	}#allow_online
	
	
	//for show .. 
	
	//header
	Saaheader("Kleeja");
	//index
	print $tpl->display("index_body.html");
	//footer
	Saafooter();
	

?>
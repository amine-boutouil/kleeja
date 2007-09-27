<?
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
	$tahmil->tashfir=$decode;              
	$tahmil->linksite= $config[siteurl]; 
	$tahmil->asarsi=$config[foldername];
	$tahmil->isam=$config[prefixname];
	$tahmil->amchan="index.php";
	$tahmil->thwara=$config[filesnum];
	//--------------------- s user system part
	$tahmil->ansaq= ( $usrcp->name() ) ? $u_exts : $g_exts;
	$tahmil->sizes= ( $usrcp->name() ) ? $u_sizes : $g_sizes ;	
	$tahmil->id_user = ( $usrcp->name() ) ? $usrcp->id() : '-1';
	//--------------------- e user system part
	$inputs = $tahmil->thwara(); //<<--- template
	$tahmil->aksid();
	

	//show errors and info
	foreach($tahmil->errs as $s )
	{
	$info[] = array( 'i' => $s );
	}
	if (!is_array($info)){$info = array();}
	
	
	//some words for template
	$info_lang = $lang['INFORMATION'];
	$welcome = $lang['WELCOME'];
	$welcome_msg = $config[welcome_msg];


	//header
	Saaheader("Kleeja");
	//index
	print $tpl->display("index_body.html");
	//footer
	Saafooter();
	

?>
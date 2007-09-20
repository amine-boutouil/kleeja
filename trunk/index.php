<?
###############################
#SaaUp 1.0
#By Saanina 
###############################

	// security .. 
	define ( 'IN_INDEX' , true);
	//include imprtant file .. 
	require ('includes/common.php');


	//decode
	if ($config[decode] == 1 ) {$decode = "time";} elseif ($config[decode] == 2) {$decode = "md5";}
	else{$decode = "";}
	
	//start class .. 
	$tahmil->tashfir=$decode;              
	$tahmil->linksite= $config[siteurl]; 
	$tahmil->asarsi=$config[foldername];
	$tahmil->isam=$config[prefixname];
	$tahmil->amchan="index.php";
	$tahmil->thwara=$config[filesnum];
	//--------------------- s user system part
	if ( $usrcp->name() )
	{
	$tahmil->ansaq= $u_exts;
	$tahmil->sizes= $u_sizes;	
	$tahmil->id_user = $usrcp->id();
	}
	else
	{
	$tahmil->ansaq= $g_exts;
	$tahmil->sizes= $g_sizes;	
	$tahmil->name_user = '-1';
	}
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
	$info_lang = "تعليمات";
	$welcome = "أهلاً";
	$welcome_msg = $config[welcome_msg];


	//header
	Saaheader("SaaUp");
	//index
	print $tpl->display("index_body.html");
	//footer
	Saafooter();
	

?>
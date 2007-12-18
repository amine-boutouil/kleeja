<?
##################################################
#						Kleeja
#
# Filename : cache.php
# purpose :  cache for all script.
# copyright 2007 Kleeja.com ..
#
##################################################

	  if (!defined('IN_COMMON'))
	  {
	  echo '<strong><br /><span style="color:red">[NOTE]: This Is Dangrous Place !! [2007 saanina@gmail.com]</span></strong>';
	  exit();
	  }


	//get config data from config table  ... ===========================================
	if (file_exists('cache/data_config.php')){include ('cache/data_config.php'); }
	if ( !$config or !file_exists('cache/data_config.php') )
	{

  	$sqlc	=	$SQL->query("SELECT * FROM {$dbprefix}config");
			$file_datac = '<' . '?php' . "\n\n";
			$file_datac .= "\n// auto-generated cache files\n//By:Saanina@gmail.com \n\n";
			$file_datac .= '$config = array( ' . "\n";

	while($row=$SQL->fetch_array($sqlc)){
			$config[$row['name']] =$row['value'];
			$file_datac .= '\''.$row['name'].'\' => \'' . $row['value'] . '\',' . "\n";
		}
			$file_datac .= ');'."\n\n";
			$file_datac .= '?' . '>';
 	$SQL->freeresult($sqlc);

	$filenumc = @fopen('cache/data_config.php', 'w');
	flock($filenumc, LOCK_EX); // exlusive look
	@fwrite($filenumc, $file_datac);
	fclose($filenumc);
	}



	//get data from types table ... ===================================
	if (file_exists('cache/data_exts.php')){include ('cache/data_exts.php'); }
	if ( !($g_exts || $u_exts) || !( file_exists('cache/data_exts.php') ) )
	{

  	$sqlt	=	$SQL->query("SELECT * FROM {$dbprefix}exts");
			$file_datat = '<' . '?php' . "\n\n";
			$file_datat .= "\n// auto-generated cache files\n//By:Saanina@gmail.com \n\n";
			$file_datat .= 'if (empty($g_exts) || !is_array($g_exts)){$g_exts = array();}'."\n";
			$file_datat .= 'if (empty($u_exts) || !is_array($u_exts)){$u_exts = array();}'."\n\n";
	while($row=$SQL->fetch_array($sqlt)){
			if ( $row['gust_allow'] )
			{
			$g_exts[$row['id']] = $row['ext'];
			$file_datat .= '$g_exts[\'' . $row['id'] . '\']  =   \'' . $row['ext'] . '\';' . "\n";
			}
			if ( $row['user_allow'] )
			{
			$u_exts[$row['id']] = $row['ext'];
			$file_datat .= '$u_exts[\'' . $row['id'] . '\']  =   \'' . $row['ext'] . '\';' . "\n";
			}
		}
			$file_datat .= "\n\n";
			$file_datat .= '?' . '>';
 	$SQL->freeresult($sqlt);

	$filenumt = @fopen('cache/data_exts.php', 'w');
	flock($filenumt, LOCK_EX); // exlusive look
	@fwrite($filenumt, $file_datat);
	fclose($filenumt);
	}


	//get sizes data from types table ... ===========================================
	if (file_exists('cache/data_sizes.php')){include ('cache/data_sizes.php'); }
	if ( !($g_sizes || $u_sizes) || !( file_exists('cache/data_sizes.php') ) )
	{

  	$sqls	=	$SQL->query("SELECT * FROM {$dbprefix}exts");
			$file_datas = '<' . '?php' . "\n\n";
			$file_datas .= "\n// auto-generated cache files\n//By:Saanina@gmail.com \n\n";
			$file_datas .= 'if (empty($g_sizes) || !is_array($g_sizes)){$g_sizes = array();}'."\n";
			$file_datas .= 'if (empty($u_sizes) || !is_array($u_sizes)){$u_sizes = array();}'."\n\n";

	while($row=$SQL->fetch_array($sqls)){
			if ( $row['gust_allow'])
			{
			$g_sizes[$row['id']] = $row['gust_size'];
			$file_datas .= '$g_sizes[\'' . $row['ext'] . '\']  =   \'' . $row['gust_size'] . '\';' . "\n";
			}
			if ( $row['user_allow'])
			{
			$u_sizes[$row['id']] = $row['user_size'];
			$file_datas .= '$u_sizes[\'' . $row['ext'] . '\']  =   \'' . $row['user_size'] . '\';' . "\n";
			}
		}
			$file_datas .= "\n\n".'if (!is_array($g_sizes)){$g_sizes = array();}'."\n";
			$file_datas .= 'if (!is_array($u_sizes)){$u_sizes = array();}'."\n\n";
			$file_datas .= '?' . '>';
 	$SQL->freeresult($sqls);

	$filenums = @fopen('cache/data_sizes.php', 'w');
	flock($filenums, LOCK_EX); // exlusive look
	@fwrite($filenums, $file_datas);
	fclose($filenums);
	}



	//stats .. to cache
	if( file_exists("cache/data_stats.php") ){
		//1
		include ("cache/data_stats.php");
		//2
		$tfile		= @filectime("cache/data_stats.php");
		if( (time()-$tfile) >= 3600){    //after 1 hours
		@unlink("cache/data_stats.php");
		}
	}else{
	$sqlstat	=	$SQL->query("SELECT * FROM {$dbprefix}stats");

	$file_dataw = '<' . '?php' . "\n\n";
	$file_dataw .= "\n// auto-generated cache files\n//By:Saanina@gmail.com \n\n";

	while($row=$SQL->fetch_array($sqlstat)){
	$stat_files 			=  $row[files];
	$stat_sizes 			=  $row[sizes];
	$stat_users 			=  $row[users];
	$stat_last_file 		=  $row[last_file];
	$stat_last_f_del 		=  $row[last_f_del];
	$stat_today 			=  $row[today];
	$stat_counter_today 	=  $row[counter_today];
	$stat_counter_yesterday	=  $row[counter_yesterday];
	$stat_counter_all		=  $row[counter_all];
	$stat_last_google		=  $row[last_google];
	$stat_last_yahoo		=  $row[last_yahoo];
	$stat_google_num		=  $row[google_num];
	$stat_yahoo_num			=  $row[yahoo_num];
	//$stat_rules				=  $row[rules];
	
	//write
	$file_dataw .= '$stat_files  			=   \'' . $row['files'] . '\';' . "\n";
	$file_dataw .= '$stat_sizes  			=   \'' . $row['sizes'] . '\';' . "\n";
	$file_dataw .= '$stat_users  			=   \'' . $row['users'] . '\';' . "\n";
	$file_dataw .= '$stat_last_file 		=	\'' . $row['last_file'] . '\';' . "\n";
	$file_dataw .= '$stat_last_f_del		=	\'' . $row['last_f_del'] . '\';' . "\n";
	$file_dataw .= '$stat_today 			=	\'' . $row['today'] . '\';' . "\n";
	$file_dataw .= '$stat_counter_today		=	\'' . $row['counter_today'] . '\';' . "\n";
	$file_dataw .= '$stat_counter_yesterday =	\'' . $row['counter_yesterday'] . '\';' . "\n";
	$file_dataw .= '$stat_counter_all 		=	\'' . $row['counter_all'] . '\';' . "\n";
	$file_dataw .= '$stat_last_google 		=	\'' . $row['last_google'] . '\';' . "\n";
	$file_dataw .= '$stat_google_num 		=	\'' . $row['google_num'] . '\';' . "\n";
	$file_dataw .= '$stat_last_yahoo 		=	\'' . $row['last_yahoo'] . '\';' . "\n";
	$file_dataw .= '$stat_yahoo_num 		=	\'' . $row['yahoo_num'] . '\';' . "\n";
	//$file_dataw .= '$stat_rules				=	\'' . $row['rules'] . '\';' . "\n";
	
		}
	$file_dataw .= '?' . '>';
	$SQL->freeresult($sqlstat);
	$filenumw = @fopen('cache/data_stats.php', 'w');
	flock($filenumw, LOCK_EX); // exlusive look
	@fwrite($filenumw, $file_dataw);
	fclose($filenumw);
	}//end else


	// administarator sometime need some files and delete other .. we
	// do that for him .. becuase he had no time .. :)            last_down - $config[del_f_day]
    if ( date( "j" ,$stat_last_f_del ) < date( "j" ,time() ) )
    {
	$filesql	=	$SQL->query("SELECT id,last_down,name,folder FROM {$dbprefix}files");
	while($row=$SQL->fetch_array($filesql)){

	     #time per day ..
	    $del_date = mktime(0, 0, 0, date(m), date(d)+$config[del_f_day], date(y));
		$totaldays = (time() - $row[last_down] )  / (60 * 60 * 24);

	    if ( $totaldays <= $del_date )
	    {
						$update = $SQL->query("DELETE FROM `{$dbprefix}files` WHERE id='" . intval($row['id']) . "' ");
						if (!$update) { die($lang['CANT_UPDATE_SQL']);}

						//delete from folder ..
						@unlink ( $row['folder'] . "/" . $row['name'] );
							//delete thumb
							if (is_file($row['folder'] . "/thumbs/" . $row['name'] ))
							{@unlink ($row['folder'] . "/thumbs/" . $row['name'] );}
							//delete thumb
	    }

    }
    //update $stat_last_f_del !!
				$update2 = $SQL->query("UPDATE `{$dbprefix}stats` SET
				last_f_del  = '" . time() . "' ");
				if (!$update2) { die($lang['CANT_UPDATE_SQL']);}
    } //stat_del


	//get banned ips data from stats table  ... ===========================================
	if (file_exists('cache/data_ban.php')){include ('cache/data_ban.php'); }
	if ( !$banss or !file_exists('cache/data_ban.php') )
	{

  	$sqlb	=	$SQL->query("SELECT ban FROM {$dbprefix}stats");
	
		$file_datab = '<' . '?php' . "\n\n";
		$file_datab .= "\n// auto-generated cache files\n//By:Saanina@gmail.com \n\n";
		$file_datab .= '$banss = array( ' . "\n";

	while($row=$SQL->fetch_array($sqlb)){$ban1 = $row[ban]; }
	$SQL->freeresult($sqlb);
	
	if (!empty($ban1) || $ban1 != ' '|| $ban1 != '  ')
	{
		//seperate ips .. 
		$ban2 = explode("|", $ban1);
		for ( $i=0;$i<count($ban2);$i++)
		{
		$banss[$i] = $ban2[$i];
		$file_datab .= '\'' . trim($ban2[$i]) . '\',' . "\n";
		}#for
	
		$file_datab .= ');'."\n\n";
		$file_datab .= '?' . '>';
 	}

	$filenumb = @fopen('cache/data_ban.php', 'w');
	flock($filenumb, LOCK_EX); // exlusive look
	@fwrite($filenumb, $file_datab);
	fclose($filenumb);
	}	
	
	//get rules data from stats table  ... ===========================================
	if (file_exists('cache/data_rules.php')){include ('cache/data_rules.php'); }
	if ( !$ruless or !file_exists('cache/data_rules.php') )
	{

  	$sqlb	=	$SQL->query("SELECT rules FROM {$dbprefix}stats");
	
		$file_datar = '<' . '?php' . "\n\n";
		$file_datar .= "\n// auto-generated cache files\n//By:Saanina@gmail.com \n\n";

	while($row=$SQL->fetch_array($sqlb)){$rules1 = $row[rules]; }
	$SQL->freeresult($sqlb);
	
	if ( !empty($rules1) || $rules1 != ' '|| $rules1 != '  ')
	{

		$ruless = $rules1;
		$file_datar .= '$ruless = \'' .str_replace(array("'","\'"), "\'", $rules1) .'\';'."\n\n"; // its took 2 hours ..

 	}
	
	$file_datar .= '?' . '>';
	$filenumr = @fopen('cache/data_rules.php', 'w');
	flock($filenumr, LOCK_EX); // exlusive look
	@fwrite($filenumr, $file_datar);
	fclose($filenumr);
	}
	
	
	
	
	//some function .. for disply ..
	
	function Saaheader($title) {
	global $tpl,$usrcp,$lang,$filecp_st,$config;

	//login - logout-profile... etc ..
	$filecp_st = ( $usrcp->name() ) ? true: false;
	if ( !$usrcp->name() ) { $login_name= $lang['LOGIN'];  $login_url= "usrcp.php?go=login";
	$usrcp_name = $lang['REGISTER'];$usrcp_url = "usrcp.php?go=register";
	}
	else{ $login_name= $lang['LOGOUT']."[".$usrcp->name()."]";  $login_url= "usrcp.php?go=logout";
	$usrcp_name = $lang['PROFILE'];$usrcp_url = "usrcp.php?go=profile";
	}

	$vars = array (0=>"navigation",1=>"index_name",2=>"guide_name",3=>"guide_url",4=>"rules_name",5=>"rules_url",
					6=>"call_name",7=>"call_url",8=>"login_name",9=>"login_url",10=>"usrcp_name",11=>"usrcp_url",12=>"filecp_name",13=>"filecp_url",14=>"stats_name",15=>"stats_url");
	
	if($config[mod_writer]){
	$vars2 = array(0=>$lang['JUMPTO'],1=>$lang['INDEX'],2=>$lang['GUIDE'],3=>"guide.html",4=>$lang['RULES'],5=>"rules.html",
					6=>$lang['CALL'],7=>"go.php?go=call",8=>$login_name,9=>$login_url,10=>$usrcp_name,11=>$usrcp_url,12=>$lang['FILECP'],13=>"filecp.html",14=>$lang['STATS'],15=>"stats.html");
	}else{
	$vars2 = array(0=>$lang['JUMPTO'],1=>$lang['INDEX'],2=>$lang['GUIDE'],3=>"go.php?go=guide",4=>$lang['RULES'],5=>"go.php?go=rules",
					6=>$lang['CALL'],7=>"go.php?go=call",8=>$login_name,9=>$login_url,10=>$usrcp_name,11=>$usrcp_url,12=>$lang['FILECP'],13=>"usrcp.php?go=filecp",14=>$lang['STATS'],15=>"go.php?go=stats");

	}

	//assign variables
	for($i=0;$i<count($vars);$i++){$tpl->assign($vars[$i],$vars2[$i]);}
	$tpl->assign("dir",$lang['DIR']);
	$tpl->assign("title",$title);


	print $tpl->display("header.html");
	return;
	}



	function Saafooter() {
	global $tpl,$SQL,$starttm,$config,$usrcp,$lang;
	//show stats ..
	if ($config[statfooter] !=0) {
	if ($do_gzip_compress !=0 ) { $gzip = "Enabled"; } else { $gzip = "Disabled"; }
	$end = explode(" ", microtime());
	$loadtime = number_format($end[1] + $end[0] - $starttm , 4);
	$queries_num = $SQL->query_num;
	$time_sql = round($SQL->query_num / $loadtime) ;
	$page_stats = "<b>[</b> GZIP : $gzip - Generation Time: $loadtime Sec [SQL: $time_sql % ] - Queries: $queries_num <b>]</b>" ;
	$tpl->assign("page_stats",$page_stats);
	}#end statfooter

	//if admin
	if ( $usrcp->admin() )
	{
	$admin_page = '<br /><a href="./admin.php">' . $lang['ADMINCP'] .  '</a><br />';
	$tpl->assign("admin_page",$admin_page);
	}
	
	// if google analytics .. 
	if ( strlen($config[googleanalytics]) > 4 ) {
		$googleanalytics = '
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write("\<script src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'>\<\/script>" );
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("' . $config[googleanalytics] . '");
pageTracker._initData();
pageTracker._trackPageview();
</script>';
		$tpl->assign("googleanalytics",$googleanalytics);
	}

	//show footer
	print $tpl->display("footer.html");

	// THEN .. at finish
	$SQL->close();
	return;
	}

	//size function
	function Customfile_size($size)
	{
	  $sizes = array(' B', ' KB', ' MB', ' GB', ' TB', 'PB', ' EB');
	  $ext = $sizes[0];
	  for ($i=1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
	   $size = $size / 1024;
	   $ext  = $sizes[$i];
	  }
	  return round($size, 2).$ext;
	}

	// for who online .. 
	function KleejaOnline () {
	global $SQL,$usrcp,$dbprefix;
	
	// get information .. 
	$ip	 = (getenv('HTTP_X_FORWARDED_FOR')) ? getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');
	$agent		= $_SERVER['HTTP_USER_AGENT'];
	$timeout 	= 600; //seconds
	$time 		= time();  
	$timeout2 	= $time-$timeout;  
	#$username	= ( $usrcp->name() ) ?  (($usrcp->admin() )?  '<span style="color:blue;"><b>' .$usrcp->name(). '</b></span>' : $usrcp->name() ): '-1';
	$username	= ( $usrcp->name() ) ? $usrcp->name(): '-1';
	//
	//for stats ------------
	if (strstr($agent, 'Googlebot')) {
		$SQL->query("UPDATE {$dbprefix}stats set last_google='$time'");  
		$SQL->query("UPDATE {$dbprefix}stats set google_num=google_num+1");  
	}elseif (strstr($agent, 'Yahoo! Slurp')) {
		$SQL->query("UPDATE {$dbprefix}stats set last_yahoo='$time'");  
		$SQL->query("UPDATE {$dbprefix}stats set yahoo_num=yahoo_num+1");  
	}
	
	//---
	
	$who_here	= $SQL->num_rows($SQL->query("SELECT id FROM {$dbprefix}online WHERE  ip='$ip'"));  
	
	if(!$who_here){
		$SQL->query("INSERT INTO {$dbprefix}online VALUES ('','$ip','$username','$agent','$time')");  
	}else
	{
		$SQL->query("UPDATE {$dbprefix}online set time='$time' WHERE ip='$ip'");  
	}

	// i hate who online feature due to this step .. :( 
	$delete 	= $SQL->query("DELETE FROM {$dbprefix}online WHERE time < $timeout2");  
	return;
	}#End function
	
	
	function visit_stats (){
	global $SQL,$usrcp,$dbprefix,$stat_today;
	
	$today = date("j");

	if ($today !=  $stat_today  ) {
	
		//counter yesterday .. and make toaay counter as 0 , then get date of today .. 
		$sqlstat	=	$SQL->query("SELECT counter_today FROM {$dbprefix}stats");
		while($row=$SQL->fetch_array($sqlstat)){ $yesterday_cout = $row[counter_today]; }
		$sql = $SQL->query("UPDATE {$dbprefix}stats set counter_yesterday='$yesterday_cout',counter_today='0',today='$today'");
		if ($sql){ @unlink("cache/data_stats.php"); } 
	
	}
	
	
		if ( !$_SESSION['visitor'] ){
			$sqls = $SQL->query("UPDATE {$dbprefix}stats set counter_today=counter_today+1, counter_all=counter_all+1");  
			if ($sqls){$_SESSION['visitor'] = true;}
		}
		
	return;
	}
	
	/// for ban ips .. 
	function get_ban () {
	global $banss,$lang,$tpl,$text;
	
		//visitor ip now 
		if (getenv('HTTP_X_FORWARDED_FOR')){$ip	= getenv('HTTP_X_FORWARDED_FOR');} else {$ip= getenv('REMOTE_ADDR');}
	
		//now .. looL for banned ips 
		if ( !empty($banss) ) {
		if (!is_array($banss)){$banss = array();}
		
		foreach ( $banss as $ip2 ) {
			//first .. replace all * with something good .
			$replaceIt = str_replace("*", '[0-9]{1,3}', $ip2);
			
			if ( $ip == $ip2 || @eregi($replaceIt , $ip) ){
				
				$text = $lang['U_R_BANNED'];
				$stylee = "info.html";
				//header
				Saaheader($lang['U_R_BANNED']);
				//index
				print $tpl->display($stylee);
				//footer
				Saafooter();
				exit();
			
			}
		
		}
	}#empty	
	return;
	}

	
	//no floods ..
	//floods : floods can get by this time 
	//pertime : time which u must dont get more floods in it ..
	function antifloods ($floods, $pertime) { 
	global $tpl, $lang, $text;
	
	
	$many	= $floods; // many [times of max floods ] 
	$time	= $pertime; // per second 
	
	//go 
	if(isset($_SESSION['antiflood']))
    {
        if((time()-$_SESSION['antiflood']['time']) >= $time)
        {
            unset($_SESSION['antiflood']);
            
            $_SESSION['antiflood']['time']=time();
            $_SESSION['antiflood']['looks']=1;
        }
        else
        {
            $_SESSION['antiflood']['looks']++;
            
            if($_SESSION['antiflood']['looks']>=$many)
            {
				//error
	           $text = $lang['U_R_FLOODER'].' #' . $many . '/' . $time;
				$stylee = "err.html";
				//header
				Saaheader($lang['U_R_FLOODER']);
				//index
				print $tpl->display($stylee);
				//footer
				Saafooter();
				exit();
            }
        }

    }
    else
    {
        $_SESSION['antiflood']['time']=time();
        $_SESSION['antiflood']['looks']=1;

    }
}#end antifloods


?>
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
			$file_datat .= '$g_exts[' . $row['id'] . ']  =   \'' . $row['ext'] . '\';' . "\n";
			}
			if ( $row['user_allow'] )
			{
			$u_exts[$row['id']] = $row['ext'];
			$file_datat .= '$u_exts[' . $row['id'] . ']  =   \'' . $row['ext'] . '\';' . "\n";
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
			$file_datas .= '$g_sizes[' . $row['ext'] . ']  =   \'' . $row['gust_size'] . '\';' . "\n";
			}
			if ( $row['user_allow'])
			{
			$u_sizes[$row['id']] = $row['user_size'];
			$file_datas .= '$u_sizes[' . $row['ext'] . ']  =   \'' . $row['user_size'] . '\';' . "\n";
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
	
	//stats .. 	while($row=$SQL->fetch_array($sqls)){
	$sqlstat	=	$SQL->query("SELECT * FROM {$dbprefix}stats");
	while($row=$SQL->fetch_array($sqlstat)){
	$stat_files = $row[files];
	$stat_sizes = $row[sizes];
	$stat_users = $row[users];
	$stat_last_file =  $row[last_file];
	} 
	$SQL->freeresult($sqlstat);   
	//
	
	
	//some function .. for disply .. 
	function Saaheader($title) {
	global $tpl,$usrcp;
	
	
	//login - logout-profile...
	if ( !$usrcp->name() ) { $login_name= "دخول";  $login_url= "usrcp.php?go=login"; 
	$usrcp_name = "تسجيل عضويه";$usrcp_url = "usrcp.php?go=register";
	}
	else{ $login_name= "خروج"."[".$usrcp->name()."]";  $login_url= "usrcp.php?go=logout"; 
	$usrcp_name = "ملفك ..";$usrcp_url = "usrcp.php?go=profile";
	}
	
	$vars = array (0=>"navigation",1=>"index_name",2=>"guide_name",3=>"guide_url",4=>"rules_name",5=>"rules_url",6=>"call_name",7=>"call_url",8=>"login_name",9=>"login_url",10=>"usrcp_name",11=>"usrcp_url");
	$vars2 = array(0=>"إنتقل إلى",1=>"الرئيسيه",2=>"الملفات المسوحه",3=>"go.php?go=guide",4=>"الشروط",5=>"go.php?go=rules",6=>"إتصل بنا",7=>"go.php?go=call",8=>$login_name,9=>$login_url,10=>$usrcp_name,11=>$usrcp_url);
	
	//assign variables 
	for($i=0;$i<count($vars);$i++){$tpl->assign($vars[$i],$vars2[$i]);}
	$tpl->assign("title",$title);

	
	print $tpl->display("header.html");
	}
	

	
	function Saafooter() {
	global $tpl,$SQL,$starttm,$config,$usrcp;
	//show stats .. 
	if ($config[statfooter]) {
	if ($do_gzip_compress) { $gzip = "Enabled"; } else { $gzip = "Disabled"; }
	$end = explode(" ", microtime());
	$loadtime = number_format($end[1] + $end[0] - $starttm , 4);
	$queries_num = $SQL->query_num;
	$time_sql = round($SQL->query_num / $loadtime) ;
	$page_stats = "<b>[</b> GZIP : $gzip - Generation Time: $loadtime Sec [SQL: $time_sql % ] - Queries: $queries_num <b>]</b>" ;  
	$tpl->assign("page_stats",$page_stats);
	}#end statfooter
	
	if ( $usrcp->admin() )
	{
	$admin_page = '<br /><a href="./admin.php">مركز التحكم</a><br />';
	$tpl->assign("admin_page",$admin_page);
	}
	//show footer
	print $tpl->display("footer.html");
	
	// THEN .. at finish
	$SQL->close();
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



?>
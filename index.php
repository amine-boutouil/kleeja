<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/

 
define ('IN_INDEX' , true);
define ('IN_SUBMIT_UPLOADING' , (isset($_POST['submitr']) || isset($_POST['submittxt'])));

include ('includes/common.php');

($hook = kleeja_run_hook('begin_index_page')) ? eval($hook) : null; //run hook

//
//Is kleeja only for memebers ?! 
//
if(isset($g_exts)  && (sizeof($g_exts) == 0 && !$usrcp->name()))
{
	// Send a 503 HTTP response code to prevent search bots from indexing this message
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	kleeja_info($lang['SITE_FOR_MEMBER_ONLY'], $lang['HOME']);
}

//
//Type of how will decoding name ..
//
switch($config['decode']):
	case 1:	$decode = 'time';	break;
	case 2:	$decode = 'md5';	break;
	default:
		//add you own decode
		$decode = '';
		($hook = kleeja_run_hook('decode_config_default')) ? eval($hook) : null; //run hook
	break;
endswitch;

//
//start uploader class .. 
//
$kljup->decode		= $decode;              
$kljup->linksite	= $config['siteurl']; 
$kljup->folder		= $config['foldername'];
$kljup->filename	= $config['prefixname'];
$kljup->action		= $action = "index.php";
$kljup->filesnum	= $config['filesnum'];
//--------------------- start user system part
$kljup->types		= ($usrcp->name()) ? $u_exts : $g_exts;
$kljup->id_user		= ($usrcp->name()) ? $usrcp->id() : '-1';
$kljup->user_is_adm = $usrcp->admin();
$kljup->safe_code	= $config['safe_code'];
//--------------------- end user system part
$kljup->process();

//add from 1rc6
$FILES_NUM_LOOP = array();
foreach(range(1, $config['filesnum']) as $i)
{
	$FILES_NUM_LOOP[] = array('i' => $i, 'show'=>($i == 1 ? '' : 'display: none'));
}

//show errors and info
$info = array();
foreach($kljup->errs as $t=>$s)	
{
	$info[] = array('t'=>$s[1], 'i' => $s[0]);
}

//some words for template
$welcome_msg	= $config['welcome_msg'];
$filecp_link	= $usrcp->id() ? $config['siteurl'] . ($config['mod_writer'] ? 'filecp.html' : 'ucp.php?go=filecp') : false;

//
//For who online now..  
//I dont like this feature and i prefer disable it
//
$show_online = $config['allow_online'] == 1 ? true : false;
if ($show_online)
{
	$visitornum		= $usersnum	=	0;
	$online_names	= array();
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

		//check if agent and add him to online list
		if(!empty($row['agent']))
		{
			foreach($search_engines as $c=>$s)
			{
				if (strstr($row['agent'], $c) && empty($online_names[$c]))
				{
					$usersnum++; 
					$online_names[$c] = $s;
					break;
				}
			}
		}

		//not guest , -1 is userid for guest
		if($row['username'] != '-1') 
		{
			$usersnum++; 
			$online_names[$row['username']] = $row['username'];
		}
		else
		{
			$visitornum++;
		}
	} #while
		
	$SQL->freeresult($result);

	//make names as array to print them in template
	$shownames = array();
	foreach ($online_names as $k)
	{
		$shownames[] = array('name' => $k, 'seperator' => sizeof($shownames) ? ',' : '');
	}

	//some variables must be destroyed here
	unset($online_names, $timeout, $timeout2);

	/**
	* Wanna increase your onlines counter ..you can from next line 
	* but you must know this is illegal ... 
	*/
	$allnumbers = $usersnum + $visitornum;

	//check & update most ever users and vistors was online
	if(empty($config['most_user_online_ever']) || trim($config['most_user_online_ever']) == '')
	{
		$most_online	= $allnumbers;
		$on_muoe		= time();
	}
	else
	{
		list($most_online, $on_muoe) = @explode($config['most_user_online_ever']);
	}

	if((int) $most_online < $allnumbers || (empty($config['most_user_online_ever']) || trim($config['most_user_online_ever']) == ''))
	{
		update_config('most_user_online_ever', $allnumbers . ':' . time());
	}

	$on_muoe = date('d-m-Y h:i a', $on_muoe);

	($hook = kleeja_run_hook('if_online_index_page')) ? eval($hook) : null; //run hook	
}#allow_online


($hook = kleeja_run_hook('end_index_page')) ? eval($hook) : null; //run hook	


//header
Saaheader();
//index
echo $tpl->display("index_body");
//footer
Saafooter();
	

//<-- EOF

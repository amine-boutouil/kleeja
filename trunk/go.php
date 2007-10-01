<?
##################################################
#						Kleeja
#
# Filename : go.php
# purpose :  File for Navigataion .
# copyright 2007 Kleeja.com ..
#
##################################################

	// security ..
	define ( 'IN_INDEX' , true);
	//include imprtant file ..
	require ('includes/common.php');


	switch ($_GET['go']) {
	case "guide" : //=============================[guide]
	$stylee = "guide.html";
	$titlee = $lang['GUIDE'];
	$text_msg_g = $lang['GUIDE_VISITORS'];
	$text_msg_u = $lang['GUIDE_USERS'];
	$L_EXT	= $lang['EXT'];
	$L_SIZE = $lang['SIZE'];
	//make it loop
	foreach($g_exts as $s )
	{
		$gggg[] = array( 'ext' => $s,
						'num' => Customfile_size($g_sizes[$s])
		);

	}

	if (!is_array($gggg)){$gggg = array();}

	//make it loop
	foreach($u_exts as $s )
	{
	$uuuu[] = array( 'ext' => $s,
					'num' => Customfile_size($u_sizes[$s])

	);
	}
	if (!is_array($uuuu)){$uuuu = array();}


	break; //=================================================
	case "report" : //=============================[report]


	//inlude class
	require ('includes/ocheck_class.php');
	//start ocheck class
	$ch = new ocheck;
	$ch->method = 'post';
	$ch->PathImg = 'images/code';

	if ( !isset($_POST['submit']) )
	{
	$stylee = "report.html";
	$titlee = $lang['REPORT'];
	$url_id = $config[siteurl]."download.php?id=".intval($_GET['id']);
	$action = "./go.php?go=report";
	$submit = $lang['REPORT'];
	$L_NAME = $lang['YOURNAME'];
	$L_MAIL = $lang['EMAIL'];
	$L_URL 	= $lang['URL'];
	$L_TEXT = $lang['REASON'];
	$L_CODE = $lang['VERTY_CODE'];
	$code = $ch->rand();
	$code_input = $ch->show();
	$id_d = intval($_GET['id']);


		// first
	if (!$_GET['id']) {
			$text = $lang['NO_ID'];
			$stylee = 'err.html';
	}

	}
	else
	{

		if (empty($_POST['rname']) || empty($_POST['rmail']) || empty($_POST['rurl']) )
		{

			$text = $lang['EMPTY_FIELDS'];
			$stylee = 'err.html';

		}
		else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['rmail'])))
		{
			$text = $lang['WRONG_EMAIL'];
			$stylee = 'err.html';
		}
		else if (strlen($_POST['rtext']) > 300 )
		{
			$text = $lang['NO_ME300RES'];
			$stylee = 'err.html';
		}
		else if ( !$ch->result($_SESSION['ocheck']) )
		{
			$text = $lang['WRONG_VERTY_CODE'];
			$stylee = 'err.html';
		}
		else
		{
				$name	= (string)	$SQL->escape($_POST['rname']);
				$text	= (string)	$SQL->escape($_POST['rtext']);
				$mail	= (string)	$_POST['rmail'];
				$url	= (string)	$_POST['rurl'];
				$time 	= (int)		time();
				$rid	= (int)		$_POST['rid'];

				if (getenv('HTTP_X_FORWARDED_FOR')){
				$ip=  getenv('HTTP_X_FORWARDED_FOR');
				} else {
				$ip=  getenv('REMOTE_ADDR');}


				$insert = $SQL->query("INSERT INTO `{$dbprefix}reports` (
				`name` ,`mail` ,`url` ,`text` ,`time` ,`ip`
				)
				VALUES (
				'$name','$mail','$url','$text','$time','$ip'
				)");

				$update = $SQL->query("UPDATE {$dbprefix}files SET
								report=report+1
	                            WHERE id='$rid' ");


				if (!$insert) {
				$text =  $lang['CANT_INSERT_SQL'];
				$stylee = 'err.html';
				}
				else
				{
				$text = $lang['THNX_REPORTED'];
				$stylee = 'info.html';
				}

				if (!$update){ die($lang['CANT_UPDATE_SQL']);}
		}
	}
	break; //=================================================
	case "rules" : //=============================[rules]
	$stylee = "rules.html";
	$titlee = $lang['RULES'];

	//get rules from txt
	$filename = "rules.txt";
	//prevent error !!
	if (filesize($filename) > 10 )
	{
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	}
	else
	{
	$contents = $lang['NO_RULES_NOW'];
	}
	$text_msg = $lang['E_RULES'];


	break; //=================================================
	case "call" : //=============================[call]

	//inlude class
	require ('includes/ocheck_class.php');
	//start ocheck class
	$ch = new ocheck;
	$ch->method = 'post';
	$ch->PathImg = 'images/code';


	if ( !isset($_POST['submit']) )
	{
	$stylee = "call.html";
	$titlee = $lang['CALL'];
	$action = "./go.php?go=call";
	$submit = $lang['SEND'];
	$L_NAME = $lang['YOURNAME'];
	$L_MAIL = $lang['EMAIL'];
	$L_TEXT = $lang['TEXT'];
	$L_CODE = $lang['VERTY_CODE'];
	$code = $ch->rand();
	$code_input = $ch->show();

	}
	else
	{

		if (empty($_POST['cname']) || empty($_POST['cmail']) || empty($_POST['ctext']) )
		{

			$text = $lang['EMPTY_FIELDS'];
			$stylee = 'err.html';

		}
		else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($_POST['cmail'])))
		{
			$text = $lang['WRONG_EMAIL'];
			$stylee = 'err.html';
		}
		else if (strlen($_POST['ctext']) > 300 )
		{
			$text = $lang['NO_ME300TEXT'];
			$stylee = 'err.html';
		}
		else if ( !$ch->result($_SESSION['ocheck']) )
		{
			$text = $lang['WRONG_VERTY_CODE'];
			$stylee = 'err.html';
		}
		else
		{
			$name = (string) $SQL->escape($_POST['cname']);
			$text = (string) $SQL->escape($_POST['ctext']);
			$mail = (string) $_POST['cmail'];
			$timee = (int) time();
			if (getenv('HTTP_X_FORWARDED_FOR')){$ip= getenv('HTTP_X_FORWARDED_FOR');
			} else {$ip=  getenv('REMOTE_ADDR');}

			$sql = "INSERT INTO `{$dbprefix}call` 	(
			`name` ,`text` ,`mail` ,`time` ,`ip`
			)
			 VALUES (
			 '$name', '$text', '$mail', '$timee', '$ip'
			 )";

			$insert = $SQL->query($sql);

			if (!$insert) {
			$text = $lang['CANT_INSERT_SQL'];
			$stylee = 'err.html';
			}
			else
			{
			$text =$lang['THNX_CALLED'];
			$stylee = 'info.html';
			}
		}
	}

	break; //=================================================
	case "down" : //=============================[down]
	//maybe ..
	function saff ($var) {
   	$var = str_replace(array('http', ':','//','/','>', '<', '.com', '.net', '.org'), '', $var);
	return $var;
	}



	if ( isset($_GET['i']) )
	{
	//for safe
	$id = intval ($_GET['i']);

	//$urlsite = $config[siteurl]; // i cant trust user :)
	$urlsite =  "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/';
	$URL = str_replace($urlsite ,'',$_SERVER['HTTP_REFERER']);
	$URL = explode('?',$URL);
	if ($URL[0] != "download.php")
	{
	header('Location: ' . $urlsite . 'download.php?id=' . $id);

	}else{//else refere


	//updates ups ..
	$update = $SQL->query("UPDATE {$dbprefix}files SET
							uploads=uploads+1,
                            last_down='". time() . "'
                            WHERE id='$id' ");
	if (!$update){ die($lang['CANT_UPDATE_SQL']);}

	//for safe !!!
	$n = saff($_GET[n]);
	$f = saff($_GET[f]);



	//start download ,,
	header("Location: ./$f/$n");

		}//elser efer

	exit(); // we doesnt need style

	}

	break; //=================================================
	case "del" : //=============================[del]

	//stop .. check first ..
	if (!$config[del_url_file])
	{
			$text = $lang['NO_DEL_F'];
			$stylee = "info.html";
			//header
			Saaheader($lang['E_DEL_F']);
			//index
			print $tpl->display($stylee);
			//footer
			Saafooter();
			exit();
	}

	//ok .. go on
	$id = intval($_GET['id']);
	$cd = $_GET['cd']; // may.. will protect

	if (!$id || !$cd )
	{
			$text =  $lang['WRONG_URL'];
			$stylee = 'err.html';
	}
	else
	{
			$sql	=	$SQL->query("SELECT name,folder FROM `{$dbprefix}files` WHERE id='".$id."' AND code_del='" . $cd . "'");

			if ($SQL->num_rows($sql) == 0) {
			$text =   $lang['CANT_DEL_F'];
			$stylee = 'err.html';
			}
			else
			{
				while($row=$SQL->fetch_array($sql)){
				@unlink ( $row[folder] . "/" . $row[name] );
				//delete thumb
				if (is_file($row[folder] . "/thumbs/" . $row[name]))
				{@unlink ( $row[folder] . "/thumbs/" . $row[name] );}
				//delete thumb
				$del = $SQL->query("DELETE FROM {$dbprefix}files WHERE 	id='" . $id . "' ");
				if (!$del) {die($lang['CANT_DELETE_SQL']);}$lang['CANT_DELETE_SQL'];
				}
				$SQL->freeresult($sql);

				$text = $lang['DELETE_SUCCESFUL'];
				$stylee = 'info.html';

			}

	}

	break; //=================================================
	/*case "example" : //=============================[example]
	$stylee = "example.html"; //>> style
	$titlee = $lang['EXAMPLE_TITLE'];  // >> title
	break; //=================================================*/
	default:
	$text = $lang['ERROR_NAVIGATATION'];
	$stylee = "err.html";
	}#end switch


	//show style ...
	//header
	Saaheader($titlee);
	//index
	print $tpl->display($stylee);
	//footer
	Saafooter();
?>
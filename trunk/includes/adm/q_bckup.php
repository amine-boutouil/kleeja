<?php
/**
*
* @package adm
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/

	
// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}

//for style ..
$stylee	= "admin_backup";
$action	= basename(ADMIN_PATH) . '?cp=' . basename(__file__, '.php');
$H_FORM_KEYS	= kleeja_add_form_key('adm_bckup');

//
// Check form key
//
if (isset($_POST['submit']))
{
	if(!kleeja_check_form_key('adm_bckup'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}


$query	= 'SHOW TABLE STATUS';
$result	= $SQL->query($query);
$i = 0;
$total_size = 0;
while($row=$SQL->fetch($result))
{
	//make new lovely arrays !!
	$size[$row['Name']]	= round($row['Data_length']/1024, 2);
	$total_size  += $row['Data_length'];
}
	
$SQL->free($result);


#total size
$total_size = Customfile_size($total_size);

//
//Use hook in admin/index.php to add your tables here
//
$tables_sho		= (isset($tables_sho)  && is_array($tables_sho)) ? $tables_sho : array();
$tables_sho[]  	= array('name' =>"{$dbprefix}config",	'size' =>$size["{$dbprefix}config"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}files",	'size' =>$size["{$dbprefix}files"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}stats",	'size' =>$size["{$dbprefix}stats"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}users",	'size' =>$size["{$dbprefix}users"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}call",		'size' =>$size["{$dbprefix}call"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}filters",		'size' =>$size["{$dbprefix}filters"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}groups",	'size' =>$size["{$dbprefix}groups"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}groups_acl",	'size' =>$size["{$dbprefix}groups_acl"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}groups_data",	'size' =>$size["{$dbprefix}groups_data"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}groups_exts",	'size' =>$size["{$dbprefix}groups_exts"]);
$tables_sho[]  	= array('name' =>"{$dbprefix}reports",	'size' =>$size["{$dbprefix}reports"]);


//after submit
if (isset($_POST['submit']))
{
	//variables
	$tables = $_POST['check'];
	$outta = '';

	//then
	foreach($tables as $table)
	{
		//clean 
		$table = preg_replace('/[^0-9a-z\-_. ]/i', '', $table);
	
		$query	= 'SHOW CREATE TABLE ' . $table . '';

		 //get code of tables ceation
		$result = $SQL->query($query);
		$que	= $SQL->fetch($result);

		//preivous code iside file
		$outta .= "\r\n# Table: " . $table . "\r\n";
		$outta .= $que['Create Table'] . ";\r\n";

		$query2	= 'SELECT * FROM ' . $table . '';

		//gets rows of table
		$result2 = $SQL->query($query2);

		$fields	= $values = array();
		while($ro = $SQL->fetch($result2))
		{
			$fields	= $values = array();
			while($res = current($ro))
			{
				$fields[] = '' . key($ro) . '';
				$values[] = "'" . str_replace("'", "\'", $res) . "'";
				next($ro);
			}

			if(is_array($fields)) 
			{
				$fields = implode(', ', $fields);
			}
			if(is_array($values))
			{
				$values = implode(', ', $values);
			}

			$q = "INSERT INTO " . $table . " ($fields) VALUES ($values);";
			$outta .= $q . "\r\n";
			
			unset($fields, $values);
		}

		$SQL->free($result);
		$SQL->free($result2);
	}

	//download now
	$sql_data = "#\n";
	$sql_data .= "# Kleeja Backup,  kleeja version : " . KLEEJA_VERSION . ", DB version : " . $config['db_version'] . "\n";
	$sql_data .= "# DATE : " . gmdate("d-m-Y H:i:s", time()) . " GMT\n";
	$sql_data .= "# Kleeja.com \n";
	$sql_data .= "#\n\n\n";

	$db_name_save = $dbname . '_' . date('dmY') . '_kleeja.sql';
	@set_time_limit(0);
	header("Content-length: " . strlen($sql_data . $outta));
	header("Content-type: text/x-sql");
	header("Content-Disposition: attachment; filename=$db_name_save");
	echo $sql_data . $outta;
	$SQL->close();
	exit;
}

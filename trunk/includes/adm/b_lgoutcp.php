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

//remove just the administator session 
if ($usrcp->logout_cp())
{
	redirect($config['siteurl']);
	$SQL->close();
	exit;
}

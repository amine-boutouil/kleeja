<?php
//lgoutcp
//part of admin extensions
//delete admin session

//copyright 2007-2009 Kleeja.com ..
//license http://opensource.org/licenses/gpl-license.php GNU Public License
//$Author: phpfalcon $ , $Rev: 737 $,  $Date:: 2009-08-07 21:26:18 +0300#$

// not for directly open
if (!defined('IN_ADMIN'))
{
	exit('no directly opening : ' . __file__);
}

//remove just the administator session 
if ($usrcp->logout_cp())
{
	redirect(basename(ADMIN_PATH));
	$SQL->close();
	exit;
}

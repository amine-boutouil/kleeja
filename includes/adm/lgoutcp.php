<?php
	//lgoutcp
	//part of admin extensions
	//delete admin session
	
	//copyright 2007-2009 Kleeja.com ..
	//license http://opensource.org/licenses/gpl-license.php GNU Public License
	//$Author$ , $Rev$,  $Date::                           $
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit('no directly opening : ' . __file__);
	}
	

	//remove just the administator session 
	if ($usrcp->logout_cp())
	{
		header('Location:./');
		$SQL->close();
		exit;
	}

?>

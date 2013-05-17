<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}
	
	
//dont change it .. please Dont !! 
$dev_m = '';
if(defined('DEV_STAGE'))
{
	$dev_verr = preg_match('!.php ([0-9]+) 2!', '$Id$', $m);
	$dev_m = '#dev' . $m[1];
}

define ('KLEEJA_VERSION' , '1.5.4' . $dev_m);
 
/*
	
	as you know , 
	kleeja is open source and free ,
	so any problem , bugs or errors  you must tell us , 
	even you are a cracker ;) . 

	http://www.kleeja.com/bugs/
	
	//wuts "kleeja" mean ? 
	you have to go wikipedia , but you will not find any thing about this word, why ? 
	because , kleeja is so so old thing ,, its type of food;  its  a sweet:) 
	you have to taste it someday ... ;)
	
	//is there any TODO list ? 
	yes , there are alots of things we are starting to add them in kleeja 
	but we are so lazy .. 
	
	
*/

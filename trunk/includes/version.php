<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


/**
 * @ignore
 */
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

define('KLEEJA_VERSION' , '2.0.0' . $dev_m);
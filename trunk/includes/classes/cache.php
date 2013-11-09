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
	exit;
}

//
//In the future here will be a real cache class 
//this codes, it's just a sample and usefull for
//some time ..
//
class cache
{

	public function __construct()
	{
		if(@extension_loaded('apc'))
		{
			define('APC_CACHE', true);
		}
	}
	
	public function get($name)
	{
		$name =  preg_replace('![^a-z0-9_]!', '_', $name);
	
		if (file_exists(PATH . 'cache/' . $name . '.php'))
		{
			include PATH . 'cache/' . $name . '.php';
			return  empty($data) ? false : $data;
		}
		else
		{
			return false;
		}
	}
	
	function exists($name)
	{
		$name =  preg_replace('![^a-z0-9_]!', '_', $name);
	
		if (file_exists(PATH . 'cache/' . $name . '.php'))
		{
			return true;
		}
	}
	
	function save($name, $data, $time = 86400)
	{		
		$name =  preg_replace('![^a-z0-9_]!i', '_', $name);
		$data_for_save = '<?' . 'php' . "\n";
		$data_for_save .= '//Cache file, generated for Kleeja at ' . gmdate('d-m-Y h:i A') . "\n\n";
		$data_for_save .= '//No direct opening' . "\n";
		$data_for_save .= '(!defined("IN_COMMON") ? exit("hacking attemp!") : null);' . "\n\n";
		$data_for_save .= '//return false after x time' . "\n";	
		$data_for_save .= 'if(time() > ' . (time() + $time) . ') return false;' . "\n\n";	
		$data_for_save .= '$data = ' . var_export($data, true) . ";\n\n//end of cache";

		if($fd = @fopen(PATH . 'cache/' . $name . '.php', 'w'))
		{
			@flock($fd, LOCK_EX); // exlusive look
			@fwrite($fd, $data_for_save);
			@flock($fd, LOCK_UN);
			@fclose($fd);
		}
		return;
	}

	function clean($name)
	{
		if(is_array($name))
		{
			foreach($name as $n)
			{
				$this->clean($n);
			}
			return;
		}

		$name =  preg_replace('![^a-z0-9_]!i', '_', $name);
		kleeja_unlink(PATH . 'cache/' . $name . '.php');
	}
}





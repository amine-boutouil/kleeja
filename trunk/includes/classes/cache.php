<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


/**
 * @ignore
 */
if (!defined('IN_COMMON'))
{
	exit;
}



class cache
{
	/**
	 * Select which cache system you want, apc or file
	 */
	public $cache_type = 'file';

	/**
	 * Initiate the cache system
	 * @return void
	 */
	public function __construct()
	{
		#If apc is aviable and 
		if(function_exists('apc_fetch') && defined('APC_CACHE'))
		{
			$this->cache_type = 'apc';
		}
	}

	/**
	 * Get cached data
	 *
	 * @param string $name The unique name of the cached data
	 * @return mixed cached data as was given or false if failed or not exists
	 */
	public function get($name)
	{
		$name =  preg_replace('![^a-z0-9_]!', '_', $name);

		if ($this->exists($name))
		{	
			if($this->cache_type == 'apc')
			{
				#get cache from apc
				$data = apc_fetch($name);
			}
			else
			{
				#get from file based cache
				include PATH . 'cache/' . $name . '.php';
			}
			
			return  empty($data) ? false : $data;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Check if the cached data exists or not
	 * 
	 * @param string $name The unique name of the cached data
	 * @return bool True if exists, false if not
	 */
	public function exists($name)
	{
		$name =  preg_replace('![^a-z0-9_]!', '_', $name);
	
		if($this->cache_type == 'apc')
		{
			#check in apc
			return apc_exists('foo');
		}
		else
		{
			#check file
			return file_exists(PATH . 'cache/' . $name . '.php');
		}
	}

	/**
	 * save data as cache
	 *
	 * @param string $name The unique name of the cached data
	 * @param mixed $data The data you want to be cached, any type you want
	 * @param int $time (optional) Time before delete
	 * @return mixed cached data as was given or false if failed or not exists
	 */
	public function save($name, $data, $time = 86400)
	{		
		$name =  preg_replace('![^a-z0-9_]!i', '_', $name);

		#if apc
		if($this->cache_type == 'apc')
		{
			return apc_store($name, $data);
		}

		#if file based
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
		return true;
	}

	/**
	 * Delete cached data
	 * 
	 * @param mixed $name The unique name of the cached data, or many names as array
	 * @return bool True if exists, false if not
	 */
	public function clean($name)
	{
		if(is_array($name))
		{
			foreach($name as $n)
			{
				$this->clean($n);
			}
			return true;
		}

		$name =  preg_replace('![^a-z0-9_]!i', '_', $name);

		#if apc
		if($this->cache_type == 'apc')
		{
			return apc_delete($name);
		}

		#else, file based
		kleeja_unlink(PATH . 'cache/' . $name . '.php');
		return true;
	}
}





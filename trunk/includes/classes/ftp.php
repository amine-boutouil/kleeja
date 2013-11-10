<?php
/**
*
* @package Kleeja
* @version $Id: pager.php 2190 2013-09-20 02:15:53Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}


/**
* Make changes with files using ftp
*/
class kftp
{
	var $handler = null;
	var $timeout = 15;
	var $root	 = '';
	var $n = 'kftp';
	var $debug = false;


	function _open($info = array())
	{
		// connect to the server
		$this->handler = @ftp_connect($info['host'], $info['port'], $this->timeout);
		//kleeja_log(var_export($info))
		if (!$this->handler)
		{
			//kleeja_log('!ftp_connect');
			return false;
		}

		// pasv mode
		@ftp_pasv($this->handler, true);

		// login to the server
		if (!ftp_login($this->handler, $info['user'], $info['pass']))
		{
			//kleeja_log('!ftp_login');
			return false;
		}

		$this->root = ($info['path'][0] != '/' ? '/' : '') . $info['path'] . ($info['path'][strlen($info['path'])-1] != '/' ? '/' : '');

		if (!$this->_chdir($this->root))
		{
			//kleeja_log('!_chdir');
			$this->_close();
			return false;
		}

		//kleeja_log('nice work kftp');
		return true;
	}

	function _close()
	{
		if (!$this->handler)
		{
			return false;
		}

		return @ftp_quit($this->handler);
	}

	function _pwd()
	{
		return ftp_pwd($this->handler);
	}

	function _chdir($dir = '')
	{
		if ($dir && $dir !== '/')
		{
			if (substr($dir, -1, 1) == '/')
			{
				$dir = substr($dir, 0, -1);
			}
		}

		return @ftp_chdir($this->handler, $dir);
	}
	
	function _chmod($file, $perm = 0644)
	{
		if (function_exists('ftp_chmod'))
		{
			$action = @ftp_chmod($this->handler, $perm, $this->_fixpath($file));
		}
		else
		{
			$chmod_cmd = 'CHMOD ' . base_convert($perm, 10, 8) . ' ' . $this->_fixpath($file);
			$action = $this->_site($chmod_cmd);
		}
		return $action;
	}
	
	function _site($cmd)
	{
		return @ftp_site($this->handler, $cmd);
	}
	
	function _delete($file)
	{
		return @ftp_delete($this->handler, $this->_fixpath($file));
	}

	function _write($filepath, $content)
	{
		
		$fnames = explode('/', $filepath);
		$filename = array_pop($fnames);
		$extension = strtolower(array_pop(explode('.', $filename)));
		$path = dirname($fnames);
		$cached_file = PATH . 'cache/plg_system_' . $filename;

		//make it as a cached one
		$h = @fopen($cached_file, 'wb');
		fwrite($h, $content);
		@fclose($h);
	
		if(in_array($extension, array('gif', 'jpg', 'png')))
		{
			$mode = FTP_BINARY;
		}
		else
		{
			$mode = FTP_ASCII;
		}

		$this->_chdir($this->_fixpath($path));

		$r = @ftp_put($this->handler, $filename, $this->_fixpath($cached_file), $mode);
		$this->_chdir($this->root);
		
		kleeja_unlink($cached_file);
		
		return $r;
	}
	
	function _rename($old_file, $new_file)
	{
		return @ftp_rename($this->handler, $this->_fixpath($old_file), $this->_fixpath($new_file));
	}
	
	
	function _mkdir($dir, $perm = 0777)
	{
		return @ftp_mkdir($this->handler, $this->_fixpath($dir));
	}
	
	function _rmdir($dir)
	{
		return @ftp_rmdir($this->handler, $this->_fixpath($dir));
	}

	function _fixpath($path)
	{
		return $this->root . str_replace(PATH, '', $path);
	}
}


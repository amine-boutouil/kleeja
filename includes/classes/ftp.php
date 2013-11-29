<?php
/**
*
* @package Kleeja
* @version $Id: pager.php 2190 2013-09-20 02:15:53Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


/**
 * @ignore
 */
if (!defined('IN_COMMON'))
{
	exit();
}


/**
 * Make changes with files using FTP
 */
class kleeja_ftp
{
	/**
	 * FTP current connection handler
	 */
	private $handler = null;
	/**
	 * TimeOut before disconnction
	 */
	public $timeout = 15;
	/**
	 * Move to this folder after connection
	 */
	public $root	 = '';
	/**
	 * If enabled, debug mode will be activated
	 */
	public $debug = false;


	/**
	 * Connect to FTP server
	 *
	 * @param string $host FTP server address
	 * @param string $user FTP server username
	 * @param string $password FTP server password
	 * @param int $port FTP server port
	 * @param string $path FTP server path
	 * @return bool
	 */
	public function open($host, $user, $password, $port = 21, $path = '/')
	{
		#connect to the server
		$this->handler = @ftp_connect($host, $port, $this->timeout);

		if (!$this->handler)
		{
			return false;
		}

		#pasv mode
		@ftp_pasv($this->handler, true);

		#login to the server
		if (!ftp_login($this->handler, $user, $password))
		{
			return false;
		}

		#move to the path
		$this->root = ($path[0] != '/' ? '/' : '') . $path . ($info['path'][strlen($path)-1] != '/' ? '/' : '');

		if (!$this->goto($this->root))
		{
			$this->close();
			return false;
		}

		return true;
	}

	/**
	 * Close current FTP connection
	 * @return bool
	 */
	public function close()
	{
		if (!$this->handler)
		{
			return false;
		}

		return @ftp_quit($this->handler);
	}

	/**
	 * Get the current folder that we are in now
	 * @return string
	 */
	public function current_folder()
	{
		return ftp_pwd($this->handler);
	}

	/**
	 * Go to the given folder 
	 * @return bool
	 */
	public function goto($dir = '')
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

	/**
	 * Change the file or folder permssion
	 * @return bool
	 */
	public function chmod($file, $perm = 0644)
	{
		if (function_exists('ftp_chmod'))
		{
			$action = @ftp_chmod($this->handler, $perm, $this->_fixpath($file));
		}
		else
		{
			$chmod_cmd = 'CHMOD ' . base_convert($perm, 10, 8) . ' ' . $this->_fixpath($file);
			$action = ftp_site($chmod_cmd);
		}
		return $action;
	}


	/**
	 * Delete given file
	 * @return bool
	 */
	public function delete($file)
	{
		return @ftp_delete($this->handler, $this->_fixpath($file));
	}

	/**
	 * Create a file and write the given content to it
	 * @return bool
	 */
	public function write($filepath, $content)
	{	
		$fnames = explode('/', $filepath);
		$filename = array_pop($fnames);
		$extension = strtolower(array_pop(explode('.', $filename)));
		$path = dirname($fnames);
		$cached_file = PATH . 'cache/cached_ftp_' . $filename;

		#make it as a cached file
		$h = @fopen($cached_file, 'wb');
		fwrite($h, $content);
		@fclose($h);

		$mode = in_array($extension, array('gif', 'jpg', 'png') ? FTP_BINARY : FTP_ASCII;

		$this->goto($this->_fixpath($path));

		$r = @ftp_put($this->handler, $filename, $this->_fixpath($cached_file), $mode);
		$this->goto($this->root);

		kleeja_unlink($cached_file);

		return $r;
	}

	/**
	 * Upload a local file to the FTP server
	 * @return bool
	 */
	public function upload($local_file, $server_file)
	{		
		$extension = strtolower(array_pop(explode('.', $local_file)));
		$mode = in_array($extension, array('gif', 'jpg', 'png') ? FTP_BINARY : FTP_ASCII;
	
		#Initate the upload
		$ret = ftp_nb_fput($this->handler, $server_file, $local_file, $mode);
		while ($ret == FTP_MOREDATA)
		{
			 #still uploading
			print ftell ($fh)."\n";
			$ret = ftp_nb_continue($this->handler);
		}
		#bad uploading
		if ($ret != FTP_FINISHED)
		{
			return false;
		}
		return true;
	}

	/**
	 * Rename a file
	 * @return bool
	 */
	public function rename($old_file, $new_file)
	{
		return @ftp_rename($this->handler, $this->_fixpath($old_file), $this->_fixpath($new_file));
	}
	
	/**
	 * Cretate a folder
	 * @return bool
	 */
	public function create_folder($dir, $perm = 0777)
	{
		return @ftp_mkdir($this->handler, $this->_fixpath($dir));
	}
	
	/**
	 * Delete the given folder
	 * @return bool
	 */
	public function delte_folder($dir)
	{
		return @ftp_rmdir($this->handler, $this->_fixpath($dir));
	}

	/**
	 * fix the given path to be compatible with the FTP
	 * @return string
	 */
	private function _fixpath($path)
	{
		return $this->root . str_replace(PATH, '', $path);
	}
}


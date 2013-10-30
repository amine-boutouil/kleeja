<?php
/**
*
* @package Kleeja
* @version $Id: autoupdate.php 2123 2013-01-21 10:19:42Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/
//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}


@set_time_limit(0); 


/* get kftp, zfile class */
include PATH . 'includes/classes/ftp.php'; 
include PATH . 'includes/classes/zip.php'; 


/**
* Kleeja auto update system
* @package Kleeja
*/
class kupdate
{

	//everytime we install update
	//we ask user for this..
	var $info				= array();
	var $f_method 			= 'zfile';
	var $f					= null;
	var $is_ftp_supported 	= true;
	var $zipped_files		= '';

	function kupdate()
	{
		#last choice, ftp ..
		if (@extension_loaded('ftp'))
		{
			$this->is_ftp_supported = true;
		}
	}

	function check_connect()
	{
		$this->f = new $this->f_method;
	}

	function atend()
	{
		if(!empty($this->f))
		{
			$this->f->_close();
		}
	}

	function check_what_method()
	{
		global $dbprefix, $SQL, $config;

		return get_config('upg_f_method');
	}

	function save_f_method($method)
	{
		global $dbprefix, $SQL, $config;

		if(!get_config('upg_f_method'))
		{
			return add_config('upg_f_method', $method);
		}
		else
		{
			return update_config('upg_f_method', $method);
		}
	}

	//update core
	function update_core($step = '1', $v)
	{
		global $dbprefix, $SQL, $lang, $config;

		$ftp = $this->check_what_method();
		//$this->f_method = 'zfile';  //standard

		if($ftp && $this->is_ftp_supported)
		{
			$this->f_method = 'kftp';

			if(!empty($this->info))
			{
				$this->info = $this->info;
			}
			else if(!empty($config['ftp_info']))
			{
				$ftp_info = @unserialize($config['ftp_info']);
				$this->info = $ftp_info;
			}
			else //no info
			{
				$this->f_method = 'zfile'; //return to file
			}
		}

		$this->check_connect();

		switch($step)
		{
			
			case '1': //....... download files
				# code...
				if(file_exists(PATH . $config['foldername'] . '/' . 'aupdatekleeja' . $v . '.tar'))
				{
					return true;
				}

				$b_url	= empty($_SERVER['SERVER_NAME']) ? $config['siteurl'] : $_SERVER['SERVER_NAME'];
				$data = fetch_remote_file('http://www.kleeja.com/check_vers2/?i=' . urlencode($b_url));

				if($data != false)
				{
					//then ..write new file
					$re = $this->plg->_write(PATH . $config['foldername'] . '/' . 'aupdatekleeja' . $v . '.tar', $data);

					if($this->f->check())
					{
				
						$this->zipped_files = $this->f->push('aupdate' . $v);
						return 'zipped';
											
					}

					return $re;
				}					
				else
				{
					return false;
				}
				break;
			case '2':   //...... extract / untar
				# code...
				break;
			case '3':   //...... database
				# code...
				break;
			case '4':   //...... finish delete temps show results 
				# code...
				break;
		}
	}


	//
	/**
	 *
	 * We need to develop this .. add ftp - etc
	 * 
	 * Simple script to extract files from a .tar archive
	 *
	 * @param string $file The .tar archive filepath
	 * @param string $dest [optional] The extraction destination filepath, defaults to "./"
	 * @return boolean Success or failure
	 */
	
	function untar($file, $dest = "./") 
	{
		if (!is_readable($file))
		{
		 	return false;
		 }

		$filesize = @filesize($file);
		
		// Minimum 4 blocks
		if ($filesize <= 512*4)
		{ 
			return false;
		}
		
		if (!preg_match("/\/$/", $dest)) 
		{
			// Force trailing slash
			$dest .= "/";
		}
		
		//Ensure write to destination
		/*if (!file_exists($dest)) 
		{
			if (!mkdir($dest, 0777, true)) 
			{
				return false;
			}
		}*/ 
		
		$total = 0;

		if($fh = @fopen($file, 'rb'))
		{
			$files = array();
			while (($block = fread($fh, 512)) !== false) 
			{
			
				$total += 512;
				$meta = array();
				
				// Extract meta data
				// http://www.mkssoftware.com/docs/man4/tar.4.asp
				$meta['filename'] = trim(substr($block, 0, 99));
				$meta['mode'] = octdec((int)trim(substr($block, 100, 8)));
				$meta['userid'] = octdec(substr($block, 108, 8));
				$meta['groupid'] = octdec(substr($block, 116, 8));
				$meta['filesize'] = octdec(substr($block, 124, 12));
				$meta['mtime'] = octdec(substr($block, 136, 12));
				$meta['header_checksum'] = octdec(substr($block, 148, 8));
				$meta['link_flag'] = octdec(substr($block, 156, 1));
				$meta['linkname'] = trim(substr($block, 157, 99));
				$meta['databytes'] = ($meta['filesize'] + 511) & ~511;
			
				if ($meta['link_flag'] == 5)  //folder
				{
					// Create folder
					$this->f->_mkdir($dest . $meta['filename'], 0777);
					$this->f->_chmod($dest . $meta['filename'], $meta['mode']);
				}
			
				if ($meta['databytes'] >= 0 && $meta['header_checksum'] != 0)  //files
				{
					$block = @fread($fh, $meta['databytes']);
					// Extract data
					$data = substr($block, 0, $meta['filesize']);

					$this->f->_write($dest . $meta['filename'], $data);  //write

					if ($meta['mode'] == 0744)
					{
						$meta['mode'] = 0644;
					}

					$this->f->_chmod($dest . $meta['filename'], $meta['mode']); //privileges stuff  

					/*
					// Write data and set permissions
					if (false !== ($ftmp = @fopen($dest . $meta['filename'], 'wb'))) 
					{
						@flock($ftmp, LOCK_EX); // exlusive look
						@fwrite($ftmp, $data);
						@fclose($ftmp);
						//@touch($dest . $meta['filename'], $meta['mtime'], $meta['mtime']);
					
						if ($meta['mode'] == 0744)
						{
							$meta['mode'] = 0644;
						}
					
						@chmod($dest . $meta['filename'], $meta['mode']);
					}
					*/
				
					$total += $meta['databytes'];
					$files[] = $meta;
					
				}


			
				if ($total >= $filesize-1024) 
				{
					//Yayy !! .. extracted everything , now delete the archive
					kleeja_unlink($file);
					return $files;
				}
			}
		}

		return false;
	}

}




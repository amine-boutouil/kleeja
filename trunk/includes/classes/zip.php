<?php
/**
*
* @package Kleeja
* @version $Id: pager.php 2190 2013-09-20 02:15:53Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
*
*/


//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}



/**
* It's not a real method, it's just for save files changes
* @package Kleeja
*/
class zfile
{
	var $handler = null;
	var $files = array();
	var $n = 'zfile';

	#no need to open or close this handler .. 
	function _open($info = array()){ return true; }
	function _close() { return true; }

	function _write($filepath, $content)
	{
		//isnt that simple ? ya it is.
		//see last function here.
		$this->files[$filepath] = $content;
	}

	function _delete($filepath)
	{
		//
		// best way is tell his that directly .. i have alot of ideas
		// just we have wait ..  
		//
		return true;
	}
	
	function _rename($oldfile, $newfile)
	{
		//see _delete
		//or, just we can give him a new file in zip ? good idea
		return true;
	}
	
	function _chmod($filepath, $perm = 0644)
	{
		//tell me why 'false', well if it returning false that mean
		//we will trying other methods and not relying on it .. so 
		//it's a wise call.
		return false;
	}

	function _mkdir($dir, $perm = 0777)
	{
		//kleeja figure out in zip class itself that,
		//so no need for standing here and yelling waiting
		//the zip class listen, it is already did that .. 
		return true;
	}
	function _rmdir($dir)
	{
		//who cares ? 
		//the most important part is creating folders or
		//editing files .. deleting in zip is not helping
		//or let say it's crazy to think of it.
		return true;
	}

	function check()
	{
		//let tell kleeja that there is files here 
		//so give the user the ability to download them and apply
		//the changes to the root folder.
		return @sizeof($this->files) ? true : false;
	}
	
	function push($plg_name)
	{
		$z = new zipfile;

		foreach($this->files as $filepath => $content)
		{
			$z->create_file($content, str_replace(PATH, '', $filepath));
		}

		$ff = md5($plg_name);

		//save file to cache and return the cached file name
		$c = $z->zipped_file();
		$fn = @fopen(PATH . 'cache/changes_of_' . $ff . '.zip', 'w');
		fwrite($fn, $c);
		fclose($fn);

		return $ff;
	}
	
}




/**
*	zipfile class for writing .zip files
*	Copyright (C) Joshua Townsend (http://www.gamingg.net)
*	Based on tutorial given by John Coggeshall
*	@edited on 2010 By Kleeja team
*/
class zipfile
{
	//container variables
	var $datasec= array(), $dirs = array(), $ctrl_dir = array();
	//end of Central directory record
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00"; 
	var $old_offset = 0;
	var $basedir = '.';

	function create_dir($name, $echo = false)
	{
		$name = str_replace("\\", "/", $name);
		$fr = "\x50\x4b\x03\x04" . "\x0a\x00" . "\x00\x00" . "\x00\x00" . "\x00\x00\x00\x00" . pack("V",0). pack("V",0) . pack("V",0) . pack("v", strlen($name)) . pack("v", 0) . $name . pack("V",0) . pack("V",0) .pack("V",0);
		$this->datasec[] = $fr;
		//output now !
		if($echo)
			echo $fr;
		$new_offset = strlen(implode('', $this->datasec));
		// now add to central record
		$cdrec = "\x50\x4b\x01\x02" . "\x00\x00" . "\x0a\x00" . "\x00\x00". "\x00\x00" . "\x00\x00\x00\x00" . pack("V",0) . pack("V",0) . pack("V",0) . pack("v", strlen($name)) . pack("v", 0) .  pack("v", 0) . pack("v", 0) . pack("v", 0) . pack("V", 16) . pack("V", $this->old_offset) . $name;
		$this->old_offset = $new_offset;
		$this->ctrl_dir[] = $cdrec;
		$this->dirs[] = $name;
	}

	function check_file_path($filepath)
	{
		// todo : check dir and create them
		// path/path2/path3/filename.ext
		// here there is 3 folder, so you have to make them
		// before creating file
		return true;
	}

	function create_file($data, $name, $echo = false)
	{
		$name = str_replace("\\", "/", $name);
		$fr = "\x50\x4b\x03\x04". "\x14\x00" . "\x00\x00" . "\x08\x00" . "\x00\x00\x00\x00"; 
		$unc_len = strlen($data);
		$crc = crc32($data);
		$zdata =  substr(gzcompress($data), 2, -4);
		$c_len = strlen($zdata);
		$fr .= pack("V",$crc) . pack("V",$c_len) . pack("V",$unc_len) . pack("v", strlen($name)) .  pack("v", 0). $name . $zdata .  pack("V",$crc) . pack("V",$c_len) . pack("V",$unc_len);
		$this->datasec[] = $fr;
		$new_offset = strlen(implode("", $this->datasec));
		//output now !
		if($echo)
			echo $fr;
		// now add to central directory record
		$cdrec = "\x50\x4b\x01\x02" . "\x00\x00" . "\x14\x00" . "\x00\x00" . "\x08\x00" . "\x00\x00\x00\x00" . pack("V",$crc) . pack("V",$c_len) . pack("V",$unc_len) . pack("v", strlen($name) ). pack("v", 0 ) . pack("v", 0 ) . pack("v", 0 ) . pack("v", 0 ) . pack("V", 32 ) . pack("V", $this->old_offset) . $name;
		$this->old_offset = $new_offset;
		$this->ctrl_dir[] = $cdrec;
	}

	function zipped_file($d = true)
	{
		$data = implode('', $this->datasec);
		$ctrldir = implode('', $this->ctrl_dir);
		return 	($d ? $data : null) . $ctrldir . $this->eof_ctrl_dir . pack("v", sizeof($this->ctrl_dir)). pack("v", sizeof($this->ctrl_dir)). pack("V", strlen($ctrldir)) . pack("V", strlen($data)) . "\x00\x00";
	}
}


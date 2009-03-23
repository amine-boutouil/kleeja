<?php
##################################################
#						Kleeja
#
# Filename : s_strings.php
# purpose : find strings in texrt and add after in another text or replace it ..
# copyright 2007-2008 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : saanina
##################################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}

//by : saanina@gmail.om  as experince .. not final version .
// do_search (numbers) : 
// 1 : to find and replace 
// 2 : find and replace which between to sentences 
// 3 : find and add after in new line 
// 4 : find and add after in the same line
// 5 : find and add before in new line 
// 6 : find and add before in the same liner

//more discussions about this file , mail me at saanina@gmail.com 
// for real discuss , and in http://moffed.com
// for try use the code in the end of  this file ;) 

class sa_srch
{

	var $text			=	''; 
	var $find_word		=	''; 
	var $another_word	=	''; 

	
	/*
	initiat class
	*/
	function do_search($type_of_do=1)
	{
		if($this->find_word == '' || $this->text == '')
		{
			return false;
		}
		
		switch($type_of_do):
			case 1: 
				$this->type_replace();
			break;
			case 2: 
				$this->type_replace(1);
			break;
			case 3: 
				$this->type_after();
			break;
			case 4: 
				$this->type_after(1);
			break;
			case 5: 
				$this->type_before();
			break;
			case 6: 
				$this->type_before(1);
			break;
		endswitch;
	
	}

	/*
	find and replace
	*/
	function type_replace($many = false)
	{	
		
		if($this->another_word == '')
		{
			return false;
		}
		
		if($many)
		{
			if(!is_array($this->find_word))
			{
				return false;
			}
			
			$this->text	=	preg_replace('/' . preg_quote($this->find_word[0] . '(.*?)' . $this->find_word[1], '/') . '/', $this->another_word, $this->text);
		}
		else
		{

			$this->text	=	preg_replace('/' . preg_quote($this->find_word, '/') . '/', $this->another_word, $this->text);
		}
	
	}
	
	/*
	find and add after 
	*/
	function type_after($same_line=false)
	{
		if($this->another_word == '')
		{
			return false;
		}
		
		$this->text	=	preg_replace('/' . preg_quote($this->find_word, '/')  . '/', $this->find_word . (($same_line) ? "\r\n" : "") . $this->another_word, $this->text);

	}
	
	/*
	find and add before 
	*/
	function type_before($same_line=false)
	{
		if($this->another_word == '')
		{
			return false;
		}
		
		$this->text	=	preg_replace('/' . preg_quote($this->find_word, '/') . '/',   $this->another_word . (($same_line) ? "\r\n" : "")  .$this->find_word, $this->text);

	}
}


?>
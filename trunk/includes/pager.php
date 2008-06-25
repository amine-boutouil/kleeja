<?php
##################################################
#						Kleeja 
#
# Filename : common.php 
# purpose :  pages system 
# copyright 2007-2008 Kleeja.com ..
# Author : Based on class from phpclasses.org
# last edit by : saanina
##################################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit;
}

class SimplePager
{ 

    var $totalPages; 
    var $startRow; 
	var $currentPage;
	
	
    function SimplePager($rowsPerPage,$numRows,$currentPage=1)
	{ 
        // Calculate the total number of pages 
        $this->totalPages	=	ceil($numRows/$rowsPerPage); 
		
        // Check that a valid page has been provided 
        if ($currentPage < 1) 
		{
            $currentPage	=	1; 
		}
        else if ($currentPage > $this->totalPages) 
        {
			$currentPage=$this->totalPages;
		}
		
        // Calculate the row to start the select with 
        $this->startRow=(($currentPage - 1) * $rowsPerPage); 
		$this->currentPage = $currentPage;
    } 
	
    function getTotalPages ()
	{
		return $this->totalPages;
	} 
	
    function getStartRow()
	{
		return $this->startRow;
	} 
	
	function print_nums($link)
	{
		global $lang;
			if($this->totalPages < 2) return;
			$re = ($this->currentPage>1) ? '<a href="'.$link.'&amp;page='.($this->currentPage-1).'" class=paging>'. $lang['PREV'] .'</a> ': '';
			for($s=1;$s<$this->totalPages+1;$s++){$re .= ($this->currentPage==$s)?"<span class=here_psge>$s</span>":"<a href=".$link."&amp;page=$s class=paging>$s</a> ";}
			$re .= ($this->currentPage<$this->totalPages) ? ' <a href="'.$link.'&amp;page='.($this->currentPage+1).'" class=paging>'. $lang['NEXT'] .'</a>': '';
		return $re;
	}
} 
?>
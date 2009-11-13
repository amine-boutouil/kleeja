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

class SimplePager
{
    var $totalPages, $startRow , $currentPage;

	/**
	*Construct function ..
	*
	*/
    function SimplePager($rowsPerPage, $numRows, $currentPage = 1)
	{ 
        // Calculate the total number of pages 
        $this->totalPages = ceil($numRows/$rowsPerPage); 

        // Check that a valid page has been provided 
		$this->currentPage = $currentPage < 1 ? 1 :  ($currentPage > $this->totalPages ? $this->totalPages : $currentPage); 

        // Calculate the row to start the select with 
        $this->startRow = ($this->currentPage - 1) * $rowsPerPage; 
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
		global $lang, $config;

		//if no page
		if($this->totalPages <= 1)
		{
			return;
		}

		$re = '<div class="pagination">';

		// Add a previous page link
		if ($this->totalPages > 1 && $this->currentPage > 1)
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a class="disabled" href="' . $link . '-' . ($this->currentPage-1) . '.html">'. $lang['PREV'] .'</a>' : $re .= '<a class="disabled" href="' . $link . '&amp;page=' . ($this->currentPage-1) . ' ">'. $lang['PREV'] .'</a>';

		if ($this->currentPage > 3)		
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a href="' . $link . '-1.html">1</a>' . (($this->currentPage > 5) ? '<span class="three_dots">...</span>' : '') : $re .= '<a href="' . $link . '&amp;page=1">1</a>' . (($this->currentPage > 5) ? '<span class="three_dots">...</span>' : '');

		// Don't ask me how the following works. It just does, OK? :-)
		for ($current = ($this->currentPage == 5) ? $this->currentPage - 3 : $this->currentPage - 2, $stop = ($this->currentPage + 4 == $this->totalPages) ? $this->currentPage + 4 : $this->currentPage + 3; $current < $stop; ++$current)
		{
			if ($current < 1 || $current > $this->totalPages)
				continue;
			else if ($current != $this->currentPage)
				($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a href="'. $link .'-'.($current).'.html">'. $current .'</a>' : $re .= '<a href="'. $link .'&amp;page='.($current).'">'. $current .'</a>';
			else
				$re .= '<span class="current">'. $current .'</span>';
		}

		if ($this->currentPage <= ($this->totalPages-3))
		{
			if ($this->currentPage != ($this->totalPages-3) && $this->currentPage != ($this->totalPages-4))
				$re .= '<span class="three_dots">...</span>';
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a href="' . $link . '-' . ($this->totalPages) . '.html">'. $this->totalPages .'</a>' : $re .= '<a href="' . $link . '&amp;page=' . ($this->totalPages) . '" >'. $this->totalPages .'</a>';
		}

		// Add a next page link
		if ($this->totalPages > 1 && $this->currentPage < $this->totalPages)
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a class="next" href="' . $link . '-' . ($this->currentPage+1) . '.html">'. $lang['NEXT'] .'</a>' :  $re .= '<a class="next" href="' . $link . '&amp;page=' . ($this->currentPage+1) . '">'. $lang['NEXT'] .'</a>';

		$re .= '</div>'; 

		return $re;
	}
}

#<-- EOF
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

		$re = '<table class="pagination"><tr>';

		// Add a previous page link
		if ($this->totalPages > 1 && $this->currentPage > 1)
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<td class="disablelink"><a href="' . $link . '-' . ($this->currentPage-1) . '.html">'. $lang['PREV'] .'</a></td>' : $re .= '<td class="disablelink"><a href="' . $link . '&amp;page=' . ($this->currentPage-1) . ' ">'. $lang['PREV'] .'</a></td>';

		if ($this->currentPage > 3)		
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<td><a href="' . $link . '-1.html">1</a></td>' . (($this->currentPage > 5) ? '<td class="three_dots"><span>...</span></td>' : '') : $re .= '<td><a href="' . $link . '&amp;page=1">1</a></td>' . (($this->currentPage > 5) ? '<td class="three_dots"><span>...</span></td>' : '');

		// Don't ask me how the following works. It just does, OK? :-)
		for ($current = ($this->currentPage == 5) ? $this->currentPage - 3 : $this->currentPage - 2, $stop = ($this->currentPage + 4 == $this->totalPages) ? $this->currentPage + 4 : $this->currentPage + 3; $current < $stop; ++$current)
		{
			if ($current < 1 || $current > $this->totalPages)
				continue;
			else if ($current != $this->currentPage)
				($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<td><a href="'. $link .'-'.($current).'.html">'. $current .'</a></td>' : $re .= '<td><a href="'. $link .'&amp;page='.($current).'">'. $current .'</a></td>';
			else
				$re .= '<td class="currentpage">'. $current .'</td>';
		}

		if ($this->currentPage <= ($this->totalPages-3))
		{
			if ($this->currentPage != ($this->totalPages-3) && $this->currentPage != ($this->totalPages-4))
				$re .= '<td class="three_dots"><span>...</span></td>';
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<td><a href="' . $link . '-' . ($this->totalPages) . '.html">'. $this->totalPages .'</a></td>' : $re .= '<td><a href="' . $link . '&amp;page=' . ($this->totalPages) . '" >'. $this->totalPages .'</a></td>';
		}

		// Add a next page link
		if ($this->totalPages > 1 && $this->currentPage < $this->totalPages)
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<td class="prevnext"><a href="' . $link . '-' . ($this->currentPage+1) . '.html">'. $lang['NEXT'] .'</a></td>' :  $re .= '<td class="prevnext"><a href="' . $link . '&amp;page=' . ($this->currentPage+1) . '">'. $lang['NEXT'] .'</a></td>';

		$re .= '</tr></table>'; 

		return $re;
	}
}

#<-- EOF
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

	function print_nums($link, $link_plus = '')
	{
		global $lang, $config;

		//if no page
		if($this->totalPages <= 1)
		{
			return;
		}

		$link_plus .= $link_plus != '' ? ' ' : '';

		$re = '<ul class="pagination">';

		// Add a previous page link
		if ($this->totalPages > 1 && $this->currentPage > 1)
		{
			$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a href="' . $link . '-' . ($this->currentPage-1) . '.html"' . $link_plus . '>' . $lang['PREV'] . '</a><li>' : '<li><a href="' . $link . '&amp;page=' . ($this->currentPage-1) . '"' . $link_plus . '>' . $lang['PREV'] . '</a><li>';
		}
		if ($this->currentPage > 3)
		{
			$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a href="' . $link . '-1.html"' . $link_plus . '>1</a></li>' . (($this->currentPage > 5) ? '<li><a href="#">...</a></li>' : '') : '<li><a href="' . $link . '&amp;page=1"' . $link_plus . '>1</a></li>' . (($this->currentPage > 5) ? '<li><a href="#">...</a></li>' : '');
		}

		for ($current = ($this->currentPage == 5) ? $this->currentPage - 3 : $this->currentPage - 2, $stop = ($this->currentPage + 4 == $this->totalPages) ? $this->currentPage + 4 : $this->currentPage + 3; $current < $stop; ++$current)
		{
			if ($current < 1 || $current > $this->totalPages)
			{
				continue;
			}
			else if ($current != $this->currentPage)
			{
				$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a href="' . $link . '-' . $current . '.html"' . $link_plus . '>' . $current . '</a></li>' : '<li><a href="' . $link . '&amp;page=' . $current . '"' . $link_plus . '>' . $current . '</a></li>';
			}
			else
			{
				$re .= '<li class="active"><a>' . $current . '</a></li>';
			}
		}

		if ($this->currentPage <= ($this->totalPages-3))
		{
			if ($this->currentPage != ($this->totalPages-3) && $this->currentPage != ($this->totalPages-4))
			{
				$re .= '<li><a href="#">...</a></li>';
			}

			$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a href="' . $link . '-' . $this->totalPages . '.html"' . $link_plus . '>' . $this->totalPages . '</a></li>' : '<li><a class="paginate" href="' . $link . '&amp;page=' . $this->totalPages . '"' . $link_plus . '>' . $this->totalPages . '</a></li>';
		}

		// Add a next page link
		if ($this->totalPages > 1 && $this->currentPage < $this->totalPages)
		{
			$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a  href="' . $link . '-' . ($this->currentPage+1) . '.html"' . $link_plus . '>' . $lang['NEXT'] . '</a></li>' :  '<li><a href="' . $link . '&amp;page=' . ($this->currentPage+1) . '"' . $link_plus . '>' . $lang['NEXT'] . '</a></li>';
		}

		$re .= '</ul>'; 

		return $re;
	}
}

#<-- EOF

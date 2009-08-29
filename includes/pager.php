<?php
##################################################
#						Kleeja 
#
# Filename : pager.php 
# purpose :  pagination system 
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author$ , $Rev$,  $Date::                           $
##################################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
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
		if($this->totalPages <= 1) return;

		$re = '<div class="pagination">';

		// Add a previous page link
		if ($this->totalPages > 1 && $this->currentPage > 1)
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a class="paging" href="' . $link . '-' . ($this->currentPage-1) . '.html">'. $lang['PREV'] .'</a>' : $re .= '<a class="paging" href="' . $link . '&amp;page=' . ($this->currentPage-1) . '">'. $lang['PREV'] .'</a>';

		if ($this->currentPage > 3)		
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a class="paging" href="' . $link . '-1.html">1</a>' . (($this->currentPage > 5) ? '...' : '') : $re .= '<a class="paging" href="' . $link . '&amp;page=1">1</a>' . (($this->currentPage > 5) ? '...' : '');

		// Don't ask me how the following works. It just does, OK? :-)
		for ($current = ($this->currentPage == 5) ? $this->currentPage - 3 : $this->currentPage - 2, $stop = ($this->currentPage + 4 == $this->totalPages) ? $this->currentPage + 4 : $this->currentPage + 3; $current < $stop; ++$current)
		{
			if ($current < 1 || $current > $this->totalPages)
				continue;
			else if ($current != $this->currentPage)
				($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a href="'. $link .'-'.($current).'.html" class="paging">'. $current .'</a>' : $re .= '<a href="'. $link .'&amp;page='.($current).'" class="paging">'. $current .'</a>';
			else
				$re .= '<strong class="here_page">'. $current .'</strong>';
		}

		if ($this->currentPage <= ($this->totalPages-3))
		{
			if ($this->currentPage != ($this->totalPages-3) && $this->currentPage != ($this->totalPages-4))
				$re .= '...';
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a href="' . $link . '-' . ($this->totalPages) . '.html"  class="paging">'. $this->totalPages .'</a>' : $re .= '<a href="' . $link . '&amp;page=' . ($this->totalPages) . '"  class="paging">'. $this->totalPages .'</a>';
		}

		// Add a next page link
		if ($this->totalPages > 1 && $this->currentPage < $this->totalPages)
			($config['mod_writer'] && !defined('IN_ADMIN')) ? $re .= '<a class="paging" href="' . $link . '-' . ($this->currentPage+1) . '.html">'. $lang['NEXT'] .'</a>' :  $re .= '<a class="paging" href="' . $link . '&amp;page=' . ($this->currentPage+1) . '">'. $lang['NEXT'] .'</a>';

		$re .= '</div>'; 

		return $re;
	}
}

#<-- EOF
<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license ./docs/license.txt
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
 * Class resposible for pagination
 */
class pagination
{
	/**
	 * The total number of pages result from the given rows
	 * @var int
	 */
	private $total_pages;
	
	/**
	 * The current row to begin the pagination from
	 * @var int
	 */
	private $start_row;

	/**
	 * The current page
	 * @var int
	 */
	private $current_page;

	/**
	 * Initiating function
	 *
	 * @param int $rows_per_page How many rows you want in one page
	 * @param int $num_rows The number of real rows
	 * @param int $current_page The current page  
	 */
	public function __construct($rows_per_page, $num_rows, $current_page = 1)
	{ 
		#Calculate the total number of pages 
		$this->total_pages = ceil($num_rows / $rows_per_page); 

		#Check that a valid page has been provided 
		$this->current_page = $current_page < 1 ? 1 :  ($current_page > $this->total_pages ? $this->total_pages : $current_page); 

		#Calculate the row to start the select with 
		$this->start_row = ($this->current_page - 1) * $rows_per_page; 
	}

	/**
	 * Get the total pages number
	 */
	public function get_total_pages()
	{
		return $this->total_pages;
	} 

	/**
	 * Get the start row, the row you want to begin the query from
	 */
	public function get_start_row()
	{
		return $this->start_row;
	}

	/**
	 * print the page numbers with urls
	 *
	 * @param string $link the current link of the page to use in links of page numbers
	 * @param string $link_plus [optional] Any extra string to be included in the end of a tag
	 */
	public function print_nums($link, $link_plus = '')
	{
		global $lang, $config;

		#if no pages
		if($this->total_pages <= 1)
		{
			return '';
		}

		$link_plus = $link_plus != '' ? ' ' . $link_plus : '';

		$re = '<ul class="pagination">';

		#Add a previous page link
		if ($this->total_pages > 1 && $this->current_page > 1)
		{
			$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a href="' . $link . '-' . ($this->current_page-1) . '.html"' . $link_plus . '>' . $lang['PREV'] . '</a><li>' : '<li><a href="' . $link . '&amp;page=' . ($this->current_page-1) . '"' . $link_plus . '>' . $lang['PREV'] . '</a><li>';
		}
		if ($this->current_page > 3)
		{
			$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a href="' . $link . '-1.html"' . $link_plus . '>1</a></li>' . (($this->current_page > 5) ? '<li><a href="#">...</a></li>' : '') : '<li><a href="' . $link . '&amp;page=1"' . $link_plus . '>1</a></li>' . (($this->current_page > 5) ? '<li><a href="#">...</a></li>' : '');
		}

		for ($current = ($this->current_page == 5) ? $this->current_page - 3 : $this->current_page - 2, $stop = ($this->current_page + 4 == $this->total_pages) ? $this->current_page + 4 : $this->current_page + 3; $current < $stop; ++$current)
		{
			if ($current < 1 || $current > $this->total_pages)
			{
				continue;
			}
			else if ($current != $this->current_page)
			{
				$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a href="' . $link . '-' . $current . '.html"' . $link_plus . '>' . $current . '</a></li>' : '<li><a href="' . $link . '&amp;page=' . $current . '"' . $link_plus . '>' . $current . '</a></li>';
			}
			else
			{
				$re .= '<li class="active"><a>' . $current . '</a></li>';
			}
		}

		if ($this->current_page <= ($this->total_pages-3))
		{
			if ($this->current_page != ($this->total_pages-3) && $this->current_page != ($this->total_pages-4))
			{
				$re .= '<li><a href="#">...</a></li>';
			}

			$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a href="' . $link . '-' . $this->total_pages . '.html"' . $link_plus . '>' . $this->total_pages . '</a></li>' : '<li><a class="paginate" href="' . $link . '&amp;page=' . $this->total_pages . '"' . $link_plus . '>' . $this->total_pages . '</a></li>';
		}

		#Add a next page link
		if ($this->total_pages > 1 && $this->current_page < $this->total_pages)
		{
			$re .= ($config['mod_writer'] && !defined('IN_ADMIN')) ? '<li><a  href="' . $link . '-' . ($this->current_page+1) . '.html"' . $link_plus . '>' . $lang['NEXT'] . '</a></li>' :  '<li><a href="' . $link . '&amp;page=' . ($this->current_page+1) . '"' . $link_plus . '>' . $lang['NEXT'] . '</a></li>';
		}

		$re .= '</ul>'; 

		return $re;
	}
}

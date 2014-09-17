<?php
/**
 * PHPSense Pagination Class
 *
 * PHP tutorials and scripts
 *
 * @package		PHPSense
 * @author		Jatinder Singh Thind
 * @copyright	Copyright (c) 2006, Jatinder Singh Thind
 * @link		http://www.phpsense.com
 * @edited		Eko Budi Prasetyo (removeusing database)
 */

// ------------------------------------------------------------------------


class Ps_pagination {
	var $php_self;
	var $rows_per_page = 10; //Number of records to display per page
	var $total_rows = 0; //Total number of rows returned by the query
	var $links_per_page = 5; //Number of links to display per page
	var $append = ""; //Paremeters to append to pagination links
	var $sql = "";
	var $debug = false;
	var $page = 1;
	var $max_pages = 0;
	var $offset = 0;
	
	/**
	 * Constructor
	 *
	 * @param integer $rows_per_page Number of records to display per page. Defaults to 10
	 * @param integer $links_per_page Number of links to display per page. Defaults to 5
	 * @param string $append Parameters to be appended to pagination links 
	 */
	
	function PS_Pagination($rows_per_page = 10, $links_per_page = 5, $append = "") {
		$this->rows_per_page = (int)$rows_per_page;
		if (intval($links_per_page ) > 0) {
			$this->links_per_page = (int)$links_per_page;
		} else {
			$this->links_per_page = 5;
		}
		$this->append = $append;
		$this->php_self = htmlspecialchars($_SERVER['PHP_SELF'] );
		if (isset($_GET['page'] )) {
			$this->page = intval($_GET['page'] );
		}
	}
	
	/**
	 * Initializes internal variables
	 *
	 * @access public
	 * @return resource
	 */
	function paginate($total_data) {
		$this->total_rows = $total_data;
		
		//Max number of pages
		$this->max_pages = ceil($this->total_rows / $this->rows_per_page );
		if ($this->links_per_page > $this->max_pages) {
			$this->links_per_page = $this->max_pages;
		}
		
		//Check the page value just in case someone is trying to input an aribitrary value
		if ($this->page > $this->max_pages || $this->page <= 0) {
			$this->page = 1;
		}
		
		//Calculate Offset
		$this->offset = $this->rows_per_page * ($this->page - 1);
	}
	
	/**
	 * Display the link to the first page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'First'
	 * @return string
	 */
	function renderFirst($tag = 'First') {
		if ($this->total_rows == 0)
			return FALSE;
		
		if ($this->page == 1) {
			return "<li class=' disabled'><a> $tag </a></li>";
		} else {
			return '<li><a href="' . $this->php_self . '?page=1&' . $this->append . '">' . $tag . '</a></li> ';
		}
	}
	
	/**
	 * Display the link to the last page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'Last'
	 * @return string
	 */
	function renderLast($tag = 'Last') {
		if ($this->total_rows == 0)
			return FALSE;
		
		if ($this->page == $this->max_pages) {
			return "<li class=' disabled'><a > $tag </a></li>";
		} else {
			return '<li> <a href="' . $this->php_self . '?page=' . $this->max_pages . '&' . $this->append . '">' . $tag . '</a></li>';
		}
	}
	
	/**
	 * Display the next link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '>>'
	 * @return string
	 */
	function renderNext($tag = '&gt;&gt;') {
		if ($this->total_rows == 0)
			return FALSE;
		
		if ($this->page < $this->max_pages) {
			return '<li><a href="' . $this->php_self . '?page=' . ($this->page + 1) . '&' . $this->append . '">' . $tag . '</a></li>';
		} else {
			return "<li class='next disabled'><a > $tag </a></li>";
		}
	}
	
	/**
	 * Display the previous link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '<<'
	 * @return string
	 */
	function renderPrev($tag = '&lt;&lt;') {
		if ($this->total_rows == 0)
			return FALSE;
		
		if ($this->page > 1) {
			return '<li> <a href="' . $this->php_self . '?page=' . ($this->page - 1) . '&' . $this->append . '">' . $tag . '</a></li>';
		} else {
			return "<li class='prev disabled'><a > $tag </a></li>";
		}
	}
	
	/**
	 * Display the page links
	 *
	 * @access public
	 * @return string
	 */
	function renderNav($prefix = '', $suffix = '') {
		if ($this->total_rows == 0)
			return FALSE;
		
		$batch = ceil($this->page / $this->links_per_page );
		$end = $batch * $this->links_per_page;
		if ($end == $this->page) {
			//$end = $end + $this->links_per_page - 1;
		//$end = $end + ceil($this->links_per_page/2);
		}
		if ($end > $this->max_pages) {
			$end = $this->max_pages;
		}
		$start = $end - $this->links_per_page + 1;
		$links = '';
		
		for($i = $start; $i <= $end; $i ++) {
			if ($i == $this->page) {
				$links .= $prefix . "<li class='active'><a > $i </a></li>" . $suffix;
			} else {
				$links .= ' ' . $prefix . '<li><a href="' . $this->php_self . '?page=' . $i . '&' . $this->append . '">' . $i . '</a></li>' . $suffix . ' ';
			}
		}
		
		return $links;
	}
	
	/**
	 * Display full pagination navigation
	 *
	 * @access public
	 * @return string
	 */
	function renderFullNav() {
		return $this->renderFirst() . $this->renderPrev() . $this->renderNav() . $this->renderNext() . $this->renderLast();
	}
	
	/**
	 * Set debug mode
	 *
	 * @access public
	 * @param bool $debug Set to TRUE to enable debug messages
	 * @return void
	 */
	function setDebug($debug) {
		$this->debug = $debug;
	}
}
?>

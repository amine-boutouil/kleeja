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
 * Wrapper for MySQLi database driver
 */
class database 
{
	/**
	 * The connect resource
	 */
	private $connect_id = null;

	/**
	 * The results resource
	 */
	private $result = null;

	/**
	 * The number of queries executed in this session
	 */
	public $query_num = 0;


	/**
	 * Initiate the database connection
	 *
	 * @param string $server The database server
	 * @param string $user The database user
	 * @param string $pass The database password
	 * @param string $dbname The database name
	 * @return resource
	 */
	public function __construct($server, $user , $pass, $dbname)
	{
		
		#if port assigned to the server variable
		$port = 3306;
		if(strpos($server, ':') !== false)
		{
			$server = substr($server, 0, strpos($server, ':'));
			$port = intval(substr($server, strpos($server, ':') + 1));
		}

		$this->connect_id = @mysqli_connect($server, $user, $pass, $dbname, $port);

		if(mysqli_connect_error())
		{
			big_error('MySQL Connection Error', 'Database Connect Error (' . mysqli_connect_errno() . '): ' . mysqli_connect_error());
		}

		#make it utf8
		$this->query("SET NAMES 'utf8'");

		return $this->connect_id;
	}

	/**
	 * To execute a query to the db
	 *
	 * @param string $q The query string
	 * @return resource|false
	 */
	public function query($q)
	{
		$this->result = mysqli_query($this->connect_id, $q);
		if(!$this->result && mysqli_errno($this->connect_id))
		{
			big_error('MySQL Query Error', $this->error());
		}

		$this->query_num++;

		return $this->result;
	}


	/**
	 * To build query from an array and execute it
	 *
	 * @param array $q The query as an array to build
	 * @return resource|false
	 */
	public function build($query)
	{
		$sql = '';

		if (isset($query['SELECT']))
		{
			$sql = 'SELECT '.$query['SELECT'].' FROM '.$query['FROM'];

			if (isset($query['JOINS']))
			{
				foreach ($query['JOINS'] as $cur_join)
					$sql .= ' '.key($cur_join).' '. @current($cur_join).' ON '.$cur_join['ON'];
			}

			if (!empty($query['WHERE']))
				$sql .= ' WHERE '.$query['WHERE'];
			if (!empty($query['GROUP BY']))
				$sql .= ' GROUP BY '.$query['GROUP BY'];
			if (!empty($query['HAVING']))
				$sql .= ' HAVING '.$query['HAVING'];
			if (!empty($query['ORDER BY']))
				$sql .= ' ORDER BY '.$query['ORDER BY'];
			if (!empty($query['LIMIT']))
				$sql .= ' LIMIT '.$query['LIMIT'];
		}
		else if (isset($query['INSERT']))
		{
			$sql = 'INSERT INTO '.$query['INTO'];

			if (!empty($query['INSERT']))
				$sql .= ' ('.$query['INSERT'].')';

			$sql .= ' VALUES('.$query['VALUES'].')';
		}
		else if (isset($query['UPDATE']))
		{
			$query['UPDATE'] = $query['UPDATE'];

			if (isset($query['PARAMS']['LOW_PRIORITY']))
				$query['UPDATE'] = 'LOW_PRIORITY '.$query['UPDATE'];

			$sql = 'UPDATE '.$query['UPDATE'].' SET '.$query['SET'];

			if (!empty($query['WHERE']))
				$sql .= ' WHERE '.$query['WHERE'];
		}
		else if (isset($query['DELETE']))
		{
			$sql = 'DELETE FROM '.$query['DELETE'];

			if (!empty($query['WHERE']))
				$sql .= ' WHERE '.$query['WHERE'];
		}
		else if (isset($query['REPLACE']))
		{
			$sql = 'REPLACE INTO '.$query['INTO'];

			if (!empty($query['REPLACE']))
				$sql .= ' ('.$query['REPLACE'].')';

			$sql .= ' VALUES('.$query['VALUES'].')';
		}

		return $this->query($sql);
	}


	/**
	 * To make query
	 *
	 * @param resource $q The resource resulted from query function
	 * @param string $t the type of fetch
	 * @return array|bool
	 */
	public function fetch($q = 0, $t = 'assoc')
	{
		$t = in_array($t , array('array', 'assoc', 'row', 'field', 'lengths', 'object')) ? "mysqli_fetch_{$t}" : 'mysqli_fetch_assoc'; 
		return @$t($q ? $q : $this->result); 
	}

	/**
	 * To return the inserted ID
	 *
	 * @param resource $c [optional] a connection resource
	 * @return int|false
	 **/
	public function id($c = 0) { return @mysqli_insert_id($c ? $c : $this->connect_id); }

	/**
	 * To return the number of resulted rows
	 *
	 * @param resource $q [optional] A result resource
	 * @return int|false
	 **/
	public function num($q = 0) { return @mysqli_num_rows($q ? $q : $this->result); }

	/**
	 * To return the number of rows affected by the last INSERT, UPDATE, REPLACE or DELETE query
	 *
	 * @param resource $c [optional] a connection resource
	 * @return int
	 **/
	public function affected($c = 0) { return @mysqli_affected_rows($c ? $c : $this->connect_id); }

	/**
	 * To escape a string before use it the in query for safety
	 *
	 * @return string
	 **/
	public function escape($var) { return @mysqli_real_escape_string($this->connect_id, $var); }

	/**
	 * Frees the memory associated with the result
	 *
	 * @return void
	 **/
	function free($q = 0) { @mysqli_free_result(($q ? $q : $this->result)); }

	/**
	 * Is there a connection resource
	 *
	 * @return resource
	 **/
	function is_connected() { return $this->connect_id; }

	/**
	 * Close the current connrection
	 *
	 * @return void
	 **/
	function close($c = 0) { return @mysqli_close($c ? $c : $this->connect_id); }

	/**
	 * To return the error if any
	 *
	 * @return string
	 **/
	public function error(){ return mysqli_errno($this->connect_id) . ': ' . mysqli_error($this->connect_id); }
	

	/**
	 * To return the Mysql version
	 *
	 * @return string
	 **/
	public function version(){ return mysqli_get_server_info($this->connect_id); }
}

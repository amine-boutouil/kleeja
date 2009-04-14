<?php
##################################################
# Filename : mysql.php
# purpose :  controll  mysql database.:
# copyright 2007-2008 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# last edit by : saanina
##################################################


//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}  

	
if(!defined("SQL_LAYER")){

define("SQL_LAYER","mysql4");

  
class SSQL 
{

	var $connect_id              	= null;		
	var $result;		
	var $query_num					= 0;
	var $mysql_version;
	var $in_transaction 			= 0;
	var $row						= array();
	var $rowset						= array();
	var $debugr						= false;
	var $show_errors 				= true;


				/*
				initiate the class
				wirth basic data
				*/
                function SSQL($host,$db_username,$db_password,$db_name, $bad_forum = false)
				{
                          $this->host        = $host;
                          $this->db_username = $db_username;
                          $this->db_name     = $db_name;
                          $this->db_password = 'hidden';

						  
                        $this->connect_id	= @mysql_connect($this->host,$this->db_username,$db_password) or die($this->error_msg("ERROR: CAN NOT CONNECT TO SERVER [" . $this->db_username .  ':' . $this->host . "] ..."));
						//version of mysql
						$this->mysql_version = mysql_get_server_info($this->connect_id);
						
						if($this->connect_id)
						{
							if(!empty($db_name))
							{
								$dbselect = @mysql_select_db($this->db_name) or die($this->error_msg("ERROR: CAN NOT SELECT DATABASE [" . $this->db_name . "]..."));;
								
								if ($dbselect)
								{
								/*	if($bad_forum != false)
									{
										//mysql_query("SET NAMES latin1_swedish_ci");
									}
									else
									{
										mysql_query("SET NAMES 'utf8'");
									}*/
								}
								else if(!$dbselect)
								{
									mysql_close($this->connect_id);
									$this->connect_id = $dbselect;
								}
							}

							return $this->connect_id;
						}
						else
						{
							return false;
						}
				}

				/*
				close the connection
				*/
                function close()
				{		
				
					if( $this->connect_id )
					{
						//
						// Commit any remaining transactions
						//
						if( $this->in_transaction )
						{
							mysql_query("COMMIT", $this->connect_id);
						}

						return @mysql_close($this->connect_id);
					}
					else
					{
						return false;
					}
				}

				/*
				the query func . its so important to do 
				the quries and give results
				*/
                function query($query, $transaction = FALSE)
				{
					//
					// Remove any pre-existing queries
					//
					unset($this->result);


					
					if(!empty($query))
					{
						//debug .. //////////////
						$srartum_sql	=	get_microtime();
						////////////////
						
						if( $transaction == 1 && !$this->in_transaction )
						{
							$result = mysql_query("BEGIN", $this->connect_id);
							if(!$result)
							{
								return false;
							}
	
							$this->in_transaction = TRUE;
						}

						$this->result = mysql_query($query, $this->connect_id);
						
						//debug .. //////////////
						$this->debugr[$this->query_num+1] = array($query, sprintf('%.5f', get_microtime() - $srartum_sql));
						////////////////
						
						if(!$this->result)
						{
							$this->error_msg('Error In query');
						}
					}
					else
					{
						if( $transaction == 2 && $this->in_transaction )
						{
							$this->result = mysql_query("COMMIT", $this->connect_id);
						}
					}
					
					//is there any result
					if($this->result)
					{
					
						unset($this->row[$this->result]);
						unset($this->rowset[$this->result]);

						if($transaction == 2 && $this->in_transaction)
						{
							$this->in_transaction = FALSE;

							if (!mysql_query("COMMIT", $this->connect_id))
							{
								mysql_query("ROLLBACK", $this->connect_id);
								return false;
							}
						}
						
						$this->query_num++;
		
						
						return $this->result;
					}
					else
					{
						if( $this->in_transaction )
						{
							mysql_query("ROLLBACK", $this->connect_id);
							$this->in_transaction = FALSE;
						}
						return false;
					}
									
				}
				
				
				/*
				query build 
				*/
				function build($query)
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

					/*
					free the memmory from the last results
					*/
					function freeresult($query_id = 0)
					{
						if( !$query_id )
						{
							$query_id = $this->result;
						}

						if ( $query_id )
						{
							unset($this->row[$query_id]);
							unset($this->rowset[$query_id]);

							mysql_free_result($query_id);

							return true;
						}
						else
						{
							return false;
						}
					}
				
				/*
				if the result is an arry ,
				this func is so important to order them as a array
				*/
                function fetch_array($query_id = 0)
				{
                 	if( !$query_id )
					{
						$query_id = $this->result;
					}

					if( $query_id )
					{
						$this->row[$query_id] = mysql_fetch_array($query_id, MYSQL_ASSOC);
						return $this->row[$query_id];
					}
					else
					{
						return false;
					}
                }

				/*
				if we have a result and we have to know 
				the number of it , this is a func ..
				*/
                function num_rows($query_id = 0)
				{
					if( !$query_id )
					{
						$query_id = $this->result;
					}

					return ( $query_id ) ? mysql_num_rows($query_id) : false;
                }

				
				/*
				last id inserted in sql
				*/
                function insert_id()
				{
					return ( $this->connect_id ) ? mysql_insert_id($this->connect_id) : false;
                }

				/*
				clean the qurery before insert it
				*/
				function escape($msg)
				{

					$msg = htmlspecialchars($msg , ENT_QUOTES);
					$msg = (!get_magic_quotes_gpc()) ? addslashes ($msg) : $msg;
					return $msg;
				}
				
				/*
				real escape .. 
				*/
				function real_escape($msg)
				{
					if (is_array($msg))
					{
						return '';
					}
					else if (function_exists('mysql_real_escape_string'))
					{
						return mysql_real_escape_string($msg, $this->connect_id);
					}
					else
					{
						// because mysql_escape_string doesnt escape % & _[php.net/mysql_escape_string]
						return addcslashes(mysql_escape_string($msg),'%_');
					}

				}
				/*
				get the information of mysql server
				*/
				function server_info()
				{
					return 'MySQL ' . $this->mysql_version;
				}

				/*
				error message func
				*/
				function error_msg($msg)
				{
					if(!$this->show_errors)
					{
						return;
					}
					
					$error_no  = mysql_errno();
					$error_msg = mysql_error();
					$error_sql = @current($this->debugr[$this->query_num+1]);

					echo "<html><head><title>ERROR IM MYSQL</title>";
					echo "<style>BODY{FONT-FAMILY:tahoma;FONT-SIZE:12px;}.error {}</style></head><body>";
					echo '<br />';
					echo '<div class="error">';
					echo " <a href='#' onclick='window.location.reload( false );'>click to Refresh this page ...</a><br />";
					echo "<h2>Sorry , There is an error in mysql " . ($msg !='' ? ", error : $msg" : "") ."</h2>";
					if($error_sql != '')
					{
						echo "<br />--[query]-------------------------- <br />$error_sql<br />---------------------------------<br /><br />";
					}
					echo "[$error_no : $error_msg] <br />";
					echo "<br /><br /><strong>Script: Kleeja <br /><a href='http://www.kleeja.com'>Kleeja Website</a></strong>";
					echo '</b></div>';
					echo '</body></html>';
					exit();
				}
				
				/*
				return last error
				*/
				function get_error()
				{
					return array(mysql_errno(), mysql_error()); 
				}
			
}#end of class
}#if
?>

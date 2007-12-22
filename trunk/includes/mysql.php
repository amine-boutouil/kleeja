<?php
##################################################
# Filename : mysql.php
# purpose :  controll  mysql database.:
#Developer: Saanina @ gmail.com
##################################################


if (!defined('IN_COMMON')) {die("Hacking attempt");	  exit(); }	  
 
if(!defined("SQL_LAYER"))
{

define("SQL_LAYER","mysql4");

  
class SSQL {

/*****************/


var $connect_id              	=        null;		
var $result;		
var $query_num					= 			0;
var $mysql_version;
var $in_transaction 			=			0;
var $row = array();
var $rowset = array();
var $debugr = false;
/***************/
                function SSQL($host,$db_username,$db_password,$db_name)
				{
                          $this->host        = $host;
                          $this->db_username = $db_username;
                          $this->db_password = $db_password;
                          $this->db_name     = $db_name;

						  
                        $this->connect_id = @mysql_connect($this->host,$this->db_username,$this->db_password) or die($this->error_msg("ERROR: CANT CONNECT"));
						//version of mysql
						$this->mysql_version = mysql_get_server_info($this->connect_id);
						
						if( $this->connect_id )
						{
							if( $db_name != "" )
							{
								$this->db_name = $db_name;
								$dbselect = mysql_select_db($this->db_name);
								
								if ($dbselect) {if ($this->mysql_version>='4.1.0') mysql_query("SET NAMES 'utf8'"); }
								if( !$dbselect )
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

/***************/
                function close(){		
					if( $this->connect_id )
					{
						//
						// Commit any remaining transactions
						//
						if( $this->in_transaction )
						{
							mysql_query("COMMIT", $this->connect_id);
						}

						return mysql_close($this->connect_id);
					}
					else
					{
						return false;
					}
					
     }
/***************/
                function query($query, $transaction = FALSE)
				{
						//
						// Remove any pre-existing queries
						//
						unset($this->result);


					
					
					if( $query != "" )
					{
					//debug .. //////////////
					if($_GET['debug']) {
					$this->debugr[] = $query;	
					}////////////////
						
						if( $transaction == 1 && !$this->in_transaction )
						{
							$result = mysql_query("BEGIN", $this->connect_id);
							if(!$result)
							{
								return false;
							}
	
							$this->in_transaction = TRUE;
						}

						$this->result = @mysql_query($query, $this->connect_id) or $this->error_msg('Error In query');
					}
					else
					{
						if( $transaction == 2 && $this->in_transaction )
						{
							$result = mysql_query("COMMIT", $this->connect_id);
						}
					}
					if( $this->result )
					{
						unset($this->row[$this->result]);
						unset($this->rowset[$this->result]);

						if( $transaction == 2 && $this->in_transaction )
						{
							$this->in_transaction = FALSE;

							if ( !mysql_query("COMMIT", $this->connect_id) )
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
/***************/
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
/***************/
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
/***************/
                function num_rows($query_id = 0)
				{
					if( !$query_id )
					{
						$query_id = $this->result;
					}

					return ( $query_id ) ? mysql_num_rows($query_id) : false;
                }
/***************/
                function insert_id()
				{
					return ( $this->connect_id ) ? mysql_insert_id($this->connect_id) : false;
                }
/***************/
				function escape($msg) // for kleeja ,, its all thing
				{

					$msg = htmlspecialchars($msg , ENT_QUOTES);
					$msg = (!get_magic_quotes_gpc()) ? addslashes ($msg) : $msg;
					return $msg;
				}
/***************/
				function server_info()
				{
					return 'MySQL ' . $this->mysql_version;
				}
/***************/
                           function error_msg($msg)
				{
                          $error_no  = mysql_errno();
                          $error_msg = mysql_error();

                          echo "<style>BODY{FONT-FAMILY:tahoma;FONT-SIZE:12px;}
								textarea {color: #FF0000;background-color: #FFECEC;border-width: 1px;
								border-color: #000000;border-style: solid;}</style>";
                          echo "<html><head></head><title>ERROR IM MYSQL</title><body>";
                          echo '<br /><div style="text-align:center;color:red;"><b>';
                          echo '<textarea  readonly="readonly" style="width: 500px; height: 161px">';
						  echo "
SORRY , THERE IS AN ERROR IN MYSQL , ERROR IS : $msg

[$error_no : $error_msg]

YOU MUST TELL MODERATOR BY THIS ERROR!
							
Script: Kleeja : By :Saanina
</textarea>";
                          echo '</b></div>';
                          echo '</body></html>';

						exit();
				}
				
/***************/
function debug (){ 
if($_GET['debug']) {
	$e = '';
	if(is_array($this->debugr)){ 
	foreach($this->debugr as $key=>$val){ $e .= '<br/>-['.$key.'] '.$val .'<hr/>';}
	}
	return $e."<br/><b>Query Number : ".$this->query_num ."</b><br/> this Class is created by Saanina@gmail.com";
	}else{
	return false;
	}
}
/***************/

        }#end of class
}#if
?>
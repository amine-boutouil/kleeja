<?php

/*
* This library by : MaaSTaaR .. http://www.el7zn.com
* developing  by saanina @ gmail.com
*/

	  if (!defined('IN_COMMON'))
	  {
	  echo '<strong><br /><span style="color:red">[NOTE]: This Is Dangrous Place !! [2007 saanina@gmail.com]</span></strong>';
	  exit();
	  }
  
  
class SSQL {

/*****************/

var $host                        =        "localhost";
var $db_username           		 =        "";
var $db_password       			 =        "";
var $db_name              		 =        "";
var $connect_id              	 =        null;		
var $result;		
var $query_num					 = 			0;
var $time_of_sql				 = 			0;
var $mysql_version;

/***************/
                function setinfo($host,$db_username,$db_password,$db_name)
				{
                          $this->host        = $host;
                          $this->db_username = $db_username;
                          $this->db_password = $db_password;
                          $this->db_name     = $db_name;
                }
/***************/
                function connect()
				{
                        $this->connect_id = @mysql_connect($this->host,$this->db_username,$this->db_password) or die($this->error_msg("لم يتمكن من الاتصال"));
						//version of mysql
						$this->mysql_version = mysql_get_server_info($this->connect_id);
				}
/***************/
                function selectdb()
				{
                        $this->select=@mysql_select_db($this->db_name) or die($this->error_msg("خطأ في اختيار قاعدة البيانات"));
						if ($this->select) {if (mysql_version>='4.1.0') mysql_query("SET NAMES 'utf8'"); }
			   }
/***************/
                function close()
				{		
						        if(! @mysql_close($this->connect_id) )
								return false; 
                }
/***************/
                function query($query)
				{
						$this->query_num++;
                        $timef = microtime(true);
						$times = microtime(true);
                        $this->time_of_sql = ($times-$timef)*(pow(10,5));
						
                        $this->result = @mysql_query($query, $this->connect_id) or die($this->error_msg("خطأ في الاستعلام"));
						return $this->result;
				}
/***************/
				function freeresult($query_id = false)
				{
						if ($query_id === false){$query_id = $this->result;}
						
                        @mysql_free_result($query_id);
						return false;
				}
/***************/
                function fetch_array($result)
				{
                         return @mysql_fetch_array($result);
                }
/***************/
                function num_rows($result)
				{
                          return @mysql_num_rows($result);
                }
/***************/
                function insert_id()
				{
                        return mysql_insert_id();

                }
/***************/
				function escape($msg)
				{
					// make sure no HTML entities were matched
					$chars = array('<', '>', '"');
					$split = false;

					$msg = str_replace(array('\&', '\"', '\\\'', '<', '>')
									,array('&amp;', '&quot;', "'", '&lt;', '&gt;'), $msg);
					
					if (!$this->connect_id)
					{
						return @mysql_real_escape_string($msg);
					}
					return @mysql_real_escape_string($msg, $this->connect_id);
					
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
                          echo "<html><head></head><title>خطأ في قواعد البيانات</title><body>";
                          echo '<br /><div style="text-align:center;color:red;"><b>';
                          echo '<textarea  readonly="readonly" style="width: 500px; height: 161px" dir="rtl">';
						  echo "
المعذرة، هناك مشكلة في قواعد البيانات و سببها : $msg

[$error_no : $error_msg]

يرجى الإتصال بمدير الموقع !
							
Script: SaaUp : By :Saanina
</textarea>";
                          echo '</b></div>';
                          echo '</body></html>';

						exit();
				}
		
        }#end of class
?>
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

class KljUploader
{
    var $folder;
    var $action;		 //page action
    var $filesnum; 		//number of fields
    var $types; 		 // filetypes
    var $ansaqimages;   // imagestypes
    var $filename;     // filename
    var $total = 0;		//total files
	//var $sizes;
	var $typet;
	var $sizet;
	var $id_for_url;
	var $name_for_url;
    var $filename2;  //alternative file name
    var $linksite;    //site link
    var $decode;     // decoding name with md5 or time or no
	var $id_user;
	var $errs = array();
	var $safe_code;	// captcha is on or off 
	var $user_is_adm; //check if user is administrator



	/**
	// watermark
	// source : php.net
	 */
	 function watermark($name, $ext, $logo)
	 {
	 
		($hook = kleeja_run_hook('watermark_func_kljuploader')) ? eval($hook) : null; //run hook	
		
		if(!file_exists($name))
		{
			return;
		}
		
		if (preg_match("/jpg|jpeg/",$ext) && function_exists('imagecreatefromjpeg'))
		{
			$src_img = @imagecreatefromjpeg($name);
		}
		elseif (preg_match("/png/",$ext) && function_exists('imagecreatefrompng'))
		{
			$src_img = @imagecreatefrompng($name);
		}
		//elseif (preg_match("/gif/", $ext) && !$this->is_ani($name)&& function_exists('imagecreatefromgif'))
		elseif (preg_match("/gif/", $ext)&& function_exists('imagecreatefromgif'))
		{
			$src_img = @imagecreatefromgif($name);
		}
		else
		{
			return;
		}

		$src_logo = imagecreatefromgif($logo);

		$bwidth  = @imageSX($src_img);
		$bheight = @imageSY($src_img);
		$lwidth  = @imageSX($src_logo);
		$lheight = @imageSY($src_logo);
		
		//fix bug for 1beta3
		if ($bwidth > 160 &&  $bheight > 130)
		{
		
				$src_x = $bwidth - ($lwidth + 5);
				$src_y = $bheight - ($lheight + 5);
				@ImageAlphaBlending($src_img, true);
				@ImageCopy($src_img,$src_logo,$src_x,$src_y,0,0,$lwidth,$lheight);

				if (preg_match("/jpg|jpeg/",$ext))
				{
					@imagejpeg($src_img, $name);
				}
				elseif (preg_match("/png/",$ext))
				{
					imagepng($src_img, $name);
				}
				elseif (preg_match("/gif/",$ext))
				{
					@imagegif($src_img, $name);
				}
		
		}# < 150
		else 
		{
			return false;
		}
		
	}
	
	//
	//check for gif image is animated or not ! 
	//(c) http://us2.php.net/manual/en/function.imagecreatefromgif.php#88005
	// todo: need more and more improvments
	//
	function is_ani($filename)
	{
		return (bool)preg_match('#(\x00\x21\xF9\x04.{4}\x00\x2C.*){2,}#s', file_get_contents($filename));
	}
	
	//
	//check exts inside file to be safe
	//
	function ext_check_safe ($filename)
	{
		$not_allowed =	array('php', 'php3' ,'php5', 'php4', 'asp' ,'shtml' , 'html' ,'htm' ,'xhtml' ,'phtml', 'pl', 'cgi', 'htaccess', 'ini');
		$tmp	= explode(".", $filename);
		$before_last_ext = $tmp[sizeof($tmp)-2];

		if (in_array(strtolower($before_last_ext), $not_allowed)) 
		{
			return false;
		}
		else
		{
			return true;
		}	
	}

	/*
		Function createthumb($name,$filename,$new_w,$new_h)
		example : createthumb('pics/apple.jpg','thumbs/tn_apple.jpg',100,100);
		creates a resized image
		source :http://icant.co.uk/articles/phpthumbnails/
	*/
	function createthumb($name, $ext, $filename, $new_w, $new_h)
	{
		($hook = kleeja_run_hook('createthumb_func_kljuploader')) ? eval($hook) : null; //run hook	
		
		if(!file_exists($name))
		{
			return;
		}
		
		if (preg_match("/jpg|jpeg/",$ext) && function_exists('imagecreatefromjpeg'))
		{
			$src_img = @imagecreatefromjpeg($name);
		}
		elseif (preg_match("/png/",$ext) && function_exists('imagecreatefrompng'))
		{
			$src_img = @imagecreatefrompng($name);
		}
		elseif (preg_match("/gif/", $ext) && !$this->is_ani($name)&& function_exists('imagecreatefromgif'))
		{
			$src_img = @imagecreatefromgif($name);
		}
		else
		{
			return;
		}
		
		$old_x	= @imageSX($src_img);
		$old_y	= @imageSY($src_img);
		
		if ($old_x > $old_y)
		{
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		elseif ($old_x < $old_y)
		{
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		elseif ($old_x == $old_y)
		{
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}
		
		$dst_img = @ImageCreateTrueColor($thumb_w,$thumb_h);
		@imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
		
		if (preg_match("/jpg|jpeg/", $ext))
		{
			@imagejpeg($dst_img, $filename);
		}
		elseif (preg_match("/png/", $ext))
		{
			@imagepng($dst_img, $filename);
		}
		elseif (preg_match("/gif/", $ext))
		{
			@imagegif($dst_img, $filename);
		}
		

		@imagedestroy($dst_img);
		@imagedestroy($src_img);
	}




//
// prorcess
//
function process () 
{
		global $SQL,$dbprefix,$config,$lang;
		global $use_ftp,$ftp_server,$ftp_user,$ftp_pass,$ch;
		
		($hook = kleeja_run_hook('start_process_kljuploader')) ? eval($hook) : null; //run hook	
		
		//check prefix 
		if (preg_match("/{rand:([0-9]+)}/i", $this->filename, $m))
		{
			$this->filename = preg_replace("/{rand:([0-9]+)}/i", substr(md5(time()), 0, $m[1]), $this->filename);
		}
		
		if (preg_match("/{date:([a-zA-Z-_]+)}/i", $this->filename, $m))
		{
			$this->filename = preg_replace("/{date:([a-zA-Z-_]+)}/i", date($m[1]), $this->filename);
		}
		
		
		// check folder
		if(!file_exists($this->folder)) 
		{
			($hook = kleeja_run_hook('no_uploadfolder_kljuploader')) ? eval($hook) : null; //run hook	

			$jadid	=	mkdir($this->folder);
			$jadid2	=	mkdir($this->folder . '/thumbs');
			
			if($jadid)
			{
				$this->errs[] = array($lang['NEW_DIR_CRT'], 'index_info');
				
				$htaccess_data = '<Files ~ "\.(php*|s?p?x?i?html|cgi|asp|php3|php4|pl|htm|sql)$">deny from all</Files>' . "\n" . 'php_flag engine off';
				$fo		= @fopen($this->folder . "/index.html","w");
				$fo2	= @fopen($this->folder . "/thumbs/index.html","w");
				$fw		= @fwrite($fo,'<a href="http://kleeja.com"><p>KLEEJA ..</p></a>');
				$fw2	= @fwrite($fo2,'<a href="http://kleeja.com"><p>KLEEJA ..</p></a>');
				$fi		= @fopen($this->folder . "/.htaccess", "w");
				$fi2	= @fopen($this->folder . "/thumbs/.htaccess","w");
				$fy		= @fwrite($fi, $htaccess_data);
				$fy2	= @fwrite($fi2, $htaccess_data);
				$chmod	= @chmod($this->folder, 0777);
				$chmod2	= @chmod($this->folder . '/thumbs/', 0777);

				if(!$chmod)
				{
					$this->errs[] = array($lang['PR_DIR_CRT'], 'index_err');
				} 
			}
			else
			{
				$this->errs[] = array($lang['CANT_DIR_CRT'], 'index_err');
			}
		}

			//then wut did u click
			if (isset($_POST['submitr']))
			{
				$wut = 1;
			}
			elseif(isset($_POST['submittxt']))
			{
				$wut = 2;
			}
			else
			{
				$wut = null;
				//no uploading yet, or just go to index.php, so we have make a new session
				unset($_SESSION['FIILES_NOT_DUPLI'], $_SESSION['FIILES_NOT_DUPLI_LINKS']);
			}
			
			//if submit 
			if($wut)
			{	
				//for plugins
				($hook = kleeja_run_hook('if_wut_kljuploader_w1')) ? eval($hook) : null; //run hook	
			}


			//safe_code .. captcha is on
			if($this->safe_code && $wut)
			{
				if(!kleeja_check_captcha())
				{
					($hook = kleeja_run_hook('wrong_captcha_kljuploader_w1')) ? eval($hook) : null; //run hook	
					 return $this->errs[] = array($lang['WRONG_VERTY_CODE'], 'index_err');
				}
			}
			
			if(!$this->user_is_adm && $this->user_is_flooding())
			{
				return $this->errs[] = array(sprintf($lang['YOU_HAVE_TO_WAIT'], ($this->id_user == '-1') ? $config['guestsectoupload'] : $config['usersectoupload']), 'index_err');
			}
			
			if ($wut == 1 && isset($_SESSION['FIILES_NOT_DUPLI']))
			{
				for($i=0;$i<=$this->filesnum;$i++)
				{
					if((!empty($_SESSION['FIILES_NOT_DUPLI']['file_' . $i . '_']['name']) && !empty($_FILES['file_' . $i . '_']['name'])) && ($_SESSION['FIILES_NOT_DUPLI']['file_' . $i . '_']['name'] == $_FILES['file_' . $i . '_']['name']))
					{
						redirect('./');
						//return $this->errs[] = array($lang['NO_REPEATING_UPLOADING'], 'index_err');
					}
				}
			}
			if ($wut == 2 && isset($_SESSION['FIILES_NOT_DUPLI_LINKS']))
			{
				for($i=0;$i<=$this->filesnum;$i++)
				{
					if((!empty($_SESSION['FIILES_NOT_DUPLI_LINKS']['file_' . $i . '_']) && !empty($_POST['file_' . $i . '_']) && trim($_POST['file_' . $i . '_']) != $lang['PAST_URL_HERE'] && trim($_SESSION['FIILES_NOT_DUPLI_LINKS']['file_' . $i . '_']) != $lang['PAST_URL_HERE']) && ($_SESSION['FIILES_NOT_DUPLI_LINKS']['file_' . $i . '_']) == ($_POST['file_' . $i . '_']))
					{
						redirect('./');
						//return $this->errs[] = array($lang['NO_REPEATING_UPLOADING'], 'index_err');
					}
				}
			}
			
			
			// uploading process 
			$check = false;
			if ($wut == 1)
			{
				($hook = kleeja_run_hook('submit_filesupload_kljuploader')) ? eval($hook) : null; //run hook	
				
				for($i=0;$i<=$this->filesnum;$i++)
				{
					$check .= isset($_FILES['file_' . $i . '_']['name']) ? $_FILES['file_' . $i . '_']['name'] : '';
					$this->filename2	= @explode(".", $_FILES['file_' . $i . '_']['name']);
					$this->filename2	= strtolower($this->filename2[sizeof($this->filename2)-1]);
					$this->typet		= $this->filename2;
					$this->sizet		= !empty($_FILES['file_' . $i . '_']['size']) ?  $_FILES['file_' . $i . '_']['size'] : null;
					
					($hook = kleeja_run_hook('for_wut1_filesupload_kljuploader')) ? eval($hook) : null; //run hook
					
						// decoding
						if($this->decode == "time")
						{
							$zaid = time();
							$this->filename2 = $this->filename . $zaid . $i . "." . $this->filename2;
						}
						elseif($this->decode == "md5")
						{
							$zaid	= md5(time());
							$zaid	= substr($zaid, 0, 10);
							$this->filename2 = $this->filename . $zaid . $i . "." . $this->filename2;
						}  
						else
						{
							//real name of file
							$filename = substr(@$_FILES['file_' . $i . '_']['name'], 0, -strlen($this->typet)-1);
							$this->filename2 = $this->filename . preg_replace('/[,.?\/*&^\\\$%#@()_!|"\~\'><=+}{; ]/', '-', $filename) . '.' . $this->typet;
							$this->filename2 = preg_replace('/-+/', '-', $this->filename2);
							($hook = kleeja_run_hook('another_decode_type_kljuploader')) ? eval($hook) : null; //run hook
						}
						

						if(empty($_FILES['file_' . $i . '_']['tmp_name']))
						{
							//if no file ? natin to do ,, why ? becuase its multiple fields
						}
						elseif(file_exists($this->folder . '/' . $this->filename2))
						{
							$this->errs[] = array(sprintf($lang['SAME_FILE_EXIST'], htmlspecialchars($_FILES['file_' . $i . '_']['name'])), 'index_err');
						}
						elseif(preg_match ("#[\\\/\:\*\?\<\>\|\"]#", $this->filename2))
						{
							$this->errs[] = array(sprintf($lang['WRONG_F_NAME'], htmlspecialchars($_FILES['file_' . $i . '_']['name'])), 'index_err');
						}
						elseif($this->ext_check_safe($_FILES['file_' . $i . '_']['name']) == false)
						{
							$this->errs[] = array(sprintf($lang['WRONG_F_NAME'], htmlspecialchars($_FILES['file_' . $i . '_']['name'])), 'index_err');
						}
						elseif(!in_array(strtolower($this->typet), array_keys($this->types)))
						{
							//guest
							if($this->id_user == '-1')
							{
								$this->errs[] = array(sprintf($lang['FORBID_EXT'], $this->typet) . '<br /> <a href="' .  ($config['mod_writer'] ? "register.html" : "ucp.php?go=register") . '" title="' . htmlspecialchars($lang['REGISTER']) . '">' . $lang['REGISTER'] . '</a>', 'index_err');
							}
							//not guest
							else
							{
								$this->errs[] = array(sprintf($lang['FORBID_EXT'], $this->typet), 'index_err');
							}
						}
						elseif(kleeja_check_mime($_FILES['file_' . $i . '_']['type'], $this->types[strtolower($this->typet)]['group_id'], $_FILES['file_' . $i . '_']['tmp_name']) == false)
						{
							$this->errs[] = array(sprintf($lang['NOT_SAFE_FILE'], htmlspecialchars($_FILES['file_' . $i . '_']['name'])), 'index_err');
						}
						elseif($this->types[strtolower($this->typet)]['size'] > 0 && $this->sizet >= $this->types[strtolower($this->typet)]['size'])
						{
							$this->errs[] = array(sprintf($lang['SIZE_F_BIG'], htmlspecialchars($_FILES['file_' . $i . '_']['name']), Customfile_size($this->types[strtolower($this->typet)]['size'])), 'index_err');
						}
						else
						{
							//
							// no errors , so uploading
							//
								//if (!$use_ftp)
								//{
										($hook = kleeja_run_hook('move_uploaded_file_kljuploader')) ? eval($hook) : null; //run hook	
										$file = move_uploaded_file($_FILES['file_' . $i . '_']['tmp_name'], $this->folder . "/" . $this->filename2);
								/*}
								else // use ftp account
								{
											($hook = kleeja_run_hook('ftp_connect_kljuploader')) ? eval($hook) : null; //run hook
											// set up a connection or die
											$conn_id		= ftp_connect($ftp_server);
											// Login with username and password
											$login_result	= ftp_login($conn_id, $ftp_user, $ftp_pass);
											
											ftp_pasv($conn_id,false);
											
											// Check the connection
											if ((!$conn_id) || (!$login_result)) 
											{
												  $this->errs[]= array($lang['CANT_CON_FTP'] . $ftp_server, 'index_err');
											}
											
											//ftp method
											if (in_array(strtolower($this->typet), array('png','gif','jpg','jpeg','tif','tiff')))
											{
												$ftp_method = FTP_BINARY;	
											}
											else
											{
												$ftp_method = FTP_ASCII;	
											}
											
											// Upload the file
											$file = ftp_put($conn_id, $this->folder . "/" . $this->filename2, $_FILES['file_' . $i . '_']['tmp_name'], $ftp_method);
											ftp_close($conn_id);
								}*/

								if ($file)
								{
									$this->saveit ($this->filename2, $this->folder, $this->sizet, $this->typet, $_FILES['file_' . $i . '_']['name']);
								} 
								else 
								{
									$this->errs[] = array(sprintf($lang['CANT_UPLAOD'], $this->filename2), 'index_err');
								}
								
								

						}
				}#for ... lmean loop
				
				
				if(!isset($check) || empty($check))
				{
					$this->errs[] = array($lang['CHOSE_F'], 'index_err');
				}

			}#wut=1
			elseif ($wut == 2 && $config['www_url'] == '1')
			{
				($hook = kleeja_run_hook('submit_urlupload_kljuploader')) ? eval($hook) : null; //run hook
				//looop text inputs
				for($i=0;$i<$this->filesnum;$i++)
				{
					$check 				.= (isset($_POST['file_' . $i . '_']) && trim($_POST['file_' . $i . '_']) != $lang['PAST_URL_HERE']) ? $_POST['file_' . $i . '_'] : '';
					$filename 			= (isset($_POST['file_' . $i . '_'])) ? basename($_POST['file_' . $i . '_']) : '';
					$this->filename2	= explode(".", $filename);
					
					($hook = kleeja_run_hook('for_wut2_filesupload_kljuploader')) ? eval($hook) : null; //run hook			
					
					if(in_array($this->filename2[count($this->filename2)-1], array('html', 'php', 'html')))
					{
						$this->filename2 = $this->typet = strtolower($this->filename2[count($this->filename2)-2]);
					}
					else
					{
						$this->filename2 = $this->typet = strtolower($this->filename2[count($this->filename2)-1]);
					}
								
					//transfer [decode]
					if($this->decode == "time")
					{
						$zaid = time();
						$this->filename2 = $this->filename . $zaid . $i . "." . $this->filename2;
					}
					elseif($this->decode == "md5")
					{
						$zaid=md5(time());
						$zaid=substr($zaid, 0, 10);
						$this->filename2 = $this->filename . $zaid . $i . "." . $this->filename2;
					}
					else
					{
						// real name of file
						$this->filename2 = $this->filename . preg_replace('/[,.?\/*&^\\\$%#@()_!|"\~\'><=+}{; ]/', '-', $filename) . '.' . $this->typet;
						$this->filename2 = preg_replace('/-+/', '-', $this->filename2);
						($hook = kleeja_run_hook('another_decode_type_kljuploader')) ? eval($hook) : null; //run hook
					}
					//end tashfer


					if(empty($_POST['file_' . $i . '_']) || trim($_POST['file_' . $i . '_']) == $lang['PAST_URL_HERE'])
					{
						//nathin
					}
					else//big else
					{
						if(file_exists($this->folder . '/' . $filename))
						{
							$this->errs[] = array(sprintf($lang['SAME_FILE_EXIST'], htmlspecialchars($_POST['file_' . $i . '_'])), 'index_err');
						}
						//elseif( preg_match ("#[\\\/\:\*\?\<\>\|\"]#", $this->filename2))
						//{
						//	$this->errs[] = array(sprintf($lang['WRONG_F_NAME'], htmlspecialchars($_POST['file_' . $i . '_'])), 'index_err');
						//}
						//elseif($this->ext_check_safe($_POST['file_' . $i . '_']) == false)
						//{
						//	$this->errs[] = array(sprintf($lang['WRONG_F_NAME'], htmlspecialchars($_POST['file_' . $i . '_'])), 'index_err');
						//}
						//elseif(kleeja_check_mime($_POST['file_' . $i . '_'], $this->types[strtolower($this->typet)]['group_id'], $_FILES['file_' . $i . '_']['tmp_name']) == false)
						//{
						// $this->errs[] = array(sprintf($lang['FORBID_EXT'], htmlspecialchars($_POST['file_' . $i . '_'])), 'index_err');
						//}
						elseif(!in_array(strtolower($this->typet),array_keys($this->types)))
						{
							$this->errs[] = array(sprintf($lang['FORBID_EXT'], htmlspecialchars($_POST['file_' . $i . '_']), $this->typet), 'index_err');
						}
						else
						{
							
							($hook = kleeja_run_hook('start_upload_wut2_kljuploader')) ? eval($hook) : null; //run hook
							
							//
							//end err .. start upload from url
							//
							if(!in_array(substr($_POST['file_' . $i . '_'], 0, 4), array('http', 'ftp:')))
							{
								$_POST['file_' . $i . '_'] = 'http://' . $_POST['file_' . $i . '_'];
							}
										
							if(function_exists("curl_init"))
							{
								$data = fetch_remote_file($_POST['file_' . $i . '_']);
								if($data != false)
								{
									$this->sizet = strlen($data);
									if($this->types[strtolower($this->typet)]['size'] > 0 && $this->sizet >= $this->types[strtolower($this->typet)]['size'])
									{
										$this->errs[] = array(sprintf($lang['SIZE_F_BIG'], htmlspecialchars($_POST['file_' . $i . '_']), Customfile_size($this->types[strtolower($this->typet)]['size'])), 'index_err');
									}
									else
									{
										//then ..write new file
										$fp2 = @fopen($this->folder . "/" . $this->filename2, "w");
										@fwrite($fp2, $data);
										@fclose($fp2);
										$this->saveit ($this->filename2, $this->folder, $this->sizet, $this->typet);
									}
								}
								else
								{
									$this->errs[] = array($lang['URL_CANT_GET'], 'index_err');
								}
							}
							else //OTHER FUNCTION
							{
								$this->sizet = $this->get_remote_file_size($_POST['file_' . $i . '_']);
		
								if($this->types[strtolower($this->typet)]['size'] > 0 && $this->sizet >= $this->types[strtolower($this->typet)]['size'])
								{
									$this->errs[] = array(sprintf($lang['SIZE_F_BIG'], htmlspecialchars($_POST['file_' . $i . '_']), Customfile_size($this->types[strtolower($this->typet)]['size'])), 'index_err');
								}
								else
								{
									$data = fetch_remote_file($_POST['file_' . $i . '_'], $this->folder . "/" . $this->filename2);
									if($data === false)
									{
										$this->errs[] = array($lang['URL_CANT_GET'], 'index_err');		
									}
									else
									{
										$this->saveit ($this->filename2, $this->folder, $this->sizet, $this->typet);
									}
								}
							}
							
						}#else
					}//big else

				}#end loop
				
			if(!isset($check) || empty($check))
			{
				$this->errs[] = array($lang['CHOSE_F'], 'index_err');
			}
			
		}#end wut2

}#END process




	//
	// save data and insert in the database
	//
	function saveit ($filname, $folderee, $sizeee, $typeee, $real_filename = '')
	{
		global $SQL, $dbprefix, $config, $lang;

				// sometime cant see file after uploading.. but ..
				@chmod($folderee . '/' . $filname , 0755); //0755
				
				$name 	= (string)	$SQL->escape($filname);
				$size	= (int) 	$sizeee;
				$type 	= (string)	strtolower($SQL->escape($typeee));
				$folder	= (string)	$SQL->escape($folderee);
				$timeww	= (int)		time();
				$user	= (int)		$this->id_user;
				$code_del=(string)	md5(time());
				$ip		= get_ip();
				$realf	= (string)	$SQL->escape($real_filename);	
				
				$insert_query = array(
									'INSERT'	=> '`name` ,`size` ,`time` ,`folder` ,`type`,`user`,`code_del`,`user_ip`, `real_filename`',
									'INTO'		=> "`{$dbprefix}files`",
									'VALUES'	=> "'$name', '$size', '$timeww', '$folder','$type', '$user', '$code_del', '$ip', '$realf'"
									);
									
				($hook = kleeja_run_hook('qr_insert_new_file_kljuploader')) ? eval($hook) : null; //run hook
				
				$SQL->build($insert_query);
				
				$this->name_for_url  = $name;
				$this->id_for_url  = $SQL->insert_id();

				//calculate stats ..s
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "`files`=files+1,`sizes`=sizes+" . $size . ",`last_file`='" . $folder . "/" . $name . "'"
								);
								
				($hook = kleeja_run_hook('qr_update_no_files_kljuploader')) ? eval($hook) : null; //run hook
				
				$SQL->build($update_query);
				
				//delete cache of stats !
				delete_cache('data_stats');

				//inforantion of file 
				$file_info = array('::ID::'=>$this->id_for_url, '::NAME::'=>$this->name_for_url, '::DIR::'=> $folderee, '::FNAME::'=>$realf);
					
				//show del code link
				$extra_del = '';
				if ($config['del_url_file'])
				{
					$extra_del	= get_up_tpl_box('del_file_code', array('b_title'=> $lang['URL_F_DEL'], 'b_code_link'=> kleeja_get_link('del', array('::CODE::'=>$code_del))));
				}
					


					//show imgs
					if (in_array(strtolower($this->typet), array('png','gif','jpg','jpeg','tif','tiff')))
					{
						//make thumbs
						$img_html_result = '';
						if( ($config['thumbs_imgs'] != 0) && in_array(strtolower($this->typet), array('png','jpg','jpeg','gif')))
						{
							list($thmb_dim_w, $thmb_dim_h) = @explode('*', $config['thmb_dims']);
							$this->createthumb($folderee . '/' . $filname, strtolower($this->typet), $folderee . '/thumbs/' . $filname, $thmb_dim_w, $thmb_dim_h);
							
							$img_html_result .= get_up_tpl_box('image_thumb', array(
																				'b_title'	=> $lang['URL_F_THMB'], 
																				'b_url_link'=> kleeja_get_link('image', $file_info), 
																				'b_img_link'=> kleeja_get_link('thumb', $file_info)
																				));

						}
						
						//write on image
						if( ($config['write_imgs'] != 0) && in_array(strtolower($this->typet), array('gif', 'png', 'jpg', 'jpeg')))
						{
							$this->watermark($folderee . "/" . $filname,strtolower($this->typet), 'images/watermark.gif');
						}

						//then show
						$img_html_result .= get_up_tpl_box('image', array(
																			'b_title'	=> $lang['URL_F_IMG'], 
																			'b_bbc_title'=> $lang['URL_F_BBC'], 
																			'b_url_link'=> kleeja_get_link('image', $file_info),
																			));
	
						$img_html_result .= $extra_del;
						
						($hook = kleeja_run_hook('saveit_func_img_res_kljuploader')) ? eval($hook) : null; //run hook
						$this->total++;
						
						$this->errs[] = array($lang['IMG_DOWNLAODED'] . '<br />' . $img_html_result, 'index_info');
					}
					else 
					{
						//then show other files
						$else_html_result = get_up_tpl_box('file', array(
																			'b_title'	=> $lang['URL_F_FILE'], 
																			'b_bbc_title'=> $lang['URL_F_BBC'], 
																			'b_url_link'=> kleeja_get_link('file', $file_info),
																			));

						$else_html_result .= $extra_del;

						($hook = kleeja_run_hook('saveit_func_else_res_kljuploader')) ? eval($hook) : null; //run hook
						$this->total++;
						$this->errs[] = array($lang['FILE_DOWNLAODED'] . '<br />' . $else_html_result, 'index_info');	
					}

					($hook = kleeja_run_hook('saveit_func_kljuploader')) ? eval($hook) : null; //run hook
					
					if (isset($_POST['submitr']))
					{
						if(isset($_SESSION['FIILES_NOT_DUPLI']))
						{
							unset($_SESSION['FIILES_NOT_DUPLI']);
						}
					
						$_SESSION['FIILES_NOT_DUPLI'] = $_FILES;
					}
					elseif(isset($_POST['submittxt']))
					{
						if(isset($_SESSION['FIILES_NOT_DUPLI_LINKS']))
						{
							unset($_SESSION['FIILES_NOT_DUPLI_LINKS']);
						}
					
						$_SESSION['FIILES_NOT_DUPLI_LINKS'] = $_POST;
					}
						
					unset ($filename, $folderee, $sizeee, $typeee);
					//unset ($_SESSION['NO_UPLOADING_YET']);

	}#save it

	//
	//get file size 
	//source : http://nopaste.planerd.net/1139
   function get_remote_file_size($url, $method = "GET", $data = "", $redirect = 10)
   {
        $url = parse_url($url);
        $fp = @fsockopen ($url['host'], (!empty($url['port']) ? (int)$url['port'] : 80), $errno, $errstr, 30);
        if ($fp) 
		{
            $path = (!empty($url['path']) ? $url['path'] : "/").(!empty($url['query']) ? "?" . $url['query'] : "");
            $header = "\r\nHost: ".$url['host'];
            if("post" == strtolower($method))
			{
				$header .= "\r\nContent-Length: " . strlen($data);
            }
			fputs ($fp, $method." ".$path." HTTP/1.0" . $header . "\r\n\r\n". ("post" == strtolower($method) ? $data : ""));
            if(!feof($fp))
			{
                $scheme = fgets($fp);
                list(, $code ) = explode(" ", $scheme);
                $headers = array("Scheme" => $scheme);
            }
            while ( !feof($fp) )
			{
                $h = fgets($fp);
                if($h == "\r\n" OR $h == "\n") break;
                list($key, $value) = explode(":", $h, 2);
                $headers[$key] = trim($value);
                if($code >= 300 AND $code < 400 AND strtolower($key) == "location" AND $redirect > 0)
				{
                    return $this->get_remote_file_size($headers[$key], $method, $data, --$redirect);
				}
            }
            $body = "";
            /*while ( !feof($fp) ) $body .= fgets($fp);*/
            fclose($fp);
        }
        else
		{
			return (array("error" => array("errno" => $errno, "errstr" => $errstr)));
        }
		
		return (string)$headers["Content-Length"];
    }
    
	//prevent flooding
    function user_is_flooding()
    {
    	global $SQL, $dbprefix, $config;
    	
    	//if the value is zero (means that the function is disabled) then return false immediately
    	if(($this->id_user == '-1' && $config['guestsectoupload'] == 0) OR $this->id_user != '-1' && $config['usersectoupload'] == 0)
    	{
    		return false;
		}
    	
    	//In my point of view I see 30 seconds is not bad rate to stop flooding .. even though this minimum rate somtitme isn't enough to protect your site from flooding attacks 
    	$time = time() - (($this->id_user == '-1') ? $config['guestsectoupload'] : $config['usersectoupload']); 
    	
		$query = array(
					'SELECT'	=> 'f.time',
					'FROM'		=> "{$dbprefix}files f",
					'WHERE'     => 'f.time >= \'' . $time . '\' AND f.user_ip = \'' .  get_ip() . '\'',
				);

		if ($SQL->num_rows($SQL->build($query)) != 0)
		{
			return true;
		}
				
		return false;
	}
}#end class


//<-- EOF

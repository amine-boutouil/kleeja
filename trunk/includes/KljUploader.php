<?php
##################################################
#						Kleeja
#
# Filename : KljUplaoder.php
# purpose :  skeleton of script, based on class of Nadorino [@msn.com]
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author$ , $Rev$,  $Date::                           $
##################################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}

class KljUploader
{
    var $folder;
    var $action;		 //page action
    var $filesnum; 		//number of fields
    var $types; 		 // filetypes
    var $ansaqimages;   // imagestypes
    var $filename;     // filename
	//var $sizes;
	var $typet;
	var $sizet;
	var $id_for_url;
    var $filename2;  //alternative file name
    var $linksite;    //site link
    var $decode;     // decoding name with md5 or time or no
	var $id_user;
	var $errs = array();
	var $safe_code;	// captcha is on or off 



	/**
	// watermark
	// source : php.net
	 */
	 function watermark($name, $ext, $logo)
	 {
	 
		($hook = kleeja_run_hook('watermark_func_kljuploader')) ? eval($hook) : null; //run hook	
		
		if(!file_exists($name)) return;
		
		if (preg_match("/jpg|jpeg/",$ext))
		{
			$src_img = @imagecreatefromjpeg($name);
		}
		elseif (preg_match("/png/",$ext))
		{
			$src_img = @imagecreatefrompng($name);
		}
		elseif (preg_match("/gif/",$ext))
		{
			$src_img = @imagecreatefromgif($name);
		}

		$src_logo = imagecreatefrompng($logo);

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
					@imagepng($src_img, $name);
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
	//check exts inside file to be safe
	//
	function ext_check_safe ($filename)
	{
		$not_allowed =	array('php','php3' ,'php5', 'php4','asp' ,'shtml' , 'html' ,'htm' ,'xhtml' ,'phtml', 'pl', 'cgi');
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
		
		if (preg_match("/jpg|jpeg/", $ext))
		{
			$src_img = imagecreatefromjpeg($name);
		}
		elseif (preg_match("/png/", $ext))
		{
			$src_img = imagecreatefrompng($name);
		}
		elseif (preg_match("/gif/", $ext))
		{
			$src_img = imagecreatefromgif($name);
		}
		
		$old_x	= imageSX($src_img);
		$old_y	= imageSY($src_img);
		
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
		
		$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
		
		if (preg_match("/jpg|jpeg/", $ext))
		{
			imagejpeg($dst_img, $filename);
		}
		elseif (preg_match("/png/", $ext))
		{
			imagepng($dst_img, $filename);
		}
		elseif (preg_match("/gif/", $ext))
		{
			imagegif($dst_img, $filename);
		}
		

		imagedestroy($dst_img);
		imagedestroy($src_img);
	}




//
// prorcess
//
function process () 
{
		global $SQL,$dbprefix,$config,$lang;
		global $use_ftp,$ftp_server,$ftp_user,$ftp_pass,$ch;

		// check folder
		if(!file_exists($this->folder)) 
		{
			($hook = kleeja_run_hook('no_uploadfolder_kljuploader')) ? eval($hook) : null; //run hook	

			$jadid	=	mkdir($this->folder);
			$jadid2	=	mkdir($this->folder . '/thumbs');
			
			if($jadid)
			{
				$this->errs[] = $lang['NEW_DIR_CRT'];
				
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
					$this->errs[]=   $lang['PR_DIR_CRT'];
				} 
			}
			else
			{
				$this->errs[]= '<strong>' . $lang['CANT_DIR_CRT'] . '</strong>';
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


			//safe_code .. captcha is on
			if($this->safe_code && $wut==1)
			{
				if(!$ch->check_captcha($_POST['public_key'], $_POST['answer_safe']))
				{
					($hook = kleeja_run_hook('wrong_captcha_kljuploader_w1')) ? eval($hook) : null; //run hook	
					 return $this->errs[] = $lang['WRONG_VERTY_CODE'];
				}
			}
			else if($this->safe_code && $wut==2)
			{
				if(!$ch->check_captcha($_POST['public_key2'], $_POST['answer_safe2']))
				{
					($hook = kleeja_run_hook('wrong_captcha_kljuploader_w2')) ? eval($hook) : null; //run hook	
					 return $this->errs[] = $lang['WRONG_VERTY_CODE'];
				}
			}
			
			// uploading process 
			if ($wut == 1)
			{
				($hook = kleeja_run_hook('submit_filesupload_kljuploader')) ? eval($hook) : null; //run hook	
			
				//
				for($i=0;$i<$this->filesnum;$i++)
				{
					$this->filename2	= explode(".", $_FILES['file']['name'][$i]);
					$this->filename2	= $this->filename2[count($this->filename2)-1];
					$this->typet		= $this->filename2;
					$this->sizet		= $_FILES['file']['size'][$i];
					
						// decoding
						if($this->decode == "time")
						{
							$zaid = time();
							$this->filename2 = $this->filename . $zaid . $i . "." . $this->filename2;
						}
						elseif($this->tashfir == "md5")
						{
							$zaid	= md5(time());
							$zaid	= substr($zaid, 0, 10);
							$this->filename2 = $this->filename . $zaid . $i . "." . $this->filename2;
						}  
						else
						{
							//real name of file
							$this->filename2 = $_FILES['file']['name'][$i];
							
							($hook = kleeja_run_hook('another_decode_type_kljuploader')) ? eval($hook) : null; //run hook
						}
						

						if(empty($_FILES['file']['tmp_name'][$i]))
						{
							//if no file ? natin to do ,, why ? becuase its multiple fields
						}
						elseif(file_exists($this->folder . '/' . $_FILES['file']['name'][$i]))
						{
							$this->errs[]=  '[ ' . $_FILES['file']['name'][$i] . ' ] ' . $lang['SAME_FILE_EXIST'];
						}
						elseif(preg_match ("#[\\\/\:\*\?\<\>\|\"]#", $this->filename2))
						{
							$this->errs[]= $lang['WRONG_F_NAME'] . '[' . $_FILES['file']['name'][$i] . ']';
						}
						elseif($this->ext_check_safe($_FILES['file']['name'][$i]) == false)
						{
							$this->errs[]= $lang['WRONG_F_NAME'] . '[' . $_FILES['file']['name'][$i] . ']';
						}
						elseif(kleeja_check_mime($_FILES['file']['type'][$i], $this->types[strtolower($this->typet)]['group_id'], $_FILES['file']['tmp_name'][$i]) == false)
						{
							$this->errs[]= $lang['NOT_SAFE_FILE'] . '[' . $_FILES['file']['name'][$i] . ']';
						}
						elseif(!in_array(strtolower($this->typet), array_keys($this->types)))
						{
							//guest
							if($this->id_user == '-1')
							{
								$this->errs[]= '[ ' . $_FILES['file']['name'][$i] . ' ] ' . $lang['FORBID_EXT'] . '[' . $this->typet . '] <br /> <a href="' .  ($config['mod_writer'] ? "register.html" : "ucp.php?go=register") . '" title="' . htmlspecialchars($lang['REGISTER']) . '">' . $lang['REGISTER'] . '</a>';
							}
							//not guest
							else
							{
								$this->errs[]= '[ ' . $_FILES['file']['name'][$i] . ' ] ' . $lang['FORBID_EXT'] . '[' . $this->typet . ']';
							}
						}
						elseif($this->types[strtolower($this->typet)]['size'] > 0 && $this->sizet >= $this->types[strtolower($this->typet)]['size'])
						{
							$this->errs[]=  '[ ' .$_FILES['file']['name'][$i] . ' ] ' . $lang['SIZE_F_BIG'] . ' ' . Customfile_size($this->types[strtolower($this->typet)]['size']);
						}
						else
						{
							//
							// no errors , so uploading
							//
								if (!$use_ftp)
								{
											($hook = kleeja_run_hook('move_uploaded_file_kljuploader')) ? eval($hook) : null; //run hook	
											$file = move_uploaded_file($_FILES['file']['tmp_name'][$i], $this->folder . "/" . $this->filename2);
								}
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
												  $this->errs[]= $lang['CANT_CON_FTP'] . $ftp_server;
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
											$file = ftp_put($conn_id, $this->folder . "/" . $this->filename2, $_FILES['file']['tmp_name'][$i], $ftp_method);
											ftp_close($conn_id);
								}

								if ($file)
								{
									$this->saveit ($this->filename2, $this->folder, $this->sizet, $this->typet, $_FILES['file']['name'][$i]);
								} 
								else 
								{
									$this->errs[] = '[ ' . $this->filename2 . ' ] ' . $lang['CANT_UPLAOD'];
								}

						}
				}#for ... lmean loop


			}#wut=1
			elseif ($wut == 2 && $config['www_url'] == '1')
			{
				($hook = kleeja_run_hook('submit_urlupload_kljuploader')) ? eval($hook) : null; //run hook
				//looop text inputs
				for($i=0;$i<$this->filesnum;$i++)
				{

								$filename 			= basename($_POST['file'][$i]);
								$this->filename2	= explode(".", $filename);
								
								
								if(in_array($this->filename2[count($this->filename2)-1], array('html', 'php', 'html')))
								{
									$this->filename2 = $this->typet  = $this->filename2[count($this->filename2)-2];
								}
								else
								{
									$this->filename2 = $this->typet  = $this->filename2[count($this->filename2)-1];
								}
								
								//tashfer [decode]
								if($this->decode == "time")
								{
									$zaid = time();
									$this->filename2 = $this->filename . $zaid . $i . "." . $this->filename2;
								}
								elseif($this->tashfir == "md5")
								{
									$zaid=md5(time());
									$zaid=substr($zaid, 0, 10);
									$this->filename2 = $this->filename . $zaid . $i . "." . $this->filename2;
								}
								else
								{
									// real name of file
									$this->filename2 = $filename;
									($hook = kleeja_run_hook('another_decode_type_kljuploader')) ? eval($hook) : null; //run hook
								}
								//end tashfer


							if(empty($_POST['file'][$i]) || trim($_POST['file'][$i]) == $lang['PAST_URL_HERE'])
							{
								//nathin
							}
							else//big else
							{
									if(file_exists($this->folder . '/' . $filename))
									{
										$this->errs[]=  '[ ' . $_POST['file'][$i] . ' ] ' . $lang['SAME_FILE_EXIST'];
									}
									//elseif( preg_match ("#[\\\/\:\*\?\<\>\|\"]#", $this->filename2))
									//{
									//	$this->errs[]= $lang['WRONG_F_NAME'] . '[' . $_POST['file'][$i] . ']';
									//}
									//elseif($this->ext_check_safe($_POST['file'][$i]) == false)
									//{
									//	$this->errs[]= $lang['WRONG_F_NAME'] . '[' . $_POST['file'][$i] . ']';
									//}
									//elseif(kleeja_check_mime($_POST['file'][$i], $this->types[strtolower($this->typet)]['group_id'], $_FILES['file']['tmp_name'][$i]) == false)
									//{
									// $this->errs[]= $lang['FORBID_EXT'] . '[' . $_POST['file'][$i] . ']';
									//}
									elseif(!in_array(strtolower($this->typet),array_keys($this->types)))
									{
										$this->errs[]= '[ ' . $_POST['file'][$i] . ' ] ' . $lang['FORBID_EXT'] . '[' . $this->typet . ']';
									}
									else
									{
									
										//
										//end err .. start upload from url
										//
										if(!in_array(substr($_POST['file'][$i], 0, 4), array('http', 'ftp:')))
										{
											$_POST['file'][$i] = 'http://' . $_POST['file'][$i];
										}
										
										if(function_exists("fsockopen"))
										{
											$this->sizet = $this->get_remote_file_size($_POST['file'][$i]);
		
											if($this->types[strtolower($this->typet)]['size'] > 0 && $this->sizet >= $this->types[strtolower($this->typet)]['size'])
											{
												$this->errs[]=  $lang['SIZE_F_BIG'] . ' ' . Customfile_size($this->types[strtolower($this->typet)]['size']);
											}
											else
											{
												$data = fetch_remote_file($_POST['file'][$i], $this->folder . "/" . $this->filename2);
											}
										}
										else
										{
											$data = fetch_remote_file($_POST['file'][$i]);
											if($data != false)
											{
												$this->sizet = strlen($data);
												if($this->types[strtolower($this->typet)]['size'] > 0 && $this->sizet >= $this->types[strtolower($this->typet)]['size'])
												{
													$this->errs[]=  $lang['SIZE_F_BIG'] . ' ' . Customfile_size($this->types[strtolower($this->typet)]['size']);
												}
												else
												{
													//then ..write new file
													$fp2 = fopen($this->folder . "/" . $this->filename2, "w");
													fwrite($fp2, $data);
													fclose($fp2);
												}
											}
										}
										
										if($data === false)
										{
											$this->errs[]	= $lang['URL_CANT_GET'];		
										}
										
										if(!sizeof($this->errs))
										{
											$this->saveit ($this->filename2, $this->folder, $this->sizet, $this->typet);
										}

									}#else
							}//big else

				}#end loop
		}#end wut2

}#END process




	//
	// save data and insert in the database
	//
	function saveit ($filname, $folderee, $sizeee, $typeee, $real_filename = '')
	{
		global $SQL,$dbprefix,$config,$lang;

				// sometime cant see file after uploading.. but ..
				@chmod($folderee . '/' . $filname , 0755); //0755

				$name 	= (string)	$SQL->escape($filname);
				$size	= (int) 	$sizeee;
				$type 	= (string)	$SQL->escape($typeee);
				$folder	= (string)	$SQL->escape($folderee);
				$timeww	= (int)		time();
				$user	= (int)		$this->id_user;
				$code_del=(string)	md5(time());
				$ip		=  (@getenv('HTTP_X_FORWARDED_FOR')) ? getenv('HTTP_X_FORWARDED_FOR') : $_SERVER['REMOTE_ADDR'];
				$ip		= (string)	$SQL->escape($ip);	
				$realf	= (string)	$SQL->escape($real_filename);	
				
				$insert_query = array(
									'INSERT'	=> '`name` ,`size` ,`time` ,`folder` ,`type`,`user`,`code_del`,`user_ip`, `real_filename`',
									'INTO'		=> "`{$dbprefix}files`",
									'VALUES'	=> "'$name', '$size', '$timeww', '$folder','$type', '$user', '$code_del', '$ip', '$realf'"
									);
									
				($hook = kleeja_run_hook('qr_insert_new_file_kljuploader')) ? eval($hook) : null; //run hook
				
				if (!$SQL->build($insert_query)) 
				{ 
					$this->errs[]=  $lang['CANT_INSERT_SQL'];
				}
				
				if ($config['mod_writer'] == '1') 
				{
					$this->id_for_url  = ($config['id_form'] == 'filename') ?  str_replace('.', '-', $name) : $SQL->insert_id();
				}
				else
				{
					$this->id_for_url  = ($config['id_form'] == 'filename') ?  $name : $SQL->insert_id();
				}


				//calculate stats ..s
				$update_query = array(
									'UPDATE'	=> "{$dbprefix}stats",
									'SET'		=> "`files`=files+1,`sizes`=sizes+" . $size . ",`last_file`='" . $folder . "/" . $name . "'"
								);
								
				($hook = kleeja_run_hook('qr_update_no_files_kljuploader')) ? eval($hook) : null; //run hook
				if (!$SQL->build($update_query))
				{
					die($lang['CANT_UPDATE_SQL']);
				}
				
				//delete cache of stats !
				delete_cache('data_stats');

					//show del code link
					if ($config['del_url_file'])
					{
							$extra_del	= $lang['URL_F_DEL'] . ':<br /><textarea rows="2" cols="49" rows="1">';
							$extra_del	.= $this->linksite . (($config['mod_writer']) ? "del" . $code_del . ".html" : 'go.php?go=del&amp;cd=' . $code_del);
							$extra_del	.='</textarea><br />';
					}


					//show imgs
					if (in_array(strtolower($this->typet), array('png','gif','jpg','jpeg','tif','tiff')))
					{

						//make thumbs
						if( ($config['thumbs_imgs'] != 0) && in_array(strtolower($this->typet), array('png','jpg','jpeg','gif')))
						{
							list($thmb_dim_w, $thmb_dim_h) = @explode('*', $config['thmb_dims']);
							$this->createthumb($folderee . "/" . $filname, strtolower($this->typet), $folderee . '/thumbs/' . $filname, $thmb_dim_w, $thmb_dim_h);
							
							$extra_thmb 	= $lang['URL_F_THMB'] . ':<br /><textarea rows="2" cols="49">';
							if($config['id_form'] == "filename")
							{
								$thumb_link_o = $this->linksite . (($config['mod_writer']) ? "thumbf-" . $this->id_for_url . ".html" : "download.php?thmbf=" . $this->id_for_url);
								$extra_thmb .= '[url=' . $this->linksite . (($config['mod_writer']) ? "imagef-" . $this->id_for_url . ".html" : "download.php?imgf=" . $this->id_for_url) . '][img]' . $thumb_link_o . '[/img][/url]';
							}
							else
							{
								$thumb_link_o = $this->linksite . (($config['mod_writer']) ? "thumb" . $this->id_for_url . ".html" : "download.php?thmb=" . $this->id_for_url);
								$extra_thmb .= '[url=' . $this->linksite . (($config['mod_writer']) ? "image" . $this->id_for_url . ".html" : "download.php?img=" . $this->id_for_url) . '][img]' . $thumb_link_o . '[/img][/url]';
							}
							
							$extra_thmb 	.= '</textarea><br />';
							$extra_show_img = '<div style="text-align:center"><img src="' . $thumb_link_o . '" style="width:100px; height:100px" /></div><br />';
						}
						
						//write on image
						if( ($config['write_imgs'] != 0) && in_array(strtolower($this->typet), array('png', 'jpg', 'jpeg')))
						{
							$this->watermark($folderee . "/" . $filname,strtolower($this->typet), 'images/watermark.png');
						}

						//then show
						$img_html_result = $extra_show_img;
						
						if($config['id_form'] == "filename")
						{
							$img_html_result .= $lang['URL_F_IMG'] . ':<br /><textarea rows="2" cols="49">' . $this->linksite . (($config['mod_writer']) ? "imagef-" . $this->id_for_url . ".html" : "download.php?imgf=" . $this->id_for_url) . '</textarea><br />
								' . $lang['URL_F_BBC'] . ':<br /><textarea rows="2" cols="49">' .
								'[url=' . $this->linksite . (($config['mod_writer']) ? "imagef-" . $this->id_for_url . ".html" : "download.php?imgf=" . $this->id_for_url) . '][img]' . $this->linksite . (($config['mod_writer']) ? "imagef-" . $this->id_for_url . ".html" : "download.php?imgf=" . $this->id_for_url) . '[/img][/url]</textarea><br />';
						}
						else
						{
							$img_html_result .= $lang['URL_F_IMG'] . ':<br /><textarea rows="2" cols="49">' . $this->linksite . (($config['mod_writer']) ? "image" . $this->id_for_url . ".html" : "download.php?img=" . $this->id_for_url) . '</textarea><br />
								' . $lang['URL_F_BBC'] . ':<br /><textarea rows="2" cols="49">' .
								'[url=' . $this->linksite . (($config['mod_writer']) ? "image" . $this->id_for_url . ".html" : "download.php?img=" . $this->id_for_url) . '][img]' . $this->linksite . (($config['mod_writer']) ? "image" . $this->id_for_url . ".html" : "download.php?img=" . $this->id_for_url) . '[/img][/url]</textarea><br />';
						}
	
						$img_html_result .= $extra_thmb . $extra_del;
						
						($hook = kleeja_run_hook('saveit_func_img_res_kljuploader')) ? eval($hook) : null; //run hook
						
						$this->errs[] = $lang['IMG_DOWNLAODED'] . '<br />' . $img_html_result;
					}
					else 
					{
						//then show other files
						
						if($config['id_form'] == "filename")
						{
							$else_html_result = $lang['URL_F_FILE'] . ':<br /><textarea cols="49" rows="1">' . $this->linksite . (($config['mod_writer']) ? "downloadf-" . $this->id_for_url . ".html" : "download.php?filename=" . $this->id_for_url) . '</textarea><br />
												' . $lang['URL_F_BBC'] . ':<br /><textarea rows="2" cols="49">[url]' . $this->linksite . (($config['mod_writer']) ? "downloadf-" . $this->id_for_url . ".html" : "download.php?filename=" . $this->id_for_url) . '[/url]</textarea><br />
												' . $extra_del;
						}
						else
						{
							$else_html_result = $lang['URL_F_FILE'] . ':<br /><textarea cols="49" rows="1">' . $this->linksite . (($config['mod_writer']) ? "download-" . $this->id_for_url . ".html" : "download.php?id=" . $this->id_for_url) . '</textarea><br />
												' . $lang['URL_F_BBC'] . ':<br /><textarea rows="2" cols="49">[url]' . $this->linksite . (($config['mod_writer']) ? "download" . $this->id_for_url . ".html" : "download.php?id=" . $this->id_for_url) . '[/url]</textarea><br />
												' . $extra_del;
						}
						

						($hook = kleeja_run_hook('saveit_func_else_res_kljuploader')) ? eval($hook) : null; //run hook
						
						$this->errs[] = $lang['FILE_DOWNLAODED'] . '<br />' . $else_html_result;	
					}

					($hook = kleeja_run_hook('saveit_func_kljuploader')) ? eval($hook) : null; //run hook
						
					unset ($filename, $folderee, $sizeee, $typeee);

	}#save it

	//
	//get file size 
	//source : http://nopaste.planerd.net/1139
   function get_remote_file_size($url, $method = "GET", $data = "", $redirect = 10)
   {
        $url = parse_url($url);
        $fp = @fsockopen ($url['host'], (!empty($url['port']) ? (int)$url['port'] : 80), $errno, $errstr, 30);
        if ($fp) {
            $path = (!empty($url['path']) ? $url['path'] : "/").(!empty($url['query']) ? "?" . $url['query'] : "");
            $header = "\r\nHost: ".$url['host'];
            if("post" == strtolower($method)) $header .= "\r\nContent-Length: " . strlen($data);
            fputs ($fp, $method." ".$path." HTTP/1.0" . $header . "\r\n\r\n". ("post" == strtolower($method) ? $data : ""));
            if(!feof($fp)) {
                $scheme = fgets($fp);
                list(, $code ) = explode(" ", $scheme);
                $headers = array("Scheme" => $scheme);
            }
            while ( !feof($fp) ) {
                $h = fgets($fp);
                if($h == "\r\n" OR $h == "\n") break;
                list($key, $value) = explode(":", $h, 2);
                $headers[$key] = trim($value);
                if($code >= 300 AND $code < 400 AND strtolower($key) == "location" AND $redirect > 0)
                    return $this->get_remote_file_size($headers[$key], $method, $data, --$redirect);
            }
            $body = "";
            /*while ( !feof($fp) ) $body .= fgets($fp);*/
            fclose($fp);
        }
        else return (array("error" => array("errno" => $errno, "errstr" => $errstr)));
        return (string)$headers["Content-Length"];
    }
}#end class

?>
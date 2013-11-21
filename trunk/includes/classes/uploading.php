<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}

#includes imortant functions
include PATH . 'includes/up_helpers/others.php';
include PATH . 'includes/up_helpers/thumbs.php';
include PATH . 'includes/up_helpers/watermark.php';
include PATH . 'includes/up_helpers/remote_uploading.php';
include PATH . 'includes/functions/functions_files.php';


/*
 * uploading class, the most important class in Kleeja
 * Where files uploaded by this class, depend on Kleeja settings
 */
class uploading
{
	/**
	 * Allowed extensions to upload
	 */
	public $allowed_extensions = array();

	/**
	 * Total uploaded files
	 */
	public $total = 0;
	
	/**
	 * Errors or info messages, shown after uploading as a loop
	 */
	public $messages = array();

	/**
	 * Local folder to upload to
	 */
	public $uploading_folder;

	/**
	 * local or ftp
	 */
	public $uploading_type = 'local';

	/**
	 * initiating uploading class
	 * @return void
	 */
	public function __construct()
	{
		global $config;

		#local folder to upload to
		$this->uploading_folder = trim($config['foldername']);
		if($this->uploading_folder == '')
		{
			$this->uploading_folder = 'uploads';
		}

		#if local, check for current folder is it exists?
		if($this->uploading_type == 'local' && !file_exists($this->uploading_folder))
		{
			make_folder($this->uploading_folder);
		}
		
		# check the live-exts-folder, live exts plugin codes
		if(!empty($config['imagefolderexts']) && !file_exists($config['imagefolder'])) 
		{
			make_folder($config['imagefolder']);
		}
	}

	/**
	 * Processing current upload, aka 'after user click upload button to upload his files'
	 *
	 * @param bool $just_check If enabled, no uploading will occur, just checking process 
	 */
	public function process($just_check = false) 
	{
		global $SQL, $dbprefix, $config, $lang;

		($hook = kleeja_run_hook('kljuploader_process_func')) ? eval($hook) : null; //run hook	

		
		#To prevent flooding, user must wait, waiting-time is grapped from Kleeja settings, admin is exceptional
		if(!user_can('enter_acp') && user_is_flooding())
		{
			return $this->messages[] = array(sprintf($lang['YOU_HAVE_TO_WAIT'], $config['usersectoupload']), 'error');
		}

		#if captcha enabled
		if($config['safe_code'])
		{
			#captcha is wrong
			if(!kleeja_check_captcha())
			{
				return $this->messages[] = array($lang['WRONG_VERTY_CODE'], 'error');
			}
		}


		#files uploading
		if(ip('submit_files'))
		{
			$files = rearrange_files_input($_FILES);

			if(empty($files))
			{
				return $this->messages[] = array($lang['CHOSE_F'], 'error');
			}

			foreach($files as $file)
			{
				#no file content
				if(empty($file['tmp_name']))
				{
					continue;
				}

				#if filename conatins bad chars or doesnt have an extension
				if(strpos($file['name'], '.') === false || preg_match("#[\\\/\:\*\?\<\>\|\"]#", $file['name']))
				{
					$this->messages[] = array(sprintf($lang['WRONG_F_NAME'], htmlspecialchars($file['name'])), 'error');
					continue;
				}
	
				$file_extension = strtolower(array_pop(explode('.', $file['name'])));

				#check for bad file extensions
				if(ext_check_safe($file['name']) == false)
				{
					$this->messages[] = array(sprintf($lang['WRONG_F_NAME'], htmlspecialchars($file['name'])), 'error');
				}

				#if file extension is not allowed?
				if(!in_array($file_extension, array_keys($this->types)))
				{
					$this->messages[] = array(sprintf($lang['FORBID_EXT'], $this->typet), 'error');
					continue;
				}

				#file check for first 265 content
				if(check_file_content($file['tmp_name']) == false)
				{
					$this->messages[] = array(sprintf($lang['NOT_SAFE_FILE'], htmlspecialchars($file['name'])), 'error');
				}

				#file size exceed allowed one
				if($this->types[$file_extension] > 0 && $file['size'] >= $this->types[$file_extension])
				{
					$this->messages[] = array(sprintf($lang['SIZE_F_BIG'], htmlspecialchars($file_extension['name']), readable_size($this->types[$file_extension])), 'error');
				}
				
				
				#modify filename to apply Admin changes 
				$filename = change_filename($file['name'], $file_extension);
			
				($hook = kleeja_run_hook('uploading_process_func_loop_files')) ? eval($hook) : null; //run hook
	
				#if this is listed as live-ext from Kleeja settings 
				$live_exts	= array_map('trim', explode(',', $config['imagefolderexts']));
				$folder_to_upload = $this->uploading_folder;
				if(in_array($file_extension, $live_exts))
				{
					# live-exts folder, if empty use default folder
					$folder_to_upload = trim($config['imagefolder']) == '' ? trim($config['foldername']) : trim($config['imagefolder']);
				}


				$is_img = in_array($file_extension, array('png','gif','jpg','jpeg', 'bmp')) ? true : false;

				#now upload
				$upload_result = move_uploaded_file($file['tmp_name'], $folder_to_upload . '/' . $filename);

				if($upload_result)
				{
					$this->add_to_database($filename, $folder_to_upload, $file['size'], $file_extension, $file['name']);
				} 
				else 
				{
					$this->messages[] = array(sprintf($lang['CANT_UPLAOD'], $filename), 'error');
				}
			}
		}
		#end-ip-submit-files
	}


	/**
	 * Insert the file data to database, also make other things like, 
	 * thumb, watermark and etc..
	 */
	public function add_to_database($filname, $folderee, $sizeee, $typeee, $real_filename = '')
	{
		global $SQL, $dbprefix, $config, $lang;

		#sometime cant see file after uploading.. but ..
		@chmod($folderee . '/' . $filname , 0644);
		
		#file data, filter them
		$name 	= (string)	$SQL->escape($filname);
		$size	= (int) 	$sizeee;
		$type 	= (string)	strtolower($SQL->escape($typeee));
		$folder	= (string)	$SQL->escape($folderee);
		$timeww	= (int)		time();
		$user	= (int)		$this->id_user;
		$code_del=(string)	md5($name . uniqid());
		$ip		= (string)	$SQL->escape(get_ip());
		$realf	= (string)	$SQL->escape($real_filename);
		$id_form= (string)	$SQL->escape($config['id_form']);
		$is_img = in_array($type, array('png','gif','jpg','jpeg', 'bmp')) ? true : false;
	
		# insertion query
		$insert_query = array(
								'INSERT'	=> 'name ,size ,time ,folder ,type,user,code_del,user_ip, real_filename, id_form',
								'INTO'		=> "{$dbprefix}files",
								'VALUES'	=> "'$name', '$size', '$timeww', '$folder','$type', '$user', '$code_del', '$ip', '$realf', '$id_form'"
								);

		($hook = kleeja_run_hook('qr_insert_new_file_kljuploader')) ? eval($hook) : null; //run hook

		# do the query
		$SQL->build($insert_query);

		# orginal name of file to use it in the file url
		$this->name_for_url  = $name;
		# inset id so it can be used in url like in do.php?id={id_for_url}
		$this->id_for_url  = $SQL->id();

		# update Kleeja stats
		$update_query = array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> ($is_img ? "imgs=imgs+1" : "files=files+1") . ",sizes=sizes+" . $size . ""
							);

		($hook = kleeja_run_hook('qr_update_no_files_kljuploader')) ? eval($hook) : null; //run hook
	
		$SQL->build($update_query);
	
	
		# inforamation of file, used for generating a url boxes
		$file_info = array('::ID::'=>$this->id_for_url, '::NAME::'=>$this->name_for_url, '::DIR::'=> $folderee, '::FNAME::'=>$realf, '::EXT::'=> $type, '::CODE::'=>$code_del);

		# show del code link box
		$extra_del = '';
		if ($config['del_url_file'])
		{
			$extra_del	= get_up_tpl_box('del_file_code', array('b_title'=> $lang['URL_F_DEL'], 'b_code_link'=> kleeja_get_link('del', $file_info)));
		}

		//show imgs
		if($is_img)
		{
			$img_html_result = '';

			# get default thumb dimensions
			$thmb_dim_w = $thmb_dim_h = 150;
			if(strpos($config['thmb_dims'], '*') !== false)
			{
				list($thmb_dim_w, $thmb_dim_h) = array_map('trim', explode('*', $config['thmb_dims']));
			}

			# generate thumb now
			helper_thumb($folderee . '/' . $filname, strtolower($this->typet), $folderee . '/thumbs/' . $filname, $thmb_dim_w, $thmb_dim_h);

			if(($config['thumbs_imgs'] != 0) && in_array(strtolower($this->typet), array('png','jpg','jpeg','gif', 'bmp')))
			{		
				$img_html_result .= get_up_tpl_box('image_thumb', array(
																			'b_title'	=> $lang['URL_F_THMB'], 
																			'b_url_link'=> kleeja_get_link('image', $file_info), 
																			'b_img_link'=> kleeja_get_link('thumb', $file_info)
																			));
			}

			# watermark on image
			if(($config['write_imgs'] != 0) && in_array(strtolower($this->typet), array('gif', 'png', 'jpg', 'jpeg', 'bmp')))
			{
				helper_watermark($folderee . "/" . $filname, strtolower($this->typet));
			}

			#then show, image box
			$img_html_result .= get_up_tpl_box('image', array(
																'b_title'	=> $lang['URL_F_IMG'], 
																'b_bbc_title'=> $lang['URL_F_BBC'], 
																'b_url_link'=> kleeja_get_link('image', $file_info),
															));
			
			#add del link box to the result if there is any
			$img_html_result .= $extra_del;
						
			($hook = kleeja_run_hook('saveit_func_img_res_kljuploader')) ? eval($hook) : null; //run hook
			$this->total++;
			
			#show success message
			$this->messages[] = array($lang['IMG_DOWNLAODED'] . '<br />' . $img_html_result, 'info');
		}
		else 
		{
			#then show other files
			$else_html_result = get_up_tpl_box('file', array(
																'b_title'	=> $lang['URL_F_FILE'], 
																'b_bbc_title'=> $lang['URL_F_BBC'], 
																'b_url_link'=> kleeja_get_link('file', $file_info),
															));
			#add del link box to the result if there is any
			$else_html_result .= $extra_del;

			($hook = kleeja_run_hook('saveit_func_else_res_kljuploader')) ? eval($hook) : null; //run hook
			$this->total++;
			
			#show success message
			$this->messages[] = array($lang['FILE_DOWNLAODED'] . '<br />' . $else_html_result, 'info');	
		}

		($hook = kleeja_run_hook('saveit_func_kljuploader')) ? eval($hook) : null; //run hook

		# clear some variables from memory
		unset($filename, $folderee, $sizeee, $typeee);
	}
}

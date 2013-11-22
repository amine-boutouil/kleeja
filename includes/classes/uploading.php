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
include PATH . 'includes/functions/functions_uploading.php';


/**
 * uploading class, the most important class in Kleeja
 * Where files uploaded by this class, depend on Kleeja settings
 * @package Uploading
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
	 * Error messages shown after uploading as a loop
	 */
	public $errors = array();

	/**
	 * The results, files data
	 */
	public $results = array();

	/**
	 * Local folder to upload to
	 */
	public $uploading_folder;

	/**
	 * local or ftp
	 */
	public $uploading_type = 'local';

	/**
	 * Default dimesions of thumbs
	 */
	public $thumb_dimensions = array('width'=>150, 'height'=>150);


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
		
		# get default thumb dimensions
		if(strpos($config['thmb_dims'], '*') !== false)
		{
			list($this->thumb_dimensions['width'], $this->thumb_dimensions['height']) = array_map('intval', explode('*', $config['thmb_dims']));
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

		($hook = kleeja_run_hook('process_func_uploading_cls')) ? eval($hook) : null; //run hook	

		
		#To prevent flooding, user must wait, waiting-time is grapped from Kleeja settings, admin is exceptional
		if(!user_can('enter_acp') && user_is_flooding())
		{
			return $this->errors[] = sprintf($lang['YOU_HAVE_TO_WAIT'], $config['usersectoupload']);
		}

		#if captcha enabled
		if($config['safe_code'])
		{
			#captcha is wrong
			if(!kleeja_check_captcha())
			{
				return $this->errors[] = $lang['WRONG_VERTY_CODE'];
			}
		}


		#files uploading
		$files = rearrange_files_input($_FILES['file']);

		if(empty($files))
		{
			$this->errors[] = $lang['CHOSE_F'];
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
				$this->errors[] = sprintf($lang['WRONG_F_NAME'], htmlspecialchars($file['name']));
				continue;
			}

			$file_extension = explode('.', $file['name']);
			$file_extension = strtolower(array_pop($file_extension));

			#check for bad file extensions
			if(ext_check_safe($file['name']) == false)
			{
				$this->errors[] = sprintf($lang['WRONG_F_NAME'], htmlspecialchars($file['name']));
				continue;
			}

			#if file extension is not allowed?
			if(!in_array($file_extension, array_keys($this->allowed_extensions)))
			{
				$this->errors[] = sprintf($lang['FORBID_EXT'], $this->typet);
				continue;
			}

			#file check for first 265 content
			if(check_file_content($file['tmp_name']) == false)
			{
				$this->errors[] = sprintf($lang['NOT_SAFE_FILE'], htmlspecialchars($file['name']));
				continue;
			}

			#file size exceed allowed one
			if($this->allowed_extensions[$file_extension] > 0 && $file['size'] >= $this->allowed_extensions[$file_extension])
			{
				$this->errors[] = sprintf($lang['SIZE_F_BIG'], htmlspecialchars($file_extension['name']), readable_size($this->allowed_extensions[$file_extension]));
				continue;
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
				$folder_to_upload = trim($config['imagefolder']) == '' ? trim($config['foldername']) : $this->uploading_folder;
			}

			#is this file an image?
			$is_img = in_array($file_extension, array('png', 'gif', 'jpg', 'jpeg')) ? true : false;

			#now upload
			$upload_result = move_uploaded_file($file['tmp_name'], $folder_to_upload . '/' . $filename);

			#if uploading went ok
			if($upload_result)
			{
				#sometime can nott see the file after uploading without this fix
				@chmod($folder . '/' . $filename , 0644);
				#generate delete code
				$delete_code = md5($filename . uniqid());

				#insert to the DB
				$insert_id = $this->add_to_database($filename, $folder_to_upload, $file['size'], $file_extension, $file['name'], $delete_code);
				#if insertion goes bad, rollback, delete the file and show error
				if(!$insert_id)
				{
					@unlink($folder . '/' . $filname);
					$this->errors[] = sprintf($lang['CANT_UPLAOD'], $filename);
					continue;
				}

				# inforamation of file, used for generating a url boxes
				$file_info = array('::ID::'=>$insert_id, '::NAME::'=>$filename, '::DIR::'=> $folder_to_upload, '::FNAME::'=>$file['name'], '::EXT::'=> $file_extension, '::CODE::'=>$delete_code);

				#if image
				if($is_img)
				{
					# generate thumb always
					create_thumb($folder_to_upload . '/' . $filename, $file_extension, $folder_to_upload . '/thumbs/' . $filename, $this->thumb_dimensions['width'], $this->thumb_dimensions['height']);
					#show thumb if enabled
					if($config['thumbs_imgs'])
					{
						$this->results[$insert_id]['thumb'] = kleeja_get_link('thumb', $file_info);
					}
					#if watermark enabled
					if($config['write_imgs'])
					{
						create_watermark($folder_to_upload . '/' . $filename, $file_extension);
					}
					
					$this->results[$insert_id]['image'] = kleeja_get_link('image', $file_info);
				}
				#is file
				else
				{
					$this->results[$insert_id]['file'] = kleeja_get_link('file', $file_info);
				}
				
				#if delete code is enabled to be displayed
				if($config['del_url_file'])
				{
					 $this->results[$insert_id]['delete_code'] = kleeja_get_link('del', $file_info);
				}
				
				#uploaded files increment++
				$this->total++;
			} 
			else 
			{
				$this->errors[] = sprintf($lang['CANT_UPLAOD'], $filename);
			}
		}
		#end-foreach
		
		#total files equal zero, then show a message to tell user to select files
		if($this->total == 0)
		{
			$this->errors[] = $lang['CHOSE_F'];
		}
	}


	/**
	 * Insert the file data to the database
	 */
	public function add_to_database($filname, $folder, $size, $ext, $real_filename = '', $delete_code = '')
	{
		global $SQL, $dbprefix, $config, $lang, $user;

		$is_img = in_array($ext, array('png', 'gif', 'jpg', 'jpeg')) ? true : false;
	
		
		$query = array(
						'INSERT'	=> 'name, size, time, folder, type, user, code_del, user_ip, real_filename, id_form',
						'INTO'		=> "{$dbprefix}files",
						'VALUES'	=> "'" . $SQL->escape($filname) . "', " . intval($size) . ", " . time() . ", '" . $SQL->escape($folder) . "'," . 
									"'" . $SQL->escape($ext) . "', " . intval($user->data['id']) . ", '" .  $SQL->escape($delete_code) . "', '" . $SQL->escape($user->data['ip']) . "'," .
										"'" . $SQL->escape($real_filename) . "', '" . $SQL->escape($config['id_form']) . "'"
					);

		($hook = kleeja_run_hook('add_to_database_qr_uploading_cls')) ? eval($hook) : null; //run hook

		# do the query
		$SQL->build($query);

		# inset id so it can be used in url like in do.php?id={id_for_url}
		$insert_id = $SQL->id();
		
		#failed
		if(!$insert_id)
		{
			return false;
		}

		# update Kleeja stats
		$update_query = array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> ($is_img ? "imgs=imgs+1" : "files=files+1") . ",sizes=sizes+" . $size . ""
							);

		($hook = kleeja_run_hook('add_to_database_qr2_uploading_cls')) ? eval($hook) : null; //run hook
	
		$SQL->build($update_query);

		return $insert_id;
	}
}

<?php
	//langs
	//part of admin extensions
	//conrtoll languages and their words
	//kleeja.com
	
	// not for directly open
	if (!defined('IN_ADMIN'))
	{
		exit();
	}



switch ($_GET['lan_t']) 
{
		default:
		case "ln" :
		
		//for style ..
		$stylee = "admin_langs";
		$action 		= "admin.php?cp=langs&lan_t=ln";

		//get styles
		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}lists",
					'WHERE'		=> "list_type=2"
					);
						
		$result = $SQL->build($query);

		while($row=$SQL->fetch_array($result))
		{
				$arr[] = array( lang_id =>$row['list_id'],
								lang_name =>$row['list_name'],
							);

		}
		$SQL->freeresult($result);
	

		//after submit ////////////////
		if (isset($_POST['submit']))
		{
			$lang_id 		= intval($_POST['lang_choose']);
			
			switch($_POST['method'])
			{
				case '1': //show templates
					//for style ..
					$stylee = "admin_show_words";
					//words
					$action 		= "admin.php?cp=langs&lan_t=lang_orders";
					
					//get_tpls
					$query = array(
								'SELECT'	=> '*',
								'FROM'		=> "{$dbprefix}lang",
								'WHERE'		=> "lang_id=" . $lang_id
								);
									
					$result = $SQL->build($query);
					
					$arr	=	array();
					
					while($row=$SQL->fetch_array($result))
					{
							$arr[] = array(
											lang_word =>$row['word'],
											lang_trans =>$row['trans']
							);
					}
					$SQL->freeresult($result);
					
				break;
			
				case '2': // del the lang
				
						//lang number 1 not for deleting 
						if($lang_id != 2)
						{
							$query_del = array(
											'DELETE'	=> "{$dbprefix}lists",
											'WHERE'		=> "list_id='". $lang_id ."' AND list_type=2"
											);

											
							if (!$SQL->build($query_del)) {die($lang['CANT_DELETE_SQL']);}	
											
							$query_del2 = array(
											'DELETE'	=> "{$dbprefix}lang",
											'WHERE'		=> 'lang_id='. $lang_id
											);		
											
							if (!$SQL->build($query_del2)) {die($lang['CANT_DELETE_SQL'].'2');}	
							
							//show msg
							$text	= $lang['LANG_DELETED'];
						}
						else
						{
							//show msg
							$text	= $lang['LANG_1_NOT_FOR_DEL'];
						}

							$stylee	= "admin_info";
						
				break;
				
				case '3': //export as xml 
				
					//get lang information
					$query = array(
								'SELECT'	=> '*',
								'FROM'		=> "{$dbprefix}lists",
								'WHERE'		=> "list_id='". $lang_id ."' AND list_type=2"
								);
									
					$result = $SQL->build($query);

					$lang_info	=	$SQL->fetch_array($result);
					
					//build the xml sheet
					
					$xml_data = "<?xml version=\"1.0\" encoding=\"utf-8\"?".">\r\n";
					$xml_data .= "<kleeja>\r\n";
					$xml_data .= "<info>\r\n";
					$xml_data .= "\t<lang_name>" . $lang_info['list_name']  . "</lang_name>\r\n";
					$xml_data .= "\t<lang_author>" . $lang_info['list_author']  . "</lang_author>\r\n";
					$xml_data .= "</info>\r\n";
					$xml_data .= "<words>\r\n";
					
					//get tpls
					$query2 = array(
								'SELECT'	=> '*',
								'FROM'		=> "{$dbprefix}lang",
								'WHERE'		=> "lang_id='" . $lang_id ."'"
								);
									
					$result2 = $SQL->build($query2);

					while($row=$SQL->fetch_array($result2))
					{
						$xml_data .= "\t<word name=\"" . $row['word'] . "\">";
						$xml_data .= "<![CDATA[" . $row['trans'] . "]]>";
						$xml_data .= "</word>\r\n";
					}
					$SQL->freeresult($result);
				
					$xml_data .= "</words>\r\n";
					$xml_data .= "</kleeja>\r\n";
				
					//now , lets export
					header('Pragma: no-cache');
					header("Content-Type: application/xml; name=" . $lang_info['list_name'] . ".xml");
					header("Content-disposition: attachment; filename=" . $lang_info['list_name'] . ".xml");
					echo $xml_data;
					exit;
					
				break;
				
			}
		}
		//new style from xml
		if(isset($_POST['submit_new_lang']))
		{
		
			$text	=	'';
			// oh , some errors
			if($_FILES['imp_file']['error'])
			{
				$text	= $lang['ERR_IN_UPLOAD_XML_FILE'];
			}
			else if(!is_uploaded_file($_FILES['imp_file']['tmp_name']))
			{
				$text	= $lang['ERR_UPLOAD_XML_FILE_NO_TMP'];
			}

			//get content
			$contents = @file_get_contents($_FILES['imp_file']['tmp_name']);
			// Delete the temporary file if possible
			
			// Are there contents?
			if(!trim($contents))
			{
				$text	= $lang['ERR_UPLOAD_XML_NO_CONTENT'];
			}
						
						
			if(empty($text))
			{
				if(creat_lang_xml($contents))
				{
					$text	= $lang['NEW_LANG_ADDED'];	
				}
				else
				{
					$text	= $lang['ERR_IN_UPLOAD_XML_FILE'];
				}
			}		
						
					
					
			$stylee	= "admin_info";
		}
		
		break; 
		
		
		case "lang_orders" :
		
		//edit or del tpl 
		if(isset($_POST['words_submit']))
		{
			
			//lang id 
			$lang_id	=	intval($_POST['lang_id']);
			//tpl name 
			$lang_word	=	$SQL->escape($_POST['word_choose']);
			
			if(!$lang_id)
			{
				exit('lang_id is not exists!!');
			}
			
			if(empty($lang_word))
			{
				exit($lang['NO_WORD_SHOOSED']);
			}
			
			switch($_POST['method'])
			{	
				case '1': //edit tpl
					//for style ..
					$stylee		= "admin_edit_word";
					$action 	= "admin.php?cp=langs&lan_t=lang_orders";

					
					$query = array(
								'SELECT'	=> 'word, trans',
								'FROM'		=> "{$dbprefix}lang",
								'WHERE'		=>	"lang_id='$lang_id' AND word='$lang_word'"
								);
											
					$get_lang			= $SQL->fetch_array($SQL->build($query));

				break;
				
				case '2' : //delete word
				
						$query_del = array(
										'DELETE'	=> "{$dbprefix}lang",
										'WHERE'		=>	"lang_id='$lang_id' AND word='$lang_word'"
										);
															
						if (!$SQL->build($query_del)) {die($lang['CANT_DELETE_SQL']);}	
						
						//show msg
						$text	= $lang['WORD_DELETED'];
						$stylee	= "admin_info";
						
				break;
			}
		}
		
		// submit edit of tpl
		if(isset($_POST['word_edit_submit']))
		{
			//lang id 
			$lang_id	=	intval($_POST['lang_id']);
			// name 
			$lang_word	=	$SQL->escape($_POST['word_choose']);
			$word		=	$SQL->escape($_POST['word']);	
			$trans		=	$SQL->escape($_POST['trans']);	
			
			if(!$lang_id)
			{
				exit('lang_id is not exists!!');
			}
			
			if(empty($lang_word) || empty($word))
			{
				exit($lang['NO_WORD_SHOOSED']);
			}	

			//update
			$update_query = array(
									'UPDATE'	=> "{$dbprefix}lang",
									'SET'		=> "word='". $word ."', trans='". $trans ."'",
									'WHERE'		=>	"lang_id='$lang_id' AND word='$lang_word'"
								);

			if ($SQL->build($update_query))
			{
					//delete cache ..
					if (file_exists('cache/langs_' . $lang_id . '.php'))
					{
						@unlink('cache/langs_' . $lang_id . '.php');
					}
					
					//show msg
					$text	= $lang['WORD_UPDATED'];
					$stylee	= "admin_info";
			}
			else
			{
					die($lang['CANT_UPDATE_SQL']);
			}	
		
		}
		
		//new template file
		if(isset($_POST['submit_new_word']))
		{
		
			//lang id 
			$lang_id	=	intval($_POST['lang_id']);
			$word		=	$SQL->escape($_POST['new_word']);	
			$trans		=	$SQL->escape($_POST['new_trans']);	
		
			if(!$lang_id)
			{
				exit('lang_id is not exists!!');
			}
			
			if(empty($word))
			{
				exit($lang['NO_WORD_SHOOSED']);
			}	

			
			$insert_query = array(
							'INSERT'	=> 'lang_id, word, trans',
							'INTO'		=> "{$dbprefix}lang",
							'VALUES'	=> "'$lang_id','$word','$trans'"
							);

			
			if($SQL->build($insert_query))
			{
			
					//delete cache ..
					if (file_exists('cache/langs_' . $lang_id . '.php'))
					{
						@unlink('cache/langs_' . $lang_id . '.php');
					}
					
					$text	= $lang['WORD_CREATED'] . "<META HTTP-EQUIV=\"refresh\" CONTENT=\"2; URL=./admin.php?cp=langs&lan_t=ln\">\n";;
					$stylee	= "admin_info";
			}
			else
			{
				die($lang['CANT_INSERT_SQL']);	
			}
		
		
		}
		break;	
		
}	



?>
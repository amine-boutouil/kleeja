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

/**
* Usefull constants to force some settings :
* 
* FORCE_COOKIES, DISABLE_INTR
*/

class usrcp
{
	// this function like a traffic sign :)
	function data ($name, $pass, $hashed = false, $expire = 86400, $loginadm = false)
	{
		global $config;

		//return user system to normal
		if(defined('DISABLE_INTR') || $config['user_system'] == '' || empty($config['user_system']))
		{
			$config['user_system'] = '1';
		}

		//expire
		$expire = time() + intval($expire);

		($hook = kleeja_run_hook('data_func_usr_class')) ? eval($hook) : null; //run hook

		if($config['user_system'] != '1')
		{
			if(file_exists(PATH . 'includes/auth_integration/' . trim($config['user_system']) . '.php'))
			{	
				include_once (PATH . 'includes/auth_integration/' . trim($config['user_system']) . '.php');
				return (kleeja_auth_login(trim($name), trim($pass), $hashed, $expire, $loginadm) ? true : false);
			}
		}

		//normal 
		return $this->normal(trim($name), trim($pass), $hashed, $expire, $loginadm);
	}

	//get username by id
	function usernamebyid ($user_id) 
	{
		global $config;

		//return user system to normal
		if(defined('DISABLE_INTR'))
		{
			$config['user_system'] = '1';
		}

		if($config['user_system'] != '1')
		{
			if(file_exists(PATH . 'includes/auth_integration/' . trim($config['user_system']) . '.php'))
			{	
				include_once (PATH . 'includes/auth_integration/' . trim($config['user_system']) . '.php');
				return kleeja_auth_username($user_id);
			}
		}

		//normal system
		$u = $this->get_data('name', $user_id);	
		return $u['name'];
	}

	//now ..  .. our table
	function normal ($name, $pass, $hashed = false, $expire, $loginadm = false)
	{
		global $SQL, $dbprefix, $config, $userinfo;

		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "`{$dbprefix}users`",
					);
		
		if($hashed)
		{
			$query['WHERE'] = "id=" . intval($name) . " and password='" . $SQL->escape($pass) . "'";
		}
		else
		{
			$query['WHERE'] = "clean_name='" . $SQL->real_escape($this->cleanusername($name)) . "'";
		}

		($hook = kleeja_run_hook('qr_select_usrdata_n_usr_class')) ? eval($hook) : null; //run hook			
		$result = $SQL->build($query);

		if ($SQL->num_rows($result) != 0) 
		{
			while($row=$SQL->fetch_array($result))
			{
				if(empty($row['password'])) //more security
				{
					return false;
				}

				$phppass = $hashed ?  $pass : $pass . $row['password_salt'];

				//CHECK IF IT'S MD5 PASSWORD
				if(strlen($row['password']) == '32' && empty($row['password_salt']))   
				{
					$passmd5 = md5($pass);

					//update old md5 hash to phpass hash
					if($row['password'] == $passmd5)
					{
						//new salt
						$new_salt = substr(kleeja_base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
						//new password hash
						$new_password = $this->kleeja_hash_password(trim($pass) . $new_salt);


						($hook = kleeja_run_hook('qr_update_usrdata_md5_n_usr_class')) ? eval($hook) : null; //run hook	

						//update now !!
						$update_query = array(
									'UPDATE'	=> "`{$dbprefix}users`",
									'SET'		=> "password='" . $new_password . "' ,password_salt='" . $new_salt . "'",
									'WHERE'		=>	"id=" . intval($row['id'])
							);

						$SQL->build($update_query);
					}
					else //if the password is wrong
					{
						return false;
					}
				}
				else if(($phppass != $row['password'] && $hashed) || ($this->kleeja_hash_password($phppass, $row['password']) != true && $hashed == false))
				{
					return false;
				}

				//Avoid dfining constants again
				if(!$loginadm)
				{
					define('USER_ID', $row['id']);
					define('USER_NAME', $row['name']);
					define('USER_MAIL', $row['mail']);
					define('USER_ADMIN', $row['admin']);
					define('LAST_VISIT', $row['last_visit']);
				}

				//all user fileds info
				$userinfo = $row;

				if(!$hashed)
				{
					$hash_key_expire = sha1(md5($config['h_key']) .  $expire);
					if(!$loginadm)
					{
						$this->kleeja_set_cookie('ulogu', $this->en_de_crypt($row['id'] . '|' . $row['password'] . '|' . $expire . '|' . $hash_key_expire), $expire);
					}
					else
					{
						//update now !!
						$update_last_visit = array(
									'UPDATE'	=> "`{$dbprefix}users`",
									'SET'		=> "last_visit=" . time() . "",
									'WHERE'		=>	"id=" . intval($row['id'])
							);

						$SQL->build($update_last_visit);
					}
				}
		
				($hook = kleeja_run_hook('qr_while_usrdata_n_usr_class')) ? eval($hook) : null; //run hook
			}
			$SQL->freeresult($result);

			unset($pass);
			return true;
		}
		else
		{
			return false;
		}
	}

	/*
		get user data
		new function:1rc5+
	*/
	function get_data($type="*", $user_id = false)
	{
		global $dbprefix, $SQL;

		if(!$user_id)
		{
			$user_id = $this->id();
		}
		
		//todo : 
		//if type != '*' and contains no , and type in 'name, id, email' return $this->id .. etc

		//te get files and update them !!
		$query_name = array(
						'SELECT'	=> $type,
						'FROM'		=> "{$dbprefix}users",
						'WHERE'		=> "id=" . intval($user_id)
					);

		($hook = kleeja_run_hook('qr_select_userdata_in_usrclass')) ? eval($hook) : null; //run hook
		$data_user = $SQL->fetch_array($SQL->build($query_name));

		return $data_user;
	}

	/*
	user ids
	*/
	function id ()
	{
		($hook = kleeja_run_hook('id_func_usr_class')) ? eval($hook) : null; //run hook
		
		return defined('USER_ID') ? USER_ID : false;
	}

	/*
	user name
	*/
	function name ()
	{
		($hook = kleeja_run_hook('name_func_usr_class')) ? eval($hook) : null; //run hook

		return defined('USER_NAME') ? USER_NAME : false;
	}

	/*
	user mail
	*/
	function mail ()
	{
		($hook = kleeja_run_hook('mail_func_usr_class')) ? eval($hook) : null; //run hook

		return defined('USER_MAIL') ? USER_MAIL : false;	
	}

	/*
	is user admin ?
	*/
	function admin ()
	{
		($hook = kleeja_run_hook('admin_func_usr_class')) ? eval($hook) : null; //run hook

		return defined('USER_ADMIN') ? USER_ADMIN : false;
	}

	/*
	logout func
	*/
	function logout()
	{
		($hook = kleeja_run_hook('logout_func_usr_class')) ? eval($hook) : null; //run hook

		//adm
		if(defined('USER_ADMIN') && USER_ADMIN == 1 && !empty($_SESSION['ADMINLOGIN']))
		{ 
			$this->logout_cp();
		}

		//is ther any cookies	
		$this->kleeja_set_cookie('ulogu', '', time() - 31536000);//31536000 = year

		return true;
	}

	/*
	logut just from acp
	*/
	function logout_cp()
	{
		($hook = kleeja_run_hook('logout_cp_func_usr_class')) ? eval($hook) : null; //run hook

		if(!empty($_SESSION['ADMINLOGIN']))
		{
			unset($_SESSION['ADMINLOGIN'], $_SESSION['USER_SESS'] /*, $_SESSION['LAST_VISIT']*/);
		}

		return true;
	}

	//clean usernames
	function cleanusername($uname) 
	{
		static $arabic_t = array();
		static $latin_t = array(
			array('á','à','â','ã','å','Á','À','Â','Ã','Å','é','è','ê','ë','É','È','Ê','í','ì','ï','î','Í','Ì','Î','Ï','ò','ó','ô','õ','º','ø','Ó','Ò','Ô','Õ','Ø','ú','ù','û','Ú','Ù','Û','ç','Ç','Ñ','ñ','ÿ','Ë'),
			array('a','a','a','a','a','a','a','a','a','a','e','e','e','e','e','e','e','i','i','i','i','i','i','i','i','o','o','o','o','o','o','o','o','o','o','o','u','u','u','u','u','u','c','c','n','n','y','e')
		);

		if(empty($arabic_t))
		{
			//Arabic chars must be stay in utf8 format, so we encoded them
			$arabic_t = unserialize(kleeja_base64_decode('YToyOntpOjA7YToxMjp7aTowO3M6Mjoi2KMiO2k6MTtzOjI6ItilIjtpOjI7czoyOiLYpCI7aTozO3M6Mjoi2YAiO2k6NDtzOjI6Itm' .
			'LIjtpOjU7czoyOiLZjCI7aTo2O3M6Mjoi2Y8iO2k6NztzOjI6ItmOIjtpOjg7czoyOiLZkCI7aTo5O3M6Mjoi2ZIiO2k6MTA7czoyOiLYoiI7aToxMTtzOjI6ItimIjt9aToxO' .
			'2E6MTI6e2k6MDtzOjI6ItinIjtpOjE7czoyOiLYpyI7aToyO3M6Mjoi2YgiO2k6MztzOjA6IiI7aTo0O3M6MDoiIjtpOjU7czowOiIiO2k6NjtzOjA6IiI7aTo3O3M6MDoiIjt' . 
			'pOjg7czowOiIiO2k6OTtzOjA6IiI7aToxMDtzOjI6ItinIjtpOjExO3M6Mjoi2YkiO319'));
		}
		$uname = str_replace($latin_t[0], $latin_t[1], $uname); //replace confusable Latin chars
    	$uname = str_replace($arabic_t[0], $arabic_t[1], $uname); //replace confusable Arabic chars
		$uname = preg_replace('#(?:[\x00-\x1F\x7F]+|(?:\xC2[\x80-\x9F])+)#', '', $uname); //un-wanted utf8 control chars
		$uname = preg_replace('# {2,}#', ' ', $uname); //2+ spaces with one space
    	return strtolower($uname);
	}

	//depand on phpass class
	function kleeja_hash_password($password, $check_pass = false)
	{
		include_once('phpass.php');

		$return = false;
		$hasher = new PasswordHash(8, true);
		$return = $hasher->HashPassword($password);
	
		//return check or hash
		return $check_pass != false ?  $hasher->CheckPassword($password, $check_pass) : $return;
	}

	//kleeja cookie
	function kleeja_set_cookie($name, $value, $expire)
	{
		global $config;

		//
		//when user add cookie_* in config this will replace the current ones
		//
		global $config_cookie_name, $config_cookie_domian, $config_cookie_secure, $config_cookie_path;
		$config['cookie_name']		= isset($config_cookie_name) ? $config_cookie_name : $config['cookie_name'];
		$config['cookie_domain']	= isset($config_cookie_domain) ? $config_cookie_domain : $config['cookie_domain'];
		$config['cookie_secure']	= isset($config_cookie_secure) ? $config_cookie_secure : $config['cookie_secure'];
		$config['cookie_path']		= isset($config_cookie_path) ? $config_cookie_path : $config['cookie_path'];

		//
		//when user add define('FORCE_COOKIES', true) in config.php we will make our settings of cookies
		//
		if(defined('FORCE_COOKIES'))
		{
			$config['cookie_domain'] = $c_domain;

			// Fix the domain to accept domains with and without 'www.'.
			if (strtolower(substr($config['cookie_domain'], 0, 4) ) == 'www.')
			{
				$config['cookie_domain'] = substr($config['cookie_domain'], 4);
			}
			// Add the dot prefix to ensure compatibility with subdomains
			if (substr($config['cookie_domain'], 0, 1) != '.' )
			{
				$config['cookie_domain'] = '.' . $config['cookie_domain'];
			}
			// Remove port information.
			$port = strpos($config['cookie_domain'], ':');
			
			if ($port !== false)
			{
				$config['cookie_domain'] = substr($config['cookie_domain'], 0, $port);
			}
			
			//other cookies settings
			$config['cookie_path'] = '/';
			$config['cookie_secure'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true : false;
		}

		// Enable sending of a P3P header
		header('P3P: CP="CUR ADM"');

		if (version_compare(PHP_VERSION, '5.2.0', '>='))
		{
			setcookie($config['cookie_name'] . '_' . $name, $value, $expire, $config['cookie_path'], $config['cookie_domain'], $config['cookie_secure'], true);
		}
		else
		{
			setcookie($config['cookie_name'] . '_' . $name, $value, $expire, $config['cookie_path'] . '; HttpOnly', $config['cookie_domain'], $config['cookie_secure']);
		}
	}

	//encrypt and decrypt any data with our function
	function en_de_crypt($data, $type = 1)
	{
		global $config;
		static $txt = array();

		if(empty($txt))
		{
			if(empty($config['h_key']))
			{
				$config['h_key'] = '2^#@qr39)]k%$_-(';//default !
			}
			$chars = str_split($config['h_key']);
			foreach(range('a', 'z') as $k=>$v)
			{
				if(!isset($chars[$k]))
				{
					break;
				}
				$txt[$v] = $chars[$k] . $k . '/'; 
			}
		}

		switch($type)
		{
			case 1:
				$data = kleeja_base64_encode($data);
				$data = strtr($data, $txt);
			break;
			case 2:
				$txtx = array_flip($txt); 
				$txtx = array_reverse($txtx, true);
				$data = strtr($data, $txtx);
				$data = kleeja_base64_decode($data);
			break;
		}

		return $data;
	}


	//
	//get cookie
	//
	function kleeja_get_cookie($name)
	{
		global $config;

		return isset($_COOKIE[$config['cookie_name'] . '_' . $name]) ? $_COOKIE[$config['cookie_name'] . '_' . $name] : false;
	}

	//check if user is admin or not 
	//return : mean return true or false, but if return is false will show msg
	function kleeja_check_user()
	{
		global $config, $SQL, $dbprefix;

		//if login up
		if($this->kleeja_get_cookie('ulogu'))
		{
			$user_data = false;

			list($user_id, $hashed_password, $expire_at, $hashed_expire) =  @explode('|', $this->en_de_crypt($this->kleeja_get_cookie('ulogu'), 2));

			//if not expire 
			if(($hashed_expire == sha1(md5($config['h_key']) . $expire_at)) && ($expire_at > time()))
			{
				//todo : 
				//i think we need to use this if and only if he is admin and in other case return true !
				//check == admin ... $this->data .. 
				//else $user_data = true...
				$user_data = $this->data($user_id, $hashed_password, true, $expire_at);
			}

			if($user_data == false)
			{
				$this->logout();
			}
			else
			{
				return $user_data;
			}
		}

		return false; //nothing
	}
	

	/*
	* convert from utf8 to cp1256 and vice versa
	*/
	function kleeja_utf8($str, $to_utf8 = true)
	{
		$utf8 = new kleeja_utf8;
		if($to_utf8)
		{
			//return iconv('CP1256', "UTF-8//IGNORE", $str);
			return $utf8->to_utf8($str);
		}
		return $utf8->from_utf8($str);
		//return iconv('UTF-8', "CP1256//IGNORE", $str);
	}

}#end class


/**
* Deep modifieded by Kleeja team ...
* depend on class by Alexander Minkovsky (a_minkovsky@hotmail.com)
*/
class kleeja_utf8
{
	var $ascMap = array();
	var $utfMap = array();
	//ignore the untranslated char, of you put true we will translate it to html tags
	//it's same the action of //IGNORE in iconv
	var $ignore = false;

	//Constructor
	function kleeja_utf8()
	{
		static $lines = array();
		if(empty($lines))
		{
			$lines = explode("\n", preg_replace(array("/#.*$/m", "/\n\n/"), '', file_get_contents(PATH . 'includes/CP1256.MAP')));
		}
		if(empty($this->ascMap))
		{
			foreach($lines as $line)
			{
				$parts = explode('0x', $line);
				if(sizeof($parts) == 3)
					$this->ascMap[hexdec(trim($parts[1]))] = hexdec(trim($parts[2]));
			}
			$this->utfMap = array_flip($this->ascMap);
		}
	}

	//Translate string ($str) to UTF-8 from given charset
	function to_utf8($str)
	{
		$chars = unpack('C*', $str);
		$cnt = sizeof($chars);
		for($i=1;$i <= $cnt; ++$i)
			$this->_charToUtf8($chars[$i]);
		return implode('', $chars);
	}

	//Translate UTF-8 string to single byte string in the given charset
	function from_utf8($utf)
	{
		$chars = unpack('C*', $utf);
		$cnt = sizeof($chars);
		$res = ''; //No simple way to do it in place... concatenate char by char
		for ($i=1;$i<=$cnt;$i++)
			$res .= $this->_utf8ToChar($chars, $i);
		return $res;
	}

	//Char to UTF-8 sequence
	function _charToUtf8(&$char)
	{
		$c = (int) $this->ascMap[$char];
		if ($c < 0x80)
			$char = chr($c);
		else if($c<0x800) // 2 bytes
			$char = (chr(0xC0 | $c>>6) . chr(0x80 | $c & 0x3F));
		else if($c<0x10000) // 3 bytes
			$char = (chr(0xE0 | $c>>12) . chr(0x80 | $c>>6 & 0x3F) . chr(0x80 | $c & 0x3F));
		else if($c<0x200000) // 4 bytes
			$char = (chr(0xF0 | $c>>18) . chr(0x80 | $c>>12 & 0x3F) . chr(0x80 | $c>>6 & 0x3F) . chr(0x80 | $c & 0x3F));
	}

	//UTF-8 sequence to single byte character
	function _utf8ToChar(&$chars, &$idx)
	{
		if(($chars[$idx] >= 240) && ($chars[$idx] <= 255))// 4 bytes
			$utf = (intval($chars[$idx]-240)   << 18) + (intval($chars[++$idx]-128) << 12) + (intval($chars[++$idx]-128) << 6) + (intval($chars[++$idx]-128) << 0);
		else if (($chars[$idx] >= 224) && ($chars[$idx] <= 239)) // 3 bytes
			$utf = (intval($chars[$idx]-224)   << 12) + (intval($chars[++$idx]-128) << 6) + (intval($chars[++$idx]-128) << 0);
		else if (($chars[$idx] >= 192) && ($chars[$idx] <= 223))// 2 bytes
			$utf = (intval($chars[$idx]-192)   << 6) + (intval($chars[++$idx]-128) << 0);
		else// 1 byte
			$utf = $chars[$idx];

		if(array_key_exists($utf, $this->utfMap))
			return chr($this->utfMap[$utf]);
		else
		  return $this->ignore ? '' : '&#' . $utf . ';';
	}
}

#<-- EOF
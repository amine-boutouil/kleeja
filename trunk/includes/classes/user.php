<?php
/**
*
* @package Kleeja
* @version $Id$
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
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
* Main class for user system
* Usefull constants to force some settings :
* FORCE_COOKIES, DISABLE_INTR
*
* @package auth
*/
class user
{
	
	/**
	 * Array holding the user data, name etc
	 * Those default values are for guest 
	 */
	public $data = array('id'=>-1, 'group_id'=>2, 'is_bot'=>false);


	/**
	 * Login into Kleeja
	 *
	 * @param mexied $name The username if not hashed, ID if hashed
	 * @param string $pass The password
	 * @param mixed $hashed does the givvn password hashed or not
	 * @param int $expire The time will the user stay logon
	 * @param bool $loginadm If this is a login for admin area, then true
	 * @return bool true or false
	 */
	public function login($name, $pass, $hashed = false, $expire = 86400, $loginadm = false)
	{
		global $config;

		#login expire after?
		$expire = time() + ((int) $expire ? intval($expire) : 86400);

		($hook = kleeja_run_hook('data_func_usr_class')) ? eval($hook) : null; //run hook

		#if the user system integrated, then get the right file
		if($config['user_system'] != 1)
		{
			if(file_exists(PATH . 'includes/auth_integration/' . trim($config['user_system']) . '.php'))
			{	
				include_once PATH . 'includes/auth_integration/' . trim($config['user_system']) . '.php';
				$login_data = kleeja_auth_login(trim($name), trim($pass), $hashed, $expire, $loginadm);
				return $login_status;
				
				#TODO add default $user->data info for those different system
				#name, mail, last_login ...
			}
		}

		#or just use the default system
		return $this->normal(trim($name), trim($pass), $hashed, $expire, $loginadm);
	}


	/**
	 * Get the username by its ID
	 * 
	 * @param int $use_id The user ID
	 * @return string Username
	 */
	public function usernamebyid($user_id) 
	{
		global $config;

		#if integrated, use its function
		if($config['user_system'] != 1)
		{
			if(file_exists(PATH . 'includes/auth_integration/' . trim($config['user_system']) . '.php'))
			{	
				include_once (PATH . 'includes/auth_integration/' . trim($config['user_system']) . '.php');
				return kleeja_auth_username($user_id);
			}
		}

		#normal system
		$u = $this->get_data('name', $user_id);	
		return $u['name'];
	}

	/**
	 * Login using Our system
	 *
	 * @see login()
	 */
	public function normal($name, $pass, $hashed = false, $expire, $loginadm = false)
	{
		global $SQL, $dbprefix, $config;

		$query = array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}users",
					'LIMIT'		=> '1'
					);

		if($hashed)
		{
			$query['WHERE'] = "id=" . intval($name) . " and password='" . $SQL->escape($pass) . "'";
		}
		else
		{
			$query['WHERE'] = "clean_name='" . $SQL->escape($this->cleanusername($name)) . "'";
		}

		($hook = kleeja_run_hook('qr_select_usrdata_n_usr_class')) ? eval($hook) : null; //run hook			
		$result = $SQL->build($query);

		if (!$SQL->num($result))
		{
			return false;
		}

		$row = $SQL->fetch($result);

		#if hacker got able to make the password empty, stop him
		if(empty($row['password']))
		{
			return false;
		}

		$phppass = $hashed ?  $pass : $pass . $row['password_salt'];

		#is it md5? and converted from other script to kleeja?
		if(strlen($row['password']) == '32' && empty($row['password_salt']) && defined('CONVERTED_SCRIPT'))   
		{
			$passmd5 = md5($pass);
			#update old md5 hash to phpass hash
			if($row['password'] == $passmd5)
			{
				#make new password hash and salt and upgrade this user to it
				$new_salt = substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
				$new_password = $this->kleeja_hash_password(trim($pass) . $new_salt);

				($hook = kleeja_run_hook('qr_update_usrdata_md5_n_usr_class')) ? eval($hook) : null; //run hook	

				$update_query = array(
							'UPDATE'	=> "{$dbprefix}users",
							'SET'		=> "password='" . $new_password . "' ,password_salt='" . $new_salt . "'",
							'WHERE'		=>	"id=" . intval($row['id'])
					);

				$SQL->build($update_query);
			}
			else
			{
				#password is wrong
				return false;
			}
		}
		
		#password doesnt match
		if(($phppass != $row['password'] && $hashed) || ($this->kleeja_hash_password($phppass, $row['password']) != true && $hashed == false))
		{
			return false;
		}

		#all user fileds info
		$this->data = $row;

		$user_y = base64_encode(serialize(array('id'=>$row['id'], 'name'=>$row['name'], 'mail'=>$row['mail'], 'last_visit'=>$row['last_visit'])));

		#set the cookies
		if(!$hashed && !$loginadm)
		{
			$hash_key_expire = sha1(md5($config['h_key'] . $row['password']).  $expire);
			$this->kleeja_set_cookie('ulogu', $this->en_de_crypt($row['id'] . '|' . $row['password'] . '|' . $expire . '|' . $hash_key_expire . '|' . $row['group_id'] . '|' . $user_y), $expire);
		}

		#if last visit > 1 minute then update it 
		if(empty($row['last_visit']) || time() - $row['last_visit'] > 60)
		{
				$update_last_visit = array(
							'UPDATE'	=> "{$dbprefix}users",
							'SET'		=> "last_visit=" . time(),
							'WHERE'		=> "id=" . intval($row['id'])
					);

				$SQL->build($update_last_visit);
		}

		($hook = kleeja_run_hook('qr_while_usrdata_n_usr_class')) ? eval($hook) : null; //run hook

		$SQL->free($result);

		unset($pass);

		#set useful data
		$this->data['password'] = '******';

		return $this->data;
	}


	/**
	 * Get any user data, to get current user data use $user->data[param]
	 *
	 * @param string $type data to get, i.e name, mail
	 * @param mixed $user_id If not given, will get current user data
	 * @return mixed Array or string if one item
	 */
	public function get_data($type = "*", $user_id = false)
	{
		global $dbprefix, $SQL;

		if(!$user_id)
		{
			$user_id = $this->data['id'];
		}

		$query_name = array(
						'SELECT'	=> $type,
						'FROM'		=> "{$dbprefix}users",
						'WHERE'		=> "id=" . intval($user_id)
					);

		($hook = kleeja_run_hook('qr_select_userdata_in_usrclass')) ? eval($hook) : null; //run hook
		$data_user = $SQL->fetch($SQL->build($query_name));

		#if not an array, return as string
		if($type != '*' && strpos($type, ',') === false)
		{
			return $data_user[$type];
		}
	
		return $data_user;
	}


	/**
	 * Logout from Kleeja
	 * 
	 * @return void
	 */
	public function logout()
	{
		($hook = kleeja_run_hook('logout_func_usr_class')) ? eval($hook) : null; //run hook

		#acp
		$this->logout_cp();

		session_unset();
		session_destroy();

		#is ther any cookies	
		$this->kleeja_set_cookie('ulogu', '', time() - 31536000);//31536000 = year
	}

	/**
	 * Logout from Kleeja admin. area only
	 * 
	 * @return void
	 */
	public function logout_cp()
	{
		($hook = kleeja_run_hook('logout_cp_func_usr_class')) ? eval($hook) : null; //run hook

		if(!empty($_SESSION['ADMINLOGIN']))
		{
			unset($_SESSION['ADMINLOGIN'], $_SESSION['USER_SESS'] /*, $_SESSION['LAST_VISIT']*/);
		}

		return true;
	}


	/**
	 * String normalization, no confusable chars
	 * 
	 * @param string $uname The string to be normalized
	 * @return string the cleaned string
	 */
	public function cleanusername($uname) 
	{
		($hook = kleeja_run_hook('cleanusername_func_usr_class')) ? eval($hook) : null; //run hook

		static $arabic_t = array();
		static $latin_t = array(
			array('á','à','â','ã','å','Á','À','Â','Ã','Å','é','è','ê','ë','É','È','Ê','í','ì','ï','î','Í','Ì','Î','Ï','ò','ó','ô','õ','º','ø','Ó','Ò','Ô','Õ','Ø','ú','ù','û','Ú','Ù','Û','ç','Ç','Ñ','ñ','ÿ','Ë'),
			array('a','a','a','a','a','a','a','a','a','a','e','e','e','e','e','e','e','i','i','i','i','i','i','i','i','o','o','o','o','o','o','o','o','o','o','o','u','u','u','u','u','u','c','c','n','n','y','e')
		);

		if(empty($arabic_t))
		{
			#Arabic chars must be stay in utf8 format, so we encoded them
			$arabic_t = unserialize(base64_decode('YToyOntpOjA7YToxMjp7aTowO3M6Mjoi2KMiO2k6MTtzOjI6ItilIjtpOjI7czoyOiLYpCI7aTozO3M6Mjoi2YAiO2k6NDtzOjI6Itm' .
			'LIjtpOjU7czoyOiLZjCI7aTo2O3M6Mjoi2Y8iO2k6NztzOjI6ItmOIjtpOjg7czoyOiLZkCI7aTo5O3M6Mjoi2ZIiO2k6MTA7czoyOiLYoiI7aToxMTtzOjI6ItimIjt9aToxO' .
			'2E6MTI6e2k6MDtzOjI6ItinIjtpOjE7czoyOiLYpyI7aToyO3M6Mjoi2YgiO2k6MztzOjA6IiI7aTo0O3M6MDoiIjtpOjU7czowOiIiO2k6NjtzOjA6IiI7aTo3O3M6MDoiIjt' . 
			'pOjg7czowOiIiO2k6OTtzOjA6IiI7aToxMDtzOjI6ItinIjtpOjExO3M6Mjoi2YkiO319'));
		}

		#replace confusable Latin chars
		$uname = str_replace($latin_t[0], $latin_t[1], $uname);
		#replace confusable Arabic chars
    	$uname = str_replace($arabic_t[0], $arabic_t[1], $uname);
		#un-wanted utf8 control chars
		$uname = preg_replace('#(?:[\x00-\x1F\x7F]+|(?:\xC2[\x80-\x9F])+)#', '', $uname);
		#2+ spaces with one space
		$uname = preg_replace('# {2,}#', ' ', $uname);
		#small letters
    	return strtolower($uname);
	}


	/**
	 * Get hashed password depend on phpass lib.
	 *
	 * @param string $password The password to be hashed
	 * @param bool $check_pass If true, only check process will be done
	 * @return mixed true or false if $chec_pass is true, or string if false
	 */
	public function kleeja_hash_password($password, $check_pass = false)
	{
		include_once PATH . 'includes/classes/phpass.php';

		($hook = kleeja_run_hook('kleeja_hash_password_func_usr_class')) ? eval($hook) : null; //run hook

		$return = false;
		$hasher = new PasswordHash(8, true);
		$return = $hasher->HashPassword($password);
	
		#return check or hash
		return $check_pass != false ?  $hasher->CheckPassword($password, $check_pass) : $return;
	}

	/**
	 * Set cookies 
	 *
	 * @param string $name A unique Cookie name
	 * @param string $value The value of cookie
	 * @param int $expire Time to stay alive
	 * @return void
	 */ 
	public function kleeja_set_cookie($name, $value, $expire)
	{
		global $config;

		($hook = kleeja_run_hook('kleeja_set_cookie_func_usr_class')) ? eval($hook) : null; //run hook

		#when user add cookie_* in config this will replace the current ones
		global $config_cookie_name, $config_cookie_domian, $config_cookie_secure, $config_cookie_path;
		$config['cookie_name']		= isset($config_cookie_name) ? $config_cookie_name : $config['cookie_name'];
		$config['cookie_domain']	= isset($config_cookie_domain) ? $config_cookie_domain : $config['cookie_domain'];
		$config['cookie_secure']	= isset($config_cookie_secure) ? $config_cookie_secure : $config['cookie_secure'];
		$config['cookie_path']		= isset($config_cookie_path) ? $config_cookie_path : $config['cookie_path'];

		#when user add define('FORCE_COOKIES', true) in config.php we will make our settings of cookies
		if(defined('FORCE_COOKIES'))
		{
			$config['cookie_domain'] = (!empty($_SERVER['HTTP_HOST'])) ? strtolower($_SERVER['HTTP_HOST']) : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : @getenv('SERVER_NAME'));
			$config['cookie_domain'] = str_replace('www.', '.', substr($config['cookie_domain'], 0, strpos($config['cookie_domain'], ':')));
			$config['cookie_path'] = '/';
			$config['cookie_secure'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true : false;
		}

		#Enable sending of a P3P header
		header('P3P: CP="CUR ADM"');

		$name_data = rawurlencode($config['cookie_name'] . '_' . $name) . '=' . rawurlencode($value);
		$rexpire = gmdate('D, d-M-Y H:i:s \\G\\M\\T', $expire);
		$domain = (!$config['cookie_domain'] || $config['cookie_domain'] == 'localhost' || $config['cookie_domain'] == '127.0.0.1') ? '' : '; domain=' . $config['cookie_domain'];

		header('Set-Cookie: ' . $name_data . (($expire) ? '; expires=' . $rexpire : '') . '; path=' . $config['cookie_path'] . $domain . ((!$config['cookie_secure']) ? '' : '; secure') . '; HttpOnly', false);
	}

	/**
	 * Get cookie from browser
	 *
	 * @param string $name The name of the cookie
	 * @return mixed The value of the cookie if exists or false if not 
	 */
	public function kleeja_get_cookie($name)
	{
		global $config;
		($hook = kleeja_run_hook('kleeja_get_cookie_func_usr_class')) ? eval($hook) : null; //run hook

		return isset($_COOKIE[$config['cookie_name'] . '_' . $name]) ? $_COOKIE[$config['cookie_name'] . '_' . $name] : false;
	}


	/**
	 * Encrpy or Decrypt a string mainly for cookies
	 * 
	 * @param string $data String to be encrypted or decrypted
	 * @param int $type Encrypt is 1, Decrypt is 2
	 * @return string Encyrpted or Decrypted string
	 */
	public function en_de_crypt($data, $type = 1)
	{
		global $config;
		static $txt = array();

		if(empty($txt))
		{
			if(empty($config['h_key']))
			{
				$config['h_key'] = sha1('2^#@qr39)]k%$_-(');//default !
			}
			$chars = str_split($config['h_key']);
			foreach(range('a', 'z') as $k=>$v)
			{
				if(!isset($chars[$k]))
				{
					break;
				}
				$txt[$v] = $chars[$k] . $k . '-'; 
			}
		}

		switch($type)
		{
			case 1:
				$data = str_replace('=', '_', base64_encode($data));
				$data = strtr($data, $txt);
			break;
			case 2:
				$txtx = array_flip($txt); 
				$txtx = array_reverse($txtx, true);
				$data = strtr($data, $txtx);
				$data = base64_decode(str_replace('_', '=', $data));
			break;
		}

		return $data;
	}


	/**
	 * A check point function used before any thing in Kleeja
	 *
	 * @return mixed
	 */
	public function kleeja_check_user()
	{
		global $config, $SQL, $dbprefix;

		($hook = kleeja_run_hook('kleeja_check_user_func_usr_class')) ? eval($hook) : null; //run hook

		#is it a bot? record bot visits stat
		$this->is_bot(true);

		#if there is no login cookie
		if(!$this->kleeja_get_cookie('ulogu'))
		{
		 	return false;	
		}
		
		$user_data = false;

		list($user_id, $hashed_password, $expire_at, $hashed_expire, $group_id, $u_info) =  @explode('|', $this->en_de_crypt($this->kleeja_get_cookie('ulogu'), 2));

		#if not expire 
		if(($hashed_expire == sha1(md5($config['h_key'] . $hashed_password) . $expire_at)) && ($expire_at > time()))
		{
			$user_data = $this->login($user_id, $hashed_password, true, $expire_at);
		}

		#no data or wrong data, clear the cookies
		if($user_data == false)
		{
			$this->logout();
			return false;
		}
		else
		{
			$this->data = $user_data;

			#is it a bot?
			$this->is_bot();

			return true;
		}
	}
	
	
	/**
	 * Check if the current visitor is a user
	 *
	 * @return bool true or false 
	 */
	public function is_user()
	{
		if($this->data['id'] >= 1)
		{
			return true;
		}

		return false;
	}
	
	
	/**
	 * Is current user a bot?
	 * 
	 * @param bool Check and return true or false or record bots visits if true
	 * @return bool True if a bot, false if not
	 */
	public function is_bot($record = false)
	{
		global $SQL, $user, $dbprefix, $config;

		#get information .. 
		$agent	= $_SERVER['HTTP_USER_AGENT'];
		$time	= time();
		$bot_name = '';

		#check
		if (strpos($agent, 'Google') !== false)
		{
			$bot_name = 'google';
		}
		elseif (strpos($agent, 'Bing') !== false)
		{
			$bot_name = 'bing';
		}

		($hook = kleeja_run_hook('is_bot_func_before_qr')) ? eval($hook) : null; //run hook	

		$this->data['is_bot'] =  $bot_name == '' ? false : true;

		#if no recoring then exit
		if(!$record || $bot_name == '')
		{	
			return $this->data['is_bot'];
		}

		#update stats
		$update_query = array(
								'UPDATE'	=> "{$dbprefix}stats",
								'SET'		=> "last_$bot_name=$time, $bot_name_num=$bot_name_num+1"
							);

		($hook = kleeja_run_hook('qr_update_is_bot')) ? eval($hook) : null; //run hook	
		$SQL->build($update_query);


		#clean online table
		if((time() - $config['last_online_time_update']) >= 3600)
		{
			#update last_online_time_update 
			update_config('last_online_time_update', time());
		}

		return $this->data['is_bot'];
	}
}


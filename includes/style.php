<?php
##################################################
#						Kleeja 
#
# Filename : style.php 
# purpose :  Template engine, based on : easy template  <http://daif.net/easy>
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author$ , $Rev$,  $Date::                           $
##################################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}

class kleeja_style
{
        var $vars; //Reference to $GLOBALS
        var $HTML; //html page content
        var $loop	= array();
        var $reg	= array('var' => '/([{]{1,2})+([A-Z0-9_\.]+)[}]{1,2}/i');
		var $caching = true;//save templates as caches to not compliled alot of times
		
        //Function to load a template file.
        function _load_template($template_name)
		{
			global $config, $root_path, $STYLE_PATH, $STYLE_PATH_ADMIN;

			$is_admin_template = false;
			$style_path = $STYLE_PATH;
			
			//admin template always begin with admin_
			if(substr($template_name, 0, 6) == 'admin_')
			{
				$style_path =  $STYLE_PATH_ADMIN;
				$is_admin_template = true;
			}
			
			$template_path = $style_path . $template_name . '.html';

			//if template not found and default style is there and not admin tpl
			$is_tpl_exist = file_exists($template_path);
			if(!$is_tpl_exist) 
			{	
				if(file_exists($style_path . 'depend_on.txt'))
				{
					$depend_on = file_get_contents($style_path . 'depend_on.txt');
					$template_path_alternative = str_replace('/' . $config['style'] . '/', '/' . trim($depend_on) . '/', $template_path);
					if(file_exists($template_path_alternative))
					{
						$template_path = $template_path_alternative;
						$is_tpl_exist = true;
					}
				}
				else if($config['style'] != 'default' && !$is_admin_template)
				{
					$template_path_alternative = str_replace('/' . $config['style'] . '/', '/default/', $template_path);
					if(file_exists($template_path_alternative))
					{
						$template_path = $template_path_alternative;
						$is_tpl_exist = true;
					}
				}
			}
			
			if(!$is_tpl_exist)
			{
				big_error('No Template !', 'Requested "' . $template_path . '" template doesnt exists or an empty !! ');
			}
			
			/*
			if(!is_writable($root_path . 'cache'))
			{
				big_error('No Template !', '"Cache" folder is not writable!! ');
			}
			*/
			
			$this->HTML = file_get_contents($template_path);
			$this->_parse($this->HTML);
			//use 'b' to force binary mode
			if($filename = @fopen($root_path . 'cache/tpl_' . $this->re_name_tpl($template_name) . '.php', 'wb'))
			{
				@flock($filename, LOCK_EX);
				@fwrite($filename, $this->HTML);
				@flock($filename, LOCK_UN);
				@fclose($filename);
				// Read and write for owner, read for everybody else
				@chmod($root_path . 'cache/tpl_' . $this->re_name_tpl($template_name) . '.php', 0644);
			}
        }
		
		
        //Function to parse the Template Tags
        function _parse()
		{
			$this->HTML = preg_replace(array('#<([\?%])=?.*?\1>#s', '#<script\s+language\s*=\s*(["\']?)php\1\s*>.*?</script\s*>#s', '#<\?php(?:\r\n?|[ \n\t]).*?\?>#s'), '', $this->HTML);
            $this->HTML = preg_replace_callback('/\(([{A-Z0-9_\.}\s!=<>]+)\?(.*):(.*)\)/iU',array('kleeja_style','_iif_callback'), $this->HTML);
            $this->HTML = preg_replace_callback('/<(IF|ELSEIF) (.+)>/iU',array('kleeja_style','_if_callback'), $this->HTML);
            $this->HTML = preg_replace_callback('/<LOOP\s+NAME\s*=\s*(\"|)+([a-z0-9_\.]{1,})+(\"|)\s*>/i',array('kleeja_style','_loop_callback'), $this->HTML);
            $this->HTML = preg_replace_callback(kleeja_style::reg('var'),array('kleeja_style','_vars_callback'), $this->HTML);

            $rep = array(
						//"/<LOOP\s+NAME\s*=\s*(\"|)+([a-z0-9_]{1,})+(\"|)\s*>/i" => "< ? php foreach(\$this->vars[\"\\2\"] as \$key=>\$value){ ? >",
						"/<LOOP\s+NAME\s*=\s*(\"|)+([a-z0-9_]{1,})+(\"|)\s*LIMIT\s*=\s*(\"\\d+\"|\\d+)\s*>/i" => "<?php \$this->_limit(\"\\2\",\\4);foreach(\$this->vars[\"\\2\"] as \$key=>\$value){ ?>",
						"/<\/(LOOP|IF|END|IS_BROWSER)>/i" => "<?php } ?>", 
						'/<SWITCH\s+NAME\s*=\s*"([A-Z0-9_]{1,})"\s*CASE\s*=\s*"(.+)"\s*VALUE\s*=\s*"(.+)"\s*>/i' => '<?php echo  $this->_switch($this->vars["\\1"],"\\2","\\3")?>',
						'/<INCLUDE\s+NAME\s*=\s*"(.+)"\s*>/iU' => '<?php echo  kleeja_style::_include("\\1"); ?>',
						'/<IS_BROWSER\s*=\s*"([a-z0-9,]+)"\s*>/iU' => '<?php if(is_browser("\\1")){ ?>',
						'/<IS_BROWSER\s*\!=\s*"([a-z0-9,]+)"\s*>/iU' => '<?php if(!is_browser("\\1")){ ?>',
						'/(<ELSE>|<ELSE \/>)/i' => '<?php }else{ ?>',
						'#<ODD="([a-zA-Z0-9\_\-\+\./]+)"\>(.*?)<\/ODD\>#is' => "<?php if(intval(\$value['\\1'])%2){?> \\2 <?php } ?>",
						'#<EVEN="([a-zA-Z0-9\_\-\+\./]+)"\>(.*?)<\/EVEN\>#is' => "<?php if(intval(\$value['\\1'])% 2 == 0){?> \\2 <?php } ?>",
						'#<RAND=\"(.*?)\"[\s]{0,},[\s]{0,}\"(.*?)\"[\s]{0,}>#is' => "<?php \$KLEEJA_tpl_rand_is=(!isset(\$KLEEJA_tpl_rand_is) || \$KLEEJA_tpl_rand_is==0)?1:0; print((\$KLEEJA_tpl_rand_is==1) ?'\\1':'\\2'); ?>",
						'/{%(key|value)%}/i' => '<?php echo $\\1; ?>',
				);
				
            $this->HTML = preg_replace(array_keys($rep), array_values($rep), $this->HTML);
        }
		
		//loop tag
		function _loop_callback($matches)
		{
			$var = (strpos($matches[2], '.') !== false) ?  str_replace('.', '"]["', $matches[2]) : $matches[2];
			return '<?php foreach($this->vars["' . $var . '"] as $key=>$value){ ?>';
		}
		
        //if tag
        function _if_callback($matches)
		{
            $char  = array(' eq ',' lt ',' gt ',' lte ',' gte ', ' neq ', '==', '!=', '>=', '<=', '<', '>');
            $reps  = array('==','<','>','<=','>=', '!=', '==', '!=', '>=', '<=', '<', '>');
			
            $atts = call_user_func(array('kleeja_style','_get_attributes'), $matches[0]);

            $con = !empty($atts['NAME']) ? $atts['NAME'] : (empty($atts['LOOP']) ? null : $atts['LOOP']);
			
            if(preg_match('/(.*)(' . implode('|', $char) . ')(.*)/i', trim($con), $arr))
			{
				$arr[1] = trim($arr[1]);
				if($arr[1][0] != '$')
				{
					$var1 = call_user_func(array('kleeja_style', '_var_callback'), (!empty($atts['NAME']) ? '{' . $arr[1] . '}' : '{{' . $arr[1] . '}}'));
				}
				else
				{
					$var1 = $arr[1];
				}

                $opr = str_replace($char, $reps, $arr[2]);
                $var2 = trim($arr[3]);
				
				//check for type 
				if($var2[0] != '$' && !preg_match('/[0-9]/', $var2))
				{
					$var2 = '"' . $var2 . '"';
				}
				
                $con = "$var1$opr$var2";
            }
			elseif($con[0] !== '$')
			{
                $con = !empty($atts['NAME']) ? '{' . $con . '}' : '{{' . $con . '}}';
                $con = call_user_func(array('kleeja_style', '_var_callback'), $con);
            }
			
            if(strtoupper($matches[1])=='IF')
			{
                return '<?php if(' . $con . '){ ?>';
            }
			else
			{
                return '<?php }elseif(' . $con . '){ ?>';
            }
        }
		
        //iif tag
        function _iif_callback($matches)
		{
            return '<IF NAME="' . $matches[1] . '">' . $matches[2] . '<ELSE>' . $matches[3] . '</IF>';
        }
		
		
        //make variable printable
        function _vars_callback($matches)
		{
            $var = call_user_func(array('kleeja_style', '_var_callback'), $matches);
            return('<?php echo  ' . $var . '?>');
        }
		
        //variable replace
        function _var_callback($matches)
		{
            if(!is_array($matches))
			{
                preg_match(kleeja_style::reg('var'), $matches, $matches);
            }

			$var = '';
			if(!empty($matches[2]))
			{
				$var = str_replace('.', '\'][\'', $matches[2]);
			}
			
            if(!empty($matches[1]) && trim($matches[1]) == '{{')
			{
                $var = '$value[\'' . $var . '\']';
            }
			else
			{
                $var = '$this->vars[\'' . $var . '\']';
            }
			
            return($var);
        }
		
        //att variable replace
        function _var_callback_att($matches)
		{
            if(trim($matches[1]) == '{')
			{
                return($this->_var_callback($matches));
            }
			else
			{
                return('{' . $this->_var_callback($matches) . '}');
            }
        }
		
		
        //get reg var
        function reg($var)
		{
            $vars = get_class_vars(__CLASS__);
            return($vars['reg'][$var]);
        }
		

        //get tag  attributes
        function _get_attributes($tag)
		{
            preg_match_all('/([a-z]+)="(.+)"/iU',$tag, $attribute);
			
			$attributes = array();
			
            for($i=0;$i<count($attribute[1]);$i++)
			{
                $att = strtoupper($attribute[1][$i]);
				
                if(preg_match('/NAME|LOOP/',$att))
				{
                    $attributes[$att] = preg_replace_callback(kleeja_style::reg('var'), array('kleeja_style', '_var_callback'), $attribute[2][$i]);
                }
				else
				{
                    $attributes[$att] = preg_replace_callback(kleeja_style::reg('var'), array('kleeja_style', '_var_callback_att'), $attribute[2][$i]);
                }
            }
            return $attributes;
        }

		
        //switch Tag
        function _switch($var,$case,$value)
		{
            $case  = explode(',',$case);
            $value = explode(',',$value);
			
            foreach($case as $k=>$val)
			{
				if($var==$val)
				{
					return $value[$k];
				}
			}
		}
		
		
        //include Tag
        function _include($fn)
		{
            list(,, $ex,) = array_values(pathinfo($fn));
			
            if(strtoupper($ex) =='PHP')
			{
				//
				//disabled for security !
				//
                #include($fn);
            }
			else
			{
                return($this->display($fn));
            }
        }
		
        //Assign Veriables
        function assign($var, $to)
		{
            $GLOBALS[$var] = $to;
        }
		
		
        //Function to make limited Array
        function _limit($arr_name, $limit=10)
		{
            $count	= count($this->vars[$arr_name]);
            $offset	= $_REQUEST[$arr_name . '_PS'];
            $offset	= ($offset*$limit<$count)? $offset*$limit : 0;
            $output	= array_slice($this->vars[$arr_name], $offset, $limit);
            $query	= preg_replace("/(\&|){$arr_name}+_PS=\\d*/i", '', $_SERVER['QUERY_STRING']);
            $prefix	= ($query) ? "?{$query}&" : '?';
			
            for($i=0;$i<ceil($count/$limit);$i++)
			{
				$this->vars[$arr_name . '_paging'] .= ($offset == $i*$limit)?' <strong>' . ($i+1) . '</strong> ' : ' <a href="' . $prefix . $arr_name . '_PS=' . $i . '" class="paging">' . ($i+1) . '</a> ';
            }
			
			$this->vars[$arr_name.'_pages'] = @ceil($count/$limit);
            $this->vars[$arr_name] = $output;
        }
		

        //load parser and return page content
        function display($template_name)
		{
			global $config, $SQL, $root_path;
			
			$this->vars  = &$GLOBALS;
			
			//is there ?
			if(!file_exists($root_path.'cache/tpl_' . $this->re_name_tpl($template_name) . '.php') or !$this->caching)
			{
				$this->_load_template($template_name);
			}

			ob_start();
			include($root_path . 'cache/tpl_' . $this->re_name_tpl($template_name) . '.php');
			$page = ob_get_contents();
			ob_end_clean();
		
			return $page;
		}
		
		function admindisplayoption($html)
		{
			global $config, $SQL, $root_path;
			
			$this->vars  = &$GLOBALS;
			$this->HTML = $html;
			$this->_parse($this->HTML);

 			ob_start();
			eval('?' . '>' . trim($this->HTML) . '<' . '?');
			$page = ob_get_contents();
			ob_end_clean();
		
			return $page;
		}
		
		//change name of template to be valid 1rc6+
		function re_name_tpl($name)
		{
			return preg_replace("/[^a-z0-9-_]/", "-", strtolower($name));
		}
		
}

//<-- EOF
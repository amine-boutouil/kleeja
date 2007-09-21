<?php
##################################################
#						Kleeja 
#
# Filename : easytemplate.php version  1.3
# purpose :  Template engine ..:
# copyright 2007 Kleeja.com ..
#class by : Daifallh Al-Otaibi <daif55@gmail.com><http://daif.net/easy>
#Developer: AzzozHSN <www.azzozhsn.net>
##################################################

	  if (!defined('IN_COMMON'))
	  {
	  echo '<strong><br /><span style="color:red">[NOTE]: This Is Dangrous Place !! [2007 saanina@gmail.com]</span></strong>';
	  exit();
	  }
  
	class EasyTemplate{
		var $vars; //Reference to $GLOBALS 
		var $HTML; //html page content
		var $Temp="template";// your template path OR "./"
		var $Cache="cache";// must be writeable check permission OR use $_ENV["TEMP"];
		var $color = array();

		//patterns Array
		var $pats = array(
		//Foreach Variables
		"/{{([A-Z0-9_\]\[\"]{1,})}}/i",
		//Globals Variables
		"/{([A-Z0-9_\]\[\"]{1,})}/i",
		//Foreach Statement
		"/<LOOP\s+NAME\s*=\s*(\"|)+([a-z0-9_]{1,})+(\"|)\s*>/i",
		//Foreach Statement With Limited Value
		"/<LOOP\s+NAME\s*=\s*(\"|)+([a-z0-9_]{1,})+(\"|)\s*LIMIT\s*=\s*(\"\\d+\"|\\d+)\s*>/i",
		"/<\/LOOP>/i",
		"/<ELSE>/i",
		"/<\/IF>/i",
		//Switch Statement
		"/<SWITCH\s+NAME\s*=\s*\"([A-Z0-9_]{1,})\"\s*CASE\s*=\s*\"(.+)\"\s*VALUE\s*=\s*\"(.+)\"\s*>/i",
		//Include Statement
		"/<INCLUDE\s+NAME\s*=\s*\"(.+)\"\s*>/iU"
		);
		//Replacements Array
		var $reps = array(
		"<?= \$var[\"\\1\"]?>",
		"<?= \$this->vars[\"\\1\"]?>",
		"<? foreach(\$this->vars[\"\\2\"] as \$key=>\$var){ ?>",
		"<? \$this->_limit(\"\\2\",\\4);foreach(\$this->vars[\"\\2\"] as \$key=>\$var){ ?>",
		"<? } ?>",
		"<?} else {?>",
		"<? } ?>",
		"<?= \$this->_switch(\$this->vars[\"\\1\"],\"\\2\",\"\\3\")?>",
		"<?= EasyTemplate::_include(\"\\1\",array(\"\$this->Temp\",\"\$this->Cache\")); ?>"
		);
		function EasyTemplate(){
			$php = (phpversion()>="4.3.0")?1:0;
			if(!$php) exit("<b>[ERROR]:</b> Update Your PHP version To 4.3.0 Or later, Your's ".phpversion());
		}
	//Function to load a template file.
		function _load_file($FileName){
			if(!file_exists($this->Temp)) exit("<b>[ERROR]:</b> Template Folder $this->Temp Not Exists");
			if(!file_exists($FileName)) exit("<b>[ERROR]:</b> Template File $FileName Not Exists");
			$this->HTML = file_get_contents($FileName);//it is the preferred way to read the contents of a file into a string.
		}
	//Function to make limited Array, I wrote this function On Ramadan 3eed :)
		function _limit($arr_name,$limit=10){
			$arr  = $this->vars[$arr_name];
			$page = $this->vars[_GET][$arr_name.'_PS'];
			$pagestart = ($page*$limit > count($arr))?0:$page*$limit;
			$pageend   = ($page*$limit+$limit > count($arr))?count($arr):$page*$limit+$limit;//need some Optimization
			for($i=$pagestart;$i<$pageend;$i++) $page_array[] = $arr[$i];
			$this->vars[$arr_name] = $page_array;
			$query = preg_replace("/(\&|)$arr_name+_PS=\\d+/i","",$_SERVER[QUERY_STRING]);
			$prefix = ($query)?"?$query&":"?";
			for($i=0;$i<count($arr)/$limit;$i++)
			$this->vars[$arr_name.'_paging'] .= ($page==$i)?"<b>$i</b> ":"<a href=".$prefix.$arr_name."_PS=$i class=paging>$i</a> ";
		}
	
	//Function to if.
		function _if_callback($matches){
			$condition = str_replace('.','"]["',$matches[2]).'"]';
			if($matches[4]){
				$condition = "$matches[2]\"]==\"$matches[4]\"";
			}
			if(strtoupper($matches[1])=="LOOP"){
				return "<? if(\$var[\"$condition){ ?>";
			}else{
				return "<? if(\$this->vars[\"$condition){ ?>";
			}
		}
	//Function to Switch Color.
		function _sw($index){
			return $this->color["$index"] = ($this->color["$index"]) ? false:true;
		}
	//Function to Replace Array Variables
		function _replace_callback($matches){
			return str_replace('.','"]["',$matches[0]);
		}
	//Function to Replace Array Variables
		function _color_callback($matches){
			$rand = rand();
			return "=<?= (\$this->_sw($rand)) ? \"$matches[1]\":\"$matches[2]\"?>";
		}
	//switch Tag
		function _switch($var,$case,$value){
			$case  = explode(',',$case);
			$value = explode(',',$value);
			foreach($case as $k=>$val)
			if($var==$val) return $value[$k];
		}
	//include Tag
		function _include($fn,$config){
			$this->Temp  = $config[0];
			$this->Cache = $config[1];
			return($this->display($fn));
		}		
	//Function to Assign Veriables
		function assign($var,&$to){
			$GLOBALS[$var] = $to;
		}
	//Function to Clean Old Cache File
		function _clean($fn){
			$fn = (is_array(glob($fn)))?glob($fn):array();
			foreach ($fn as $file)	unlink($file);
		}
	//Function to parse the Template Tags
		function _parse(){
			$this->HTML = preg_replace_callback("/<IF\s+(NAME|LOOP)\s*=\s*\"([A-Z0-9_\.\-]{1,})+(=(.*)|)\"\s*>/i",array('EasyTemplate','_if_callback'),$this->HTML);
			$this->HTML = preg_replace_callback("/({[A-Z0-9_\.\-]{1,}})/i",array('EasyTemplate','_replace_callback'),$this->HTML);
			$this->HTML = preg_replace_callback("/=\"([#0-9A-Z_\.\-\/]{1,})\|([#0-9A-Z_\.\-\/]{1,})\"/iU",array('EasyTemplate','_color_callback'),$this->HTML);
			$this->HTML = preg_replace($this->pats,$this->reps,$this->HTML);
		}
	//Function to OUTPUT
		function display($FileName) {
			$this->vars  = &$GLOBALS;
			$this->Cache = (!is_writeable($this->Cache))?$_ENV["TEMP"]:$this->Cache;
			$FileFullName     = $this->Temp."/".$FileName;
			$tmd = @filemtime($FileFullName);
			$FileName = str_replace('/', '-', $FileName);
			$CacheFileName = $this->Cache."/".$tmd.$FileName.".php";
			if(!file_exists($CacheFileName)){
				$this->_clean("./$this->Cache/*$FileName.php");
				$this->_load_file($FileFullName);
				$this->_parse();
				$fp = fopen($CacheFileName,"w");
				fwrite($fp,$this->HTML);
				fclose($fp);
			}
			ob_start();
			include($CacheFileName);
			$this->page = ob_get_contents();
			ob_end_clean();
			return $this->page;
		}
	}
?>
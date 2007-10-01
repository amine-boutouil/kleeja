<?php
##################################################
#						Kleeja 
#
# Filename : ocheck_class.php 1.0
# purpose :  new idea for captcha [ images captcha].:
# copyright 2007 Kleeja.com ..
#class by : Saanina [@gmail.com]
##################################################

class ocheck {
	var $PathImg = 'imgs';
	var $method;
	var $post;
	
	#to show the images 
	function show ()
	{
	
	$content =  "
			<fieldset name=\"check\" style=\"width: 125px; height: 85px\">
			<legend>...</legend>
			<input name=\"panel1\" type=\"radio\" value=\"1a\" /><img src=\"".$this->PathImg."/1a.gif\" />
			<input name=\"panel1\" type=\"radio\" value=\"1b\" /><img src=\"".$this->PathImg."/1b.gif\" />
			<input name=\"panel1\" type=\"radio\" value=\"1c\" /><img src=\"".$this->PathImg."/1c.gif\" /><br />
			<input name=\"panel2\" type=\"radio\" value=\"2a\" /><img src=\"".$this->PathImg."/2a.gif\" />
			<input name=\"panel2\" type=\"radio\" value=\"2b\" /><img src=\"".$this->PathImg."/2b.gif\" />
			<input name=\"panel2\" type=\"radio\" value=\"2c\" /><img src=\"".$this->PathImg."/2c.gif\" /><br />
			<input name=\"panel3\" type=\"radio\" value=\"3a\" /><img src=\"".$this->PathImg."/3a.gif\" />
			<input name=\"panel3\" type=\"radio\" value=\"3b\" /><img src=\"".$this->PathImg."/3b.gif\" />
			<input name=\"panel3\" type=\"radio\" value=\"3c\" /><img src=\"".$this->PathImg."/3c.gif\" />
			</fieldset>
		";
	return $content;
	}
	#for random .. 
	function rand () 
	{
		$p1 = array(1=>'1a',2=>'1b',3=>'1c');$ra = rand(1, 3); 
		$p2 = array(1=>'2a',2=>'2b',3=>'2c');$ra2 = rand(1, 3); 
		$p3 = array(1=>'3a',2=>'3b',3=>'3c');$ra3 = rand(1, 3); 
		$_SESSION['ocheck'] = md5($p1[$ra].'|'.$p2[$ra2].'|'.$p3[$ra3]); 
		return "<img src=\"".$this->PathImg."/".$p1[$ra].".gif\" /><img src=\"".$this->PathImg."/".$p2[$ra2].".gif\" />
			<img src=\"".$this->PathImg."/".$p3[$ra3].".gif\" />";
	}
	
	#foe save 
	function save ()
	{	
		//dont ask me why !!
		if ($this->method == 'get')
		{$this->post = md5($_GET['panel1'].'|'.$_GET['panel2'].'|'.$_GET['panel3']);}
		else{$this->post = md5($_POST['panel1'].'|'.$_POST['panel2'].'|'.$_POST['panel3']);}
		
		return $this->post;
	}
	
	#procssing data .. 
	function result ($value) 
	{
	$this->save();
	unset($_SESSION['ocheck']);
	if ($this->post == $value ){return true;}else{return false;}
	}

}#end ocheck
?>
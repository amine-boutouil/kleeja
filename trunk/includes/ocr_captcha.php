<?php
##################################################
#						Kleeja 
#
# Filename : common.php 
# purpose :  captcha
# copyright 2007-2008 Kleeja.com ..
#license http://opensource.org/licenses/gpl-license.php GNU Public License
# based on class of  :Julien PACHET ,email : julien@pachet.com  ,and he disterburted for free
# last edit by : saanina
##################################################

//no for directly open
if (!defined('IN_COMMON'))
{
	exit('no directly opening : ' . __file__);
}

class ocr_captcha
{
    var $key;       // ultra private static text
    var $long;      // size of text
    var $lx;        // width of picture
    var $ly;        // height of picture
    var $nb_noise;  // nb of background noisy characters
    var $filename;  // file of captcha picture stored on disk
    var $imagetype="png"; // can also be "png";
    var $public_key;    // public key
    var $font_file="./includes/arial.ttf";
   //var $font_file="./includes/DUNGEON.TTF"; for more complix
    var $font_file2="./includes/DUNGEON.GDF";
	
	
    function ocr_captcha($long=6,$lx=120,$ly=30,$nb_noise=20)
	{
      $this->key		=	md5("HI, WITH NEW LIFE1#! , SAANINA IS ME#! , NO PROBLEM!#! Kleeja is updated...");
      $this->long		=	$long;
	   $this->lx		=	$lx;
      $this->ly			=	$ly;
      $this->nb_noise	=	$nb_noise;
      $this->public_key	=	'captcha_' .  substr(md5(uniqid(rand(),true)),0,$this->long); // generate public key with entropy
    }
    
    function get_filename($public="")
	{ 
		if ($public=="")
		{
        $public=$this->public_key;
		}
		
		if (strpos($_SERVER['SystemRoot'], ":\\")===false) // so linux system
		{
			$rad="cache/"; // Document_root works nicely here  
		}
		else // windows system 
        {
			$rad="cache/"; 
		}
		
		return $rad . $public . "." . $this->imagetype;
    }
    
    // generate the private text coming from the public text, using $this->key 
	//(not to be public!!), all you have to do is here to change the algorithm
    function generate_private($public="")
	{
		if ($public=="")
		{
			$public=$this->public_key;
		}
		
		$d =  substr(md5($this->key.$public),16-$this->long/2,$this->long);
    	return str_replace(array('o','O','0'), '', $d); //remove zero and o
    }
    
    // check if the public text is link to the private text
    function check_captcha($public,$private)
	{
			//delete all captcha files 
			if (is_dir('cache')) 
			{
			    if ($dh = opendir('cache')) 
				{
			        while (($file = readdir($dh)) !== false) 
					{
			            if(strpos($file, 'captcha_') !== false)
						{
							unlink('cache/'.$file);
						}
			        }
			        closedir($dh);
			    }
			}
		
		return (strtolower($private)==strtolower($this->generate_private($public)));
    }
    
    // display a captcha picture with private text and return the public text
    function make_captcha($noise=true)
	{
		$private_key	= $this->generate_private();
		$image		= imagecreatetruecolor($this->lx,$this->ly);

      $back=ImageColorAllocate($image,intval(rand(224,255)),intval(rand(224,255)),intval(rand(224,255)));
      ImageFilledRectangle($image,0,0,$this->lx,$this->ly,$back);
      if ($noise)
	  { // rand characters in background with random position, angle, color
	        for ($i=0;$i<$this->nb_noise;$i++)
			{
				$size=intval(rand(6,14));
				$angle=intval(rand(3,360));
				$x=intval(rand(10,$this->lx-10));
				$y=intval(rand(0,$this->ly-5));
				$color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
				$text=chr(intval(rand(45,250)));
		         
				//fix in 1rc4  
				$this->klg_ImageTTFText($image, $size, $angle, $x, $y, $color, $text);
	        }
      }
      else
	  { // random grid color
	        for ($i=0;$i<$this->lx;$i+=10) 
			{
		          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
		          imageline($image,$i,0,$i,$this->ly,$color);
	        }
	        for ($i=0;$i<$this->ly;$i+=10)
			{
		          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
		          imageline($image,0,$i,$this->lx,$i,$color);
	        }
      }
	  
      // private text to read
      for ($i=0,$x=5; $i<$this->long;$i++)
	  {
	        $r=intval(rand(10,128));
	        $g=intval(rand(10,128));
	        $b=intval(rand(10,128));
	        $color = ImageColorAllocate($image, $r,$g,$b);
	        $shadow= ImageColorAllocate($image, $r+128, $g+128, $b+128);

	        $size=intval(rand(12,17));
	        $angle=intval(rand(-30,30));
	        $text=strtoupper(substr($private_key,$i,1));
	        $this->klg_ImageTTFText($image,$size,$angle,$x+2,26,$shadow,$text);
	        $this->klg_ImageTTFText($image,$size,$angle,$x,24,$color,$text);
	        $x+=$size+2;
      }
	  
		if ($this->imagetype=="jpg")
		{
			imagejpeg($image, $this->get_filename(), 100);
		}
		else
		{
			imagepng($image, $this->get_filename());
			ImageDestroy($image);
		}
	}
    
    function display_captcha($noise=true)
	{
		global $lang;
		
	      $this->make_captcha($noise);
	      $res="<input type=\"hidden\" name=\"public_key\" value=\"".$this->public_key."\">\n";
	      $alt= $lang['ENTER_CODE_IMG'];
	      $res.="<img src='".$this->get_filename()."' alt='$alt' border='0'>\n";
	      return $res;
    }
	
	function klg_ImageTTFText($image,$size,$angle,$x,$y,$color,$text)
	{
		if(function_exists('imagettftext'))
		{
			@imagettftext ($image, $size, $angle, $x, $y, $color,$this->font_file, $text);
		}
		else
		{
			$font = imageloadfont($this->font_file2);
			imagestring ($image, $font, $x, $y-17, $text, $color);
		}
	}
}
  
?>

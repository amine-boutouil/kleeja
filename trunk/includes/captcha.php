<?php
##################################################
#						Kleeja 
#
# Filename : captcha.php 
# purpose :  captcha
# copyright 2007-2009 Kleeja.com ..
# license http://opensource.org/licenses/gpl-license.php GNU Public License
# $Author: saanina $ , $Rev: 550 $,  $Date:: 2009-07-17 02:48:55 +0300#$
##################################################

session_start();

/*
 * When any body request this file , he will see an image .. 
 */
kleeja_cpatcha_image();
exit();

//
//this function will just make an image
//source : http://webcheatsheet.com/php/create_captcha_protection.php
//
function kleeja_cpatcha_image()
{
    //Let's generate a totally random string using md5
    $md5_hash = md5(rand(0,999)); 
    //We don't need a 32 character long string so we trim it down to 5 
    $security_code = substr($md5_hash, 15, 5); 

    //Set the session to store the security code
    $_SESSION["klj_sec_code"] = $security_code;

    //Set the image width and height
    $width = 150;
    $height = 25; 

    //Create the image resource 
    $image = ImageCreate($width, $height);  

    //We are making three colors, white, black and gray
    $white = ImageColorAllocate($image, 255, 255, 255);
    $black = ImageColorAllocate($image, rand(0, 100), 0, rand(0, 50));
    $grey = ImageColorAllocate($image, 204, 204, 204);

    //Make the background black 
    ImageFill($image, 0, 0, $black); 
	
	//options
	$x = 10;
	$y = 14;
	$angle = rand(-7, -10);

    //Add randomly generated string in white to the image
	if(function_exists('imagettftext'))
	{
		imagettftext ($image, 16,$angle , rand(50, $x), $y+rand(1,3), $white,'arial.ttf', $security_code);
	}
	else
	{
		imagestring ($image, imageloadfont('arial.gdf'), $x+rand(10,15), $y-rand(10,15), $security_code, $white);
	}
	
	//kleeja !
	imagestring ($image, 1, $width-35, $height-10, 'kleeja', ImageColorAllocate($image, 200, 200, 200));
	
    //Throw in some lines to make it a little bit harder for any bots to break 
    ImageRectangle($image,0,0,$width-1,$height-1,$grey); 
    imageline($image, 0, $height/2, $width, $height/2, $grey); 
    imageline($image, $width/2, 0, $width/2, $height, $grey); 
 


			
    //Tell the browser what kind of file is come in 
    header("Content-Type: image/png"); 

    //Output the newly created image in jpeg format 
    ImagePng($image);
   
    //Free up resources
    ImageDestroy($image);
}

?>
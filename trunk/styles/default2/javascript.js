/**
*
* @package Kleeja_style
* @version $Id: uploading.php 2230 2013-11-19 16:30:50Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/

$(document).ready(function(){		
	//display new input until it exceed maximum input
	$('.file').change(function(){
		var i = this.name.replace(/file|_/g,'');
		if( i >= number_of_uploads )
		{
			alert(LANG_MORE_F_FILES);
			return;
		};

		$('.file:eq('+ i +')').css('display', 'block');
	});
		
	//display loader while upload files
	$('#uploader').submit(function(){
		$('#loadbox').css('display', 'block');
		$('#uploader').css('display', 'none');
	});
});
	
//javascript for captcha
function update_kleeja_captcha(captcha_file, input_id)
{
	document.getElementById(input_id).value = '';
	//Get a reference to CAPTCHA image
	img = document.getElementById('kleeja_img_captcha'); 
	 //Change the image
	img.src = captcha_file + '?' + Math.random();
}



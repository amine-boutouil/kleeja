/** ------------------------------------------------
	^_^ JavaScript
	Filename: 	styles/default/javascript.js
	package:	Kleeja  copyright(c)2007-2013
	URL:		http://www.kleeja.com
	-------------------------------------------------
	$Id$
	-------------------------------------------------
**/

	//to enable mutli onload
	function addLoadEvent(func)
	{
		var oldonload = window.onload;
		if (typeof window.onload != 'function'){
			window.onload = func;
		}else{
			window.onload = function(){
				if (oldonload){
					oldonload();
				}
				func();
			}
		}
	}

	//new field
	function plus (id, type)
	{
		id = id+1;
		var input_id = (type==1) ?  'file' : 'fileu';
		var br_id = (type==1) ?  'br' : 'bru';
		
		if (id < number_of_uploads+1)
		{
			eval('var s = "' + input_id +'_' + id + '_";var br = "' + br_id + '_' + id + '_";');
			$(s).style.display = '';
			$(br).style.display = '';			
		}
		else
		{
			alert(LANG_MORE_F_FILES);
		}
	}

	//submit
	function form_submit() 
	{
		setTimeout
		( 
			function() 
			{ 
				var load = $('loadbox');
				load.style.display='inline'; 
				load.src='ajax-loader.gif'
			},
			500
		)
		var uploader = $("uploader");
		uploader.style.display = "none";
		document.uploader.submit();
	}

	//show or hide some fields
	function showhide()
	{
		var txt = $("texttype");
		var fle = $("filetype");

		if (txt.style.display == 'none')
		{
			txt.style.display = 'block';
			fle.style.display = 'none';
		}
		else
		{
			fle.style.display = 'block';
			txt.style.display = 'none';
		}
	}
	
	//
	//javascript for captcha
	//
	function update_kleeja_captcha(captcha_file, input_id)
	{
		document.getElementById(input_id).value = '';
		//Get a reference to CAPTCHA image
		img = document.getElementById('kleeja_img_captcha'); 
		 //Change the image
		img.src = captcha_file + '&' + Math.random();
	}

	/** IE > Suckerfish :focus **/	
	sfFocus = function() {var sfEls = document.getElementsByTagName("INPUT");for (var i=0; i<sfEls.length; i++) {sfEls[i].onfocus=function() {this.className+=" sffocus";};sfEls[i].onblur=function() {this.className=this.className.replace(new RegExp(" sffocus\\b"), "");};};};if (window.attachEvent) window.attachEvent("onload", sfFocus);
	
	// Add a getElementsByClassName function if the browser doesn't have one
	// Limitation: only works with one class name
	// Copyright: Eike Send http://eike.se/nd
	// License: MIT License
	// modified by Kleeja
	function tabs(c1,c2,c3,c4){var m2,m3,l,t;var onclickf=function(){if(l<1)return 0;for(t=0;t<l;t++)if(m2[t]!=this){m2[t].className="";m3[t].style.display="none"}else{m2[t].className=c4;m3[t].style.display="block"}};var cl=function(){if(l<1)return 0;m2[0].className=c4;m3[0].style.display="block";for(t=0;t<l;t++)m2[t].onclick=onclickf};m2=$(c1+" "+c2+" li");if(m2[0]!="undefined"){m3=$(c1+" "+c3);l=m2.length;cl()}}$(document).ready(function(){var mah;mah=new tabs(".tabmain",".tabnav",".tabcon","tabactive")});
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
	function tabs(c,j,k,h){var a,d,b,l=function(){if(1>d)return 0;for(b=0;b<d;b++)a[b]!=this?(a[b].className="",m3[b].style.display="none"):(a[b].className=h,m3[b].style.display="block")},g=function(b,a){var f,d,e,c=[];if(document.getElementsByClassName)return a.getElementsByClassName(b);if(a.querySelectorAll)return a.querySelectorAll("."+b);if(a.evaluate)for(f=a.evaluate(".//*[contains(concat(' ', @class, ' '), ' "+b+" ')]",a,null,0,null);e=f.iterateNext();)c.push(e);else{f=a.getElementsByTagName("*");
	d=RegExp("(^|\\s)"+b+"(\\s|$)");for(e=0;e<f.length;e++)d.test(f[e].className)&&c.push(f[e])}return c};c=g(c,document)[0];a=g(j,c)[0];a=a.getElementsByTagName("li");m3=g(k,c);d=a.length;if(!(1>d)){a[0].className=h;m3[0].style.display="block";for(b=0;b<d;b++)a[b].onclick=l}};

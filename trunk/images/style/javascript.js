/*
 javascript for kleeja 
 www.kleeja.com
*/

	var totalupload_num=number_of_uploads-1;
	
	//get element by id name
	function $(id)
	{
		if(document.all){
         	return eval("document.all['" + id + "']");
    	}
		else{
			return document.getElementById(id);
		}
	}
	
	//filds of upload 
	function makeupload(type)
	{
		var value = (type==1) ?  $("upload_num").value : $("upload_f_num").value;
		var upload_forum3 = (type==1) ? $("upload_forum") :  $("upload_f_forum");
		var upload_show_num = "";
		uploaded=1;
		upload_num=(value-1);
		if(upload_num>totalupload_num){	upload_num=totalupload_num;	}
		
		for(i=0;i<upload_num;i++)
		{
			thisuid = uploaded+i;
			upload_show_num += (type==1) ?  '<input type="file" id="file[]" name="file[]" /><br />' : '<input type="text" size="50" id="file[]" name="file['+thisuid+']" value="' + LANG_PAST_URL_HERE + '" style="color:silver;" dir="ltr" /><br />';
		}
		upload_forum3.innerHTML  = upload_show_num;
	
	}
	
	//new field
	function plus (type)
	{
		var value = (type==1) ?  $("upload_num") : $("upload_f_num");
		var num = number_of_uploads;
		if (value.value < num )
		{
			value.value++;
		}
		else
		{
			alert(LANG_MORE_F_FILES);
		}
	}
	
	//remove field
	function minus (type)
	{
		var value = (type==1) ?  $("upload_num") : $("upload_f_num");
		var num = number_of_uploads;
		if (value.value != 1 )value.value--;
	}
	
	//submit
	function form_submit() 
	{
		var load = $('loadbox');
		var uploader = $("uploader");
		load.style.display = "inline";
		uploader.style.display = "none";
		document.uploader.submit();
	}
	
	//acceept terms of uploads
	function accept_terms (sub,ch)
	{
		var submit = $(sub);
		var checker = $(ch);
		submit.disabled = ( checker.checked ) ?  "" : "disabled";
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

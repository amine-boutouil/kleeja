/**********************************************************************/
/***************** Kleeja Uploader  - acp javascript ******************/  
/**********************************************************************/
	
	function change_color(obj, id, c, c2)
	{
		c = (c == null) ? 'ored' : c;
		c2 = (c == null) ? 'osilver' : c2;
		var ii = document.getElementById(id);
		if(obj.checked)
		{
			ii.setAttribute("class", c); ii.setAttribute("className", c);
		}
		else
		{
			ii.setAttribute("class", c2); ii.setAttribute("className", c2);	
		}
	}

	function checkAll(form, id, _do_c_, c, c2)
	{
		for (var i=0;i<form.elements.length;i++)
		{
			if(form.elements[i].getAttribute("rel") != id)
				continue;
			
			if(form.elements[i].checked)
			{
				uncheckAll(form, id, _do_c_, c, c2);
				break;
			}

			form.elements[i].checked = true ;
			change_color(form.elements[i], _do_c_+'['+form.elements[i].value+']', c, c2);
		}
	}

	function uncheckAll(form, id, _do_c_, c, c2)
	{
		for (var i=0;i<form.elements.length;i++)
		{
			if(form.elements[i].getAttribute("rel") != id)
				continue;
			form.elements[i].checked = false ;
			change_color(form.elements[i], _do_c_+'['+form.elements[i].value+']', c, c2);
		}
	}
	
	/* for exts */
	function change_color_exts(id)
	{
		eval('var ii = document.getElementById("su[' + id + ']");');
		eval('var g_obj = document.getElementById("gal_' + id + '");');
		eval('var u_obj = document.getElementById("ual_' + id + '");');
		if(g_obj.checked && u_obj.checked)
		{
			ii.setAttribute("class", 'o_all'); ii.setAttribute("className", 'o_all');
		}
		else if(g_obj.checked)
		{
			ii.setAttribute("class", 'o_g'); ii.setAttribute("className", 'o_g');
		}
		else if(u_obj.checked)
		{
			ii.setAttribute("class", 'o_u'); ii.setAttribute("className", 'o_u');
		}
		else
		{
			ii.setAttribute("class", ''); ii.setAttribute("className", '');	
		}
	}

	function checkAll_exts(form, id, _do_c_)
	{
		for (var i=0;i<form.elements.length;i++)
		{
			if(form.elements[i].getAttribute("rel") != id)
				continue;
			if(form.elements[i].checked)
			{
				uncheckAll_exts(form, id, _do_c_);
				break;
			}
			form.elements[i].checked = true ;
			change_color_exts(form.elements[i].value);
		}
	}
	
	function uncheckAll_exts(form, id, _do_c_)
	{
		for (var i=0;i<form.elements.length;i++)
		{
			if(form.elements[i].getAttribute("rel") != id)
				continue;
			form.elements[i].checked = false;
			change_color_exts(form.elements[i].value);
		}
	}
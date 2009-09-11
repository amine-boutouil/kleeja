var STYLE_PATH_ADMIN = '{STYLE_PATH_ADMIN}';

	function change_color(obj, id, c, c2)
	{
		c = (c == null) ? 'ored' : c;
		c2 = (c == null) ? 'osilver' : c2;
		var ii = document.getElementById(id);
		
		if(obj.checked)
		{
			ii.setAttribute("class", c);
			ii.setAttribute("className", c);
		}
		else
		{
			ii.setAttribute("class", c2);
			ii.setAttribute("className", c2);	
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
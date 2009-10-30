function agree ()
	{
		var agrec = document.getElementById('agrec');
		var agres = document.getElementById('agres');

		if (agrec.checked) { agres.disabled= '';}else{agres.disabled= 'disabled';}
	}

function w_email(l)
{
		var m = document.getElementById(l);
		if (m.value.indexOf("@") == -1 ||m.value.indexOf(".") == -1 || m.value.length < 7 ) 
		{
			alert("{{echo $lang['WRONG_EMAIL']}}");
			m.focus();
		}
}

//By JavaScript Kit (http://javascriptkit.com)
function checkrequired(which)
{
	var pass	=	true;
	if (document.images)
	{
		for (i=0;i<which.length;i++)
		{
			var tempobj=which.elements[i]
			if (tempobj.name.substring(0,8)=="required")
			{
				if (((tempobj.type=="text"||tempobj.type=="textarea")&&tempobj.value=='')||(tempobj.type.toString().charAt(0)=="s" && tempobj.selectedIndex==-1))
				{
					pass	=	false;
					break
				}
			}
		}
	}
	if (!pass)
	{
		alert("{{echo $lang['VALIDATING_FORM_WRONG']}}");
		return false;
	}
	else
	{
		return true;
	}
}

// http://www.dynamicdrive.com/ 
function formCheck(formobj, fieldRequired)
{
	// dialog message
	var alertMsg = "{{echo $lang['VALIDATING_FORM_WRONG']}}:\n";
	var l_Msg = alertMsg.length;
	//lang
	var lang = new Array(9);
	lang["db_server"] = "{{echo $lang['DB_SERVER']}}";
	lang["db_user"] = "{{echo $lang['DB_USER']}}";
	lang["db_name"] = "{{echo $lang['DB_NAME']}}";
	lang["sitename"] = "{{echo $lang['SITENAME']}}";
	lang["siteurl"] = "{{echo $lang['SITEURL']}}";
	lang["sitemail"] = "{{echo $lang['SITEMAIL']}}";
	lang["username"] = "{{echo $lang['USERNAME']}}";
	lang["password"] = "{{echo $lang['PASSWORD']}}";
	lang["password2"] = "{{echo $lang['PASSWORD2']}}";
	lang["email"] = "{{echo $lang['EMAIL']}}";
	
	for (var i = 0; i < fieldRequired.length; i++)
	{
		var obj = formobj.elements[fieldRequired[i]];
		if (obj)
		{
			switch(obj.type)
			{
				case "text":
				case "textarea":
					if (obj.value == "" || obj.value == null)
						alertMsg += " - " + lang[fieldRequired[i]] + "\n";
					break;
				default:
			}
			
			if (obj.type == undefined)
			{
				var blnchecked = false;
				for (var j = 0; j < obj.length; j++)
				{
					if (obj[j].checked)
						blnchecked = true;
				}
				
				if (!blnchecked)
					alertMsg += " - " + lang[fieldRequired[i]] + "\n";
			}
		}
	}

	if (alertMsg.length == l_Msg)
		return true;
	else
	{
		alert(alertMsg);
		return false;
	}
}

/*language step*/
$(document).ready(function() {
  $('#choose li').hover(function() {
    $(this).animate( { width: "256", height: "256" }, { queue: false, duration: 200 });
  },
  function() {
    $(this).animate( { width: "128", height: "128" }, { queue: false, duration: 230 });
  });
});
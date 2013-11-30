<script type="text/javascript">
function check_search_files_form(form)
{
	var do_it = false;
	if(form.filename.value != '')
		do_it = true;
	else if(form.username.value != '')
		do_it = true;
	else if(form.ext.value != '')
		do_it = true;
	else if(form.size.value != '')
		do_it = true;
	else if(form.ups.value != '')
		do_it = true;
	else if(form.rep.value != '')
		do_it = true;
	else if(form.lastdown.value != '')
		do_it = true;
	else if(form.user_ip.value != '')
		do_it = true;

	if(!do_it)
	{
		 alert('{lang.NO_SEARCH_WORD}');
		 return false;
	}
}
function check_search_users_form(form)
{
	var do_it = false;
	if(form.username.value != '')
		do_it = true;
	else if(form.username.value != '')
		do_it = true;

	if(!do_it)
	{
		 alert('{lang.NO_SEARCH_WORD}');
		 return false;
	}
}
</script>

<IF NAME="current_smt==files">
<!-- search -->
<div class="page-header">
	<h1>{lang.R_SEARCH}</h1>
</div>


<form method="post" action="{action}" onsubmit="return check_search_files_form(this);" id="adv_search" role="form">
<ul class="list-group">
<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="filename">{lang.FILENAME}</label></h4>
	<p class="list-group-item-text">
		<input type="text" name="filename" id="filename" value=""  class="form-control" placeholder="{lang.FILENAME}">
	</p>
</li>
<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="username">{lang.USERNAME}</label></h4>
	<p class="list-group-item-text">
		<input type="text" name="username" id="username" value="{filled_username}"  class="form-control" placeholder="{lang.USERNAME}">
	</p>
</li>

<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="ext">{lang.FILETYPE}</label></h4>
	<p class="list-group-item-text">
		<input type="text" name="ext" id="ext" value="" class="form-control" placeholder="{lang.FILETYPE}">
	</p>
</li>

<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="size">{lang.FILESIZE}</label></h4>
	<p class="list-group-item-text input-group">
		<span class="input-group-addon">
		<select name="than">
			<option value="1">&gt;</option>
			<option value="2">&lt;</option>
		</select>
		</span>
		<input type="text" id="size" name="size"  class="form-control" placeholder="{lang.FILESIZE}">
		<span class="input-group-addon">{lang.KILOBYTE}</span>
		
	</p>
</li>

<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="ups">{lang.FILEUPS}</label></h4>
	<p class="list-group-item-text input-group">
		<span class="input-group-addon">
		<select name="uthan">
			<option value="1">&gt;</option>
			<option value="2">&lt;</option>
		</select>
		</span>
		<input type="text" id="ups" name="ups" class="form-control" placeholder="{lang.FILEUPS}">
	</p>
</li>

<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="rep">{lang.REPORT}</label></h4>
	<p class="list-group-item-text input-group">
		<span class="input-group-addon">
		<select name="rthan">
			<option value="1">&gt;</option>
			<option value="2">&lt;</option>
		</select>
		</span>
		<input type="text" id="rep" name="rep"  class="form-control" placeholder="{lang.REPORT}"/>
	</p>
</li>

<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="lastdown">{lang.LAST_DOWN}</label></h4>
	<p class="list-group-item-text input-group">
	<span class="input-group-addon">{lang.WAS_B4}</span>
	<input type="text" name="lastdown" id="lastdown" value="" class="form-control" placeholder="{lang.LAST_DOWN}">
	<span class="input-group-addon">{lang.DAYS}</span>
	</p>
</li>

<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="user_ip">{lang.IP}</label></h4>
	<p class="list-group-item-text">
		<input type="text" name="user_ip" id="user_ip" value="{filled_ip}" size="20" class="form-control" placeholder="{lang.IP}">
	</p>
</li>

<!-- admin search data input extra -->
</ul>

<div class="clearfix"></div>


<!-- button adv -->
<p class="submit <IF NAME="{lang.DIR} == rtl">pull-left</IF>">
	<input type="hidden" name="search_file" value="1" />
	<button type="submit" id="submit" name="search_file" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#adv_search', '#content', 0);">{lang.SEARCH_SUBMIT}</button>
</p>

{H_FORM_KEYS}
</form>

<!-- search end -->

<ELSEIF NAME="current_smt==users">

<!-- start users search -->
<IF NAME="default_user_system">
<div class="page-header">
	<h1>{lang.SEARCH_USERS}</h1>
</div>


<form method="post" action="{action}" onsubmit="return check_search_users_form(this);" id="user_search" role="form">

<ul class="list-group">
<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="username">{lang.USERNAME}</label></h4>
	<p class="list-group-item-text">
		<input type="text" name="username" id="username" value="" placeholder="{lang.USERNAME}" class="form-control">
	</p>
</li>

<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="usermail">{lang.EMAIL}</label></h4>
	<p class="list-group-item-text input-group">
		<input type="text" name="usermail" id="usermail" value="" placeholder="{lang.EMAIL}" class="form-control">
	</p>
</li>
<!-- another usersearch field -->
</ul>

<!-- button user -->
<p class="submit <IF NAME="{lang.DIR} == rtl">pull-left</IF>">
	<input type="hidden" name="search_user" value="1" />
	<button type="submit" id="submit" name="search_user" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#user_search', '#content', 0);">{lang.SEARCH_SUBMIT}</button>
</p>

{H_FORM_KEYS2}
</form>

<!-- user search end -->
</IF>

</IF>



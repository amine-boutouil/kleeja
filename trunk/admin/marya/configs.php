
<!--- START EDIT STAGE -->
<!-- the big box begin -->
<div class="configs_page">
<form method="post" action="{action}" id="opt_form" role="form">
<!-- admin configs tr extra begin -->

{options}
</ul>

<!-- admin configs tr extra -->

<!-- button -->
<p class="submit <IF NAME="{lang.DIR} == rtl">pull-left</IF>">
	<input type="hidden" name="submit" value="1" />
	<button type="submit" id="submit" name="_submit_" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#opt_form', '#content', 0);"><span>{n_submit}</span></button>
</p>

{H_FORM_KEYS}
	
</form>
</div>
<!-- the big box end -->
<!--- / END EDIT STAGE -->

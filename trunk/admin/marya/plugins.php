<IF NAME="current_smt == general">
<IF NAME="there_is_cached">
<div class="note-info">
	<strong>{lang.CACHED_STYLES}!</strong>
		{there_is_cached}
</div>
<div class="br"></div>
</IF>
<div class="tit_con">
	<h1>{lang.R_PLUGINS}</h1>
	<a class="btn btn-primary" onclick="javascript:get_kleeja_link('./?cp=j_plugins&amp;smt=newplg', '#content'); return false;">{lang.ADD_NEW_PLUGIN}</a>
</div><hr>
<!-- the big box begin -->
<div class="big-box <IF NAME="no_plugins">no-plugins-bg</IF>">
<IF NAME="no_plugins">
<p class="text-danger">{lang.NO_PLUGINS}</p>
<ELSE>
<div id="plugin_boxes">
<table style="width:100%" class="table table-striped">
<tr>
<LOOP NAME="arr">
<td>
<div class="plugin_box">
	<div class="plugin_info_show plugin_info_show-{{plg_id}}"><p class="f_text lead text-primary">{lang.VIEW} : {{plg_name}} {{plg_ver}}</p></div>
	<div class="plugin_else plugin_else-{{plg_id}} row">
		<div class="plugin_img col-md-4"><img src="{{plg_icon_url}}" /></div>
		<div class="col-md-8">
			<div class="plugin_title"><p class="text-info">{{plg_name}} {{plg_ver}}</p></div>
			<div class="plugin_by"><p class="text-info">{{plg_author}}</p></div>
			<div class="plugin_info_desc"><p class="text-info">{{plg_dsc}}</p></div>
			<div class="plugin_info_settings">
				<IF LOOP="plg_disabled">
				<a onclick="javascript:$.facybox.close(); get_kleeja_link(this.href); return false;" href="{action}&amp;do_plg={{plg_id}}&amp;m=2&amp;{GET_FORM_KEY}" title="{lang.ENABLE}" class="btn btn-success"><span class="glyphicon glyphicon-play"></span> {lang.ENABLE}</a>
				<ELSE>
				<a onclick="javascript:$.facybox.close(); get_kleeja_link(this.href); return false;" href="{action}&amp;do_plg={{plg_id}}&amp;m=1&amp;pn={{plg_name}}&amp;{GET_FORM_KEY}" title="{lang.DISABLE}" class="btn btn-warning"><span class="glyphicon glyphicon-pause"></span> {lang.DISABLE}</a>
				</IF>
				<a onclick="javascript:$.facybox.close(); get_kleeja_link(this.href, '#content', {confirm:true}); return false;" href="{action}&amp;do_plg={{plg_id}}&amp;m=3&amp;pn={{plg_name}}&amp;{GET_FORM_KEY}" title="{lang.DELETE}" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> {lang.DELETE}</a>
				<IF LOOP="plg_instructions">
				<a onclick="javascript:$.facybox.close(); get_kleeja_link(this.href); return false;" href="{action}&amp;do_plg={{plg_id}}&amp;m=4&amp;{GET_FORM_KEY}" title="{lang.INFORMATION}"><img src="{STYLE_PATH_ADMIN}images/help.png" alt="{lang.INFORMATION}" /> {lang.INFORMATION}</a>
				</IF>
			</div>
		</div>
	</div>
</div>
</td>
<IF LOOP="i"></tr><tr></IF>
</LOOP>
</tr>
</table>
<div class="clear"></div>
</div>
<div class="note-info"><strong>{lang.NOTE} !</strong> {klj_d_s}</div>

</IF>

</div>
<!-- the big box end -->




<!-- note for user  -->
<!-- cached changed -->
<IF NAME="is_there_changes_files">
<div class="hr"></div>

<!-- the big box begin#changes_files -->
<div class="big-box changes_files">
<h1>{lang.PLUGINS_CHANGES_FILES} </h1>
<h2>{lang.PLUGINS_CHANGES_FILES_EXP}</h2>

<div class="br"></div>
<table style="width:100%" class="table table-striped">
	<LOOP NAME="changes_files">	
	<tr>
			<td>{{file}}</td>
			<td class="downloadchf"><a href="{{path}}">{lang.DOWNLAOD}</a></td>
	</tr>
	</LOOP>
</table>

<hr><p class="submit">
	<button type="submit" class="btn btn-primary btn-mg" onclick="javascript:get_kleeja_link('{action}&amp;cc=1'); return false;"><span>{lang.DELETE}</span></button>
</p>

<div class="br"></div>
<div class="br"></div>
<div class="clear"></div>

</div>
<!-- the big box end#changes_files -->
</IF>


<ELSEIF NAME="current_smt == newplg">
<!-- add new plugin -->
<!-- the big box begin -->
<div class="big-box">
<div class="tit_con">
	<h1><a href="{action}&amp;smt=general" onclick="javascript:get_kleeja_link(this.href); return false;">{lang.R_PLUGINS}</a> &raquo; {lang.ADD_NEW_PLUGIN}</h1>   
</div>

<div class="note-info"><strong>{lang.NOTE} !</strong> {lang.PLUGIN_CONFIRM_ADD}</div>
<div class="hr"></div>

<form method="post" action="{action}" enctype="multipart/form-data" id="add_plugin_form">

<h2>{lang.ADD_NEW_PLUGIN_EXP}</h2><hr>
<div class="section">
	<h3><label for="imp_file"></label></h3>
	<div class="box">
		<input name="imp_file" id="imp_file" type="file" class="btn btn-primary"/>
	</div>
</div><hr>
<div class="clear"></div>


<div class="br"></div>
<div class="hr"></div>
<INCLUDE NAME="admin_plugin_mfile"> 


<div class="br"></div>

<p class="submit">
	<input type="hidden" name="submit_new_plg" value="1" />
	<button type="submit" name="submit_new_plg" class="btn btn-primary btn-lg"><span>{lang.SUBMIT}</span></button>
</p>

{H_FORM_KEYS}
</form>
</div>
<!-- the big box end -->
</IF>

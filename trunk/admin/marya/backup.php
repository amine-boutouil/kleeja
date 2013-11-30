<!-- the big box begin -->
<div class="big-box">
<div class="tit_con">
	<h1>{lang.R_BCKUP}</h1>
</div>
   
<h2>{lang.E_BACKUP}</h2>


<form method="post" name="bkupfrom" action="{action}" id="bkupfrom">

<table>
	<tr class="first">
		<td style="width:20px;"><a href="javascript:void(0);" onclick="checkAll(document.bkupfrom, '_bkup', 'su',  'osilver', 'ored');">#</a></td>
		<td>{lang.NAME}</td>
		<td>{lang.SIZE}</td>
	</tr>
	<LOOP NAME="tables_sho">
	<tr id="su[{{name}}]" class="osilver">
		<td><input type="checkbox" name="check[]" value="{{name}}" checked="checked" onclick="change_color(this,'su[{{name}}]', 'osilver', 'ored');" rel="_bkup" /></td>
		<td>{{name}}</td>
		<td>{{size}} kb</td>
	</tr>        
	</LOOP>     
	<tr style="background-color:#D2D2D2;height:4px;">
		<td></td>
		<td></td>
		<td>{lang.FILESIZE}: {total_size}</td>
	</tr>  
</table>

<div class="br"></div>

<div class="left check_all check_all_table">[ <a href="javascript:void(0);" onclick="checkAll(document.bkupfrom, '_bkup', 'su');">{lang.CHECK_ALL}</a> ]</div>
	
<div class="br"></div>
	

<!-- button  -->
<p class="submit">
	<button type="submit" id="submit" name="submit" class="btn"><span>{lang.TAKE_BK}</span></button>
</p>

{H_FORM_KEYS}
</form>
</div>
<!-- the big box end -->

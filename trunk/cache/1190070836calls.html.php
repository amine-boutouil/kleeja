<script language="javascript">
function show_msg (text , id)
{
var iee = text;
	iee += "<br /><textarea name='v_" + id + "' cols='50' rows='4'></textarea>";
	iee += "<input class='button2' name='submit' type='submit' value='<?= $this->vars["n_reply"]?>' />";	
document.getElementById('mail_here').innerHTML= iee;

}
</script>
<div class="middle">
<form method="post" action="<?= $this->vars["action"]?>">

	<fieldset>
		<legend><?= $this->vars["n_text"]?></legend>
<div id="mail_here"><?= $this->vars["n_mouse"]?></div>
	</fieldset>
	
<form method="post" action="<?= $this->vars["action"]?>">
	<fieldset>
		<legend><?= $this->vars["calls_name"]?></legend>

<table style="width: 100%">
	<tr style="background-color:silver">
		<td><?= $this->vars["n_name"]?></td>
		<td><?= $this->vars["n_time"]?></td>
		<td><?= $this->vars["n_ip"]?></td>
		<td><?= $this->vars["n_del"]?></td>
	</tr>
<? $this->_limit("arr","15");foreach($this->vars["arr"] as $key=>$var){ ?>
	<tr style="background-color:#F5F5F5">
		<td><a title="<?= $this->vars["n_mail"]?>:<?= $var["mail"]?>" onclick="javascript:show_msg('<?= $var["mail"]?> :<br /><?= $var["text"]?>',<?= $var["id"]?>);"><?= $var["name"]?></a></td>
		<td><?= $var["time"]?></td>
		<td><?= $var["ip"]?></td>
		<td><input type="checkbox" name="del_<?= $var["id"]?>" value="1"></td>
	</tr>
<? } ?>

</table>

</fieldset>
<fieldset class="quick" style="text-align:center">
		<input class="button2" name="submit" type="submit" value="<?= $this->vars["n_submit"]?>" />
</fieldset>
</form>
<div style="text-align:center"><b><?= $this->vars["arr_paging"]?></b></div>
</div>




<div class="middle">
<form method="post" action="<?= $this->vars["action"]?>">
	<fieldset>
		<legend><?= $this->vars["users_name"]?></legend>

<table style="width: 100%">
	<tr style="background-color:silver">
		<td><?= $this->vars["n_name"]?></td>
		<td><?= $this->vars["n_mail"]?></td>
		<td><?= $this->vars["n_pass"]?></td>
		<td><?= $this->vars["n_admin"]?></td>
		<td><?= $this->vars["n_del"]?></td>
	</tr>
<? $this->_limit("arr","15");foreach($this->vars["arr"] as $key=>$var){ ?>
	<tr style="background-color:#F5F5F5">
		<td><label><input type="text" name="nm_<?= $var["id"]?>" value="<?= $var["name"]?>" size="20"></label></td>
		<td><label><input type="text" name="ml_<?= $var["id"]?>" value="<?= $var["mail"]?>" size="20"></label></td>
		<td><label><input type="password" name="ps_<?= $var["id"]?>" value="" size="10"></label></td>
		<td><?= $var["admin"]?></td>
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

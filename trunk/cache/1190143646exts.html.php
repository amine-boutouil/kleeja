
<div class="middle">
<form method="post" action="<?= $this->vars["action"]?>">
	<fieldset>
		<legend><?= $this->vars["exts_name"]?></legend>

<table style="width: 100%">
	<tr style="background-color:silver">
		<td><?= $this->vars["n_ext"]?></td>
		<td><?= $this->vars["n_group"]?></td>
		<td><?= $this->vars["n_gsize"]?></td>
		<td><?= $this->vars["n_gallow"]?></td>
		<td><?= $this->vars["n_usize"]?></td>
		<td><?= $this->vars["n_uallow"]?></td>
	</tr>
<? $this->_limit("arr","15");foreach($this->vars["arr"] as $key=>$var){ ?>
	<tr>
		<td><?= $var["name"]?></td>
		<td><?= $var["group"]?></td>
		<td><input name="gsz_<?= $var["id"]?>" type="text" value="<?= $var["g_size"]?>" size="7"/></td>
		<td><?= $var["g_allow"]?></td>
		<td><input name="usz_<?= $var["id"]?>" type="text" value="<?= $var["u_size"]?>" size="7"/></td>
		<td><?= $var["u_allow"]?></td>
	</tr>
<? } ?>

</table>

</fieldset>
<fieldset class="quick" style="text-align:center">
		<input class="button2" name="submit" type="submit" value="<?= $this->vars["n_submit"]?>" />
		<br />
		<?= $this->vars["n_note"]?>
</fieldset>
</form>
<div style="text-align:center"><b><?= $this->vars["arr_paging"]?></b></div>
</div>



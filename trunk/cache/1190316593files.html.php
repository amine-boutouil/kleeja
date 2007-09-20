
<div class="middle">
<form method="post" action="<?= $this->vars["action"]?>">
	<fieldset>
		<legend><?= $this->vars["files_name"]?></legend>

<table style="width: 100%">
	<tr style="background-color:silver">
		<td><?= $this->vars["n_name"]?></td>
		<td><?= $this->vars["n_user"]?></td>
		<td><?= $this->vars["n_size"]?></td>
		<td><?= $this->vars["n_uploads"]?></td>
		<td><?= $this->vars["n_time"]?></td>
		<td><?= $this->vars["n_type"]?></td>
		<td><?= $this->vars["n_folder"]?></td>
		<td><?= $this->vars["n_report"]?></td>
		<td><?= $this->vars["n_del"]?></td>
	</tr>
<? $this->_limit("arr","15");foreach($this->vars["arr"] as $key=>$var){ ?>
	<tr style="background-color:#F5F5F5">
		<td><?= $var["name"]?></td>
		<td><?= $var["user"]?></td>
		<td><?= $var["size"]?></td>
		<td><?= $var["ups"]?></td>
		<td><?= $var["time"]?></td>
		<td><?= $var["type"]?></td>
		<td><?= $var["folder"]?></td>
		<td><?= $var["report"]?></td>
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



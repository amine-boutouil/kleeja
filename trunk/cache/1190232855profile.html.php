<div class="middle">

<form action="<?= $this->vars["action"]?>" method="post">
<?= $this->vars["L_NAME"]?> : <input type="text" name="pname" value="<?= $this->vars["name"]?>" readonly="readonly" /><br />
<?= $this->vars["L_MAIL"]?> :<input type="text" name="pmail" value="<?= $this->vars["mail"]?>"  /><br />
	<fieldset>
		<legend><?= $this->vars["L_PASS"]?></legend>
<?= $this->vars["L_PASS_OLD"]?> :<input type="password" name="ppass_old" value="<?= $this->vars["_POST"]["ppass_old"]?>"  /><br />
<?= $this->vars["L_PASS_NEW"]?> :<input type="password" name="ppass_new" value="<?= $this->vars["_POST"]["ppass_new"]?>"  /><br />
<?= $this->vars["L_PASS_NEW2"]?> :<input type="password" name="ppass_new2" value="<?= $this->vars["_POST"]["ppass_new2"]?>"  /><br />
		</fieldset>
<input type="submit" name="submit" value="<?= $this->vars["n_submit"]?>" />
</form>

</div>
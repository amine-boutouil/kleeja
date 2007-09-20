<div class="middle">
<?= $this->vars["n_explain"]?>
<form action="<?= $this->vars["action"]?>" method="post">
<?= $this->vars["L_NAME"]?> : <input type="text" name="rmail" value="<?= $this->vars["_POST"]["rmail"]?>"  /><br />
<input type="submit" name="submit" value="<?= $this->vars["n_submit"]?>" />
</form>
</div>
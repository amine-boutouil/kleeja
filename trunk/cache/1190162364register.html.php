<div class="middle">

<form action="<?= $this->vars["action"]?>" method="post">
<?= $this->vars["L_NAME"]?> : <input type="text" name="lname" value="<?= $this->vars["_POST"]["lname"]?>"  /><br />
<?= $this->vars["L_PASS"]?> : <input type="password" name="lpass" value="<?= $this->vars["_POST"]["lpass"]?>"  /><br />
<?= $this->vars["L_MAIL"]?> : <input type="text" name="lmail" value="<?= $this->vars["_POST"]["lmail"]?>"  /><br />
<?= $this->vars["L_CODE"]?> <?= $this->vars["code"]?><br /><?= $this->vars["code_input"]?><br />
<input type="submit" name="submit" value="<?= $this->vars["n_submit"]?>" />
</form>

</div>

<div class="middle">
			
<h2><?= $this->vars["welcome"]?>..</h2>	
<?= $this->vars["welcome_msg"]?>
<br />
<br />
<?= $this->vars["inputs"]?>
<br />
<br />

<? foreach($this->vars["info"] as $key=>$var){ ?>
<b><?= $this->vars["info_lang"]?>:</b> <?= $var["i"]?><br />
<? } ?>

</div>
		


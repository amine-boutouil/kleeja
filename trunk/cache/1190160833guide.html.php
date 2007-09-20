<div class="middle">

<?= $this->vars["text_msg_g"]?> : 
<br />
<? foreach($this->vars["gggg"] as $key=>$var){ ?>
<?= $this->vars["L_EXT"]?> <b><?= $var["ext"]?> : </b><?= $this->vars["L_SIZE"]?> <?= $var["num"]?><br />
<? } ?>
<br />
<br/>
<?= $this->vars["text_msg_u"]?> : 
<br />
<? foreach($this->vars["uuuu"] as $key=>$var){ ?>
<?= $this->vars["L_EXT"]?> <b><?= $var["ext"]?> : </b><?= $this->vars["L_SIZE"]?> <?= $var["num"]?><br />
<? } ?>


</div>
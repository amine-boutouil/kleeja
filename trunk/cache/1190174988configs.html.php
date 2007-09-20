<div class="middle">
<form method="post" action="<?= $this->vars["action"]?>">
	<fieldset>
		<legend><?= $this->vars["configs_name"]?></legend>

<table style="width: 90%">
	<tr>
		<td><label for="sitename"><?= $this->vars["n_sitename"]?></label></td>
		<td><label><input type="text" id="sitename" name="sitename" value="<?= $this->vars["con"]["sitename"]?>" size="40"></label></td>
	</tr>
	<tr>
		<td><label for="siteurl"><?= $this->vars["n_siteurl"]?></label></td>
		<td><label><input type="text" id="siteurl" name="siteurl" value="<?= $this->vars["con"]["siteurl"]?>" size="40"></label></td>
	</tr>
	<tr>
		<td><label for="sitemail"><?= $this->vars["n_sitemail"]?></label></td>
		<td><label><input type="text" id="sitemail" name="sitemail" value="<?= $this->vars["con"]["sitemail"]?>" size="40"></label></td>
	</tr>
	<tr>
		<td><label for="foldername"><?= $this->vars["n_foldername"]?></label></td>
		<td><label><input type="text" id="foldername" name="foldername" value="<?= $this->vars["con"]["foldername"]?>" size="20"></label></td>
	</tr>
	<tr>
		<td><label for="prefixname"><?= $this->vars["n_prefixname"]?></label></td>
		<td><label><input type="text" id="prefixname" name="prefixname" value="<?= $this->vars["con"]["prefixname"]?>" size="10"></label></td>
	</tr>
	<tr>
		<td><label for="filesnum"><?= $this->vars["n_filesnum"]?></label></td>
		<td><label><input type="text" id="filesnum" name="filesnum" value="<?= $this->vars["con"]["filesnum"]?>" size="10"></label></td>
	</tr>
	<tr>
		<td><label for="siteclose"><?= $this->vars["n_siteclose"]?></label></td>
		<td>
		<label><?= $this->vars["n_yes"]?><input type="radio" id="siteclose" name="siteclose" value="1"  <? if($this->vars["yclose"]){ ?> checked="checked"<? } ?>></label>
		<label><?= $this->vars["n_no"]?><input type="radio" id="siteclose" name="siteclose" value="0"  <? if($this->vars["nclose"]){ ?> checked="checked"<? } ?>></label>
		</td>
	</tr>
	<tr>
		<td><label for="closemsg"><?= $this->vars["n_closemsg"]?></label></td>
		<td><label><input type="text" id="closemsg" name="closemsg" value="<?= $this->vars["con"]["closemsg"]?>" size="40"></label></td>
	</tr>
	<tr>
		<td><label for="decode"><?= $this->vars["n_decode"]?></label></td>
		<td><label>
		<select id="decode" name="decode">
		<option <? if($this->vars["none_decode"]){ ?>selected="selected"<? } ?> value="0"><?= $this->vars["n_none"]?></option>
		<option <? if($this->vars["md5_decode"]){ ?>selected="selected"<? } ?> value="2"><?= $this->vars["n_md5"]?></option>
		<option <? if($this->vars["time_decode"]){ ?>selected="selected"<? } ?> value="1"><?= $this->vars["n_time"]?></option>
		</select>

		</label></td>
	</tr>
	<tr>
		<td><label for="user_system"><?= $this->vars["n_user_system"]?></label></td>
		<td><label>
		<select id="user_system" name="user_system">
		<option <? if($this->vars["user_system_normal"]){ ?>selected="selected"<? } ?> value="1"><?= $this->vars["us_normal"]?></option>
		<option <? if($this->vars["user_system_phpbb"]){ ?>selected="selected"<? } ?> value="2"><?= $this->vars["us_phpbb"]?></option>
		<option <? if($this->vars["user_system_vb"]){ ?>selected="selected"<? } ?> value="3"><?= $this->vars["us_vb"]?></option>
		</select>

		</label></td>
	</tr>
	<tr>
		<td><label for="register"><?= $this->vars["n_register"]?></label></td>
		<td>
		<label><?= $this->vars["n_yes"]?><input type="radio" id="register" name="register" value="1"  <? if($this->vars["yregister"]){ ?> checked="checked"<? } ?>></label>
		<label><?= $this->vars["n_no"]?><input type="radio" id="register" name="register" value="0"  <? if($this->vars["nregister"]){ ?> checked="checked"<? } ?>></label>
		</td>	
	</tr>
	<tr>
		<td><label for="style"><?= $this->vars["n_style"]?></label></td>
		<td><label><input type="text" id="style" name="style" value="<?= $this->vars["con"]["style"]?>" size="40"></label></td>
	</tr>
	<tr>
		<td><label for="sec_down"><?= $this->vars["n_sec_down"]?></label></td>
		<td><label><input type="text" id="sec_down" name="sec_down" value="<?= $this->vars["con"]["sec_down"]?>" size="40"></label></td>
	</tr>
	<tr>
		<td><label for="statfooter"><?= $this->vars["n_statfooter"]?></label></td>
		<td>
		<label><?= $this->vars["n_yes"]?><input type="radio" id="statfooter" name="statfooter" value="1"  <? if($this->vars["ystatfooter"]){ ?> checked="checked"<? } ?>></label>
		<label><?= $this->vars["n_no"]?><input type="radio" id="statfooter" name="statfooter" value="0"  <? if($this->vars["nstatfooter"]){ ?> checked="checked"<? } ?>></label>
		</td>	
		</tr>
	</tr>
	<tr>
		<td><label for="gzip"><?= $this->vars["n_gzip"]?></label></td>
		<td>
		<label><?= $this->vars["n_yes"]?><input type="radio" id="gzip" name="gzip" value="1"  <? if($this->vars["ygzip"]){ ?> checked="checked"<? } ?>></label>
		<label><?= $this->vars["n_no"]?><input type="radio" id="gzip" name="gzip" value="0"  <? if($this->vars["ngzip"]){ ?> checked="checked"<? } ?>></label>
		</td>	
	</tr>
	<tr>
		<td><label for="welcome_msg"><?= $this->vars["n_welcome_msg"]?></label></td>
		<td><label><input type="text" id="welcome_msg" name="welcome_msg" value="<?= $this->vars["con"]["welcome_msg"]?>" size="40"></label></td>
	</tr>

</table>
</fieldset>
<fieldset class="quick" style="text-align:center">
		<input class="button2" name="submit" type="submit" value="<?= $this->vars["n_submit"]?>" />
</fieldset>
</form>
</div>

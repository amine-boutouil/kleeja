<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- login template -->
<div id="content" class="border_radius">

	<!-- msg, Infos & Alerts & Errors -->
	<?php if($ERRORS):?>
	<dl id="system-message">
		<dd class="error">
			<ul>
				<?php foreach($ERRORS as $n=>$error):?>
				<li> <strong><?=$lang['INFORMATION']?> </strong> <?=$error?></li>
				<?php endforeach;?>
			</ul>
		</dd>
	</dl>
	<?php endif;?>
	<!-- @end-msg -->

	<!-- form login  --> 
	<form action="<?=$action?>" method="post" name="login_form">
		<div class="login">
			<div class="title_login"><?=$lang['USER_LOGIN']?></div>
			<div class="box_form_login">
				<label><?=$lang['USERNAME']?> :</label>
				<input class="username" type="text" id="lname" name="lname" value="<?=$t_lname?>" size="30" tabindex="1" />
				<div class="clr"></div>
				<label><?=$lang['PASSWORD']?> :</label>
				<input class="password" type="password" name="lpass" value="<?=$t_lpass?>" size="30" tabindex="2" />
				<div class="clr"></div>
				<br />
				<label><?=$lang['REMME']?> :<input type="checkbox" name="remme" value="31536000" checked="checked" /> <p class="explain">(<?=$lang['REMME_EXP']?>)</p></label> 
					
				<div class="clr"></div><br />				
				<input type="submit" name="submit" value="<?=$lang['LOGIN']?>" tabindex="3" />

				<div class="forget_pass"><a tabindex="5" href="<?=$forget_pass_link?>"><?=$lang['LOSS_PASSWORD']?></a></div>
				
				<?=kleeja_add_form_key('login')?>

			</div>		
		</div>
	</form>
	<!-- @end-form-login -->
 
	<div class="clr"></div>

</div>
<!-- @end-login-template -->

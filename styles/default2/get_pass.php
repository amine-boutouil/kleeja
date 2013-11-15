<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- Password Recovery Template -->
<div id="content" class="border_radius">

	<!-- title -->
	<h1 class="title">&#9679; <?=$current_title?></h1>
	<!-- @end-title -->

	<!-- line top -->
	<div class="line"></div>
	<!-- @end-line -->

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

	<!-- form -->
	<form action="<?=$action?>" method="post">
		<div class="get_password">
			<p><?=$lang['E_GET_LOSTPASS']?></p>
			<div class="clr"></div>
			<label><?=$lang['EMAIL']?> :</label>
			<input type="text" name="rmail" value="<?=$t_rmail?>" size="50" style="direction:ltr;" tabindex="1" />
			<div class="clr"></div>
		</div>

		<div class="clr"></div>

		<!-- verification code -->
		<?php if($config['enable_captcha']):?>
		<div class="safe_code">
			<p><?=$lang['VERTY_CODE']?></p>
			<div class="clr"></div>
			<div>
				<img style="vertical-align:middle;" id="kleeja_img_captcha" src="<?=$captcha_file_path?>" alt="<?=$lang['REFRESH_CAPTCHA']?>" title="<?=$lang['REFRESH_CAPTCHA']?>" onclick="javascript:update_kleeja_captcha('<?=$captcha_file_path?>', 'kleeja_code_answer');" />
				<input type="text" name="kleeja_code_answer" id="kleeja_code_answer" tabindex="4" />
			</div>
			<div class="clr"></div>
			<p class="explain"><?=$lang['NOTE_CODE']?></p>
		</div>
		<?php endif;?>
		<!-- @end-verification-code -->
	
		<div class="clr"></div>

		<?=kleeja_add_form_key('get_pass');?>

		<input type="submit" name="submit" value="<?=$lang['GET_LOSTPASS']?>" tabindex="3" />

	</form>
	<!-- @end-form -->

	<div class="clr"></div>
   
</div>
<!-- @end-Password-Recovery-Template -->

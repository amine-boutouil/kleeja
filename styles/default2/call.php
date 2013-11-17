<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- Contact Us template -->
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

	<!-- form Contact Us -->
	<form action="<?=$action?>" method="post">
		<div class="call">
			<?php if(!$user->is_user()):?>
				<label><?=$lang['YOURNAME']?> :</label>
				<input type="text" name="cname" value="<?=$t_cname?>" size="30" tabindex="1" />
				<label><?=$lang['EMAIL']?> :</label>
				<input type="text" name="cmail" value="<?=$t_cmail?>" size="30" style="direction:ltr" tabindex="2" />
			<?php endif;?>
				<label><?=$lang['TEXT']?> :</label>
				<textarea name="ctext" class="text_area" rows="6" cols="40" tabindex="3"><?=$t_ctext?></textarea>
		</div>

		<div class="clr"></div>

		<!-- verification code -->
		<?php if($config['enable_captcha']):?>
		<div class="safe_code">
			<p><?=$lang['VERTY_CODE']?></p>
			<div class="clr"></div>
			<div>
				<img style="vertical-align:middle;" id="kleeja_img_captcha" src="<?=$captcha_file_path?>" alt="<?=$lang['REFRESH_CAPTCHA']?>" title="<?=$lang['REFRESH_CAPTCHA']?>" onclick="javascript:update_kleeja_captcha('<?=$captcha_file_path?>', 'kleeja_code_answer');" />
				<input type="text" name="kleeja_code_answer" id="kleeja_code_answer" tabindex="5" />
			</div>
			<div class="clr"></div>
			<p class="explain"><?=$lang['NOTE_CODE']?></p>
		</div>
		<?php endif;?>
		<!-- @end-verification-code -->

		<div class="clr"></div>

		<input type="submit" name="submit" value="<?=$lang['SEND']?>" tabindex="5" />

		<?=kleeja_add_form_key('call')?>

	</form>
	<!-- @end-form -->
	
	<div class="clr"></div>
	
</div>
<!-- @end-Contact-Us-template -->

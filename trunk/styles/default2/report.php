<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- Report template -->
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
	
	<!-- Report Forom -->
	<form action="<?=$action?>" method="post">

		<div class="rebort">
			<?php if(!$user->is_user()):?>
				<label><?=$lang['YOURNAME']?> :</label>
				<input type="text" name="rname" value="<?=$t_rname?>" size="30" tabindex="1" />
				<label><?=$lang['EMAIL']?> : </label>
				<input type="text" name="rmail" value="<?=$t_rmail?>" size="30" style="direction:ltr" tabindex="2" />
			<?php endif?>
	
			<?php if($id_d):?>
				<label><?=$lang['FILENAME']?> : </label>
				<input class="urlcolor" type="text" name="rurl" value="<?=$filename_for_show?>"  readonly="readonly" style="direction:ltr" />
			<?php else:?>
				<label><?=$lang['URL_F_FILE']?> : </label>
				<input class="urlnoncolor" type="text" name="surl" value="<?=$s_url?>" style="direction:ltr" />
			<?php endif?>
			<label><?=$lang['REASON']?> : </label>
			<textarea name="rtext" class="text_area" rows="3" cols="42" tabindex="3"><?=$t_rtext?></textarea>
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

		<input name="rid" value="<?=$id_d?>" type="hidden" />

		<input type="submit" name="submit" value="<?=$lang['REPORT']?>" tabindex="5" />
		
		<?=kleeja_add_form_key('report')?>
		
	</form>
	<!-- @end-Report-Forom -->
	
</div>
<!-- @end-Report-template -->

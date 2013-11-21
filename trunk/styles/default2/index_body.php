<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- index body template -->
<div id="content_index">

	<!-- welcome -->
	<h6>
		<img src="<?=STYLE_PATH?>images/Smile.png" width="20" height="20" style="vertical-align:middle;" />
		 <?=$lang['WELCOME']?> <?php if($user->is_user()):?>[ <?=$user->data['name']?> ]<?php endif;?> ...</h6>
	<div class="wolcome_msg"><?=$welcome_msg?></div>
	<!-- @end-welcome -->

	<div class="clr"></div>

	<!-- form upload -->
	<form id="uploader" action="<?=$config['siteurl']?>" method="post" enctype="multipart/form-data">

			<div class="go_up">
				<!-- upload normal -->
				<?php foreach($FILES_NUM_LOOP as $number=>$show):?>
				<input class="file" type="file" name="file[]" id="file_<?=$number?>_" style="<?php if(!$show):?>display:none<?php endif;?>" size="60" />
				<?php endforeach;?>
				<div class="agree"><span><?=$terms_msg?></span></div>
				<div class="bn_up"><input type="submit" id="submit_files" name="submit_files" value="<?=$lang['DOWNLOAD_F']?>" /></div>
				<div class="clr"></div>
				<!-- @upload normal -->
			</div>
		
		<!-- verification code -->
		<?php if($config['enable_captcha'] && $config['safe_code']):?>
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

	</form>
	<!-- @end-form-upload -->


	<!-- box loading -->
	<div id="loadbox">
		<div class="waitloading"><?=$lang['WAIT_LOADING']?></div>
		<div class="clr"></div><br />
			<img src="<?=STYLE_PATH?>images/loading.gif" alt="loading ..." />
		<div class="clr"></div><br /><br /><br />
	</div>
	<!-- @end-box-loading -->

	<!-- OnLine -->
	<?php if($show_online):?>
		<div class="online">
			<p class="onlineall"><?=$lang['NUMBER_ONLINE']?> : [ <?=$usersnum?> ]</p>
			<?php foreach ($online_names as $name):?>
			<p class="name_users"><?=$name?></p>
			<?php endforeach;?>
		</div>
	<?php endif;?>
	<!-- @end-OnLine -->
	

	<!-- end of index -->
	<?php ($hook = kleeja_run_hook('index_body_tpl_end_page')) ? eval($hook) : null;?>

<div class="clr"></div>
</div>

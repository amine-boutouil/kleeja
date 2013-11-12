<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- Profile template -->
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
				<li>
				<?php foreach($ERRORS as $n=>$error):?>
				<strong><?=$lang['INFORMATION']?> </strong> <?=$error?><br />
				<?php endforeach;?>
				</li>
			</ul>
		</dd>
	</dl>
	<?php endif;?>
	<!-- @end-msg -->


	<!-- profile -->
	<form action="<?=get_url_of('profile')?>" method="post">
	<div id="profile">
		<div class="boxdata">
			<h6 class="tit"><?=$lang['EDIT_U_DATA']?></h6>
			<div class="boxdata_inner">
				<label><?=$lang['USERNAME']?></label>
				<input type="text" readonly="readonly" class="bu_username" value="<?=$user->data['name']?>" />
				<label><?=$lang['SHOW_MY_FILECP']?></label>
				<select name="show_my_filecp">
					<option value="1"<?php if($user->data['show_my_filecp'] == 1):?> selected="selected"<?php endif;?>><?=$lang['YES']?></option>
					<option value="0"<?php if($user->data['show_my_filecp'] == 0):?> selected="selected"<?php endif;?>><?=$lang['NO']?></option>
				</select>
			 </div>
		</div>
				
		<div class="boxdata">
			<h6 class="tit"><?=$lang['EMAIL']?></h6>
			<div class="boxdata_inner">
				<label><?=$lang['PASSWORD']?> :</label>
				<input type="password" name="pppass_old" value="<?=$t_pppass_old?>" tabindex="1" />
				<label><?=$lang['EMAIL']?> :</label>
				<input type="text" name="pmail" value="<?=$t_pmail?>" style="direction:ltr" tabindex="2" />
			</div>
		</div>
			
		<div class="boxdata">
			<h6 class="tit"><?=$lang['PASS_ON_CHANGE']?></h6>
			<div class="boxdata_inner">
				<label><?=$lang['OLD']?> :</label>
				<input type="password" name="ppass_old" value="<?=$t_ppass_old?>" tabindex="3" />
				<label><?=$lang['NEW']?> :</label>
				<input type="password" name="ppass_new" value="<?=$t_ppass_new?>" tabindex="4" />
				<label><?=$lang['NEW_AGAIN']?> :</label>
				<input type="password" name="ppass_new2" value="<?=$t_ppass_new2?>" tabindex="5" />

			</div>
		</div>

				
		<!-- button -->
		<div class="buttons_center"><input type="submit" name="submit_data" value="<?=$lang['EDIT_U_DATA']?>" tabindex="6" /></div>
		<!-- @end-button -->		
	</div>	

	<?=kleeja_add_form_key('profile')?>

	</form>
	<!-- @end-profile -->

	
	 
	<div class="clr"></div>
</div>
<!-- @end-Profile-template -->
<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- Users Files template -->
<IF NAME="user_himself">
<script type="text/javascript">
<!--
	function confirm_from()
	{
		if(confirm('<?=$lang['ARE_YOU_SURE_DO_THIS']?>'))
			return true;
		else
			return false;
	}
	function change_color(obj,id,c,c2){c=(c==null)?'ored':c;c2=(c==null)?'osilver':c2;var ii=document.getElementById(id);if(obj.checked){ii.setAttribute("class",c);ii.setAttribute("className",c)}else{ii.setAttribute("class",c2);ii.setAttribute("className",c2)}}function checkAll(form,id,_do_c_,c,c2){for(var i=0;i<form.elements.length;i++){if(form.elements[i].getAttribute("rel")!=id)continue;if(form.elements[i].checked){uncheckAll(form,id,_do_c_,c,c2);break}form.elements[i].checked=true;change_color(form.elements[i],_do_c_+'['+form.elements[i].value+']',c,c2)}}function uncheckAll(form,id,_do_c_,c,c2){for(var i=0;i<form.elements.length;i++){if(form.elements[i].getAttribute("rel")!=id)continue;form.elements[i].checked=false;change_color(form.elements[i],_do_c_+'['+form.elements[i].value+']',c,c2)}}function change_color_exts(id){eval('var ii = document.getElementById("su['+id+']");');eval('var g_obj = document.getElementById("gal_'+id+'");');eval('var u_obj = document.getElementById("ual_'+id+'");');if(g_obj.checked&&u_obj.checked){ii.setAttribute("class",'o_all');ii.setAttribute("className",'o_all')}else if(g_obj.checked){ii.setAttribute("class",'o_g');ii.setAttribute("className",'o_g')}else if(u_obj.checked){ii.setAttribute("class",'o_u');ii.setAttribute("className",'o_u')}else{ii.setAttribute("class",'');ii.setAttribute("className",'')}}function checkAll_exts(form,id,_do_c_){for(var i=0;i<form.elements.length;i++){if(form.elements[i].getAttribute("rel")!=id)continue;if(form.elements[i].checked){uncheckAll_exts(form,id,_do_c_);break}form.elements[i].checked=true;change_color_exts(form.elements[i].value)}}function uncheckAll_exts(form,id,_do_c_){for(var i=0;i<form.elements.length;i++){if(form.elements[i].getAttribute("rel")!=id)continue;form.elements[i].checked=false;change_color_exts(form.elements[i].value)}}
//-->
</script>
</IF>

<div id="content" class="border_radius filecp-page">

		<!-- title -->
		<h1 class="title"><?php if($user_himself):?>&#9679; <?=$lang['YOUR_FILEUSER']?><?php else:?>&#9679; <?=$current_title?><?php endif;?></h1>
		<!-- @end-title -->

		<!-- line top -->
		<div class="line"></div>
		<!-- @end-line -->

		<IF NAME="user_name">
		<!-- box user name and all files  -->
		<div id="boxfileuser">
				<div class="box_user">
				<img class="right pngfix" src="<?=STYLE_PATH?>images/imguser.png" alt="image user" />
						<div class="public"><?=$lang['PUBLIC_USER_FILES']?></div>
						<div class="uname"><?=$username?></div>
				</div>
				<div class="us3r_n4me">
						<img class="right pngfix" src="<?=STYLE_PATH?>images/boxfileuser.png" alt="files number" />
						<div class="public"><?=$lang['ALL_FILES']?></div>
						<div class="nums"><?=$nums_rows?></div>
				</div>
				<div class="clr"></div>
		</div>
		<!-- @end-box-user-name-and-all-files -->
		</IF>

		<?php if($user_himself):?>
		<form name="c" action="<?=$action?>" method="post" onsubmit="javascript:return confirm_from();">
		<?php endif;?>

		<?php if($no_results):?>
		<!-- no files user -->
		<div id="boxfileuser" style="text-align:center">
			<img class="pngfix" src="<?=STYLE_PATH?>images/warning_nofile.png" />
			<br />
			<h1><?=$lang['NO_FILE_USER']?></h1>
		</div>
		<!-- @end-no-files-user -->

		<?php else:?>

		<!-- fileuser_files -->
		<div class="fileuser_files">
			<div class="fileuser-thumbs">
			<ul>
			<?php /* The loop */ $loop_number = 0;?>
			<?php while($file=$SQL->fetch($result)):?>
					
					<?php /* First row */ ?>
					<?php if($loop_number == 1):?>
						
					<?php endif;?>
					
					<?php /* Every 4 rows */ ?>
					<?php if($loop_number % 4):?>

					<?php endif;?>
					
					
					<li id="su[<?=$file['id']?>]" class="<?php if(is_image($file['type'])):?>is_image<?php else:?>is_file<?php endif;?>">
					<?php if(is_image($file['type'])):?>
						<a href="<?=kleeja_get_link('image', $file)?>" target="_blank" title="<?=$lang['FILEUPS']?>: <?=$file['uploads']?>, <?=$lang['FILESIZE']?>: <?=readable_size($file['size'])?>, <?=$lang['FILEDATE']?>: <?=kleeja_date($file['time'])?>">
							<img src="<?=kleeja_get_link('thumb', $file)?>" />
						</a>
					<?php else:?>
						<div class="filebox" style="background-image:url(images/filetypes/file.png)">
							<div class="this_file">
								<a href="<?=kleeja_get_link('file', $file)?>" target="_blank"><?=shorten_text($file['real_filename'])?></a>

								<div class="fileinfo">
								<span><?=$lang['FILEUPS']?>: <?=$file['uploads']?></span>
								<span><?=$lang['FILESIZE']?>: <?=readable_size($file['size'])?></span>
								<span><?=$lang['FILEDATE']?>: <?=kleeja_date($file['time'])?></span>
								</div>
							</div>
						</div>
					<?php endif?>
					<?php if($user_himself):?>
						<p class="kcheck">
							<input id="del_<?=$file['id']?>" name="del_<?=$file['id']?>" type="checkbox" value="<?=$file['id']?>" rel="_del"  onclick="change_color(this,'su[<?=$file['id']?>]');" />
						</p>
					<?php endif;?>
					</li>
	

					<?php /* Last Row */ ?>
					<?php if($loop_number == $perpage):?>
						
					<?php endif?>
			
			<?php $loop_number++; ?>
			<?php endwhile;?>
			</ul>
			</div>
			<div class="clr"></div>
		</div>
		 <!-- end#fileuser_files-->

		<?php endif;?>


		<div class="clr"></div><br />

		<!-- pagination -->
		<?=$page_nums?>
		<!-- @end-pagination -->

		<div class="clr"></div><br />

		<?php if($user_himself):?>
		<!-- button -->
		<div class="left_button"><input type="submit" name="submit_files" value="<?=$lang['DEL_SELECTED']?>" /></div>
		<div class="right_button">[ <a href="javascript:void(0);" onclick="checkAll(document.c, '_del', 'su');"><?=$lang['CHECK_ALL']?></a> ]</div>

		<!-- @end-button -->
		
		
		<?=kleeja_add_form_key('fileuser')?>
		</form>
		
		<!-- link user -->
		<div id="filecplink">
				<div class="clr"></div>
						<fieldset>
								<legend class="copylink"><?=$lang['COPY_AND_GET_DUD']?></legend>
								<input class="link_user" readonly="readonly" onclick="this.select();" type="text" name="option" value="<?=$your_fileuser_link?>" />
						 </fieldset>
				<div class="clr"></div>
		</div>
		<!-- @end-link-user -->
		<?php endif;?>
 
		<div class="clr"></div>
   
</div>
<!-- @end-Users-Files -->

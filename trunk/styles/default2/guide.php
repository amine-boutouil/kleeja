<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- List of allowed file template -->
<div id="content" class="border_radius">

	<!-- title -->
	<h1 class="title">&#9679; <?=$current_title?></h1>
	<!-- @end-title -->

	<!-- line top -->
	<div class="line"></div>
	<!-- @end-line -->

	<!-- table files allowed -->
	<table id="guide" border="0" cellspacing="0" cellpadding="0">	
		<?php foreach($guide_exts as $group_id=>$group_data):?>
		<tr>
			<td>
				<div class="guide_right_th"><?=$group_data['group_name']?></div>
				<!-- group list files -->
				<?php foreach($group_data['exts'] as $ext=>$size):?>
					<div class="guide_left_ext">
						<span class="guide_right_ext_lang"><?=$lang['EXT']?> : </span>
						<span class="guide_right_ext_color"><?=$ext?></span>
						&mdash;
						<span class="guide_right_ext_lang"><?=$lang['SIZE']?> : </span>
						<span class="guide_(right_ext_color"><?=readable_size($size)?></span>
					</div>
				<?php endforeach;?>
		</td>
		</tr>
		<!-- @end-group-list-files -->
		<!-- @end-clear -->
		<tr>
		<td style="width:2%">&nbsp;</td>
		</tr>
		<?php endforeach;?>
	</table>
	<!-- @end-table-files-allowed -->

	<div class="clr"></div>
   
</div>
<!-- @end-List-allowed-file-template -->

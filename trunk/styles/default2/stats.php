<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- stats template -->
<div id="content" class="border_radius">

	<!-- title -->
	<h1 class="title">&#9679; <?=$current_title?></h1>
	<!-- @end-title -->

	<!-- line top -->
	<div class="line"></div>
	<!-- @end-line -->

	<!-- box stats -->
	<div class="stats">
	<ul>
		<li><?=$lang['FILES_ST']?> : <span>[ <?=$files_st?> <?=$lang['FILE']?> <?=$lang['AND']?> <?=$imgs_st?> <?=$lang['IMAGE']?> ] </span></li>
		<?php if($config['user_system']):?>
			<li><?=$lang['USERS_ST']?> : <span>[ <?=$users_st?> <?=$lang['USER']?> ] </span></li>
			<li><?=$lang['LAST_REG']?> : <span>[ <?=$lst_reg?> ]  </span></li>
		<?php endif;?>
		<li><?=$lang['SIZES_ST']?> : <span style="color:red;">[ <?=$sizes_st?> ] </span></li>

		<?php if($config['allow_online']):?>
			<li><?=$lang['MOST_EVER_ONLINE']?> : <span>[ <?=$most_online?> ]</span> <?=$lang['ON']?> <span>[ <?=$on_muoe?> ]</span></li>
		<?php endif;?>
	</ul>
		<div class="clr"></div><br />
			<p class="st"><i><?=$lang['LAST_1_H']?></i></p>
		<div class="clr"></div><br />
	</div>
	<!-- @end-box-stats -->

</div>
<!-- @end-stats-template -->

<?php if(!defined('IN_KLEEJA')) { exit; } ?>
	<div class="clr"></div>

	<!-- Extras Footer -->
	<?php if($extras['footer']):?>
	<div class="dot"></div><div class="extras_footer"><?=$extras['footer']?></div><div class="dot"></div>
	<?php endif;?>
	<!-- @end-extras-footer -->
	
	</div><!-- @end-wrapper -->
</div><!-- @end-main -->

<!-- begin footer -->
<div class="FooterLine clr"></div>
<div id="footer">
    <div class="footer_inner">
		<div class="left">	
		<!--
			Powered by kleeja, 
			Kleeja is Free PHP software, designed to help webmasters by
			give their Users ability to upload files yo thier servers. 
			www.Kleeja.com
		 -->
        </div>
		<div class="right">
			<!-- Copyrights-->
			<div class="Copyrights">
				<?=$lang['COPYRIGHTS_X']?> &copy; <a href="<?=$config['siteurl']?>"><?=$config['sitename']?></a>
			</div><!-- @end-Copyrights -->
		</div>

		<div class="clr"></div>

		<!-- button panel -->
		<?php if(user_can('enter_acp')):?>
		<div class="bu-panel"><a href="<?=ADMIN_PATH?>" class="admin_cp_link"><span><?=$lang['ADMINCP']?></span></a></div>
		<?php endif;?>
		<!-- @end-button-panel -->

		<!-- footer stats -->
		<?php if($page_stats):?>
		<div class="footer_stats"><?=$page_stats?></div>
		<?php endif;?>
		<!-- @end-footer-stats -->

		<!-- google analytics -->
		<?php if($google_analytics):?>
		<div class="footer_stats"><?=$google_analytics?></div>
		<?php endif;?>
		<!-- @end-google-analytics -->
		
	</div>
</div>
<!-- @end-footer -->

<!-- don't ever delete this -->
<img src="<?=$config['siteurl']?>queue.php?image.gif" width="1" height="1" alt="queue" />

</body>
</html>

<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?=$lang['DIR']?>">
<head>
	<title><?=$title?> <?php echo $title ? '&#9679;' :'';?> <?=$config['sitename']?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=<?=$charset?>" />
	<meta http-equiv="Content-Language" content="<?=$lang['LANG_SMALL_NAME']?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<meta name="copyrights" content="Powered by Kleeja" />
	<!-- metatags.info/all_meta_tags -->
	<link rel="shortcut icon" href="images/favicon.ico" />
	<link rel="icon" type="image/gif" href="images/favicon.gif" />
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png" />
	<link rel="apple-touch-startup-image" href="images/iPhone.png" />
	<link rel="stylesheet" type="text/css" media="all" href="{STYLE_PATH}css/stylesheet.css" />
	<script type="text/javascript" src="<?=STYLE_PATH?>jquery.js"></script>

	<link rel="stylesheet" type="text/css" media="all" href="<?=STYLE_PATH?>css/color.css" />
	<?php if($lang['DIR']=='ltr'):?>
		<link rel="stylesheet" type="text/css" media="all" href="<?=STYLE_PATH?>css/ltr.css" />
	<?php endif;?>

	<?php if(is_browser('ie')):?>
	<link rel="stylesheet" type="text/css" media="all" href="<?=STYLE_PATH?>css/ie.css" />
	<style type="text/css">.border_radius {behavior: url('<?=STYLE_PATH?>ie/css3.htc')}</style>
	<?php endif;?>
	
	<script type="text/javascript">
	<!--
	document.write('<link rel="stylesheet" type="text/css" media="all" href="<?=STYLE_PATH?>css/css3.css" />');
	$(document).ready(function() {
		$('.up_input').prop("readonly", true);
	});
	-->
	</script>

	<script type="text/javascript">
	<!--
	var number_of_uploads={config.filesnum};
	var LANG_PAST_URL_HERE = "{lang.PAST_URL_HERE}";
	var LANG_MORE_F_FILES = "{lang.MORE_F_FILES}";
	var STYLE_PATH  = "<?=STYLE_PATH?>";
	-->
	</script>

	<script type="text/javascript" src="<?=STYLE_PATH?>javascript.js"></script>

	<!-- Extra code -->
	<?=$extra_head_code?>
</head>
<body>
<!-- begin Main -->
<div id="main">

	<!--begin Header-->
	<div id="header" class="clearfix">
		<h1><a title="?=$config['sitename']?>" href="?=$config['siteurl']?>" class="logo"><?=$config['sitename']?></a></h1>
	</div>
	<!-- @end-Header -->

	<div class="clr"></div>

	<!-- begin Top Navigation -->
	<div class="top_nav">
		<ul class="menu">
		<LOOP NAME="top_menu"><IF LOOP="show">
		<li><a href="{{url}}" (go_current=={{name}}?class="current":)>{{title}}</a></li>
		</IF></LOOP>
		</ul>
	</div><!-- @end-Top-Navigation -->

	<div class="clr"></div>

	<!-- begin wrapper -->
	<div id="wrapper" class="clearfix">

	<!-- begin extras header -->
	<?php if($extras['header']):?>
	<div class="dot"></div>
		<div class="extras_header"><?=$extras['header']?></div>
	<div class="dot"></div>
	<?php endif;?>
	<!-- @end-extras-header -->

	<div class="clr"></div>

	<!-- begin menu right -->
	<div id="sidebar">  
		<div id="sidebar_box">
		<IS_BROWSER!="mobile"> 
			<img src="{user_avatar}" alt="{username}" title="{username}" height="90" width="100" />
		</IS_BROWSER>
			<ul class="menu">
				<LOOP NAME="side_menu"><IF LOOP="show">
				<li><a href="{{url}}" (go_current=={{name}}?class="current":)><IS_BROWSER!="mobile"><img src="{STYLE_PATH}images/{{name}}.png" style="float:right;" class="pngfix" alt="{lang.LOGIN}" style="vertical-align:middle;" /></IS_BROWSER>{{title}} <IF LOOP="name==logout">[ {username} ]</IF></a></li>
				</IF></LOOP>
			</ul>
		</div>
		<div class="dot clr"></div>
	</div><!-- @end-menu-right -->

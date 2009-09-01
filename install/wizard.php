<?php
//
// kleeja wizard ...
// $Author: saanina $ , $Rev: 287 $,  $Date:: 2009-04-28 00:42:26 +0300#$
//


// Report all errors, except notices
@error_reporting(E_ALL ^ E_NOTICE);

//not installed
$_path = "../";
if (!file_exists($_path . 'config.php')) 
{
	header('Location:./index.php');
}

//to show login
$_GET['step'] = 'kleeja_is_the_best';


/*
include important files
*/
define ( 'IN_COMMON' , true);
include_once ($_path . 'config.php');
include_once ($_path . 'includes/functions.php');
include_once ($_path . 'includes/mysql.php');
include_once ('func_inst.php');


//links
$w1_link = $_path . 'admin/?cp=options';
$w2_link = $_path . 'admin/?cp=exts';
$w3_link = $_path . 'admin/?cp=styles';
$w4_link = $_path . 'admin/?cp=rules';


$right_left = $lang['DIR']=='ltr' ? 'left' : 'right';

/*
//echo header
*/
echo $header_inst;


echo '<legend>' . $lang['WZ_TITLE'] . ' :</legend>';
echo '<div style="text-align:' . $right_left . '">';
//w1
echo '<div class="wz"><a href="' . $w1_link . '" target="_blank"><img src="img/w1.png"></a>&nbsp;&nbsp;' . $lang['WZ_TXT_W1'] . '';
echo '<a href="' . $w1_link . '" target="_blank"><img src="img/action_go.gif"></a></div><br />';
//w2
echo '<div class="wz"><a href="' . $w2_link . '" target="_blank"><img src="img/w2.png"></a>&nbsp;&nbsp; ' . $lang['WZ_TXT_W2'] . '';
echo '<a href="' . $w2_link . '" target="_blank"><img src="img/action_go.gif"></a></div><br />';
//w3
echo '<div class="wz"><a href="' . $w3_link . '" target="_blank"><img src="img/w3.png"></a>&nbsp;&nbsp; ' . $lang['WZ_TXT_W3'] . '';
echo '<a href="' . $w3_link . '" target="_blank"><img src="img/action_go.gif"></a></div><br />';
//w4
echo '<div class="wz"><a href="' . $w4_link . '" target="_blank"><img src="img/w4.png"></a>&nbsp;&nbsp; ' . $lang['WZ_TXT_W4'] . '';
echo '<a href="' . $w4_link . '" target="_blank"><img src="img/action_go.gif"></a></div><br />';

echo '</div>';

echo '<fieldset class="home"><img src="img/home.gif" alt="home" />&nbsp;<a href="../index.php">' . $lang['INDEX'] . '</a><br /> ';
echo '<img src="img/adm.gif" alt="admin" />&nbsp;<a href="../admin.php">' . $lang['ADMINCP'] . '</a></fieldset>';
/*
//echo footer
*/
echo $footer_inst;

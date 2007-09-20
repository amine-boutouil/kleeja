<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
<head>
<title><?= $this->vars["title"]?> - <?= $this->vars["config"]["sitename"]?></title>
<meta http-equiv="Content-Language" content="ar" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?= $this->vars["stylepath"]?>/stylesheet.css" media="screen" />
</head>
<body>
<div id="wrap">

<div id="top"></div>

<div id="content">

<div class="header">
<h1><a href="<?= $this->vars["config"]["siteurl"]?>"><?= $this->vars["config"]["sitename"]?></a></h1>
<h2>...</h2>
</div>

<div class="breadcrumbs">
<a href="<?= $this->vars["config"]["siteurl"]?>"><?= $this->vars["config"]["sitename"]?></a> &middot; <?= $this->vars["title"]?>
</div>

<div class="right">
<h2><?= $this->vars["navigation"]?></h2>

<ul>
<li><a href="<?= $this->vars["config"]["siteurl"]?>"><?= $this->vars["index_name"]?></a></li>
<li><a href="<?= $this->vars["guide_url"]?>"><?= $this->vars["guide_name"]?></a></li>
<li><a href="<?= $this->vars["rules_url"]?>"><?= $this->vars["rules_name"]?></a></li>
<li><a href="<?= $this->vars["call_url"]?>"><?= $this->vars["call_name"]?></a></li>
<li><a href="<?= $this->vars["login_url"]?>"><?= $this->vars["login_name"]?></a></li>
<li><a href="<?= $this->vars["usrcp_url"]?>"><?= $this->vars["usrcp_name"]?></a></li>
</ul>
		
</div>


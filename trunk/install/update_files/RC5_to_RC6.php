<?php
// not for directly open
if (!defined('IN_COMMON'))	exit();

///////////////////////////////////////////////////////////////////////////////////////////////////////
// sqls /////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////





///////////////////////////////////////////////////////////////////////////////////////////////////////
//notes ////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

$update_notes[]	= $lang['INST_NOTE_RC5_TO_RC6'];



///////////////////////////////////////////////////////////////////////////////////////////////////////
//functions ////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

//index changes
$style_changes = <<<KLEEJA
<?xml version="1.0" encoding="utf-8"?>
<kleeja>
<info>
  <plugin_name>....</plugin_name>
  <plugin_version>0</plugin_version>
  <plugin_description>....</plugin_description>
  <plugin_author>....</plugin_author>
</info>

  <templates>
		<edit>
			<template name="index_body">
				<find><![CDATA[<input type="file" name="file[]" />]]></find>
				<action type="add_after">
				<![CDATA[<LOOP NAME=FILES_NUM_LOOP>
				<input type="file" name="file[{{i}}]" id="file[{{i}}]" style="display:{{show}}" />
				</LOOP>]]>
				</action>
			</template>
			<template name="index_body">
				<find><![CDATA[<input type="text" name="file[0]" size="50" value="{lang.PAST_URL_HERE}" onclick="this.value=''" style="color:silver;" dir="ltr">]]></find>
				<action type="add_after">
				<![CDATA[<LOOP NAME=FILES_NUM_LOOP>
				<input type="text" name="file[{{i}}]" id="file[{{i}}]" size="50" value="{lang.PAST_URL_HERE}" onclick="this.value=''" style="color:silver;display{{show}}" dir="ltr">
				</LOOP>]]>
				</action>
			</template>	
			<template name="index_body">
				<find><![CDATA[makeupload(1]);]]></find>
				<action type="replace_with">
				<![CDATA[ ]]>
				</action>
			</template>	
			<template name="index_body">
				<find><![CDATA[makeupload(2]);]]></find>
				<action type="replace_with">
				<![CDATA[ ]]>
				</action>
			</template>	
		</edit>

  </templates>
</kleeja>
KLEEJA;


function up_make_style()
{
	global $style_changes;
	creat_plugin_xml($style_changes);
}

$update_functions[]	=	'up_make_style()';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
	<link href="/resources/css/reset.css" rel="stylesheet" type="text/css"/>
	<link href="/resources/css/styles.css" rel="stylesheet" type="text/css"/>
	<link REL="SHORTCUT ICON" HREF="favicon.ico" />
	<link href="/resources/css/modal.css" rel="stylesheet" type="text/css"/>
	
	<script type="text/javascript" src="/resources/js/ajax/Ajax.js"></script>

	<title>Delete</title>
	
</head>

<body class="noStyle">
	<div id="logContainer" style="margin:0 auto;text-align:center;">
		<!--h1>Delete This Photo?</h1-->
		<h1>Delete <?=$photo?>?</h1>
		<input id="delete" type="image" value="" src="/resources/images/spacer.gif" onclick="DeletePhoto(<?=$photo?>);"  />
		<input id="cancel" type="image" value="" src="/resources/images/spacer.gif" onclick="parent.window.focus();parent.window.Shadowbox.close();" />
		
	</div>
</body>
</html>

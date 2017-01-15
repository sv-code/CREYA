<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FORM'); ?>
	
	<link href="/resources/css/login.css" rel="stylesheet" type="text/css"/>

	<title>CREYA | request an invite</title>

	<script type="text/javascript" src="/resources/js/artist.invite.js"></script>
	<script type="text/javascript" src="/resources/js/artist.utils.js"></script>
</head>

<body class="modal" style="background:#000;">

	<div id="RequestInviteContainer">
		
		<div class="content_left">
			
			<form id="artist_invite" class="form" action="/artist/submitrequest" target="_parent" method="post">
			
				<div id="invite_email"><div class="indicator left"></div><label class="right">email</label><div class="clear"></div>
				<input class="finput_margin text right bmargin20" name="email" type="text" value="" /><div class="clear"></div></div>
								
				<div id="invite_work"><div class="indicator left"></div><label class="right">tell us about your photographic work</label><div class="clear"></div>
				<textarea class="finput_margin right" name="work" rows="" cols=""></textarea><div class="clear"></div></div>				
								
				<input type="submit" class="submit finput_margin right button_request_invite" value="" style="margin-top:17px;" />
				
			</form>
			
		</div>
		
		<img class="right" src="/resources/images/dashboard.requestinvite.png"/>
		
	</div>
	
</body>
</html>
	
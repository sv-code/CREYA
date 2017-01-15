<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FORM'); ?>
		
	<link href="/resources/css/login.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="/resources/js/jquery.sha1.js"></script>
	<script type="text/javascript" src="/resources/js/login.js"></script>
		
	<title>CREYA | login</title>
	
</head>

<body class="modal" style="background:#000;">
	<div id="LoginContainer">
		
		<div class="logotag">
			<div class="content_left">
				<img class="logo right" src="/resources/images/logo.big.png"/>
			</div>
			<div class="content_right">
				<img class="tagline left" src="/resources/images/tagline.big.png"/>
			</div> 	
			<div class="clear"></div>		
		</div>
		
		<div class="clear"></div>
		
		<form id="artist_login" class="form" action="/login/artistlogin" target="_parent">
		
			<div class="content_left">
				<div class="clear"></div>
				<label class="right">display name</label>
				<div class="clear"></div>
				<input id="username" class="finput_margin text right dname" name="username" type="text" value="" />
				<div class="clear"></div>
				<div class="indicator right"></div>
			</div>
			
			<div class="content_right">	
				<div class="clear"></div>
				<label>password</label>
				<div class="clear"></div>
				<input id="password" class="finput_margin text" name="password" type="password" value="" />
				<div class="clear"></div>
				<span class="prompt">authenticating</span>
			</div>
			
			<div class="clear"></div>
			<div class="divider"></div>
			
			<div class="content_secondary">
				
				<input type="submit" class="submit button_login left" value="" onclick="SubmitLogin();return false;" />
				<!--a id="artist_profile" href="" target="_parent" class="left"><img src="/resources/images/button.login.png" onclick="SubmitLogin();" /></a-->
				<input id="remember_me" type="checkbox" class="checkbox"/>
				<label>remember me</label>
				<a href="#" class="forgot"><span class="lucida">forgot password</span></a>
				<div class="clear"></div>
				
			</div>
		
		</form>
		
		<!--form id="searchform" action="/login/artistlogin" method="post" target="_parent">
			
			<div class="logBox" style="margin-bottom:10px">
				<label>Username</label>
				<input id="username" name="username" type="text" value="" />
			</div>
			
			<div class="logBox">
				<label>Password</label>
				<input id="password" name="password" type="password" value="" />
			</div>
			
			<div class="remember left">
				<label>Remember Me?</label>
				<input type="checkbox" />
			</div>
	
			<input id="login" class="right" type="image" value="" src="/resources/images/spacer.gif" />
			
		</form>
		
		<div class="forgot">
			<a href="#">Forgot Your Password?</a>
		</div-->
		
	</div>
</body>
</html>

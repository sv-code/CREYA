<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FORM'); ?>
	
	<script type="text/javascript" src="/resources/js/artist.preferences.js"></script>
	<script type="text/javascript" src="/resources/js/artist.utils.js"></script>
	<script type="text/javascript" src="/resources/js/jquery.sha1.js"></script>	
	<link href="/resources/css/artist.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="/resources/js/artist.js"></script>
		
	<title>CREYA | Account Preferences</title>
			
</head>

<body>
	
	<? $data=array('navbar_dim'=>false);
	$this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<div id="main_black" class="pageminheight2">
			
			<!-- Control Bar -->
			<div id="controls_black">
				<h2 class="flashheader left">account preferences</h2>
			</div>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<!-- Content Column -->
			<div id="content_black" class="artist_preferences">
				
				<div class="form cmargin">
					
					<div id="prompt_artist_email" class="next"><span class="title  left title_email_yellow">email</span><div class="indicator right"></div><span class="right prompt"></span></div><div class="clear"></div>
					<div class="cdivider"></div>		
					<input id="artist_email" class="mandatory_input text finput_margin" type="text" value="<?=$artist_details['artist_email']?>" name="artist_email"/>
					
					<div id="prompt_artist_password" class="next"><span class="title  left title_change_password">change password</span><div class="indicator right"></div><span class="right prompt"></span></div><div class="clear"></div>
					<div class="cdivider"></div>		
					<div id="prompt_artist_current_password" class="subnext"><span class="title2 title_current_password2 lucida">current password</span></div>
					<input id="artist_current_password" class="mandatory_input text finput_margin" type="password" value="" name="artist_current_password"/>
					<input id="artist_current_password_sha1" class="text finput_margin" type="hidden" value="" name="artist_current_password_sha1"/>
					<div id="prompt_artist_new_password" class="subnext"><span class="title2 title_new_password2 lucida">new password</span></div>
					<input id="artist_new_password" class="mandatory_input text finput_margin" type="password" value="" name="artist_new_password"/>
					<div id="prompt_artist_confirm_new_password" class="subnext"><span class="title2 title_confirm_new_password2 lucida">confirm new password</span></div>
					<input id="artist_confirm_new_password" class="mandatory_input text finput_margin" type="password" value="" name="artist_confirm_new_password"/>
					<input id="artist_new_password_sha1" class="text finput_margin" type="hidden" value="" name="artist_new_password_sha1"/>
					
					<!--div id="prompt_cc_license" class="next"><span class="title  left title_cc_license_yellow">creative commons license</span><div class="indicator right"></div><span class="right prompt"></span></div><div class="clear"></div>
					<div class="cdivider"></div-->
					<!--? $form_data['cc_checked'] = $artist_details['CC_LICENSE_ID']; 
					$this->load->view('Embed.FORM.Radios.CCLicenses',$form_data); ?-->
					
				</div>
				
				<div class="content_secondary" style="margin-top:30px;height:60px;padding-top:30px;">
					<input class="submit button_save" style="margin-left:45px;" type="submit" value="" onclick="SaveArtistPreferences();return false;"/>
				</div>							
					
			</div>
			<!-- END Content Column -->
			
			
					
		</div>
		<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->
	
	
	<? $this->load->view('Embed.FOOTER'); ?>	
</body>
</html>

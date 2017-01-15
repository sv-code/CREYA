<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FORM'); ?>
	<? $this->load->view('Meta.UPLOADER'); ?>
	
	<script type="text/javascript" src="/resources/js/artist.create.js"></script>
	<script type="text/javascript" src="/resources/js/artist.utils.js"></script>
	<script type="text/javascript" src="/resources/js/jquery.sha1.js"></script>
	<link href="/resources/css/artist.css" rel="stylesheet" type="text/css"/>
	
	<title>CREYA | create an artist profile</title>
		
</head>

<body>
	
	<!--? $this->load->view('Embed.HEADER'); ?-->
	
	<div id="bg-wrapper-black" style="margin-top:50px;"></div>
	<div id="wrapper2">
		
		<div id="main_black" class="pageminheight2">
			
			<!-- Control Bar -->
			<div id="controls_black">
				<h2 class="flashheader left">Create a profile</h2>
			</div>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<!-- Content Column -->
			<div id="content_black">
					
				<form id="form_artist_profile_create" action="/artist/add_artist" method="post">
				
					<img class="dashboard right" src="/resources/images/forms.dashboard.create_profile.png"/>
				
					<div class="form left cmargin form_rounded2">
							
						<div><span class="title left title_email">email</span></div><div class="clear"></div>
						<!--div class="more_info"><label>required</label></div-->
						<div class="input_text finput_margin">
							<div class="start"></div>
								<div class="ctext"><input readonly class="mandatory_input text" id="artist_email" type="text" value="<?=$artist_email?>" name="artist_email"/></div>
							<div class="end"></div>
							<div class="clear"></div>				
						</div>							
						
						<div id="prompt_artist_password" class="next"><span class="title  left title_password">password</span><div class="indicator right"></div><span class="right prompt"></span></div>
						<!--div class="more_info"><label>required</label></div-->
						<input id="artist_password" class="mandatory_input text finput_margin" type="password" value="" name="artist_password"/>
						
						<div id="prompt_artist_password_confirm" class="next left"><span class="title left title_confirm_password">confirm password</span><div class="indicator right"></div><span class="right prompt"></span></div>
						<!--img class="password_validation" src="/resources/images/accept.png"/-->
						<!--img class="password_validation"/-->
						<!--div class="more_info"><label>required</label></div-->
						<input id="artist_password_confirm" class="mandatory_input text finput_margin" type="password" value="" name="artist_password_confirm"/>
						<input id="artist_password_sha1" class="text finput_margin" type="hidden" value="" name="artist_password_sha1"/>						
						
						<div id="prompt_artist_dname" class="next left"><span class="title left title_display_name">display name</span><div class="indicator right"></div><span class="right prompt"></span></div>
						<!--div class="more_info"><label>required</label></div-->
						<input class="mandatory_input text finput_margin" id="artist_dname" type="text" value="" name="artist_dname"/>
						
						<div id="prompt_artist_avatar" class="next"><span class="title left title_avatar">avatar</span><div class="indicator right"></div><span class="right prompt"></span></div>
						<div class="clear"></div>
						<!--div class="more_info"><label>required, select a single image from your gallery or file system</label></div-->	
						<div class="finput_margin"><? $this->load->view('Embed.CONTENT.Plugin.Single_Upload.Output'); ?></div>
						
						<div id="prompt_artist_location" class="next"><span class="title left title_location">location</span><div class="indicator right"></div><span class="right prompt"></span></div>
						<!--div class="more_info"><label>required</label></div-->
						<input id="artist_location" class="mandatory_input text finput_margin" type="text" value="" name="artist_location"/>
						
						<div id="prompt_artist_focii" class="next" style="margin-bottom:0px;"><span class="title left title_focus">focus</span><span class="helper">choose a maximum of three</span><div class="indicator right"></div></div><div class="clear"></div>
						<!--div class="more_info"><label>required</label></div-->
						<? $form_data['column_count'] = 2; 
						$this->load->view('Embed.FORM.Checkboxes.Focii',$form_data); ?>
						
						<!--div id="prompt_creative_commons" class="next" style="margin-top:25px;"><span class="title left title_cc_license">cc_license</span><span class="helper"></span><div class="indicator right"></div></div><div class="clear"></div>
						<div class="more_info"><label>required</label></div-->
						<!--? $this->load->view('Embed.FORM.Radios.CCLicenses'); ?-->
						
						<div class="clear"></div>
						
					</div>
					
					<div class="clear"></div>
					<div class="content_divider"></div>
						
					<div class="content_secondary" style="height:60px;padding-top:30px;">
						<input class="submit button_create" style="margin-left:45px;" type="submit" value="" />
					</div>
								
				</form>
					
			
			</div>
			<!-- END Content Column -->
						
					
		</div>
		<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->
	
	<? $this->load->view('Embed.FOOTER'); ?>	
	
</body>
</html>

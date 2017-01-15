<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FORM'); ?>
	<? $this->load->view('Meta.UPLOADER'); ?>
	
	<script type="text/javascript" src="/resources/js/group.create.js"></script>
	
	<link href="/resources/css/group.css" rel="stylesheet" type="text/css"/>
	
	<title>CREYA | create a group</title>

</head>

<body>
	
	<? $this->load->view('Embed.HEADER'); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<div id="main_black" class="pageminheight2">
			
			<!-- Control Bar -->
			<div id="controls_black">
				<h2 class="flashheader left">Create a group</h2>
			</div>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<!-- Content Column -->
			<div id="content_black">
					
				<form id="form_group_create" action="/group/add" method="post">
					
					<div class="form">
						
						<div id="prompt_group_name" class="first"><span class="title left title_group_name">group name</span><div class="indicator right"></div><span class="right prompt"></span></div><div class="clear"></div>
						<input id="group_name" class="mandatory_input text finput_margin interested_entities_trigger" type="text" value="" name="group_name" />
						<? $this->load->view('Embed.CONTENT.Interested_Entities'); ?>
						
						<div id="prompt_group_image" class="next"><span class="title left title_group_image">group image</span><? $data['input_gallery_internal']=false; $this->load->view('Embed.CONTENT.Plugin.Single_Upload.Input',$data); ?><div class="indicator right"></div><span class="right prompt"></span></div>
						<div class="clear"></div>	
						<? $this->load->view('Embed.CONTENT.Plugin.Single_Upload.Output'); ?>
						
						<div id="prompt_group_description" class="next"><span class="title left title_group_description">group description</span><div class="indicator right"></div><span class="right prompt"></span></div><div class="clear"></div>
						<textarea id="group_desc" class="mandatory_input finput_margin" name="group_desc" rows="" cols=""></textarea>
						
					</div>
					
					<div class="content_divider"></div>
						
					<div class="content_secondary" style="height:60px;padding-top:30px;">
						<input class="submit button_create" style="margin-left:55px;" type="submit" value="" />
					</div>
					
					
				</form>				

			</div>
			<!-- END Content Column -->
			
			<!-- Dashboard Column -->
			<!--div id="dashboard">
			
				<? $this->load->view('Embed.CONTENT.Group.Create.Interested-Groups'); ?>
				
			</div-->
			<!-- END Dashboard Column -->
					
		</div>
		<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->
	
	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>	
	
</body>
</html>

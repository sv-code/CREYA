<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FILTERS'); ?>
	<? $this->load->view('Meta.FORM'); ?>
	<? $this->load->view('Meta.UPLOADER.MULTI'); ?>
	
	<link href="/resources/css/discussion.css" rel="stylesheet" type="text/css"/>
	<!-- @todo: why community?? -->
	<script type="text/javascript" src="/resources/js/discussion.create.js"></script> 
	
	<script type="text/javascript">
		$(function() 
		{
			InitFlash('/community/upload_discussion_image');
			InitInputs();
			
			$('#CarouselContainer').hide();	
		});
	</script>	
	
	<title>CREYA | create a community post </title>
	
</head>

<body>
	
	<? $this->load->view('Embed.HEADER'); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		<div id="main_black" class="pageminheight2">
			
			<!-- Control Bar -->
			<div id="controls_black">
				<h2 class="flashheader left">Create a community post</h2>
			</div>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<!-- Content Column -->
			<div id="content_black" class="fade_preload" style="width:100%">
				<div class="preloader_container"><div class="preloader"></div></div>	
					
				<? $this->load->view('Embed.CONTENT.Discussion.Create'); ?>

			</div>
			<!-- END Content Column -->
						
				
						
			</div>
			<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->
	
	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>
	

</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	
	<script type="text/javascript" src="/resources/js/simplegallery.js"></script>
	<script type="text/javascript" src="/resources/js/group.js"></script>
		
	<link href="/resources/css/group.css" rel="stylesheet" type="text/css"/>

	<title>CREYA | "<?=$group_name?>" | info</title>
	
	<script type="text/javascript">
		$(function() 
		{
			var photo_urls = <?=json_encode($group_slideshow_photo_urls)?>;
		//	alert(img1[0]);
		
		/*	var photos = new Array();
			for (i=0;i<photo_urls.length;++i)
			{
				photos[i] = new Array();
				photos[i].push(photo_urls[i]);
				photos[i].push(" ");
				photos[i].push(" ");
			}
		
		//	alert(photos);
		*/
			
			ShowPhotoSlides
			(
				photo_urls
				/*[
					 [img1[0], "http://en.wikipedia.org", "_new"],
					 ["/images.user/photo/panorama/6.2.jpg", "http://google.com", ""],
					 ["/images.user/photo/panorama/6.19.jpg", "http://toddham.com", ""],
					 ["/images.user/photo/panorama/6.4.jpg", "http://yahoo.com", ""],
					 ["/images.user/photo/panorama/6.5.jpg", "http://google.com", ""]
			 	]*/
			);		
			
			MakeGroupDescriptionEditable();
		});
	</script>
	
</head>

<body>
	
	<? $data=array('navbar_dim'=>true);
	$this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<!-- Control TABS -->
		<? $data['control_active'] = VIEW_GROUP_CONTROL_ACTIVE_INFO ?>
		<? $this->load->view('Embed.CONTROL.TABS.Group',$data); ?>
		<!-- END Control TABS -->
		
		<div id="main_black" class="pageminheight2">
			
			<!-- Control Bar -->
			<? $data['control_active'] = VIEW_GROUP_CONTROL_ACTIVE_INFO ?>
			<? $this->load->view('Embed.CONTROL.Group',$data); ?>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<!-- Content Column -->
			<div id="content_black">
					
				<div id="groupgallery" class="slideshow"></div>
								
			</div>
			<!-- END Content Column -->
			
			<div class="content_divider"></div>
			<? $this->load->view('Embed.CONTENT.GroupInfo.Meta'); ?>							
					
		</div>
		<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->

	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>	
		
</body>
</html>

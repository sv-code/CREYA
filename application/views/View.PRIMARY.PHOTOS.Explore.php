<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.GALLERY'); ?>
		
	<title>CREYA | photographs</title>

</head>

<body>
	
	<? $data['navigation_active'] = VIEW_NAVIGATION_ACTIVE_PHOTOS ?>
	<? $this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		<div id="PhotoExploreGallery" class="background_black pageminheight fade_preload reloadgallery">
			<div class="preloader_container"><div class="preloader"></div></div>
			
			<? $this->load->view('Embed.GALLERY.COL.Photos'); ?>
			
			<!--? $this->load->view('Embed.TILEGALLERY.Photos'); ?-->	
				
		</div>
				
		<!-- END mainGallery -->
		
	</div>
	<!-- END Wrapper 2 -->
	
	<? $this->load->view('Embed.DOCK.Photo_Filters'); ?>
	
	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>		
	
</body>
</html>
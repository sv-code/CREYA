<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.GALLERY'); ?>
	
	<script type="text/javascript" src="/resources/js/group.js"></script>
	
	<link href="/resources/css/group.css" rel="stylesheet" type="text/css"/>
	
	<title>CREYA | "<?=$group_name?>" | gallery</title>
	
</head>

<body>
	
	<? $data=array('navbar_dim'=>true);
	$this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<!-- Control TABS -->
		<? $data['control_active'] = VIEW_GROUP_CONTROL_ACTIVE_GALLERY ?>
		<? $this->load->view('Embed.CONTROL.TABS.Group',$data); ?>
		<!-- END Control TABS -->
		
		<div id="GroupPhotoGallery" class="background_black pageminheight">
						
			<!-- Control Bar -->
			<? $data['control_active'] = VIEW_GROUP_CONTROL_ACTIVE_GALLERY ?>
			<? $this->load->view('Embed.CONTROL.Group',$data); ?>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<div class="reloadgallery fade_preload">
				<div class="preloader_container"><div class="preloader"></div></div>
			
				<? $data['gallery_hidden_field'] = array('id'=>'entity_id','value'=>$gid); 			
				$this->load->view('Embed.GALLERY.COL.Photos',$data); ?>	
			</div>
			
		</div>		
		<!-- END mainGallery -->
		
	</div>
	<!-- END Wrapper 2 -->
	
	<? $this->load->view('Embed.DOCK.Photo_Filters'); ?>
	
	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>	
	
</body>
</html>
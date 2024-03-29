<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FILTERS'); ?>
	
	<script type="text/javascript" src="/resources/js/shoeboxgallery.js"></script>
	
	<link href="/resources/css/gallery.css" rel="stylesheet" type="text/css"/>
	<link href="/resources/css/artist.css" rel="stylesheet" type="text/css"/>	
	<script type="text/javascript" src="/resources/js/artist.js"></script>
	
	<title>CREYA | <?=$artist_dname?>'s shoeboxes</title>
	
</head>

<body>
	
	<? $data=array('navbar_dim'=>false);
	$this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<!-- Control TABS -->
		<? $data['control_active'] = VIEW_ARTIST_CONTROL_ACTIVE_SHOEBOXES ?>
		<? $this->load->view('Embed.CONTROL.TABS.Artist',$data); ?>
		<!-- END Control TABS -->
				
		<div id="ShoeboxExploreGallery" class="background_black pageminheight">
			
			<!-- Control Bar -->
			<div id="controls_black">
				
				<div id="galleryinfo">
					<? $data['info_active'] = VIEW_ARTIST_GALLERY_INFO_ACTIVE_SHOEBOXES ?>
					<? $this->load->view('Embed.INFO.Artist.Gallery',$data); ?>
				</div>	
				<div id="userHeader"> 
					<? $data['control_active'] = VIEW_ARTIST_CONTROL_ACTIVE_GALLERY ?>
					<? $this->load->view('Embed.CONTROL.Artist',$data); ?>
				</div>
				
			</div>
			
			<div class="content_divider"></div>
			
			<div class="reloadgallery fade_preload">
				<div class="preloader_container"><div class="preloader"></div></div>
				<div id="subcontrols">
					<a href="/artist/<?=$artist_dname?>/gallery" <? if($data['info_active']==VIEW_ARTIST_GALLERY_INFO_ACTIVE_PHOTOS){?>class="active"<?}?>>
						<div class="photographs left"></div>
						<span class="subscript"> (<?=$total_photo_count?>)</span>
					</a>
					<a href="/artist/<?=$artist_dname?>/shoeboxexplore" <? if($data['info_active']==VIEW_ARTIST_GALLERY_INFO_ACTIVE_SHOEBOXES){?>class="active"<?}?>>
						<div class="shoeboxes left"></div>
						<span class="subscript"> (<?=$total_shoebox_count?>)</span>
					</a>
				</div>
				<div class="content_divider"></div>
				<!-- END Control Bar -->
				<? $filter_data = array('filter_prompt'=>'filter shoeboxes by name','url_reload'=>'/artist/'.$artist_dname.'/shoeboxexplore .reloadgallery','div_reload'=>'.reloadgallery');
				$this->load->view('Embed.GALLERY.Shoeboxes',$filter_data); ?>	
			</div>
			
		</div>		
		<!-- END mainGallery -->

	</div>
	<!-- END Wrapper 2 -->
	
	<? $this->load->view('Embed.DOCK.Section_Search',$filter_data); ?>
	
	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>
	
</body>
</html>

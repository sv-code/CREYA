<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.GALLERY'); ?>
	
	<? if($session_user!=null)
	{ 
		$this->load->view('Meta.USERPAD'); 
	}?>
	
	<link href="/resources/css/artist.css" rel="stylesheet" type="text/css"/>
	<link href="/resources/css/shoebox.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="/resources/js/artist.js"></script>
	
	<title>CREYA | <?=$artist_dname?>'s shoebox | "<?=$shoebox_details['shoebox_name']?>"</title>
	
</head>

<body>
	
	<? $data=array('navbar_dim'=>false);
	$this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<!-- Control TABS -->
		<? $this->load->view('Embed.CONTROL.TABS.Artist'); ?>
		<!-- END Control TABS -->
		
		<div id="ShoeboxPhotoGallery" class="background_black pageminheight">
			
			<!-- Control Bar -->
			<div id="controls_black">
				
				<h2 class="flashheader left">Shoebox:<?=$shoebox_details['shoebox_name']?></h2>
								
				<div class="control_links">
					<? $this->load->view('Embed.CONTROL.Artist'); ?>
				</div>
				
			</div>
			<div class="content_divider"></div>
			<div id="subcontrols">
				<ul>
					<? if($shoebox_details['photo_tag_list']!=null && $shoebox_details['photo_tag_list']!='TAG_NONE')
					{?>
						<li><span>tags:<?=$shoebox_details['photo_tag_list']?></span></li>
					<?}?>
					<? if($shoebox_details['photo_type']!=null && $shoebox_details['photo_type']!=0)
					{?>
						<li><span>genre:<?=$shoebox_details['photo_type_name']?></span></li>
					<?}?>
					<? if($shoebox_details['photo_exif_focal']!=null && $shoebox_details['photo_exif_focal']!=0)
					{?>
						<li><span>focal length:<?=$shoebox_details['photo_exif_focal']?></span></li>
					<?}?>
					<? if($shoebox_details['photo_exif_aperture']!=null && $shoebox_details['photo_exif_aperture']!=0)
					{?>
						<li><span>f-stop:<?=$shoebox_details['photo_exif_aperture']?></span></li>
					<?}?>
					<? if($shoebox_details['photo_exif_shutter']!=null && $shoebox_details['photo_exif_shutter']!=0)
					{?>
						<li><span>shutter speed:<?=$shoebox_details['photo_exif_shutter']?></span></li>
					<?}?>
					<? if($shoebox_details['photo_exif_iso']!=null && $shoebox_details['photo_exif_iso']!=0)
					{?>
						<li><span>ISO:<?=$shoebox_details['photo_exif_iso']?></span></li>
					<?}?> 
									
				</ul>
			</div>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<div class="reloadgallery fade_preload">
				<div class="preloader_container"><div class="preloader"></div></div>
			
				<? $this->load->view('Embed.GALLERY.COL.Photos'); ?>
				
			</div>
			
			
		</div>		
		<!-- END mainGallery -->
		
	</div>
	<!-- END Wrapper 2 -->
	
	<!-- $this->load->view('Embed.DOCK.Photo_Filters'); ?-->
	
	<? if($session_user!=null)
	{
		if($shoebox_details['artist_dname']!=$session_user)
		{
			/*
			$data['bookmark_entity_id'] = $disc_details['DISC_ID'];
			$data['add_bookmark_url'] = '/community/adddiscussionbookmark';
			$data['remove_bookmark_url'] = '/community/removediscussionbookmark';   
			$data['is_bookmarked'] = $is_bookmarked;
			*/
		}
		else
		{
			$data['delete_entity_id'] = $shoebox_details['SHOEBOX_ID'];
			$data['delete_function'] = 'Ajax_DeleteEntity';
			$data['delete_entity_url'] = '/artist/deleteshoebox';
			$data['delete_redirect_url'] = '/artist/'.$artist_dname.'/shoeboxexplore';
			
			$this->load->view('Embed.DOCK.Userpad',$data); 
		}
		
		
	}?>
	
	<? $this->load->view('Embed.FOOTER'); ?>	
	
</body>
</html>
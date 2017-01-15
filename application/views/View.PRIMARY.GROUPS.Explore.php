<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FILTERS'); ?>
	
	<!--script type="text/javascript" src="/resources/js/groupgallery.js"></script-->
	<link href="/resources/css/gallery.css" rel="stylesheet" type="text/css"/>
	
	<title>CREYA | groups</title>
	
</head>

<body>
	
	<? $data['navigation_active'] = VIEW_NAVIGATION_ACTIVE_GROUPS ?>
	<? $this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<div id="GroupExploreGallery" class="background_black pageminheight content_secondary">
			
			<? if($current_page==1)
			{?>			
				<div class="intro">
					<div class="info">
						<h2 class="single">groups are a great way to create a space within creya, based on a specific theme, interest or goal</h2>
					</div>
					<a class="create button_create_a_group" href="/group/create"></a>
				</div>
			<?}?>			
			
			<div class="reload_gallery">			
				<? $filter_data = array('filter_prompt'=>'filter groups by name','url_reload'=>'/groupexplore .reload_gallery','div_reload'=>'.reload_gallery');
				$this->load->view('Embed.GALLERY.Groups',$filter_data); ?>				
			</div>
					
		</div>
		
	</div>
	<!-- END Wrapper 2 -->
	
	<? $this->load->view('Embed.DOCK.Section_Search',$filter_data); ?>
		
	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>	
	
</body>
</html>

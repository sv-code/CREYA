<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
		
	<? if($session_user!=null)
	{ 
		$this->load->view('Meta.UPLOADER'); 
	}?>
	
	<? if($artist_dname==$session_user)
	{?>
		<script type="text/javascript" src="/resources/js/artist.profile.avatar.js"></script>
	<?}
	else
	{
		$this->load->view('Meta.USERPAD'); 
	}?>	
	
	<script type="text/javascript" src="/resources/js/artist.js"></script>
	
	<link href="/resources/css/artist.css" rel="stylesheet" type="text/css"/>
	
	<title>CREYA | <?=$artist_dname?>'s profile</title>
	
	<script type="text/javascript">
		$(function() 
		{	
			setBackgroundImage(<?=json_encode(cgraphics_image_artist_avatar_url(ARTIST_AVATAR_PROFILE,$artist_details['artist_avatar']))?>);
			SetOldAvatar(<?=json_encode($artist_details['artist_avatar'])?>);	
		});
	</script>		
		
</head>

<body>
	
	<? $data=array('navbar_dim'=>false);
	$this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<!-- Control TABS -->
		<? $data['control_active'] = VIEW_ARTIST_CONTROL_ACTIVE_PROFILE ?>
		<? $this->load->view('Embed.CONTROL.TABS.Artist',$data); ?>
		<!-- END Control TABS -->
		
		<div id="main_black" class="pageminheight">
			
			<!-- Control Bar -->
			<div id="controls_black">
				<h2 class="flashheader left"><?=$artist_dname?>'s profile</h2>
				<div class="control_links">
					<? $data['control_active'] = VIEW_ARTIST_CONTROL_ACTIVE_PROFILE ?>
					<? $this->load->view('Embed.CONTROL.Artist',$data); ?>
				</div>
			</div>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<!-- Content Column -->
			<div id="content_black">
				
				<? $this->load->view('Embed.CONTENT.Profile.Split')?>
				
								
					
			</div>
			<!-- END Content Column -->
			
			
					
		</div>
		<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->
	
	<? if($session_user!=null && $artist_dname!=$session_user)
	{
		$data['bookmark_entity_id'] = $artist_dname;
		$data['add_bookmark_url'] = '/artist/bookmarkartist';
		$data['remove_bookmark_url'] = '/artist/unbookmarkartist';   
		$data['is_bookmarked'] = $is_artist_bookmarked;
		$this->load->view('Embed.DOCK.Userpad',$data); 
	}?>
	
	<? $this->load->view('Embed.FOOTER'); ?>	
</body>
</html>

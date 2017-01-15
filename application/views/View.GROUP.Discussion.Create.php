<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FILTERS'); ?>
	<? $this->load->view('Meta.FORM'); ?>
	<? $this->load->view('Meta.UPLOADER.MULTI'); ?>
	
	<script type="text/javascript" src="/resources/js/group.js"></script>
	<link href="/resources/css/group.css" rel="stylesheet" type="text/css"/>
	
	<script type="text/javascript" src="/resources/js/discussion.create.js"></script>
	<link href="/resources/css/discussion.css" rel="stylesheet" type="text/css"/>
		
	<title>CREYA | "<?=$group_name?>" | create a post</title>
	
	<script type="text/javascript">
		$(function() 
		{
			InitFlash('/group/upload_group_discussion_image');
			InitInputs();			
		});
	</script>

</head>

<body>
	
	<? $data=array('navbar_dim'=>true);
	$this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
			<div id="main_black" class="pageminheight">
				
				<!-- Control Bar -->
				<? $data['control_active'] = VIEW_GROUP_CONTROL_ACTIVE_INFO ?>
				<? $this->load->view('Embed.CONTROL.Group',$data); ?>
				<div class="content_divider"></div>
				<div id="subcontrols">
					<span class="page_title yellow group">Create a group discussion</span>
				</div>
				<!-- END Control Bar -->
				
			<!-- Content Column -->
			<div id="content_black">
					
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

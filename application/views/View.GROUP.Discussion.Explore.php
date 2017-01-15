<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FILTERS'); ?>
	
	<script type="text/javascript" src="/resources/js/group.js"></script>
	
	<link href="/resources/css/group.css" rel="stylesheet" type="text/css"/>
	<link href="/resources/css/discussion.css" rel="stylesheet" type="text/css"/>
	
	<script>
	var InvokeReload = function (page)
	{
		var url = '/group/'+<?=json_encode($gid)?>+'/discussions #paginated_group_discussions';
		var div = '#paginated_group_discussions';

		Reload(url,div,page);
	};
</script>

	<title>CREYA | "<?=$group_name?>" | posts</title>
	
</head>

<body>
	
	<? $data=array('navbar_dim'=>true);
	$this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<!-- Control TABS -->
		<? $data['control_active'] = VIEW_GROUP_CONTROL_ACTIVE_POSTS ?>
		<? $this->load->view('Embed.CONTROL.TABS.Group',$data); ?>
		<!-- END Control TABS -->
		
		<div id="main_black" class="pageminheight">
			
			<!-- Control Bar -->
			<? $data['control_active'] = VIEW_GROUP_CONTROL_ACTIVE_POSTS ?>
			<? $this->load->view('Embed.CONTROL.Group',$data); ?>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<!-- Content Column -->
			<div id="content_black">
				<div id="paginated_group_discussions">
					<? $filter_data['filter_prompt'] = 'filter posts by title or type'; 
					$pagination_params = array('function_reload'=>'Reload','url_reload'=>'/group/'.$gid.'/discussions #paginated_group_discussions','div_reload'=>'#paginated_group_discussions','filter_exclude'=>$filter_data['filter_prompt']); ?>
					<? $this->load->view('Embed.PAGINATION.Static',$pagination_params); ?>		
					<? $this->load->view('Embed.CONTENT.Discussions.List'); ?>
				</div>							
			</div>
			<!-- END Content Column -->
								
						
		</div>
		<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->
	
	<? $data['filter_prompt'] = 'filter posts by title'; 
	$this->load->view('Embed.DOCK.Section_Search',$data); ?>
	
	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>	
</body>
</html>

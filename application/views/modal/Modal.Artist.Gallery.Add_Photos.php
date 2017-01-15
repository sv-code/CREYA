<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	
	<title>Remove or add photographs</title>
	
	<script type="text/javascript">
	$(document).ready(function() 
	{
		BindCarousel();
	});
	
	var ProcessAddRemoveAction = function(gid,pid)
	{
		$operation_type_element = $('#' + pid + ' .addremove span');
		var operation_type = $operation_type_element.attr('class');
		
		switch(operation_type)
		{
			case 'add':
				AddGroupPhoto(gid,pid);
				break;
				
			case 'remove':
				RemoveGroupPhoto(gid,pid);
				break;
		}
		
		$element = $('#' + pid);
		$element.fadeOut(function()
		{
			$operation_type_element.toggleClass('add');
			$operation_type_element.toggleClass('remove');
		
			$title_element = $('#' + pid + ' .title');
			$title_element.toggleClass('hidden');
		
			$element.fadeIn();
			
		});	
		
		return false;
	}
	
	</script>

</head>

<body class="modal">
	
	<div id="carouselContainer">

		<div class="header">
			<h1 class="right">Add photographs to discussion</h1>
		</div>
		
		<div class="cdivider clight clear"></div>
		<div id="subcontrols">
			
		</div>
		<div class="cdivider clight"></div>

		<? $this->load->view('Embed.CAROUSEL.Photos.Add_Remove'); ?>
		
		<div class="filter">
			<a class="relative reset"><span class="hoverhighlight"></span></a>
			<input id="filter_keywords" class="text" type="text" value="filter photographs by name or tag" name="" onkeypress="if (event.keyCode == 13) {  ReloadCarousel('',$('#filter').attr('value')); } ;"/>
		</div>
	
	</div>

</body>
</html>
	
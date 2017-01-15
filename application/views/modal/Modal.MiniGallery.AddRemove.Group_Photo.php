<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	
	<title>CREYA | remove or add photographs to this group</title>
	
	<script type="text/javascript">
	$(document).ready(function() 
	{
		BindCarousel();
		//alert('[DEBUG] Here');
		BindInputPrompt('.filter_keywords','filter photographs by name or tag');		
	});
	
	var SetFilterKeyWordCheckbox = function()
	{
		 $("input[type=radio]").each( function() 
		 {
		 	if( $(this).val() == 'filter_keywords' )
			{
				$(this).attr("checked",true);
			}
		});
	}
	
	var ProcessTagFilter = function(url)
	{
		var radioValue = $("input[@name='radio']:checked").val();
		var keyword = $('#filter_keywords').attr('value');
		if (radioValue == 'filter_keywords') {
			ReloadCarousel(url, keyword);
		}
		//ReloadCarousel(url,$('.filter_keywords').attr('value'));
	}
	
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
	
		<div class="header">
			<h1 class="modal_header right">Remove or add photographs to this group</h1>
		</div>
		
		<div class="cdivider clight clear"></div>
		<div id="subcontrols"></div>
		<div class="cdivider clight"></div>

		<div id="CarouselContainer">
		
			<ul id="minigallery" class="jcarousel-skin-tango fade_preload add_remove_photos"> 
				
				<div class="preloader_container"><div class="preloader"></div></div>
				
				<? for($i=0;$i<count($photos);$i+=2) 
				{?>
					<li>
						<a id="<?=$photos[$i]['PHOTO_ID']?>" href="#" class="left thumbnail90 hoverhighlight" target="_parent" onclick="ProcessAddRemoveAction('<?=$gid?>','<?=$photos[$i]['PHOTO_ID']?>'); return false;">
							<div class="relative addremove"><span class="<? if($photos[$i]['IsAdded']){?>remove<?}else{?>add<?}?>"></span></div>
							<div class="relative"><span class="title <? if(!$photos[$i]['IsAdded']){?>hidden<?}?>">Added</span></div>
							<img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photos[$i]['PHOTO_ID']);?>" alt="" width="90" height="90" />
						</a>
						
						<? if(array_key_exists($i+1,$photos))
						{?>
							<a id="<?=$photos[$i+1]['PHOTO_ID']?>" href="#" class="left thumbnail90 hoverhighlight" target="_parent" onclick="ProcessAddRemoveAction('<?=$gid?>','<?=$photos[$i+1]['PHOTO_ID']?>'); return false;">
								<div class="relative addremove"><span class="<? if($photos[$i+1]['IsAdded']){?>remove<?}else{?>add<?}?>"></span></div>
								<div class="relative"><span class="title <? if(!$photos[$i+1]['IsAdded']){?>hidden<?}?>">Added</span></div>
								<img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photos[$i+1]['PHOTO_ID']);?>" alt="" width="90" height="90" />
							</a>
						<?}?>	
					</li>
				<?}?>
			</ul>
			
			<div id="filters" style="position:relative;top:3px;">
				<? $reload_url = '/group/addremovephotos/'.$gid ?>				
				<div class="filter_none left">
					<input class="left radio" type="radio" value="filter_none" name="" onclick="ReloadCarousel('<?=$reload_url?>','');"/><span class="block"></span>
				</div>
				
				<div class="filter_added left">
					<input class="left radio" type="radio" value="filter_added" name="" onclick="ReloadCarousel('<?=$reload_url?>','','added');" /><span class="block"></span>
				</div>
				
				<div class="filter_keywords left">
					<!--a class="relative reset"><span class="hoverhighlight"></span></a-->
					<!--input class="left radio" type="radio" value="filter_keywords" name=""/>	<input class="filter_keywords text left" type="text" value="filter photographs by name or tag" name="" onkeypress="if (event.keyCode == 13) { if ( $("input[@name='rad']:checked").val() == 'first' ) { ReloadCarousel('<?=$reload_url?>',$(this).attr('value')); }  } ;"/-->
					<input class="left radio" type="radio" value="filter_keywords" name=""/>	<input id="filter_keywords" class="filter_keywords text left" type="text" value="filter photographs by name or tag" name="" onkeypress="if (event.keyCode == 13) { ProcessTagFilter('<?=$reload_url?>');  } else { SetFilterKeyWordCheckbox(); } ;"/>					
				</div>
				
			</div>
			
		</div>
		
		
	
	</div>

</body>
</html>
	
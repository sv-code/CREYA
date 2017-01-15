<div id="CarouselContainer">

	<ul id="minigallery" class="jcarousel-skin-tango fade_preload add_remove_photos carousel_photos"> 
				
		<div class="preloader_container"><div class="preloader"></div></div>
			
		<? for($i=0;$i<count($photos);$i+=2) 
		{?>
			<li>
				<a id="<?=$photos[$i]['PHOTO_ID']?>" href="#" class="left thumbnail90 hoverhighlight" target="_parent" onclick="ProcessAddRemoveAction('<?=$photos[$i]['PHOTO_ID']?>'); return false;">
					<div class="relative addremove"><span class="add"></span></div>
					<div class="relative"><span class="title hidden">Added</span></div>
					<img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photos[$i]['PHOTO_ID']);?>" alt="" width="90" height="90" />
				</a>
				
				<? if(array_key_exists($i+1,$photos))
				{?>
					<a id="<?=$photos[$i+1]['PHOTO_ID']?>" href="#" class="left thumbnail90 hoverhighlight" target="_parent" onclick="ProcessAddRemoveAction('<?=$photos[$i+1]['PHOTO_ID']?>'); return false;">
						<div class="relative addremove"><span class="add"></span></div>
						<div class="relative"><span class="title hidden">Added</span></div>
						<img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photos[$i+1]['PHOTO_ID']);?>" alt="" width="90" height="90" />
					</a>
				<?}?>	
			</li>
		<?}?>
	</ul>
	
	<div class="clear"></div>
	
	<div id="filters">
		<div class="filter_none left">
			<input class="left radio" type="radio" value="" name=""/><span class="block"></span>
		</div>
		
		<div class="filter_added left">
			<input class="left radio" type="radio" value="" name=""/><span class="block"></span>
		</div>
		
		<div class="filter_keywords left">
			<input class="left radio" type="radio" value="" name=""/><input class="filter_keywords text left" type="text" value="filter photographs by name or tag" name="" onkeypress="if (event.keyCode == 13) {SomeFunction($('#filter_keywords').attr('value'));};"/>
		</div>
	</div>

</div>
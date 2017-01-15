<div class="form_left">
	
	<!--form action="artist/addshoebox" method="post"-->
	
		<div class="form_rounded_start"></div>
		
		<div class="form_content">
			
			<div class="form_title"><label>shoebox name</label></div>
			<div class="more_info"><label>required</label></div>
			<input id="shoebox_name" type="text" value="" name="group_name"/>
			
			<div class="form_title next_content"><label>select tags from your photos</label></div>
			<div class="more_info" style="clear:both"><label style="margin-left:0">selecting more than one tag will create a shoebox with photos that contain all the tags</label></div>
			<div id="photo_tags_search" class="next_content"><input id="tags_search" type="text" value="" name="tags_live_search"/></div>	
			<div id="photo_tags">
				<? foreach($tags as $tag): ?>
					<div class="drag"><span><?=$tag['tag_name'].LIGHTBOX_TAG_LIST_DELIMITER?></span></div>
				<? endforeach ?>
			</div>
			
			<div class="form_title next_content"><label>shoebox tags</label></div>
			<div class="next_content" id="drop"></div>
			
		</div>
		
		<div class="form_rounded_end"></div>
		
		<div class="form_submit"><input type="image" value="" src="/resources/images/form/form.button.CREATE.png"/ onclick="AddShoebox();"></div>
	
	<!--/form-->
	
</div>


<!--div id="createForm" style="width:100%;overflow:hidden">	
					
	<div class="left" style="width:450px">
		<label>Shoebox Name</label>
		<input id="shoebox_name" type="text" value="" class="createField" style="width:400px"/>
		<input id="artist_dname" type="hidden" value="<?=$session_user?>" />
		<input id="shoebox_photo" type="hidden" value="" />

		<label>Select Tags</label>
		<div id="dragTags">
			<? foreach($tags as $tag): ?>
				<div class="drag"><span><?=$tag['tag_name'].LIGHTBOX_TAG_LIST_DELIMITER?></span></div>
			<? endforeach ?>
		</div>
		
		<input id="create" class="left" style="clear:both;" type="image" value="" src="/resources/images/spacer.gif" / onclick="AddShoebox();">
	</div>

	<div id="drop" class="left"></div>

	<div id="shoeThumb" class="thumb left" style="margin-left:20px;"><img src=<? if($shoebox_preview_pid==null){?>"/images/thumb150.png"<?}else{?><?=cgraphics_image_photo_url(PHOTO_SNAPSHOT,$shoebox_preview_pid);?><?}?> alt="" /></div>
	
	
</div-->
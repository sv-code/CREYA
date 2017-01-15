<div class="form_left">
	
	<form action="/group/discussion/add" method="post">
	
		<div class="form_rounded_start"></div>
		
		<div class="form_content">
			
			<div class="form_title"><label>discussion title</label></div>
			<div class="more_info"><label>required</label></div>
			<input id="disc_title" type="text" value="" name="disc_title"/>
			<input id="gid" type="hidden" value="<?=$gid?>" name="gid" />
			
			<div class="form_title next_content"><label>discussion body</label></div>
			<div class="more_info"><label>required</label></div>	
			<textarea name="disc_body" rows="" cols=""></textarea>
			
			<div class="form_title next_content"><label>attach an image</label></div>
			<div class="more_info"><label>optional, select a single image from your gallery or file system</label></div>	
			<div class="buttons_browse">
				<div class="button_image_choice">
					<img src="/resources/images/form/form.button.small.grey.my_gallery.png"/>
				</div>
				<div id="group_browse" class="button_image_choice">
					<!--img src="/resources/images/form/form.button.small.grey.browse.png"/-->
				</div>
			</div>
			
			<? $this->load->view('Embed.CONTENT.Plugin.Single_Upload.Output'); ?>
			
		</div>
		
		<div class="form_rounded_end"></div>
		
		<div class="form_submit"><input type="image" value="" src="/resources/images/form/form.button.CREATE.png"/></div>
	
	</form>
	
</div>


<!--div id="createForm" class="left">
		
	<label>Category</label>
	
	<div style="position:relative;margin-bottom:15px;">
		<a href="#" class="styleSelect" style="width:200px;">
			<span>Select a Category</span>
		</a>
	
		<div id="selectDD" class="selectDD" style="visibility:hidden;width:210px;">
			<? for($i=0;$i<count($categories);++$i) 
			{?>
				<a href="#" id="<?=$categories[$i]['CAT_ID']?>" <? if($i==count($categories)-1){?> class="last" <?}?>><?=$categories[$i]['cat_name']?></a>
			<?}?>
	
		</div>
	</div>

	<form action="/community/discussion/add" method="post">
	
		<label>Discussion Title</label>
		<input id="disc_title" name="disc_title" type="text" value="" class="createField"/>
		<input id="artist_dname" name="artist_dname" type="hidden" value="<?=$session_user?>" />
		<input id="cat_name" name="cat_name" type="hidden" value="" />
		
		<div class="left" style="margin-right:20px;">
			<label>Comment</label>
			<textarea class="createField" name="disc_body" rows="" cols=""></textarea>
		</div>
	
		<div class="left">
			<label style="margin-bottom:4px;">Discussion Image</label>
	
			<a class="uploadThumb left" href="#">
				<span><label>ADD IMAGE</label><img src="/resources/images/add_64.png" alt=""/></span>
			</a>
		</div>
	
		<div style="clear:left;">
			<input id="create" type="image" value="" src="/resources/images/spacer.gif"/>
		</div>
	
	</form>
	
</div-->
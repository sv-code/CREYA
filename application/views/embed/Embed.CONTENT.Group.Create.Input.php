<div class="form_left">
	
	<form action="/group/add" method="post">
	
		<div class="form">
			
			<div class="title"><span>group name</span></div>
			<div class="more_info"><label>required</label></div>
			<input id="group_name" type="text" value="" name="group_name"/>
			
			<div class="form_title next_content"><label>group description</label></div>
			<div class="more_info"><label>required</label></div>	
			<textarea name="group_desc" rows="" cols=""></textarea>
			
			<div class="form_title next_content"><label>group image</label></div>
			<div class="more_info"><label>required, select a single image from your gallery or file system</label></div>	
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
		
		<div class="form_submit"><input type="image" value="" src="/resources/images/form/form.button.CREATE.png"/></div>
	
	</form>
	
</div>


<!--div id="createForm" class="left">	

	<form action="/group/add" method="post">
	
		<label>Group Name</label>
		<input id="group_name" type="text" name="group_name" value="" class="createField"/>
		<input id="artist_dname" name="artist_dname" type="hidden" value="<?=$session_user?>" />
	
		<div class="left" style="margin-right:20px;">
			<label>Group Description</label>
			<textarea class="createField" name="group_desc" rows="" cols=""></textarea>
		</div>
	
		<div class="left">
			<label style="margin-bottom:4px;">Group Image</label>
			<a class="thumb left" href="#"><span class="add" style="display:block"></span><img src="/resources/images/thumb150.png" alt="" /></a>
		</div>
	
		<div style="clear:left;">
			<input id="create" type="image" value="" src="/resources/images/spacer.gif"/>
		</div>
	
	</form>
	
</div-->
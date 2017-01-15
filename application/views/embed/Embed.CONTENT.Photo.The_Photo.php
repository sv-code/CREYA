<div id="<?=$photo_details['PHOTO_ID']?>" class="photo the_photo_container" style="margin-top:-2px;">
	
	<div id="thePhoto" class="photo_standard">
		
		<? $this->load->view('Embed.CONTENT.Plugin.PhotoInfoContainer',$photo_details); ?>
						
		<img id="main_photo" src="<?=cgraphics_image_photo_url(PHOTO_STANDARD,$photo_details['PHOTO_ID']);?>" alt="" />
		<!--input id="photo_id" name="photo_id" type="hidden" value="<?=$photo_details['PHOTO_ID']?>" /-->
		
	</div>	
	
</div>
<? $pagination_params = array('function_reload'=>'ReloadGallery','div_reload'=>'','url_reload'=>0,'filter_exclude'=>'FILTER_NONE'); ?>
<? $this->load->view('Embed.PAGINATION.Static',$pagination_params); ?>

<div id="photogallery" class="tilegallery colgallery">	
	
	<? if(isset($gallery_hidden_field) && is_array($gallery_hidden_field) && array_key_exists('id',$gallery_hidden_field) && array_key_exists('value',$gallery_hidden_field))
	{?>
		<input id="<?=$gallery_hidden_field['id']?>" type="hidden" value="<?=$gallery_hidden_field['value']?>" />
	<?}?>
	
	<? if(count($photos)==0)
	{
		$this->load->view('Embed.Prompt.Empty'); 
	}
	else
	{	
		for($i=0;$i<VIEW_GALLERY_PHOTOS_NUM_COLUMNS;++$i) 
		{?>
			<div class="column <? if(($i+1)!=VIEW_GALLERY_PHOTOS_NUM_COLUMNS){?>rmargin40<?}?>">
			
				<? for($j=0;$j<VIEW_GALLERY_PHOTOS_NUM_ROWS;++$j) 
				{?>
					<? $index = ($j*VIEW_GALLERY_PHOTOS_NUM_COLUMNS) + ($i);
					if(isset($photos[$index]))
					{
						$photo = $photos[$index] ?>
					
						<div id="<?=$photo['PHOTO_ID']?>" class="hoverhighlight deletable thumbnail140 photo_snapshot">
							
							<? if($session_user==$photo['artist_dname'])
							{?>
								<!-- DELETE PHOTO -->
								<? $delete_data['function_delete'] =  "VerifySession(Ajax_DeleteEntity('".$photo['PHOTO_ID']."','/artist/deletephoto'));";
								$this->load->view('Embed.PLUGIN.Delete',$delete_data); ?>
							<?}
							else
							{?>
								<!-- BOOKMARK / UNBOOKMARK PHOTO -->
								<div class="chighest bookmark_container">
									<a class="<? if($session_user!=null && $photo['IsBookmarked']){?>on<?}else{?>off<?}?>"></a>
								</div>
								
								<span class="prompt_bookmark"></span>								
							<?}?>
											
							<a href="/photo/<?=$photo['PHOTO_ID']?>"><img class="chigh fade thumb" src="<?=cgraphics_image_photo_url(PHOTO_SNAPSHOT,$photo['PHOTO_ID']);?>"/></a>
						</div>
						
					<?}?>
				
				<?}?>	
						
			</div>
		
		<?}?>
		
	<?}?>	
	
	<div class="clear"></div>
			
</div>






			
			
				
																
						

		
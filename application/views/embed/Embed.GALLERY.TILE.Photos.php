<div id="photogallery" class="fade_preload tilegallery">
	<div class="preloader_container"><div class="preloader"></div></div>
	
	<? for($i=0;$i<VIEW_GALLERY_PHOTOS_NUM_COLUMNS;++$i) 
	{?>
		<div class="column <? if(($i+1)!=VIEW_GALLERY_PHOTOS_NUM_COLUMNS){?>rmargin5<?}?>">
		
			<? for($j=0;$j<VIEW_GALLERY_PHOTOS_NUM_ROWS;++$j) 
			{?>
				<? $index = ($j*VIEW_GALLERY_PHOTOS_NUM_COLUMNS) + ($i);
				if(isset($photos[$index]))
				{
					$photo = $photos[$index] ?>
				
					<div id="<?=$photo['PHOTO_ID']?>" class="hoverhighlight deletable photo_snapshot">
						
						<? if($session_user==$photo['artist_dname'])
						{?>
							<!-- DELETE PHOTO -->
							<div class="delete">
								<a class="delete_start hoverlighthighlight" href="#"></a>
								<div class="delete_confirmation">
									<a class="accept" href="#" onclick="VerifySession(DeletePhoto('<?=$photo['PHOTO_ID']?>')); return false;"></a>
									<a class="cancel" href="#"></a>
								</div>
							</div>
							
						<?}
						else
						{?>
							<!-- BOOKMARK / UNBOOKMARK PHOTO -->
							<div class="chighest bookmark_container">
								<a class="<? if($session_user!=null && $photo['IsBookmarked']){?>on<?}else{?>off<?}?>"></a>
							</div>
						<?}?>
										
						<a href="/photo/<?=$photo['PHOTO_ID']?>"><img class="chigh fade thumb" src="<?=cgraphics_image_photo_url(PHOTO_SNAPSHOT_TILE,$photo['PHOTO_ID']);?>"/></a>
					</div>
					
				<?}?>
			
			<?}?>	
					
		</div>
	
	<?}?>	
	
	<div class="clear"></div>
			
</div>

<div class="content_divider"></div>
<? $pagination_params = array('function_reload'=>'ReloadGallery','div_reload'=>'','url_reload'=>0); ?>
<? $this->load->view('Embed.PAGINATION',$pagination_params); ?>




			
			
				
																
						

		
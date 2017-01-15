<div id="cdashbox_more_photos" class="cdashbox">
	
	<span class="cdtitle more_from_this_artist"></span><div class="clear"></div>
			
	<div class="right">
	
		<? $numrows = ceil(count($more_photos)/4); $NUM_PHOTOS_PER_ROW=4;
		for($i=0;$i<$numrows;++$i)
		{?>
			<div class="thumbnail_row"?>
			<? for($j=0;$j<$NUM_PHOTOS_PER_ROW;++$j)
			{
				$photo=$more_photos[$j+$i*$NUM_PHOTOS_PER_ROW]; 
				if(isset($photo))
				{?>
					<a class="thumb dashThumb" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_THUMBNAIL,$photo['PHOTO_ID']);?>" alt="" /></a>
				<?}?>
			<?}?>
			</div>
		<?}?>
		
			
		
	
	</div>

	<div class="clear"></div>
	
	<div class="divider"></div>	
	
</div>
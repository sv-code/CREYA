<div class="dashBoxPhoto">
	<a href="#" class="boxClose boxOpen" id="link4">MORE PHOTOS BY USER</a> 
	<div class="box-link4" style="display:block;">
		
		<? foreach($more_photos as $photo): ?>
			<a class="thumb dashThumb" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_THUMBNAIL,$photo['PHOTO_ID']);?>" alt="" /></a>
		<? endforeach ?>
		
	</div>
</div>
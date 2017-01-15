

<div id="shoePrev">
	<div id="ShoeboxPreview">
		<? if(isset($shoebox_preview_photos)) 
		{?>
			<? foreach($shoebox_preview_photos as $photo): ?>
				<a id="<?=$photo['PHOTO_ID']?>" href="/photo/<?=$photo['PHOTO_ID']?>" class="thumb left"><img src="<?=cgraphics_image_photo_url(PHOTO_THUMBNAIL,$photo['PHOTO_ID']);?>" alt="" /></a>
			<? endforeach ?>
		<?}?>
	</div>
</div>

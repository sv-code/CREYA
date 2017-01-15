<div class="contentHead">
	<label class="contentLabel">Recently Added to This Group</label>
	<a href="/group/<?=$gid?>/gallery" class="viewMore">View More</a>
</div>
<div class="thumbRow">
	<? foreach($group_recently_added_photos as $photo): ?>
		<a class="thumb left" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photo['PHOTO_ID']);?>" alt="" /></a>
	<? endforeach ?>
</div>
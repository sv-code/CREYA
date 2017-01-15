<div class="contentHead">
	<label class="contentLabel">Top 5 Rated Group Photos</label>
	<a href="/group/<?=$gid?>/gallery/top-rated" class="viewMore">View More</a>
</div>
<div class="thumbRow">
	<? foreach($group_top_rated_photos as $photo): ?>
		<a class="thumb left" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photo['PHOTO_ID']);?>" alt="" /></a>
	<? endforeach ?>
</div>
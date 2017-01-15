<div class="contentHead">
	<label class="contentLabel">Top 5 Rated Photos</label>
</div>
<div class="thumbRow">
	<? foreach($artist_top_rated_photos as $photo): ?>
		<a class="thumb left" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photo['PHOTO_ID']);?>" alt="" /></a>
	<? endforeach ?>
</div>
<div class="contentHead">
	<label class="contentLabel">Recently Bookmarked</label>
</div>
<div class="thumbRow">
	<? foreach($artist_recently_bookmarked as $photo): ?>
		<a class="thumb left" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photo['PHOTO_ID']);?>" alt="" /></a>
	<? endforeach ?>
</div>
<div class="contentHead">
	<label class="contentLabel">Recently Commented On</label>
</div>
<div class="thumbRow">
	<? foreach($artist_recently_commented as $photo): ?>
		<a class="thumb left"><img src="/resources/images/thumb90.png" alt="" /></a>
	<? endforeach ?>
</div>
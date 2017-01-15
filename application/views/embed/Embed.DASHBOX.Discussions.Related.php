<div class="dashBox related_discussions">
	<h3>Related Discussions</h3>
	<? foreach($discs_related as $disc): ?>	
		<div>
			<a href="/community/discussion/<?=$disc['DISC_ID']?>"><?=$disc['disc_title']?></a>
			<p><?=$disc['disc_comment_count']?> Comments</p>
		</div>
	<? endforeach ?>
</div>
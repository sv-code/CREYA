<div class="dashBox" <? if(isset($style)){?> style="margin-top:20px;" <?}?>>
	<h3>Popular Discussions</h3>
	<? foreach($disc_popular_array as $disc): ?>
	<div>
		<a href="#"><?=$disc['disc_title']?></a>
		<p><?=$disc['disc_comment_count']?> Comments</p>
	</div>
	<? endforeach ?>
</div>
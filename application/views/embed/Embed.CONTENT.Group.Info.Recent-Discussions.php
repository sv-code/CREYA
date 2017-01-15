<div class="contentHead">
	<label class="contentLabel">Recent Discussions</label>
	<a href="/group/<?=$gid?>/dicsussions" class="viewMore">View More</a>
</div>

<? foreach($group_recent_discussions as $disc): ?>
	<div class="group_discussion">
		<div class="discussion_head">
			<h3><?=$disc['disc_title']?> <span> started by <a href="#" class="bold"><?=$disc['artist_dname']?></a></span></h3>
			<a href="#">Comments <span class="bold"><?=$disc['disc_comment_count']?></span></a>
		</div>
		<div class="discussion_body">
			<a class="thumb left"><img src="/resources/images/user40.png" alt="" /></a>
			<p><?=$disc['disc_body']?></p>
		</div>
	</div>
<? endforeach ?>

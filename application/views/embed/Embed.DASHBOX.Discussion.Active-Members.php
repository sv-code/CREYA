<div class="dashBox newMembers" >
	<h3>Active Members</h3>
	<div id="active_members">
		<? foreach($active_members as $artist): ?>	
			<div>
				<a class="thumb left"><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_THUMBNAIL_SMALL,$artist['artist_avatar']);?>" alt="" /></a>
				<div class="thumbInfo">
					<a class="name" href="/artist/<?=$artist['artist_dname']?>"><?=$artist['artist_dname']?></a>
					<p><?=$artist['comment_count']?> comment<? if($artist['comment_count'] > 1){?>s<?}?></p>
				</div>
			</div>
		<? endforeach ?>
	</div>
</div>
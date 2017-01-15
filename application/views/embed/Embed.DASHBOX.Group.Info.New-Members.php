<div class="dashBox newMembers">
	<h3>New Members</h3>
	
	<? foreach($group_new_members as $artist): ?>
	<div>
		<a class="thumb left"><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_THUMBNAIL_SMALL,$artist['artist_avatar']);?>" alt="" /></a>
		<div class="thumbInfo">
			<a href="/artist/<?=$artist['artist_dname']?>" class="name"><?=$artist['artist_dname']?></a>
			<p>Member since <?=$artist['artist_g_join_date']?></p>
		</div>
	</div>
	<? endforeach ?>

</div>
<div class="artist left">
	<? if($is_artist_bookmarked) 
	{?>
		<a id="<?=$artist_details['artist_dname']?>" href="#" class="pic left"><span class="bmarkOn"></span><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_STANDARD,$artist_details['artist_avatar']);?>" alt=""></a>
	<?}
	else
	{?>
		<a id="<?=$artist_details['artist_dname']?>" href="#" class="pic left"><span class="bmarkOff"></span><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_STANDARD,$artist_details['artist_avatar']);?>" alt=""></a>
	<?}?>
</div>

<div class="artistInfo" class="left">
	<ul class="bold">
		<li><label>Location:</label><span id="artist_location" class="editable"><?=$artist_details['artist_location']?></span></li>
		<li><label>Focus:</label><span id="artist_focus" class="editable"><?=$artist_details['artist_focus']?></span></li>
		<li class="website">
			<label>Website:</label>
				<input type="text" value="" style="display:none" />
				<a href="#" class="blue"><?=$artist_details['artist_website']?></a>
				<img src="/resources/images/pencil.png" alt="edit" style="" />
		</li>
	</ul>
	<blockquote class="small"><p class="edit_area"><?=$artist_details['artist_about_me']?></p></blockquote>
</div>

